<?php
// seed_faqs.php - Script para poblar FAQs iniciales
require_once __DIR__ . '/config/db.php';

try {
    // El $pdo ya está disponible desde config/db.php

    // Limpiar tabla antes si se desea (opcional)
    // $pdo->exec("DELETE FROM faqs");

    $faqs = [
        [
            'pregunta' => '¿Cómo creo mi primera reserva?',
            'respuesta' => 'Para crear una reserva, ve al menú "Reservaciones", haz clic en el botón "Nueva Reserva" y selecciona al cliente y el tour deseado. No olvides completar los datos de los pasajeros.',
            'categoria' => 'reservas',
            'orden' => 1
        ],
        [
            'pregunta' => '¿Cómo edito el estado de un colaborador?',
            'respuesta' => 'Ve a "Gestión de Colaboradores", haz clic en el botón de edición (icono de lápiz) junto al empleado. Allí podrás cambiar su estado a Activo o Inactivo.',
            'categoria' => 'cuenta',
            'orden' => 2
        ],
        [
            'pregunta' => '¿Dónde veo el flujo de caja de mis ventas?',
            'respuesta' => 'Solo el Administrador (Dueño) tiene acceso a "Flujo de Pagos" en el menú principal. Allí verás todos los ingresos registrados por reservas aprobadas.',
            'categoria' => 'pagos',
            'orden' => 3
        ],
        [
            'pregunta' => '¿Cómo cambio mi contraseña personal?',
            'respuesta' => 'Haz clic en tu nombre en la barra superior o ve a "Mi Perfil". Verás una sección de Seguridad donde podrás ingresar una nueva contraseña.',
            'categoria' => 'cuenta',
            'orden' => 4
        ],
        [
            'pregunta' => '¿Cómo registro un nuevo guía local?',
            'respuesta' => 'Accede a "Recursos y Logística" > "Mis Guías". Presiona "Nuevo Guía" y completa sus datos incluyendo DNI y especialidad.',
            'categoria' => 'reservas',
            'orden' => 5
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO faqs (pregunta, respuesta, categoria, orden, visible) VALUES (?, ?, ?, ?, 1)");

    foreach ($faqs as $f) {
        $stmt->execute([$f['pregunta'], $f['respuesta'], $f['categoria'], $f['orden']]);
    }

    echo "FAQs predeterminadas insertadas correctamente.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
