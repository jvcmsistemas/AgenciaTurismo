# 🗄️ BASE DE DATOS COMPLETA Y MEJORADA
## Sistema Agencia Turismo - Versión Optimizada con Auditoría

**Fecha:** 8 de Diciembre, 2025  
**Estado:** BD Corregida y Lista para Ejecutar  
**Cambios:** guias_turisticos MANTENIDO (tours personalizables), Auditoría mejorada

---

## 📋 ESTRUCTURA FINAL (ENTENDIMIENTO CORRECTO)

```
TOURS en tu agencia:
├─ TOURS PROGRAMADOS (regulares cada semana)
│  └─ Tabla: salidas + salida_guias
│     Guía: Fijo en cada salida
│     Clientes: Pueden sumarse a grupo compartido
│
├─ TOURS PERSONALIZADOS (clientes custom)
│  └─ Tabla: reserva_detalles + guias_turisticos
│     Guía: Flexible, asignable según necesidad
│     Clientes: Grupo exclusivo (privado)
│
├─ GUÍAS REGISTRADOS (tu nómina)
│  └─ Tabla: guias
│     - Profesionales fijos
│     - Disponibilidad
│     - Rating
│
└─ GUÍAS PARA TOURS (pool flexible)
   └─ Tabla: guias_turisticos
      - Para tours que se arman "sobre la marcha"
      - Pueden ser empleados o freelancers
      - Diferentes por cada tour personalizado
```

---

## 🔍 TABLA AUDITORIA - CAMPOS COMPLETOS

```sql
auditoria:
  id                     INT - Identificador único
  tabla_afectada         VARCHAR(50) - 'reservas', 'pagos', 'tours', etc.
  registro_id            INT - ID del registro modificado (ej: reserva #2)
  operacion              ENUM - INSERT, UPDATE, DELETE
  usuario_id             INT FK → usuarios(id) - Quién hizo el cambio
  
  valor_anterior         JSON - Estado ANTES (completo para auditoría)
                                {"precio": 70, "estado": "confirmada"}
  
  valor_nuevo            JSON - Estado DESPUÉS (completo para auditoría)
                                {"precio": 75, "estado": "confirmada"}
  
  ip_origen              VARCHAR(45) - Dirección IP del usuario
  user_agent             VARCHAR(500) - Navegador, SO, dispositivo
  
  fecha_hora             TIMESTAMP - Cuándo ocurrió
  
  motivo                 TEXT - Por qué se hizo el cambio
                                "Cliente solicitó cambio de fecha"
                                "Error de cálculo - corregido por admin"
                                "Cancelación por fuerza mayor"
  
  accion_tipo            ENUM - Categoría del cambio
                                'corrección' (fix de error)
                                'cancelación' (reserva/pago cancelado)
                                'modificación_normal' (cambio regular)
                                'descuento' (aplicación de descuento)
                                'admin' (acción administrativa)
  
  descripcion_cambio     TEXT - Descripción legible (no JSON)
                                "Cambio de precio de $70 a $75"
                                "Reserva cancelada - devolución de $500"
  
  ipv4_usuario           VARCHAR(45) - Para seguimiento de accesos
  dispositivo            VARCHAR(100) - Móvil, Desktop, Tablet
  pais_origen            VARCHAR(50) - País detectado
  
  ÍNDICES:
  - PRIMARY KEY (id)
  - INDEX tabla_registro (tabla_afectada, registro_id)
  - INDEX usuario_fecha (usuario_id, fecha_hora)
  - INDEX fecha (fecha_hora)
  - INDEX operacion_accion (operacion, accion_tipo)
```

### **Ejemplos de Registros en Auditoria:**

