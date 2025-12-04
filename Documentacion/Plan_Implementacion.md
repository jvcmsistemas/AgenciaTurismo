# Plan de Modernización de Base de Datos (Scalability & AI Ready)

## Objetivo
Reestructurar la base de datos para soportar:
1.  **Escalabilidad Real:** Reservas complejas con múltiples servicios.
2.  **Gestión Financiera:** Control de pagos parciales y liquidaciones.
3.  **Multi-Tenencia Robusta:** Asegurar aislamiento de datos entre agencias.
4.  **AI Readiness:** Estructuras para futuras integraciones con IA (Chat, Recomendaciones).

## Cambios Propuestos en Base de Datos

### 1. Nuevas Tablas Principales

#### `pagos`
Manejo de transacciones financieras vinculadas a reservas.
- `id`: PK
- `reserva_id`: FK -> reservas
- `monto`: Decimal
- `fecha_pago`: Datetime
- `metodo_pago`: Enum (tarjeta, efectivo, transferencia, yape, plin)
- `referencia_pago`: Varchar (código de operación)
- `estado`: Enum (pendiente, confirmado, rechazado)
- `comprobante_url`: Varchar (para subir foto del voucher)
- `agencia_id`: FK (Multi-tenancy)

#### `reserva_detalles` (Tabla Pivote Flexible)
Permite que una reserva incluya N servicios (ej. 1 Tour + 2 Hoteles + 1 Transporte extra).
- `id`: PK
- `reserva_id`: FK
- `tipo_servicio`: Enum ('tour', 'hotel', 'transporte', 'guia', 'restaurante', 'otro')
- `servicio_id`: Int (ID del servicio específico en su tabla respectiva)
- `cantidad`: Int
- `precio_unitario`: Decimal
- `subtotal`: Decimal
- `fecha_servicio`: Datetime (Cuándo se usará este servicio específico)
- `notas`: Text

#### `itinerarios`
Detalle día a día de los tours para generar contenido rico para la IA y PDFs.
- `id`: PK
- `tour_id`: FK
- `dia_numero`: Int
- `titulo`: Varchar
- `descripcion`: Text
- `actividades`: Text (JSON o texto plano estructurado)

### 2. Tablas para IA y Futuro Frontend

#### `solicitudes_ia`
Para guardar lo que los clientes piden ("Quiero ir a Cusco barato").
- `id`: PK
- `cliente_id`: FK (puede ser NULL si es visitante)
- `prompt_usuario`: Text (Lo que escribió el usuario)
- `respuesta_ia`: Text (Lo que recomendó la IA)
- `intencion_detectada`: Varchar (ej. "compra", "informacion", "reclamo")
- `presupuesto_detectado`: Decimal
- `fecha`: Datetime

#### `conversaciones`
Historial de chat.
- `id`: PK
- `sesion_id`: Varchar (para usuarios no logueados)
- `usuario_id`: FK
- `mensaje`: Text
- `emisor`: Enum ('usuario', 'ia', 'agente')
- `fecha`: Datetime

### 3. Modificaciones a Tablas Existentes

#### `reservas`
- **ELIMINAR:** `guia_id`, `transporte_id`, `hotel_id`, `restaurante_id` (Se mueven a `reserva_detalles`).
- **AGREGAR:** `codigo_reserva` (String único amigable, ej. "RES-8392"), `origen_reserva` (Enum: 'web', 'presencial', 'ia_chat').

#### `tours`
- **AGREGAR:** `tags` (Varchar/JSON: "aventura, familia, economico"), `nivel_dificultad`, `ubicacion_geografica` (Lat/Long o Ciudad para búsqueda por mapa).
