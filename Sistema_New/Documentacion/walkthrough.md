## Dashboard Superadmin (Panel de Control)

### Descripción
Centro de mando principal para el Administrador General, con visión global del negocio, soporte y accesos rápidos.

### Componentes Implementados

#### 1. KPIs y Métricas
- **Ingresos Mensuales**: Cálculo en tiempo real de pagos completados.
- **Tickets Pendientes**: Contador de solicitudes de soporte abiertas.
- **Agencias y Usuarios**: Estadísticas de crecimiento.

#### 2. Visualización
- **Gráfico de Ingresos**: Implementado con Chart.js (Datos simulados/reales).
- **Diseño Glassmorphism**: Tarjetas con transparencias y desenfoque (`backdrop-filter`).

### Superadmin Dashboard
- **Backend**: Update `AdminController` to fetch agency count, user count, monthly revenue (simulated), and open tickets.
- **Frontend**: Create `views/admin/dashboard.php` with 4 KPI cards, Chart.js integration for "Ingresos del Año", and lists for Recent Agencies and Recent Tickets.
- **Styling**: Applied Glassmorphism (glass-card) and dark/light mode compatibility.

### Superadmin Profile
- **Route**: `admin/profile` and `admin/profile/update` added to `index.php`.
- **Controller**: Added `profile()` and `updateProfile()` to `AdminController` to handle user data fetching and updates (Name, Surname, Password).
- **Model Standard**: Aliased `findById` to `getById` in `User.php` to maintain consistency.
- **View**: Created `views/admin/profile/index.php` allowing admins to update their personal info and password securely.

### Verificación
- Se verificó la carga correcta de todos los widgets.
- Se probó la navegación desde "Acciones Rápidas".
- Se confirmó la integridad visual del tema "Premium".
