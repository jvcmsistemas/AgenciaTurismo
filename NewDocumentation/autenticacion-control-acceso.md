# 🔐 ARQUITECTURA DE AUTENTICACIÓN Y CONTROL DE ACCESO

**Sistema:** Agencia Turismo Multi-Tenant  
**Fecha:** 8 Diciembre 2025  
**Objetivo:** Sistema seguro de login, roles y permisos

---

## 🔑 ESQUEMA DE AUTENTICACIÓN

### USUARIOS Y ROLES

```
┌─────────────────────────────────────────────────┐
│         TABLA USUARIOS MEJORADA                 │
├─────────────────────────────────────────────────┤

Campos:
  id              INT PRIMARY KEY
  nombre          VARCHAR(100)
  apellido        VARCHAR(100)
  email           VARCHAR(100) UNIQUE
  contrasena      VARCHAR(255) HASHED bcrypt
  rol             ENUM('superadmin','dueno_agencia','empleado_agencia','guia')
  agencia_id      INT FK (NULL si superadmin)
  es_activo       BOOLEAN DEFAULT TRUE
  ultimo_acceso   TIMESTAMP NULL
  
  created_at      TIMESTAMP
  updated_at      TIMESTAMP

ÍNDICES:
  PRIMARY KEY (id)
  UNIQUE (email)
  INDEX (agencia_id)
  INDEX (rol)
```

---

## 👥 MATRIZ DE ROLES Y PERMISOS

### 1. SUPERADMIN (Dueño del Software)

```
┌─────────────────────────────────────┐
│ SUPERADMIN (superadmin@system.com)  │
├─────────────────────────────────────┤

ROL:        superadmin
AGENCIA_ID: NULL (sin agencia)
ACCESO:     GLOBAL (todas agencias)

PERMISOS:
✅ Dashboard Global
   - Ver KPIs de todas agencias
   - Ver ingresos totales
   - Ver usuarios globales

✅ Gestión de Agencias (CRUD)
   - Crear agencia
   - Editar agencia
   - Ver detalles agencia
   - Cambiar estado (activa/inactiva/suspendida)
   - Eliminar agencia (soft delete)

✅ Gestión de Planes & Suscripciones
   - Crear planes
   - Editar planes
   - Ver todas las suscripciones
   - Cambiar plan de agencia
   - Renovar/cancelar suscripción
   - Ver próximos vencimientos
   - Generar invoices

✅ Gestión de Usuarios Globales
   - Ver todos los usuarios (de todas agencias)
   - Crear superadmin nuevo
   - Crear admin agencia
   - Editar rol de usuario
   - Resetear contraseña
   - Bloquear/desbloquear usuario

✅ Finanzas Globales
   - Ver todos los pagos (de todas agencias)
   - Reportes de ingresos (MRR, ARR, proyecciones)
   - Ver facturas
   - Reportes por plan (prueba/semestral/anual)

✅ Auditoría Global
   - Ver auditoría de TODAS las agencias
   - Filtrar por tabla, usuario, operación
   - Detectar cambios sospechosos
   - Exportar auditoría completa

✅ Logs de Acceso Global
   - Ver accesos de todos los usuarios
   - Detectar intentos fallidos
   - Ver IPs sospechosas
   - Bloquear usuarios por seguridad

✅ Configuración del Sistema
   - Cambiar configuraciones globales
   - SMTP (email)
   - URLs, dominios
   - Branding
   - Integraciones (WhatsApp, etc)

✅ Backups & Restore
   - Crear backups
   - Restaurar backups
   - Ver historial de backups

NO TIENE:
❌ Acceso directo a operaciones de agencias
   (no ve reservas, guías, clientes de ninguna agencia
    salvo en reportes agregados)
```

### 2. ADMIN AGENCIA (Dueño de una Agencia)

