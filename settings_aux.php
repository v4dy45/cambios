<?php
if ($_SESSION['grado']==2){$e="espacios_2";$ee="espacios_extra2";$he="h2_2";$hee="h2";}
if ($_SESSION['grado']==3){$e="espacios_3";$ee="espacios_extra3";$he="h3_3";$hee="h3";}
switch ($_SESSION['tipo'])
{
	case 0:
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$cct_ou."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		if ($row->e > 0){$temp_aux = 0;}
		else{if ($row->ee > 0){$temp_aux = 1;}else{$temp_aux = 2;}}
		break;
	}
	case ($_SESSION['tipo']== 1 || $_SESSION['tipo']==2):
	{
		$sql = "SELECT $e AS e, $ee AS ee FROM t_espacios WHERE id_cct_e = '".$cct_ou."'";
		require ("consulta.php");
		$row=mysql_fetch_object($rs);
		if ($row->e > 0){$temp_aux = 0;}
		else{$temp_aux = 1;}
		break;
	}
	case 3:
	{$temp_aux = 0;break;}

}
?>