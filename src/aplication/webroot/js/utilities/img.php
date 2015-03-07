<?php

require_once("thumbnail.class.php");
$image = new Thumbnail("../webroot/imgs/catalogo/".$_GET['imagen']);
//$image->SetTexto(" aledemacedo");
//$image->SetfontColor("lavender");
$image->CreateThumbnail($_GET['w'],$_GET['h']);

?>