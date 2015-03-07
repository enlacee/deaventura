<?php 
include("inc.aplication_top.php");
//echo $_GET['id_venta'];
$salidas = new Salidas(); 
$salidas->detalleSalidasCuenta($_GET['id_venta']); 
?>
