<?php
include 'inc.aplication_top.php';
include(_includes_ . "inc.header.php");

?>
    <body>
        <div id="fb-root"></div>

        <div class="container">
            container
        </div><!-- end body.container -->


        <div class="footer container">
            <div class="row">
                <div class="col-md-3">
                    <h3>De Aventura</h3>
                    <p><a href="blog/">Blog del Aventurero</a></p>
                    <p><a href="nosotros">Quiénes Somos</a></p>
                    <p><a href="contactanos">Contáctenos</a></p>
                    <p><a href="terminos-y-condiciones">Terminos y condiciones</a></p>
                    <p><a href="mapa-de-sitio">Mapa de Sitio</a></p>
                </div>
                
                <div class="col-md-9">
                    <img src="<?php echo _url_ ?>aplication/webroot/imgs/icon_tags.png" width="16" height="16" alt="tags de deportes de aventura">
                    <h3 style="padding-bottom:5px;">Mas Buscados</h3>

                    <?php
                    $tags = new Tags();
                    $arr = $tags->getTags("",30);
                    for ($i = 0; $i < count($arr); $i++) {
                        echo '<a href="q=' . url_friendly($arr[$i]['nombre_tag'], 1) . '">' . $arr[$i]['nombre_tag'] . '</a>  ';
                    }
                    ?>
                </div>
                
            </div>
            <div class="row">
                derechos reservados
            </div>
            
       
        </div>
        
        

    </body>
</html>
    
    
