# 🎨 DESIGN SYSTEM & AI PROMPT - AGENCIA TURISMO

**Fecha:** 8 Diciembre 2025  
**Objetivo:** Prompt completo para desarrollar UI/UX con IA  
**Status:** ✅ Listo para usar con Claude/ChatGPT/Perplexity

---

## 🎯 PROMPT PARA SUPERADMIN PANEL (OSCURO)

```
INSTRUCCIONES PARA IA (Copia y pega esto):

---

Voy a crear un SISTEMA DE GESTIÓN DE AGENCIAS TURÍSTICAS Multi-Tenant 
con dos paneles separados. 

PANEL 1: SUPERADMIN (OSCURO - Para dueño del software)

REQUISITOS:

1. PALETA DE COLORES (Tema OSCURO):
   - Fondo Principal: #1a1a2e (gris muy oscuro, casi negro)
   - Fondo Secundario: #16213e (azul oscuro profundo)
   - Sidebar: #0f3460 (azul oscuro)
   - Accent Principal: #e94560 (rojo/coral vibrante - para CTAs)
   - Accent Secundario: #00d4ff (cyan/turquesa - para highlights)
   - Texto Principal: #ffffff (blanco puro)
   - Texto Secundario: #a8a8a8 (gris claro)
   - Success: #10b981 (verde esmeralda)
   - Warning: #f59e0b (naranja/dorado)
   - Error: #ef4444 (rojo)
   - Border: #2d3561 (gris azulado oscuro)

2. TIPOGRAFÍA:
   - Font Principal: "Segoe UI" o "Inter" (sans-serif moderna)
   - Font Secundaria: "Roboto" (clara y profesional)
   - Font Monospace: "Fira Code" o "JetBrains Mono" (para datos)
   
   Tamaños:
   - H1: 32px, bold (800), letter-spacing: -0.5px
   - H2: 28px, bold (800), letter-spacing: -0.3px
   - H3: 24px, semibold (700), letter-spacing: 0
   - H4: 20px, semibold (700)
   - Body: 14px, normal (400), line-height: 1.6
   - Small: 12px, normal (400)
   - Caption: 11px, normal (400)

3. COMPONENTES PRINCIPALES:

   a) NAVBAR (Top):
      - Height: 64px
      - Background: #16213e (azul oscuro)
      - Logo: Blanco + Icono (40x40)
      - Search Bar: Input gris oscuro con icono lupa
      - Icons (derecha): Notificaciones, Perfil, Logout
      - Border-bottom: 1px #2d3561
      - Box-shadow: 0 2px 8px rgba(0,0,0,0.3)

   b) SIDEBAR (Izquierda):
      - Width: 280px (colapsable a 80px en mobile)
      - Background: #0f3460
      - Logo: Arriba (60px height section)
      - Menu Items: 
        * Padding: 12px 20px
        * Height: 44px
        * Border-left: 4px transparent
        * Hover: background #1a5490, border-left #e94560
        * Active: background #1a5490, border-left #e94560
        * Icon + Text (side-by-side)
        * Color de icono: #a8a8a8 (normal), #e94560 (hover/active)
      - Submenu Items:
        * Padding-left: 50px
        * Font-size: 13px
        * Opacity: 0.8
      - Divider: 1px #1a5490

   c) MAIN CONTENT AREA:
      - Background: #1a1a2e
      - Padding: 24px
      - Min-height: calc(100vh - 64px)

   d) CARDS:
      - Background: #16213e
      - Border: 1px #2d3561
      - Border-radius: 8px
      - Padding: 20px
      - Box-shadow: 0 2px 8px rgba(0,0,0,0.2)
      - Hover: Box-shadow: 0 4px 12px rgba(233,69,96,0.15)
      - Transition: 0.3s ease

   e) BUTTONS:
      Primary (Rojo/Coral):
      - Background: #e94560
      - Text: #ffffff
      - Padding: 10px 24px
      - Border-radius: 6px
      - Font-weight: 600
      - Cursor: pointer
      - Hover: background #d63650, box-shadow: 0 4px 12px rgba(233,69,96,0.3)
      - Active: background #c02640
      - Disabled: opacity 0.5, cursor not-allowed
      
      Secondary (Cyan):
      - Background: transparent
      - Border: 2px #00d4ff
      - Text: #00d4ff
      - Hover: background rgba(0,212,255,0.1)
      
      Tertiary (Gris):
      - Background: #2d3561
      - Text: #a8a8a8
      - Hover: background #3a4875

   f) INPUTS & FORMS:
      - Background: #0f3460
      - Border: 1px #2d3561
      - Text: #ffffff
      - Placeholder: #6b7590
      - Padding: 10px 12px
      - Border-radius: 6px
      - Font-size: 14px
      - Focus: border-color #00d4ff, box-shadow: 0 0 0 3px rgba(0,212,255,0.1)
      - Error: border-color #ef4444

   g) TABLES:
      - Background: #16213e
      - Border: 1px #2d3561
      - Header BG: #0f3460
      - Header Text: #ffffff, bold
      - Row Hover: background rgba(0,212,255,0.05)
      - Border-radius: 8px
      - Striped rows: alternate #16213e y #1a1f3a
      - Padding: 12px 16px

   h) MODALS/DIALOGS:
      - Background: #16213e
      - Border: 1px #2d3561
      - Border-radius: 12px
      - Backdrop: rgba(0,0,0,0.7)
      - Box-shadow: 0 20px 25px rgba(0,0,0,0.3)
      - Header: padding 20px, border-bottom 1px #2d3561
      - Footer: padding 16px 20px, justify-content flex-end

   i) ALERTS/NOTIFICATIONS:
      Success:
      - Background: rgba(16,185,129,0.1)
      - Border-left: 4px #10b981
      - Text: #10b981
      
      Warning:
      - Background: rgba(245,158,11,0.1)
      - Border-left: 4px #f59e0b
      - Text: #f59e0b
      
      Error:
      - Background: rgba(239,68,68,0.1)
      - Border-left: 4px #ef4444
      - Text: #ef4444
      
      Info:
      - Background: rgba(0,212,255,0.1)
      - Border-left: 4px #00d4ff
      - Text: #00d4ff

   j) PAGINATION & DROPDOWN:
      - Page buttons: background #2d3561, text #a8a8a8
      - Active page: background #e94560, text #ffffff
      - Dropdown: background #0f3460, text #ffffff, border 1px #2d3561

4. ESTRUCTURA DE PÁGINAS:

   Dashboard:
   - Grid de 4 KPI cards en fila 1 (Ingresos, Agencias, Usuarios, Suscripciones)
   - 2 gráficos grandes (Línea: ingresos, Pie: planes)
   - Tabla de actividad reciente
   - Todo con Cards según especificación

   Agencias:
   - Búsqueda + Filtros (parte superior)
   - Tabla de agencias (paginada)
   - Columnas: Nombre, Email, Plan, Estado, Fecha Creación, Acciones
   - Botón "+ Nueva Agencia" (Rojo #e94560)
   - Modales para crear/editar

   Usuarios:
   - Búsqueda + Filtros por rol
   - Tabla con columnas: Nombre, Email, Rol, Agencia, Estado, Último Acceso
   - Botón "+ Nuevo Usuario"
   - Modales para crear/editar/resetear contraseña

5. ESPACIADO & LAYOUT:
   - Padding/Margin base: 8px (múltiplos de 8)
   - Gap entre componentes: 16px o 24px
   - Max-width contenido: 1400px
   - Grid: 12 columnas responsive

6. RESPONSIVE:
   - Desktop: 1200px+
   - Tablet: 768px - 1199px
   - Mobile: < 768px (Sidebar hamburguesa, bottom nav con 5 items)

7. ANIMACIONES:
   - Duración estándar: 0.3s
   - Easing: ease-in-out
   - Hover effects: color change + box-shadow
   - Transiciones suaves en página

8. ICONOS:
   - Usar Font Awesome 6, Feather Icons o Heroicons
   - Size: 20px (normal), 24px (grande), 16px (pequeño)
   - Color: #a8a8a8 (normal), #e94560 (hover), #00d4ff (active)

ESTILO GENERAL:
- Moderno, profesional, tech-forward
- Dark mode premium (como Stripe, Linear, GitHub Dark)
- Accesible (contrast ratios 4.5:1 mínimo)
- Rápido y responsive
- Animaciones sutiles (no distraer)
- Enfoque en datos y números (tablas, gráficos)

Ahora crea una página de SUPERADMIN DASHBOARD completa con todos estos especificaciones. 
Incluye: Navbar, Sidebar con menú, KPI cards, gráficos, tabla de actividad, responsive design.

---
```

