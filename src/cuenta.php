<?php
include ('inc.aplication_top.php');
//include(_includes_ . "inc.header.php");


if (!$cuenta->getLogeado()) {
    switch ($_GET['cuenta']){
        case 'add':
            $cuenta->cuentaAdd();
            break;
        default :
            //echo '<script>location.replace("cuenta.php")</script>';
        break;
    }
} else {
    if ($_GET['cuenta'] == 'cerrar') {
        $cuenta->cerrarSesion();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="es"/>
        <title><?php echo $_GET["titulo"] . ' - ' . NOMBRE_SITIO ?></title>
        <meta name="description" content="<?php echo $descripcion ?>" />
        <meta name="language" content="spanish" />
        <meta name="country" content="PE, Perú" /> 

        <link rel="shortcut icon" href="<?php echo _imgs_ ?>/favicon_deaventura.png" />
        <link href="<?php echo _imgs_ . 'facebook/' . $image_face ?>" rel="image_src" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/app/profile-aventurero.css" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,greek,latin-ext" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]><link href="<?php echo _url_ ?>aplication/webroot/css/ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
        
        
        <script src="<?php echo _url_ ?>aplication/webroot/js/modernizr.custom.43235.js"></script>
        <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>
        <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
        
        <script type="text/javascript">
        //<![CDATA[
        var URLS = {siteUrl : '<?php echo _url_ ?>'}    //]]>
        </script>
        <!-- [if lt IE 9]
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" > </script>
        <![endif] -->
    
        <script src="<?php echo _url_ ?>aplication/webroot/js/app.js" type="text/javascript" ></script>
        <script type="text/javascript">
            $(document).ready(function() {
                App.initAppFacebook();
            });
        </script>
    </head>
<body>
    <div id="fb-root"></div>
    <div id="window">
        <div id="pagina">
            <?php include (_includes_ . "inc.top.php"); ?>

            <div class="container" id="cuerpo_cuenta">
                <?php
                if ($cuenta->getLogeado()) {
				
                    if($_GET['cuenta']){
                        switch ($_GET['cuenta']) {
                            /* case 'acceso':
                              $cuenta->cuentaAcceso($_GET["idFace"]);
                              break; */
                            case 'misdatos':
                                if ($_POST['action'] == 'update-tab1') {
                                    $cuenta->cuentaUpdate();
                                } else if ($_POST['action'] == 'update-tab2') {
                                    $cuenta->cuentaUpdateTab2();
                                } else if ($_POST['action'] == 'update-tab3') {
                                    $cuenta->cuentaUpdateTab3();
                                }
                                $cuenta->misdatos_cuenta();
                            break;
                            
                            /*case 'misdatos2':
                                $cuenta->misdatos_cuenta2();
                            break;*/
                        
                            case 'compartir':
                                switch ($_POST['action']) {
                                    case 'step2':
                                        $cuenta->comparteAventura_step2();
                                    break;
                                    case 'step3':
                                        $cuenta->comparteAventura_step3();
                                    break;
                                    case 'step4':
                                        $aventura = new Aventuras(NULL, $cuenta);
                                        $aventura->addAventura();
                                        $cuenta->comparteAventura_step4();
                                    break;
                                    default:
                                        $cuenta->comparteAventura_step1();
                                    break;
                                }
                            break;
                            case 'edit':
                                $aventura = new Aventuras(NULL, $cuenta);
                                $aventura->editAventura();
                                break;
                            case 'updateAventura':
                                $aventura = new Aventuras();
                                $aventura->updateAventura();
                                break;
                            case 'misAventuras':
                                $cuenta->misAventuras_cuenta();
                                break;
                            case 'bienvenido':
                            	$cuenta->dashboard_cuenta();
                                //$cuenta->bienvenido_cuenta();
                                break;
                            case 'solicitar-eventos':
                                $eventos = new Eventos();
                                $eventos->solicitar_eventos_cuenta($cuenta->__get("_cliente"));
                                break;
                            case 'favoritos':
                                $cuenta->favoritos_cuenta();
                                break;
                            case 'miseventos':
                                $eventos = new Eventos();
                                switch($_GET['action']){
                                    case 'new':$eventos->neweventos_cuenta();break;
                                    case 'add':$eventos->addeventos_cuenta($cuenta->__get("_cliente"));break;
                                    case 'edit':$eventos->editeventos_cuenta();break;
                                    case 'update':$eventos->updateeventos_cuenta($cuenta->__get("_cliente"));break;
                                    case 'del':$eventos->deleventos_cuenta($cuenta->__get("_cliente"));break;
                                    default:$eventos->miseventos_cuenta($cuenta->__get("_cliente"));break;
                                }
                            break;
                            case 'agencia':
                                $agencia = new Agencias(); 
                                if ($_POST['action'] == 'update') {
                                    $agencia->updateAgenciasCuenta($cuenta->__get("_cliente"));
                                }
                                $agencia->editAgenciasCuenta($cuenta->__get("_cliente")->__get("_agencia"));
                            break;
                            default :
                                $cuenta->dashboard_cuenta();
                                break;
                            }
                        }
                    if($_GET['actividades']){
                         switch($_GET['actividades']) {
                            case 'salidas':
                                $salidas = new Salidas();
                                switch($_GET['action']){
                                    case 'new':$salidas->newSalidasActividad();break;
                                    case 'add':$salidas->addSalidasActividad($cuenta->__get("_cliente"));break;
                                    case 'edit':$salidas->editSalidasActividad();break;
                                    case 'update':$salidas->updateSalidasActividad($cuenta->__get("_cliente"));break;
                                    case 'state':$salidas->stateSalidasActividad($cuenta->__get("_cliente"));break;
                                    case 'del':$salidas->deleteSalidasActividad($cuenta->__get("_cliente"));break;
                                    case 'inscritos':  $inscripciones = new Inscripciones(); $inscripciones->listInscripcionesCuenta(); break;
                                    case 'detalle':$salidas->detalleSalidasCuenta();break;
                                    default:$salidas->listSalidasActividad($cuenta->__get("_cliente"));break;
                                }
                            break;
                            case 'paquetes':
                                $paquetes = new Paquetes();
                                switch($_GET['action']){
                                    case 'new':$paquetes->newPaquetes();break;
                                    case 'add':$paquetes->addPaquetesCuenta($cuenta->__get("_cliente"));break;
                                    case 'edit':$paquetes->editPaquetesCuenta();break;
                                    case 'update':$paquetes->updatePaquetesCuenta($cuenta->__get("_cliente"));break;
                                    case 'state':$paquetes->statePaquetesCuenta($cuenta->__get("_cliente"));break;
                                    case 'del':$paquetes->deletePaquetesCuenta($cuenta->__get("_cliente"));break;
									case 'inscritos':  $inscripciones = new Inscripciones(); $inscripciones->listInscripcionesCuenta(); break;
                                    case 'detalle':$salidas->detalleSalidasCuenta();break;
                                    default:$paquetes->listPaquetes($cuenta->__get("_cliente"));break;
                                }
                            break;
                            case 'eventos':
                                $eventos = new EventosActividades();
                                switch($_GET['action']){
                                    case 'new':$eventos->newEventos();break;
                                    case 'add':$eventos->addEventosCuenta($cuenta->__get("_cliente"));break;
                                    case 'edit':$eventos->editEventosCuenta();break;
                                    case 'update':$eventos->updateEventosCuenta($cuenta->__get("_cliente"));break;
                                    case 'state':$eventos->stateEventosCuenta($cuenta->__get("_cliente"));break;
                                    case 'del':$eventos->deleteEventosCuenta($cuenta->__get("_cliente"));break;
									case 'inscritos':  $inscripciones = new Inscripciones(); $inscripciones->listInscripcionesCuenta(); break;
                                    case 'detalle':$salidas->detalleSalidasCuenta();break;
                                    default:$eventos->listEventos($cuenta->__get("_cliente"));break;
                                }
                            break;
                        }
                    } 
                    
                    if(!$_GET["cuenta"] && !$_GET["actividades"]){
                        $cuenta->dashboard_cuenta();
                        
                    }
                }
                ?>
            </div>

            <div class="footer-user container">
                <div clas="row">
                <div class="col-md-6">© 2015. De Aventura. Todos los derechos reservados.</div>
                <div class="col-md-6">Desarrollado por Develoweb</div>
                </div>
            </div>

        </div> 
    </div>
    <?php
    if ($_GET['cuenta']) {
        require_once (_includes_ . "inc.bottom.cuenta.php");
    } else {
        require_once (_includes_ . "inc.bottom.php");
    }
    ?>
</body>
</html>