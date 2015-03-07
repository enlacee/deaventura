<?php
include("inc.aplication_top.php");
include(_includes_ . "admin/inc.header.php");

?>
<body>
    <div id="dw-window"> 
        <div id="dw-admin">
            <div id="dw-menu">
                <!-- Menu -->
                <?php include(_includes_ . "admin/inc.top.php"); ?>
            </div>
            <div id="dw-page">
                <div id="dw-cuerpo" class="tag">
                    <?php 
                    $deporte = new Deporte($_GET["id_dep"]);
                    ?>
                    <h1>Administrar Tags - <?php echo $deporte->__get("_nombre_deporte") ?></h1>
                    <div id="nav">
                        <a href="index.php">Inicio</a> > <a href="deportes.php">Deportes</a> > Tags
                    </div>
                    <?php 
                    echo $msgbox->getMsgbox();
                    
                    $obj = new Tags($msgbox,$sesion->getUsuario(),$_GET["id_dep"]);
                    $obj->newTags();
                    
                    if ($_GET['action']) {
                        $accion = $_GET['action'] . "Tags";
                        $obj->$accion();
                    } else {
                        $obj->listTags();
                    }
                    ?>	
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>