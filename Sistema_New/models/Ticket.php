<?php
// Sistema_New/models/Ticket.php

class Ticket
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --- TICKETS ---

    public function getAll($filters = [])
    {
        $sql = "SELECT t.*, a.nombre as agencia, u.nombre as usuario 
                FROM tickets t
                JOIN agencias a ON t.agencia_id = a.id
                JOIN usuarios u ON t.usuario_id = u.id
                WHERE 1=1";

        $params = [];
        if (!empty($filters['estado'])) {
            $sql .= " AND t.estado = :estado";
            $params['estado'] = $filters['estado'];
        }

        if (!empty($filters['agencia_id'])) {
            $sql .= " AND t.agencia_id = :agencia_id";
            $params['agencia_id'] = $filters['agencia_id'];
        }

        $sql .= " ORDER BY t.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT t.*, a.nombre as agencia, u.nombre as usuario 
                FROM tickets t
                JOIN agencias a ON t.agencia_id = a.id
                JOIN usuarios u ON t.usuario_id = u.id
                WHERE t.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createTicket($data)
    {
        // $data: agencia_id, usuario_id, asunto, prioridad, categoria
        $sql = "INSERT INTO tickets (agencia_id, usuario_id, asunto, prioridad, categoria, estado) 
                VALUES (:aid, :uid, :asunto, :prioridad, :cat, 'abierto')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'aid' => $data['agencia_id'],
            'uid' => $data['usuario_id'],
            'asunto' => $data['asunto'],
            'prioridad' => $data['prioridad'] ?? 'media',
            'cat' => $data['categoria'] ?? 'tecnico'
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE tickets SET estado = :est WHERE id = :id");
        return $stmt->execute(['est' => $status, 'id' => $id]);
    }

    // --- MENSAJES ---

    public function getMessages($ticketId)
    {
        $sql = "SELECT tm.*, u.nombre as sender_name, u.rol as sender_role 
                FROM ticket_mensajes tm
                JOIN usuarios u ON tm.usuario_id = u.id
                WHERE tm.ticket_id = :tid 
                ORDER BY tm.created_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tid' => $ticketId]);
        return $stmt->fetchAll();
    }

    public function addMessage($ticketId, $userId, $message, $image = null)
    {
        $sql = "INSERT INTO ticket_mensajes (ticket_id, usuario_id, mensaje, adjunto_url) 
                VALUES (:tid, :uid, :msg, :img)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'tid' => $ticketId,
            'uid' => $userId,
            'msg' => $message,
            'img' => $image
        ]);
    }

    // --- FAQS ---

    public function getFaqById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM faqs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getFaqs($all = false)
    {
        $sql = "SELECT * FROM faqs";
        if (!$all) {
            $sql .= " WHERE visible = 1";
        }
        $sql .= " ORDER BY orden ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function createFaq($data)
    {
        $sql = "INSERT INTO faqs (pregunta, respuesta, categoria, orden, visible) 
                VALUES (:p, :r, :c, :o, :v)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'p' => $data['pregunta'],
            'r' => $data['respuesta'],
            'c' => $data['categoria'] ?? 'General',
            'o' => $data['orden'] ?? 0,
            'v' => $data['visible'] ?? 1
        ]);
    }

    public function updateFaq($id, $data)
    {
        $sql = "UPDATE faqs SET pregunta = :p, respuesta = :r, categoria = :c, orden = :o, visible = :v 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'p' => $data['pregunta'],
            'r' => $data['respuesta'],
            'c' => $data['categoria'],
            'o' => $data['orden'],
            'v' => $data['visible'],
            'id' => $id
        ]);
    }

    public function deleteFaq($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM faqs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Obtiene el número de tickets que tienen respuestas nuevas de parte de la administración
     * (Estado = 'abierto' y el último mensaje no es del usuario de la agencia)
     */
    public function getPendingRepliesCount($agencyId)
    {
        $sql = "SELECT COUNT(DISTINCT t.id) 
                FROM tickets t
                JOIN ticket_mensajes tm ON t.id = tm.ticket_id
                WHERE t.agencia_id = :aid 
                AND t.estado != 'cerrado'
                AND tm.id = (SELECT MAX(id) FROM ticket_mensajes WHERE ticket_id = t.id)
                AND tm.usuario_id IN (SELECT id FROM usuarios WHERE rol = 'admin')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['aid' => $agencyId]);
        return $stmt->fetchColumn();
    }
}
