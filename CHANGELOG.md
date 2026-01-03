# Changelog - Sistema de Agencia de Turismo

Registro de mejoras, correcciones y nuevas funcionalidades implementadas en el sistema.

## [2026-01-03] - Estandarización Financiera y Control de Caja

### 🪙 Estandarización de Moneda
- **Símbolo Global**: Se estableció **"S/ "** como el símbolo de moneda oficial en todo el sistema (PHP y JavaScript).
- **Formateador Centralizado**: Implementada la función `formatCurrency()` para asegurar que todos los montos en el Dashboard, Tour, Salidas y Reservas tengan el mismo formato profesional.
- **Iconografía Localizada**: Reemplazados los iconos de dólar (`$`) por el símbolo de Soles en todos los formularios de creación y edición.

### 💳 Gestión de Pagos Estética y Funcional
- **Badges Semánticos**: Implementadas etiquetas de color para métodos de pago con alto contraste:
    - **Efectivo**: Verde.
    - **Yape**: Morado.
    - **Transferencia**: Azul.
    - **Tarjeta**: Naranja.
- **Soporte de Temas**: Optimización cross-theme (Claro/Oscuro) para que las etiquetas sean vibrantes y legibles bajo cualquier preferencia de usuario.
- **Agrupación Visual (Listón de Identidad)**: Añadido un indicador lateral de color en el Flujo de Pagos que agrupa automáticamente todas las transacciones pertenecientes a una misma reserva.

### 🔄 Sincronización Automática (Control de Auditoría)
- **Registro en Tiempo Real**: Ahora, cada "Pago Inicial" al crear una reserva se registra automáticamente en la tabla de Pagos, alimentando el flujo de caja sin intervención manual.
- **Migración Histórica**: Se ejecutó un proceso de sincronización para recuperar pagos iniciales de reservas antiguas, garantizando que el historial de ingresos sea retroactivo y preciso para el dueño.

## [2026-01-02] - Mejoras en Reservas, Pagos y Salidas

### 🛡️ Módulo de Pagos
- **Corrección de Saldo**: Implementada la funcionalidad para eliminar pagos registrados por error. El sistema ahora recalcula automáticamente el `saldo_pendiente` y el `estado` de la reserva al borrar un abono.
- **UX de Registro de Pago**:
    - Botón "Pagar Saldo" que autocompleta el monto exacto pendiente.
    - Botón rápido "Registrar Abono" en el encabezado del historial.
    - Opción "Registrar Pago" directamente desde el menú desplegable del listado de reservas.

### 📄 Reportes (PDF)
- **Sello de Pago**: Se añadió un sello flotante de **"PAGADO"** (watermark) que aparece automáticamente cuando la reserva no tiene saldo pendiente.
- **Optimización de Espacio**: Refinamiento estético reduciendo espacios verticales excesivos para lograr un reporte más compacto y profesional.

### 🎨 Interfaz y UX (Tema Dinámico)
- **Consistencia de Modo Claro**:
    - Corregido el fondo oscuro en el listado de reservas que no se adaptaba al tema claro.
    - Implementación de clases CSS dinámicas (`text-dynamic`, `bg-surface-dynamic`) para asegurar legibilidad en el historial de pagos bajo cualquier tema.
- **Claridad de Negocio**: Se renombró la etiqueta "Pasajeros" por **"Servicios"** en el listado de reservas para reflejar con precisión la suma de ítems contratados (evitando la confusión cuando un grupo pequeño contrata múltiples servicios).

### 🚐 Programación de Salidas
- **Inventario Inteligente**: El sistema ahora recalcula automáticamente los `cupos_disponibles` cuando se edita la capacidad total de una salida, restando las reservas activas (confirmadas/pendientes).
- **Validación de Capacidad**: Protección que impide reducir el cupo total por debajo del número de asientos ya reservados.
- **Mejora en Transporte**:
    - Se muestra la capacidad de asientos de cada vehículo en el selector.
    - Inclusión de la opción **"-- Sin transporte (Movilidad externa/propia) --"** para dar flexibilidad a la operación.

### 💡 Centro de Ayuda (Onboarding Visual)
- **Ruta del Éxito**: Implementación de una guía visual paso a paso para nuevos empleados con ilustraciones 3D personalizadas.
- **Navegación Directa**: Botones integrados que llevan directamente a la configuración de recursos, catálogo, salidas y ventas.
- **Mejora en Accesibilidad**: Tarjetas de ayuda con iconos y descripciones claras para facilitar la curva de aprendizaje.
- **Guías Avanzadas**: Inclusión de manuales específicos sobre organización de tours por **Zonas**, **Rutas** y creación de **Paquetes**.

### 🚀 Dashboard: Command Center (v3.2)
- **Rediseño Operativo**: Transformación total del dashboard hacia un centro de mando orientado a la acción inmediata.
- **Calendario Visual de Salidas**: Nuevo widget tipo "Timeline" que permite visualizar las salidas de hoy y mañana de forma secuencial y estilizada.
- **Privacidad Inteligente**:
    - Los KPIs financieros (Ingresos Mensuales y Totales) ahora solo son visibles para el **Dueño de la Agencia**.
    - Para **Empleados**, se han implementado métricas de motivación: **"Guía de Oro"** (más activo) y **"Top Movilidad"** (más utilizada).
- **Correcciones Técnicas**:
    - Eliminadas las inconsistencias visuales ("partes blancas") en el modo oscuro mediante el uso de variables CSS dinámicas.
    - Solucionado el error de deprecación PHP en `substr()` mediante el uso de funciones multibyte (`mb_substr`) y null-checks.
    - Corregida la visibilidad del icono de "Nueva Venta" que antes se perdía por falta de contraste.

---
*Documentado por Antigravity AI.*
