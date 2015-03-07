<?php require_once "phpthumb/examples/thumbnail4/thumbnail.inc.php";
$thumb = new Thumbnail("../webroot/imgs/catalogo/".$_GET['imagen']);
$thumb->adaptiveResize($_GET['w'],$_GET['h']);
$thumb->cropFromCenter5($_GET['w'],$_GET['h']);
$thumb->show();
$thumb->destruct();?>