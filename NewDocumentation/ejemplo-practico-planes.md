# 💻 EJEMPLO PRÁCTICO: Integrar Planes en 4 Pasos

**Objetivo:** Implementar planes de suscripción en tu sistema paso a paso

---

## 🎯 PASO 1: Preparar Base de Datos (20 min)

### 1.1 Crear tabla `planes`

Abre **phpMyAdmin** y ejecuta esto:

```sql
-- Crear tabla planes
CREATE TABLE planes (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  codigo varchar(20) NOT NULL UNIQUE COMMENT 'prueba, semestral, anual',
  nombre varchar(100) NOT NULL COMMENT 'Nombre visible',
  descripcion text COMMENT 'Descripción del plan',
  precio decimal(10, 2) NOT NULL DEFAULT 0,
  duracionmeses int(11) NOT NULL DEFAULT 1,
  
  -- Límites (NULL = ilimitado)
  limiteclientes int(11) DEFAULT NULL,
  limitetours int(11) DEFAULT NULL,
  limiteusuarios int(11) DEFAULT NULL,
  limiteempleados int(11) DEFAULT NULL,
  
  -- Características
  incluye_auditorias tinyint(1) DEFAULT 1,
  incluye_reportes tinyint(1) DEFAULT 1,
  incluye_api tinyint(1) DEFAULT 0,
  incluye_integraciones tinyint(1) DEFAULT 0,
  incluye_soporte_premium tinyint(1) DEFAULT 0,
  incluye_backup_automatico tinyint(1) DEFAULT 0,
  
  -- Admin
  orden int(11) DEFAULT 0,
  activo tinyint(1) DEFAULT 1,
  destacado tinyint(1) DEFAULT 0,
  
  createdat timestamp DEFAULT CURRENT_TIMESTAMP,
  updatedat timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
) COLLATE=utf8mb4_general_ci;
```

**Tiempo:** 2 min ⏱️

### 1.2 Insertar los 3 planes

```sql
-- Insertar planes iniciales
INSERT INTO planes (codigo, nombre, descripcion, precio, duracionmeses, 
  limiteclientes, limitetours, limiteusuarios, limiteempleados,
  incluye_auditorias, incluye_reportes, incluye_api, incluye_integraciones,
  incluye_soporte_premium, incluye_backup_automatico,
  orden, activo, destacado) VALUES

-- Plan PRUEBA
('prueba', 
 'Plan Prueba',
 'Ideal para agencias nuevas que quieren explorar el sistema por 1 mes',
 0.00,
 1,
 10, 5, 2, 1,
 1, 1, 0, 0, 0, 0,
 1, 1, 0),

-- Plan SEMESTRAL (POPULAR)
('semestral',
 'Plan Semestral',
 'Acceso completo por 6 meses. Opción más popular y recomendada.',
 150.00,
 6,
 NULL, 50, 5, 10,
 1, 1, 1, 0, 1, 0,
 2, 1, 1),

-- Plan ANUAL
('anual',
 'Plan Anual',
 'Inversión completa: acceso ilimitado a todas las características.',
 250.00,
 12,
 NULL, NULL, NULL, NULL,
 1, 1, 1, 1, 1, 1,
 3, 1, 0);
```

**Resultado esperado:**
```
✅ Insertados 3 planes exitosamente
```

**Tiempo:** 2 min ⏱️

### 1.3 Crear vista para Superadmin

```sql
-- Vista: Agencias con detalles de planes
CREATE VIEW v_agencias_con_planes AS
SELECT
  a.id,
  a.nombre AS agencia,
  a.email,
  a.estado,
  
  p.codigo AS plan_tipo,
  p.nombre AS plan_nombre,
  p.precio,
  p.duracionmeses,
  p.limiteclientes,
  p.limitetours,
  p.limiteusuarios,
  p.incluye_api,
  p.incluye_integraciones,
  
  a.fechavencimiento,
  CASE 
    WHEN a.fechavencimiento < NOW() THEN 'VENCIDO'
    WHEN DATEDIFF(a.fechavencimiento, NOW()) <= 7 THEN 'POR VENCER'
    ELSE 'ACTIVO'
  END AS estado_suscripcion,
  
  DATEDIFF(a.fechavencimiento, NOW()) AS dias_restantes,
  
  CONCAT(u.nombre, ' ', u.apellido) AS dueno_nombre,
  u.email AS dueno_email,
  
  (SELECT COUNT(*) FROM clientes WHERE agenciaid = a.id) AS total_clientes,
  (SELECT COUNT(*) FROM tours WHERE agenciaid = a.id) AS total_tours,
  (SELECT COUNT(*) FROM usuarios WHERE agenciaid = a.id) AS total_usuarios
  
FROM agencias a
LEFT JOIN planes p ON a.planid = p.id
LEFT JOIN usuarios u ON a.duenoid = u.id
ORDER BY a.nombre;
```

