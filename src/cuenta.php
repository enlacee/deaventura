<?php
include ('inc.aplication_top.php');
include(_includes_ . "inc.header.php");


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
<body>
    
  <script>
  $(function() {
        $( "#tabs" ).tabs();
    
  });
  </script>

    <div id="window">
        <div id="pagina">
            <?php include (_includes_ . "inc.top.php"); ?>

            <div id="cuerpo_cuenta">
                <?php
                if ($cuenta->getLogeado()) {
				
                    if($_GET['cuenta']){
                        switch ($_GET['cuenta']) {
                            /* case 'acceso':
                              $cuenta->cuentaAcceso($_GET["idFace"]);
                              break; */
                            case 'misdatos':
                                if ($_POST['action'] == 'update') {
                                    $cuenta->cuentaUpdate();
                                }
                                $cuenta->misdatos_cuenta();
                            break;
                            
                            case 'misdatos2':
                                $cuenta->misdatos_cuenta2();
                            break;
                        
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
            <div id="footer_usu">
                <div id="footer">
                    <p>© 2014. De Aventura. Todos los derechos reservados.</p>
                    <p>Desarrollado por Develoweb</p>
                </div>
            </div>
            <div class="clear"></div>
        </div> 
    </div>
    <?php
    //include (_includes_ . "inc.bottom.php");
    ?>
</body>
</html>