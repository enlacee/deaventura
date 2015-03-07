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
                    <h1>Administrar Lugares
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="<?php echo $_SERVER['PHP_SELF']?>?action=new">
                                <em>Nueva Lugar</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <div id="nav">
                        <a href="index.php">Inicio</a> > Lugares
                    </div>
                    <?php 
                    echo $msgbox->getMsgbox(); 
                    
                    $obj = new Lugares($msgbox);
                    
                    if ($_GET['action']) {
                        $accion = $_GET['action'] . "Lugares";
                        $obj->$accion();
                    } else {
                        $obj->listLugares();
                    }
                    ?>	
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>