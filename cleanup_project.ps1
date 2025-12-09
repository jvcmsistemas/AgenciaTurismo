# Cleanup Script for Agencia Turismo Project

# 1. Define deprecated files in root
$garbage = @(
    "add_discount_col.php",
    "add_salida_id_to_reservas.php",
    "backup_script.ps1",
    "check_details_table.php",
    "check_provider_table.php",
    "check_reservas.php",
    "create_departures_table.php",
    "create_pagos_table.php",
    "create_reserva_detalles.php",
    "debug_res.php",
    "find_agency_id.php",
    "find_target_agency.php",
    "fix_res_3.php",
    "fix_resources_schema.php",
    "fix_salidas_schema.php",
    "fix_transport_table.php",
    "inspect_clientes.php",
    "inspect_reservas_schema.php",
    "install_missing_tables.txt",
    "install_missing_tables_robust.txt",
    "install_new_db.txt",
    "migration_agency.txt",
    "seed_agency_resources.php",
    "seed_agency_tours.php",
    "seed_clients.php",
    "seed_initial_data.txt",
    "seed_providers.php",
    "seed_super_admin.txt",
    "setup_agency_module.php",
    "setup_db.php",
    "synchronize_db.txt",
    "test_search_api.php"
)

# 2. Move Schema Definition to Documentation
if (Test-Path "bd-completa-mejorada.md") {
    Move-Item "bd-completa-mejorada.md" "Documentacion/Schema_Reference.md" -Force
    Write-Output "Moved Schema Doc to Documentacion folder."
}

# 3. Delete Root Garbage
foreach ($file in $garbage) {
    if (Test-Path $file) {
        Remove-Item $file -Force
        Write-Output "Deleted root file: $file"
    }
}

# 4. Clean Documentation Folder (Archiving old docs)
$oldDocs = @(
    "Cambios_Recientes.md",
    "Informe_Avance_y_Planes.md",
    "Instrucciones_Migracion.md",
    "Plan_Implementacion.md",
    "Simulacion_Flujo.md"
)

foreach ($doc in $oldDocs) {
    $path = "Documentacion/$doc"
    if (Test-Path $path) {
        Remove-Item $path -Force
        Write-Output "Deleted old doc: $doc"
    }
}

Write-Output "Cleanup Complete. Project Structure is clean."