```
┌─────────────────────────────────────┐
│ ADMIN AGENCIA (admin@agencia.com)   │
├─────────────────────────────────────┤

ROL:        dueno_agencia
AGENCIA_ID: 1 (su agencia específica)
ACCESO:     SOLO su agencia (datos filtrados)

PERMISOS:
✅ Dashboard Agencia
   - Ver KPIs de su agencia
   - Ingresos su agencia
   - Clientes activos su agencia
   - Reservas pendientes su agencia
   - Tours próximos su agencia

✅ Gestión de Reservas (CRUD)
   - Listar reservas (solo su agencia)
   - Crear reserva
   - Editar reserva
   - Cambiar estado (pendiente/confirmada/en_curso/completada/cancelada)
   - Cancelar con motivo (auditoría)
   - Ver historial de cambios

✅ Gestión de Clientes (CRUD)
   - Listar clientes
   - Crear cliente
   - Editar cliente
   - Ver historial reservas
   - Ver saldo adeudado
   - Contactar cliente (email/WhatsApp)

✅ Gestión de Tours (CRUD)
   - Listar tours
   - Crear tour (regular o personalizable)
   - Editar tour
   - Cambiar precio (con motivo en auditoría)
   - Ver historial de precios
   - Cambiar estado (activo/inactivo)

✅ Gestión de Salidas (CRUD)
   - Listar salidas
   - Crear salida
   - Editar salida
   - Asignar guía
   - Asignar transporte
   - Ver participantes
   - Marcar como completada

✅ Gestión de Guías
   - Listar guías de su agencia
   - Crear guía
   - Editar guía
   - Ver disponibilidad
   - Asignar salidas
   - Ver calificaciones

✅ Gestión de Guías Turísticos
   - Listar guías turísticos
   - Crear guía turístico (freelancer)
   - Editar guía turístico
   - Asignar a tours personalizables

✅ Gestión de Transportes
   - Listar vehículos
   - Crear vehículo
   - Editar vehículo
   - Gestionar documentos (SOAT, licencia)
   - Asignar a salida

✅ Gestión de Proveedores
   - Listar proveedores (hoteles, restaurantes)
   - Crear proveedor
   - Editar proveedor
   - Ver contratos

✅ Registrar Pagos
   - Registrar pago de cliente
   - Especificar método (efectivo/tarjeta/transferencia/yape/plin)
   - Subir comprobante
   - Ver pagos registrados
   - Marcar como aprobado/rechazado

✅ Auditoría de su Agencia
   - Ver cambios en RESERVAS (quién, cuándo, por qué)
   - Ver cambios en PRECIOS (historial completo)
   - Ver cambios en DESCUENTOS (aplicados, motivos)
   - Ver cambios en PAGOS (registrados, modificados)
   - Filtrar por tipo de cambio
   - Exportar para cumplimiento legal

✅ Reportes y Analíticos
   - Ingresos por período
   - Tours más vendidos
   - Clientes por cobrar
   - Tasa de cobranza
   - Performance de guías
   - Análisis de ocupación

✅ Gestión de Usuarios de su Agencia
   - Listar empleados
   - Crear empleado nuevo
   - Editar empleado
   - Asignar permisos (granulares)
   - Cambiar estado (activo/inactivo)
   - Resetear contraseña

✅ Configuración de su Agencia
   - Ver información agencia
   - Ver plan actual
   - Ver fecha vencimiento
   - Renovar plan (si vence)
   - Cambiar plan (si quiere)
   - Configurar integraciones (WhatsApp, Email)

NO TIENE:
❌ Cambiar su propio plan (solo ver/renovar)
❌ Ver otras agencias
❌ Cambiar estado de auditoría
❌ Ver logs de acceso global
❌ Acceso a configuración del sistema
```

### 3. EMPLEADO AGENCIA (Staff)

```
┌─────────────────────────────────────┐
│ EMPLEADO AGENCIA                    │
├─────────────────────────────────────┤

ROL:        empleado_agencia
AGENCIA_ID: 1 (su agencia, misma admin)
ACCESO:     Limitado según permisos

PERMISOS (asignables por Admin Agencia):
✅ PUEDE (si tiene permiso):
   - Ver reservas
   - Crear reservas
   - Editar reservas
   - Registrar pagos
   - Ver clientes
   - Crear clientes
   - Ver tours
   - Ver salidas
   - Ver guías

❌ NO PUEDE (nunca):
   - Ver auditoría
   - Cambiar precios
   - Aplicar descuentos (sin permiso específico)
   - Editar guías
   - Acceder a configuración
   - Crear usuarios
   - Ver logs de acceso
   - Cambiar plan

AUDITORÍA:
- Toda acción registrada (quién, cuándo)
- Si registra pago: auditoría automática
- Si crea reserva: auditoría automática
- Si intenta algo no permitido: log de intento fallido
```

