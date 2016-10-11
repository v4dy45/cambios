<?php
/*
Ejecuta una consulta
Debe pre-existir:
$sql - Cadena con la consulta

Crea/llena las siguientes variables:
$rs - Ressource al resultado de la consulta
$zero_results - TRUE si no hubo filas con resultado, FALSE otherwise
$affected_rows - Entero del número de filas a las que hace referencia $rs
$last_id - Entero identificador de la última inserción, actualización o búsqueda de $rs
$sql_errors - Arreglo con el número y el mensaje de error o cero y cadena vacía
$info - Arreglo de valores asociados al movimiento: records, duplicates, warnings, deleted, skipped, rows_matched, changed

*/
$debug = 0;
$verbose = 0;

if ($debug AND $verbose) 
	echo("<br />************** Comienza consulta: ************** <p>$sql</p>");
$zero_results = FALSE;
$rs = mysql_query($sql);
$strInfo = mysql_info();
$sql_errors[0] = mysql_errno();
$sql_errors[1] = mysql_error();

if ($verbose) echo("<br />Información del movimiento: $strInfo");

if (is_bool($rs) AND $rs == FALSE)
	{
	_display("
		La consulta falló:
		<br />$sql_errors[0] - $sql_errors[1]
		", 0);
	}
else
	{
	$last_id = mysql_insert_id();
	if ($verbose)
		echo("<br />ID del último movimiento: $last_id");
	}
	
$info = array();
preg_match("/Records: ([0-9]*)/", $strInfo, $records);
preg_match("/Duplicates: ([0-9]*)/", $strInfo, $dupes);
preg_match("/Warnings: ([0-9]*)/", $strInfo, $warnings);
preg_match("/Deleted: ([0-9]*)/", $strInfo, $deleted);
preg_match("/Skipped: ([0-9]*)/", $strInfo, $skipped);
preg_match("/Rows matched: ([0-9]*)/", $strInfo, $matched);
preg_match("/Changed: ([0-9]*)/", $strInfo, $changed);

if (isset($records[1])) $info['records'] = $records[1]; else $info['records'] = 0;
if (isset($dupes[1])) $info['duplicates'] = $dupes[1]; else $info['duplicates'] = 0;
if (isset($warnings[1])) $info['warnings'] = $warnings[1]; else $info['warnings'] = 0;
if (isset($deleted[1])) $info['deleted'] = $deleted[1]; else $info['deleted'] = 0;
if (isset($skipped[1])) $info['skipped'] = $skipped[1]; else $info['skipped'] = 0;
if (isset($matched[1])) $info['matched'] = $matched[1]; else $info['matched'] = 0;
if (isset($changed[1])) $info['changed'] = $changed[1]; else $info['changed'] = 0;


if (is_resource($rs) AND $debug AND $verbose) 
	echo("<br />El tipo de recurso es: ".get_resource_type($rs));

// Si la consulta es un select o un show, usar:
// mysql_num_rows(); si no, usar:
// mysql_affected_rows();
$inicial = substr($sql, 0, 1);
switch($inicial)
	{
	case "s":
	case "S":
		$affected_rows = mysql_num_rows($rs);
		$info['matched'] = $affected_rows;
		if ($verbose) echo("<br />La consulta es un SELECT o un SHOW y contiene $affected_rows filas.</p>");
		break;
	
	case "i":
	case "I":
		$affected_rows = mysql_affected_rows();
		$info['records'] = $affected_rows;
		if ($verbose) echo("<br />El movimiento insertó $affected_rows filas.</p>");
		break;
		
	case "r":
	case "R":
		$affected_rows = mysql_affected_rows();
		$info['matched'] = $affected_rows;
		if ($verbose) echo("<br />El movimiento afectó $affected_rows filas.</p>");
		break;
		
	case "d":
	case "D":
		$affected_rows = mysql_affected_rows();
		$info['matched'] = $affected_rows;
		$info['deleted'] = $affected_rows;
		if ($verbose) echo("<br />El movimiento borró $affected_rows filas.</p>");
		break;
	
	case "l":
	case "L":
	case "a":
	case "A":
	case "u":
	case "U":
		$affected_rows = $info['changed'];
		if ($verbose) echo("<br />El movimiento afectó $affected_rows filas de ".$info['matched']." encontradas.</p>");
		break;
	
	default:
		$affected_rows = (-1);
		break;
	}
	
if ($debug) _preprint($info);

switch ($affected_rows)
	{
	case (-1):
		echo("<p class=\"error\">_consulta.php:<br />Error al ejecutar $sql <br /> ' $sql_errors[0] - $sql_errors[1] '. <br />Debe haber una referencia a una tabla o columna inexistente o algún valor fue nulo.</p>");
		// Not to have it die miserably without error information.
		/*
		require("footer.php");
		die();
		*/
		break;
	
	case 0:
		if ($info['matched'] == $affected_rows)
			{
			if ($verbose) echo("<p class=\"error\">No hubo resultados para: <br />".$sql."</p>");
			$zero_results = TRUE;
			}
		else
			{
			if ($verbose) echo("<p class=\"warning\">No se actualizó todo lo encontrado $affected_rows / ".$info['matched']."</p>");
			$zero_results = FALSE;
			}
		break;
		
	default:
		break;
	}
	
if ($debug AND $verbose) 
	echo("<br /> ************** Termina consulta ************** <br />");
?>