**Tiempo:** 1 min ⏱️

### 1.4 Verificar

En phpMyAdmin:
```
1. Ir a Base de datos: agencia_turismo_db
2. Ver tabla: planes → Debes ver 3 filas
3. Ver vista: v_agencias_con_planes → Debes ver agencias con planes
```

**Tiempo:** 5 min ⏱️

---

## 💻 PASO 2: Conectar en Antigravity (30 min)

### 2.1 Agregar tabla a conexión

1. Abre **https://antigravity.app**
2. Ve a **Database Connections**
3. Selecciona tu conexión MySQL
4. Click **Refresh Tables**
5. Verifica que aparezca tabla `planes`

**Tiempo:** 5 min ⏱️

### 2.2 Generar API automática

1. Ve a **API Builder**
2. Antigravity debe haber generado automáticamente endpoints para `planes`
3. Verifica que aparezcan:
   - `GET /planes`
   - `GET /planes/{id}`
   - `POST /planes`
   - `PUT /planes/{id}`

**Tiempo:** 2 min ⏱️

### 2.3 Probar endpoints

Abre **Postman** y prueba:

```
GET http://tu-api.antigravity.app/api/planes
Authorization: Bearer YOUR_JWT_TOKEN

Respuesta esperada:
[
  {
    "id": 1,
    "codigo": "prueba",
    "nombre": "Plan Prueba",
    "precio": 0,
    ...
  },
  {
    "id": 2,
    "codigo": "semestral",
    "nombre": "Plan Semestral",
    "precio": 150,
    ...
  },
  ...
]
```

**Tiempo:** 5 min ⏱️

### 2.4 Actualizar agencias existentes

```sql
-- Asignar planes a agencias existentes
UPDATE agencias SET planid = 1 WHERE tiposuscripcion = 'prueba';
UPDATE agencias SET planid = 2 WHERE tiposuscripcion = 'semestral';
UPDATE agencias SET planid = 3 WHERE tiposuscripcion = 'anual';
```

**Tiempo:** 2 min ⏱️

---

## 🎨 PASO 3: Implementar Componentes React (2-3 horas)

### 3.1 Componente: SubscriptionCard

Crea archivo: `src/components/SubscriptionCard.jsx`

