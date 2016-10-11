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
require ('config.php');
require ('conex.php');
require ('functions.php');
if ($_SESSION['aut'] == 0){	_frog('index.php');}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Cambios e Inscripciones DAE</title>
<link href="css/sep.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
<!--
-->
</script>
</head>



<div align="center">
<form id="header" name="header" method="POST">
<div class="header" align="center"></div>
</form>
<br><br>
<fieldset>
<legend><?php echo $_SESSION['user']; ?> - Seleccione una opción</legend>
<form action="admin_conf.php" method="post" name="admin_conf" id="admin_conf">
<?php
if ($_SESSION['t'] == 0)
{ ?>
	<div align = "center"><label><p class="textito">Existen <?php echo $_SESSION['t']; ?> folio(s) caduco(s). Para borrarlo(s) de click <a class="textote" href="borrar_c.php" onclick="window.location.reload(); window.open(this.href, 'SICAM',
'left=300,top=300,width=100,height=50,toolbar=1,resizable=0'); return false;" >AQUÍ</a>.</p></label></div>
<?php
	$_SESSION['t'] = 1;
}
else
{ ?>
	<div align = "center"><label><p class="textito">Reloaded</a>.</p></label></div>
<?php
	$_SESSION['t'] = 0;
}
?>




</form>
</fieldset>
</div>






</html>
</>