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
require ('settings.php');
if ($_SESSION['aut'] == 0){	_frog('index.php');}
$_SESSION['grado'] = 2;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
function _valida_datos()
{
	var curp = document.getElementById("curp").value, app = document.getElementById("app").value, apm = document.getElementById("apm").value, nom = document.getElementById("nom").value;
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
	{}
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

function oculta(ele1,ele2,ele3,ele4)
{
	document.getElementById(ele1).style.display="none";
	document.getElementById(ele2).style.display="none";
	document.getElementById(ele3).style.display="none";
	document.getElementById(ele4).style.display="none";
}
-->
</script>
</head>

<?php
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



if (!isset($_POST['app'])){$app="";}
else{$app= $_REQUEST['app'];}
if (!isset($_POST['apm'])){$apm="";}
else{$apm= $_REQUEST['apm'];}
if (!isset($_POST['nom'])){$nom="";}
else{$nom= $_REQUEST['nom'];}
if (!isset($_POST['curp'])){$curp="";}
else{$curp= $_REQUEST['curp'];}
if (!isset($_POST['cct_ou'])){$cct_ou="";}
else{$cct_ou = $_REQUEST['cct_ou'];}
if (!isset($_POST['cct_org'])){$cct_org="";}
else{$cct_org = $_REQUEST['cct_org'];}


if (isset($_GET['md']))
{
	$_SESSION['curp'] = $_GET['md'];
	$sql = "SELECT
				*
			FROM
				t_inscritos
			WHERE
				curp_ins= '".$_SESSION['curp']."'
			LIMIT 1
			";
	require ('consulta.php');
	if (!$zero_results)
	{
		$row=mysql_fetch_object($rs);
		$app = $row->app_ins;
		$apm = $row->apm_ins;
		$nom = $row->nom_ins;
	}
}
else
{
	$_SESSION['curp'] = $curp;
}
$c_grado = 'espacios_2'; $ee = 'espacios_extra2';
$error = 0;
$msn = "";

if (isset($_POST['guardar']))
{
	$curp = trim($curp);
	$curp = stripslashes($curp);
	$curp = mysql_real_escape_string($curp);
	$nom = trim($nom);
	$nom = stripslashes($nom);
	$nom = mysql_real_escape_string($nom);
	$app = trim($app);
	$app = stripslashes($app);
	$app = mysql_real_escape_string($app);
	$apm = trim($apm);
	$apm = stripslashes($apm);
	$apm = mysql_real_escape_string($apm);
	$sql = "SELECT * FROM t_alumnos WHERE id_alum = '".$_SESSION['curp']."'";
	require ('consulta.php');
	if (!$zero_results) //if already exist
	{
		$sql = "SELECT
					CASE nivel_e
						WHEN 1 THEN 'DGOSE'
						WHEN 2 THEN 'DGSEI'
						WHEN 3 THEN 'DGEST'
						WHEN 4 THEN 'DGNAM'
					END,
					CASE sub_tipo_e
						WHEN 1 THEN 'D.O.1'
						WHEN 2 THEN 'D.O.2'
						WHEN 3 THEN 'D.O.3'
						WHEN 4 THEN 'D.O.4'
						WHEN 6 THEN 'D.O.6'
					END,
					cct_ou
				FROM
					t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
				WHERE
					id_alum = '".$_SESSION['curp']."'";
		require ('consulta.php');
		if (!$zero_results) {$row=mysql_fetch_object($rs); $nivel_o = $row->nivel_e; $sub_o = $row->sub_tipo_e; $cct_ou = $row->cct_ou; }
		if ($nivel_o != $_SESSION['nivel'])
		{
			echo '<script>alert ("Ese alumno ya está registrado con cambio en '.$nivel_o.' en la '.$sub_o.' en la escuela '.$cct_ou.'. Comuníquese con el Administrador de su dependencia para que borre el formato y pueda darse de alta aquí.");</script>';
		}
		else
		{
			if ($sub_o != $_SESSION['sub_tipo']){echo '<script>alert ("Ese alumno ya está registrado con cambio en la '.$sub_o.', en la escuela '.$cct_ou.'. Comuníquese con el Administrador de su dependencia para que borre el formato y pueda darse de alta aquí");</script>';}
			else {echo '<script>alert ("Ese alumno ya está registrado con cambio en la escuela '.$cct_ou.'. Si desea realizar un cambio diferente al existente por favor bórrelo y cree uno nuevo.");</script>';}
		}
	}
	else //if not
	{
		require ("alta_alum.php");
		if ($_SESSION['aux'] == 0)
		{
			require ("settings_aux.php");
			if ($cct_org == "NULL")
			{$sql = "INSERT INTO t_alumnos VALUES ('".$curp."','".$nom."','".$app."','".$apm."',NULL,'".$cct_ou."',DEFAULT,CURDATE(),'SEGUNDO','".$_SESSION['user']."',0,NULL,NULL,0,'".$temp."',".$temp_aux.")";}
			else
			{$sql = "INSERT INTO t_alumnos VALUES ('".$curp."','".$nom."','".$app."','".$apm."','".$cct_org."','".$cct_ou."',DEFAULT,CURDATE(),'SEGUNDO','".$_SESSION['user']."',0,NULL,NULL,0,'".$temp."',".$temp_aux.")";}
			require ('consulta.php');
			if ($affected_rows ==0)	//If didn't work
			{
				echo '<script>alert ("No se pudieron guardar los datos del alumno. Intente de nuevo. Si no funciona contacte al Administrador");</script>';
			}
			else
			{
				require ("spots.php");
				echo '<script>alert ("'.$msn_alta.'");</script>';
				$_SESSION['curp'] = $curp;
			}
		}
		else
		{
			echo '<script>alert ("¡ERROR FATAL CON LA DB!.El alumno no pudo darse de alta. Favor de comunicarse con el Administrador lo más pronto posible.");</script>';
		}
	}
}//If guardar


?>
<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Inscripción <u>SEGUNDO</u> Grado</legend>
<form action="cambios2.php" method="post" name="cambios2" id="cambios2">
<div align = "left">
<a class="texto" href="cambios2a.php">ALUMNO SIN CURP</a>
<table class="datos" style="width:420px">
<tr>
<td style="width:170px" ><label><p class="texto" id="p_curp">*CURP:</p></label></td>
<td><input name="curp" type="text" id="curp" class="daton" title="Escriba el CURP" value="<?php if($_SESSION['curp'] != ""){echo $_SESSION['curp'];}?>" style="width:250px" maxlength="18" placeholder="CURP" onkeyup="this.value = this.value.toUpperCase()" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?> onclick="_quita('p_curp')"/></td>
</tr>
<tr>
<td style="width:170px" ><label><p class="texto" id="p_app">*Apellido Paterno:</p></label></td>
<td><input name="app" type="text" id="app" class="daton" title="Escriba el apellido paterno" value="<?php if ($app != "") echo $app; ?>" style="width:250px" maxlength="30" placeholder="Apellido Paterno" onkeyup="this.value = this.value.toUpperCase()" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?> onclick="_quita('p_app')"/></td>
</tr>
<tr>
<td style="width:170px" ><label><p class="texto" id="p_apm">Apellido Materno:</p></label></td>
<td><input name="apm" type="text" id="apm" class="daton" title="Escriba el apellido materno" value="<?php if ($apm != "") echo $apm; ?>" style="width:250px" maxlength="30" placeholder="Apellido Materno" onkeyup="this.value = this.value.toUpperCase()" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?> onclick="_quita('p_apm')"/></td>
</tr>
<tr>
<td style="width:170px" ><label><p class="texto" id="p_nom">*Nombre(s):</p></label></td>
<td><input name="nom" type="text" id="nom" class="daton" title="Escriba el nombre" value="<?php if ($nom != "") echo $nom; ?>" style="width:250px" maxlength="30" placeholder="Nombre(s)" onkeyup="this.value = this.value.toUpperCase()" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?> onclick="_quita('p_nom')"/></td>
</tr>
<tr>
<td style="width:170px"><label><p class="texto">*Escuela a la que desea inscribirse:</p></label></td>
<td><div align="left">
<?php echo _draw_list($cct_ou); ?>
</div></td>
</tr>
<tr>
<?php
$sql="SELECT * FROM t_escuelas ORDER BY id_cct";
require("consulta.php");
?>
<td style="width:170px"><label><p class="texto">Escuela de origen:</p></label></td>
<td><div align="left">
<select name="cct_org" class="daton" style="width:250px" title="Seleccione un centro de trabajo" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?>>
  	<option value="NULL" <?php if($cct_org =="NULL") echo "selected"; ?>>-Seleccione una opción-</option>
	<?php
	while($row=mysql_fetch_object($rs))
	{
		if ($cct == $row->id_cct)
		{
			echo "<option value='$row->id_cct' selected>$row->id_cct - $row->nom_e</option>";
		}
		else
		{
			echo "<option value='$row->id_cct'>$row->id_cct - $row->nom_e</option>";
		}
	}
	?>
</select>
</div></td>
</tr>
</table>
*Los campos son obligatorios.
<br><br>
<div align="center"><input name="guardar" value="Inscribir Alumno" type="submit" title="Inscribir Alumno" onclick="return _valida_datos()" <?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?>/></div>
</div>
</form>
<br>
<form method="post" action="<?php echo $_SESSION['a']; ?>" target="_blank">
<div align="center"><input name="imprimir" value="Imprimir Formato" type="submit" title="Imprimir Formato" <?php if ($_SESSION['flag']!=1) echo 'disabled="disabled"'; ?> /></div>
</form>
</fieldset>
<br><br>
<form name="boton" method="post" action="menu.php">
<input name="cancelar" value="Regresar al Menú Principal" type="submit" title="Presione para regresar al menú principal" />
</form>
<form method="post" action="cambios2.php">
<div align="center"><br><div class="footer" align="center"></div></div><br>
</form>
</div>






</html>
</>