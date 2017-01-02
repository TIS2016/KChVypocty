<?php
session_start();
if ($_SESSION['running'] != 1){
    shell_exec("/usr/bin/php /home/tis/KChVypocty/app/ajax/ajax_handler.php &");
    echo "running";
} else {
    echo "already running";
}

