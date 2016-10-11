<?php
require ('password.php');
/*function exec_enabled()
	{
	$disabled = explode(', ', ini_get('disable_functions'));
	return !in_array('exec', $disabled);
	}
	
	
function _encrypt($string, $key)
	{
   $result = '';
   for($i=0; $i<strlen($string); $i++)
		{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
		}
   return base64_encode($result);
	}	*/
if ($_SESSION['grado'] == 2)
{
	$sql = "SELECT espacios_2 AS e,espacios_extra2 AS ee,h2 AS hee,h2_2 AS he FROM t_espacios WHERE id_cct_e = '".$_SESSION['cct']."'";
	//echo '<script>alert ("'.$sql.'");</script>';
}
else
{
	if ($_SESSION['grado'] == 3)
	{
		$sql = "SELECT espacios_3 AS e,espacios_extra3 AS ee,h3 AS hee,h3_3 AS he FROM t_espacios WHERE id_cct_e = '".$_SESSION['cct']."'";
	}
}
require ("consulta.php");
if ($zero_results)
{
	$_SESSION['aux']=1;
}
else
{
	$row=mysql_fetch_object($rs);
	if (password_verify($row->e, $row->he))
	{
		if (password_verify($row->ee, $row->hee))
		{
			$_SESSION['aux'] = 0;
		}
		else
		{
			$_SESSION['aux'] = 1;
		}
	}
	else
	{
		$_SESSION['aux'] = 1;
	}
}
?>