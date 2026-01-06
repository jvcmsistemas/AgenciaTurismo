# 🎯 ESTRUCTURA DE MENÚS - SUPERADMIN vs ADMIN AGENCIA

**Sistema:** Agencia Turismo Multi-Tenant  
**Fecha:** 8 Diciembre 2025  
**Objetivo:** Menús optimizados con los cambios de BD implementados

---

## 🔐 SUPERADMIN PANEL (Oscuro - Dueño del Software)

Login: `superadmin@system.com` | Pass: `password`

### MENÚ PRINCIPAL SUPERADMIN

```
┌─────────────────────────────────────────┐
│ 🏢 SUPERADMIN PANEL (LOGIN OSCURO)      │
├─────────────────────────────────────────┤

📊 DASHBOARD
├─ KPI Global (Ingresos totales todas agencias)
├─ Agencias activas/inactivas
├─ Usuarios por agencia
└─ Gráficos de suscripciones

🏢 AGENCIAS (NUEVO MENÚ COMPLETO)
├─ Lista de Agencias
│  ├─ Crear agencia nueva
│  ├─ Editar agencia
│  ├─ Ver detalles
│  ├─ Cambiar estado (activa/inactiva/suspendida)
│  └─ Eliminar agencia
├─ Suscripciones (CRÍTICO)
│  ├─ Ver plan de cada agencia
│  ├─ Cambiar plan (prueba → semestral → anual)
│  ├─ Fecha de vencimiento
│  ├─ Renovar automáticamente SÍ/NO
│  └─ Enviar recordatorio de vencimiento
├─ Pagos de Agencias
│  ├─ Historial de pagos
│  ├─ Pendientes de pago
│  ├─ Marcar como pagado
│  └─ Generar invoice
└─ Auditoría de Agencias
   ├─ Cuándo fue creada cada agencia
   ├─ Cambios en datos de agencia
   ├─ Accesos de admin agencia
   └─ Actividad sospechosa

👥 USUARIOS GLOBALES
├─ Lista de todos los usuarios
│  ├─ Superadmins
│  ├─ Admins de Agencias
│  ├─ Empleados de Agencias
│  └─ Guías
├─ Crear usuario
├─ Editar usuario
├─ Cambiar rol
├─ Resetear contraseña
├─ Bloquear/Desbloquear usuario
└─ Auditoría de usuarios
   ├─ Accesos por usuario
   ├─ Cambios realizados
   └─ IP origen de accesos

💰 PAGOS & FACTURACIÓN (NIVEL SISTEMA)
├─ Ingresos totales
│  ├─ Por período (mes, trimestre, año)
│  ├─ Por plan (prueba, semestral, anual)
│  └─ Por método de pago
├─ Facturas generadas
├─ Reportes de ingresos
└─ Proyecciones de MRR/ARR

📋 PLANES DE SUSCRIPCIÓN (ADMIN)
├─ Ver planes actuales
│  ├─ Prueba (1 mes gratis)
│  ├─ Semestral
│  └─ Anual
├─ Crear nuevo plan
├─ Editar precios/features
└─ Ver agencias por plan

🔒 SEGURIDAD & AUDITORÍA
├─ Logs de acceso (todos los usuarios)
├─ Auditoría de cambios (todos los registros)
├─ Cambios sospechosos
├─ Intentos de acceso fallido
└─ Cambios de IP origen

⚙️ CONFIGURACIÓN SISTEMA
├─ Configuración de aplicación
├─ Emails (SMTP)
├─ URLs y dominios
├─ Branding
├─ Integraciones (WhatsApp, etc)
└─ Backup & Restore

📊 REPORTES GLOBALES
├─ Agencias más activas
├─ Tours más populares
├─ Clientes más valiosos
├─ Tasa de conversión global
└─ Performance por agencia

🆘 SOPORTE
├─ Tickets de soporte
├─ Chat con agencias
├─ FAQs
└─ Documentación

└─ CERRAR SESIÓN / PERFIL
```

---

## 🏢 ADMIN AGENCIA PANEL (Verde - Dueño Agencia)

Login: `admin@agencia.com` | Pass: `password`

