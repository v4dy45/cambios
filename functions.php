<script type="text/javascript">
function _jump_imp()
{
    var myWindow = window.open("imp.php", "_blank");
}
</script>



<?php
// Validates a person's name as such and not as garbage or numbers crypt_blowfish_inv
function _valid_name($name){
$result = FALSE;
$delimiter = "#";
$pattern = "_/#%<>$^|()?*+{}[]\ºª¡!·=¿:;@~€¬";
$pattern = $delimiter."[0-9".preg_quote($pattern, $delimiter)."]".$delimiter;
$count = preg_match_all($pattern, mb_convert_encoding($name, "ISO-8859-1", "UTF-8"), $match);
if($count>0) {
	echo("<p class=\"error\">'$name' no es un nombre válido.");
	if ($debug AND $verbose)
		{
		echo("<br />Los $count carácteres inválidos son ");
		for($i=0;$i<$count;$i++) {echo(htmlentities($match[0][$i]).", ");}
		echo("Intenta de nuevo.");
		}
	echo("</p>");
	}
else
	{
	$result = TRUE;
	}
return $result;
}



// _preprint($array);
// Prints arrays
function _preprint($s, $return=false) { 
	$x = "<pre>"; 
    $x .= print_r($s, 1); 
    $x .= "</pre>"; 
    if ($return) return $x; 
    else print $x; 
} 


// _array_flatten($array);
// Flattens an array, or returns FALSE on fail. 
function _array_flatten($array)
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
	} 

// _fix_session_register();
// Fix for removed Session functions:
// Generates 3 more functions:
// 		_sess_register($var1, $var2);
// 		_sess_is_registered($var);
// 		_sess_unregister($var);
function _sess_register()
	{ 
    $args = func_get_args(); 
    foreach ($args as $key)
		{
		$_SESSION[$key] = $GLOBALS[$key];
		}
	}
function _sess_is_registered($key){return isset($_SESSION[$key]);} 

function _sess_unregister($key){unset($_SESSION[$key]);} 



/*
_explode_trim($str, $delimiter);
Performs explode() on a string with the given delimiter (or ',' by default)
and trims all whitespace for the elements
*/ 
function _explode_trim($str, $delimiter = ',')
	{ 
    if ( is_string($delimiter) )
		{ 
        $str = trim(preg_replace('|\\s*(?:'.
					preg_quote($delimiter) .
					')\\s*|',
					$delimiter, $str)); 
        return explode($delimiter, $str); 
		} 
    return $str; 
	}


/*
Verifica que una cadena sea una fecha válida (con hora)
y retorna un arreglo con lo siguiente en las posiciones:
0 - un entero, equivalente a un valor de timestamp válido.
1 - un objeto datetime
o FALSE en error
*/
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$keyote = "created by laura ramirez";
function _valid_date($cadena)
	{
	try 
		{
		// Estas generarán una excepción al final, si las fechas u horas no son válidas.
		$valid_timestamp[0] = strtotime($cadena);
		$valid_timestamp[1] = new DateTime($cadena);
		} // FIN DEL TRY (verificar valores correctos)

	catch (Exception $e)
		{
		return FALSE;
		}
	
	return $valid_timestamp;
	}
	

