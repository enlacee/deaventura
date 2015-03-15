<?php
include 'inc.aplication_top.php';
include(_includes_ . "inc.header.php");

$deporte = new Deporte($_GET['cat']);
$secciones = new Secciones($cuenta, $deporte);
?>
<body>
    <div id="fb-root"></div>
    <?php
    if (isset($_GET['aventura']) && $_GET['aventura'] != '') {
        $aventura = new Aventura($_GET['aventura']);
        $tipo = _catalogo_ . $aventura->__get("_deporte")->__get("_imagen_fondo");
    } else if (isset($_GET['cliente'])) {
        $tipo = _catalogo_ . 'surf_bg.jpg';
    } else if (isset($_GET['mod'])) {
        $modalidad = new Modalidad($_GET['mod']);
        $tipo = _catalogo_ . $modalidad->__get('_deporte')->__get("_imagen_fondo");
    } else if (isset($_GET['cat']) && $_GET['cat'] != 0) {
        $deporte = new Deporte($_GET['cat']);
        $tipo = _catalogo_ . $deporte->__get('_imagen_fondo');
    } else {
        unset($_GET['cat']);
        $tipo = _catalogo_ . 'sandboard_bg.jpg';
    }
    
    ?>
    <?php
        include (_includes_ . "inc.top.php");
    ?>
<!--    <div id="window" style="background:url(<?php echo $tipo ?>) no-repeat center top fixed;">-->
        <div id="window" >
        
        <div id="pagina">
            
            <div id="cuerpo">
                <?php
                
                if ($_GET['id_ruta']) {
                    $secciones->descripcion_rutas();
                } else if ($_GET['id_evento']) {
                    $secciones->descripcion_eventos();
                } else if ($_GET['id_inspiracion']) {
                    $secciones->descripcion_inspiracion();
                } else if ($_GET['id_articulo']) {
                    $secciones->descripcion_articulo();
                } else if ($_GET['id_org']) {
                    $secciones->descripcion_organizaciones();
                } else if ($_GET['id_proveedor']) {
                    $secciones->descripcion_proveedores();
                } else if ($_GET['id_agencia']) {
                    $secciones->descripcion_agencias();
                } else if (isset($_GET['aventura'])) {
                    $secciones->descripcion_aventura();
                } else if ($_GET['sec'] == 1 && $_GET['cat'] != 0) {
                    $secciones->rutas();
                } else if ($_GET['sec'] == 2 && $_GET['cat'] != 0) {
                    $secciones->eventos();
                } else if ($_GET['sec'] == 3 && $_GET['cat'] != 0) {
                    $secciones->organizaciones(); //Clubes
                } else if ($_GET['sec'] == 4 && $_GET['cat'] != 0) {
                    $secciones->proveedores(); //Tiendas
                } else if ($_GET['sec'] == 5 && $_GET['cat'] != 0) {
                    $secciones->agencias();
                }else if ($_GET['sec'] == 12 && $_GET['cat'] == 0) {                    
                    $secciones->inspiraciones();
                }else if ($_GET['sec'] == 15 && $_GET['cat'] == 0) {                    
                    $secciones->articulos();
                }else if ($_GET['sec'] == 13 && $_GET['cat'] == 0) {                    
                    $secciones->eventosAgenda();    
                }else if ($_GET['sec'] == 14 && $_GET['cat'] == 0) { 
                    $secciones->aventurasComunidad();    
                }else if ($_GET['sec'] == 14 && $_GET['cat'] != 0) {
                    $secciones->sitios_web();
                } else if ($_GET['sec'] == 7) {
                    $secciones->mapa_web();
                }else if ($_GET['sec'] == 9) {
                    $secciones->nosotros();
                }else if ($_GET['sec'] == 10) {
                    $secciones->contactanos();
                }else if ($_GET['sec'] == 11) {
                    $secciones->terminos();
                }else if ($_GET['sec'] == 8) {
                    $secciones->busqueda();
                }else if ($_GET['mod']) {
                    $secciones->deportes_modalidad();
                }else if ($_GET['cliente']) {
                    $secciones->deportes_cliente();
                }else if ($_GET['cat']) {
                    $secciones->deportes_categoria();
                }else {
                    $secciones->inicio();
                }

                ?>
                
                <section id="footer_nav">
                    <section id="footer_info">
                        <h3>De Aventura</h3>
                        <p><a href="blog/">Blog del Aventurero</a></p>
                        <p><a href="nosotros">Quiénes Somos</a></p>
                        <p><a href="contactanos">Contáctenos</a></p>
                        <p><a href="terminos-y-condiciones">Terminos y condiciones</a></p>
                        <p><a href="mapa-de-sitio">Mapa de Sitio</a></p>
                    </section>
                    <section id="tags">
                        <img src="<?php echo _url_ ?>aplication/webroot/imgs/icon_tags.png" width="16" height="16" alt="tags de deportes de aventura" style="float:left; padding-right:6px;">
                        <h3 style="padding-bottom:5px;">Mas Buscados</h3>
                        
                        <?php
                        
                        $tags = new Tags();
                        $arr = $tags->getTags("",30);
 
                        for ($i = 0; $i < count($arr); $i++) {
                             
                            echo '<a href="q=' . url_friendly($arr[$i]['nombre_tag'], 1) . '">' . $arr[$i]['nombre_tag'] . '</a>  ';
                        }
                        ?>
                    </section>
                    <div class="clear"></div>
                </section>
            </div>

            <?php //if (!$_GET["id_ruta"] && !$_GET["id_evento"] && !$_GET["id_org"] && !$_GET["id_proveedor"] && !$_GET["id_agencia"] && !$_GET["sec"] && !$_GET["mod"] && !$_GET["cat"] || $_GET["cliente"]) { ?>
            

                
             
            <footer id="footer">
                <p>© <?php echo date('Y') ?>. De Aventura. Todos los derechos reservados.</p>
                <p>Desarrollado por <a href="http://www.develoweb.net/">Develoweb</a></p>
            </footer> 
        </div> 
    </div>
    <script type="text/javascript">
        App.initAppFacebook();
    </script>
    <script src="http://assets.pinterest.com/js/pinit.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
        {lang: 'es-419'}
    </script>
    <?php
    include (_includes_ . "inc.bottom.php");
    ?>
</body>
</html>