### 4. GUÍA (Guide/Staff)

```
┌─────────────────────────────────────┐
│ GUÍA (Tour Guide)                   │
├─────────────────────────────────────┤

ROL:        guia
AGENCIA_ID: 1 (su agencia)
ACCESO:     Mínimo (solo información operacional)

PERMISOS:
✅ PUEDE:
   - Ver próximas salidas asignadas
   - Ver itinerario de cada salida
   - Ver lista de clientes (para esa salida)
   - Ver contacto de clientes
   - Marcar asistencia
   - Enviar fotos/comentarios

❌ NO PUEDE:
   - Ver precios
   - Ver información financiera
   - Cambiar nada
   - Ver otros guías
   - Acceder a auditoría

ACCESO LIMITADO:
- Panel guía simple
- Solo info de sus tours
```

---

## 🔐 ESQUEMA DE AUTENTICACIÓN (JWT)

### LOGIN FLOW

```
┌─────────────────────────────────────────────────┐
│                  LOGIN                          │
├─────────────────────────────────────────────────┤

1. Usuario ingresa email + contraseña
2. Sistema verifica:
   - Email existe
   - Contraseña correcta (bcrypt)
   - Usuario es_activo = TRUE
3. Sistema genera JWT Token
   {
     sub: user_id,
     email: user_email,
     rol: 'superadmin' | 'dueno_agencia' | 'empleado_agencia' | 'guia',
     agencia_id: agencia_id (NULL si superadmin),
     permisos: [...],
     iat: fecha_creación,
     exp: fecha_expiracion (24h)
   }
4. Retorna:
   - access_token (JWT)
   - refresh_token (válido 7 días)
   - user_info (nombre, rol, agencia)
5. Frontend guarda tokens en localStorage (secure)
6. Cada request incluye: Authorization: Bearer <access_token>
```

### LOGOUT FLOW

```
┌─────────────────────────────────────────────────┐
│                  LOGOUT                         │
├─────────────────────────────────────────────────┤

1. Usuario click "Cerrar sesión"
2. Frontend elimina tokens de localStorage
3. Frontend redirige a /login
4. Token se vuelve inválido automáticamente (24h)
```

### TOKEN REFRESH

```
┌─────────────────────────────────────────────────┐
│               REFRESH TOKEN                     │
├─────────────────────────────────────────────────┤

Si access_token vence:
1. Frontend detecta token vencido
2. Usa refresh_token para obtener nuevo access_token
3. Si refresh_token también vence: redirect a login
4. Si refresh_token válido: obtiene nuevo access_token
```

---

## 🛡️ CONTROL DE ACCESO (ACL - Access Control List)

### MIDDLEWARE DE AUTENTICACIÓN

```javascript
// Pseudocódigo

middleware authenticateToken(req, res, next):
  token = req.headers.authorization.split(' ')[1]
  if !token:
    return 401 Unauthorized
  
  try:
    decoded = jwt.verify(token, SECRET_KEY)
    req.user = decoded
    next()
  catch:
    return 401 Invalid Token
```

### MIDDLEWARE DE AUTORIZACIÓN

```javascript
// Pseudocódigo

middleware authorizeRole(requiredRoles):
  return (req, res, next):
    if req.user.rol NOT IN requiredRoles:
      return 403 Forbidden
    next()

middleware authorizeAgency(agencyIdParam):
  return (req, res, next):
    if req.user.rol == 'superadmin':
      next()  // Superadmin accede a todo
    else if req.user.agencia_id == agencyIdParam:
      next()  // Admin agencia accede solo a su agencia
    else:
      return 403 Forbidden
```

### EJEMPLOS DE RUTAS PROTEGIDAS

```javascript
// SUPERADMIN - Solo superadmin
GET /api/admin/agencias
  Middleware: authenticateToken, authorizeRole(['superadmin'])

// ADMIN AGENCIA - Solo de su agencia
GET /api/agencia/1/reservas
  Middleware: authenticateToken, authorizeAgency(1)

// EMPLEADO - Con permisos específicos
POST /api/agencia/1/pagos
  Middleware: authenticateToken, authorizeAgency(1)
  Permisos: usuario.permisos.includes('registrar_pagos')

// GUÍA - Información limitada
GET /api/guia/mis-salidas
  Middleware: authenticateToken, authorizeRole(['guia'])
```

