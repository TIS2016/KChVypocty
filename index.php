<?php

turnOnDisplayErrors();

function turnOnDisplayErrors()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL ^ E_NOTICE);
}

require_once __DIR__ . '/vendor/autoload.php';

// TODO zatial prazdny subor

echo "SUCCESS";









