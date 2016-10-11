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
//charge contents of head flag
require ('config.php');
require ('conex.php');
require ('functions.php');
if ($_SESSION['aut'] == 0){_frog('index.php');}
if ($_SESSION['flag'] == 100){_frog('menu.php');}
//$_SESSION['link'] = "imprimir.php";
$error_e = 0;
$msn_e = "";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function search(valor,variable,pagina,ele1)
{
	if (valor.length > 2)
	{
		showResult(valor,variable,pagina)
	}
	else
	{
		document.getElementById(variable).style.display="none";
	}
}
function search_curp(valor,variable,pagina,ele1)
{
	if (valor.length > 9)
	{
		showResult(valor,variable,pagina)
	}
	else
	{
		document.getElementById(variable).style.display="none";
	}
}
function search_cct(valor,variable,pagina,ele1)
{
	if (valor.length > 6)
	{
		showResult(valor,variable,pagina)
	}
	else
	{
		document.getElementById(variable).style.display="none";
	}
}
function confirmar()
{
	var x;
    if (confirm("¿Está seguro que desea borrar este oficio?") != true)
	{
        window.location.href = 'menu.php';
    }
}
function oculta(ele1,ele2,ele3,ele4)
{
	document.getElementById(ele1).style.display="none";
	document.getElementById(ele2).style.display="none";
	document.getElementById(ele3).style.display="none";
	document.getElementById(ele4).style.display="none";
}

</script>
</head>


<body>
<div align="center">

<?php
if ($_SESSION['flag']==100){header("Refresh:0; url=menu.php");}
if (isset($_GET['md']))
{
	$_SESSION['curp'] = $_GET['md'];
	$_SESSION['flag'] = 1;
}

//--> Buscar el alumno deseado por CURP
$sql = "SELECT
			folio,
			cct_ou,
			grado,
			nom,
			app,
			apm,
			fecha,
			temp
		FROM
			t_alumnos
		WHERE
			id_alum = '".$_SESSION['curp']."'
		LIMIT 1
		";
require ('consulta.php');
if (!$zero_results)
{
	$row=mysql_fetch_object($rs);
	$_SESSION['cct'] = $row->cct_ou;
	$_SESSION['folio'] = $row->folio;
	$grado = $row->grado;
	$app = $row->app;
	$apm = $row->apm;
	$nom = $row->nom;
	$fecha_in = $row->fecha;
	//$_SESSION['aux'] = $row->temp;
	$temp = $row->temp;
}
else
{
	$_SESSION['cct'] = "";
	$_SESSION['folio'] = "";
	$grado = "";
	$app = "";
	$apm = "";
	$nom = "";
	$fecha_in = "";
}
$sql = "SELECT
			COUNT(*) AS 'Total'
		FROM
			t_file
		WHERE
			curp_f = '".$_SESSION['curp']."'
		";
require ('consulta.php');
$row=mysql_fetch_object($rs);
if ($row->Total > 0)
{
	$msn_opciones = "**Este alumno ha realizado ".$row->Total." cambios";
}
else
{
	$msn_opciones = "";
}


if(isset($_POST['borrar']))	//borrar
{	
	if ($_SESSION['curp'] != "" and $_SESSION['cct'] != "" and $_SESSION['folio'] != "")
	{
		switch ($grado)
		{
			case 'SEGUNDO':
			{$_SESSION['grado']=2; break;}
			case 'TERCERO':
			{$_SESSION['grado']=3; break;}
		}
		/*switch ($temp)
		{
			case 0:
			{$c_grado = $e;break;}
			case 1:
			{$c_grado = $ee;break;}
		}*/

		$sql = "DELETE
				FROM
					t_alumnos
				WHERE
					id_alum = '".$_SESSION['curp']."'
				LIMIT 1
				";
		require ('consulta.php');
		if ($affected_rows!=1)
		{
			$msn = "No se pudo borrar al alumno. Contacte a su administrador.";
			$error = 1;
		}
		else
		{
			require ("borrar_e.php");
			if ($error_e == 0)
			{	///ATTENTION il faut faire attencion au cicle scolaire
				$sql = "INSERT INTO
							t_file
						VALUES
							(
							DEFAULT,
							'".$_SESSION['folio']."',
							'".$_SESSION['curp']."',
							'".$_SESSION['cct']."',
							'".$grado."',
							'".$nom."',
							'".$app."',
							'".$apm."',
							'2016-2017',
							'".$fecha_in."',
							CURDATE(),
							'".$_SESSION['id_u']."')
						";
				require ('consulta.php');
				if ($zero_results)
				{
					echo '<script>alert ("Ocurrió un error con el historial del sistema. Contacte al Administrador.");</script>';
				}
				else
				{
					$_SESSION['flag'] == 100;
					echo '<script>alert ("Se ha borrado satisfactoriamente el formato.");</script>';
					_frog('menu.php');
				}
			}
			else
			{
				echo '<script>alert ("'.$msn_e.'");</script>';
				$error = 1;
			}
		}
		
		if ($error == 1)
		{
			echo '<script>alert ("'.$msn.'");</script>';
			$error = 0;
		}
	}
	else
	{
		echo '<script>alert ("Ocurrió un error. Contacte al Administrador.");</script>';
	}
}

