<?php
// Sistema_New/models/Report.php

class Report
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // --- FINANCIERO (Ingresos) ---

    public function getIncomeByPeriod($period = 'month')
    {
        // Default: Last 6 months
        $groupBy = "DATE_FORMAT(r.fecha_hora_reserva, '%Y-%m')"; // YYYY-MM
        $limit = 6;

        $sql = "SELECT 
                    $groupBy AS periodo, 
                    SUM(r.precio_total) as total_vendido,
                    SUM(r.saldo_pendiente) as total_pendiente
                FROM reservas r 
                WHERE r.estado != 'cancelada' 
                GROUP BY periodo 
                ORDER BY periodo DESC 
                LIMIT $limit";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function getIncomeByAgency()
    {
        // Top 10 Agencias por Ingresos (Superadmin)
        $sql = "SELECT 
                    a.nombre as agencia, 
                    SUM(r.precio_total) as total
                FROM reservas r
                JOIN agencias a ON r.agencia_id = a.id
                WHERE r.estado != 'cancelada'
                GROUP BY a.id, a.nombre
                ORDER BY total DESC
                LIMIT 10";
        return $this->pdo->query($sql)->fetchAll();
    }

    // --- OPERATIVO (Reservas, Tours) ---

    public function getReservationStats()
    {
        $sql = "SELECT estado, COUNT(*) as cantidad FROM reservas GROUP BY estado";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR); // 'confirmada' => 10, etc.
    }

    public function getTopTours()
    {
        // Based on reservation details count
        $sql = "SELECT 
                    rd.tipo_servicio as tiposervicio, 
                    COUNT(*) as cantidad 
                FROM reserva_detalles rd 
                GROUP BY rd.tipo_servicio 
                ORDER BY cantidad DESC 
                LIMIT 5";
        return $this->pdo->query($sql)->fetchAll();
    }

    // --- SATISFACCIÓN (Reviews) ---

    public function getSatisfactionStats()
    {
        $sql = "SELECT 
                    AVG(calificacion) as promedio, 
                    COUNT(*) as total_reviews,
                    SUM(CASE WHEN calificacion = 5 THEN 1 ELSE 0 END) as cinco_estrellas
                FROM reviews 
                WHERE visible = 1";
        return $this->pdo->query($sql)->fetch();
    }

    public function getRecentReviews()
    {
        $sql = "SELECT r.*, a.nombre as agencia 
                FROM reviews r 
                JOIN agencias a ON r.agencia_id = a.id 
                WHERE r.visible = 1 
                ORDER BY r.created_at DESC 
                LIMIT 5";
        return $this->pdo->query($sql)->fetchAll();
    }
}
