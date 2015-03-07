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
                    <h1>Administrar Modalidades - <?php
                if ($_GET["id_dep"] != "") {
                    $_SESSION["id_dep"] = $_GET["id_dep"];
                }
                $deporte = new Deporte($_SESSION["id_dep"]);
                echo $deporte->__get("_nombre_deporte");
                ?>
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?id_dep=' . $_SESSION["id_dep"] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="<?php echo $_SERVER['PHP_SELF'] . '?id_dep=' . $_SESSION["id_dep"] ?>&action=new">
                                <em>Nueva Modalidad</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <div id="nav">
                        <a href="index.php">Inicio</a> > <a href="deportes.php">Deportes</a> > Modalidades
                    </div>
                    <?php
                    echo $msgbox->getMsgbox();

                    $obj = new Modalidades($_SESSION["id_dep"], $msgbox);

                    if ($_GET['action']) {
                        $accion = $_GET['action'] . "Modalidades";
                        $obj->$accion();
                    } else {
                        $obj->listModalidades();
                    }
                    ?>	
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>