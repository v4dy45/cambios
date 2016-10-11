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

$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$cle = "created by laura ramirez";	////CAMBIAR!!!!!!!!!!!!!
$sql = "SELECT * FROM t_settings WHERE val_1 = '".$_SESSION['user']."'";
require ('conex.php');
require ('consulta.php');
if (!$zero_results){$row=mysql_fetch_object($rs);$_val[0] = 1;$_val[1] = $row->val_2;$_val[2] = $row->val_3;}
else{$_val[0] = 0;}
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