### MENÚ PRINCIPAL ADMIN AGENCIA

```
┌─────────────────────────────────────────┐
│ 🏢 ADMIN AGENCIA PANEL (LOGIN VERDE)    │
├─────────────────────────────────────────┤

📊 DASHBOARD
├─ KPIs Agencia
│  ├─ Ingresos este mes
│  ├─ Reservas pendientes
│  ├─ Clientes activos
│  ├─ Tours programados
│  └─ Saldo adeudado
├─ Gráficos
│  ├─ Ingresos últimos 30 días
│  ├─ Tours más vendidos
│  ├─ Distribución de pagos
│  └─ Tasa de ocupación
├─ Alertas
│  ├─ Reservas sin confirmar
│  ├─ Pagos pendientes > 15 días
│  ├─ Tours próximos a salir
│  └─ Guías sin disponibilidad
└─ Acciones rápidas
   ├─ + Nueva reserva
   ├─ + Registrar pago
   ├─ + Crear salida
   └─ Ver reportes

📅 RESERVAS (MÓDULO PRINCIPAL)
├─ Listar reservas
│  ├─ Filtrar por estado (pendiente/confirmada/en_curso/completada/cancelada)
│  ├─ Filtrar por fecha
│  ├─ Filtrar por cliente
│  ├─ Filtrar por tour
│  ├─ Búsqueda por código
│  └─ Tabla con detalles
├─ Crear reserva nueva
│  ├─ Seleccionar cliente (o crear nuevo)
│  ├─ Seleccionar tour
│  ├─ Tipo: tour regular o personalizado
│  ├─ Si personalizado:
│  │  ├─ Seleccionar guía_turistico
│  │  ├─ Precio customizado
│  │  ├─ Itinerario customizado
│  │  └─ Salida fija
│  ├─ Si regular:
│  │  ├─ Seleccionar salida existente
│  │  └─ Precio por defecto
│  ├─ Cantidad personas
│  ├─ Descuentos (si aplica)
│  ├─ Notas
│  └─ Guardar
├─ Editar reserva
│  ├─ Cambiar fecha/hora
│  ├─ Cambiar cliente
│  ├─ Agregar/quitar personas
│  ├─ Ajustar precio
│  └─ Actualizar notas
├─ Ver detalle reserva
│  ├─ Info cliente
│  ├─ Info tour
│  ├─ Info guía asignado
│  ├─ Historial de cambios (auditoría)
│  ├─ Pagos asociados
│  └─ Comentarios
├─ Acciones por reserva
│  ├─ Confirmar reserva
│  ├─ Cancelar (con motivo)
│  ├─ Cambiar estado
│  ├─ Marcar como completada
│  ├─ Enviar email/WhatsApp cliente
│  ├─ Generar PDF comprobante
│  └─ Duplicar para cliente
├─ Auditoría de reservas
│  ├─ Ver TODO cambio de precio
│  ├─ Ver TODO descuento aplicado
│  ├─ Ver TODO cancelación
│  ├─ Ver motivos de cambios
│  └─ Exportar auditoría
└─ Reportes reservas
   ├─ Por período
   ├─ Por tour
   ├─ Por guía
   └─ Estado actual

💰 PAGOS (MÓDULO CRÍTICO)
├─ Resumen pagos
│  ├─ Total cobrado mes actual
│  ├─ Pendiente de cobrar
│  ├─ Tasa de cobranza %
│  └─ Proyección ingresos
├─ Clientes por cobrar
│  ├─ Tabla con deuda por cliente
│  ├─ Días sin pagar
│  ├─ Número de reservas adeudando
│  ├─ Botón "Enviar recordatorio"
│  └─ Botón "Registrar pago"
├─ Registrar pago
│  ├─ Seleccionar cliente/reserva
│  ├─ Monto
│  ├─ Método (efectivo/tarjeta/transferencia/yape/plin)
│  ├─ Referencia (nro operación)
│  ├─ Comprobante (foto/PDF)
│  ├─ Nota
│  └─ Guardar
├─ Historial pagos
│  ├─ Tabla con todos los pagos
│  ├─ Filtrar por estado (aprobado/rechazado/pendiente)
│  ├─ Filtrar por método
│  ├─ Filtrar por período
│  ├─ Ver detalles pago
│  └─ Editar/anular pago
├─ Auditoría pagos
│  ├─ Ver TODA operación de pago
│  ├─ Quién registró cada pago
│  ├─ Cuándo se registró
│  ├─ IP origen
│  ├─ Cambios posteriores (si los hay)
│  └─ Motivos de rechazo
├─ Generar reportes
│  ├─ Por período
│  ├─ Por cliente
│  ├─ Por método pago
│  └─ Exportar Excel
└─ Recordatorios de pago
   ├─ Enviar email a deudores
   ├─ Enviar WhatsApp
   └─ Registro de envíos

👥 CLIENTES
├─ Listar clientes
│  ├─ Búsqueda por nombre/DNI
│  ├─ Filtrar activos/inactivos
│  ├─ Ver total gasto por cliente
│  ├─ Ver saldo adeudado
│  └─ Tabla completa
├─ Crear cliente nuevo
│  ├─ Datos personales (nombre, apellido, DNI, pasaporte)
│  ├─ Contacto principal (teléfono, celular, email)
│  ├─ Contactos alternos (familiares, amigos)
│  ├─ Información demográfica (ciudad, país, edad)
│  ├─ Preferencias (tipo tour favorito, idioma)
│  └─ Notas
├─ Editar cliente
│  ├─ Actualizar datos
│  ├─ Agregar/eliminar contactos
│  ├─ Cambiar preferencias
│  └─ Cambiar estado
├─ Ver perfil cliente
│  ├─ Datos personales
│  ├─ Historial reservas
│  ├─ Pagos realizados
│  ├─ Saldo actual
│  ├─ Calificaciones dadas
│  ├─ Tours favoritos
│  └─ Contactos asociados
├─ Acciones por cliente
│  ├─ + Nueva reserva
│  ├─ Registrar pago
│  ├─ Enviar email
│  ├─ Enviar WhatsApp
│  ├─ Solicitar feedback
│  ├─ Crear contacto alternativo
│  └─ Bloquear/Desbloquear
└─ Segmentación clientes
   ├─ Por gasto (altos/medios/bajos)
   ├─ Por frecuencia (frecuentes/ocasionales)
   ├─ Por origen (referencia/web/presencial)
   └─ Crear grupo personalizado

🎫 TOURS (GESTIÓN DE CATÁLOGO)
├─ Listar tours
│  ├─ Tours regulares
│  ├─ Tours personalizables
│  ├─ Filtrar activos/inactivos
│  └─ Ver precio y disponibilidad
├─ Crear tour nuevo
│  ├─ Nombre y descripción
│  ├─ Ubicación
│  ├─ Duración
│  ├─ Precio
│  ├─ Cupo mín/máx
│  ├─ Dificultad
│  ├─ Idiomas
│  ├─ Imagen/foto
│  ├─ Itinerario (día a día)
│  │  ├─ Día 1: título, actividades, horarios
│  │  ├─ Día 2: título, actividades, horarios
│  │  └─ Día N: etc
│  ├─ Incluidos (qué está incluido)
│  ├─ Exclusiones (qué NO está incluido)
│  ├─ Política de cancelación
│  ├─ ¿Personalizable? SÍ/NO
│  └─ Guardar
├─ Editar tour
│  ├─ Modificar todos los campos
│  ├─ Ver cambios en auditoría
│  └─ Actualizar
├─ Cambios de precio
│  ├─ Cambiar precio
│  ├─ Especificar motivo
│  ├─ Registra automáticamente en auditoría
│  └─ Ver historial de precios
├─ Gestión promociones
│  ├─ Crear promoción
│  ├─ Descuento %
│  ├─ Fecha inicio/fin
│  └─ Tours aplicables
└─ Ver tour en catálogo
   └─ Cómo lo ven los clientes

📍 SALIDAS (TOURS PROGRAMADOS)
├─ Listar salidas programadas
│  ├─ Calendario de salidas
│  ├─ Próximas 30 días
│  ├─ Filtrar por tour
│  ├─ Filtrar por estado (programada/confirmada/cerrada/en_curso/completada)
│  └─ Ver cupos disponibles
├─ Crear salida nueva
│  ├─ Seleccionar tour
│  ├─ Fecha y hora de salida
│  ├─ Fecha y hora de regreso
│  ├─ Seleccionar guía principal
│  ├─ Agregar guías secundarios (si aplica)
│  ├─ Seleccionar transporte
│  ├─ Cupos disponibles
│  ├─ Precio (por defecto del tour)
│  ├─ Tipo (compartida/privada)
│  └─ Guardar
├─ Editar salida
│  ├─ Cambiar fecha/hora
│  ├─ Cambiar guía
│  ├─ Cambiar transporte
│  ├─ Cerrar/reabrir cupos
│  └─ Actualizar
├─ Participantes en salida
│  ├─ Ver clientes confirmados
│  ├─ Ver clientes asistieron/faltaron
│  ├─ Recolectar calificaciones
│  ├─ Ver comentarios
│  └─ Generar lista de asistencia
├─ Marcar como completada
│  ├─ Cambiar estado a "completada"
│  ├─ Bloquear cambios
│  └─ Activar encuestas de satisfacción
└─ Auditoría salidas
   ├─ Ver cambios en fechas/guías
   ├─ Ver cambios de capacidad
   └─ Historial de modificaciones

👨‍✈️ GUÍAS
├─ Listar guías (solo empleados)
│  ├─ Búsqueda por nombre
│  ├─ Filtrar activos/inactivos
│  ├─ Ver disponibilidad
│  ├─ Ver rating
│  ├─ Ver próximos tours
│  └─ Tabla con detalles
├─ Crear guía nuevo
│  ├─ Datos personales
│  ├─ Documento (DNI/Pasaporte)
│  ├─ Contacto
│  ├─ Especialidad
│  ├─ Experiencia (años)
│  ├─ Idiomas
│  ├─ Certificaciones
│  ├─ Tarifa diaria
│  ├─ Disponibilidad (desde/hasta)
│  ├─ Es empleado SÍ/NO
│  └─ Guardar
├─ Editar guía
│  ├─ Actualizar información
│  ├─ Cambiar disponibilidad
│  ├─ Cambiar tarifa
│  ├─ Ver auditoría de cambios
│  └─ Guardar
├─ Ver perfil guía
│  ├─ Datos personales
│  ├─ Tours asignados (próximos)
│  ├─ Tours completados (histórico)
│  ├─ Calificación promedio
│  ├─ Comentarios de clientes
│  ├─ Disponibilidad
│  ├─ Certificados válidos
│  └─ Documentos vigentes
├─ Asignar salida a guía
│  ├─ Seleccionar salida
│  ├─ Asignar guía
│  ├─ Rol (principal/secundario)
│  └─ Guardar
└─ Guías Turísticos (Pool Flexible)
   ├─ Listar guías turísticos
   ├─ Crear guía turístico (freelancer)
   ├─ Editar guía turístico
   ├─ Asignar a tour personalizado
   └─ Ver tours asignados

🚐 TRANSPORTES
├─ Listar vehículos
│  ├─ Tipo (minivan, bus, auto)
│  ├─ Capacidad
│  ├─ Estado (activo/inactivo/mantenimiento)
│  ├─ Próximo mantenimiento
│  ├─ Documentos vigentes
│  └─ Tabla completa
├─ Crear vehículo
│  ├─ Tipo
│  ├─ Modelo
│  ├─ Placa
│  ├─ Capacidad
│  ├─ Chofer
│  ├─ Fechas vencimiento (SOAT, licencia, revisión técnica)
│  └─ Guardar
├─ Editar vehículo
│  ├─ Actualizar datos
│  ├─ Cambiar estado
│  ├─ Renovar documentos
│  └─ Guardar
├─ Asignar transporte a salida
│  └─ Seleccionar salida y vehículo
└─ Alertas documentos
   ├─ Vencimientos próximos
   ├─ Documentos vencidos
   └─ Recordatorios de renovación

🏨 PROVEEDORES
├─ Hoteles
│  ├─ Listar hoteles
│  ├─ Crear/editar
│  ├─ Ver tours que usan
│  └─ Rating y comentarios
├─ Restaurantes
│  ├─ Listar restaurantes
│  ├─ Crear/editar
│  ├─ Ver tours que incluyen
│  └─ Menús disponibles
├─ Otros proveedores
│  ├─ Listar proveedores
│  ├─ Crear/editar
│  ├─ Contactos
│  ├─ Documentos (contrato)
│  └─ Vigencia

📊 REPORTES & ANALÍTICOS
├─ Ingresos
│  ├─ Por período (día/semana/mes/año)
│  ├─ Por tour
│  ├─ Por guía
│  ├─ Por cliente
│  ├─ Tasa de cobranza
│  └─ Proyecciones
├─ Operacionales
│  ├─ Tours más vendidos
│  ├─ Tours con baja ocupación
│  ├─ Guías más demandados
│  ├─ Tasa de ocupación por tour
│  └─ Análisis de disponibilidad
├─ Clientes
│  ├─ Cliente más valioso
│  ├─ Clientes nuevos
│  ├─ Churn (cancelaciones)
│  ├─ LTV (lifetime value)
│  └─ Segmentación
├─ Satisfacción
│  ├─ Rating promedio
│  ├─ Comentarios (positivos/negativos)
│  ├─ NPS (Net Promoter Score)
│  └─ Temas de quejas
└─ Exportar reportes
   ├─ Excel
   ├─ PDF
   └─ Email automático

🔒 SEGURIDAD & AUDITORÍA (AGENCIA)
├─ Logs de acceso
│  ├─ Quién entró y cuándo
│  ├─ Desde qué IP
│  ├─ Qué dispositivo
│  └─ Actividad realizada
├─ Auditoría de cambios
│  ├─ Ver TODO cambio en la agencia
│  ├─ Quién hizo qué
│  ├─ Cuándo exacto
│  ├─ Desde dónde
│  ├─ Por qué (motivo)
│  ├─ Antes vs después
│  └─ Exportar para cumplimiento legal
├─ Cambios de precio (auditoría)
│  ├─ Historial completo
│  ├─ Quién y cuándo cambió
│  ├─ Motivo del cambio
│  ├─ Impacto en reservas
│  └─ Revertir cambio si es necesario
└─ Cambios de descuentos
   ├─ Historial de descuentos
   ├─ Por quién se aplicaron
   ├─ Motivos
   └─ Total impactado

👥 USUARIOS DE LA AGENCIA
├─ Listar empleados
│  ├─ Nombre, rol, email
│  ├─ Último acceso
│  ├─ Estado (activo/inactivo)
│  └─ Permisos
├─ Crear usuario nuevo
│  ├─ Nombre, email
│  ├─ Rol (empleado_agencia, guia)
│  ├─ Permisos específicos
│  ├─ Generar contraseña temporal
│  └─ Enviar email de invitación
├─ Editar usuario
│  ├─ Cambiar rol
│  ├─ Cambiar permisos
│  ├─ Cambiar estado
│  └─ Resetear contraseña
├─ Permisos granulares
│  ├─ Ver reservas: SÍ/NO
│  ├─ Crear reservas: SÍ/NO
│  ├─ Registrar pagos: SÍ/NO
│  ├─ Ver auditoría: SÍ/NO (admin only)
│  ├─ Cambiar precios: SÍ/NO
│  └─ Aplicar descuentos: SÍ/NO
└─ Auditoría de usuarios
   ├─ Actividad por usuario
   ├─ Cambios realizados
   └─ Accesos desde IPs raras

⚙️ CONFIGURACIÓN AGENCIA
├─ Información de agencia
│  ├─ Nombre
│  ├─ Logo
│  ├─ Descripción
│  ├─ Ubicación
│  ├─ Contacto
│  └─ Web
├─ Suscripción
│  ├─ Plan actual (prueba/semestral/anual)
│  ├─ Fecha vencimiento
│  ├─ Próxima renovación
│  ├─ Botón "Cambiar plan"
│  └─ Botón "Renovar ahora"
├─ Integraciones
│  ├─ WhatsApp (conectar)
│  ├─ Email (SMTP)
│  ├─ Calendario (Google/Outlook)
│  └─ Métodos de pago
├─ Branding
│  ├─ Logo
│  ├─ Colores primarios
│  ├─ Email templates
│  └─ SMS templates
└─ Políticas
   ├─ Cancelación
   ├─ Reembolsos
   └─ Términos

📞 SOPORTE
├─ Contactar soporte
├─ Tickets de soporte
│  ├─ Crear ticket
│  ├─ Ver respuestas
│  ├─ Historial
│  └─ Estado
├─ Centro de ayuda
│  ├─ FAQs
│  ├─ Tutoriales
│  ├─ Documentación
│  └─ Videos
└─ Chat en vivo
   └─ Hablar con soporte

└─ CERRAR SESIÓN / PERFIL
   ├─ Mi perfil
   ├─ Cambiar contraseña
   └─ Cerrar sesión
```

