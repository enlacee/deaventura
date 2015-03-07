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
                <div id="dw-cuerpo" class="tag_panel_rutas">
                    <?php $lugar = new Lugar($_GET["idl"]); ?>
                    <h1>Administrar Rutas - <?php echo $lugar->__get("_nombre_lugar") ?>
                        <span class="operations">
                            <a href="rutas.php?idl=<?php echo $_GET['idl'] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <div id="nav">
                        <a href="index.php">Inicio</a> > <a href="lugares.php">Lugares</a> > Rutas
                    </div>
                    <?php 
                    echo $msgbox->getMsgbox().'<br/>'; 
                    
                    $obj = new Rutas_otros($msgbox);
                    
                    if ($_GET['action']) {
                        $accion = $_GET['action'] . "Rutas_otros";
                        $obj->$accion();
                    } else {
                        $obj->listRutas_otros();
                    }
                    ?>	
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>