---

## 🎯 PROMPT PARA ADMIN AGENCIA PANEL (VERDE)

```
INSTRUCCIONES PARA IA (Copia y pega esto):

---

PANEL 2: ADMIN AGENCIA (VERDE - Para dueño de agencia individual)

Similar al panel anterior pero con tema VERDE, para diferenciación visual clara.

REQUISITOS:

1. PALETA DE COLORES (Tema VERDE):
   - Fondo Principal: #f0f9ff (azul muy claro/casi blanco)
   - Fondo Secundario: #e0f2fe (azul muy claro)
   - Sidebar: #10b981 (verde esmeralda)
   - Accent Principal: #059669 (verde oscuro - para CTAs)
   - Accent Secundario: #34d399 (verde claro - para highlights)
   - Texto Principal: #1f2937 (gris oscuro casi negro)
   - Texto Secundario: #6b7280 (gris medio)
   - Success: #10b981 (verde esmeralda)
   - Warning: #f59e0b (naranja/dorado)
   - Error: #ef4444 (rojo)
   - Border: #d1d5db (gris claro)

2. TIPOGRAFÍA: (Igual que Superadmin)
   - Font Principal: "Segoe UI" o "Inter" (sans-serif moderna)
   - Font Secundaria: "Roboto"
   - Font Monospace: "Fira Code"
   
   Tamaños: (Igual que Superadmin)
   - H1: 32px, bold
   - H2: 28px, bold
   - etc...

3. COMPONENTES PRINCIPALES:

   a) NAVBAR (Top):
      - Height: 64px
      - Background: #ffffff (blanco puro)
      - Border-bottom: 2px #10b981 (verde)
      - Logo: Verde #10b981 + Icono
      - Search Bar: Input gris claro
      - Icons (derecha): Notificaciones (con badge verde), Perfil, Logout
      - Box-shadow: 0 2px 8px rgba(0,0,0,0.05)

   b) SIDEBAR (Izquierda):
      - Width: 280px (colapsable a 80px en mobile)
      - Background: #10b981 (verde esmeralda)
      - Logo: Arriba (blanco sobre verde)
      - Menu Items:
        * Text color: #ffffff
        * Hover: background rgba(255,255,255,0.2)
        * Active: background rgba(255,255,255,0.3), bold
        * Icon: blanco
      - Divider: rgba(255,255,255,0.2)

   c) MAIN CONTENT AREA:
      - Background: #f0f9ff (azul muy claro)
      - Padding: 24px
      - Grid responsive

   d) CARDS:
      - Background: #ffffff (blanco)
      - Border: 1px #e5e7eb (gris muy claro)
      - Border-radius: 8px
      - Padding: 20px
      - Box-shadow: 0 1px 3px rgba(0,0,0,0.05)
      - Hover: Box-shadow: 0 4px 12px rgba(16,185,129,0.1), border-color #10b981

   e) BUTTONS:
      Primary (Verde Oscuro):
      - Background: #059669
      - Text: #ffffff
      - Padding: 10px 24px
      - Border-radius: 6px
      - Font-weight: 600
      - Hover: background #047857, box-shadow: 0 4px 12px rgba(5,150,105,0.3)
      
      Secondary (Verde Claro):
      - Background: transparent
      - Border: 2px #10b981
      - Text: #10b981
      - Hover: background rgba(16,185,129,0.1)

   f) INPUTS & FORMS:
      - Background: #ffffff
      - Border: 1px #d1d5db
      - Text: #1f2937
      - Placeholder: #9ca3af
      - Focus: border-color #10b981, box-shadow: 0 0 0 3px rgba(16,185,129,0.1)

   g) TABLES:
      - Background: #ffffff
      - Header BG: #f3f4f6 (gris muy claro)
      - Header Text: #1f2937, bold
      - Row Hover: background #f9fafb
      - Border: 1px #e5e7eb

   h) ALERTS:
      Success:
      - Background: #ecfdf5
      - Border-left: 4px #10b981
      - Text: #065f46
      
      Similar para Warning, Error, Info (tonos verdes y otros)

   i) BADGES & STATUS:
      - Pendiente: amarillo (#fbbf24)
      - Confirmada: verde (#10b981)
      - Completada: azul (#3b82f6)
      - Cancelada: rojo (#ef4444)

4. ESTRUCTURA DE PÁGINAS PRINCIPALES:

   Dashboard:
   - Bienvenida "Hola, {{nombre agencia}}"
   - 4 KPI cards: Ingresos Mes, Reservas Pendientes, Clientes Activos, Saldo Pendiente
   - Gráfico ingresos últimos 30 días
   - Tabla "Próximas salidas"
   - Tabla "Pagos pendientes"
   - Card de suscripción (mostrando plan actual y fecha vencimiento)

   Reservas:
   - Filtros: Estado, Fechas, Cliente, Tour
   - Tabla con columnas: Código, Cliente, Tour, Fecha, Estado, Precio, Acciones
   - Tabla paginada y ordenable
   - Botón "+ Nueva Reserva" (verde)
   - Modal para crear/editar
   - Botón ver detalles (expande fila o abre modal)
   - Botón "Cancelar" con motivo (auditoria)
   - Botón "Confirmar"
   - Botón "Cambiar estado"

   Pagos:
   - Card de resumen (Total cobrado, Pendiente, Tasa cobranza %)
   - Tabla "Clientes por cobrar" (columnas: Cliente, Deuda, Días, Acciones)
   - Botón "Registrar Pago" (modal)
   - Tabla "Historial Pagos" (filtrable por período, método, estado)
   - Botón "Enviar recordatorio" (email/WhatsApp)

   Clientes:
   - Búsqueda + Filtros
   - Tabla: Nombre, Email, Teléfono, Total Gasto, Saldo Adeudado, Acciones
   - Botón "+ Nuevo Cliente"
   - Click en cliente: abre sidebar derecha con detalles:
     * Datos personales
     * Historial reservas
     * Pagos realizados
     * Saldo
     * Botones: Editar, Nueva Reserva, Contactar

5. RESPONSIVE:
   - Desktop: 1200px+
   - Tablet: 768px - 1199px (Sidebar colapsable)
   - Mobile: < 768px (Sidebar hamburguesa, Bottom Nav)

6. DARK MODE (Opcional):
   - Detectar preferencencia sistema
   - Toggle en perfil de usuario
   - Guardar preferencia en localStorage

Ahora crea una página de ADMIN AGENCIA DASHBOARD completa con estos especificaciones.
Incluye: Navbar, Sidebar, KPIs, gráfico ingresos, tablas de actividad, responsive.

---
```

