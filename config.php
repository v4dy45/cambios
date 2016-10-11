<?php
// Variables del sistema
// El server corre en 172.18.63.225 pero no es necesario actualizarlo :-/
//$database_host = "localhost"; // Host name
//$database_username = "cambios"; // Mysql username 
//$database_password = "configurar$do4"; // Mysql password 
//$database_username = "sysweb"; // Mysql username 
//$database_password = "websys"; // Mysql password 
//$database = "cambios"; // Database name 
//$userstable = "t_usuarios"; // Table name 

// Configura el lenguaje 
setlocale(LC_ALL, 'es_ES.UTF-8');

// Configura la zona horaria
$time_zone = 'America/Mexico_City';
date_default_timezone_set($time_zone);

// Variables globales
global $fh_actual;
$fh_actual[0] = date("Y-m-d"); // String
$fh_actual[1] = date("H:i:s"); // String
$fh_actual[2] = time(); // int (seconds since the epoch)
$max_días = " -20 days";
require ('config_all.php');
//if ($_val[0]=1)
//{$_deprint[0] = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $cle, $_val[0], MCRYPT_MODE_ECB, $iv);$_deprint[1] = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $cle, $_val[1], MCRYPT_MODE_ECB, $iv); }

?>