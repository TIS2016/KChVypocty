<?php
require_once __DIR__ . '/vendor/autoload.php';
turnOnDisplayErrors();
function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

// just for testing default logged in by default
$_SESSION['logged_in'] = true;

if (isset($_SESSION['logged_in'])){
    header("Location: app/views/table_index.php");
} else {
    header("Location: app/views/login.php");
}




