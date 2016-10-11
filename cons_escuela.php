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
<legend><?php echo $_SESSION['user']; ?> - Escuelas con lugares</legend>
<form action="cons_escuela.php" method="post" name="cons_escuela" id="cons_escuela">
<?php
if ($_SESSION['sub_tipo'] == 0)
{
	$sql = "SELECT
				id_cct AS 'Centro de Trabajo',
				nom_e AS 'Nombre de la Escuela',
				espacios_2 AS 'Espacios en Segundo',
				espacios_3 AS 'Espacios en Tercero',
				insp_e AS 'Zona Escolar',
				col_e AS 'Colonia',
				sub_tipo_e AS 'Direccion Operativa',
				deleg AS 'Delegacion'
			FROM
				t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e
			WHERE
				espacios_2 > 0 OR
				espacios_3 > 0
			ORDER BY
				id_cct
			";
}
else
{
	$sql = "SELECT
				id_cct AS 'Centro de Trabajo',
				nom_e AS 'Nombre de la Escuela',
				espacios_2 AS 'Espacios en Segundo',
				espacios_3 AS 'Espacios en Tercero',
				insp_e AS 'Zona Escolar',
				col_e AS 'Colonia',
				sub_tipo_e AS 'Direccion Operativa',
				deleg AS 'Delegacion'
			FROM
				t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e
			WHERE
				sub_tipo_e = ".$_SESSION['sub_tipo']." AND
				(espacios_2 > 0 OR
				espacios_3 > 0)
			ORDER BY
				id_cct
			";
}
require("consulta.php");
if (!$zero_results)
{
	?>
	<table class="datos">
	<tr>
		<th>C.C.T.</th><th>Nombre de la Escuela</th><th>Espacios en 2do</th><th>Espacios en 3ero</th><th>Delegación</th><th>Colonia</th><th>Zona Escolar</th>
	</tr>
	<?php
	while($row=mysql_fetch_array($rs))
	{
		?>
		<tr>
			<td><?php echo '<a class="textito" href="directorio.php?md='.$row['Centro de Trabajo'].'">'.$row['Centro de Trabajo'].'</a>' ?></td>
			<td><?php echo $row['Nombre de la Escuela']; ?></td>
			<td><?php echo $row['Espacios en Segundo']; ?></td>
			<td><?php echo $row['Espacios en Tercero']; ?></td>
			<td><?php echo $row['Delegacion']; ?></td>
			<td><?php echo $row['Colonia']; ?></td>
			<td><?php echo $row['Zona Escolar']; ?></td>
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
<form name="boton" method="post" action="menu.php">
<input name="cancelar" value="Regresar al Menú Principal" type="submit" title="Presione para regresar al menú principal" />
</form>
<br><br>
<form method="post" action="menu.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>
</div>
</>