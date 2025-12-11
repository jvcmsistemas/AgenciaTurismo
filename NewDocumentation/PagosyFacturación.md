
# 💰 Pagos \& Facturación

Este módulo se basa completamente en las tablas ya existentes en la base de datos `agencia_turismo_db` y permite gestionar cobros, saldos pendientes e ingresos por agencia y método de pago.[^1]

***

## 🧱 Tablas involucradas

### 1. Tabla `reservas`

Representa la cabecera comercial de cada operación (la "orden" o reserva del cliente). Es la base para calcular saldos, estado de pago y reportes de facturación.[^1]

Campos relevantes:

- `id` (int, PK): Identificador de la reserva.[^1]
- `codigoreserva` (varchar(20)): Código visible para el cliente, por ejemplo `RES-2024-001`.[^1]
- `clienteid` (int, FK `clientes.id`): Cliente asociado a la reserva.[^1]
- `agenciaid` (int, FK `agencias.id`): Agencia a la que pertenece la reserva.[^1]
- `fechahorareserva` (datetime): Fecha y hora de creación de la reserva.[^1]
- `estado` (enum): `pendiente`, `confirmada`, `encurso`, `completada`, `cancelada`.[^1]
- `preciototal` (decimal(10,2)): Importe total pactado de la reserva (antes de pagos).[^1]
- `descuento` (decimal(10,2)): Monto de descuento aplicado a la reserva.[^1]
- `saldopendiente` (decimal(10,2)): Monto que falta por cobrar al cliente.[^1]
- `origen` (enum): Canal de venta (`web`, `presencial`, `whatsapp`, `iachat`).[^1]

> Uso principal: mostrar totales de la reserva, controlar su estado y saber cuánto falta pagar.

***

### 2. Tabla `reservadetalles`

Desglosa la reserva en servicios específicos (tour, hotel, transporte, etc.) y sirve como base para reportes de ingresos por tipo de servicio y proveedor.[^1]

Campos relevantes:

- `id` (int, PK).[^1]
- `reservaid` (int, FK `reservas.id`).[^1]
- `tiposervicio` (enum): `tour`, `hotel`, `transporte`, `guia`, `restaurante`, `otro`.[^1]
- `servicioid` (int): ID del servicio en su tabla correspondiente (tours, hoteles, proveedores, etc.).[^1]
- `fechaservicio` (datetime): Fecha en la que se presta el servicio.[^1]
- `cantidad` (int): Unidades (personas, noches, traslados, etc.).[^1]
- `preciounitario` (decimal(10,2)).[^1]
- `subtotal` (decimal(10,2)): Resultado de `cantidad * preciounitario`.[^1]

> Uso principal: detalle contable para analítica (ingresos por tipo de servicio, por día, por proveedor, etc.).

***

### 3. Tabla `pagos`

Tabla central del módulo **Pagos \& Facturación**. Registra cada cobro asociado a una reserva, con método de pago, monto, estado y referencia del comprobante.[^1]

```sql
CREATE TABLE pagos (
  id            int(11)       NOT NULL,
  reservaid     int(11)       NOT NULL,
  monto         decimal(10,2) NOT NULL,
  fechapago     datetime      DEFAULT current_timestamp,
  metodopago    enum('efectivo','tarjeta','transferencia','yape','plin','otro') NOT NULL,
  referencia    varchar(100)  DEFAULT NULL COMMENT 'Nro de operación o voucher',
  comprobanteurl varchar(255) DEFAULT NULL COMMENT 'Ruta a la imagen del voucher',
  estado        enum('pendiente','aprobado','rechazado') DEFAULT 'aprobado',
  registradopor int(11)       DEFAULT NULL COMMENT 'Usuario que registró el pago'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Relaciones y claves:

- FK `pagosreservafk`: `reservaid` → `reservas.id`.[^1]
- Índice en `reservaid` para consultas rápidas por reserva.[^1]

Significado de campos:

- `reservaid`: identifica a qué reserva pertenece el pago.[^1]
- `monto`: importe del pago (positivo, en la moneda configurada para el sistema).[^1]
- `fechapago`: fecha/hora real de registro del pago (se puede usar para filtros de caja diaria).[^1]
- `metodopago`: tipo de medio de pago (`efectivo`, `tarjeta`, `transferencia`, `yape`, `plin`, `otro`).[^1]
- `referencia`: número de operación, código de transacción, número de voucher, etc.[^1]
- `comprobanteurl`: URL o ruta al archivo (imagen/PDF) del comprobante de pago.[^1]
- `estado`: estado del pago (`pendiente`, `aprobado`, `rechazado`).[^1]
- `registradopor`: usuario del sistema que registró el pago (FK a `usuarios.id`).[^1]

> Uso principal: historial de cobros, conciliación de caja, reportes de ingresos y validación del saldo de cada reserva.

***

## 🔗 Modelo conceptual Pagos \& Facturación

A nivel de negocio, el flujo de datos se entiende así:[^1]

1. `reservas` define el importe total pactado (`preciototal`) y el saldo pendiente (`saldopendiente`).[^1]
2. `reservadetalles` especifica en qué se compone ese total (tours, hoteles, transportes, etc.).[^1]
3. `pagos` almacena cada abono del cliente hacia esa reserva, con su método de pago y comprobante.[^1]
4. La suma de `pagos.monto` aprobados contra cada `reservaid` permite calcular el **total pagado** y ajustar el **saldo pendiente**.[^1]

Esquema simplificado:

```text
CLIENTE ──► RESERVA (cabecera)
             ├─ RESERVADETALLES (detalle de servicios)
             └─ PAGOS (movimientos de cobro)
