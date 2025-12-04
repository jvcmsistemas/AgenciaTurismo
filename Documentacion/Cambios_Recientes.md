# Registro de Cambios y Organización

## Fecha: 2025-12-01

### Correcciones
- **Error de Conexión**: Se corrigió el archivo `config/db.php` que estaba corrupto y causaba el error `Undefined variable $pdo`. Se restauraron las credenciales y la inicialización de PDO.

### Organización de Archivos
- **Carpeta `database/`**: Se creó esta carpeta para agrupar todos los scripts SQL y mantener la raíz del proyecto limpia.
    - `database_v2.sql`: Esquema principal.
    - `database_seed.sql`: Datos de prueba iniciales.
    - `database_update_roles.sql`: Actualización para roles y suscripciones.

### Estructura Actualizada
```
Sistema_New/
├── config/
│   └── db.php
├── database/           <-- NUEVA CARPETA
│   ├── database_v2.sql
│   ├── database_seed.sql
│   └── database_update_roles.sql
├── controllers/
├── models/
├── views/
├── public/
├── includes/
├── index.php
└── .htaccess
```
