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
</head>

<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['id_u']; ?> - Estadística Total</legend>
<form action="cons_escuela.php" method="post" name="cons_escuela" id="cons_escuela">
<?php
if($_SESSION['tipo_u'] == 0)
{
	$sql = "SELECT
				cct,
				nom_e,
				insp_e,
				grado,
				COUNT(*) AS total
			FROM
				(cambios.t_alumnos LEFT JOIN directorio.t_escuelas ON t_alumnos.cct_ou = t_escuelas.cct) LEFT JOIN cambios.t_doc ON cambios.t_alumnos.id_alum = cambios.t_doc.id_alum
			WHERE
				fecha BETWEEN '".$_SESSION['fec1']."' AND '".$_SESSION['fec2']."'
			GROUP BY
				cct,
				grado
			";
}
else
{
	$sql = "SELECT
				cct,
				nom_e,
				insp_e,
				grado,
				COUNT(*) AS total
			FROM
				(cambios.t_alumnos LEFT JOIN directorio.t_escuelas ON t_alumnos.cct_ou = t_escuelas.cct) LEFT JOIN cambios.t_doc ON cambios.t_alumnos.id_alum = cambios.t_doc.id_alum
			WHERE
				do_e = ".$_SESSION['do']." AND
				fecha BETWEEN '".$_SESSION['fec1']."' AND '".$_SESSION['fec2']."'
			GROUP BY
				cct,
				grado
			";
}
require("consulta.php");
if (!$zero_results)
{
	?>
	<table class="datos">
	<tr>
		<th>C.C.T.</th><th>Nombre de la Escuela</th><th>Zona Escolar</th><th>Grado</th><th>Número de cambios</th>
	</tr>
	<?php
	while($row=mysql_fetch_array($rs))
	{
		?>
		<tr>
			<td><?php echo '<a class="textito" href="directorio.php?md='.$row['cct'].'">'.$row['cct'].'</a>' ?></td>
			<td><?php echo $row['nom_e']; ?></td>
			<td><?php echo $row['insp_e']; ?></td>
			<td><?php echo $row['grado']; ?></td>
			<td><?php echo $row['total']; ?></td>
		</tr>
		<?php
	}
	?>
	</table>
	<br><br>
	<?php
}
else
{
	echo '<div align="center"><label><p class="error">No hubo resultados.</p></label></div>';
	echo '<script>alert ("No hubo resultados.");</script>';
}

?>

</form>
</fieldset>
<br><br>
<form name="boton" method="post" action="estadistica.php">
<input name="cancelar" value="Regresar al Estadística" type="submit" title="Presione para regresar a estadística" />
</form>
<br><br>
<form method="post" action="menu.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>
</div>
</>