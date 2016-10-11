<!DOCTYPE html>
<!-- index.php -->

<!--
Cambios en php.ini + reinicio del servicio Apache:

register_globals = Off
session.use_cookies = 1
session.cookie_secure = 0 // VERY IMPORTANT !!!
session.use_only_cookies = 1 // 1 is optimal but only 0 works, waiting for firefox
session.auto_start = 1 // 1 works for lazy coders, 0 good but more coding
...
session.use_trans_sid = 0 // 1 works but very insecure, 0 optimal
-->

<html lang="es" id="cambios">
<head>
<?php
//charge contents of head
require ('config.php');
require ('conex.php');
require ('functions.php');
if ($_SESSION['aut'] == 0){	_frog('index.php');}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
<!--
-->
</script>
</head>

<?php
///que esten caducos y NO validados
$fecha = date('Y-m-d');
$fecha_aux = date('Y-m-d', strtotime($fecha.$max_dias));
/*
aux0
aux1
*/
//inicializando variables durante la session tipo_e
$_SESSION['flag_imp'] = 0;
$_SESSION['grado'] = "";
$_SESSION['flag'] = 0;
$_SESSION['busqueda'] = "";
$_SESSION['link'] = "";
$_SESSION['folio'] = 0;
$_SESSION['curp'] = "";
$_SESSION['cct'] = "";
$_SESSION['a'] = "";
$_SESSION['t'] = 0;
$_SESSION['aux'] = 0;
$_SESSION['grado'] = 0;

if ($_SESSION['tipo'] == 2 or $_SESSION['tipo'] == 3 or $_SESSION['tipo'] == 0)	//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
{
	$sql = "SELECT
				COUNT(*) AS total
			FROM
				t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
			WHERE
				fecha < '".$fecha_aux."' AND
				validate = 0 AND
				nivel_e = ".$_SESSION['nivel']." AND
				sub_tipo_e = ".$_SESSION['sub_tipo'];
	require ('consulta.php');
	$row=mysql_fetch_object($rs);
	$_SESSION['t'] = $row->total;
}
?>


<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Seleccione una opción</legend>
<form action="menu.php" method="post" name="menu" id="menu">
<?php
if ($_SESSION['t'] != 0)
{
	echo '<div align = "left"><label><p class="textito">Existen folios caducos. Favor de revisar en el apartado "CONFIGURACIONES ADMINISTRADOR"</p></label></div><br><br>';
}
?>
<div align = "center">
<label><p class="texto_2">Inscribir un alumno a:</p></label>
<a class="textote" href="cambios2.php">Segundo*</a>
<a class="textote" href="cambios3.php">Tercero*</a>
<br><br><br>
<table width="600" border="0" align="center">
<tr><td width="300">
<div align="center">
<a class="textote" href="imprimir.php">Imprimir un formato*</a>
<br><br>
<a class="textote" href="borrar.php">Borrar un formato*</a>
<br><br>
<a class="textote" href="modif.php">Modificar un formato*</a>
<br><br>
</div>
</td><td width="300">
<div align="center">
<a class="textote" href="cons_escuela.php">Consultar escuelas con espacio*</a>
<br><br>
<a class="textote" href="cons_oficio.php">Consultar oficios hechos*</a>
<br><br>
<a class="textote" href="estadistica.php">Estadística</a>
<br><br>
</div>
</td></tr>
</table>
<?php
if ($_SESSION['tipo'] == 0 or $_SESSION['tipo'] == 1)
{?>
	<br><br>
	<a class="textote" href="set_p.php">Modificar espacios en las escuelas</a>
	<br><br>
	<a class="texto" href="admin_conf.php">CONFIGURACIONES ADMINISTRADOR</a> !!!!
	<br><br>
<?php	
}
?>
</div>
</form>
</fieldset>


<br><br>
<form name="boton" method="post" action="index.php">
<input name="cancelar" value="Cerrar la página" type="submit" onclick="_close()" />
</form>
<form method="post" action="menu.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>

</div>

</html>
</>