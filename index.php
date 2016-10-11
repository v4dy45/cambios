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


!!Check the lenght of both nom and pass in the script/DB
-->

<html lang="es" id="cambios">
<head>
<?php
//charge contents of head
require ('password.php');
require ('config.php');
require ('conex.php');
require ('functions.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
<!--
function _valida_datos()
{
	var nom = document.getElementById("nom").value, pass = document.getElementById("pass").value;
	var msn = "", error = 0;
	
	if (nom == "")
	{
		msn = msn + "- El nombre de usuario es OBLIGATORIO. \n";
		document.getElementById("p_nom").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!nom.match(/^([a-z0-9]{2,15})$/i))
		{
			msn = msn + "- El Nombre de usuario contiene caractéres inválidos. \n";
			document.getElementById("p_nom").style.color="#F21111";
			error = 1;
		}
	}
	if (pass == "")
	{
		msn = msn + "- La contraseña es OBLIGATORIA. \n";
		document.getElementById("p_pass").style.color="#F21111";
		error = 1;
	}
	else
	{
		if (!pass.match(/^([a-zA-Z0-9]{2,15})$/i))
		{
			msn = msn + "- La contraseña contiene caractéres inválidos. \n";
			document.getElementById("p_pass").style.color="#F21111";
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
		return true;
	}
}

function _quita(p_ele)
{
	document.getElementById(p_ele).style.color="#000000";
}
/*function _ojito()
{
	if (document.getElementById('pass').type == 'text'){document.getElementById('pass').type='password';}
	if (document.getElementById('pass').type == 'password'){document.getElementById('pass').type='text';}
}*/
//-->
</script>

</head>

<?php
$browser = $_SERVER['HTTP_USER_AGENT'];
$chrome = '/Chrome/';
$firefox = '/Firefox/';
$ie = '/MSIE/';
if (!preg_match($chrome, $browser))
{
    echo '<script> alert("No puede usar este navegador para el sistema. Por favor utilice Opera"); </script>';
	_frogui("http://www.opera.com/es-419",1);
}

//inicializamos variables
$_SESSION ['aut']=0;
//$_SESSION ['tipo_u'] = 0;

if (!isset($_POST['nom'])){$nom="";}
else{$nom= $_REQUEST['nom'];}
if (!isset($_POST['pass'])){$pass="";}
else{$pass= $_REQUEST['pass'];}

if (isset($_POST['Aceptar']))
{
	$nom = mysql_real_escape_string($nom);
	$pass = mysql_real_escape_string($pass);
	require ("settings.php");
	if ($res0 == 1)
	{
		if ($res1 == 0)
		{
			$nom = "";
			$pass = "";
			echo '<script>alert ("Este usuario no se encuentra activo. Contacte al Administrador.");</script>';
		}
		else
		{
			if (password_verify($pass, $hash))
			{
				if (password_verify($ilfaut, $hcrypt))
				{
					$_SESSION['id_u'] = $id_u;
					$_SESSION['user'] = $user;
					$_SESSION['nivel'] = $nivel;
					$_SESSION['tipo'] = $tipo;
					$_SESSION['sub_tipo'] = $sub_tipo;
					$_SESSION['aut'] = 1;
					_frog ('menu.php');
				}
				else
				{
					$nom = "";
					$pass = "";
					echo '<script>alert ("¡¡¡ERROR FATAL CON LA BASE DE DATOS!!! Contacte al Administrador.");</script>';
				}
			}
			else
			{
				$nom = "";
				$pass = "";
				echo '<script>alert ("La contraseña es errónea.");</script>';
			}
		}
		
	}
	else
	{
		$nom = "";
		$pass = "";
		echo '<script>alert ("El nombre de usuario es erróneo.");</script>';
	}
}

?>
<form action="index.php" method="post">
<div align="center"><div class="header"></div></div>
<br><br>
<table width="311" border="1" align="center">
<tr><td width="99"><div align="left" title="Escriba su nombre de usuario"><label><p class="texto" id="p_nom">Nombre de usuario:</p></label></div></td>
<td width="196"><div align="left"><input title="Escriba su nombre de usuario" name="nom" class="campo" value="<?php if($nom != ""){echo $nom;}?>" type="text" id="nom" maxlength="10" align="left" width="200"  placeholder="Nombre de usuario" onclick="_quita('p_nom')"/></div></td></tr>
<tr><td><div align="left"><label><p class="texto" id="p_pass">Contraseña:</p></label></div></td>
<td><div align="left">
<input title="Escriba su contraseña" name="pass" class="campo" value="<?php if($pass != ""){echo $pass;}?>" type="password" id="pass" maxlength="15" align="left" width="200"  placeholder="Contraseña" onclick="_quita('p_pass')"/>
</div></td></tr>
<tr><td width="99"></td>
<td><div align="left"><input type="checkbox" onchange="document.getElementById('pass').type = this.checked ? 'text' : 'password'"><label><p class="texto_foot">Mostrar Contraseña</p></label></div></td></tr>
</table>
<br><br>
<div align="center">
<input name="Aceptar" class="boton" align="center" type="submit" onclick="return _valida_datos()" value="Aceptar" title="Presione este botón para ingresar al sistema" />
<br><br>
</div>
<div align="center"><div class="footer"></div></div>
<div align="center"><a href="javascript:alert('Ing. Laura Fabiola Ramírez Luelmo\n Ingeniería en Telemática ITAM\n Desarrolladora Web SIP de la D.O.4')"><img src='data976x35.jpg' border='0' width='976' height='35'></a></div>
</form>
</htlm>
</>
