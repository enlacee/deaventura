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
                    <h1>Configuración del Sistema
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <?php echo $msgbox->getMsgbox(); ?>
                    <?php
                    switch ($_GET['action']) {
                        case 'edit':
                            $config_site->editConfiguration($_GET['id1']);
                            break;
                        case 'update':
                            $config_site->updateConfiguration($_GET['id1']);
                            $config_site->listConfiguration($_GET['id1']);
                            break;
                        default:
                            $config_site->listConfiguration($_GET['id1']);
                            break;
                    }
                    ?>
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>