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
$_SESSION['link'] = "imprimir.php";
$error = 0;
$msn = "";
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
//-->

switch($_SESSION['nivel']) //1=DGOSE,2=DGSEI,3=DGEST,4=DGNAM
{
	case 0:{$_SESSION['a']="imp_0.php";break;}
	case 1:
	{
		switch ($_SESSION['sub_tipo'])
		{
			case 0: {$_SESSION['a']="imp_0.php";break;}
			case 1: {$_SESSION['a']="imp_1.php";break;}
			case 2: {$_SESSION['a']="imp_2.php";break;}
			case 3: {$_SESSION['a']="imp_3.php";break;}
			case 4: {$_SESSION['a']="imp_4.php";break;}
			case 6: {$_SESSION['a']="imp_6.php";break;}
		}
		break;
	}
	
}


if (isset($_GET['md']))
{
	$_SESSION['curp'] = $_GET['md'];
	$_SESSION['flag'] = 1;
}

//Buscar el alumno deseado por CURP
$sql = "SELECT
			folio,
			cct_ou,
			grado,
			nom,
			app,
			apm
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
	$grado = $row->grado;
	$_SESSION['folio'] = $row->folio;
	$app = $row->app;
	$apm = $row->apm;
	$nom = $row->nom;
}
else
{
	$_SESSION['cct'] = "";
	$grado = "";
	$_SESSION['folio'] = 0;
	$app = "";
	$apm = "";
	$nom = "";
}
//-->
?>

<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Imprimir Formato</legend>
<form action="imprimir.php" method="post" name="imprimir" id="imprimir">
<div align="center">
<br>
<table class="datos" style="width:500px">
<tr>
<td style="width:170px"><label><p class="texto">CURP:</p></label></td>
<td style="width:330px"><input name="curp" type="text" id="curp" class="daton" maxlength="18" style="width:230px" value="<?php if($_SESSION['curp'] != ""){echo $_SESSION['curp'];}?>" title="Escriba el CURP" placeholder="CURP" onkeyup="this.value = this.value.toUpperCase();search_curp(this.value,'curp_a','curp_ajax.php')" onfocus="oculta('app_a','apm_a','nom_a','cct_a')"/>
<div id="curp_a" style=" display:none;position:absolute;margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Apellido Paterno:</p></label></td>
<td style="width:330px"><input name="app" type="text" id="app" class="daton" maxlength="20" style="width:230px" value="<?php if($app != ""){echo $app;}?>" title="Escriba el apellido paterno" placeholder="Apellido Paterno" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'app_a','app_ajax.php')" onfocus="oculta('curp_a','apm_a','nom_a','cct_a')"/>
<div id="app_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Apellido Materno:</p></label></td>
<td style="width:330px"><input name="apm" type="text" id="apm" class="daton" maxlength="20" style="width:230px" value="<?php if($apm != ""){echo $apm;}?>" title="Escriba el apellido materno" placeholder="Apellido Materno" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'apm_a','apm_ajax.php')" onfocus="oculta('app_a','curp_a','nom_a','cct_a')"/>
<div id="apm_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Nombre(s):</p></label></td>
<td style="width:330px"><input name="nom" type="text" id="nom" class="daton" maxlength="20" style="width:230px" value="<?php if($nom != ""){echo $nom;}?>" title="Escriba el nombre" placeholder="Nombre(s)" onkeyup="this.value = this.value.toUpperCase(); search(this.value,'nom_a','nom_ajax.php')" onfocus="oculta('app_a','apm_a','curp_a','cct_a')"/>
<div id="nom_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Escuela:</p></label></td>
<td style="width:330px"><input name="cct" type="text" id="cct" class="daton" maxlength="10" style="width:230px" value="<?php if($_SESSION['cct'] != ""){echo $_SESSION['cct'];}?>" title="Escriba la CCT" placeholder="CCT" onkeyup="this.value = this.value.toUpperCase(); search_cct(this.value,'cct_a','cct_ajax.php')" onfocus="oculta('app_a','apm_a','nom_a','cct_a')"/>
<div id="cct_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;" ></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">Grado:</p></label></td>
<td style="width:330px"><input name="grado" type="text" id="grado" class="daton" style="width:230px" value="<?php if($grado != ""){echo $grado;}?>" placeholder="Grado" disabled="disabled"/>
</tr>
</table>
<br>
</div>
</form>
<?php
if ($_SESSION['flag'] == 1)
{
?>
	<form method="post" action="<?php echo $_SESSION['a']; ?>">
		<div align="center"><input name='imp' type='submit' value='Imprimir' /></div>
	</form>
<?php
}
?>
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