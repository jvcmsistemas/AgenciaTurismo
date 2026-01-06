<?php
// Sistema_New/models/AgencyReport.php

class AgencyReport
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene la rentabilidad detallada por salida para una agencia.
     */
    public function getProfitabilityPerDeparture($agencyId, $startDate = null, $endDate = null)
    {
        $sql = "SELECT 
                    s.id as salida_id,
                    s.fecha_salida,
                    s.hora_salida,
                    t.nombre as tour_nombre,
                    s.costo_guia,
                    s.costo_transporte,
                    s.costo_otros,
                    (SELECT COALESCE(SUM(monto), 0) FROM gastos WHERE salida_id = s.id) as total_gastos_tabla,
                    (s.costo_guia + s.costo_transporte + s.costo_otros + (SELECT COALESCE(SUM(monto), 0) FROM gastos WHERE salida_id = s.id)) as costo_total_operativo,
                    COALESCE(SUM(rd.subtotal), 0) as ingreso_bruto,
                    (COALESCE(SUM(rd.subtotal), 0) - (s.costo_guia + s.costo_transporte + s.costo_otros + (SELECT COALESCE(SUM(monto), 0) FROM gastos WHERE salida_id = s.id))) as utilidad_neta,
                    s.cupos_totales,
                    (s.cupos_totales - s.cupos_disponibles) as cupos_vendidos,
                    s.estado
                FROM salidas s
                JOIN tours t ON s.tour_id = t.id
                LEFT JOIN reserva_detalles rd ON s.id = rd.servicio_id AND rd.tipo_servicio = 'tour'
                LEFT JOIN reservas r ON rd.reserva_id = r.id AND r.estado != 'cancelada'
                WHERE s.agencia_id = :agencia_id";

        $params = ['agencia_id' => $agencyId];

        if ($startDate) {
            $sql .= " AND s.fecha_salida >= :start_date";
            $params['start_date'] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND s.fecha_salida <= :end_date";
            $params['end_date'] = $endDate;
        }

        $sql .= " GROUP BY s.id ORDER BY s.fecha_salida DESC, s.hora_salida DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
