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
