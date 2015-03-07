<?php

require_once("thumbnail.class.php");
$image = new Thumbnail($_GET['path'].$_GET['imagen']);
//$image->SetTexto(" aledemacedo");
//$image->SetfontColor("lavender");
$image->CreateThumbnail($_GET['w'],$_GET['h']);

?>