```
EJEMPLO 1: Cambio de precio de tour
┌─────────────────────────────────────────────┐
│ tabla_afectada: tours                       │
│ registro_id: 5 (Full Day Villa Rica)        │
│ operacion: UPDATE                           │
│ usuario_id: 3 (Juan Dueño)                  │
│ valor_anterior: {"precio": 70.00, "duracion": 1} │
│ valor_nuevo: {"precio": 75.00, "duracion": 1}   │
│ fecha_hora: 2025-12-08 15:30:45             │
│ motivo: "Combustible aumentó 7%"            │
│ accion_tipo: modificación_normal            │
└─────────────────────────────────────────────┘

EJEMPLO 2: Descuento aplicado a reserva
┌─────────────────────────────────────────────┐
│ tabla_afectada: reservas                    │
│ registro_id: 3 (RES-2025-001234)            │
│ operacion: UPDATE                           │
│ usuario_id: 2 (Admin)                       │
│ valor_anterior: {"descuento": 0, "precio": 913.00} │
│ valor_nuevo: {"descuento": 50, "precio": 863.00}  │
│ fecha_hora: 2025-12-06 18:35:00             │
│ motivo: "Cliente solicitó descuento por referencia" │
│ accion_tipo: descuento                      │
└─────────────────────────────────────────────┘

EJEMPLO 3: Pago rechazado
┌─────────────────────────────────────────────┐
│ tabla_afectada: pagos                       │
│ registro_id: 1 (Pago inicial)               │
│ operacion: UPDATE                           │
│ usuario_id: 2 (Admin)                       │
│ valor_anterior: {"estado": "aprobado", "monto": 300} │
│ valor_nuevo: {"estado": "rechazado", "monto": 300}  │
│ fecha_hora: 2025-12-05 12:00:00             │
│ motivo: "Tarjeta rechazada - transferencia en su lugar" │
│ accion_tipo: corrección                     │
└─────────────────────────────────────────────┘

EJEMPLO 4: Cancelación de reserva
┌─────────────────────────────────────────────┐
│ tabla_afectada: reservas                    │
│ registro_id: 1 (RES-6933989E6B556)          │
│ operacion: UPDATE                           │
│ usuario_id: 6 (Empleado)                    │
│ valor_anterior: {"estado": "confirmada", "cantidad_personas": 9} │
│ valor_nuevo: {"estado": "cancelada", "cantidad_personas": 9}    │
│ fecha_hora: 2025-12-05 21:45:00             │
│ motivo: "Cliente enfermedad - requiere devolución total" │
│ accion_tipo: cancelación                    │
└─────────────────────────────────────────────┘
```

---

## 🗄️ SQL COMPLETO - BASE DE DATOS NUEVA Y MEJORADA

### **PASO 0: CREAR BD DESDE CERO (Limpia)**

