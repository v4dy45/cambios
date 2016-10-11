<?php
require ('config.php');
require ('conex.php');
require ('functions.php');
//
if ($_SESSION['aut'] == 0)
{
	_frog('index.php');
	//exit();
}

?>