---

## 📋 GUÍA DE COLORES RÁPIDA

### SUPERADMIN (Oscuro)
```
Copiar-pegar para CSS:

:root {
  --color-bg-primary: #1a1a2e;
  --color-bg-secondary: #16213e;
  --color-sidebar: #0f3460;
  --color-accent-primary: #e94560;
  --color-accent-secondary: #00d4ff;
  --color-text-primary: #ffffff;
  --color-text-secondary: #a8a8a8;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
  --color-border: #2d3561;
}

body {
  background-color: var(--color-bg-primary);
  color: var(--color-text-primary);
  font-family: 'Segoe UI', 'Inter', sans-serif;
}

.card {
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  border-radius: 8px;
}

.btn-primary {
  background-color: var(--color-accent-primary);
  color: white;
}

.btn-primary:hover {
  background-color: #d63650;
  box-shadow: 0 4px 12px rgba(233,69,96,0.3);
}
```

### ADMIN AGENCIA (Verde)
```
Copiar-pegar para CSS:

:root {
  --color-bg-primary: #f0f9ff;
  --color-bg-secondary: #ffffff;
  --color-sidebar: #10b981;
  --color-accent-primary: #059669;
  --color-accent-secondary: #34d399;
  --color-text-primary: #1f2937;
  --color-text-secondary: #6b7280;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
  --color-border: #d1d5db;
}

body {
  background-color: var(--color-bg-primary);
  color: var(--color-text-primary);
  font-family: 'Segoe UI', 'Inter', sans-serif;
}

.card {
  background-color: var(--color-bg-secondary);
  border: 1px solid var(--color-border);
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.btn-primary {
  background-color: var(--color-accent-primary);
  color: white;
}

.btn-primary:hover {
  background-color: #047857;
  box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}
```

