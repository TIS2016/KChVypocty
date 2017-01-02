<?php
session_start();
if (!isset($_SESSION['running'])){
    shell_exec("/usr/bin/php /home/tis/KChVypocty/app/ajax/ajax_handler.php &");
    echo "Running";
} else {
    echo "already running";
}

