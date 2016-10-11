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
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {
		var data = new google.visualization.DataTable();
		var fem0 = parseInt(document.getElementById("muj0").value);
		var fem1 = parseInt(document.getElementById("muj1").value);
		var fem2 = parseInt(document.getElementById("muj2").value);
		var homm0 = parseInt(document.getElementById("hom0").value);
		var homm1 = parseInt(document.getElementById("hom1").value);
		var homm2 = parseInt(document.getElementById("hom2").value);
		data.addColumn('string', 'Top');
		data.addColumn('number', 'Slices');
		data.addRows([
        ['Mujeres 1ero',fem0],
        ['Mujeres 2do', fem1],
        ['Mujeres 3ero', fem2],
        ['Hombres 1ero',homm0],
        ['Hombres 2do', homm1],
        ['Hombres 3ero', homm2]
		]);

		var options = {'title':'Estadística Cambios','width':400,'height':300};
		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		chart.draw(data, options);
    }
	
function _valida_datos()
{
	var fecha1 = document.getElementById("fecha1").value, fecha2 = document.getElementById("fecha2").value;
	var msn = "", error = 0;
	
	if (fecha1 == "" || fecha2 == "")
	{
		alert("- Seleccione las fechas del periodo que desee consultar. \n");
		return false;
	}
	else
	{
		return true;
	}
}
</script>



</head>
<?php
if (!isset($_POST['bus'])){	$bus=0;}
else{$bus = $_REQUEST['bus'];}
if (!isset($_POST['cct'])){$cct="";}
else{$cct = $_REQUEST['cct'];}
if (!isset($_POST['fecha1'])){$fecha1="";}
else{$fecha1 = $_REQUEST['fecha1'];}
if (!isset($_POST['fecha2'])){$fecha2="";}
else{$fecha2 = $_REQUEST['fecha2'];}

$hombres[0]=0;
$hombres[1]=0;
$hombres[2]=0;
$mujeres[0]=0;
$mujeres[1]=0;
$mujeres[2]=0;

$error = 0;
$msn="";

?>

<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Estadística</legend>
<form action="estadistica.php" method="post" name="estadistica" id="estadistica">
<div align="center">
<table class="datos" style="width:120px">
<tr><td style="width:50px"><div align="right"><label><p class="texto_imp00">Estadística por:</p></label></div></td>
<td style="width:70px"><div align="left"><select name="bus" class="daton" onchange="estadistica.submit(this.value)" style="width:70px">
  	<option value="0" <?php if($bus ==0) echo "selected"; ?>>Todos</option>
    <option value="1" <?php if($bus ==1) echo "selected"; ?>>Escuela</option>
