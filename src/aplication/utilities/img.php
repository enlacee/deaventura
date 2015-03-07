<?php

require_once("thumbnail.class.php");

$image = new Thumbnail('../webroot/imgs/'.$_GET['src']);
$image->CreateThumbnail($_GET['w'],$_GET['h']);

?>