/*
_valid_time(str $time)
Checks if a string is a valid time formatted: 
hh:mm:ss
h:mm:ss
hh:mm
h:mm
h

and so it returns a timestamp with no date or FALSE on any fail.

uses _explode_trim()
*/
function _valid_time($cadena)
	{
	$result = array(0,0,0);
	$cadena_error = "<p class=\"error\">";
	$longitud = strlen($cadena);
	$cadena_array = _explode_trim($cadena, ':');
	$index = 0;

	// Si es de más de 8 carácteres (hh:mm:ss), anula
	if ($longitud > 8) 
		{
		echo($cadena_error."La cadena es de más de 8 carácteres:".$longitud."</p>");
		return FALSE;
		}
	
	foreach($cadena_array as $key => $value)
		{
		if ($value == "00") $value += 1;
		if ((int)$value)
			{
			if ($value == 1) $value -= 1;
			// Si es posible convertirlo a un entero, ver en qué segmento vamos.
			switch ($index)
				{
				case 0:
					if ($value < 0 || $value > 24) return FALSE;
					$result[0] = $value;
					break;
				case 1:
					if ($value < 0 || $value > 59) return FALSE;
					$result[1] = $value;
					break;
				case 2:
					if ($value < 0 || $value > 59) return FALSE;
					$result[2]= $value;
					break;
				case 3:
					echo("$cadena_error $cadena contiene más de 3 segmentos, el último es $value</p>");
					return FALSE;
				}
			// Cambiar de segmento
			$index += 1;
			}
		else
			{
			echo("$cadena_error Un segmento no se pudo convertir a un entero: $value.</p>");
			return FALSE;
			} // Fin del IF
		} // Fin del FOREACH
		
	return mktime($result[0], $result[1], $result[2]);
	}


	
/*
_are_int(list of args);
Checks if the list of arguments are integers.
*/
function _are_int ( )
	{
    $args = func_get_args ();
    foreach ( $args as $arg ) 
		{
        if ( ! is_int ( $arg ) ) { return FALSE; } else { return TRUE; }
		}
	}



/*
Converts a ressource variable into an array of all that data, unidimensional or bidimensional
Recibe:
$resource - Resource puntero a los resultados, normalmente $result

Establece:
$resultado - Arreglo uni o bidimensional equivalente a los resultados, referenciable numéricamente

Falta:
QUE EL ARREGLO CONTENGA CABECERAS 
*/
function _res2arr($resource)
	{
	if (!is_resource($resource)) return FALSE;
	
	$filas = mysql_num_rows($resource);
	$columnas = mysql_num_fields($resource);
	$temp = array();
	for ($i=0; $i<$filas; $i++)
		{
		$row = mysql_fetch_array($resource);
		// Si es una arreglo de 1 columna
		if ($columnas == 1) $temp[$i] = $row[0];
		else
		for ($j=0; $j<$columnas; $j++) 
			{
			// Si es un arreglo de 1 fila
			if ($filas == 1) $temp[$j] = $row[$j];
			else $temp[$i][$j] = $row[$j];
			}
		}
	return $temp;
	}

	
function _res2arr2($resource)
	{
	if (!is_resource($resource)) return FALSE;
	
	$filas = mysql_num_rows($resource);
	$columnas = mysql_num_fields($resource);
	for ($i=0; $i<$filas; $i++)
		{
		$row = mysql_fetch_array($resource);
		for ($j=0; $j<$columnas; $j++) 
			{
			$temp[$i][$j] = $row[$j];
			}
		}
	if (isset($temp))
		return $temp;
	else
		return FALSE;
	}

		
function _res2arr3($resource)
	{
	if (!is_resource($resource)) return FALSE;
	
	$temp = array();
	$counter = 0;
	while($row = mysql_fetch_assoc($resource))
		{
		$temp[$counter] = $row;
		$counter++;
		}
		
	return $temp;
	}
	

/*
_date_sustract($string_inicio, $string_fin)

Valida ambas cadenas de fechas y obtiene la diferencia en segundos entre ellas.

Utiliza _valid_time()
*/
function _date_sustract($inicio, $fin)
	{
	// Convierte ambos valores a TIMESTAMP
	if (!_valid_date($inicio) OR !_valid_date($fin))
		{
		return FALSE;
		}

	/*
	$inicio_timestamp = strtotime('Y-m-d', $inicio);
	$fin_timestamp = strtotime('Y-m-d', $fin);
	*/
	$inicio_timestamp = _valid_date($inicio);
	$fin_timestamp = _valid_date($fin);
	$inicio_timestamp = $inicio_timestamp[0];
	$fin_timestamp = $fin_timestamp[0];
	
	// Calcula la diferencia entre las horas, en segundos.
	$difference = $inicio_timestamp - $fin_timestamp;
	
	// Muestra información sobre el proceso positivo
	//if ($difference >= 0)	
	if ($difference >= 0 AND $debug AND $verbose)	
		{
		$days = floor($difference) / 86400;
		$hours = floor($difference / 3600);
		$minutes = ($difference / 60) % 60; 
		$seconds = $difference % 60;
		
		echo ("<br />
			Este segmento ($difference) comporta ".
			$days."d = ".
			$hours."h ".
			$minutes."m ".
			$seconds."s ".
			"continuos.
			");
		}
	else
		{
		if ($debug AND $verbose)
			echo("<br />La diferencia es negativa ('$difference').");
		}
	
	return $difference;
	}

function _boleano($string, $key)
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
}

