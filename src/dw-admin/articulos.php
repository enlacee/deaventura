<?php include("inc.aplication_top.php");
include(_includes_."admin/inc.header.php");
?>
<body>
<div id="dw-window"> 
<div id="dw-admin">
<div id="dw-menu">
<!-- Menu -->
<?php include(_includes_."admin/inc.top.php"); ?>
</div>
<div id="dw-page">
<div id="dw-cuerpo">
<h1>Administrar Articulos
<span class="operations">
<a href="<?php echo $_SERVER['PHP_SELF']?>">
<em>Listar</em>
<span></span>
</a>
<a href="<?php echo $_SERVER['PHP_SELF']?>?action=new">
<em>Nuevo</em>
<span></span>
</a>
</span>
</h1>
<?php echo $msgbox->getMsgbox();
$obj =  new Articulos($msgbox);
if($_GET['action']){
$accion = $_GET['action']."Articulos";   
 $obj->$accion();
}else{
 $obj->listArticulos(); 
}
?>
</div>
</div>
</div>
</div>
</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>

