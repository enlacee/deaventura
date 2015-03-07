<?php include("inc.aplication_top.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Documento sin título</title>
        <style type="text/css">
            H2{ color:#2874D0; font:bold 12px Arial, Helvetica, sans-serif; margin-bottom:0px}
            label{ background:#CEE6FF; font:bold 11px Verdana, Geneva, sans-serif; padding:6px; width:40%; display:block; float:left; margin-bottom:1px}
            span{ width:51%; display:block; float:left; margin-bottom:1px; border:1px solid #CEE6FF; padding:5px}
            body{font:normal 11px Verdana, Geneva, sans-serif;}
            hr{ margin-bottom:10px}
        </style>
    </head>

    <body>
        <?php
        if ($_GET['id']) {
            $obj = new Usuario($_GET['id']);
            ?>
            <h2>DATOS DE USUARIO</h2>
            <hr color="#BFDEFF" />
            <label>Apellidos</label> <span><?php echo $obj->getApellidos(); ?></span><br clear="all" />
            <label>Nombre</label> <span><?php echo $obj->getNombre(); ?></span><br clear="all" />
            <label>Cargo</label> <span><?php echo $obj->getRol()->getNombre(); ?></span><br clear="all" />
            <label>Email</label> <span><?php echo $obj->getEmail(); ?></span><br clear="all" />
            <label>Login</label> <span><?php echo $obj->getLogin(); ?></span><br clear="all" />
            <?php }
        ?>
    </body>
</html><?php include("inc.aplication_bottom.php"); ?>