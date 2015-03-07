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
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=new">
                                <em>Nuevo Usuario</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <?php echo $msgbox->getMsgbox(); ?>
                    <?php
                    $metodoListar = $sesion->conFiltro() == false ? "listUsuarios" : "listUsuariosConFiltroDeUsuario";
                    $id = isset($_GET['id']) ? $_GET['id'] : 0;
                    $user = new Usuario($id);
                    $usuarios = new Usuarios($msgbox);
                    switch ($_GET['action']) {
                        case 'new':
                            $usuarios->newUsuarios($idioma);
                            break;
                        case 'add':
                            $usuarios->addUsuarios();
                            $usuarios->listUsuarios();
                            break;
                        case 'edit_psw':
                            $usuarios->editPassword($id);
                            break;
                        case 'update_psw':
                            $usuarios->update_password($id, $sesion->getUsuario());
                            $usuarios->listUsuarios();
                            break;
                        case 'edit':
                            $usuarios->editUsuarios($idioma);
                            break;
                        case 'update':
                            $usuarios->updateUsuarios($id, $sesion->getUsuario());
                            $usuarios->listUsuarios();
                            break;
                        case 'delete':
                            $usuarios->deleteUsuarios();
                            $usuarios->listUsuarios();
                            break;
                        case 'list':
                            $usuarios->listUsuarios();
                            break;
                        default:
                            $usuarios->listUsuarios();
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