---

## 🎨 COMPONENTES HTML BÁSICOS (Para Copiar)

### Navbar (Superadmin - Oscuro)
```html
<nav class="navbar">
  <div class="navbar-container">
    <div class="navbar-logo">
      <img src="logo.svg" alt="Logo">
      <span>ADMIN PANEL</span>
    </div>
    <input type="text" class="navbar-search" placeholder="Buscar...">
    <div class="navbar-actions">
      <button class="icon-btn">🔔</button>
      <button class="icon-btn">👤</button>
      <button class="icon-btn logout">Salir</button>
    </div>
  </div>
</nav>

<style>
.navbar {
  height: 64px;
  background-color: #16213e;
  border-bottom: 1px solid #2d3561;
  display: flex;
  align-items: center;
  padding: 0 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.navbar-container {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 20px;
}

.navbar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #ffffff;
  font-weight: 700;
}

.navbar-search {
  flex: 1;
  max-width: 300px;
  padding: 8px 12px;
  background-color: #0f3460;
  border: 1px solid #2d3561;
  border-radius: 6px;
  color: #ffffff;
}

.navbar-actions {
  display: flex;
  gap: 16px;
}

.icon-btn {
  background: none;
  border: none;
  color: #a8a8a8;
  cursor: pointer;
  font-size: 20px;
}

.icon-btn:hover {
  color: #00d4ff;
}
</style>
```