function _boleano_check($string, $key)
{
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++)
   {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}

function _hola_mundo()
{
	$result = 'enter';
	return $result;
}

function _frog($pagi,$sec=0)
	{
	echo '<script>location.href = "'.$pagi.'";</script>';
	echo '<meta http-equiv="refresh" content="'.$sec.';url='.$pagi.'"';
	header ('Location:'.$pagi.'');
	}
	
function _frogui($pagi,$sec)
	{
	//echo '<script>location.href = "'.$pagi.'";</script>';
	echo '<meta http-equiv="refresh" content="'.$sec.';url='.$pagi.'">';
	//header ('Location:'.$pagi.'');
	}
	
/*
_time_sustract($string_inicio, $string_fin)

Verifica la validez de los argumentos como TIME y 
obtiene la diferencia en segundos entre las dos posibles horas,
asume que ambas horas son el mismo día

Utiliza _valid_time()
*/
function _time_sustract($inicio, $fin)
	{
	// Verifica que ambos valores sean TIME válidos
	$inicio_timestamp = _valid_time($inicio);
	$fin_timestamp = _valid_time($fin);
	 
	// Calcula la diferencia entre las horas, en segundos.
	$difference = $fin_timestamp - $inicio_timestamp;
	
	// Muestra información sobre el proceso positivo
	if (isset($debug, $verbose) AND $debug AND $verbose)
		if ($difference >= 0)	
			{
			$hours = floor($difference / 3600);
			$minutes = ($difference / 60) % 60; 
			$seconds = $difference % 60;
			
			echo ("<br />
				Este segmento comporta ".
				$hours."h ".
				$minutes."m ".
				$seconds."s ".
				"continuos.
				");
			}
	
	return $difference;
	}

/*
$array _2d3d($arreglo, $numero_columna)

Converts a 2D array into a 3D array according to a $numero_columna value
The $numero_columna value will be used as index for the additional dimension

*/

function _2d3d($arreglo, $numero_columna)
	{
	if (
		!is_array($arreglo) || 
		!is_int($numero_columna) || 
		$numero_columna > count(array_keys($arreglo[0])) ||
		$numero_columna < 0
		)
		return array();
	
	unset($old_temp);
	foreach($arreglo as $indice => $fila)
		{
		$temp = $fila[$numero_columna];
		if (isset($old_temp) && $temp === $old_temp)
			{
			$return[$old_temp][] = $fila;
			}
		else 
			{
			$return[$temp][] = $fila;
			}
		$old_temp = $temp;
		}
	
	return $return;
	}

	

/*
float _math_function_1()

Recibe un valor entre 0 y 1
Calcula un valor entre 0 y 1 que obedece la siguiente función Gaussian desplazada:

y(x) = 1 * exp(-abs(((1.25x - 0.2)^3)/0.3))

Para valores muy cercanos a 0 la probabilidad es casi 1
Para valores no tan cercanos a 0 y hasta 0.4, la probabilidad es 1 
Para 0.5, la probabilidad es cercana a 0.75
Para valores cercanos a 1, la probabilidad es casi 0.

*/
function _math_function_1($x, $shift)
	{
	if ($x >= -2 && $x <= 2 AND isset($shift) AND $shift >= -2 AND $shift <= 2)
		{
		$y = exp(-abs(pow(((1.25*$x) - $shift), 3)/0.3));
		}
	else
		{
		$value = FALSE;
		}
	
	if (isset($y))
		{
		$value = $y;
		}
	else
		{
		$value = FALSE;
		}
	
	return $value;
	}


/*
GAUSS Functions:
_random_0_1()	- returns a random number evenly distributed between 0 and 1
_gauss()		- returns a random number normally distributed between 0 and 1
_gauss_ms(m,s)	- returns a random number normally distributed between 0 and 1, 
					given m = mean and s = standard deviation
*/

function crypt_blowfish_inv($a,$b,$c,$d)
{
	$password = $a.$b.$c.'SEGUNDO'.$d;
	$digi = 7;
	$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $digi);
	for($i = 0; $i < 22; $i++)
	{
		$salt .= $set_salt[mt_rand(0, 22)];
	}
	return crypt($password, $salt);
}

