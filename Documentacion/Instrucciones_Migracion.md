# Instrucciones de Migración a XAMPP

Tu idea de separar `Old` (Referencia) y `New` (Desarrollo limpio) es **excelente**. Nos permitirá avanzar rápido sin arrastrar deuda técnica, pero consultando el código antiguo cuando sea necesario.

## Pasos para la Migración

### 1. Preparar el Entorno en XAMPP
1.  Ve a tu carpeta de instalación de XAMPP, usualmente: `C:\xampp\htdocs`.
2.  Crea una nueva carpeta principal para el proyecto, por ejemplo: `AgenciaTurismo`.
3.  Dentro de `AgenciaTurismo`, crea dos carpetas:
    *   `Sistema_Old`
    *   `Sistema_New`

### 2. Respaldar el Sistema Actual
1.  Ve a tu carpeta actual: `c:\Antigravity\Sistema control de agencia turismo`.
2.  Selecciona **TODO** el contenido (archivos y carpetas).
3.  Cópialo y pégalo dentro de `C:\xampp\htdocs\AgenciaTurismo\Sistema_Old`.

### 3. Preservar la Documentación (Vital para la IA)
Para que cuando abras el nuevo proyecto yo sepa qué hacer, necesitamos mover los planes que acabamos de crear.
1.  Ve a `C:\xampp\htdocs\AgenciaTurismo\Sistema_Old\Documentacion`.
2.  Copia toda la carpeta `Documentacion`.
3.  Pégala dentro de `C:\xampp\htdocs\AgenciaTurismo\Sistema_New`.
4.  También copia el archivo `database_v2.sql` a `Sistema_New`.

### 4. Cambiar de Espacio de Trabajo
1.  En tu editor, cierra la carpeta actual.
2.  Abre la carpeta raíz: `C:\xampp\htdocs\AgenciaTurismo`.
    *   *Al abrir esta carpeta padre, podré ver tanto el código viejo como el nuevo.*

### 5. Siguientes Pasos (Una vez en el nuevo entorno)
Cuando me hables en el nuevo chat, dime:
> "Ya migré los archivos. Estoy en la carpeta AgenciaTurismo. Revisa la carpeta Sistema_New/Documentacion y empecemos a crear la estructura del proyecto."

Yo leeré el plan y comenzaremos a crear los archivos `index.php`, las carpetas MVC y la conexión a la base de datos de forma limpia y ordenada.