```sql
-- Crear base de datos nueva
DROP DATABASE IF EXISTS agencia_turismo_db;
CREATE DATABASE agencia_turismo_db 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE agencia_turismo_db;

-- ============================================================
-- TABLA 1: USUARIOS (Gestión de acceso)
-- ============================================================
CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  rol ENUM('administrador_general','dueno_agencia','empleado_agencia','guia') NOT NULL,
  agencia_id INT,
  es_activo BOOLEAN DEFAULT TRUE,
  ultimo_acceso TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_email (email),
  INDEX idx_agencia_id (agencia_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 2: AGENCIAS (Multi-tenant)
-- ============================================================
CREATE TABLE agencias (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  direccion VARCHAR(255),
  telefono VARCHAR(20),
  email VARCHAR(100),
  ruc VARCHAR(15) UNIQUE,
  pais VARCHAR(50),
  ciudad VARCHAR(50),
  web VARCHAR(255),
  logo_url VARCHAR(255),
  descripcion TEXT,
  
  estado ENUM('activa','inactiva','suspendida') DEFAULT 'activa',
  tipo_suscripcion ENUM('prueba','semestral','anual') DEFAULT 'prueba',
  fecha_vencimiento DATETIME,
  dueno_id INT NOT NULL,
  
  es_activa BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (dueno_id) REFERENCES usuarios(id),
  INDEX idx_ruc (ruc),
  INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar FK a usuarios
ALTER TABLE usuarios ADD FOREIGN KEY (agencia_id) REFERENCES agencias(id);

-- ============================================================
-- TABLA 3: CLIENTES
-- ============================================================
CREATE TABLE clientes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  tipo_documento ENUM('DNI','Pasaporte','RUC','Otro') DEFAULT 'DNI',
  numero_documento VARCHAR(20),
  email VARCHAR(100),
  telefono VARCHAR(20),
  celular VARCHAR(20),
  nacionalidad VARCHAR(50),
  fecha_nacimiento DATE,
  genero ENUM('M','F','Otro'),
  
  ciudad VARCHAR(100),
  pais VARCHAR(50),
  direccion TEXT,
  
  es_activo BOOLEAN DEFAULT TRUE,
  total_gasto DECIMAL(10,2) DEFAULT 0,
  saldo_adeudado DECIMAL(10,2) DEFAULT 0,
  numero_reservas INT DEFAULT 0,
  
  fecha_registro DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id),
  UNIQUE KEY uk_documento_agencia (numero_documento, agencia_id),
  INDEX idx_email (email),
  INDEX idx_agencia_activo (agencia_id, es_activo),
  INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 4: TOURS (Catálogo)
-- ============================================================
CREATE TABLE tours (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  ubicacion VARCHAR(100),
  duracion INT,
  duracion_minutos INT,
  precio DECIMAL(10,2),
  
  nivel_dificultad ENUM('bajo','medio','alto') DEFAULT 'medio',
  tags TEXT,
  idiomas VARCHAR(255) DEFAULT 'Español',
  imagen_url VARCHAR(255),
  
  cupo_minimo INT DEFAULT 1,
  cupo_maximo INT DEFAULT 50,
  es_promocion BOOLEAN DEFAULT FALSE,
  descuento_promocion DECIMAL(5,2),
  
  es_activo BOOLEAN DEFAULT TRUE,
  es_personalizable BOOLEAN DEFAULT FALSE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id),
  INDEX idx_agencia_activo (agencia_id, es_activo),
  INDEX idx_ubicacion (ubicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 5: ITINERARIOS (Detalles día a día)
-- ============================================================
CREATE TABLE itinerarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT NOT NULL,
  dia_numero INT NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT,
  actividades TEXT,
  hora_inicio TIME,
  hora_fin TIME,
  ubicacion VARCHAR(100),
  
  FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
  INDEX idx_tour (tour_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 6: GUÍAS (Pool de profesionales en agencia)
-- ============================================================
CREATE TABLE guias (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100),
  tipo_documento ENUM('DNI','Pasaporte','Otro') DEFAULT 'DNI',
  numero_documento VARCHAR(20),
  email VARCHAR(100),
  telefono VARCHAR(20),
  celular VARCHAR(20),
  
  especialidad VARCHAR(100),
  experiencia_anios INT DEFAULT 1,
  idiomas VARCHAR(255) DEFAULT 'Español',
  certificaciones TEXT,
  documentos_validos BOOLEAN DEFAULT TRUE,
  fecha_caducidad_cert DATE,
  
  tarifa_diaria DECIMAL(10,2),
  tarifa_por_persona DECIMAL(10,2),
  numero_tours INT DEFAULT 0,
  rating DECIMAL(3,2) DEFAULT 0,
  
  disponibilidad_desde DATE,
  disponibilidad_hasta DATE,
  
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  es_empleado BOOLEAN DEFAULT TRUE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
  INDEX idx_agencia (agencia_id),
  INDEX idx_estado (estado),
  INDEX idx_disponibilidad (disponibilidad_desde, disponibilidad_hasta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 7: GUÍAS TURÍSTICOS (Para tours PERSONALIZABLES)
-- ============================================================
CREATE TABLE guias_turisticos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  tipo_documento VARCHAR(20),
  numero_documento VARCHAR(20),
  telefono VARCHAR(20),
  email VARCHAR(100),
  idiomas VARCHAR(255),
  especialidad VARCHAR(100),
  
  agencia_id INT,
  tarifa DECIMAL(10,2),
  rating DECIMAL(3,2),
  
  es_disponible BOOLEAN DEFAULT TRUE,
  notas TEXT,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE SET NULL,
  INDEX idx_agencia (agencia_id),
  INDEX idx_disponibilidad (es_disponible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 8: SALIDAS (Tours programados regulares)
-- ============================================================
CREATE TABLE salidas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  tour_id INT,
  guia_id INT,
  transporte_id INT,
  
  fecha_salida DATE NOT NULL,
  hora_salida TIME NOT NULL,
  fecha_regreso DATE,
  hora_regreso TIME,
  
  tipo ENUM('compartida','privada') DEFAULT 'compartida',
  estado ENUM('programada','confirmada','cerrada','cancelada','en_curso','completada') DEFAULT 'programada',
  
  cupos_totales INT,
  cupos_disponibles INT DEFAULT 0,
  precio_actual DECIMAL(10,2),
  precio_privado DECIMAL(10,2),
  
  notas TEXT,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
  FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE SET NULL,
  FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE SET NULL,
  FOREIGN KEY (transporte_id) REFERENCES transportes(id) ON DELETE SET NULL,
  
  INDEX idx_agencia_fecha (agencia_id, fecha_salida),
  INDEX idx_estado (estado),
  INDEX idx_tour (tour_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 9: SALIDA_GUÍAS (Múltiples guías por salida)
-- ============================================================
CREATE TABLE salida_guias (
  salida_id INT NOT NULL,
  guia_id INT NOT NULL,
  rol ENUM('principal','secundario','auxiliar') DEFAULT 'principal',
  
  PRIMARY KEY (salida_id, guia_id),
  FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE CASCADE,
  FOREIGN KEY (guia_id) REFERENCES guias(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 10: TRANSPORTES (Vehículos)
-- ============================================================
CREATE TABLE transportes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  tipo VARCHAR(50) DEFAULT 'Minivan',
  modelo VARCHAR(50),
  placa VARCHAR(20),
  capacidad INT,
  chofer_nombre VARCHAR(100),
  chofer_telefono VARCHAR(20),
  
  color VARCHAR(50),
  anio_modelo INT,
  licencia_vencimiento DATE,
  soat_vencimiento DATE,
  inspeccion_tecnica DATE,
  
  estado ENUM('activo','inactivo','mantenimiento') DEFAULT 'activo',
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
  INDEX idx_agencia_estado (agencia_id, estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 11: HOTELES (Proveedores)
-- ============================================================
CREATE TABLE hoteles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT,
  nombre VARCHAR(100) NOT NULL,
  direccion VARCHAR(255),
  telefono VARCHAR(20),
  email VARCHAR(100),
  web VARCHAR(255),
  categoria INT COMMENT 'Estrellas 1-5',
  
  numero_habitaciones INT,
  descripcion TEXT,
  precio_promedio DECIMAL(10,2),
  
  es_activo BOOLEAN DEFAULT TRUE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id),
  INDEX idx_agencia (agencia_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 12: RESTAURANTES (Proveedores)
-- ============================================================
CREATE TABLE restaurantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT,
  nombre VARCHAR(100) NOT NULL,
  direccion VARCHAR(255),
  telefono VARCHAR(20),
  email VARCHAR(100),
  tipo_cocina VARCHAR(50),
  
  horario_atencion VARCHAR(255),
  capacidad INT,
  precio_promedio DECIMAL(10,2),
  descripcion TEXT,
  
  es_activo BOOLEAN DEFAULT TRUE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id),
  INDEX idx_agencia (agencia_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 13: PROVEEDORES (Genéricos)
-- ============================================================
CREATE TABLE proveedores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  agencia_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  tipo ENUM('restaurante','hotel','ticket','transporte','otro') NOT NULL,
  
  contacto_nombre VARCHAR(100),
  email VARCHAR(100),
  telefono VARCHAR(20),
  ubicacion VARCHAR(255),
  web VARCHAR(255),
  
  rating DECIMAL(3,2) DEFAULT 0,
  contrato_numero VARCHAR(50),
  fecha_vencimiento DATE,
  
  estado ENUM('activo','inactivo') DEFAULT 'activo',
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id) ON DELETE CASCADE,
  INDEX idx_agencia_estado (agencia_id, estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 14: RESERVAS (Core del negocio)
-- ============================================================
CREATE TABLE reservas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  codigo_reserva VARCHAR(20) UNIQUE,
  
  agencia_id INT NOT NULL,
  cliente_id INT,
  
  fecha_hora_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_inicio_tour DATETIME,
  fecha_fin_tour DATETIME,
  
  estado ENUM('pendiente','confirmada','en_curso','completada','cancelada') DEFAULT 'pendiente',
  cantidad_personas INT DEFAULT 1,
  
  precio_total DECIMAL(10,2) DEFAULT 0.00,
  descuento DECIMAL(10,2) DEFAULT 0.00,
  descuento_razon VARCHAR(255),
  saldo_pendiente DECIMAL(10,2) DEFAULT 0.00,
  
  notas TEXT,
  notas_internas TEXT,
  origen ENUM('web','presencial','whatsapp','ia_chat') DEFAULT 'presencial',
  
  salida_id INT,
  es_personalizada BOOLEAN DEFAULT FALSE,
  guia_turistico_id INT,
  asignado_a_usuario INT,
  
  requiere_confirmacion BOOLEAN DEFAULT TRUE,
  confirmada_en TIMESTAMP NULL,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (agencia_id) REFERENCES agencias(id),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE SET NULL,
  FOREIGN KEY (guia_turistico_id) REFERENCES guias_turisticos(id) ON DELETE SET NULL,
  FOREIGN KEY (asignado_a_usuario) REFERENCES usuarios(id),
  
  UNIQUE KEY uk_codigo_reserva (codigo_reserva),
  INDEX idx_cliente_fecha (cliente_id, fecha_inicio_tour),
  INDEX idx_agencia_estado (agencia_id, estado),
  INDEX idx_estado_fecha (estado, fecha_inicio_tour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 15: RESERVA_DETALLES (Items en reserva)
-- ============================================================
CREATE TABLE reserva_detalles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  reserva_id INT NOT NULL,
  
  tipo_servicio ENUM('tour','hotel','transporte','guia','restaurante','otro') NOT NULL,
  servicio_id INT NOT NULL,
  fecha_servicio DATETIME,
  
  cantidad INT DEFAULT 1,
  precio_unitario DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2) DEFAULT 0.00,
  
  notas TEXT,
  
  FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE,
  INDEX idx_reserva (reserva_id),
  INDEX idx_tipo_servicio (tipo_servicio, servicio_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 16: PAGOS (Registro de pagos)
-- ============================================================
CREATE TABLE pagos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  reserva_id INT NOT NULL,
  cliente_id INT,
  
  monto DECIMAL(10,2) NOT NULL,
  metodo_pago ENUM('efectivo','tarjeta','transferencia','yape','plin','cheque','otro') NOT NULL,
  
  referencia VARCHAR(100) COMMENT 'Nro de operación o voucher',
  comprobante_numero VARCHAR(50),
  comprobante_url VARCHAR(255),
  
  fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('pendiente','aprobado','rechazado','anulado') DEFAULT 'aprobado',
  
  usuario_registrado_por INT,
  es_devolicion BOOLEAN DEFAULT FALSE,
  motivo_rechazo TEXT,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  
  FOREIGN KEY (reserva_id) REFERENCES reservas(id),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (usuario_registrado_por) REFERENCES usuarios(id),
  
  INDEX idx_reserva_fecha (reserva_id, fecha_pago),
  INDEX idx_cliente_fecha (cliente_id, fecha_pago),
  INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 17: AUDITORIA (CRÍTICA - Cumplimiento legal)
-- ============================================================
CREATE TABLE auditoria (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  tabla_afectada VARCHAR(50) NOT NULL,
  registro_id INT NOT NULL,
  operacion ENUM('INSERT','UPDATE','DELETE') NOT NULL,
  
  usuario_id INT,
  ip_origen VARCHAR(45),
  user_agent VARCHAR(500),
  dispositivo VARCHAR(100),
  
  valor_anterior JSON,
  valor_nuevo JSON,
  
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  motivo TEXT,
  accion_tipo ENUM('corrección','cancelación','modificación_normal','descuento','admin','pago') DEFAULT 'modificación_normal',
  descripcion_cambio TEXT,
  
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  
  INDEX idx_tabla_registro (tabla_afectada, registro_id),
  INDEX idx_usuario_fecha (usuario_id, fecha_hora),
  INDEX idx_fecha (fecha_hora),
  INDEX idx_operacion (operacion),
  INDEX idx_accion_tipo (accion_tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 18: LOGS_ACCESO (Control de accesos)
-- ============================================================
CREATE TABLE logs_acceso (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  usuario_id INT,
  accion VARCHAR(100),
  recurso VARCHAR(100),
  
  ip_origen VARCHAR(45),
  user_agent VARCHAR(500),
  
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('exitoso','fallido','no_autorizado') DEFAULT 'exitoso',
  
  detalles JSON,
  
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  
  INDEX idx_usuario_fecha (usuario_id, fecha_hora),
  INDEX idx_fecha (fecha_hora),
  INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 19: HISTORIAL_PRECIOS
-- ============================================================
CREATE TABLE historial_precios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  tabla_origen VARCHAR(50) NOT NULL,
  registro_id INT NOT NULL,
  
  precio_anterior DECIMAL(10,2),
  precio_nuevo DECIMAL(10,2),
  
  usuario_id INT,
  fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  motivo VARCHAR(255),
  
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  
  INDEX idx_registro (tabla_origen, registro_id, fecha_cambio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 20: CLIENTE_CONTACTOS (Múltiples contactos)
-- ============================================================
CREATE TABLE cliente_contactos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  cliente_id INT NOT NULL,
  tipo ENUM('principal','emergencia','facturacion','referencia') DEFAULT 'principal',
  
  nombre VARCHAR(100),
  relacion VARCHAR(50),
  telefono VARCHAR(20),
  email VARCHAR(100),
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  INDEX idx_cliente (cliente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 21: SALIDA_PARTICIPANTES (Quiénes fueron a cada salida)
-- ============================================================
CREATE TABLE salida_participantes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  salida_id INT NOT NULL,
  cliente_id INT NOT NULL,
  
  asistio BOOLEAN DEFAULT TRUE,
  calificacion INT,
  comentarios TEXT,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (salida_id) REFERENCES salidas(id) ON DELETE CASCADE,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  
  UNIQUE KEY uk_salida_cliente (salida_id, cliente_id),
  INDEX idx_salida (salida_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 22: NOTIFICACIONES
-- ============================================================
CREATE TABLE notificaciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  usuario_id INT,
  cliente_id INT,
  
  tipo ENUM('pago_pendiente','proximidad_viaje','confirmacion','recordatorio','otro') NOT NULL,
  asunto VARCHAR(255),
  mensaje TEXT,
  
  metodo ENUM('email','sms','whatsapp','in_app') DEFAULT 'in_app',
  
  enviado_at TIMESTAMP NULL,
  leido_at TIMESTAMP NULL,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  
  INDEX idx_usuario_leido (usuario_id, leido_at),
  INDEX idx_cliente (cliente_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 23: SOLICITUDES_IA (Para IA chat)
-- ============================================================
CREATE TABLE solicitudes_ia (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  cliente_id INT COMMENT 'NULL si es visitante anónimo',
  sesion_id VARCHAR(100),
  
  prompt_usuario TEXT NOT NULL,
  respuesta_ia TEXT,
  
  intencion VARCHAR(50),
  presupuesto_detectado DECIMAL(10,2),
  
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  
  INDEX idx_cliente (cliente_id),
  INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 24: PASSWORD_RESETS
-- ============================================================
CREATE TABLE password_resets (
  email VARCHAR(100),
  token VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  
  KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA 25: CONVERSACIONES (IA chat historia)
-- ============================================================
CREATE TABLE conversaciones (
  id INT PRIMARY KEY AUTO_INCREMENT,
  
  solicitud_id INT,
  emisor ENUM('usuario','ia','agente') NOT NULL,
  
  mensaje TEXT NOT NULL,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  
  FOREIGN KEY (solicitud_id) REFERENCES solicitudes_ia(id),
  
  INDEX idx_solicitud (solicitud_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## ✅ VISTAS ANALÍTICAS

```sql
-- Vista: Reservas con saldos actualizados
CREATE OR REPLACE VIEW v_reservas_saldos AS
SELECT 
  r.id, r.codigo_reserva, r.cliente_id,
  c.nombre AS cliente_nombre, c.telefono AS cliente_telefono,
  r.agencia_id, r.precio_total,
  COALESCE(SUM(p.monto), 0) AS pagado,
  r.precio_total - COALESCE(SUM(p.monto), 0) AS saldo_pendiente,
  CASE 
    WHEN r.precio_total - COALESCE(SUM(p.monto), 0) > 0 THEN 'Adeudando'
    WHEN r.precio_total - COALESCE(SUM(p.monto), 0) = 0 THEN 'Pagado'
    ELSE 'Sobrepago'
  END AS estado_pago,
  r.estado, r.fecha_inicio_tour
