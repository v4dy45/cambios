<?php
if ($_SESSION['grado']==2){$e="espacios_2";$ee="espacios_extra2";$he="h2_2";$hee="h2";}
if ($_SESSION['grado']==3){$e="espacios_3";$ee="espacios_extra3";$he="h3_3";$hee="h3";}
require ('password.php');
switch ($_SESSION['tipo'])
{
	case 0:
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$cct_ou."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		if ($row->e > 0)
		{
			$ne = $row->e - 1;
			$nhe = password_hash($ne, PASSWORD_DEFAULT);
			$sql = "UPDATE t_espacios SET $e = $ne, $he = '".$nhe."' WHERE id_cct_e = '".$cct_ou."' LIMIT 1";
			//$temp_aux = 0;
		}
		else
		{
			if ($row->ee > 0)
			{
				$nee = $row->ee - 1;
				$nhee = password_hash($nee, PASSWORD_DEFAULT);
				$sql = "UPDATE t_espacios SET $ee = $nee, $hee = '".$nhee."' WHERE id_cct_e = '".$cct_ou."' LIMIT 1";
				//$temp_aux = 1;
			}
			else
			{
				//$temp_aux = 2;
			}
		}
		break;
	}
	case ($_SESSION['tipo']== 1 || $_SESSION['tipo']==2):
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$cct_ou."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		if ($row->e > 0)
		{
			$ne = $row->e - 1;
			$nhe = password_hash($ne, PASSWORD_DEFAULT);
			$sql = "UPDATE t_espacios SET $e = $ne, $he = '".$nhe."' WHERE id_cct_e = '".$cct_ou."' LIMIT 1";
			//$temp_aux = 0;
		}
		else
		{
			$nee = $row->ee - 1;
			$nhee = password_hash($nee, PASSWORD_DEFAULT);
			$sql = "UPDATE t_espacios SET $ee = $nee, $hee = '".$nhee."' WHERE id_cct_e = '".$cct_ou."' LIMIT 1";
			//$temp_aux = 1;
		}
		break;
	}
	case 3:
	{
		$sql = "UPDATE t_espacios SET $e = $e - 1 WHERE id_cct_e = '".$cct_ou."' LIMIT 1";
		//$temp_aux = 0;
		break;
	}

}
require ("consulta.php");
if ($affected_rows==0){$error_alta = 1;$msn_alta="Ocurrió un error con los espacios en ese plantel. Imprima el comprobante y contacte al Administrador.";}
else{$error_alta=0;$msn_alta="El alumno ha sido inscrito satisfactoriamente. Para imprimir el formato presione el botón <Imprimir>";}
$_SESSION['flag'] = 1;

?>