function crypt_blowfish_rev($a,$b,$c,$d)
{
	$password = $a.$b.$c.'TERCERO'.$d;
	$digi = 7;
	$set_salt = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$salt = sprintf('$2a$%02d$', $digi);
	for($i = 0; $i < 22; $i++)
	{
		$salt .= $set_salt[mt_rand(0, 22)];
	}
	return crypt($password, $salt);
}
	
function _gauss()
{   // N(0,1)
    // returns random number with normal distribution:
    //   mean = 0
    //   std dev = 1
    
    // auxilary vars
    $x = _random_0_1();
    $y = _random_0_1();
    
    // two independent variables with normal distribution N(0,1)
    $u = sqrt(-2*log($x))*cos(2*pi()*$y);
    $v = sqrt(-2*log($x))*sin(2*pi()*$y);
    
    // i will return only one, 'cause only one is needed
    return $u;
}

function _gauss_ms($m = 0.0, $s = 1.0)
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
}


/*
_display($cadena, $int)
Displays $cadena according to the $entero case
So far, 3 cases only but it can be expanded to many more with more code possibilities
*/
function _display($cadena, $entero)
{
switch ($entero)
	{
	case 0:
		{
		$code = "<p class=\"error\">
				$cadena
				</p>
				";
		break;
		}
	
	case 1:
		{
		$code = "<p class=\"success\">
				$cadena
				</p>
				";
		break;
		}
	
	case 2:
		{
		$code = "<p class=\"warning\">
				$cadena
				</p>
				";
		break;
		}

	case 3:
		{
		$code = "<p class=\"info\">
				$cadena
				</p>
				";
		break;
		}

	case 4:
		{
		$code = "<script>
				alert('$cadena');
				</script>
				";
		break;
		}
	
	case 5:
		{
		$code = "<p class=\"calming\">
				$cadena
				</p>
				";
		break;
		}
		
	default:
		{
		$code = "<p class=\"info\">
				$cadena
				</p>
				";
		break;
		}	
	}

echo($code);

}

function stripAccents($string)
{
	$a1 = array("à","á","â","ã","ä","ç","è","é","ê","ë","ì","í","î","ï","ñ","ò","ó","ô","õ","ö","ù","ú","û","ü","ý","ÿ","ñ","À","Á","Â","Ã","Ä","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ñ","Ò","Ó","Ô","Õ","Ö","Ù","Ú","Û","Ü","Ý","Ñ"," ",".","-");
	$a2 = array("a","a","a","a","a","c","e","e","e","e","i","i","i","i","n","o","o","o","o","o","u","u","u","u","y","y","n","A","A","A","A","A","C","E","E","E","E","I","I","I","I","N","O","O","O","O","O","U","U","U","U","Y","N","","","");
	//return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿñÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝÑ','aaaaaceeeeiiiinooooouuuuyynAAAAACEEEEIIIINOOOOOUUUUYN');
	return str_replace($a1,$a2,$string);
}

