<?php

define("_RUTA_",$_SERVER['DOCUMENT_ROOT']."/casa/");
include_once(_RUTA_."config.php");
include_once(_RUTA_."aplication/model/include.cls.php");
require_once(_RUTA_."aplication/model/mysql.cls.php");
require_once(_RUTA_."aplication/model/conexion.cls.php");
require_once(_RUTA_."aplication/utilities/lib.cls.php");

$link= new Conexion(_HOST, _USER, _PASSWORD, _BD);

// recibo mi valor de estado en la variable $edo 
$idd = $_GET["idp"]; 

//llamo a la función para imprimir el select 
$xtra =' onChange="cargarContent(\'dist\')" ';
impSelect("provincias",$xtra,$idd); 
?> 