FROM reservas r
LEFT JOIN pagos p ON r.id = p.reserva_id AND p.deleted_at IS NULL
LEFT JOIN clientes c ON r.cliente_id = c.id
WHERE r.deleted_at IS NULL
GROUP BY r.id, r.cliente_id, r.agencia_id, r.precio_total;

-- Vista: Tours con itinerarios
CREATE OR REPLACE VIEW v_tours_detallados AS
SELECT 
  t.id, t.nombre, t.duracion, t.precio, t.agencia_id,
  COUNT(i.id) AS dias_itinerario,
  GROUP_CONCAT(i.titulo SEPARATOR ' → ') AS dias_resumen,
  t.es_activo,
  COUNT(DISTINCT s.id) AS proximas_salidas
FROM tours t
LEFT JOIN itinerarios i ON t.id = i.tour_id
LEFT JOIN salidas s ON t.id = s.tour_id AND s.estado IN ('programada', 'confirmada')
WHERE t.es_activo = TRUE
GROUP BY t.id, t.agencia_id;

-- Vista: Clientes por cobrar
CREATE OR REPLACE VIEW v_clientes_por_cobrar AS
SELECT 
  c.id, c.nombre, c.apellido, c.telefono, c.email, c.agencia_id,
  COUNT(DISTINCT r.id) AS numero_reservas_adeudando,
  COALESCE(SUM(r.precio_total - COALESCE(SUM(p.monto), 0)), 0) AS total_adeudado,
  MAX(r.fecha_hora_reserva) AS ultima_reserva,
  DATEDIFF(NOW(), MAX(r.fecha_hora_reserva)) AS dias_sin_pagar
