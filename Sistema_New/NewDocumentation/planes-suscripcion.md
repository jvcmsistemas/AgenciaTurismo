# 📊 PLANES DE SUSCRIPCIÓN - GUÍA COMPLETA IMPLEMENTACIÓN

**Fecha:** 8 Diciembre 2025  
**Status:** ✅ LISTO PARA IMPLEMENTAR  
**Basado en:** Base de datos agencia_turismo_db

---

## 🎯 OVERVIEW GENERAL

La tabla `agencias` contiene un campo `tiposuscripcion` que guarda qué plan tiene cada agencia.

Actualmente en la BD tienes **3 planes principales**:
- `prueba` (Trial)
- `semestral` (6 meses)
- `anual` (12 meses)

---

## 📋 TABLA: AGENCIAS (Estructura Actual)

```sql
CREATE TABLE agencias (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(100) NOT NULL,
  direccion varchar(255),
  telefono varchar(20),
  email varchar(100),
  estado enum('activa','inactiva','suspendida') DEFAULT 'activa',
  tiposuscripcion enum('prueba','semestral','anual') DEFAULT 'prueba',
  fechavencimiento datetime DEFAULT NULL,
  duenoid int(11) DEFAULT NULL,
  FOREIGN KEY (duenoid) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Campos Relevantes:
```
tiposuscripcion:  Qué plan tiene la agencia
fechavencimiento: Cuándo vence el plan (CRÍTICO para auditoría)
estado:           Si está activa, inactiva o suspendida
```

---

## 🆕 TABLA RECOMENDADA: PLANES (NUEVA - Para mejor control)

Para una mejor arquitectura, te recomiendo crear una tabla separada `planes`:

```sql
-- CREAR TABLA PLANES
CREATE TABLE planes (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  codigo varchar(20) NOT NULL UNIQUE COMMENT 'prueba, semestral, anual',
  nombre varchar(100) NOT NULL COMMENT 'Nombre visible para usuarios',
  descripcion text COMMENT 'Descripción del plan',
  
  -- PRECIOS
  precio decimal(10, 2) NOT NULL DEFAULT 0 COMMENT 'Precio en USD/Moneda',
  duracionmeses int(11) NOT NULL DEFAULT 1 COMMENT 'Duración del plan en meses',
  
  -- LÍMITES & CARACTERÍSTICAS
  limiteclientes int(11) DEFAULT NULL COMMENT 'NULL = ilimitado',
  limitetours int(11) DEFAULT NULL COMMENT 'NULL = ilimitado',
  limiteusuarios int(11) DEFAULT NULL COMMENT 'NULL = ilimitado',
  limiteempleados int(11) DEFAULT NULL COMMENT 'NULL = ilimitado',
  
  -- CARACTERÍSTICAS (BOOLEANS)
  incuye_auditorias tinyint(1) DEFAULT 1,
  incluye_reportes tinyint(1) DEFAULT 1,
  incluye_api tinyint(1) DEFAULT 0,
  incluye_integraciones tinyint(1) DEFAULT 0 COMMENT 'WhatsApp, Email, etc',
  incluye_soporte_premium tinyint(1) DEFAULT 0,
  incluye_backup_automatico tinyint(1) DEFAULT 0,
  
  -- ORDENAMIENTO & VISIBILIDAD
  orden int(11) DEFAULT 0 COMMENT 'Para ordenar en UI',
  activo tinyint(1) DEFAULT 1,
  destacado tinyint(1) DEFAULT 0 COMMENT 'Plan recomendado',
  
  -- AUDITORÍA
  createdat timestamp DEFAULT CURRENT_TIMESTAMP,
  updatedat timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Datos Iniciales:

```sql
-- INSERTAR PLANES
INSERT INTO planes (
  codigo, nombre, descripcion, precio, duracionmeses,
  limiteclientes, limitetours, limiteusuarios, limiteempleados,
  incuye_auditorias, incluye_reportes, incluye_api, incluye_integraciones,
  incluye_soporte_premium, incluye_backup_automatico,
  orden, activo, destacado
) VALUES

-- Plan PRUEBA
('prueba', 
 'Plan Prueba',
 'Ideal para agencias nuevas que quieren explorar el sistema',
 0.00,
 1,
 10,
 5,
 2,
 1,
 1, 1, 0, 0, 0, 0,
 1, 1, 0),

-- Plan SEMESTRAL
('semestral',
 'Plan Semestral',
 'Opción popular: 6 meses de acceso completo. Descuento vs anual.',
 150.00,
 6,
 NULL,
 50,
 5,
 10,
 1, 1, 1, 0, 1, 0,
 2, 1, 1),

-- Plan ANUAL
('anual',
 'Plan Anual',
 'Mejor inversión: acceso ilimitado con todas las características.',
 250.00,
 12,
 NULL,
 NULL,
 NULL,
 NULL,
 1, 1, 1, 1, 1, 1,
 3, 1, 0);
```

---

## 🔗 RELACIÓN: AGENCIAS ↔ PLANES

Ahora la tabla `agencias` quedaría así:

```sql
-- MODIFICAR TABLA AGENCIAS (Opción A - Recomendada)
ALTER TABLE agencias
ADD COLUMN planid int(11) DEFAULT NULL,
MODIFY tiposuscripcion varchar(20) COMMENT 'Por referencia backward-compatibility',
ADD FOREIGN KEY (planid) REFERENCES planes(id) ON DELETE SET NULL;

-- Ahora la estructura sería:
CREATE TABLE agencias (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(100) NOT NULL,
  direccion varchar(255),
  telefono varchar(20),
  email varchar(100),
  estado enum('activa','inactiva','suspendida') DEFAULT 'activa',
  
  -- NUEVA FORMA
  planid int(11) DEFAULT NULL,
  
  -- LEGACY (mantener por compatibilidad)
  tiposuscripcion varchar(20) DEFAULT 'prueba',
  fechavencimiento datetime DEFAULT NULL,
  
  duenoid int(11) DEFAULT NULL,
  createdat timestamp DEFAULT CURRENT_TIMESTAMP,
  updatedat timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (duenoid) REFERENCES usuarios(id),
  FOREIGN KEY (planid) REFERENCES planes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 📊 DATOS ACTUALES EN BD

Actualmente tienes estas agencias con estos planes:

```
┌────┬─────────────────────────────────────────────┬──────────┬──────────────┐
│ id │ nombre                                      │ plan     │ vencimiento  │
├────┼─────────────────────────────────────────────┼──────────┼──────────────┤
│ 1  │ Agencia Demo                                │ semestral│ 2026-06-02   │
│ 2  │ APKTour                                     │ anual    │ 2026-12-02   │
│ 3  │ Agencia Oxapampa selva tour y centro Peru  │ prueba   │ 2026-01-02   │
│ 4  │ Agencia 001                                 │ prueba   │ 2026-01-03   │
│ 5  │ Oxa                                         │ prueba   │ 2026-01-03   │
│ 6  │ Agencia 003                                 │ prueba   │ 2026-01-04   │
└────┴─────────────────────────────────────────────┴──────────┴──────────────┘

RESUMEN:
- 1 agencia en plan SEMESTRAL
- 1 agencia en plan ANUAL
- 4 agencias en plan PRUEBA
```

---

## 🎨 TABLA PLANES - CARACTERÍSTICAS DETALLADAS

| Plan | Prueba | Semestral | Anual |
|------|--------|-----------|-------|
| **Precio** | Gratis ($0) | $150 | $250 |
| **Duración** | 1 mes | 6 meses | 12 meses |
| **Clientes** | Máx 10 | Ilimitado | Ilimitado |
| **Tours** | Máx 5 | Máx 50 | Ilimitado |
| **Usuarios** | Máx 2 | Máx 5 | Ilimitado |
| **Empleados** | Máx 1 | Máx 10 | Ilimitado |
| **Auditoría** | ✅ Sí | ✅ Sí | ✅ Sí |
| **Reportes** | ✅ Sí | ✅ Sí | ✅ Sí |
| **API REST** | ❌ No | ✅ Sí | ✅ Sí |
| **Integraciones** | ❌ No | ❌ No | ✅ Sí (WhatsApp, Email) |
| **Soporte** | Email | Email | Premium 24/7 |
| **Backup Automático** | ❌ No | ❌ No | ✅ Sí |

---

## 🛠️ VISTA SQL: AGENCIAS CON PLANES DETALLADOS

```sql
-- Vista para ver agencias con detalles del plan
CREATE VIEW v_agencias_con_planes AS
SELECT
  a.id,
  a.nombre AS agencia,
  a.email,
  a.estado,
  
  -- Datos del plan
  p.codigo AS plan_tipo,
  p.nombre AS plan_nombre,
  p.precio,
  p.duracionmeses,
  p.limiteclientes,
  p.limitetours,
  p.limiteusuarios,
  p.incluye_api,
  p.incluye_integraciones,
  
  -- Vencimiento
  a.fechavencimiento,
  CASE 
    WHEN a.fechavencimiento < NOW() THEN 'VENCIDO'
    WHEN DATEDIFF(a.fechavencimiento, NOW()) <= 7 THEN 'POR VENCER'
    ELSE 'ACTIVO'
  END AS estado_suscripcion,
  
  DATEDIFF(a.fechavencimiento, NOW()) AS dias_restantes,
  
  -- Usuario dueño
  CONCAT(u.nombre, ' ', u.apellido) AS dueno_nombre,
  u.email AS dueno_email,
  
  -- Contador de recursos
  (SELECT COUNT(*) FROM clientes WHERE agenciaid = a.id) AS total_clientes,
  (SELECT COUNT(*) FROM tours WHERE agenciaid = a.id) AS total_tours,
  (SELECT COUNT(*) FROM usuarios WHERE agenciaid = a.id) AS total_usuarios
  
FROM agencias a
LEFT JOIN planes p ON a.planid = p.id
LEFT JOIN usuarios u ON a.duenoid = u.id
ORDER BY a.nombre;
```

---

## 🗺️ QUERIES ÚTILES PARA IMPLEMENTACIÓN

### 1️⃣ Ver todas las agencias con estado de suscripción

```sql
SELECT 
  id, nombre, plan_tipo, estado_suscripcion, dias_restantes, total_clientes
FROM v_agencias_con_planes
ORDER BY dias_restantes ASC;
```

### 2️⃣ Alertas: Planes que vencen en 7 días

```sql
SELECT 
  id, nombre, plan_nombre, fechavencimiento, dueno_email
FROM v_agencias_con_planes
WHERE estado_suscripcion = 'POR VENCER'
ORDER BY fechavencimiento ASC;
```

### 3️⃣ Uso de recursos vs límite

```sql
SELECT 
  a.nombre,
  p.nombre AS plan,
  
  -- Clientes
  (SELECT COUNT(*) FROM clientes WHERE agenciaid = a.id) AS clientes_usados,
  p.limiteclientes,
  CONCAT(ROUND((SELECT COUNT(*) FROM clientes WHERE agenciaid = a.id) / p.limiteclientes * 100), '%') 
    AS clientes_uso,
  
  -- Tours
  (SELECT COUNT(*) FROM tours WHERE agenciaid = a.id) AS tours_usados,
  p.limitetours,
  CONCAT(ROUND((SELECT COUNT(*) FROM tours WHERE agenciaid = a.id) / p.limitetours * 100), '%')
    AS tours_uso
    
FROM agencias a
LEFT JOIN planes p ON a.planid = p.id
WHERE a.estado = 'activa';
```

### 4️⃣ Ingresos por plan (Para Superadmin)

```sql
SELECT 
  p.nombre AS plan,
  COUNT(a.id) AS agencias,
  p.precio,
  p.duracionmeses,
  (COUNT(a.id) * p.precio) AS ingresos_totales
FROM agencias a
LEFT JOIN planes p ON a.planid = p.id
WHERE a.estado = 'activa'
GROUP BY p.id
ORDER BY ingresos_totales DESC;
```

---

## 💻 ENDPOINTS API (Antigravity)

Una vez conectes la BD a Antigravity, tendrás automáticamente:

### GET endpoints:
```
GET /planes                    → Listar todos los planes
GET /planes/{id}              → Detalles de un plan
GET /agencias                 → Listar agencias
GET /agencias/{id}            → Detalles agencia + plan
GET /v_agencias_con_planes    → Vista con detalles completos
```

### POST endpoints:
```
POST /planes                  → Crear nuevo plan (solo Superadmin)
POST /agencias                → Crear nueva agencia
POST /agencias/{id}/planes    → Cambiar plan de agencia
```

### PUT endpoints:
```
PUT /planes/{id}              → Actualizar plan
PUT /agencias/{id}            → Actualizar datos agencia (incl. plan)
```

### DELETE endpoints:
```
DELETE /planes/{id}           → Eliminar plan (cuidado: soft delete)
DELETE /agencias/{id}         → Eliminar agencia
```

---

## 🎨 COMPONENTES FRONTEND - REACT

### 1️⃣ Componente: Mostrar Plan Actual (Admin Agencia)

```jsx
// components/SubscriptionCard.jsx
import React, { useEffect, useState } from 'react';

export const SubscriptionCard = ({ agenciaid }) => {
  const [plan, setPlan] = useState(null);
  const [daysLeft, setDaysLeft] = useState(0);

  useEffect(() => {
    // Llamar a Antigravity API
    fetch(`/api/v_agencias_con_planes/${agenciaid}`)
      .then(res => res.json())
      .then(data => {
        setPlan(data[0]);
        setDaysLeft(data[0].dias_restantes);
      });
  }, [agenciaid]);

  if (!plan) return <div>Cargando...</div>;

  const isExpiring = daysLeft <= 7 && daysLeft > 0;
  const isExpired = daysLeft <= 0;

  return (
    <div className="card card-subscription">
      <div className="card-header">
        <h3>📅 Mi Suscripción</h3>
      </div>
      
      <div className="card-body">
        {/* Plan Actual */}
        <div className="subscription-info">
          <p className="plan-name">{plan.plan_nombre}</p>
          <p className="plan-price">
            ${plan.precio}
            <span className="plan-duration"> / {plan.duracionmeses} meses</span>
          </p>
        </div>

        {/* Vencimiento */}
        <div className="subscription-expiry">
          <p className="expiry-label">Vencimiento:</p>
          <p className={`expiry-date ${isExpiring ? 'warning' : isExpired ? 'error' : ''}`}>
            {new Date(plan.fechavencimiento).toLocaleDateString('es-PE')}
          </p>
          
          <p className={`days-left ${isExpiring ? 'warning' : isExpired ? 'error' : 'success'}`}>
            {isExpired ? '❌ VENCIDO' : isExpiring ? `⚠️ ${daysLeft} días restantes` : `✅ ${daysLeft} días restantes`}
          </p>
        </div>

        {/* Barra de Progreso */}
        {plan.duracionmeses && (
          <div className="subscription-progress">
            <div className="progress-bar">
              <div 
                className="progress-fill" 
                style={{ width: `${(daysLeft / (plan.duracionmeses * 30)) * 100}%` }}
              ></div>
            </div>
          </div>
        )}

        {/* Características */}
        <div className="subscription-features">
          <h4>Características:</h4>
          <ul>
            <li>{plan.incluye_api ? '✅' : '❌'} API REST</li>
            <li>{plan.incluye_integraciones ? '✅' : '❌'} Integraciones (WhatsApp, Email)</li>
            <li>{plan.limiteclientes ? `👥 Máx ${plan.limiteclientes} clientes` : '👥 Clientes ilimitados'}</li>
            <li>{plan.limitetours ? `🎫 Máx ${plan.limitetours} tours` : '🎫 Tours ilimitados'}</li>
          </ul>
        </div>

        {/* Botones */}
        <div className="subscription-actions">
          {!isExpired && <button className="btn btn-primary">Renovar Plan</button>}
          {isExpired && <button className="btn btn-primary">Activar Nuevo Plan</button>}
          <button className="btn btn-secondary">Ver Planes Disponibles</button>
        </div>
      </div>

      <style>{`
        .card-subscription {
          background: linear-gradient(135deg, #10b981 0%, #059669 100%);
          border: none;
          color: white;
        }

        .subscription-info {
          margin-bottom: 20px;
          padding-bottom: 20px;
          border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .plan-name {
          font-size: 20px;
          font-weight: bold;
          margin: 0;
        }

        .plan-price {
          font-size: 28px;
          font-weight: bold;
          margin: 5px 0 0 0;
        }

        .plan-duration {
          font-size: 14px;
          opacity: 0.8;
        }

        .subscription-expiry {
          margin-bottom: 20px;
        }

        .expiry-label {
          font-size: 12px;
          opacity: 0.8;
          margin-bottom: 5px;
        }

        .expiry-date {
          font-size: 16px;
          font-weight: bold;
          margin: 0;
        }

        .days-left {
          font-size: 14px;
          margin-top: 5px;
        }

        .days-left.warning {
          color: #fbbf24;
        }

        .days-left.error {
          color: #ef4444;
        }

        .days-left.success {
          color: #34d399;
        }

        .progress-bar {
          width: 100%;
          height: 8px;
          background: rgba(255,255,255,0.2);
          border-radius: 4px;
          overflow: hidden;
          margin: 15px 0;
        }

        .progress-fill {
          height: 100%;
          background: #34d399;
          transition: width 0.3s ease;
        }

        .subscription-features {
          margin: 20px 0;
          padding: 15px;
          background: rgba(0,0,0,0.1);
          border-radius: 6px;
        }

        .subscription-features h4 {
          margin-top: 0;
          font-size: 13px;
          opacity: 0.9;
        }

        .subscription-features ul {
          list-style: none;
          padding: 0;
          margin: 0;
          font-size: 13px;
        }

        .subscription-features li {
          padding: 5px 0;
        }

        .subscription-actions {
          display: flex;
          gap: 10px;
          margin-top: 20px;
        }

        .subscription-actions button {
          flex: 1;
        }
      `}</style>
    </div>
  );
};
```

### 2️⃣ Componente: Tabla Planes (Superadmin)

```jsx
// components/PlanesTable.jsx
import React, { useEffect, useState } from 'react';

export const PlanesTable = () => {
  const [planes, setPlanes] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch('/api/planes')
      .then(res => res.json())
      .then(data => {
        setPlanes(data);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Cargando planes...</div>;

  return (
    <div className="table-container">
      <table className="table">
        <thead>
          <tr>
            <th>Plan</th>
            <th>Precio</th>
            <th>Duración</th>
            <th>Clientes</th>
            <th>Tours</th>
            <th>Usuarios</th>
            <th>API</th>
            <th>Integraciones</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {planes.map(plan => (
            <tr key={plan.id} className={plan.destacado ? 'highlighted' : ''}>
              <td>
                <strong>{plan.nombre}</strong>
                <br />
                <small>{plan.descripcion}</small>
              </td>
              <td className="text-right">${plan.precio}</td>
              <td>{plan.duracionmeses} mes(es)</td>
              <td>{plan.limiteclientes ? `Máx ${plan.limiteclientes}` : 'Ilimitado'}</td>
              <td>{plan.limitetours ? `Máx ${plan.limitetours}` : 'Ilimitado'}</td>
              <td>{plan.limiteusuarios ? `Máx ${plan.limiteusuarios}` : 'Ilimitado'}</td>
              <td>{plan.incluye_api ? '✅' : '❌'}</td>
              <td>{plan.incluye_integraciones ? '✅' : '❌'}</td>
              <td>
                <button className="btn-sm btn-primary">Editar</button>
                <button className="btn-sm btn-secondary">Ver Agencias</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};
```

### 3️⃣ Componente: Grid de Planes (Landing Page)

```jsx
// components/PricingGrid.jsx
import React from 'react';

export const PricingGrid = ({ planes }) => {
  return (
    <div className="pricing-grid">
      {planes.map(plan => (
        <div 
          key={plan.id} 
          className={`pricing-card ${plan.destacado ? 'featured' : ''}`}
        >
          {plan.destacado && <div className="badge-popular">POPULAR</div>}
          
          <h3>{plan.nombre}</h3>
          <p className="plan-description">{plan.descripcion}</p>
          
          <div className="price">
            <span className="currency">$</span>
            <span className="amount">{plan.precio}</span>
            <span className="period">/{plan.duracionmeses} meses</span>
          </div>
          
          <button className="btn btn-primary btn-full">
            Seleccionar Plan
          </button>
          
          <div className="features">
            <h4>Características:</h4>
            <ul>
              <li>
                <span>{plan.limiteclientes ? `Máx ${plan.limiteclientes}` : 'Ilimitados'}</span>
                Clientes
              </li>
              <li>
                <span>{plan.limitetours ? `Máx ${plan.limitetours}` : 'Ilimitados'}</span>
                Tours
              </li>
              <li>
                <span>{plan.limiteusuarios ? `Máx ${plan.limiteusuarios}` : 'Ilimitados'}</span>
                Usuarios
              </li>
              <li className={plan.incluye_api ? 'included' : 'excluded'}>
                {plan.incluye_api ? '✅' : '❌'} API REST
              </li>
              <li className={plan.incluye_integraciones ? 'included' : 'excluded'}>
                {plan.incluye_integraciones ? '✅' : '❌'} Integraciones
              </li>
              <li className={plan.incluye_soporte_premium ? 'included' : 'excluded'}>
                {plan.incluye_soporte_premium ? '✅' : '❌'} Soporte Premium
              </li>
            </ul>
          </div>
        </div>
      ))}
    </div>
  );
};
```

---

## 🔐 VALIDACIONES EN FRONTEND

### Validar límites de recursos:

```javascript
// hooks/useResourceLimits.js
import { useEffect, useState } from 'react';

export const useResourceLimits = (agenciaid) => {
  const [limits, setLimits] = useState({
    clientes: { usado: 0, limite: Infinity, alerta: false },
    tours: { usado: 0, limite: Infinity, alerta: false },
    usuarios: { usado: 0, limite: Infinity, alerta: false }
  });

  useEffect(() => {
    // Obtener plan
    fetch(`/api/agencias/${agenciaid}`)
      .then(res => res.json())
      .then(agencia => {
        const plan = agencia.plan;
        
        // Obtener recursos usados
        Promise.all([
          fetch(`/api/clientes?agenciaid=${agenciaid}&limit=1`),
          fetch(`/api/tours?agenciaid=${agenciaid}&limit=1`),
          fetch(`/api/usuarios?agenciaid=${agenciaid}&limit=1`)
        ])
        .then(([resClientes, resTours, resUsuarios]) => 
          Promise.all([resClientes.json(), resTours.json(), resUsuarios.json()])
        )
        .then(([clientes, tours, usuarios]) => {
          setLimits({
            clientes: {
              usado: clientes.count,
              limite: plan.limiteclientes || Infinity,
              alerta: plan.limiteclientes && clientes.count >= plan.limiteclientes * 0.8
            },
            tours: {
              usado: tours.count,
              limite: plan.limitetours || Infinity,
              alerta: plan.limitetours && tours.count >= plan.limitetours * 0.8
            },
            usuarios: {
              usado: usuarios.count,
              limite: plan.limiteusuarios || Infinity,
              alerta: plan.limiteusuarios && usuarios.count >= plan.limiteusuarios * 0.8
            }
          });
        });
      });
  }, [agenciaid]);

  return limits;
};
```

### Usar el hook:

```jsx
const App = () => {
  const limits = useResourceLimits(5); // ID agencia 5
  
  if (limits.tours.alerta) {
    return (
      <Alert type="warning">
        ⚠️ Estás usando {limits.tours.usado}/{limits.tours.limite} tours.
        Actualiza tu plan para crear más tours.
      </Alert>
    );
  }
};
```

---

## 📈 DASHBOARD SUPERADMIN - Secciones de Planes

### Panel Superior (KPIs):

```
┌────────────────────┬────────────────────┬────────────────────┬────────────────────┐
│ 💰 Ingresos Planes │ 🏢 Agencias Activas│ 📅 Próx Vencimientos│ 🎯 Conversión Plan │
├────────────────────┼────────────────────┼────────────────────┼────────────────────┤
│ $8,500 este mes    │ 4 / 6 agencias     │ 2 en próx 7 días   │ 67% → Plan Pagado  │
└────────────────────┴────────────────────┴────────────────────┴────────────────────┘
```

### Tabla de Planes:

```
Nombre    | Precio | Duracion | Agencias | Ingresos | Conversión
----------|--------|----------|----------|----------|----------
Prueba    | $0     | 1 mes    | 4        | $0       | 50%
Semestral | $150   | 6 meses  | 1        | $150     | 25%
Anual     | $250   | 12 meses | 1        | $250     | 25%
```

### Alertas de Vencimiento:

```
Agencia                                  Plan       Vencimiento   Días
Agencia Demo                             Semestral  2026-06-02    180 días
Agencia Oxapampa selva tour y centro     Prueba     2026-01-02    25 días ⚠️
```

---

## 🔄 FLUJO DE CAMBIO DE PLAN

```
1. Admin Agencia ve su plan actual
2. Click en "Cambiar Plan" o "Renovar"
3. Muestra grid de planes disponibles
4. Selecciona nuevo plan
5. Calcula costo (pro-rata si existe plan anterior)
6. Procesa pago (si no es prueba)
7. Actualiza BD:
   - agencias.planid = nuevo_plan_id
   - agencias.fechavencimiento = NOW() + duracionmeses
8. Registra en auditoría
9. Envía confirmación por email
```

---

## 📧 EMAIL DE CONFIRMACIÓN

```html
Asunto: Tu suscripción a {{plan_nombre}} ha sido activada

Hola {{agencia_nombre}},

Tu plan ha sido actualizado exitosamente.

📋 Detalles de tu suscripción:

Plan: {{plan_nombre}}
Precio: ${{plan_precio}}
Duración: {{plan_duracionmeses}} meses
Vencimiento: {{fecha_vencimiento}}

📊 Límites de tu plan:
- Clientes: {{limite_clientes || 'Ilimitado'}}
- Tours: {{limite_tours || 'Ilimitado'}}
- Usuarios: {{limite_usuarios || 'Ilimitado'}}
- API: {{incluye_api ? 'Habilitado' : 'No incluido'}}
- Integraciones: {{incluye_integraciones ? 'Habilitado' : 'No incluido'}}

Si tienes preguntas, contáctanos a soporte@agenciasoft.com

¡Bienvenido!
Equipo AgenciaSoft
```

---

## 🚀 PRÓXIMOS PASOS

### PASO 1: Crear tabla `planes` en BD (Hoy)
```bash
Ejecutar SQL anterior en phpMyAdmin
```

### PASO 2: Migrar datos (Hoy)
```sql
-- Asignar planes a agencias existentes
UPDATE agencias SET planid = 1 WHERE tiposuscripcion = 'prueba';
UPDATE agencias SET planid = 2 WHERE tiposuscripcion = 'semestral';
UPDATE agencias SET planid = 3 WHERE tiposuscripcion = 'anual';
```

### PASO 3: Conectar en Antigravity (Mañana)
```
- Agregar tabla `planes` a conexión
- Generar API endpoints para planes
```

### PASO 4: Implementar componentes React (Semana 1)
```
- SubscriptionCard (mostrar plan actual)
- PlanesTable (Superadmin)
- PricingGrid (página pública)
- useResourceLimits (validar límites)
```

### PASO 5: Integración con pagos (Semana 2)
```
- Stripe / MercadoPago
- PayPal
- Transferencia bancaria
```

---

## 💡 TIPS & BEST PRACTICES

✅ **Siempre validar límites** antes de crear recursos
✅ **Mostrar advertencia al 80%** de límite alcanzado
✅ **Auditorizar cambios de plan** (quién, cuándo, por qué)
✅ **Enviar email 7 días antes** de vencimiento
✅ **Auto-renovar planes** (con opción de deshabilitar)
✅ **Permitir downgrade** de plan (con pro-rata)
✅ **Soft delete en planes** (nunca eliminar, solo inactivar)

---

## 🎯 RESUMEN

**Tienes:**
- ✅ Tabla `agencias` con campo `tiposuscripcion`
- ✅ 3 planes básicos (prueba, semestral, anual)
- ✅ Query para obtener agencias con detalles de plan
- ✅ Componentes React listos para implementar

**Para empezar:**
1. Crea tabla `planes` en BD
2. Conecta en Antigravity
3. Implementa componentes React
4. Integra validación de límites
5. Configura pagos

**¡Listo para ir a producción!** 🚀
