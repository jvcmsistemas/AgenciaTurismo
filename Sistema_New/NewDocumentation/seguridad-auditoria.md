# 🔒 Seguridad & Auditoría: Logs de Acceso y Permisos

Este módulo gestiona el control de acceso basado en roles (RBAC), registro de auditoría de acciones y monitoreo de seguridad en el sistema de gestión de agencias turísticas.

---

## 📋 Estado actual en la BD

### 1. Tabla `usuarios` (Existe)

Ya contiene la estructura base de usuarios y roles:[file:19]

```sql
CREATE TABLE usuarios (
  id            int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre        varchar(100)  NOT NULL,
  apellido      varchar(100)  NOT NULL,
  email         varchar(100)  NOT NULL UNIQUE,
  contrasena    varchar(255)  NOT NULL,
  rol           enum('administradorgeneral','duenoagencia','empleadoagencia') NOT NULL,
  createdat     timestamp     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  agenciaid     int(11)       DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos relevantes:

- `id`: Identificador único del usuario.
- `email`: Correo del usuario (usado para login).
- `rol` (enum): Define el nivel de acceso:
  - `administradorgeneral`: Superadmin del sistema (acceso total).
  - `duenoagencia`: Dueño/gerente de una agencia.
  - `empleadoagencia`: Empleado de una agencia con accesos limitados.
- `agenciaid` (FK): Si es NULL, es superadmin; si tiene valor, pertenece a esa agencia.

---

## 🆕 Tablas necesarias para Auditoría

### 2. Tabla `logs_acceso` (NUEVA)

Registra cada login, logout y acceso al sistema para detectar intentos sospechosos.

```sql
CREATE TABLE logs_acceso (
  id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuarioid         int(11)       NOT NULL,
  email             varchar(100)  NOT NULL,
  tipo_evento       enum('login','logout','acceso_recurso','intento_fallido','cambio_contrasena') NOT NULL,
  direccion_ip      varchar(45)   DEFAULT NULL COMMENT 'IPv4 o IPv6',
  user_agent        text          DEFAULT NULL COMMENT 'Navegador/cliente',
  endpoint          varchar(255)  DEFAULT NULL COMMENT 'Ruta accedida (ej: /api/clientes)',
  metodo_http       enum('GET','POST','PUT','DELETE','PATCH') DEFAULT NULL,
  codigo_respuesta  int(3)        DEFAULT NULL COMMENT 'Código HTTP (200, 401, 403, 404, 500)',
  descripcion       text          DEFAULT NULL COMMENT 'Detalles del evento',
  resultado         enum('exitoso','fallido') DEFAULT 'exitoso',
  fecha_hora        datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (usuarioid) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuarioid (usuarioid),
  INDEX idx_fecha_hora (fecha_hora),
  INDEX idx_tipo_evento (tipo_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos significado:

- `usuarioid`: FK a `usuarios.id`.
- `email`: Copia del email del usuario (para auditoría si se borra el usuario).
- `tipo_evento`: Tipo de acción: login, logout, acceso a recurso, intento fallido, cambio de contraseña.
- `direccion_ip`: IP del cliente (útil para detectar accesos desde lugares inusuales).
- `user_agent`: Información del navegador/cliente.
- `endpoint`: API endpoint o ruta accedida.
- `metodo_http`: GET, POST, PUT, DELETE, PATCH.
- `codigo_respuesta`: HTTP status (200 = OK, 401 = No autorizado, 403 = Prohibido, 500 = Error).
- `resultado`: `exitoso` o `fallido` (para detectar patrones de ataque).

---

### 3. Tabla `auditorias` (NUEVA)

Registra cambios a datos sensibles (creación, modificación, eliminación de recursos).

```sql
CREATE TABLE auditorias (
  id              int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuarioid       int(11)       NOT NULL,
  agenciaid       int(11)       DEFAULT NULL,
  tabla           varchar(50)   NOT NULL COMMENT 'Tabla afectada (clientes, tours, reservas)',
  id_recurso      int(11)       NOT NULL COMMENT 'ID del registro en la tabla',
  tipo_operacion  enum('crear','actualizar','eliminar') NOT NULL,
  valores_antes   json          DEFAULT NULL COMMENT 'JSON con valores antiguos',
  valores_despues json          DEFAULT NULL COMMENT 'JSON con valores nuevos',
  razon_cambio    text          DEFAULT NULL COMMENT 'Por qué se hizo el cambio',
  fecha_hora      datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (usuarioid) REFERENCES usuarios(id) ON DELETE SET NULL,
  FOREIGN KEY (agenciaid) REFERENCES agencias(id) ON DELETE CASCADE,
  INDEX idx_usuarioid (usuarioid),
  INDEX idx_tabla (tabla),
  INDEX idx_fecha_hora (fecha_hora),
  INDEX idx_tipo_operacion (tipo_operacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos significado:

- `usuarioid`: Usuario que realizó la acción.
- `agenciaid`: Agencia donde ocurrió el cambio.
- `tabla`: Nombre de la tabla modificada (clientes, tours, reservas, etc.).
- `id_recurso`: ID del registro específico que cambió.
- `tipo_operacion`: CREATE, UPDATE o DELETE.
- `valores_antes`: JSON del estado anterior.
- `valores_despues`: JSON del estado actual.
- `razon_cambio`: Explicación del cambio (ej: "Cancelado por solicitud del cliente").

---

### 4. Tabla `permisos` (NUEVA)

Define qué acciones puede hacer cada rol.

```sql
CREATE TABLE permisos (
  id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  rol               varchar(50)   NOT NULL,
  recurso           varchar(100)  NOT NULL COMMENT 'clientes, tours, reservas, pagos, etc.',
  accion            enum('crear','leer','actualizar','eliminar') NOT NULL,
  descripcion       text          DEFAULT NULL,
  
  UNIQUE KEY uq_rol_recurso_accion (rol, recurso, accion),
  INDEX idx_rol (rol),
  INDEX idx_recurso (recurso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos significado:

- `rol`: Tipo de usuario (administradorgeneral, duenoagencia, empleadoagencia).
- `recurso`: Entidad del sistema (clientes, tours, reservas, pagos, usuarios, reportes).
- `accion`: CRUD (crear, leer, actualizar, eliminar).

---

### 5. Tabla `sesiones` (NUEVA)

Controla sesiones activas para detectar múltiples logins y forzar logout.

```sql
CREATE TABLE sesiones (
  id                int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuarioid         int(11)       NOT NULL,
  token_jwt         varchar(500)  NOT NULL UNIQUE,
  direccion_ip      varchar(45)   DEFAULT NULL,
  user_agent        text          DEFAULT NULL,
  fecha_inicio      datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_expiracion  datetime      NOT NULL COMMENT 'Cuando vence el token',
  activa            tinyint(1)    DEFAULT 1,
  fecha_cierre      datetime      DEFAULT NULL COMMENT 'Cuando hizo logout',
  razon_cierre      varchar(100)  DEFAULT NULL COMMENT 'logout_usuario, expiracion, forzado',
  
  FOREIGN KEY (usuarioid) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_usuarioid (usuarioid),
  INDEX idx_token_jwt (token_jwt),
  INDEX idx_activa (activa),
  INDEX idx_fecha_expiracion (fecha_expiracion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos significado:

- `token_jwt`: Token JWT de la sesión.
- `fecha_expiracion`: Cuándo vence el token.
- `activa`: 1 = sesión activa, 0 = cerrada.
- `razon_cierre`: Por qué se cerró (logout manual, token expirado, administrador forzó cierre).

---

### 6. Tabla `intentos_fallidos` (NUEVA)

Detecta intentos de fuerza bruta y bloquea IP/email sospechosos.

```sql
CREATE TABLE intentos_fallidos (
  id              int(11)       NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email           varchar(100)  NOT NULL,
  direccion_ip    varchar(45)   DEFAULT NULL,
  fecha_intento   datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  razon           varchar(100)  DEFAULT NULL COMMENT 'password_incorrecto, usuario_no_existe',
  
  INDEX idx_email (email),
  INDEX idx_direccion_ip (direccion_ip),
  INDEX idx_fecha_intento (fecha_intento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Campos significado:

- `email`: Email del intento de login.
- `direccion_ip`: IP del atacante.
- `razon`: Tipo de fallo.

**Lógica antibrute-force:**

- Si hay > 5 intentos fallidos en 15 minutos → bloquear IP por 30 minutos.
- Si hay > 10 intentos fallidos en 1 hora → notificar al superadmin.

---

## 📊 Insertar permisos iniciales

```sql
-- Permisos: ADMINISTRADOR GENERAL (Superadmin)
INSERT INTO permisos (rol, recurso, accion, descripcion) VALUES
('administradorgeneral', 'agencias', 'crear', 'Crear nuevas agencias'),
('administradorgeneral', 'agencias', 'leer', 'Ver todas las agencias'),
('administradorgeneral', 'agencias', 'actualizar', 'Editar agencias'),
('administradorgeneral', 'agencias', 'eliminar', 'Eliminar agencias'),

('administradorgeneral', 'usuarios', 'crear', 'Crear usuarios en cualquier agencia'),
('administradorgeneral', 'usuarios', 'leer', 'Ver todos los usuarios'),
('administradorgeneral', 'usuarios', 'actualizar', 'Editar usuarios'),
('administradorgeneral', 'usuarios', 'eliminar', 'Eliminar usuarios'),

('administradorgeneral', 'reportes', 'leer', 'Ver reportes de todas las agencias'),
('administradorgeneral', 'auditorias', 'leer', 'Ver logs de auditoría globales'),
('administradorgeneral', 'pagos', 'leer', 'Ver todos los pagos'),

-- Permisos: DUEÑO DE AGENCIA
('duenoagencia', 'clientes', 'crear', 'Crear clientes en su agencia'),
('duenoagencia', 'clientes', 'leer', 'Ver clientes de su agencia'),
('duenoagencia', 'clientes', 'actualizar', 'Editar clientes'),
('duenoagencia', 'clientes', 'eliminar', 'Eliminar clientes'),

('duenoagencia', 'tours', 'crear', 'Crear tours'),
('duenoagencia', 'tours', 'leer', 'Ver tours'),
('duenoagencia', 'tours', 'actualizar', 'Editar tours'),
('duenoagencia', 'tours', 'eliminar', 'Eliminar tours'),

('duenoagencia', 'reservas', 'crear', 'Crear reservas'),
('duenoagencia', 'reservas', 'leer', 'Ver reservas'),
('duenoagencia', 'reservas', 'actualizar', 'Editar reservas'),
('duenoagencia', 'reservas', 'eliminar', 'Cancelar reservas'),

('duenoagencia', 'usuarios', 'crear', 'Agregar empleados a su agencia'),
('duenoagencia', 'usuarios', 'leer', 'Ver empleados'),
('duenoagencia', 'usuarios', 'actualizar', 'Editar empleados'),
('duenoagencia', 'usuarios', 'eliminar', 'Eliminar empleados'),

('duenoagencia', 'reportes', 'leer', 'Ver reportes de su agencia'),
('duenoagencia', 'pagos', 'leer', 'Ver pagos de su agencia'),
('duenoagencia', 'auditorias', 'leer', 'Ver auditoría de su agencia'),

-- Permisos: EMPLEADO DE AGENCIA
('empleadoagencia', 'clientes', 'crear', 'Crear clientes'),
('empleadoagencia', 'clientes', 'leer', 'Ver clientes'),
('empleadoagencia', 'clientes', 'actualizar', 'Editar clientes'),

('empleadoagencia', 'tours', 'leer', 'Ver tours'),

('empleadoagencia', 'reservas', 'crear', 'Crear reservas'),
('empleadoagencia', 'reservas', 'leer', 'Ver reservas'),
('empleadoagencia', 'reservas', 'actualizar', 'Editar reservas'),

('empleadoagencia', 'reportes', 'leer', 'Ver reportes básicos');
```

---

## 📈 Consultas base para reportes

### 1. Logins por usuario (últimos 7 días)

```sql
SELECT
  u.nombre,
  u.apellido,
  u.email,
  u.rol,
  COUNT(la.id) AS total_logins,
  MAX(la.fecha_hora) AS ultimo_login,
  MIN(la.fecha_hora) AS primer_login
FROM usuarios u
LEFT JOIN logs_acceso la 
  ON u.id = la.usuarioid 
  AND la.tipo_evento = 'login'
  AND la.fecha_hora >= DATE_SUB(NOW(), INTERVAL 7 DAY)
WHERE u.agenciaid = :agenciaid OR u.rol = 'administradorgeneral'
GROUP BY u.id, u.nombre, u.email, u.rol
ORDER BY ultimo_login DESC;
```

### 2. Intentos fallidos de login (últimas 24 horas)

```sql
SELECT
  email,
  COUNT(*) AS total_intentos_fallidos,
  COUNT(DISTINCT direccion_ip) AS ips_unicas,
  MAX(fecha_intento) AS ultimo_intento
FROM intentos_fallidos
WHERE fecha_intento >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY email
HAVING total_intentos_fallidos >= 3
ORDER BY total_intentos_fallidos DESC;
```

### 3. Cambios a clientes (últimos 30 días)

```sql
SELECT
  a.id,
  u.nombre AS usuario_que_cambio,
  a.tipo_operacion,
  c.nombre AS cliente,
  c.email,
  a.valores_antes,
  a.valores_despues,
  a.fecha_hora
FROM auditorias a
JOIN usuarios u ON a.usuarioid = u.id
LEFT JOIN clientes c ON a.tabla = 'clientes' AND a.id_recurso = c.id
WHERE a.tabla = 'clientes'
  AND a.agenciaid = :agenciaid
  AND a.fecha_hora >= DATE_SUB(NOW(), INTERVAL 30 DAY)
ORDER BY a.fecha_hora DESC;
```

### 4. Accesos a API por endpoint (últimas 24 horas)

```sql
SELECT
  endpoint,
  metodo_http,
  COUNT(*) AS total_accesos,
  SUM(CASE WHEN codigo_respuesta >= 200 AND codigo_respuesta < 300 THEN 1 ELSE 0 END) AS exitosos,
  SUM(CASE WHEN codigo_respuesta >= 400 THEN 1 ELSE 0 END) AS errores,
  MAX(fecha_hora) AS ultimo_acceso
FROM logs_acceso
WHERE fecha_hora >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
  AND endpoint IS NOT NULL
GROUP BY endpoint, metodo_http
ORDER BY total_accesos DESC;
```

### 5. Permisos del usuario actual

```sql
SELECT
  p.recurso,
  GROUP_CONCAT(p.accion SEPARATOR ', ') AS acciones_permitidas,
  p.descripcion
FROM permisos p
WHERE p.rol = (SELECT rol FROM usuarios WHERE id = :usuarioid)
GROUP BY p.recurso
ORDER BY p.recurso;
```

### 6. Sesiones activas

```sql
SELECT
  s.id,
  u.nombre,
  u.email,
  u.rol,
  s.direccion_ip,
  s.fecha_inicio,
  s.fecha_expiracion,
  TIMESTAMPDIFF(MINUTE, NOW(), s.fecha_expiracion) AS minutos_restantes,
  CASE
    WHEN s.fecha_expiracion <= NOW() THEN 'EXPIRADA'
    WHEN TIMESTAMPDIFF(MINUTE, NOW(), s.fecha_expiracion) <= 5 THEN 'POR EXPIRAR'
    ELSE 'ACTIVA'
  END AS estado_sesion
FROM sesiones s
JOIN usuarios u ON s.usuarioid = u.id
WHERE s.activa = 1
ORDER BY s.fecha_expiracion DESC;
```

### 7. Actividad sospechosa (múltiples IPs en 1 hora)

```sql
SELECT
  u.nombre,
  u.email,
  COUNT(DISTINCT la.direccion_ip) AS ips_diferentes,
  COUNT(la.id) AS total_accesos,
  GROUP_CONCAT(DISTINCT la.direccion_ip SEPARATOR ', ') AS ips
FROM logs_acceso la
JOIN usuarios u ON la.usuarioid = u.id
WHERE la.fecha_hora >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY la.usuarioid, u.nombre, u.email
HAVING COUNT(DISTINCT la.direccion_ip) >= 3
ORDER BY total_accesos DESC;
```

---

## 🧩 Resumen de tablas

| Tabla | Propósito | Campos clave |
|-------|-----------|-------------|
| `usuarios` | Almacena usuarios y roles | id, email, rol, agenciaid |
| `logs_acceso` | Auditoría de logins/accesos | usuarioid, tipo_evento, direccion_ip, fecha_hora |
| `auditorias` | Historial de cambios de datos | tabla, id_recurso, tipo_operacion, valores_antes, valores_despues |
| `permisos` | Matriz RBAC | rol, recurso, accion |
| `sesiones` | Control de sesiones activas | usuarioid, token_jwt, fecha_expiracion, activa |
| `intentos_fallidos` | Detección de fuerza bruta | email, direccion_ip, fecha_intento |

---

## 🔑 Flujos de seguridad

### Login exitoso:
1. Verificar email y contraseña en `usuarios`.
2. Validar que `usuarios.estado != 'suspendida'` (si existe campo).
3. Crear sesión en `sesiones` con JWT.
4. Insertar log en `logs_acceso` con tipo_evento='login', resultado='exitoso'.

### Login fallido:
1. Insertar en `intentos_fallidos`.
2. Insertar en `logs_acceso` con resultado='fallido'.
3. Si intentos > 5 en 15 min → bloquear IP temporalmente.

### Cambio de datos:
1. Guardar valores anteriores en `auditorias.valores_antes`.
2. Realizar el cambio.
3. Guardar valores nuevos en `auditorias.valores_despues`.

### Validar permisos:
1. Obtener rol del usuario.
2. Consultar tabla `permisos` con rol + recurso + accion.
3. Si existe → permitir; si no → denegar (código 403).

---

## 🚀 Implementación recomendada

**Backend (Node.js + Express):**
1. Crear middleware de autenticación JWT.
2. Crear middleware de log de accesos (`logAcceso()`).
3. Crear middleware de validación de permisos (`checkPermiso(recurso, accion)`).
4. En cada ruta protegida, envolver con `checkPermiso()`.

**Frontend (React):**
1. Guardar JWT en localStorage.
2. Incluir JWT en header de cada petición API.
3. Si respuesta 401/403 → limpiar sesión y redirigir a login.
4. Mostrar mensaje de "sesión expirada".

---

**El sistema está listo para ser implementado en tu arquitectura actual.**