/*
_draw_table($data_array, $class_string, $tablename_string)

Draws a table named $tablename_string of a class $class_string with the values in $data_array

*/
function _draw_table($arreglo, $clase = "", $nombre="")
	{
	if (is_array($arreglo) AND count($arreglo, 1) != 0)
		{
		echo("
			<table class=\"$clase\" name=\"$nombre\" >
			<tr>
			");
		
		
		$cabecera = array_keys($arreglo[0]);
		
		if (is_array($cabecera))
			{
			foreach($cabecera as $contador_1 => $titulo)
				{
				echo("<th>$titulo</th>");
				}
			echo("</tr>");
			
			foreach($arreglo as $contador_2 => $fila)
				{
				echo("<tr>");
				foreach($fila as $contador_3 => $celda)
					{
					echo("<td>$celda</td>");
					}
				echo("</tr>");
				}
			}
		
		echo("</table>");
		return TRUE;
		}		
	else
		{
		//_display("_draw_table: La variable no es un arreglo o no contiene datos.", 2);
		return FALSE;
		}
	
	}
/*
Redirects the flow to another $url in $seconds
by using three different methods: JS, PHP and HTML
It reloads the current page if $url is ommitted.
The jump is instantaneous if $seconds is ommitted
*/
function _jump_to($url = "", $seconds = 0)
	{
	echo("<script>setTimeout(\"location.href = '$url'\", $seconds * 1000);</script>");
	header("refresh:$seconds;url='$url'");
	echo("<meta http-equiv=\"refresh\" content=\"$seconds;URL='$url'\">");
	}


/*
Returns if the 'exec()' function is enabled or disabled
*/
function exec_enabled()
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
	}	

function _decrypt($string, $key)
	{
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++)
		{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
		}
   return $result;
	}
	
function _draw_list($val1) //$cct_ou
{
	if ($_SESSION['grado']==2){$aux1="espacios_2";$aux2="espacios_extra2";}
	else {if ($_SESSION['grado']==3){$aux1="espacios_3";$aux2="espacios_extra3";}}
	switch ($_SESSION['tipo'])
	{
		case 0:{$sql = "SELECT id_cct, nom_e FROM t_escuelas";break;}
		case 1:{$sql = "SELECT id_cct, nom_e FROM t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e WHERE (".$aux1." > 0 OR ".$aux2." > 0) AND sub_tipo_e = ".$_SESSION['sub_tipo'];break;}
		case 2:{if ($_SESSION['sub_tipo'] == 0){$sql = "SELECT id_cct, nom_e FROM t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e WHERE ".$aux1." > 0 OR ".$aux2." > 0 ";}else{$sql = "SELECT id_cct, nom_e FROM t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e WHERE (".$aux1." > 0 OR ".$aux2." > 0) AND sub_tipo_e = ".$_SESSION['sub_tipo'];}break;}
		case 3:{$sql = "SELECT id_cct, nom_e FROM t_escuelas LEFT JOIN t_espacios ON id_cct = id_cct_e WHERE ".$aux1." > 0 AND sub_tipo_e = ".$_SESSION['sub_tipo'];break;}
	}
	//echo '<script>alert ("'.$sql.'");</script>';
	require ("consulta.php");
	if ($_SESSION['flag']==1){$lista = '<select name="cct_ou" class="daton" style="width:250px" title="Seleccione un centro de trabajo" disabled="disabled">';}
	else{$lista = '<select name="cct_ou" class="daton" style="width:250px" title="Seleccione un centro de trabajo">';}
	/*$lista = '<select name="cct_ou" class="daton" style="width:250px" title="Seleccione un centro de trabajo" '.<?php if ($_SESSION['flag']==1) echo 'disabled="disabled"'; ?>.'>';*/
	while($row=mysql_fetch_object($rs))
	{
		if ($val1 == $row->id_cct)
		{
			$lista = $lista."<option value='$row->id_cct' selected>$row->id_cct - $row->nom_e</option>";
		}
		else
		{
			$lista = $lista."<option value='$row->id_cct'>$row->id_cct - $row->nom_e</option>";
		}
	}
	$lista = $lista."</select>";
	return $lista;
}
	
	
	
?>