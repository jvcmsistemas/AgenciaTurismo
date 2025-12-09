# 🏁 HITO V2: ESTADO DEL SISTEMA PARA SIGUIENTE SESIÓN
**Fecha:** 08 de Diciembre, 2025
**Estado:** PRODUCCIÓN LIMPIA (V2)

Este documento sirve como "caja negra" para iniciar la siguiente fase de desarrollo.

## 1. 🏗️ Arquitectura Actual
*   **Base de Datos:** `agencia_turismo_db` (Versión V2 Completa).
    *   25 Tablas (incluye `auditoria`, `pagos` separados, `itinerarios`).
    *   4 Vistas (`v_ingresos...`, `v_deudores...`).
    *   **Importante:** Se incluyeron `deleted_at` (Soft Delete) y `fecha_inicio_tour` en reservas.
*   **Estructura de Carpetas:** LIMPIA.
    *   Se eliminaron scripts temporales (`seed_*.php`, `fix_*.php`).
    *   El núcleo MVC (`controllers`, `models`, `views`) está intacto.
    *   El módulo de **Reservas (Create)** usa el diseño "Split-View" avanzado.

## 2. 🔑 Credenciales de Acceso
El sistema tiene dos niveles de acceso diferenciados:

| Panel | URL Relativa | Usuario | Contraseña | Rol |
| :--- | :--- | :--- | :--- | :--- |
| **Agencia** (Operativo) | `/login` | `admin@agencia.com` | `password` | `dueno_agencia` |
| **Super Admin** (Oscuro) | `/admin/login` | `superadmin@system.com` | `password` | `administrador_general` |

## 3. 🚨 Lo Pendiente (Roadmap Inmediato)
Al abrir el nuevo chat, el foco debe ser:

1.  **Refactor de Menús:** La BD ahora soporta roles y módulos nuevos. Se debe actualizar el sidebar (`includes/sidebar.php` o similar) para reflejar:
    *   Auditoría
    *   Pagos
    *   Reportes Avanzados
2.  **Módulo "Editar Reserva":** NO EXISTE. Es prioridad absoluta. Debe usar la misma lógica JS que `create.php`.
3.  **Verificación de Compatibilidad:** Verificar que el controlador antiguo de Reservas escriba correctamente en las columnas nuevas (`salida_id`, `fecha_inicio_tour`).

## 4. 📁 Ubicación de Recursos
*   **Esquema de BD:** `Documentacion/Schema_Reference.md` (La biblia de la base de datos).
*   **Backups:** Git Commit "Backup V1".

---
**Instrucción para el siguiente Agente:**
"El sistema está limpio y en V2. No intentes migrar la base de datos de nuevo. Concéntrate en la UI de Menús y el módulo de Edición."
