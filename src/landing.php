<?php
include 'inc.aplication_top.php';
include(_includes_ . "inc.header.php");
//echo "cat---->>".$_GET['cat'];
$deporte = new Deporte($_GET['cat']);
/*echo "<hr/>";
echo "<pre>";
print_r($cuenta);
echo "</pre>";/**/
$secciones = new Secciones($cuenta, $deporte);
?>

<body>
    <div id="fb-root"></div>
    <script>
        //initializing API
        window.fbAsyncInit = function() {
            FB.init({appId: '466105603427618', status: true, cookie: true,
                xfbml: true});
        };
        (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
                '//connect.facebook.net/es_LA/all.js';
            document.getElementById('fb-root').appendChild(e);
        }());
    </script>
    <div id="window">
        <div id="pagina">
            <?php
            include (_includes_ . "inc.top.php");
            ?>
            <div id="cuerpo"> 
                 

                <div id="panel_right">
                    <?php $secciones->right_principal_landing(); ?>
                </div>
                <section id="seccion-right">
                    <section id="ultimas-aventuras">
                    <div class="titulo">
                        <div id="social">  
                            <div class="fb-like" data-href="<?php echo _url_ ?>" data-send="true" data-layout="button_count" data-width="250" data-show-faces="false"></div>
                            <script type="text/javascript" src="https://apis.google.com/js/plusone.js">{
                                    lang: 'es-419'
                                }</script>
                            <div class="g-plusone" data-size="medium" data-href="<?php echo _url_ ?>"></div>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo _url_ ?>" data-text="Visita este sitio" data-lang="es" data-dnt="true">Twittear</a>
                            <script>!function(d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + '://platform.twitter.com/widgets.js';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, 'script', 'twitter-wjs');</script>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <h1>UNETE A LA COMUNIDAD DE AVENTUREROS</h1>
                    <article><p>Comparte tus aventuras con la comunidad, ayuda a promover los deportes de aventura en el Perú.</p></article>
<!--                    <div id="banner-call-to-action">                      
                        <a href="http://www.deaventura.pe/landing.php" target="_blank" ><img src="aplication/webroot/imgs/bg_landing_unete.jpg" style="width:100%"></a>            
                    </div>-->
                </section>
                    <section id="ultimos-post">
                        <?php if ($secciones->__get("_cuenta")->__get("_cliente")->__get("_logeado")) { ?>  
                <a href="cuenta.php?cuenta=compartir" class="boton"><img src="aplication/webroot/imgs/concurso-gopro.jpg" style="width:100%" /></a>
                <?php } else { ?>   
                    <a href="#" class="boton" onclick="login(); return false;"><img src="aplication/webroot/imgs/bg_landing_unete.jpg" style="width:100%" /></a>
                <?php } 
                ?>
                        
                    </section>
                    
                </section>
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
            <div id="footer">
                <p>© <?php echo date('Y') ?>. De Aventura. Todos los derechos reservados.</p>
                <p><a href="mapa-de-sitio">Mapa de Sitio</a></p>
                <p>Desarrollado por <a href="http://www.develoweb.net/">Develoweb</a></p>
            </div>
            <div class="clear"></div>
        </div> 
    </div>
    <script src="//assets.pinterest.com/js/pinit.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
        {lang: 'es-419'}
    </script>
    <?php
    include (_includes_ . "inc.bottom.php");
    ?>
</body>
</html>