---

## 🔒 SEGURIDAD DE CONTRASEÑAS

### HASH (Bcrypt)

```
Almacenamiento:
  NO guardar contraseña en texto plano
  
SIEMPRE usar bcrypt:
  password: "$2b$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36glvlG2"
  
  (este hash puede ser cualquier contraseña,
   pero no puedes revertirlo)

Proceso de hash:
  1. Usuario ingresa: "miContraseña123"
  2. Sistema hash con bcrypt: "$2b$10$..."
  3. Compara con hash guardado: ¿coincide?
  4. Si sí → login correcto
  5. Si no → login fallido
```

### REQUISITOS DE CONTRASEÑA

```
Mínimo:
  - 8 caracteres
  - 1 mayúscula
  - 1 minúscula
  - 1 número
  - 1 carácter especial (@, #, $, %, &, etc)

Ejemplo válido:
  ✅ MiContraseña@123
  ✅ Admin!Agencia2025
  ❌ 12345678 (solo números)
  ❌ Password (sin números)
```

### CAMBIO DE CONTRASEÑA

```
Flujo:
  1. Usuario ingresa contraseña actual
  2. Sistema verifica (compara con hash)
  3. Si correcto: permite cambiar
  4. Nueva contraseña se hashea con bcrypt
  5. Se guarda en BD
  6. Se registra en auditoría (cambio de contraseña)
  
Auditoría:
  tabla: usuarios
  operacion: UPDATE
  campo: contrasena
  motivo: "Cambio de contraseña por usuario"
  ip_origen: IP del usuario
  fecha_hora: 2025-12-08 14:30:00
```

### RESET DE CONTRASEÑA

```
Flujo (si olvida contraseña):
  1. Usuario click "Olvidé contraseña"
  2. Ingresa email
  3. Sistema genera token temporal (válido 1 hora)
  4. Envía email con link de reset
     https://sistema.com/reset-password?token=...
  5. Usuario click link
  6. Ingresa nueva contraseña
  7. Sistema verifica token (no expirado)
  8. Cambia contraseña
  9. Token se invalida (solo 1 uso)

Seguridad:
  - Token único por usuario
  - Válido solo 1 hora
  - Se usa 1 sola vez
  - Se registra en auditoría
```

---

## 📋 TABLA DE PERMISOS (GRANULARES)

```sql
CREATE TABLE permisos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100),
  descripcion TEXT,
  
  -- Ejemplos:
  -- 'ver_reservas', 'crear_reservas', 'editar_reservas'
  -- 'registrar_pagos', 'ver_pagos'
  -- 'cambiar_precios', 'aplicar_descuentos'
  -- 'ver_auditoria'
  -- 'crear_usuarios', 'editar_usuarios'
);

CREATE TABLE rol_permisos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  rol VARCHAR(50),
  permiso_id INT,
  
  FOREIGN KEY (permiso_id) REFERENCES permisos(id)
);

-- Ejemplo:
INSERT INTO rol_permisos (rol, permiso_id)
VALUES 
  ('empleado_agencia', 1),   -- ver_reservas
  ('empleado_agencia', 2),   -- crear_reservas
  ('empleado_agencia', 7);   -- registrar_pagos

-- Pero NO:
-- ('empleado_agencia', 15);  -- cambiar_precios
-- ('empleado_agencia', 20);  -- ver_auditoria
```

---

## 🔍 AUDITORÍA DE LOGIN/LOGOUT

```sql
CREATE TABLE logs_acceso (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT,
  accion ENUM('LOGIN','LOGOUT','CAMBIO_CONTRASENA','INTENTO_FALLIDO'),
  ip_origen VARCHAR(45),
  user_agent VARCHAR(500),
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('exitoso','fallido'),
  detalles JSON,
  
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Ejemplo de registro:
INSERT INTO logs_acceso VALUES
  (NULL, 3, 'LOGIN', '192.168.1.100', 'Chrome Windows', NOW(), 'exitoso', 
   '{"navegador": "Chrome", "so": "Windows 10"}');

-- Intento fallido registrado:
INSERT INTO logs_acceso VALUES
  (NULL, NULL, 'LOGIN', '203.0.113.45', 'Unknown', NOW(), 'fallido',
   '{"email": "intruder@test.com", "razon": "contraseña incorrecta"}');
```

