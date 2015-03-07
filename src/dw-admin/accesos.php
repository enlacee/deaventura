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
                    <h1>Cuentas y Accesos
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF'] . "?action=list&id1=" . $_GET['id1']; ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="usuarios.php">
                                <em>Usuarios</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <?php echo $msgbox->getMsgbox(); ?>
                    <?php
                    $id = isset($_GET['id1']) ? $_GET['id1'] : 0;
                    $user = new Usuario($id);
                    $usuarios = new Usuarios($msgbox);
                    switch ($_GET['action']) {
                        case 'add':
                            $usuarios->AccesosAddUsuarios($id);
                            $usuarios->AccesoslistUsuarios($id);
                            break;
                        case 'list':
                            $usuarios->AccesoslistUsuarios($id);
                            break;
                        default:
                            $usuarios->AccesoslistUsuarios($id);
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