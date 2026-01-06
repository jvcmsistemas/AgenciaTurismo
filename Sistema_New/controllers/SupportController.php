<?php
// Sistema_New/controllers/SupportController.php

require_once BASE_PATH . '/models/Ticket.php';

class SupportController
{
    private $pdo;
    private $ticketModel;

    public function __construct($pdo)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }
        $this->pdo = $pdo;
        $this->ticketModel = new Ticket($pdo);
    }

    public function index()
    {
        // Dashboard principal
        $filter = $_GET['status'] ?? '';
        $tickets = $this->ticketModel->getAll(['estado' => $filter]);
        $faqs = $this->ticketModel->getFaqs();

        require_once BASE_PATH . '/views/admin/support/index.php';
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $ticket = $this->ticketModel->getById($id);

        if (!$ticket) {
            redirect('admin/support'); // O 404
        }

        $messages = $this->ticketModel->getMessages($id);

        require_once BASE_PATH . '/views/admin/support/show.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Nota: En un sistema real, el usuario_id vendría de la sesión y agencia_id también.
            // Para Superadmin, podría crear tickets para agencias (opcional).
            // Asumimos que quien crea es un usuario logueado.

            // VALIDAR: agencia_id si no es superadmin
            // Esto es un simplificado para Superadmin View por ahora
            // TODO: Implementar creación real desde Agencia Panel

            $data = [
                'agencia_id' => $_POST['agencia_id'] ?? 1, // Fallback demo
                'usuario_id' => $_SESSION['user_id'],
                'asunto' => $_POST['subject'],
                'prioridad' => $_POST['priority'],
                'categoria' => $_POST['category']
            ];

            $newId = $this->ticketModel->createTicket($data);

            // Agregar mensaje inicial
            $this->ticketModel->addMessage($newId, $_SESSION['user_id'], $_POST['message']);

            redirect('admin/support');
        }
    }

    public function reply()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketId = $_POST['ticket_id'];
            $message = $_POST['message'];
            $newStatus = $_POST['status'] ?? null;

            if (!empty($message)) {
                $this->ticketModel->addMessage($ticketId, $_SESSION['user_id'], $message);
            }

            if ($newStatus) {
                $this->ticketModel->updateStatus($ticketId, $newStatus);
            } else {
                // Logica por defecto si no se especifica status
                if ($_SESSION['user_role'] === 'administrador_general') {
                    $this->ticketModel->updateStatus($ticketId, 'esperando_cliente');
                } else {
                    $this->ticketModel->updateStatus($ticketId, 'abierto');
                }
            }

            redirect("admin/support/show?id=$ticketId");
        }
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ticket_id'];
            $status = $_POST['status'];

            $this->ticketModel->updateStatus($id, $status);

            if ($_SESSION['user_role'] === 'administrador_general') {
                redirect("admin/support/show?id=$id");
            } else {
                redirect("agency/support");
            }
        }
    }


    public function agencyIndex()
    {
        $agencyId = $_SESSION['agencia_id'];

        // Obtener mis tickets
        $tickets = $this->ticketModel->getAll(['agencia_id' => $agencyId]);

        // Obtener FAQs
        $faqs = $this->ticketModel->getFaqs();

        require_once BASE_PATH . '/views/agency/support/index.php';
    }

    public function storeTicket()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'agencia_id' => $_SESSION['agencia_id'],
                'usuario_id' => $_SESSION['user_id'],
                'asunto' => $_POST['subject'],
                'prioridad' => $_POST['priority'] ?? 'media',
                'categoria' => $_POST['category'] ?? 'tecnico'
            ];

            $newId = $this->ticketModel->createTicket($data);

            // Agregar mensaje inicial
            $this->ticketModel->addMessage($newId, $_SESSION['user_id'], $_POST['message']);

            redirect('agency/support?success=1');
        }
    }

    public function close()
    {
        $id = $_GET['id'];
        // Verificar que el ticket pertenezca a la agencia si no es admin
        if ($_SESSION['user_role'] !== 'administrador_general') {
            $ticket = $this->ticketModel->getById($id);
            if ($ticket['agencia_id'] != $_SESSION['agencia_id']) {
                redirect('agency/support');
            }
        }

        $this->ticketModel->updateStatus($id, 'cerrado');

        if ($_SESSION['user_role'] === 'administrador_general') {
            redirect("admin/support/show?id=$id");
        } else {
            redirect("agency/support");
        }
    }

    // --- GESTIÓN DE FAQS ---

    public function faqsIndex()
    {
        $faqs = $this->ticketModel->getFaqs(true); // true para traer todos (incluidos ocultos)
        require_once BASE_PATH . '/views/admin/support/faqs/index.php';
    }

    public function createFaq()
    {
        require_once BASE_PATH . '/views/admin/support/faqs/create.php';
    }

    public function storeFaq()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'pregunta' => $_POST['pregunta'],
                'respuesta' => $_POST['respuesta'],
                'categoria' => $_POST['categoria'],
                'orden' => $_POST['orden'] ?? 0,
                'visible' => isset($_POST['visible']) ? 1 : 0
            ];
            $this->ticketModel->createFaq($data);
            redirect('admin/support/faqs');
        }
    }

    public function editFaq()
    {
        $id = $_GET['id'] ?? 0;
        $faq = $this->ticketModel->getFaqById($id);
        if (!$faq)
            redirect('admin/support/faqs');

        require_once BASE_PATH . '/views/admin/support/faqs/edit.php';
    }

    public function updateFaq()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $data = [
                'pregunta' => $_POST['pregunta'],
                'respuesta' => $_POST['respuesta'],
                'categoria' => $_POST['categoria'],
                'orden' => $_POST['orden'] ?? 0,
                'visible' => isset($_POST['visible']) ? 1 : 0
            ];
            $this->ticketModel->updateFaq($id, $data);
            redirect('admin/support/faqs');
        }
    }

    public function deleteFaq()
    {
        $id = $_GET['id'] ?? 0;
        $this->ticketModel->deleteFaq($id);
        redirect('admin/support/faqs');
    }
}