### Sidebar Menu (Superadmin - Oscuro)
```html
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="logo.svg" alt="Logo" width="40">
  </div>
  
  <nav class="sidebar-nav">
    <a href="#" class="nav-item active">
      <span class="icon">📊</span>
      <span class="text">Dashboard</span>
    </a>
    <a href="#" class="nav-item">
      <span class="icon">🏢</span>
      <span class="text">Agencias</span>
    </a>
    <a href="#" class="nav-item">
      <span class="icon">👥</span>
      <span class="text">Usuarios</span>
    </a>
    <a href="#" class="nav-item">
      <span class="icon">💰</span>
      <span class="text">Pagos</span>
    </a>
    <div class="nav-divider"></div>
    <a href="#" class="nav-item">
      <span class="icon">🔒</span>
      <span class="text">Auditoría</span>
    </a>
    <a href="#" class="nav-item">
      <span class="icon">⚙️</span>
      <span class="text">Configuración</span>
    </a>
  </nav>
</aside>

<style>
.sidebar {
  width: 280px;
  background-color: #0f3460;
  border-right: 1px solid #1a5490;
  height: calc(100vh - 64px);
  overflow-y: auto;
  padding: 20px 0;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 60px;
  border-bottom: 1px solid #1a5490;
  margin-bottom: 20px;
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 20px;
  height: 44px;
  color: #a8a8a8;
  text-decoration: none;
  border-left: 4px solid transparent;
  transition: all 0.3s ease;
  cursor: pointer;
}

.nav-item:hover {
  background-color: #1a5490;
  border-left-color: #e94560;
  color: #ffffff;
}

.nav-item.active {
  background-color: #1a5490;
  border-left-color: #e94560;
  color: #e94560;
}

.nav-item .icon {
  font-size: 20px;
}

.nav-divider {
  height: 1px;
  background-color: #1a5490;
  margin: 10px 0;
}
</style>
```

