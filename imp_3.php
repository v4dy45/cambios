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
//require ('autenti.php');
if ($_SESSION['aut'] == 0){	_frog('index.php');}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
</head>

<div align="center">
<br>
<?php
$_SESSION['flag'] = 100;
//echo '<script>alert ("'.$_SESSION['curp'].'");</script>';
echo '<script type="text/javascript">window.onload = window.print;</script>';

$sql = "SELECT
			folio AS Folio,
			id_alum AS Curp,
			fecha AS Fecha,
			nom AS Nom,
			app AS App,
			apm AS Apm,
			cct_org AS cct2,
			cct_ou AS cct1,
			grado AS Grado,
			nom_e AS Nom_cct,
			CASE turno
				WHEN 1 THEN 'MATUTINO'
				WHEN 2 THEN 'VESPERTINO'
				WHEN 3 THEN 'JORNADA AMPLIADA'
				WHEN 4 THEN 'TIEMPO COMPLETO SIN INGESTA'
				WHEN 5 THEN 'NOCTURNO'
				WHEN 6 THEN 'TIEMPO COMPLETO CON INGESTA'
			END AS turno,
			nom_e AS Nom_cct
		FROM
			t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
		WHERE
			id_alum = '".$_SESSION['curp']."'";
require ('consulta.php');
if (!$zero_results)
{
	$row=mysql_fetch_object($rs);
	$fecha = $row->Fecha;
	$nom = $row->App.' '.$row->Apm.' '.$row->Nom;
	//$curp = $row->Curp;
	$cct = $row->cct1;
	$nom_cct = $row->Nom_cct;
	$grado = $row->Grado;
	$fecha_aux = date('Y-m-d', strtotime($fecha. ' + 20 days'));
	$turno = $row->turno;
	$folio = $row->Folio;
	switch (strlen($folio))
	{
		case 1:{$folio = "DO30000".$folio; break;}
		case 2:{$folio = "DO3000".$folio; break;}
		case 3:{$folio = "DO300".$folio; break;}
		case 4:{$folio = "DO30".$folio; break;}
		case 5:{$folio = "DO3".$folio; break;}
	}
}
else
{
	echo '<script>alert ("El documento que quiere imprimir no existe. Revise los cambios ya realizados o contacte al Administrador.");</script>';
	_frog('menu.php');
}
?>
<form id="header" name="header" method="POST">
<div class="header_imp" align="center"><img src="header_impcses.jpg" alt="Header" width="956" height="97"></div>
</form>
<form name="body" bgcolor="#FFFFFF">
<table border="0" style="width:770px">

<tr><table border="0" style="width:760px"><tr><td><label><p class="texto_imp00">Todos los trámites son GRATUITOS.</p></label></td></tr>
<br><br>
<?php
$pos = strpos($_SESSION['curp'], "000000");
if ($pos !== false)
{
	echo '<tr><td><label><p class="texto_imp00">Esta CURP PROVISIONAL <u>NO TIENE VALIDEZ OFICIAL</u> ni podrá usarse para ningún otro trámite.</p></label></td></tr>';
}
?>
</table></tr>
<tr><table border="0" style="width:760px"><tr><td><label><p class="texto_imp">Solicitud de Inscripción</p></label></td></tr></table></tr>
<tr><table style="width:760px" class="imp">
<tr>
<td class="imp" style="width:420px"><label><p class="texto_imp00"></p></label></td>
<td class="imp" style="width:80px"><label><p class="texto_imp00">Folio:</p></label></td>
<td class="imp" style="width:80px"><label><p class="texto_imp00"><?php echo $folio;  ?></p></label></td>
<td class="imp" style="width:80px"><label><p class="texto_imp00">Fecha:</p></label></td>
<td class="imp" style="width:80px"><label><p class="texto_imp00"><?php echo $fecha;  ?></p></label></td>
</tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">I.- NOMBRE DEL ALUMNO</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $nom;  ?></p></label></td></tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">II.- CURP DEL ALUMNO</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $_SESSION['curp'];  ?></p></label></td></tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">III.- CLAVE DE LA ESCUELA</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $cct;  ?></p></label></td></tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">VI.- NOMBRE DE LA ESCUELA</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $nom_cct;  ?></p></label></td></tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">V.- TURNO DE LA ESCUELA</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $turno;  ?></p></label></td></tr>
</table></tr>
<br>
<tr><table style="width:760px" class="imp">
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00">VI.- GRADO</p></label></td></tr>
<tr><td class="imp" style="width:760px"><label><p class="texto_imp00"><?php echo $grado;  ?></p></label></td></tr>
</table></tr>
<tr><table border="0" style="width:760px"><br><br>
<tr><td><label><p class="texto_imp">IMPORTANTE</p></label></td></tr>
<tr><td><label><p class="texto_imp">Una vez que tenga TODOS los documentos requeridos, deberá acudir a la escuela que seleccionó para inscribirse de manera INMEDIATA ya que este documento tiene una vigencia hasta el día <?php echo $fecha_aux; ?>, una vez pasada esta fecha deberá acudir a la D.O. correspondiente para solicitar un nuevo cambio.</p></label></td></tr>
</table></tr>
<br><br><br>
<tr><table border="0" style="width:760px"><tr><td><label><p class="textito">FIRMA DO3</p></label></td></tr></table></tr>
</table>
<br><br><br>
<div align="center">
<p class="texto_foot">
Escuela Secundaria General 21 "Jovita A. Elguero", Av. Canario Col. Bellavista Tacubaya, Deleg. Álvaro Obregón<br>
C.P. 01140, Tel. 55397386, Correo Electrónico cses.do3@sepdf.gob.mx. Visite las páginas www.sep.gob.mx y www.sepdf.gob.mx
</p>
</div>

</form>
</div>
</>
