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
                <div id="dw-cuerpo">
                    <h1>Administrar Deportes
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=new">
                                <em>Nuevo Deporte</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <div id="nav">
                        <a href="index.php">Inicio</a> > Deportes
                    </div>
                    <?php 
                    echo $msgbox->getMsgbox();
                    
                    $obj = new Deportes($msgbox,$sesion->getUsuario());
                    if ($_GET['action']) {
                        $accion = $_GET['action'] . "Deportes";
                        $obj->$accion();
                    } else {
                        $obj->listDeportes();
                    }
                    ?>	
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>