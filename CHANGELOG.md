# Changelog - Sistema de Agencia de Turismo

Registro de mejoras, correcciones y nuevas funcionalidades implementadas en el sistema.

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

---
*Documentado por Antigravity AI.*