---

## 📋 COMPARACIÓN MENÚS

| Funcionalidad | Superadmin | Admin Agencia |
|---------------|:----------:|:-------------:|
| **Dashboard** | Global (todas agencias) | Agencia específica |
| **Agencias** | Gestión total (CRUD) | Ver solo su agencia |
| **Usuarios** | Todos los usuarios | Solo empleados su agencia |
| **Reservas** | Ver (todas agencias) | Gestión total (su agencia) |
| **Pagos** | Solo reportes | Gestión + auditoría |
| **Planes** | Crear/editar | Ver + renovar |
| **Auditoría** | Global | Solo su agencia |
| **Suscripción** | Gestionar todas | Ver/renovar la suya |

---

## 🔑 NUEVOS ÍTEMS POR CAMBIOS DE BD

### Para SUPERADMIN (nuevos con BD mejorada):

```
✅ Suscripciones de Agencias (CRÍTICO)
   ├─ Plans menu
   ├─ Vencimientos
   ├─ Renovaciones
   └─ Pagos

✅ Auditoría Global
   ├─ Todos los cambios de todas agencias
   ├─ Filtrar por agencia
   ├─ Filtrar por tipo cambio
   └─ Detectar fraudes

✅ Logs de Acceso Global
   ├─ Quién accedió cuándo
   ├─ Desde dónde
   ├─ Qué hizo
   └─ Detección de anomalías
```