?>

<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Borrar Formato</legend>
<form action="borrar.php" method="post" name="borrar" id="borrar">
<div align="center">
<br>
<table class="datos" style="width:500px">
<tr>
<td style="width:170px"><label><p class="texto">CURP:</p></label></td>
<td style="width:330px"><input name="curp" type="text" id="curp" class="daton" maxlength="18" style="width:230px" value="<?php if($_SESSION['curp'] != ""){echo $_SESSION['curp'];}?>" title="Escriba el CURP" placeholder="CURP" onkeyup="this.value = this.value.toUpperCase();search_curp(this.value,'curp_a','curp_ajax.php')" onfocus="oculta('app_a','apm_a','nom_a','cct_a')" <?php if($_SESSION['flag'] != 1){echo 'disabled="disabled"';}?>/>
<div id="curp_a" style=" display:none;position:absolute;margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Apellido Paterno:</p></label></td>
<td style="width:330px"><input name="app" type="text" id="app" class="daton" maxlength="20" style="width:230px" value="<?php if($app != ""){echo $app;}?>" title="Escriba el apellido paterno" placeholder="Apellido Paterno" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'app_a','app_ajax.php')" onfocus="oculta('curp_a','apm_a','nom_a','cct_a')" <?php if($_SESSION['flag'] != 1){echo 'disabled="disabled"';}?>/>
<div id="app_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Apellido Materno:</p></label></td>
<td style="width:330px"><input name="apm" type="text" id="apm" class="daton" maxlength="20" style="width:230px" value="<?php if($apm != ""){echo $apm;}?>" title="Escriba el apellido materno" placeholder="Apellido Materno" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'apm_a','apm_ajax.php')" onfocus="oculta('app_a','curp_a','nom_a','cct_a')" <?php if($_SESSION['flag'] != 1){echo 'disabled="disabled"';}?>/>
<div id="apm_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Nombre(s):</p></label></td>
<td style="width:330px"><input name="nom" type="text" id="nom" class="daton" maxlength="20" style="width:230px" value="<?php if($nom != ""){echo $nom;}?>" title="Escriba el nombre" placeholder="Nombre(s)" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'nom_a','nom_ajax.php')" onfocus="oculta('app_a','apm_a','curp_a','cct_a')" <?php if($_SESSION['flag'] != 1){echo 'disabled="disabled"';}?>/>
<div id="nom_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Escuela:</p></label></td>
<td style="width:330px"><input name="cct" type="text" id="cct" class="daton" maxlength="10" style="width:230px" value="<?php if($_SESSION['cct'] != ""){echo $_SESSION['cct'];}?>" title="Escriba la CCT" placeholder="CCT" onkeyup="this.value = this.value.toUpperCase(); search_cct(this.value,'cct_a','cct_ajax.php')" onfocus="oculta('app_a','apm_a','nom_a','cct_a')" <?php if($_SESSION['flag'] != 1){echo 'disabled="disabled"';}?>/>
<div id="cct_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;" ></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Grado:</p></label></td>
<td style="width:330px"><input name="grado" type="text" id="grado" class="daton" style="width:230px" value="<?php if($grado != ""){echo $grado;}?>" placeholder="Grado" disabled="disabled"/>
</tr>
</table>
<br><br>
<?php
if ($_SESSION['flag'] == 1)
{
?>
	<div align="center"><input name="borrar" value="Borrar Alumno" type="submit" title="Borrar Alumno" onclick="confirma()" <?php if ($_SESSION['flag']==100) echo 'disabled="disabled"'; ?>/></div>
<?php
}
else
{if ($_SESSION['flag']!=0){header("Refresh:0; url=menu.php");}}
?>
</div>
</form>
</fieldset>



<br>
<form name="boton" method="post" action="menu.php">
<input name="cancelar" value="Regresar al Menú Principal" type="submit" title="Presione para regresar al menú principal" />
</form>
<form method="post" action="imprimir.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>

</div>
</body>
</>