</select></div></td></tr>
</table>
<br>
<div align="center">
<table class="datos" style="width:500px">
<tr>
<td style="width:100px"><div align="right"><label><p class="texto_imp00">entre la fecha:</p></label></div></td>
<td style="width:150px"><input name='fecha1' id='fecha1' type='date' class="datoso" title="Seleccione la fecha" /></td>
<td style="width:100px"><div align="right"><label><p class="texto_imp00">y la fecha:</p></label></div></td>
<td style="width:150px"><input name='fecha2' id='fecha2' type='date' class="datoso" title="Seleccione la fecha" /></td>
</tr>
</table>
</div>
<?php
switch ($bus)
{
	case 1:
	{
		if($_SESSION['sub_tipo'] == 0)
		{
			$sql = "SELECT
						cct AS CCT,
						nom_e AS Nom
					FROM
						directorio.t_escuelas
					WHERE
						tipo != 4
					ORDER BY CCT
					";
		}
		else
		{
			$sql = "SELECT
						cct AS CCT,
						nom_e AS Nom
					FROM
						directorio.t_escuelas
					WHERE
						do_e = ".$_SESSION['do']." AND
						tipo != 4
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
<br><input name='enter' type='submit' onclick="return _valida_datos()" value='Buscar' /> 
<?php

if(isset($_POST['enter']))
{
	$_SESSION['fec1'] = $fecha1;
	$_SESSION['fec2'] = $fecha2;
	
	$c=0;
	switch ($bus)
	{
		case 0:
		{
			if($_SESSION['tipo_u'] == 0)
			{
				$sql = "SELECT
							grado,
							COUNT(*) AS total
						FROM
							(t_alumnos LEFT JOIN cct_do4 ON t_alumnos.cct_ou = cct_do4.cct_do4) LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum
						WHERE
							id_alum LIKE '__________H%' AND
							fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
						GROUP BY
							grado
						";
			}
			else
			{
				$sql = "SELECT
							grado,
							COUNT(*) AS total
						FROM
							(t_alumnos LEFT JOIN cct_do4 ON t_alumnos.cct_ou = cct_do4.cct_do4) LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum
						WHERE
							id_alum LIKE '__________H%' AND
							do_cct = ".$_SESSION['do']." AND
							fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
						GROUP BY
							grado
						";
			}
			require('consulta.php');
			if (!$zero_results)
			{
				while($row=mysql_fetch_object($rs))
				{
					switch ($row->grado)
					{
						case "PRIMERO":
						{
							$hombres[0] = $row->total;
							break;
						}
						case "SEGUNDO":
						{
							$hombres[1] = $row->total;
							break;
						}
						case "TERCERO":
						{
							$hombres[2] = $row->total;
							break;
						}
					}
				}
			}
			if($_SESSION['tipo_u'] == 0)
			{
				$sql = "SELECT
							grado,
							COUNT(*) AS total
						FROM
							(t_alumnos LEFT JOIN cct_do4 ON t_alumnos.cct_ou = cct_do4.cct_do4) LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum
						WHERE
							id_alum LIKE '__________M%' AND
							fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
						GROUP BY
							grado
						";
			}
			else
			{
				$sql = "SELECT
								grado,
							COUNT(*) AS total
						FROM
							(t_alumnos LEFT JOIN cct_do4 ON t_alumnos.cct_ou = cct_do4.cct_do4) LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum
						WHERE
							id_alum LIKE '__________M%' AND
							do_cct = ".$_SESSION['do']." AND
							fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
						GROUP BY
							grado
						";
			}
			require('consulta.php');
			if (!$zero_results)
			{
				while($row=mysql_fetch_object($rs))
				{
					switch ($row->grado)
					{
						case "PRIMERO":
						{
							$mujeres[0] = $row->total;
							break;
						}
						case "SEGUNDO":
						{
							$mujeres[1] = $row->total;
							break;
						}
						case "TERCERO":
						{
							$mujeres[2] = $row->total;
							break;
						}
					}
				}
			}
			?>
			<br><br>
			<h1>Estadística para todos los planteles de la D.O. <?php echo $_SESSION['do']; ?></h1>
			<br>
			<table class="data" style="width:750px">
				<tr>
				<td style="width:250px">Cambios de mujeres en PRIMERO: <b><?php echo $mujeres[0]; ?></b></td>
				<td style="width:250px">Cambios de hombres en PRIMERO: <b><?php echo $hombres[0]; ?></b></td>
				<td style="width:250px">Total de cambios en PRIMERO: <b><?php echo $mujeres[0]+$hombres[0]; ?></b></td>
				</tr>
				<tr>
				<td style="width:250px">Cambios de mujeres en SEGUNDO: <b><?php echo $mujeres[1]; ?></b></td>
				<td style="width:250px">Cambios de hombres en SEGUNDO: <b><?php echo $hombres[1]; ?></b></td>
				<td style="width:250px">Total de cambios en SEGUNDO: <b><?php echo $mujeres[1]+$hombres[1]; ?></b></td>
				</tr>
				<tr>
				<td style="width:250px">Cambios de mujeres en TERCERO: <b><?php echo $mujeres[2]; ?></b></td>
				<td style="width:250px">Cambios de hombres en TERCERO: <b><?php echo $hombres[2]; ?></b></td>
				<td style="width:250px">Total de cambios en TERCERO: <b><?php echo $mujeres[2]+$hombres[2]; ?></b></td>
				</tr>
			</table>
			<br>
			<table class="data" style="width:750px">
				<tr>
				<td style="width:250px"><u>Total de cambios de MUJERES: </u><b><?php echo $muj=$mujeres[0]+$mujeres[1]+$mujeres[2]; ?></b></td>
				<td style="width:250px"><u>Total de cambios de HOMBRES: </u><b><?php echo $hom=$hombres[0]+$hombres[1]+$hombres[2]; ?></b></td>
				<td style="width:250px"><u>TOTAL de cambios: </u><b><?php echo $tot=$muj+$hom; ?></b></td>
				</tr>
			</table>
			<input type="hidden" id="muj0" value="<?php echo $mujeres[0]; ?>">
			<input type="hidden" id="muj1" value="<?php echo $mujeres[1]; ?>">
			<input type="hidden" id="muj2" value="<?php echo $mujeres[2]; ?>">
			<input type="hidden" id="hom0" value="<?php echo $hombres[0]; ?>">
			<input type="hidden" id="hom1" value="<?php echo $hombres[1]; ?>">
			<input type="hidden" id="hom2" value="<?php echo $hombres[2]; ?>">
			<br>
			<?php
			if ($tot !=0)
			{
			?>
				<br>
				<div id="chart_div" style="width:400; height:300"></div>
				<br>
				<div align="right"><a class="texto" href="estadistica2.php">Ver todos los cambios por escuela</a></div>
			<?php
			}
			break;
		}
		case 1:
		{
			$sql = "SELECT
						grado,
						COUNT(*) AS total
					FROM
						t_alumnos LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum 
					WHERE
						id_alum LIKE '__________H%' AND
						cct_ou = '".$cct."' AND
						fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
					GROUP BY
						grado
					";
			require('consulta.php');
			if (!$zero_results)
			{
				while($row=mysql_fetch_object($rs))
				{
					switch ($row->grado)
					{
						case "PRIMERO":
						{
							$hombres[0] = $row->total;
							break;
						}
						case "SEGUNDO":
						{
							$hombres[1] = $row->total;
							break;
						}
						case "TERCERO":
						{
							$hombres[2] = $row->total;
							break;
						}
					}
				}
			}
			$sql = "SELECT
						grado,
						COUNT(*) AS total
					FROM
						t_alumnos LEFT JOIN t_doc ON t_alumnos.id_alum = t_doc.id_alum 
					WHERE
						id_alum LIKE '__________M%' AND
						cct_ou = '".$cct."' AND
						fecha BETWEEN '".$fecha1."' AND '".$fecha2."'
					GROUP BY
						grado
					";
			require('consulta.php');
			if (!$zero_results)
			{
				while($row=mysql_fetch_object($rs))
				{
					switch ($row->grado)
					{
						case "PRIMERO":
						{
							$mujeres[0] = $row->total;
							break;
						}
						case "SEGUNDO":
						{
							$mujeres[1] = $row->total;
							break;
						}
						case "TERCERO":
						{
							$mujeres[2] = $row->total;
							break;
						}
					}
				}
			}
			?>
			<br><br>
			<h1>Estadística para la escuela <?php echo $cct; ?></h1>
			<br>
			<table class="data" style="width:750px">
				<tr>
				<td style="width:250px">Cambios de mujeres en PRIMERO: <b><?php echo $mujeres[0]; ?></b></td>
				<td style="width:250px">Cambios de hombres en PRIMERO: <b><?php echo $hombres[0]; ?></b></td>
				<td style="width:250px">Total de cambios en PRIMERO: <b><?php echo $mujeres[0]+$hombres[0]; ?></b></td>
				</tr>
				<tr>
				<td style="width:250px">Cambios de mujeres en SEGUNDO: <b><?php echo $mujeres[1]; ?></b></td>
				<td style="width:250px">Cambios de hombres en SEGUNDO: <b><?php echo $hombres[1]; ?></b></td>
				<td style="width:250px">Total de cambios en SEGUNDO: <b><?php echo $mujeres[1]+$hombres[1]; ?></b></td>
				</tr>
				<tr>
				<td style="width:250px">Cambios de mujeres en TERCERO: <b><?php echo $mujeres[2]; ?></b></td>
				<td style="width:250px">Cambios de hombres en TERCERO: <b><?php echo $hombres[2]; ?></b></td>
				<td style="width:250px">Total de cambios en TERCERO: <b><?php echo $mujeres[2]+$hombres[2]; ?></b></td>
				</tr>
			</table>
			<br>
			<table class="data" style="width:750px">
				<tr>
				<td style="width:250px"><u>Total de cambios de MUJERES: </u><b><?php echo $muj=$mujeres[0]+$mujeres[1]+$mujeres[2]; ?></b></td>
				<td style="width:250px"><u>Total de cambios de HOMBRES: </u><b><?php echo $hom=$hombres[0]+$hombres[1]+$hombres[2]; ?></b></td>
				<td style="width:250px"><u>TOTAL de cambios: </u><b><?php echo $tot=$muj+$hom; ?></b></td>
				</tr>
			</table>
			<input type="hidden" id="muj0" value="<?php echo $mujeres[0]; ?>">
			<input type="hidden" id="muj1" value="<?php echo $mujeres[1]; ?>">
			<input type="hidden" id="muj2" value="<?php echo $mujeres[2]; ?>">
			<input type="hidden" id="hom0" value="<?php echo $hombres[0]; ?>">
			<input type="hidden" id="hom1" value="<?php echo $hombres[1]; ?>">
			<input type="hidden" id="hom2" value="<?php echo $hombres[2]; ?>">
			<br>
			<?php
			if ($tot !=0)
			{
			?>
			<br>
			<div id="chart_div" style="width:400; height:300"></div>
			<br>
			<?php
			}
			break;
		}
	}//switch
	?>
	
	<?php
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