### Para ADMIN AGENCIA (nuevos con BD mejorada):

```
✅ Auditoría de Reservas
   ├─ Ver TODO cambio en precio
   ├─ Ver TODO descuento
   ├─ Ver TODO cancelación
   └─ Motivos registrados

✅ Auditoría de Pagos
   ├─ Ver TODA operación
   ├─ Quién registró
   ├─ Desde dónde
   └─ Cambios posteriores

✅ Guías Turísticos (Pool Flexible)
   ├─ Listar guías freelance
   ├─ Crear/editar
   ├─ Asignar a tours
   └─ Tarifas personalizadas

✅ Tours Personalizables
   ├─ Crear tours "a medida"
   ├─ Asignar guía flexible
   ├─ Precio personalizado
   └─ Itinerario custom

✅ Historial de Precios
   ├─ Ver TODO cambio
   ├─ Quién cambió
   ├─ Cuándo
   ├─ Por qué
   └─ Revertir si es necesario
```

---

## 🎨 ESTRUCTURA VISUAL RECOMENDADA

### SUPERADMIN (Esquema OSCURO)

```
┌─────────────────────────────────────┐
│ 🏢 SUPERADMIN PANEL                  │
│ superadmin@system.com                │
├─────────────────────────────────────┤
│                                     │
│ ☰ MENÚ (Vertical a la izquierda)   │
│ ├─ 📊 Dashboard                     │
│ ├─ 🏢 Agencias                      │
│ ├─ 👥 Usuarios                      │
│ ├─ 💰 Pagos & Facturación          │
│ ├─ 📋 Planes de Suscripción         │
│ ├─ 🔒 Seguridad & Auditoría        │
│ ├─ ⚙️  Configuración               │
│ ├─ 📊 Reportes                      │
│ └─ 🆘 Soporte                       │
│                                     │
│         CONTENIDO PRINCIPAL         │
│                                     │
│         (Cambios según menú)        │
│                                     │
└─────────────────────────────────────┘

Colores: Fondo gris oscuro, texto blanco
Iconos: FontAwesome o similares
Mobile: Menú hamburguesa
```

