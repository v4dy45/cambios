<?php
echo '<script>alert ("Entre!!!!!");</script>';
if ($_SESSION['grado']==2){$e="espacios_2";$ee="espacios_extra2";$he="h2_2";$hee="h2";}
if ($_SESSION['grado']==3){$e="espacios_3";$ee="espacios_extra3";$he="h3_3";$hee="h3";}
require ('password.php');
echo '<script>alert ("'.$e.$ee.'");</script>';
switch ($temp)
{
	case 0:
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$_SESSION['cct']."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		$ne = $row->e + 1;
		$nhe = password_hash($ne, PASSWORD_DEFAULT);
		$sql = "UPDATE t_espacios SET $e = $ne, $he = '".$nhe."' WHERE id_cct_e = '".$_SESSION['cct']."' LIMIT 1";
		break;
	}
	case 1:
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$_SESSION['cct']."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		$nee = $row->ee + 1;
		$nhee = password_hash($nee, PASSWORD_DEFAULT);
		$sql = "UPDATE t_espacios SET $ee = $nee, $hee = '".$nhee."' WHERE id_cct_e = '".$_SESSION['cct']."' LIMIT 1";
		break;
	}
	case 2:
	{
		$sql = "UPDATE t_espacios SET id_cct_e = '".$_SESSION['cct']."' WHERE id_cct_e = '".$_SESSION['cct']."' LIMIT 1";
		//$temp_aux = 0;
		break;
	}

}
echo '<script>alert ("'.$sql.'");</script>';
require ("consulta.php");
if ($affected_rows==0){$error_e = 1;$msn_e="Ocurrió un error con los espacios. Contacte al Administrador.";}
else{$error_e=0;$msn_e="Los espacios han sido actualizados correctamente.";}
$_SESSION['flag'] = 100;

?>