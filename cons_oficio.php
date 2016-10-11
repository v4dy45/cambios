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
<script type="text/javascript">
<!--
function _valida_datos()
{
	var curp = document.getElementById("curp").value;
	var msn = "", error = 0;
	
	if (curp == "")
	{
		msn = msn + "- El CURP es OBLIGATORIO. \n";
		document.getElementById("p_curp").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!curp.match(/^([A-Z]{4})([0-9]{6})([A-Z]{6})([A-Z0-9]{2})$/i))
		{
			msn = msn + "- El CURP es incorrecto. \n";
			document.getElementById("p_curp").style.color="#F21111";
			error = 1;
		}
	}
	if (error == 1)
	{
		alert(msn);
		return false;
	}
	else
	{
		document.getElementById(imp).style.display="";
		return true;
	}
}

function _quita(p_ele)
{
	document.getElementById(p_ele).style.color="#000000";
}
//-->
</script>

</head>
<?php
if (!isset($_POST['bus'])){	$bus=0;}
else{$bus = $_REQUEST['bus'];}
if (!isset($_POST['curp'])){$curp="";}
else{$curp= $_REQUEST['curp'];}
if (!isset($_POST['cct'])){$cct="";}
else{$cct = $_REQUEST['cct'];}
if (!isset($_POST['fecha'])){$fecha="";}
else{$fecha = $_REQUEST['fecha'];}


$error = 0;
$msn="";

?>

<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Buscar un formato</legend>
<form action="cons_oficio.php" method="post" name="cons_oficio" id="cons_oficio">
<div align="center">
<table class="datos">
<tr><td><label><p class="texto_imp00">Buscar formatos de inscripción por:</p></label></td>
<td><select name="bus" class="daton" onchange="cons_oficio.submit(this.value)">
  	<option value="0" <?php if($bus ==0) echo "selected"; ?>>Todos</option>
    <option value="1" <?php if($bus ==1) echo "selected"; ?>>CURP</option>
    <option value="2" <?php if($bus ==2) echo "selected"; ?>>Fecha</option>
	<option value="3" <?php if($bus ==3) echo "selected"; ?>>Escuela</option>
</select></td>
</table>
<br>
<?php
switch ($bus)
{
	case 1:
	{?>
		<table class="datos" style="width:520px">
		<tr>
		<td style="width:270px"><label><p class="texto_imp00" id="p_curp">Ingrese el CURP:</p></label></td>
		<td><input name="curp" type="text" id="curp" class="daton" title="Escriba el CURP" value="<?php if ($curp != "") echo $curp; ?>" style="width:250px" maxlength="18" onkeyup="this.value = this.value.toUpperCase()" onclick="_quita('p_curp')"/></td>
		</tr>
		</table>
	<?php
		break;
	}
	case 2:
	{?>
		<table class="datos" style="width:520px">
		<tr>
		<td style="width:270px"><label><p class="texto_imp00">Ingrese la fecha con formato aaaa-mm-dd:</p></label></td>
		<td><input name='fecha' type='date' class="datoso" title="Seleccione la fecha" /></td>
		</tr>
		</table>
	<?php
		break;
	}
	case 3:
	{
		if($_SESSION['sub_tipo'] == 0)
		{
			$sql = "SELECT
						id_cct AS CCT,
						nom_e AS Nom
					FROM
						t_escuelas
					WHERE
						kind_e != 4
					ORDER BY CCT
					";
		}
		else
		{
			$sql = "SELECT
						id_cct AS CCT,
						nom_e AS Nom
					FROM
						t_escuelas
					WHERE
						usb_tipo_e = ".$_SESSION['sub_tipo']." AND
						kind_e != 4
					ORDER BY CCT
					";
		}
		require ('consulta.php');
		?>
		<table class="datos" style="width:520px">
		<tr>
		<td style="width:270px"><label><p class="texto_imp00">Seleccione una CCT:</p></label></td>
		<td><select name="cct" class="daton" style="width:250px" title="Seleccione un centro de trabajo"> <?php
		while($row=mysql_fetch_object($rs))
		{
			if ($cct == $row->CCT)
			{
				echo "<option value='$row->CCT' selected>$row->CCT - $row->Nom</option>";
			}
			else
			{
				echo "<option value='$row->CCT'>$row->CCT - $row->Nom</option>";
			}
		} ?>
		</select></td></tr></table> <?php
		break;
	}
}?>
<br><br><input name='enter' type='submit' onclick="return _valida_datos()" value='Buscar' /> 
<?php