### ADMIN AGENCIA (Esquema VERDE)

```
┌─────────────────────────────────────┐
│ 🏢 MI AGENCIA                        │
│ admin@agencia.com | Plan: Anual     │
├─────────────────────────────────────┤
│                                     │
│ ☰ MENÚ (Vertical a la izquierda)   │
│ ├─ 📊 Dashboard                     │
│ ├─ 📅 Reservas                      │
│ ├─ 💰 Pagos                         │
│ ├─ 👥 Clientes                      │
│ ├─ 🎫 Tours                         │
│ ├─ 📍 Salidas                       │
│ ├─ 👨‍✈️  Guías                        │
│ ├─ 🚐 Transportes                   │
│ ├─ 🏨 Proveedores                   │
│ ├─ 📊 Reportes                      │
│ ├─ 🔒 Auditoría                     │
│ ├─ 👥 Usuarios Agencia              │
│ ├─ ⚙️  Configuración               │
│ └─ 📞 Soporte                       │
│                                     │
│         CONTENIDO PRINCIPAL         │
│                                     │
│         (Cambios según menú)        │
│                                     │
└─────────────────────────────────────┘

Colores: Verde principal, blanco, grises
Tema: Profesional pero amigable
Mobile: Menú hamburguesa + fixed bottom nav
```

