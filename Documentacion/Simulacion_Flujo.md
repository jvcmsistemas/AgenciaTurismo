# Simulación de Flujo y Cambios en el Sistema (V2)

Este documento explica cómo la nueva base de datos transforma la experiencia de usuario (Backend y Frontend) y simula un flujo completo de datos.

## 1. Impacto en los Formularios (Backend - Agencias)

Como cada agencia gestiona sus propios datos, los formularios deben cambiar drásticamente para aprovechar la nueva flexibilidad.

### A. Formulario de "Nueva Reserva" (El cambio más grande)
**Antes:**
- Seleccionabas 1 Cliente.
- Seleccionabas 1 Tour, 1 Guía, 1 Hotel, 1 Transporte (Dropdowns simples).
- *Limitación:* No podías vender un paquete con 2 hoteles o sin transporte.

**Ahora (V2):**
- **Sección Cliente:** Buscas o creas el cliente (igual que antes).
- **Sección "Carrito de Servicios" (Dinámica):**
    - Botón `[+ Agregar Tour]`: Abre modal para seleccionar tours.
    - Botón `[+ Agregar Hotel]`: Abre modal para seleccionar hotel y fechas (Check-in/Check-out).
    - Botón `[+ Agregar Extra]`: Para guías adicionales o transporte especial.
    - *Visualmente:* Es como una factura donde vas agregando filas.
- **Sección Financiera:**
    - Muestra `Total Calculado` (suma de servicios).
    - Campo `A cuenta` (Pago inicial).

### B. Módulo de Pagos (Nuevo)
**Antes:** Solo existía un campo de texto "Método de Pago".
**Ahora:**
- Nueva pestaña o botón **"Gestionar Pagos"** en el detalle de la reserva.
- Formulario: `Monto`, `Fecha`, `Método` (Yape, Plin, Tarjeta), `Foto del Voucher`.
- Tabla de historial: Muestra todos los pagos parciales y calcula la `Deuda Pendiente`.

---

## 2. Experiencia Frontend (Cliente Final + IA)

El frontend será un portal público (tipo Booking o Airbnb de tours) donde todas las agencias exponen sus productos.

### A. Búsqueda Inteligente (IA)
- **Chat Widget:** "Hola, quiero viajar a Cusco con mi esposa, tenemos 3 días y nos gusta la aventura pero con buen hotel."
- **Procesamiento:** La IA busca en la tabla `tours` filtrando por `tags` ('aventura', 'pareja') y `ubicacion` ('Cusco').
- **Respuesta:** "Te recomiendo el paquete 'Cusco Romántico & Aventura' de la agencia *Viajes Cusco Top* y el 'Valle Sagrado VIP' de *Andes Travel*."

### B. Comparador
- El cliente ve las opciones de diferentes agencias (filtrado transparente).
- Al hacer clic en "Reservar", el sistema sabe a qué `agencia_id` pertenece el tour.

---

## 3. Simulación de Flujo de Datos (Paso a Paso)

Imaginemos el ciclo de vida completo de una venta.

### Paso 1: Registro de la Agencia (Backend)
La agencia "Cusco Expeditions" se registra.
```sql
INSERT INTO agencias (nombre, estado) VALUES ('Cusco Expeditions', 'activa');
-- ID generado: 10
INSERT INTO usuarios (email, rol, agencia_id) VALUES ('admin@cuscoexp.com', 'dueno_agencia', 10);
```

### Paso 2: Creación de Producto (Backend)
La agencia crea un Tour de 2 días.
```sql
INSERT INTO tours (nombre, precio, agencia_id, tags) 
VALUES ('Machu Picchu Express', 350.00, 10, 'cultura,rapido,machu picchu');
-- ID generado: 500

INSERT INTO itinerarios (tour_id, dia_numero, titulo) VALUES 
(500, 1, 'Viaje en Tren y Aguas Calientes'),
(500, 2, 'Subida a la Ciudadela y Retorno');
```

### Paso 3: Interacción Cliente (Frontend/IA)
El turista "Juan Pérez" chatea con la IA.
```sql
INSERT INTO solicitudes_ia (prompt_usuario, intencion) 
VALUES ('Busco tour rapido a machu picchu', 'compra');
-- La IA encuentra el Tour 500 y se lo muestra.
```

### Paso 4: Reserva (Frontend o Backend)
Juan decide comprar. Se crea la reserva y los detalles.
```sql
-- 1. Crear Cliente
INSERT INTO clientes (nombre, email, agencia_id) VALUES ('Juan Pérez', 'juan@gmail.com', 10);
-- ID generado: 2000

-- 2. Crear Cabecera de Reserva
INSERT INTO reservas (codigo_reserva, cliente_id, agencia_id, precio_total, saldo_pendiente) 
VALUES ('RES-10-999', 2000, 10, 350.00, 350.00);
-- ID generado: 8000

-- 3. Agregar el Tour a la Reserva (Tabla Pivote)
INSERT INTO reserva_detalles (reserva_id, tipo_servicio, servicio_id, precio_unitario) 
VALUES (8000, 'tour', 500, 350.00);
```

### Paso 5: Pago Parcial (Backend o Pasarela)
Juan paga $100 de adelanto por Yape.
```sql
-- Registrar Pago
INSERT INTO pagos (reserva_id, monto, metodo_pago, referencia) 
VALUES (8000, 100.00, 'yape', 'OPE-123456');

-- Actualizar Saldo en Reserva (Trigger o Lógica de Código)
UPDATE reservas SET saldo_pendiente = 250.00 WHERE id = 8000;
```

### Resultado Final
- La **Agencia 10** ve en su Dashboard: "Nueva Reserva de Juan Pérez. Pagado: $100. Pendiente: $250".
- Las otras agencias **NO** ven nada de esto.
- El sistema escala: Si Juan quisiera agregar una noche extra de hotel, solo insertamos una fila más en `reserva_detalles` vinculada a la misma reserva `8000`.