if(isset($_POST['enter']))
{
	if ($error != 1)
	{
		switch($bus)
		{
			case 0:
			{
				if($_SESSION['sub_tipo'] == 0)
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct";
				}
				else
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								sub_tipo_e = ".$_SESSION['sub_tipo'];
				}
				break;
			}
			case 1:
			{
				if($_SESSION['tipo_u'] == 0)
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								id_alum = '".$curp."'";
				}
				else
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								id_alum = '".$curp."' AND
								sub_tipo_e = ".$_SESSION['sub_tipo'];
				}
				break;
			}
			case 2:
			{
				if($_SESSION['tipo_u'] == 0)
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
						WHERE
							fecha = '".$fecha."'";
				}
				else
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								fecha = '".$fecha."' AND
								sub_tipo_e = ".$_SESSION['sub_tipo'];
				}
				break;
			}
			case 3:
			{
				if($_SESSION['tipo_u'] == 0)
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								cct_ou = '".$cct."'";
				}
				else
				{
					$sql = "SELECT
								id_alum AS 'CURP',
								nom AS 'Nombre(s)',
								app AS 'Apellido Paterno',
								apm AS 'Apellido Materno',
								cct_ou AS 'CCT',
								grado AS 'Grado',
								fecha AS 'Fecha',
								nom_e AS 'Nombre de la Escuela',
								turno AS 'Turno',
								folio AS 'Folio',
								CASE validate
									WHEN 1 THEN 'SI'
									WHEN 0 THEN 'NO'
								END AS 'Confirmado'
							FROM
								t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
							WHERE
								cct_ou = '".$cct."' AND
								sub_tipo_e = ".$_SESSION['sub_tipo'];
				}
				break;
			}
		}
		require('consulta.php');
		if (!$zero_results)
		{
			?>
			<br>
			<table class = "datos">
			<tr><th>CURP</th><th>Nombre(s)</th><th>Apellido Paterno</th><th>Apellido Materno</th><th>CCT</th><th>Grado</th><th>Fecha</th><th>Turno</th><th>Confirmado</th></tr>
			<?php
			$arreglo = _res2arr2($rs);
			foreach($arreglo as $contador_2 => $fila)
			{
				switch ($fila[8])
				{
					case 1: {$turno="M"; break;}case 2: {$turno="V"; break;}case 3: {$turno="J.A."; break;}case 4: {$turno="T.C."; break;}case 5: {$turno="N"; break;}case 6: {$turno="T.C.C.I."; break;}
				}
				echo("<tr>");
				echo '<td>'.$fila[0].'</td><td>'.$fila[1].'</td><td>'.$fila[2].'</td><td>'.$fila[3].'</td><td>'.$fila[4].'</td><td>'.$fila[5].'</td><td>'.$fila[6].'</td><td>'.$turno.'</td><td>'.$fila[10].'</td>';
				echo("</tr>");
			}
			echo '</table>';
			echo '<br><br>';
		}
		else
		{
			echo '<div align="center"><label><p class="error">No hubo resultados.</p></label></div>';
		}
	} //if todo bien
	else
	{
		echo '<script>alert ("'.$msn.'");</script>';
		$error = 0;
	}
}//if isset enter

?>
</div>
</form>
</fieldset>
<br>
<form name="boton" method="post" action="menu.php">
<input name="cancelar" value="Regresar al Menú Principal" type="submit" title="Presione para regresar al menú principal" />
</form>
<form method="post" action="cambios01.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>
</div>
</>