---

## 📱 RESPONSIVE DESIGN

### DESKTOP (1200px+)
```
Sidebar fijo (300px) + Contenido (900px)
Menú horizontal opciones
Tablas con scroll horizontal
```

### TABLET (768px-1199px)
```
Sidebar colapsable (hamburguesa)
Menú optimizado
Tablas con columnas ajustadas
```

### MOBILE (< 768px)
```
Sidebar oculto (menú hamburguesa)
Bottom navigation bar (5 ítems principales)
Tablas con scroll horizontal
Botones grandes (48px mín)
```

---

## 🔐 CONTROL DE ACCESO (Permisos)

### SUPERADMIN
- ✅ Acceso TOTAL a todo
- ✅ No hay restricciones
- ✅ Ver todas agencias
- ✅ Editar planes/suscripciones

### ADMIN AGENCIA
- ✅ Acceso SOLO a su agencia
- ✅ NO puede ver otras agencias
- ✅ NO puede cambiar su plan (solo ver)
- ✅ Puede renovar cuando vence
- ✅ Todas operaciones auditadas

### EMPLEADO AGENCIA
- ✅ Acceso limitado según permisos
- ✅ NO puede ver auditoría (salvo admin)
- ✅ NO puede cambiar precios (salvo permisos)
- ✅ NO puede aplicar descuentos (salvo permisos)

