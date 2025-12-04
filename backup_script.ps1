# Script de Respaldo Automático para Sistema_New
# Guarda una copia del código y la base de datos

$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm"
$sourceDir = "C:\xampp\htdocs\AgenciaTurismo\Sistema_New"
$backupDir = "C:\xampp\htdocs\AgenciaTurismo\Backups"
$backupFile = "$backupDir\Backup_Sistema_$timestamp.zip"
$dbUser = "root" # Cambiar si es necesario
$dbName = "agencia_turismo_db" # Confirmar nombre de BD
$sqlFile = "$sourceDir\backup_db_$timestamp.sql"

# 1. Crear directorio de backups si no existe
if (!(Test-Path -Path $backupDir)) {
    New-Item -ItemType Directory -Path $backupDir | Out-Null
    Write-Host "Directorio de backups creado: $backupDir" -ForegroundColor Green
}

# 2. Exportar Base de Datos (requiere mysqldump en PATH o ruta completa)
$mysqldump = "C:\xampp\mysql\bin\mysqldump.exe"
if (Test-Path $mysqldump) {
    Write-Host "Exportando base de datos..." -ForegroundColor Cyan
    & $mysqldump -u $dbUser $dbName > $sqlFile
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Base de datos exportada correctamente." -ForegroundColor Green
    }
    else {
        Write-Host "Error al exportar base de datos. Verifique credenciales." -ForegroundColor Red
    }
}
else {
    Write-Host "No se encontró mysqldump en la ruta estándar de XAMPP." -ForegroundColor Yellow
}

# 3. Comprimir Archivos
Write-Host "Comprimiendo archivos del sistema..." -ForegroundColor Cyan
Compress-Archive -Path $sourceDir -DestinationPath $backupFile -Force

# 4. Limpieza (borrar SQL temporal)
if (Test-Path $sqlFile) {
    Remove-Item $sqlFile
}

Write-Host "Respaldo completado: $backupFile" -ForegroundColor Green
Start-Sleep -Seconds 3
