<?php
// _array_flatten($array);
// Flattens an array, or returns FALSE on fail. 
/*function _array_flatten($array)
	{ 
	if (!is_array($array)) 
		{ 
		return FALSE; 
		}
		
	$result = array(); 
	foreach ($array as $key => $value)
		{
		if (is_array($value)) 
			{
			$result = array_merge($result, _array_flatten($value)); 
			}
		else 
			{
			$result[$key] = $value;
			}
		} 

	return $result; 
} */

// _fix_session_register();
// Fix for removed Session functions:
// Generates 3 more functions:
// 		_sess_register($var1, $var2);
// 		_sess_is_registered($var);
// 		_sess_unregister($var);

$sql="SELECT * FROM t_usuarios WHERE user='".$nom."'";
require("consulta.php");
if (!$zero_results)
{
	$res0 = 1;
	$row=mysql_fetch_object($rs);
	if ($row->activo!=1)
	{
		$res1=0;
	}
	else
	{
		$res1 = 1;
		$hash = $row->pass;
		$hcrypt = $row->hash;
		$ilfaut = $row->user.$row->nivel.$row->tipo.$row->sub_tipo.$res1;
		//put these on an array
		$id_u = $row->id_user;
		$user = $row->user;
		$nivel = $row->nivel;
		$tipo = $row->tipo;
		$sub_tipo = $row->sub_tipo;
	}
	
}
else
{
	$res0=0;
}

/*function _gauss_ms($m = 0.0, $s = 1.0)
{   // N(m,s)
    // returns random number with normal distribution:
    //   mean = $m
    //   std dev = $s
    
    return _gauss()*$s+$m;
}

function _random_0_1()
{   // auxiliary function
    // returns random number with flat distribution from 0 to 1
    return (float)rand()/(float)getrandmax();
}*/
?>