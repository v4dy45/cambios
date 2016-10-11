<?php
//Conexion a la DB
//$conexion = mysql_connect('$database_host', '$database_username', '$database_password') or die (mysql_error());
$conexion = mysql_connect('localhost','root','configurar$do4') or die (mysql_error());
//$conexion = mysql_connect('localhost','sicam','laura') or die (mysql_error());
//$bd=mysql_select_db('$database',$conexion) or die (mysql_error());
//$bd2=mysql_select_db("directorio",$conexion) or die (mysql_error());
$bd=mysql_select_db("cambios_all",$conexion) or die (mysql_error());
// Configurar la base a guardar automáticamente.
mysql_query("SET AUTOCOMMIT=TRUE");
// Configura la transferencia de información a la base.
mysql_query("SET NAMES 'utf8' ");


//Inicializamos variable SESSION
if(!isset($_SESSION)) 
{ 
	session_start();
	/*$expireAfter = 1;
	if(isset($_SESSION['last_action']))
	{
		$secondsInactive = time() - $_SESSION['last_action'];
        $expireAfterSeconds = $expireAfter * 60;
		if($secondsInactive >= $expireAfterSeconds)
		{
			session_unset();
			session_destroy();
			echo '<script>alert ("Han pasado más de 5 minutos sin actividad. Favor de registrarse de nuevo.");</script>';
		}
    }
 	$_SESSION['last_action'] = time();*/
} 

?>