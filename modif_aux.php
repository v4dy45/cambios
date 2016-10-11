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
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
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
	$app = $row->app;
	$apm = $row->apm;
	$nom = $row->nom;
}
else
{
	$app = "";
	$apm = "";
	$nom = "";
}

//-->
if(isset($_POST['modif']))	//modificar
{
	$app= $_REQUEST['app'];
	$apm= $_REQUEST['apm'];
	$nom= $_REQUEST['nom'];
	
	$nom = trim($nom);
	$nom = stripslashes($nom);
	$nom = mysql_real_escape_string($nom);
	$app = trim($app);
	$app = stripslashes($app);
	$app = mysql_real_escape_string($app);
	$apm = trim($apm);
	$apm = stripslashes($apm);
	$apm = mysql_real_escape_string($apm);
	
	$sql = "UPDATE
				t_alumnos
			SET
				nom = '".$nom."',
				app = '".$app."',
				apm = '".$apm."',
				modif = 1,
				fecha_modif = CURDATE(),
				modif_by = ".$_SESSION['id_u']."
			WHERE
				id_alum = '".$_SESSION['curp']."'
			LIMIT 1
			";
	require ('consulta.php');
	if (!$zero_results)
	{
		echo '<script>alert ("Los datos del alumno con CURP '.$_SESSION['curp'].' han sido modificados.");</script>';
	}
	else
	{
		echo '<script>alert ("No se han podido realizar la modificaciones deseadas. Favor de contactar al administrador.");</script>';
	}
	$_SESSION['flag'] = 100;
}
//-->
?>
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend>Modificar Formato</legend>
<form action="modif_aux.php" method="post" name="modif_aux" id="modif_aux">
<div align="center">
<br>
<table class="datos" style="width:500px">
<tr>
<td style="width:170px"><label><p class="texto">CURP:</p></label></td>
<td style="width:330px"><input name="curp" type="text" id="curp" class="daton" maxlength="18" style="width:230px" value="<?php if($_SESSION['curp'] != ""){echo $_SESSION['curp'];}?>" title="Escriba el CURP" placeholder="CURP" disabled="disabled"/></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_app">Apellido Paterno:</p></label></td>
<td style="width:330px"><input name="app" type="text" id="app" class="daton" maxlength="20" style="width:230px" value="<?php if($app != ""){echo $app;}?>" title="Escriba el apellido paterno" placeholder="Apellido Paterno" onclick="_quita('p_app')" onkeyup="this.value = this.value.toUpperCase()"/></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_apm">Apellido Materno:</p></label></td>
<td style="width:330px"><input name="apm" type="text" id="apm" class="daton" maxlength="20" style="width:230px" value="<?php if($apm != ""){echo $apm;}?>" title="Escriba el apellido materno" placeholder="Apellido Materno" onclick="_quita('p_apm')" onkeyup="this.value = this.value.toUpperCase()"/></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto" id="p_nom">Nombre(s):</p></label></td>
<td style="width:330px"><input name="nom" type="text" id="nom" class="daton" maxlength="20" style="width:230px" value="<?php if($nom != ""){echo $nom;}?>" title="Escriba el nombre" placeholder="Nombre(s)" onclick="_quita('p_nom')" onkeyup="this.value = this.value.toUpperCase()"/></td>
</tr>
</table>
<br>
<br>
<input name='modif' type='submit' value='Modificar' onclick="return _valida_datos()" <?php if($_SESSION ['flag'] == 100) {echo "disabled='disabled'";} ?> />
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