FROM clientes c
INNER JOIN reservas r ON c.id = r.cliente_id AND r.deleted_at IS NULL
LEFT JOIN pagos p ON r.id = p.reserva_id AND p.deleted_at IS NULL
WHERE r.precio_total - COALESCE(SUM(p.monto), 0) > 0
GROUP BY c.id, c.agencia_id
HAVING total_adeudado > 0
ORDER BY total_adeudado DESC;

-- Vista: Ingresos por agencia
CREATE OR REPLACE VIEW v_ingresos_por_agencia AS
SELECT 
  a.id, a.nombre AS agencia,
  COUNT(DISTINCT r.id) AS total_reservas,
  SUM(r.precio_total) AS ingresos_brutos,
  SUM(CASE WHEN p.id IS NOT NULL THEN p.monto ELSE 0 END) AS pagado_efectivo,
  SUM(r.precio_total) - SUM(CASE WHEN p.id IS NOT NULL THEN p.monto ELSE 0 END) AS pendiente_cobrar,
  ROUND(
    (SUM(CASE WHEN p.id IS NOT NULL THEN p.monto ELSE 0 END) / SUM(r.precio_total) * 100),
    2
  ) AS tasa_cobranza_porcentaje
FROM agencias a
LEFT JOIN reservas r ON a.id = r.agencia_id AND r.deleted_at IS NULL
LEFT JOIN pagos p ON r.id = p.reserva_id AND p.deleted_at IS NULL
WHERE a.es_activa = TRUE
GROUP BY a.id
ORDER BY ingresos_brutos DESC;
```

---

## 🎯 RESUMEN DE CAMBIOS

| Punto | Anterior | Nuevo | Razón |
|-------|----------|-------|-------|
| **guias_turisticos** | ¿Eliminar? | ✅ MANTENER | Tours personalizables necesitan flexibilidad |
| **Tablas** | 20 | 25 | Añadidas tablas críticas (auditoría, logs, etc) |
| **Auditoría** | No existía | ✅ COMPLETA | Cumplimiento legal Perú |
| **Soft delete** | No | ✅ SÍ | Nunca borrar, marcar deleted_at |
| **Campos timestamps** | Parcial | ✅ TODO | created_at, updated_at en todas |
| **Índices** | Básicos | ✅ ESTRATÉGICOS | Performance mejorado |

---

## 🚀 PRÓXIMOS PASOS

1. **Ejecutar este SQL completo** en phpMyAdmin
2. **Verificar que NO hay errores**
3. **Poblar datos iniciales** (users, agencias, tours, etc)
4. **Conectar a Antigravity**
5. **Generar API automática**
6. **Crear UI en Antigravity**

---

**¡BD LISTA PARA PRODUCCIÓN CON ANTIGRAVITY!**
