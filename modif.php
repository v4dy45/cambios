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
$error = 0;
$msn = "";
$_SESSION['link'] = "modif_aux.php";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function oculta(ele1,ele2,ele3,ele4)
{
	document.getElementById(ele1).style.display="none";
	document.getElementById(ele2).style.display="none";
	document.getElementById(ele3).style.display="none";
	document.getElementById(ele4).style.display="none";
}
function _confirma() 
{
    if (confirm("¿Está seguro que desea modificar estos datos?") == true)
	{
       return true;
    }
	else
	{
        return false;
    }
}

function _valida_datos()
{
	var app = document.getElementById("app").value, apm = document.getElementById("apm").value, nom = document.getElementById("nom").value;
	var msn = "", error = 0;

	if (app == "")
	{
		msn = msn + "- El apellido paterno es OBLIGATORIO. \n";
		document.getElementById("p_app").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!app.match(/^([A-Z ÑÁÉÍÓÚÄËÏÖÜ.-]{2,30})$/i))
		{
			msn = msn + "- El Apellido Paterno contiene caractéres inválidos. \n";
			document.getElementById("p_app").style.color="#F21111";
			error = 1;
		}
	}
	if (apm == "")
	{
		msn = msn + "- El apellido materno es OBLIGATORIO. \n";
		document.getElementById("p_apm").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!apm.match(/^([A-Z ÑÁÉÍÓÚÄËÏÖÜ.-]{2,30})$/i))
		{
			msn = msn + "- El Apellido Materno contiene caractéres inválidos. \n";
			document.getElementById("p_apm").style.color="#F21111";
			error = 1;
		}
	}
	if (nom == "")
	{
		msn = msn + "- El nombre es OBLIGATORIO. \n";
		document.getElementById("p_nom").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!nom.match(/^([A-Z ÑÁÉÍÓÚÄËÏÖÜ.-]{2,30})$/i))
		{
			msn = msn + "- El Nombre contiene caractéres inválidos. \n";
			document.getElementById("p_nom").style.color="#F21111";
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

</script>
</head>


<body>
<div align="center">

<?php
if (isset($_GET['md']))
{
	$_SESSION['curp'] = $_GET['md'];
	$_SESSION['flag'] = 1;
}

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
	$_SESSION['folio'] = $row->id_doc;
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

//-->
?>
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Modificar Formato</legend>
<form action="modif.php" method="post" name="modif" id="modif">
<div align="center">
<br>
<table class="datos" style="width:500px">
<tr>
<td style="width:170px"><label><p class="texto">CURP:</p></label></td>
<td style="width:330px"><input name="curp" type="text" id="curp" class="daton" maxlength="18" style="width:230px" value="<?php if($_SESSION['curp'] != ""){echo $_SESSION['curp'];}?>" title="Escriba el CURP" placeholder="CURP" onkeyup="this.value = this.value.toUpperCase();if (this.value.length > 9) {showResult(this.value,'curp_a','curp_ajax.php')}" onfocus="oculta('app_a','apm_a','nom_a','cct_a')" <?php if($_SESSION['flag']==1){echo 'disabled="disabled"';} ?>/>
<div id="curp_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_app">Apellido Paterno:</p></label></td>
<td style="width:330px"><input name="app" type="text" id="app" class="daton" maxlength="20" style="width:230px" value="<?php if($app != ""){echo $app;}?>" title="Escriba el apellido paterno" placeholder="Apellido Paterno" onkeyup="this.value = this.value.toUpperCase();if (this.value.length > 2) {showResult(this.value,'app_a','app_ajax.php')}" onfocus="oculta('curp_a','apm_a','nom_a','cct_a')" onclick="_quita('p_app')"/>
<div id="app_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_apm">Apellido Materno:</p></label></td>
<td style="width:330px"><input name="apm" type="text" id="apm" class="daton" maxlength="20" style="width:230px" value="<?php if($apm != ""){echo $apm;}?>" title="Escriba el apellido materno" placeholder="Apellido Materno" onkeyup="this.value = this.value.toUpperCase();if (this.value.length > 2) {showResult(this.value,'apm_a','apm_ajax.php')}" onfocus="oculta('app_a','curp_a','nom_a','cct_a')" onclick="_quita('p_apm')"/>
<div id="apm_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_nom">Nombre(s):</p></label></td>
<td style="width:330px"><input name="nom" type="text" id="nom" class="daton" maxlength="20" style="width:230px" value="<?php if($nom != ""){echo $nom;}?>" title="Escriba el nombre" placeholder="Nombre(s)" onkeyup="this.value = this.value.toUpperCase();if (this.value.length > 2) {showResult(this.value,'nom_a','nom_ajax.php')}" onfocus="oculta('app_a','apm_a','curp_a','cct_a')" onclick="_quita('p_nom')"/>
<div id="nom_a" style=" display:none;position:absolute; margin-right: auto;margin-left: auto; max-width:800px; max-height:300px; overflow:scroll; border:1; background-color:#CCCCCC;"></div></td>
</tr>
</table>
<br>
<br>
</div>
</form>
</fieldset>


<br>
<form name="boton" method="post" action="menu.php">
<input name="cancelar" value="Regresar al Menú Principal" type="submit" title="Presione para regresar al Menú principal" />
</form>
<form method="post" action="modif.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>


</div>
</body>
</>