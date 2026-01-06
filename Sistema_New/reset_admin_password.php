<?php
require_once 'config/db.php';

try {
    $email = 'superadmin@system.com';
    $newPassword = '123456';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET contrasena = :password WHERE email = :email");
    $stmt->execute(['password' => $hashedPassword, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        echo "✅ Password updated for $email to '$newPassword'\n";
    } else {
        echo "⚠️ User $email not found or password already set.\n";
    }

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
