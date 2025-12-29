<?php
// Sistema_New/includes/functions.php

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Genera o recupera el token CSRF de la sesión actual
 */
function csrf_token()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Devuelve el HTML para el campo oculto CSRF
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verifica si el token CSRF recibido es válido
 */
function is_csrf_valid()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}
