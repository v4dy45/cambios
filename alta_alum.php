<?php

//Faltan distractores (funciones anteriores no servian)
$temp = crypt_blowfish_inv($curp,$cct_ou,$fecha,$_SESSION['id_u']); //password_verify 1pass 2hash
$sqls = "INSERT INTO t_alumnos VALUES ('".$curp."','".$nom."','".$app."','".$apm."',NULL,'".$cct_ou."',DEFAULT,CURDATE(),'SEGUNDO',".$_SESSION['id_u'].",0,NULL,NULL,0,'".$temp."',0)";
$_SESSION['cct'] = $cct_ou;
require ("set_place.php");
?>