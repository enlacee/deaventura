<?php 
include("inc.aplication_top.php");
	
$operador = (!empty($_GET['action'])) ? $_GET['action'] : 'list' ;
 
$obj =  new Paginas( $msgbox, $sesion->getUsuario() );
$class = ucfirst(get_class($obj));
$accion = $operador.$class;
 
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
                <div id="dw-cuerpo" class="tag_panel">
                    <h1>Administrar Proveedores 
                        <span class="operations">
                            <a href="<?php echo $_SERVER['PHP_SELF']?>">
                                <em>Listar</em>
                                <span></span>
                            </a>
                            <a href="<?php echo $_SERVER['PHP_SELF']?>?action=new">
                                <em>Nueva Sección</em>
                                <span></span>
                            </a>
                        </span>
                    </h1>
                    <?php
                    echo $msgbox->getMsgbox();
                    $obj->$accion();
                    ?>  
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>