```


***

## 📈 Consultas base para reportes y lógica de negocio

### 1. Pagos de una reserva

Listado de pagos asociados a una reserva específica:

```sql
SELECT 
  p.id,
  p.monto,
  p.fechapago,
  p.metodopago,
  p.referencia,
  p.comprobanteurl,
  p.estado,
  p.registradopor
FROM pagos p
WHERE p.reservaid = :reservaid
ORDER BY p.fechapago ASC;
```


### 2. Total pagado vs saldo pendiente

Permite mostrar al usuario cuánto ha pagado el cliente y cuánto falta cobrar:

```sql
SELECT
  r.id,
  r.codigoreserva,
  r.preciototal,
  r.descuento,
  r.saldopendiente,
  COALESCE(SUM(p.monto), 0) AS total_pagado
FROM reservas r
LEFT JOIN pagos p 
       ON p.reservaid = r.id 
      AND p.estado = 'aprobado'
WHERE r.id = :reservaid
GROUP BY r.id;
```


### 3. Ingresos por método de pago

Ideal para reportes de caja diaria/mensual y conciliación bancaria:

```sql
SELECT 
  metodopago,
  SUM(monto) AS total_pagado,
  COUNT(*)  AS cantidad_pagos
FROM pagos
WHERE estado = 'aprobado'
  AND fechapago BETWEEN :fecha_inicio AND :fecha_fin
GROUP BY metodopago
ORDER BY total_pagado DESC;
```


### 4. Ingresos por agencia

Sirve para que el **Superadmin** vea cuánto genera cada agencia:

```sql
SELECT 
  r.agenciaid,
  a.nombre       AS agencia,
  SUM(p.monto)   AS total_pagado,
  COUNT(p.id)    AS cantidad_pagos
FROM pagos p
JOIN reservas r ON p.reservaid = r.id
JOIN agencias a ON r.agenciaid = a.id
WHERE p.estado = 'aprobado'
  AND fechapago BETWEEN :fecha_inicio AND :fecha_fin
GROUP BY r.agenciaid, a.nombre
ORDER BY total_pagado DESC;
```


### 5. Ingresos por tipo de servicio (basado en `reservadetalles`)

Aunque los pagos se registran al nivel de reserva, la analítica por tipo de servicio se hace vía detalle:

```sql
SELECT 
  rd.tiposervicio,
  SUM(rd.subtotal) AS total_servicio
FROM reservadetalles rd
JOIN reservas r ON rd.reservaid = r.id
WHERE r.estado IN ('confirmada','completada')
  AND r.fechahorareserva BETWEEN :fecha_inicio AND :fecha_fin
GROUP BY rd.tiposervicio
ORDER BY total_servicio DESC;
```


***

## 🧾 Relación con facturación (boletas/facturas)

La estructura actual cubre **Pagos \& Cobros** (movimientos de caja), pero no define aún una tabla específica de documentos tributarios (boleta/factura). Sin embargo, el campo `comprobanteurl` en `pagos` permite vincular el archivo generado por un sistema de facturación externo.[^1]

En una siguiente iteración se puede agregar una tabla `comprobantes` por cada pago o por cada reserva, pero no es estrictamente necesaria para poner en marcha el módulo de Pagos \& Facturación.[^1]

***

## 🧩 Resumen funcional

- `reservas` define el **monto a cobrar** y el **estado comercial** de la operación.[^1]
- `reservadetalles` define **en qué se compone** ese monto (tours, hoteles, transportes, etc.).[^1]
- `pagos` registra **cada cobro** con importe, método, estado y comprobante.[^1]
- Con consultas agregadas puedes construir dashboards de:
    - Ingresos por agencia, rango de fechas y método de pago.[^1]
    - Saldos pendientes por reserva/cliente.[^1]
    - Ingresos por tipo de servicio (tour, hotel, transporte, etc.).[^1]

Este diseño es suficiente para implementar un módulo completo de **Pagos \& Facturación** sobre la base de datos actual, sin cambios estructurales obligatorios.[^1]

<div align="center">⁂</div>

[^1]: agencia_turismo_db.sql