---

## 🚨 DETECCIÓN DE ANOMALÍAS

```
Sistema detecta:
  ✅ Intento login desde país diferente
  ✅ Intento login a hora inusual
  ✅ Múltiples intentos fallidos (bloquea)
  ✅ Cambio de contraseña sospechoso
  ✅ Acceso desde IP no registrada
  ✅ Cambios de precio no autorizados
  ✅ Aplicación de descuentos no autorizados
  ✅ Auditoría manipulada (detecta)

Acciones:
  - Registrar en logs
  - Alertar a superadmin
  - Bloquear usuario (si es grave)
  - Solicitar verificación 2FA
```

---

## 🎯 FLUJO COMPLETO DE SEGURIDAD

```
┌──────────────────────────────────────────────────┐
│                                                  │
│  1. Usuario intenta login                        │
│     ↓                                            │
│  2. Sistema verifica email + contraseña          │
│     ↓                                            │
│  3. Genera JWT token                            │
│     ↓                                            │
│  4. Retorna token + refresh_token                │
│     ↓                                            │
│  5. Frontend guarda tokens (localStorage)        │
│     ↓                                            │
│  6. Usuario navega a /dashboard                  │
│     ↓                                            │
│  7. Sistema verifica token en cada request       │
│     ↓                                            │
│  8. Si válido: permite acceso                    │
│  9. Si vencido: intenta refresh                  │
│  10. Si refresh falló: redirige a login          │
│     ↓                                            │
│  11. Usuario realiza acción (ej: crear reserva) │
│     ↓                                            │
│  12. Sistema verifica:                           │
│      - Token válido                              │
│      - Rol tiene permiso                         │
│      - Agencia_id coincide                       │
│     ↓                                            │
│  13. Si todo correcto: ejecuta acción            │
│  14. Registra en auditoría (quién, qué, cuándo) │
│     ↓                                            │
│  15. Usuario click "Cerrar sesión"               │
│     ↓                                            │
│  16. Frontend elimina tokens                     │
│     ↓                                            │
│  17. Registra LOGOUT en logs_acceso              │
│     ↓                                            │
│  18. Redirige a /login                           │
│                                                  │
└──────────────────────────────────────────────────┘
```

---

## ✅ CHECKLIST DE SEGURIDAD

```
AUTENTICACIÓN:
  [ ] Contraseñas hashadas con bcrypt
  [ ] JWT tokens con expiración 24h
  [ ] Refresh tokens válido 7 días
  [ ] Logout limpia tokens
  [ ] Reset password con token temporal (1 hora)

AUTORIZACIÓN:
  [ ] Verificar rol en cada endpoint
  [ ] Verificar agencia_id en multi-tenant
  [ ] Permisos granulares asignables
  [ ] Superadmin no puede editar otros superadmins
  [ ] Admin agencia no ve otras agencias

AUDITORÍA:
  [ ] Log de LOGIN/LOGOUT
  [ ] Log de cambios de contraseña
  [ ] Log de intentos fallidos
  [ ] Log de cada operación (quién, qué, cuándo)
  [ ] Log de acceso desde IPs raras

SEGURIDAD:
  [ ] Tokens en localStorage (no cookies)
  [ ] HTTPS/TLS en producción
  [ ] CORS configurado correctamente
  [ ] Rate limiting en endpoints
  [ ] SQL injection prevention (prepared statements)
  [ ] XSS prevention (sanitizar input)
  [ ] CSRF protection (tokens)

DETECCIÓN:
  [ ] Alertar múltiples intentos fallidos
  [ ] Alertar cambios de IP origen
  [ ] Alertar cambios de usuario a hora rara
  [ ] Alertar cambios de precio sospechosos
  [ ] Bloquear usuario después de N intentos
```

---

**Sistema de autenticación listo para implementar en Antigravity + Frontend**