```jsx
import React, { useEffect, useState } from 'react';
import axios from 'axios';

export const SubscriptionCard = ({ agenciaid, token }) => {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `https://tu-api.antigravity.app/api/v_agencias_con_planes`,
          {
            headers: { Authorization: `Bearer ${token}` }
          }
        );
        
        const agencia = response.data.find(a => a.id === agenciaid);
        setData(agencia);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [agenciaid, token]);

  if (loading) return <div className="card-loading">Cargando...</div>;
  if (error) return <div className="card-error">Error: {error}</div>;
  if (!data) return <div className="card-error">No hay datos</div>;

  const daysLeft = data.dias_restantes;
  const isExpiring = daysLeft <= 7 && daysLeft > 0;
  const isExpired = daysLeft <= 0;
  const statusClass = isExpired ? 'expired' : isExpiring ? 'warning' : 'active';

  return (
    <div className={`card card-subscription ${statusClass}`}>
      <div className="card-header">
        <h3>📅 Mi Suscripción</h3>
      </div>
      
      <div className="card-body">
        {/* Información del Plan */}
        <div className="subscription-info">
          <p className="plan-name">{data.plan_nombre}</p>
          <p className="plan-price">
            ${data.precio}
            <span className="plan-duration"> / {data.duracionmeses} meses</span>
          </p>
        </div>

        {/* Vencimiento */}
        <div className="subscription-expiry">
          <p className="expiry-label">Vencimiento:</p>
          <p className="expiry-date">
            {new Date(data.fechavencimiento).toLocaleDateString('es-PE', {
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })}
          </p>
          
          <p className={`days-left ${statusClass}`}>
            {isExpired ? '❌ VENCIDO' : 
             isExpiring ? `⚠️ ${daysLeft} días restantes` : 
             `✅ ${daysLeft} días restantes`}
          </p>
        </div>

        {/* Barra de Progreso */}
        <div className="subscription-progress">
          <div className="progress-bar">
            <div 
              className="progress-fill" 
              style={{ 
                width: `${Math.min((daysLeft / (data.duracionmeses * 30)) * 100, 100)}%` 
              }}
            ></div>
          </div>
        </div>

        {/* Características */}
        <div className="subscription-features">
          <h4>✨ Características incluidas:</h4>
          <ul>
            <li>
              {data.incluye_api ? '✅' : '❌'} 
              <span>API REST</span>
            </li>
            <li>
              {data.incluye_integraciones ? '✅' : '❌'} 
              <span>Integraciones (WhatsApp, Email)</span>
            </li>
            <li>
              👥 
              <span>
                {data.limiteclientes ? `Máx ${data.limiteclientes}` : 'Clientes ilimitados'}
              </span>
            </li>
            <li>
              🎫 
              <span>
                {data.limitetours ? `Máx ${data.limitetours}` : 'Tours ilimitados'}
              </span>
            </li>
          </ul>
        </div>

        {/* Botones */}
        <div className="subscription-actions">
          {!isExpired && (
            <button className="btn btn-primary">
              🔄 Renovar Plan
            </button>
          )}
          {isExpired && (
            <button className="btn btn-primary">
              ⚡ Activar Nuevo Plan
            </button>
          )}
          <button className="btn btn-secondary">
            📋 Ver Planes Disponibles
          </button>
        </div>
      </div>

      <style>{`
        .card-subscription {
          background: linear-gradient(135deg, #10b981 0%, #059669 100%);
          border: none;
          color: white;
          border-radius: 12px;
          box-shadow: 0 4px 12px rgba(16,185,129,0.2);
        }

        .card-subscription.warning {
          background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
          box-shadow: 0 4px 12px rgba(245,158,11,0.2);
        }

        .card-subscription.expired {
          background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
          box-shadow: 0 4px 12px rgba(239,68,68,0.2);
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
          margin: 10px 0 0 0;
        }

        .plan-duration {
          font-size: 14px;
          opacity: 0.8;
        }

        .subscription-features h4 {
          margin: 15px 0 10px 0;
          font-size: 13px;
        }

        .subscription-features ul {
          list-style: none;
          padding: 0;
          margin: 0;
          font-size: 13px;
        }

        .subscription-features li {
          display: flex;
          gap: 8px;
          padding: 5px 0;
        }

        .subscription-actions {
          display: flex;
          gap: 10px;
          margin-top: 20px;
        }

        .subscription-actions button {
          flex: 1;
          padding: 10px 16px;
          border: none;
          border-radius: 6px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .btn-primary {
          background: rgba(255,255,255,0.2);
          color: white;
          border: 2px solid white;
        }

        .btn-primary:hover {
          background: rgba(255,255,255,0.3);
          transform: translateY(-2px);
        }

        .btn-secondary {
          background: rgba(0,0,0,0.1);
          color: white;
          border: none;
        }

        .btn-secondary:hover {
          background: rgba(0,0,0,0.2);
          transform: translateY(-2px);
        }
      `}</style>
    </div>
  );
};
```

**Tiempo:** 45 min ⏱️

### 3.2 Hook: useResourceLimits

Crea archivo: `src/hooks/useResourceLimits.js`

```javascript
import { useEffect, useState } from 'react';
import axios from 'axios';