### KPI Cards (Superadmin - Oscuro)
```html
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon">💰</div>
    <div class="kpi-content">
      <p class="kpi-label">Ingresos Totales</p>
      <p class="kpi-value">$125,430.00</p>
      <p class="kpi-change positive">+12.5% este mes</p>
    </div>
  </div>
  
  <div class="kpi-card">
    <div class="kpi-icon">🏢</div>
    <div class="kpi-content">
      <p class="kpi-label">Agencias Activas</p>
      <p class="kpi-value">45</p>
      <p class="kpi-change">5 nuevas este mes</p>
    </div>
  </div>
  
  <div class="kpi-card">
    <div class="kpi-icon">👥</div>
    <div class="kpi-content">
      <p class="kpi-label">Usuarios Totales</p>
      <p class="kpi-value">234</p>
      <p class="kpi-change">+18 esta semana</p>
    </div>
  </div>
  
  <div class="kpi-card">
    <div class="kpi-icon">📅</div>
    <div class="kpi-content">
      <p class="kpi-label">Suscripciones</p>
      <p class="kpi-value">38/45</p>
      <p class="kpi-change">84.4% conversión</p>
    </div>
  </div>
</div>

<style>
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.kpi-card {
  background-color: #16213e;
  border: 1px solid #2d3561;
  border-radius: 8px;
  padding: 20px;
  display: flex;
  gap: 15px;
  transition: all 0.3s ease;
}

.kpi-card:hover {
  border-color: #e94560;
  box-shadow: 0 4px 12px rgba(233,69,96,0.15);
}

.kpi-icon {
  font-size: 32px;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.kpi-content {
  flex: 1;
}

.kpi-label {
  margin: 0;
  font-size: 12px;
  color: #a8a8a8;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.kpi-value {
  margin: 8px 0 0 0;
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
}

.kpi-change {
  margin: 4px 0 0 0;
  font-size: 12px;
  color: #6b7280;
}

.kpi-change.positive {
  color: #10b981;
}
</style>
```

### Button Variations
```html
<!-- SUPERADMIN (Oscuro) -->
<button class="btn btn-primary">+ Nueva Agencia</button>
<button class="btn btn-secondary">Cancelar</button>
<button class="btn btn-tertiary">Más Opciones</button>
<button class="btn btn-primary btn-lg">Acción Grande</button>
<button class="btn btn-primary" disabled>Deshabilitado</button>

<!-- ADMIN AGENCIA (Verde) -->
<button class="btn btn-primary-green">+ Nueva Reserva</button>
<button class="btn btn-secondary-green">Cancelar</button>

<style>
.btn {
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

/* SUPERADMIN - OSCURO */
.btn-primary {
  background-color: #e94560;
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background-color: #d63650;
  box-shadow: 0 4px 12px rgba(233,69,96,0.3);
}

.btn-primary:active:not(:disabled) {
  background-color: #c02640;
}

.btn-secondary {
  background-color: transparent;
  border: 2px solid #00d4ff;
  color: #00d4ff;
}

.btn-secondary:hover:not(:disabled) {
  background-color: rgba(0,212,255,0.1);
}

.btn-tertiary {
  background-color: #2d3561;
  color: #a8a8a8;
}

.btn-tertiary:hover:not(:disabled) {
  background-color: #3a4875;
}

/* ADMIN AGENCIA - VERDE */
.btn-primary-green {
  background-color: #059669;
  color: #ffffff;
}

.btn-primary-green:hover:not(:disabled) {
  background-color: #047857;
  box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}

.btn-secondary-green {
  background-color: transparent;
  border: 2px solid #10b981;
  color: #10b981;
}

.btn-secondary-green:hover:not(:disabled) {
  background-color: rgba(16,185,129,0.1);
}

.btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-lg {
  padding: 14px 32px;
  font-size: 16px;
}
</style>
```

---

## 🎨 DESIGN TOKENS (VARIABLES GLOBALES)