### GUÍA
- ✅ Solo ver sus próximos tours
- ✅ Ver rutas/itinerarios
- ✅ Enviar fotos/videos
- ✅ Ver calificaciones

---

## 🚀 IMPLEMENTACIÓN RECOMENDADA

### FASE 1 (Semana 1): Core
```
✅ Dashboard (ambos)
✅ Agencias (superadmin)
✅ Usuarios (ambos)
✅ Login/Logout
✅ Cambio contraseña
```

### FASE 2 (Semana 2): Operacionales
```
✅ Reservas (admin agencia)
✅ Clientes (admin agencia)
✅ Tours (admin agencia)
✅ Salidas (admin agencia)
```

### FASE 3 (Semana 3): Financiero
```
✅ Pagos (admin agencia + superadmin)
✅ Facturación (superadmin)
✅ Suscripciones (superadmin)
✅ Reportes ingresos
```

### FASE 4 (Semana 4+): Complementarios
```
✅ Guías (admin agencia)
✅ Transportes (admin agencia)
✅ Proveedores (admin agencia)
✅ Auditoría completa
✅ Reportes analíticos
✅ Integraciones (WhatsApp, Email)
```

---

**¿Necesitas que profundice en algún módulo específico?**

Próximo paso: Definir estructura BD con autenticación de usuarios (roles, permisos, tokens JWT).
