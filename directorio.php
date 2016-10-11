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

<body>
<?php
if ($_SESSION['aut'] == 0){	_frog('index.php');}
if (isset($_GET['md']))
{
	$cct = $_GET['md'];
}
?>
<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend>Directorio Escolar D.O.4</legend>
<form action="directorio.php" method="post" name="directorio" id="directorio">
<div align="center">
<?php

$sql = "SELECT
			id_cct,
			nom_e,
			dir_e,
			col_e,
			cp_e,
			tel_e,
			correo_e,
			director_e,
			sub_e,
			subo_e,
			url,
			kind_e,
			zona_i,
			dir_i,
			sede_i,
			correo_i,
			tel_i,
			inspector,
			deleg,
			CASE turno
				WHEN 1 THEN 'MATUTINO'
				WHEN 2 THEN 'VESPERTINO'
				WHEN 3 THEN 'JORNADA AMPLIADA'
				WHEN 4 THEN 'T.C.S.I.'
				WHEN 5 THEN 'NOCTURNO'
				WHEN 6 THEN 'T.C.C.I.'
			END AS 'turno'
		FROM
			t_escuelas LEFT JOIN t_zonas on insp_e = zona_i
		WHERE
			id_cct = '".$cct."'
		ORDER BY
			id_cct
		LIMIT 1
		";

require ('consulta.php');
if (!$zero_results)
{
	while($row=mysql_fetch_object($rs))
	{
		switch ($row->tipo)
		{
			case 1:
			{
				$tipo = "ESCUELA SECUNDARIA DIURNA ";
				break;
			}
			case 2:
			{
				$tipo = "TELESECUNDARIA ";
				break;
			}
			case 3:
			{
				$tipo = "ESCUELA PARA TRABAJADORES ";
				break;
			}
			case 4:
			{
				$tipo = "ESCUELA SECUNDARIA PARTICULAR ";
				break;
			}
		}
		?>
		<table class="datos">
			<tr>
			<td width="650">
			<table class="data">
				<tr><td class="imp"><label><p class="texto_imp">Turno:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $kind_e."<br>".$row->turno; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Clave:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $row->id_cct; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Nombre del plantel:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $row->nom_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Dirección:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $row->dir_e.", ".$row->col_e.", ".$row->deleg.", C.P: ".$row->cp_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Teléfono:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $row->tel_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">E-mail:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo $row->correo_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Director(a):</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo "Prof(a). ".$row->director_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Sub.(a) de Op. Esc:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo "Prof(a). ".$row->subo_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Sub.(a) de Des. Esc:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo "Prof(a). ".$row->sub_e; ?></p></label></td></tr>
				<tr><td class="imp"><label><p class="texto_imp">Inspección:</p></label></td>
				<td class="imp"><label><p class="texto_imp00"><?php echo "Zona Escolar No. ".$row->zona_i."<br>Sede: ".$row->sede_i."<br>Teléfono: ".$row->tel_i."<br>E-mail: ".$row->correo_i."<br> Prof(a):".$row->inspector; ?></p></label></td></tr>
			</table>
			</td>
			<td width="650">
			<iframe src="<?php echo $row->url; ?>" width="500" height="375" frameborder="0" style="border:0"></iframe>
			</td>
			</tr>
		</table>
		<br><br>
		<?php
	}
}
else
{
	echo '<div align="center"><label><p class="error">No hubo resultados. Contacte al Administrador.</p></label></div>';
	echo '<script>alert ("No hubo resultados. Contacte al Administrador.");</script>';
}
?>
</div>
</form>
</fieldset>

<br>
<form name="boton" method="post" action="cons_escuela.php">
<input name="cancelar" value="Regresar" type="submit" title="Presione para regresar al Menú Principal" />
</form>
<form method="post" action="directorio.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>

</div>
</html>
</body>
</>