```css
/* COLORES */
--color-bg-primary: (oscuro #1a1a2e / verde claro #f0f9ff)
--color-bg-secondary: (oscuro #16213e / verde blanco #ffffff)
--color-sidebar: (oscuro #0f3460 / verde #10b981)
--color-accent-primary: (oscuro #e94560 / verde #059669)
--color-accent-secondary: (oscuro #00d4ff / verde #34d399)
--color-text-primary: (oscuro #ffffff / verde #1f2937)
--color-text-secondary: (oscuro #a8a8a8 / verde #6b7280)
--color-border: (oscuro #2d3561 / verde #d1d5db)

/* TIPOGRAFÍA */
--font-family-base: 'Segoe UI', 'Inter', sans-serif
--font-family-mono: 'Fira Code', monospace

--font-size-h1: 32px
--font-size-h2: 28px
--font-size-h3: 24px
--font-size-h4: 20px
--font-size-body: 14px
--font-size-small: 12px

--font-weight-normal: 400
--font-weight-medium: 500
--font-weight-semibold: 600
--font-weight-bold: 700

/* ESPACIADO */
--spacing-xs: 4px
--spacing-sm: 8px
--spacing-md: 16px
--spacing-lg: 24px
--spacing-xl: 32px

/* BORDER RADIUS */
--radius-sm: 4px
--radius-md: 6px
--radius-lg: 8px
--radius-xl: 12px

/* SOMBRAS */
--shadow-sm: 0 1px 3px rgba(0,0,0,0.05)
--shadow-md: 0 2px 8px rgba(0,0,0,0.1)
--shadow-lg: 0 4px 12px rgba(0,0,0,0.15)
--shadow-xl: 0 20px 25px rgba(0,0,0,0.2)

/* TRANSICIONES */
--transition-fast: 0.15s ease
--transition-normal: 0.3s ease
--transition-slow: 0.5s ease
```

---

## 📱 RESPONSIVE BREAKPOINTS

```css
/* Mobile First */
$mobile: 320px
$tablet: 768px
$desktop: 1200px
$wide: 1400px

/* Ejemplo */
@media (min-width: 768px) {
  .sidebar {
    width: 280px;  /* Desktop */
  }
}

@media (max-width: 767px) {
  .sidebar {
    position: fixed;
    left: -280px;   /* Off-canvas */
    top: 64px;
    z-index: 1000;
  }
  
  .sidebar.open {
    left: 0;
    box-shadow: 2px 0 8px rgba(0,0,0,0.2);
  }
}
```

---

## 🚀 PASOS PARA IMPLEMENTAR

### 1. Copia el PROMPT para SUPERADMIN
```
Selecciona todo el contenido entre "---" del primer prompt.
Copia y pega en ChatGPT, Claude o Perplexity.
Elige el framework: React, Vue, Next.js, etc.
Pide que genere el código completo.
```

### 2. Copia el PROMPT para ADMIN AGENCIA
```
Repetir lo anterior con el segundo prompt (verde).
```

### 3. Pide a IA que genere componentes reutilizables
```
"Crea componentes reutilizables (Card, Button, Input, Table, Modal) 
basados en las especificaciones de color y tipografía anteriores."
```

### 4. Integra con API de Antigravity
```
"Conecta los componentes con endpoints de Antigravity 
usando JWT auth. Los datos deben llegar desde API."
```

---

## 💡 TIPS PARA USAR ESTOS PROMPTS

✅ **Sé específico:** Copia y pega TODO el prompt (no resuman)
✅ **Elige framework:** Antes de usar, especifica React/Vue/Next.js
✅ **Pide iteraciones:** "Ahora añade responsive design"
✅ **Solicita componentes:** Por separado (Card, Button, Table, etc)
✅ **Prueba en navegador:** Abre archivo HTML generado
✅ **Customiza:** Ajusta colores según tu marca si es necesario

---

## 🎯 RESUMEN FINAL

**Tienes:**
- ✅ Dos temas de color completos (Oscuro + Verde)
- ✅ Tipografía profesional definida
- ✅ Componentes UI especificados
- ✅ Prompts listos para IA
- ✅ CSS variables copiar-pegar
- ✅ HTML básico de componentes
- ✅ Design tokens

**Para empezar:**
1. Elige tu framework favorito (React recomendado)
2. Copia el prompt correspondiente
3. Pégalo en ChatGPT/Claude
4. Ajusta según necesites
5. Integra con API de Antigravity

**Tiempo estimado:** 2-3 días para UI completa con IA

---

**¿LISTO? Copia un prompt y comienza a generar!** 🚀
