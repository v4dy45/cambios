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
include_once("config.php");
include_once("conex.php");
include_once("functions.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cambios</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script language="javascript" type="text/javascript" src="ajax/ajax.js"></script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
</head>
<body>




<?php

$q = $_GET['q'];

$con = mysqli_connect('localhost','root','configurar$do4','cambios_all');
//$con = mysqli_connect('localhost','sicam','laura','cambios');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

mysqli_select_db($con,"cambios_all");

if($_SESSION['sub_tipo'] == 0)
{
	if ($_SESSION['tipo'] == 2)		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	{
		$sql = "SELECT
					*
				FROM
					t_alumnos
				WHERE
					cct_ou LIKE '".$q."%' AND
					created_by = '".$_SESSION['user']."'
				ORDER BY
					id_alum
				";
	}
	else
	{
		$sql = "SELECT
					*
				FROM
					t_alumnos
				WHERE
					cct_ou LIKE '".$q."%'
				ORDER BY
					id_alum
				";
	}
}
else 
{
	$sql = "SELECT
				*
			FROM
				t_alumnos LEFT JOIN t_escuelas ON cct_ou = id_cct
			WHERE
				cct_ou LIKE '".$q."%' AND
				sub_tipo_e = ".$_SESSION['sub_tipo']."
			ORDER BY
				id_alum
			";
}


$result = mysqli_query($con,$sql);
//echo "<br>".$q."<br>";

echo "<table>
<tr>
<th>CURP</th>
<th>NOMBRE(S)</th>
<th>APELLIDO PATERNO</th>
<th>APELLIDO MATERNO</th>
</tr>";
while($row = mysqli_fetch_array($result,MYSQL_BOTH))
{?>
	<tr>
	<td onClick="location.href='<?php echo $_SESSION['link'];?>?md=<?php echo $row["id_alum"];?>'"><?php echo $row['id_alum']; ?></td>
	<td onClick="location.href='<?php echo $_SESSION['link'];?>?md=<?php echo $row["id_alum"];?>'"><?php echo $row['nom']; ?></td>
	<td onClick="location.href='<?php echo $_SESSION['link'];?>?md=<?php echo $row["id_alum"];?>'"><?php echo $row['app']; ?></td>
	<td onClick="location.href='<?php echo $_SESSION['link'];?>?md=<?php echo $row["id_alum"];?>'"><?php echo $row['apm']; ?></td>
	</tr>
	<?php
}
echo "</table>";
mysqli_close($con);
?>
</body>
</html>