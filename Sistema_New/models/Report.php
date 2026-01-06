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
    // --- STRATEGIC BI METHODS ---

    public function getAgencyFinancialPerformance()
    {
        $sql = "SELECT 
                    a.id,
                    a.nombre as agencia, 
                    COUNT(r.id) as num_reservas,
                    SUM(r.precio_total) as total_ventas,
                    SUM(r.precio_total - r.saldo_pendiente) as total_cobrado,
                    SUM(r.saldo_pendiente) as deuda_total,
                    (SUM(r.saldo_pendiente) / SUM(r.precio_total) * 100) as ratio_deuda
                FROM agencias a
                LEFT JOIN reservas r ON a.id = r.agencia_id AND r.estado != 'cancelada'
                GROUP BY a.id, a.nombre
                ORDER BY total_ventas DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getOperationalKPIs()
    {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM tickets WHERE estado = 'abierto') as tickets_abiertos,
                    (SELECT COUNT(*) FROM agencias WHERE estado = 'activa') as agencias_activas,
                    (SELECT COUNT(*) FROM reservas WHERE estado = 'confirmada' AND fecha_inicio_tour >= CURDATE()) as proximas_salidas,
                    (SELECT SUM(monto) FROM pagos WHERE estado = 'aprobado' AND MONTH(fecha_pago) = MONTH(CURRENT_DATE())) as recaudacion_mes
                ";
        return $this->pdo->query($sql)->fetch();
    }

    public function getMarketInsights()
    {
        // Distribución por origen y método de pago
        $insights = [];

        $insights['origen'] = $this->pdo->query("SELECT origen, COUNT(*) as cantidad FROM reservas GROUP BY origen")->fetchAll();
        $insights['pagos'] = $this->pdo->query("SELECT metodo_pago, COUNT(*) as cantidad FROM pagos WHERE estado = 'aprobado' GROUP BY metodo_pago")->fetchAll();

        return $insights;
    }

    public function getSupportLoadByAgency()
    {
        $sql = "SELECT 
                    a.nombre as agencia, 
                    COUNT(t.id) as total_tickets,
                    SUM(CASE WHEN t.estado = 'cerrado' THEN 1 ELSE 0 END) as resueltos
                FROM agencias a
                LEFT JOIN tickets t ON a.id = t.agencia_id
                GROUP BY a.id, a.nombre
                HAVING total_tickets > 0
                ORDER BY total_tickets DESC";
        return $this->pdo->query($sql)->fetchAll();
    }
}