export const useResourceLimits = (agenciaid, token) => {
  const [limits, setLimits] = useState({
    clientes: { usado: 0, limite: Infinity, porcentaje: 0, alerta: false },
    tours: { usado: 0, limite: Infinity, porcentaje: 0, alerta: false },
    usuarios: { usado: 0, limite: Infinity, porcentaje: 0, alerta: false }
  });

  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchLimits = async () => {
      try {
        // Obtener plan de agencia
        const agenciaRes = await axios.get(
          `https://tu-api.antigravity.app/api/agencias/${agenciaid}`,
          { headers: { Authorization: `Bearer ${token}` } }
        );
        const plan = agenciaRes.data;

        // Obtener conteo de recursos
        const [clientesRes, toursRes, usuariosRes] = await Promise.all([
          axios.get(`https://tu-api.antigravity.app/api/clientes?agenciaid=${agenciaid}`, 
            { headers: { Authorization: `Bearer ${token}` } }),
          axios.get(`https://tu-api.antigravity.app/api/tours?agenciaid=${agenciaid}`,
            { headers: { Authorization: `Bearer ${token}` } }),
          axios.get(`https://tu-api.antigravity.app/api/usuarios?agenciaid=${agenciaid}`,
            { headers: { Authorization: `Bearer ${token}` } })
        ]);

        // Calcular límites
        const calcularLimite = (usado, limite) => {
          if (!limite) return { usado, limite: Infinity, porcentaje: 0, alerta: false };
          const porcentaje = (usado / limite) * 100;
          return {
            usado,
            limite,
            porcentaje: Math.round(porcentaje),
            alerta: porcentaje >= 80
          };
        };

        setLimits({
          clientes: calcularLimite(clientesRes.data.length, plan.limiteclientes),
          tours: calcularLimite(toursRes.data.length, plan.limitetours),
          usuarios: calcularLimite(usuariosRes.data.length, plan.limiteusuarios)
        });
      } catch (error) {
        console.error('Error fetching limits:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchLimits();
  }, [agenciaid, token]);

  return { limits, loading };
};
```

**Tiempo:** 30 min ⏱️

### 3.3 Usar en Dashboard

En tu archivo `src/pages/Dashboard.jsx`:

```jsx
import { SubscriptionCard } from '../components/SubscriptionCard';
import { useResourceLimits } from '../hooks/useResourceLimits';

export const Dashboard = () => {
  const token = localStorage.getItem('token');
  const agenciaid = localStorage.getItem('agenciaid');

  const { limits } = useResourceLimits(agenciaid, token);

  return (
    <div className="dashboard">
      {/* Card de Suscripción */}
      <SubscriptionCard agenciaid={agenciaid} token={token} />

      {/* Alertas de límites */}
      {limits.tours.alerta && (
        <div className="alert alert-warning">
          ⚠️ Estás usando {limits.tours.usado} de {limits.tours.limite} tours. 
          Considera actualizar tu plan.
        </div>
      )}

      {limits.clientes.alerta && (
        <div className="alert alert-warning">
          ⚠️ Estás usando {limits.clientes.usado} de {limits.clientes.limite} clientes.
          Considera actualizar tu plan.
        </div>
      )}

      {/* Resto del dashboard... */}
    </div>
  );
};
```

**Tiempo:** 20 min ⏱️

---

## 📊 PASO 4: Implementar Panel Superadmin (1-2 horas)

### 4.1 Tabla de Planes

Crea archivo: `src/pages/SuperadminPlanes.jsx`

```jsx
import React, { useEffect, useState } from 'react';
import axios from 'axios';

export const SuperadminPlanes = () => {
  const [planes, setPlanes] = useState([]);
  const [agencias, setAgencias] = useState([]);
  const token = localStorage.getItem('token');

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [planesRes, agenciasRes] = await Promise.all([
          axios.get('https://tu-api.antigravity.app/api/planes', 
            { headers: { Authorization: `Bearer ${token}` } }),
          axios.get('https://tu-api.antigravity.app/api/v_agencias_con_planes',
            { headers: { Authorization: `Bearer ${token}` } })
        ]);

        setPlanes(planesRes.data);
        setAgencias(agenciasRes.data);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    fetchData();
  }, [token]);

  const getAgenciasCount = (planId) => {
    return agencias.filter(a => a.plan_tipo === planId).length;
  };

  const getTotalIngreso = (planId) => {
    return agencias
      .filter(a => a.plan_tipo === planId && a.estado === 'activa')
      .reduce((sum, a) => sum + (parseFloat(a.precio) || 0), 0);
  };

  return (
    <div className="page">
      <h1>💰 Gestión de Planes</h1>

      {/* KPIs */}
      <div className="kpi-grid">
        <div className="kpi-card">
          <p className="kpi-label">Ingresos Totales</p>
          <p className="kpi-value">
            ${planes.reduce((sum, p) => sum + (getTotalIngreso(p.codigo) || 0), 0).toFixed(2)}
          </p>
        </div>
        <div className="kpi-card">
          <p className="kpi-label">Agencias Activas</p>
          <p className="kpi-value">
            {agencias.filter(a => a.estado === 'activa').length}
          </p>
        </div>
        <div className="kpi-card">
          <p className="kpi-label">Por Vencer (7d)</p>
          <p className="kpi-value">
            {agencias.filter(a => a.dias_restantes <= 7 && a.dias_restantes > 0).length}
          </p>
        </div>
      </div>

      {/* Tabla Planes */}
      <div className="table-container">
        <h2>Planes Disponibles</h2>
        <table className="table">
          <thead>
            <tr>
              <th>Plan</th>
              <th>Precio</th>
              <th>Duración</th>
              <th>Agencias</th>
              <th>Ingresos</th>
              <th>API</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {planes.map(plan => (
              <tr key={plan.id}>
                <td>
                  <strong>{plan.nombre}</strong>
                  {plan.destacado && <span className="badge">POPULAR</span>}
                </td>
                <td>${plan.precio}</td>
                <td>{plan.duracionmeses} mes(es)</td>
                <td>{getAgenciasCount(plan.codigo)}</td>
                <td>${getTotalIngreso(plan.codigo).toFixed(2)}</td>
                <td>{plan.incluye_api ? '✅' : '❌'}</td>
                <td>
                  <button className="btn-sm">Editar</button>
                  <button className="btn-sm">Ver Agencias</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Alertas de Vencimiento */}
      <div className="alerts-section">
        <h2>⚠️ Alertas de Vencimiento</h2>
        <div className="alert-list">
          {agencias
            .filter(a => a.dias_restantes <= 7 && a.dias_restantes > 0)
            .sort((a, b) => a.dias_restantes - b.dias_restantes)
            .map(agencia => (
              <div key={agencia.id} className="alert alert-warning">
                <p>
                  <strong>{agencia.agencia}</strong> ({agencia.plan_nombre})
                  vence en {agencia.dias_restantes} días
                </p>
                <small>{agencia.fechavencimiento}</small>
              </div>
            ))}
        </div>
      </div>
    </div>
  );
};
```

**Tiempo:** 1 hora ⏱️

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

```
BD (20 min):
  [✓] Crear tabla planes
  [✓] Insertar 3 planes
  [✓] Crear vista v_agencias_con_planes
  [✓] Actualizar agencias existentes

Antigravity (30 min):
  [✓] Agregar tabla planes a conexión
  [✓] Regenerar API
  [✓] Probar GET /planes en Postman
  [✓] Verificar JWT tokens

React - Admin Agencia (2-3 horas):
  [✓] Componente SubscriptionCard
  [✓] Hook useResourceLimits
  [✓] Alertas en Dashboard
  [✓] Validación en crear recursos

React - Superadmin (1-2 horas):
  [✓] Tabla de planes
  [✓] Tabla de agencias con plan
  [✓] KPIs de ingresos
  [✓] Alertas de vencimiento

Pagos (Opcional - Próximo):
  [ ] Integrar Stripe / MercadoPago
  [ ] Botón "Actualizar Plan"
  [ ] Procesar pago
  [ ] Email confirmación
```

**Tiempo Total: 4-6 horas** ⏱️

---

## 🚀 PRÓXIMOS PASOS

### Una vez implementados los 4 pasos:

1. **Prueba en dev:** Cambia el plan de una agencia manualmente
2. **Verifica límites:** Intenta crear más tours de lo permitido
3. **Simula vencimiento:** Actualiza una fecha de vencimiento a pasado
4. **Integra pagos:** Agrega Stripe/MercadoPago (opcional)

### Links útiles:

- Antigravity Docs: https://docs.antigravity.app
- Stripe Integration: https://stripe.com/docs/api
- MercadoPago: https://www.mercadopago.com.ar/developers

---

**¡LISTO! Tu sistema de planes está operativo. 🎉**
