<?php
include 'inc.aplication_top.php';
$secciones = new Secciones($cuenta, new Deporte());
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
        <script type="text/javascript">
        //<![CDATA[
        var URLS = {siteUrl : '<?php echo _url_ ?>'}    //]]>
        </script>

        <!-- [if lt IE 9]
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" > </script>
        <![endif] -->
    </head>
<body>
    <div id="fb-root"></div>

    <?php
        include (_includes_ . "inc.top.php");
    ?>
        <div id="window" class="aventurero">
        
        <div id="pagina">
            
            <div id="cuerpo">
                <?php

                $secciones->deportes_cliente();
                ?>
            </div>

        
            <div class="cuerpo">
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