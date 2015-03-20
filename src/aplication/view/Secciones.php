<?php

class Secciones {

    private $_cuenta;
    private $_deporte;

    public function __construct(Cuenta $cuenta = NULL, Deporte $dep) {
        $this->_cuenta = $cuenta;
        $this->_deporte = $dep;
    }

    public function inicio() { ?>
        

        <div id="panel_right">
            <?php $this->right_principal(); ?>
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
                <h1>Últimas Aventuras Compartidas</h1>
                <article><p>Comparte tus aventuras con la comunidad, ayuda a promover los deportes de aventura en el Perú.</p></article>
<!--                <div id="banner-call-to-action">                      
                    <a href="http://www.deaventura.pe/landing.php" target="_blank" ><img src="aplication/webroot/imgs/banner-promo-gopro.jpg" style="width:100%"></a>            
                </div>-->
            </section>
            <?php
            $aventuras = new Aventuras();
            $aventuras->listAventuras("", "all");
            //echo '<div class="clear"></div>'; ?>
            <section id="ultimos-post" >
                <h2>Ultimos Artículos</h2>
                <ul>
                <?php
                $obj_posts = new Posts();
                $posts = $obj_posts->getPosts();
                $total_posts = count($posts);
                for($i = 0; $i < $total_posts; $i++){
                     $imagen_post = utf8_encode($obj_posts->getImagen($posts[$i]['id_post']));
                     $thumb = explode(".jpg",$imagen_post);
                     $thumbnail = $thumb[0]."-150x150.jpg";
                     $descripcion = str_replace("<strong>","",$posts[$i]["descripcion_post"]);
                     $descripcion = str_replace("</strong>","",$descripcion);
                     $url_post = _url_."blog/".$posts[$i]['url_post']; //url 
                     ?>
                    <li>
                        <section>
                            <article id="ultimos-post-image"><img src="<?php echo $thumbnail;?>" /></article> 
                            <article id="ultimos-post-description">
                                <b><a href="<?php echo $url_post ?>"><?php echo $posts[$i]["titulo_post"]; ?></a></b><br></br>
                                <p><?php echo substr($descripcion,0,320); ?>...</p>
                            </article>
                        </section>
                    </li>
                    <?php
                }  ?>
                </ul>
            </section>
            
        </section><?php
    }

    public function top_deportes() {
        $ms = "";
        $obj = new Deporte($_GET['cat']);
        $sec = $_GET['sec'];
        $sitio = new Seccion_sitio($sec);

        if (isset($_GET["id_evento"]) || isset($_GET["id_org"]) || isset($_GET["id_proveedor"]) || isset($_GET["id_agencia"]) || isset($_GET["id_ruta"])) {
            $nom_s = $sitio->__get('_nombre_seccion_sitio') . ' de ' . $obj->__get('_nombre_deporte');
            ?>
            <div class="breadcrumb">
                <a href="<?php echo _url_ ?>">Inicio</a> » 
                <a href="<?php echo url_friendly($obj->__get('_nombre_deporte'), 1) ?>"><?php echo $obj->__get('_nombre_deporte') ?></a> » 
                <a href="<?php echo url_friendly($nom_s, 1) ?>"><?php echo $nom_s ?></a> »
                <span><?php echo $_GET["titulo"] ?></span>
            </div>
            <?php include(_includes_."inc.social.php"); ?>
            <div class="clear"></div>
            <span id="deport"><?php echo $sitio->__get('_nombre_seccion_sitio') . ' de ' . $obj->__get('_nombre_deporte') ?></span>
            <h2 class="minititulo" id="sec_<?php echo $sitio->__get('_id_seccion_sitio') ?>"><?php echo $sitio->__get('_nombre_seccion_sitio') ?></h2>
            <?php
        } else if (isset($sec)) {
            ?>
            <div class="breadcrumb">
                <a href="<?php echo _url_ ?>">Inicio</a> » 
                <?php if (isset($_GET["cat"])) { ?>
                    <a href="<?php echo url_friendly($obj->__get('_nombre_deporte'), 1) ?>"><?php echo $obj->__get('_nombre_deporte') ?></a> » 
                <?php } ?>
                <span><?php echo $_GET["titulo"] ?></span>
            </div>
            <?php include(_includes_."inc.social.php"); ?>
            <div class="clear"></div>
            <h1><?php echo $_GET["titulo"] ?></h1>
            <h2 class="minititulo" id="sec_<?php echo $sitio->__get('_id_seccion_sitio') ?>"><?php echo $sitio->__get('_nombre_seccion_sitio') ?></h2>
                    
                    <?php
        } else {
            ?>
            <div class="breadcrumb">
                <?php
                if (isset($_GET["mod"])) {
                    ?>
                    <a href="<?php echo _url_ ?>">Inicio</a> » 
                    <a href="<?php echo url_friendly($obj->__get('_nombre_deporte'), 1) ?>"><?php echo $obj->__get('_nombre_deporte') ?></a> » 

                    <span><?php echo $_GET["titulo"] ?></span>
                    <?php
                } else {
                    ?>
                    <a href="<?php echo _url_ ?>">Inicio</a> » 
                    <span><?php echo $obj->__get('_nombre_deporte') ?></span>
                    <?php
                }
                ?>               
            </div>
            <?php include(_includes_."inc.social.php"); ?>
            <div class="clear"></div>
            <h1><?php echo $_GET["titulo"] ?></h1>
            <h2 class="minititulo" id="sec_2">AVENTURAS</h2>
            <?php
            $array = $obj->__get('_modalidades');
            if (count($array) > 0) {
                foreach ($array as $indice) {
                    echo '<a class="etiqueta" href="' . url_friendly($obj->__get('_nombre_deporte'), 1) . '/' . url_friendly($indice['nombre_modalidad'], 1) . '">' . $indice['nombre_modalidad'] . '</a>';
                }
            }
        }
        ?>
        <div class="clear"></div>
        <?php
    }

    public function left_eventos() { ?>
       
        <!-- registro actividades -->
        <?php
        $arr = $this->_cuenta->get_registroActividades();
        ?>
        <div class="panel" id="actividad">
            <h2>Actividad Reciente</h2>
            <ul>
                <?php
                $item = 0;
                if (!empty($arr)) {
                    foreach ($arr as $value) {
                        if($item < 10){ ?>
                            <li> <?php
                                if ($value["tipo_foto_cliente"] == 'F') {
                                    ?>
                                    <img src="https://graph.facebook.com/<?php echo $value["id_facebook_cliente"]; ?>/picture" width="24" height="24">
                                <?php } else if ($value["tipo_foto_cliente"] == 'C') { //90 90             ?>
                                    <img src="<?php echo _url_files_users_ . $value["image"]; ?>" width="24" height="24" />
                                <?php } ?>

                                <?php
                                if ($value["id_aventura"] == 0) {
                                    ?>
                                    <p><span><?php echo $value["nombre_cliente"] ?></span> ya es parte de deAventura. </p>
                                <?php } else { ?>
                                    <p><span><?php echo $value["nombre_cliente"] ?></span> publicó la aventura: <?php echo $value["titulo_aventura"] ?></p>
                                <?php } ?>

                            </li> <?php
                        }
                        $item++;
                    }


                } else {
                    ?>
                    <li>Información no disponible</li>
                    <?php
                }
                $aventuras = new Aventuras();
                $err = $aventuras->getAventuras_valoradas();

                    if (!empty($err)) { 
                        foreach ($err as $value) {
                            $url_aventura = $aventuras->url_Aventura($value["nombre_deporte"], $value["id_aventura"], $value['titulo_aventura']);
                            ?>
                            <li>
                                <a href="<?php echo $url_aventura ?>">
                                    <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/aventuras_img_usuarios/<?php echo $value['nombre_aventuras_archivos'] ?>&h=24&w=24&zc=1"/>
                                    <p><?php echo ucfirst(strtolower($value['titulo_aventura'])) ?><br><span><?php echo $value['nombre_cliente'] ?></span></p>
                                </a>
                            </li>
                            <?php
                        }
                    }
                ?>
            </ul>
        </div>
         <div id="somos">
            <p>Comunidad de aficionados a los deportes de aventura, Unete ahora</p>
            <div class="fotos">
                
                <ul>
                    <?php
                    $clientes = new Clientes();
                    $arr = $clientes->getFotos();
                    if (!empty($arr)) {
                        foreach ($arr as $value) {
                            ?>
                            <li><a href="<?php echo $clientes->getURL($value['id_cliente'], $value['nombre_cliente']) ?>"><img src="https://graph.facebook.com/<?php echo $value['id_facebook_cliente'] ?>/picture?width=24&height=24" width="24" height="24"/></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php if ($this->_cuenta->__get("_cliente")->__get("_logeado")) { ?>  
                <a href="cuenta.php?cuenta=compartir" class="boton">Ingresa y comparte tu aventura</a>
            <?php } else { ?>   
                <a href="#" class="boton" onclick="login(); return false;">Ingresa y comparte tu aventura</a>
            <?php } ?> 
        </div>
        <div class="panel" id="aventuras">
            <h2>Inspiracion de Aventura</h2>
            <?php
            $obj_inspiraciones = new Inspiraciones();
            $inspiraciones = $obj_inspiraciones->getInspiraciones();
            //$url_aventura = _url_."blog/".$value['url_post']; //url  ?>
            <ul>
            <a href="<?php echo '#'; ?>">
               <img src="<?php echo "aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/".$inspiraciones[0]['imagen'] ?>&h=262&w=255&zc=1" class="img-inspiracion">
            </a>
                   <li><?php echo ucfirst(strtolower($inspiraciones[0]['insight'])) ?></li>
            </ul>
        </div>
        <?php
    }
    
    public function right_principal() { ?>
        <div id="somos">
            <p>Comunidad de Aventureros, UNETE AHORA</p>
            <div class="fotos">
                <ul>
                    <?php
                    $clientes = new Clientes();
                    $arr = $clientes->getFotos();
                    if (!empty($arr)) {
                        foreach ($arr as $value) {
                            ?>
                            <li><a href="<?php echo $clientes->getURL($value['id_cliente'], $value['nombre_cliente']) ?>"><img src="https://graph.facebook.com/<?php echo $value['id_facebook_cliente'] ?>/picture?width=36&height=36" width="36" height="36"/></a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <?php if ($this->_cuenta->__get("_cliente")->__get("_logeado")) { ?>  
                <a href="cuenta.php?cuenta=compartir" class="boton">Ingresa y comparte tu aventura</a>
            <?php } else { ?>   
                <a href="#" class="boton" onclick="login(); return false;">Ingresa y comparte tu aventura</a>
            <?php } ?> 
        </div>
        <!-- registro actividades -->
        <?php
        $arr = $this->_cuenta->get_registroActividades();
        ?>
        <div class="panel" id="actividad">
            <h2>Actividad Reciente</h2>
            <ul>
                <?php
                $item = 0;
                if (!empty($arr)) {
                    foreach ($arr as $value) {
                        if($item < 5){ ?>
                            <li> <?php
                                if ($value["tipo_foto_cliente"] == 'F') {
                                    ?>
                                    <img src="https://graph.facebook.com/<?php echo $value["id_facebook_cliente"]; ?>/picture" width="24" height="24">
                                <?php } else if ($value["tipo_foto_cliente"] == 'C') { //90 90             ?>
                                    <img src="<?php echo _url_files_users_ . $value["image"]; ?>" width="24" height="24" />
                                <?php } ?>

                                <?php
                                if ($value["id_aventura"] == 0) {
                                    ?>
                                    <p><span><?php echo $value["nombre_cliente"] ?></span> ya está en De Aventura. </p>
                                <?php } else { ?>
                                    <p><span><?php echo $value["nombre_cliente"] ?></span> publicó la aventura: <?php echo $value["titulo_aventura"] ?></p>
                                <?php } ?>

                            </li> <?php
                        }
                        $item++;
                    }


                } else {
                    ?>
                    <li>Información no disponible</li>
                    <?php
                }
                $aventuras = new Aventuras();
                $err = $aventuras->getAventuras_valoradas();

                    if (!empty($err)) { 
                        foreach ($err as $value) {
                            $url_aventura = $aventuras->url_Aventura($value["nombre_deporte"], $value["id_aventura"], $value['titulo_aventura']);
                            ?>
                            <li>
                                <a href="<?php echo $url_aventura ?>">
                                    <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/aventuras_img_usuarios/<?php echo $value['nombre_aventuras_archivos'] ?>&h=24&w=24&zc=1"/>
                                    <p><?php echo ucfirst(strtolower($value['titulo_aventura'])) ?><br><span><?php echo $value['nombre_cliente'] ?></span></p>
                                </a>
                            </li>
                            <?php
                        }
                    }
                ?>
            </ul>
        </div>

        <div class="panel" id="proximos">
            <h2>Próximos Eventos <a href="eventos">Ver Todos</a></h2>
            <ul>
                <?php
                $obj_eventos = new Eventos();
                $eventos = $obj_eventos->getEventos(TRUE);
                /* echo '<pre>';
                  print_r($irr);
                  echo '</pre>'; */
                if (!empty($eventos)) {
                    //echo count($eventos);
                    
                    for ($i = 0; $i < 8; $i++) {
                        $deportes = $eventos[$i]["nombre_deporte"] . ', ';   ?>  
                        <li>
                            <a href="eventos-de-<?php echo strtolower($eventos[$i]['nombre_deporte'])?>/<?php echo url_friendly($eventos[$i]['titulo_evento'], 1) ?>">
                                <img src="aplication/webroot/imgs/icon_evento.jpg"/>
                                <p><?php echo ucfirst(strtolower($eventos[$i]['titulo_evento'])) ?><br> <span><?php echo trim($deportes, " ,") ?></span> - <?php echo fecha_min_long($eventos[$i]['fecha_evento']) ?></p>
                            </a>
                        </li>
                        <?php 
                    }
                } else {
                    echo '<li>Eventos no disponibles</li>';
                }
                ?>
            </ul>
        </div>

        <div class="panel" id="aventuras">
            <h2>Inspiracion de Aventura - <a href="inspiraciones">Ver Todos</a></h2>
            <?php
            $obj_inspiraciones = new Inspiraciones();
            $inspiraciones = $obj_inspiraciones->getInspiraciones();  ?>
            <ul>
            <a href="inspiraciones-de-<?php echo strtolower($inspiraciones[0]['deporte']->__get("_nombre_deporte"))?>/<?php echo url_friendly($inspiraciones[0]['url'],1) ?>">
               <img src="<?php echo "aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/image_inspiraciones/".$inspiraciones[0]['imagen'] ?>&h=310&w=310&zc=1" class="img-inspiracion">
            </a>
                   <li><?php echo ucfirst(strtolower($inspiraciones[0]['insight'])) ?></li>
            </ul>
        </div>
        <?php
    }
    
    public function right_principal_landing() { ?>
        
        <?php
        $arr = $this->_cuenta->get_registroActividades();
        ?>
        <div class="panel" id="actividad">
            <h2>Actividad Reciente de la Comunidad</h2>
            <ul>
                <?php
                if (!empty($arr)) {
                    foreach ($arr as $value) {
                        ?>
                        <li>
                            <?php
                            if ($value["tipo_foto_cliente"] == 'F') {
                                ?>
                                <img src="https://graph.facebook.com/<?php echo $value["id_facebook_cliente"]; ?>/picture" width="24" height="24">
                            <?php } else if ($value["tipo_foto_cliente"] == 'C') { //90 90             ?>
                                <img src="<?php echo _url_files_users_ . $value["image"]; ?>" width="24" height="24" />
                            <?php } ?>

                            <?php
                            if ($value["id_aventura"] == 0) {
                                ?>
                                <p><span><?php echo $value["nombre_cliente"] ?></span> ya es parte de deAventura. </p>
                            <?php } else { ?>
                                <p><span><?php echo $value["nombre_cliente"] ?></span> publicó la aventura: <?php echo $value["titulo_aventura"] ?></p>
                            <?php } ?>

                        </li>
                        <?php
                    }
                } else {
                    ?>
                    <li>Información no disponible</li>
                    <?php
                }
                ?>
            </ul>
        </div>
 

        <div class="panel" id="aventuras">
            <h2>Aventuras Valoradas</h2>
            <ul>
                <?php
                $aventuras = new Aventuras();
                $err = $aventuras->getAventuras_valoradas();

                if (!empty($err)) {
                    foreach ($err as $value) {
                        $url_aventura = $aventuras->url_Aventura($value["nombre_deporte"], $value["id_aventura"], $value['titulo_aventura']);
                        ?>
                        <li>
                            <a href="<?php echo $url_aventura ?>">
                                <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/aventuras_img_usuarios/<?php echo $value['nombre_aventuras_archivos'] ?>&h=24&w=24&zc=1"/>
                                <p><?php echo ucfirst(strtolower($value['titulo_aventura'])) ?><br><span><?php echo $value['nombre_cliente'] ?></span></p>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }
    
    /*
     * Show menu LEFT (buttons) Sections Site
     */
    public function right_deportes($descripcion = "") {
        $deporte = new Deporte($_GET["cat"]);

        $deporte->getRutas();
        $deporte->getEventos();
        $rutas = $deporte->__get("_rutas");
        $eventos = $deporte->__get("_eventos");
        ?>
        <div id="panel_right" class="inner_right">
            <?php
            $sec = new secciones_sitio();
            $arr = $sec->get_secciones_sitio();
            if (!empty($arr)) {
                ?>
                <ul id="secciones_deporte">
                    <?php foreach ($arr as $key => $value) { ?>
                        <li>
                            <a class="section" id="sec_<?php echo $key + 1 ?>" href="<?php echo url_friendly($value["nombre_seccion_sitio"], 1) . '-de-' . url_friendly($deporte->__get("_nombre_deporte"), 1) ?>"><?php echo url_friendly($value["nombre_seccion_sitio"], 1) . ' de ' . $deporte->__get("_nombre_deporte") ?> > </a>
                            <?php if ($key <= 1) { ?>
                                <?php if (strtolower($value["nombre_seccion_sitio"]) == "rutas" && !empty($rutas)) { ?>
                                    <span class="view_more">+</span> 
                                    <ul>
                                        <?php
                                        if ($_GET["cat"] != 15) { //15 es el ID del deporte "Otros" que esta en la tabla Deportes
                                            foreach ($rutas as $rut) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo url_friendly($value["nombre_seccion_sitio"], 1) . '-de-' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '/' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '-en-' . url_friendly($rut["nombre_lugar"], 1) ?>">
                                                        <?php echo $deporte->__get("_nombre_deporte") . ' en ' . $rut["nombre_lugar"] ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        } else {
                                            foreach ($rutas as $rut) {
                                                ?>
                                                <li>
                                                    <a href="<?php echo url_friendly($value["nombre_seccion_sitio"], 1) . '-de-' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '/' . url_friendly($rut["nombre_modalidad"], 1) . '-en-' . url_friendly($rut["nombre_lugar"], 1) ?>">
                                                        <?php echo $rut["nombre_modalidad"] . ' en ' . $rut["nombre_lugar"] ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>    
                                <?php }
                                
                                
                               
                              } ?>
                        </li>
                    <?php } ?>
                </ul>
                <?php
            }
            ?>

            <div class="info_panel">
                <script type="text/javascript"><!--
                    google_ad_client = "ca-pub-9292585337121975";
                    /* anumciamas-250 */
                    google_ad_slot = "5760024414";
                    google_ad_width = 250;
                    google_ad_height = 250;
                    //-->
                </script>
                <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>
            </div>
           
            <?php if ($deporte->__get('_descripcion_p') != "" && !$_GET["sec"]) { ?>
                <div class="info_panel"> 
                    <h3>
                        <img src="aplication/webroot/imgs/desc.png" width="16" height="15" >
                        El Dato:
                    </h3>
                    <div id="top_desc_deporte">
                    <?php 
                        echo $deporte->__get('_descripcion_p');
                        //echo $descripcion; 
                    ?>
                    </div>
                </div>
            <?php } ?>
            <div class="info_panel" id="tags">
                <h3>
                    <img src="aplication/webroot/imgs/icon_tags.png" width="16" height="16">
                    Más Buscados
                </h3>

                <?php
                $tags = new SeccionesTags();
                $arr = $tags->getSeccionesTagsPorDeporte($_GET["cat"], $_GET["sec"]);

                if (!empty($arr)) {
                    foreach ($arr as $value) {
                        $arr2[] = $value['visitas_tag'];
                    }
                    $min = min(array_values($arr2));
                    $max = max(array_values($arr2));

                    $spread = $max - $min;
                    if ($spread == 0) {
                        $spread = 1;
                    }

                    for ($i = 0; $i < count($arr); $i++) {
                        $size = 1 + ($arr[$i]['visitas_tag'] - $min) * (1.3 - 1) / $spread;
                        echo '<a id="q' . url_friendly($arr[$i]['id'], 1) . '" style="font-size:' . $size . 'em" href="q=' . url_friendly($arr[$i]['tag'], 1) . '">' . $arr[$i]['tag'] . '</a>  ';
                    }
                } else {
                    
                    if(!isset($_GET['id_ruta'])){
                    	echo '<span>Tags no disponibles</span>';
                    }
                }
		
               
                ?>
            </div>
            
            <div class="info_panel">
                <h3 >
                    <img src="aplication/webroot/imgs/link.png" width="16" height="15">
                    <a title="Click aqui" href="sitios-web-sobre-<?php echo url_friendly($this->_deporte->__get("_nombre_deporte"), 1) ?>">Sitios web sobre <?php echo $this->_deporte->__get("_nombre_deporte") ?></a>
                </h3>
                <?php ?>
            </div>
 
        </div>

        <?php
    }

    public function deportes_categoria() {
        
        $array_categorias_con_baner = array("Trekking","Escalada","Ciclismo","Canotaje");
        $deporte = new Deporte($_GET['cat']);

        //Top deportes
        $this->top_deportes();
 
        //Right deportes
        $this->right_deportes($deporte->__get('_descripcion'));     ?>
        
        <div id="panel_left" post-type="<?php echo $_GET['cat'] ?>" post-id="15<?php echo "deporte" ?>"> 
            <div id="banner-call-to-action"> <?php 
                //imprimir banner
                if (in_array($deporte->__get("_nombre_deporte"), $array_categorias_con_baner)) { ?>
                     <a href="#" class="banner_top_unete" onclick="login(); return false;"><img src="aplication/webroot/imgs/landing_<?php echo  strtolower($deporte->__get("_nombre_deporte")) ?>.jpg" /></a><?php
                }   ?>
            </div> <?php
         
        //eventos 
        if (count($deporte->__get('_eventos')) > 0) {
            echo "<h2>Proximos Eventos de ".$deporte->__get("_nombre_deporte")." </h2>"; 
            $eventos = new Eventos(NULL, $_GET['cat']);
            echo $eventos->listarEventos_usuario("pasados"); 
        }
            
        //historias de aventura    
        echo "<h2>Últimas Aventuras de ".$deporte->__get("_nombre_deporte")."</h2>"; 
        //Listado deportes
        $aventuras = new Aventuras(NULL, $this->_cuenta);
        $aventuras->listAventuras($_GET['cat'], "deporte"); 
        ?>
        <section id="ultimos-post" >
                <h2>Ultimos Artículos de <?php echo $deporte->__get("_nombre_deporte"); ?></h2>
                <ul>
                <?php
                $obj_posts = new Posts();
                $posts = $obj_posts->getPostsPorDeporte($deporte->__get("_nombre_deporte"));
                $total_posts = count($posts);
                for($i = 0; $i < $total_posts; $i++){
                     $imagen_post = utf8_encode($obj_posts->getImagen($posts[$i]['id_post']));
                     $thumb = explode(".jpg",$imagen_post);
                     $thumbnail = $thumb[0]."-150x150.jpg";
                     $descripcion = str_replace("<strong>","",$posts[$i]["descripcion_post"]);
                     $descripcion = str_replace("</strong>","",$descripcion);
                     $url_post = _url_."blog/".$posts[$i]['url_post']; //url 
                     ?>
                    <li>
                        <section>
                            <article id="ultimos-post-image"><image><img src="<?php echo $thumbnail;?>" /></image></article> 
                            <article id="ultimos-post-description">
                                <b><a href="<?php echo $url_post ?>"><?php echo $posts[$i]["titulo_post"]; ?></a></b><br></br>
                                <p><?php echo substr($descripcion,0,320); ?>...</p>
                            </article>
                        </section>
                    </li>
                    <?php
                }  ?>
                </ul>
            </section><?php
        ?>
        </div> 
        <div class="clear"></div>
        <?php
    }

    public function deportes_modalidad() {
        $this->top_deportes();
        $deporte = new Deporte($_GET['cat']);
        $this->right_deportes($deporte->__get('_descripcion'));
        ?>
        <div id="panel_left">
            <?php
            $aventuras = new Aventuras(NULL, $this->_cuenta);
            $aventuras->listAventuras($_GET['mod'], "modalidad");
            ?>
        </div>
        <div class="clear"></div>
        <?php
    }

    public function deportes_cliente() {
        $aventuras = new Aventuras();
        $cliente = new Cliente($_GET['cliente']);
        $tipo2 = $cliente->__get('_tipo_foto');

    ?>

        <div class="profile col-md-12 margin-bottom-20 red">
            
            <div class="row margin-bottom-10">
                <div class="col-md-1 blue">
                    <div class="me-image">
                        <?php if ($tipo2 == 'F') : ?>
                            <img src="https://graph.facebook.com/<?php echo $cliente->__get('_idFacebook'); ?>/picture?width=74&height=74" width="74" height="74">
                        <?php  elseif ($tipo2 == 'C') : ?>
                            <img src="<?php echo _url_files_users_ . $cliente->__get('_foto'); ?>" width="74" />
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-11 red">
                    <h1 class="profile-title div-inline"><?php echo $cliente->__get("_nombre").' '.$cliente->__get("_apellidos") ?></h1>
                    <div class="div-inline me-level"><span>1</span></div>
                    <div><?php echo $cliente->__get("_describete"); ?></div>
                </div>
            </div>
            

        <div class="row profile-body blue">
    
            <div class="col-md-6">
            <div class="block-gray">
                <?php if (!empty($cliente->__get("_vivo_en"))
                            || !empty($cliente->__get("_deporte_desde"))
                            || !empty($cliente->getFechaOfStarted()) ) :?>
                <h2 class="margin-bottom-5">Mis Datos:</h2>
                <ul class="mis-datos">
                    <?php if (!empty($cliente->__get("_vivo_en"))) : ?>
                        <li><span class="ico-profile ico-profile-location"></span>Vivo en <?php echo $cliente->__get("_vivo_en"); ?></li>
                    <?php endif; ?>                        
                    <?php if (!empty($cliente->__get("_deporte_desde"))) : ?>
                        <li><span class="ico-profile ico-profile-star"></span>Practico Deportes de Aventuras desde el <?php echo $cliente->__get("_deporte_desde"); ?>.</li>
                    <?php endif; ?>
                    <?php if (!empty($cliente->getFechaOfStarted())) : ?>
                        <li><span class="ico-profile ico-profile-compass"></span>Parte del DeAventura desde <?php echo $cliente->getFechaOfStarted() ?></li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            
                <?php
                    $dFavorito = $cliente->__get("_deporte_favorito");
                if (is_array($dFavorito) && count($dFavorito)> 0) : ?>
                    <h3>Deportes Favoritos:</h3>
                    <?php foreach ($dFavorito as $key => $value) :?>
                        <div class="btn btn-sport-yellow"><span class="ico-sport ico-sport-<?php echo $value['id'] ?>"></span><?php echo strtoupper($value['name']) ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>                
                
                <?php if (!empty($cliente->__get('_deporte_equipo_que_utilizo'))) : 
                $equipo = explode(',', $cliente->__get('_deporte_equipo_que_utilizo')); ?>
                <h3>Equipo que utilizo:</h3>
                <?php if (count($equipo > 0)) : ?>
                <?php foreach ($equipo as $key => $value) : ?>
                <div class="btn btn-sport-blue-small"><?php echo $value ?></div>                
                <?php endforeach; ?>
                <?php endif; ?>
                <?php endif; ?>

                <h3>Actividad:</h3>
                <div class="btn btn-sport-blue">12 Destinos/Rutas</div>
                <div class="btn btn-sport-blue">2 Aventuras</div>
                <div class="btn btn-sport-blue">3 Salidas Grupales</div>
                <div class="btn btn-sport-blue">0 Eventos Competitivos</div>
            </div>
            </div>
            
            <div id="map_canvas" class="col-md-6 block-gray" style="height:600px;">
                MAPA
                
                
            </div>

            
        </div>


            
            
        </div>
        
        
        <div class="row_ profile margin-bottom-30">
            <h2> Aventuras de <?php echo $cliente->__get("_nombre").' '.$cliente->__get("_apellidos") ?></h2>
        </div>
        
        
        <div class="row_ margin-bottom-20">
            <?php $aventuras->listAventurasCliente($_GET['cliente']); ?> 
        </div>
    
        
<?php
    }

    public function eventos() {
        $this->top_deportes();
        $evento = new Deporte($_GET['cat']);
        $evento->getEventos();
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($evento->__get('_descripcion')); ?>
        <div id="panel_left">
            <br>
            <?php
            if (count($evento->__get('_eventos')) > 0) {
                $eventos = new Eventos(NULL, $_GET['cat']);
                echo $eventos->listarEventos_usuario("pasados");

                $eve = $eventos->listarEventos_usuario("futuros");
                if ($eve != "") {
                    echo '<h3>Eventos Pasados</h3><br>' . $eve;
                }
            } else {
                echo '<br/><div align="center">No se encontraron eventos para este deporte.</div>';
            }
            ?>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function descripcion_eventos() {
        $this->top_deportes();
        $obj_evento = new Evento($_GET["id_evento"]);
        $obj = new Deporte($_GET["cat"]);
        $this->right_deportes($obj->__get('_descripcion')); ?>
        <div id="panel_left">
            
            <div class="panel_desc2 evento_desc">
                <?php
                $ajax = new Ajax();

                if (comparar_fechas($obj_evento->__get('_fecha_evento')) == 1) {
                    if ($ajax->verificar_asistencia_evento($_GET["id_evento"], $this->_cuenta->__get("_cliente")->__get("_id")) != 1) {
                        ?>
                        <div id="btn_asistire" class="abtn_evento" onclick="javascript: btn_eventos(<?php echo $_GET["id_evento"] ?>)"></div>
                    <?php } else { ?>
                        <div id="btn_asistire" class="abtn_evento active"><a title="Yo no asistiré" id="cancel_evento" href="javascript: cancel_evento(<?php echo $_GET["id_evento"] ?>);"></a></div>
                        <?php
                    }
                } else {
                    if ($ajax->verificar_asistencia_evento($_GET["id_evento"], $this->_cuenta->__get("_cliente")->__get("_id")) != 1) {
                        ?>
                        <div id="btn_estuve" class="abtn_evento" onclick="javascript: btn_eventos(<?php echo $_GET["id_evento"] ?>)"></div>
                    <?php } else { ?>
                        <div id="btn_estuve" class="abtn_evento active"><a title="Yo no estuve aqui" id="cancel_evento" href="javascript: cancel_evento(<?php echo $_GET["id_evento"] ?>);"></a></div>
                        <?php
                    }
                }
                ?>



                <h1><?php echo $obj_evento->__get('_titulo_evento'); ?></h1> 
                <section id="inscripcion-evento" style="display:none">
                    <form name="formulario_inscripcion">
                        <section class="contacto-pago">
                            <input type="text" name="nombres" placeholder="Nombres">
                            <input type="text" name="apellidos" placeholder="Apellidos">
                            <input type="text" name="dni" placeholder="DNI/Pasaporte">
                            <input type="text" name="email" placeholder="E-mail">
                            <input type="text" name="telefono" placeholder="Teléfono">
                        </section>
                        <section class="metodo-pago">
                             
                            <label for="cantidad" style="width:auto;padding-left:5px">Cantidad:</label>
                            <select name="cantidad" id="cantidad" style="width:20%">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            
                            <h3>Método de Pago</h3>
                            <input type="radio" name="metodo_pago" id="paypal" value="paypal"><label for="paypal" style="width:auto;padding-left:5px">PAYPAL (Visa / Mastercard) </label></br>
                            <input type="radio" name="metodo_pago" id="deposito" value="deposito"><label for="deposito" style="width:auto;padding-left:5px">Depósito / Transferencia </label>
                        </section>
                    </form>
                </section>
                <div class="imgae_evento_desc" align="center">
                    <?php if ($obj_evento->__get('_imagen_evento') != "") { ?>
                        <div><img src="<?php echo _url_evento_img_ . $obj_evento->__get('_imagen_evento') ?>"  class="img-responsive" /></div>
                    <?php } else { ?>
                        <div><img src="<?php echo _imgs_ . 'img_no.png' ?>"  class="img-responsive" /></div>
                    <?php } ?>
                </div>
                <p><b>Lugar:</b> <?php echo $obj_evento->__get('_lugar_evento'); ?> <br/> <b>Fecha:</b> <?php echo $obj_evento->__get('_fecha_evento'); ?></p>
                <br/> 
                <div id="descripcion_panel"><?php echo $obj_evento->__get('_descripcion_evento'); ?></div>
                <br/><br/>
                
                    <?php
                     //	Tags de Evento
                if($obj_evento->__get("_tags")){
                         
                    $tageos = explode(",",$obj_evento->__get("_tags"));
                    $ntageos = count($tageos) - 1;
                    $coma = "";
                    echo "<p id='parrafo_tags'>Tags: ";
                    for ($i = 0; $i < $ntageos; $i++) {
                         if($i > 0){ $coma = ", ";}
                    echo $coma.' <a id="q' . url_friendly(trim($tageos[$i]), 1) . '" href="q=' . url_friendly(trim($tageos[$i]), 1) . '">' . trim($tageos[$i]) . '</a>';
                    }
                    echo "</p>";
                }
                    ?>
                    
                
                <a href="<?php echo 'eventos-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a Eventos</a><br></br>
                
                <div id="face_coments_evento">
                    <div class="fb-comments" data-href="<?php echo dameURL() ?>" data-num-posts="5" data-width="690"></div>
                </div>
                
            </div>

        </div> 

        <?php
    }
    
    
    public function descripcion_actividades($id_actividad, $id_tipo_actividad) { 
        
        //echo "hola".$id_tipo_actividad;
        
        $this->top_deportes();
        $tipos_actividades = array("1"=>"Evento","2"=>"Salida","3"=>"Paquete");
        $actividad = $tipos_actividades[$id_tipo_actividad];
        $obj_actividad = new $actividad(0,$id_actividad); 
//        echo '<pre>';
//          print_r($obj_actividad);
//        echo '</pre>';
        $obj_deporte = new Deporte($_GET["cat"]);
        $this->right_deportes($obj_deporte->__get('_descripcion')); ?>
        <div id="panel_left">
            
            <div class="panel_desc2 evento_desc">
                <?php
                $ajax = new Ajax();

                if (comparar_fechas($obj_actividad->getPadre('_fecha')) == 1) {
                    if ($ajax->verificar_asistencia_evento($_GET["id_evento"], $this->_cuenta->__get("_cliente")->__get("_id")) != 1) {
                        ?>
                        <div id="btn_asistire" class="abtn_evento" onclick="javascript: btn_eventos(<?php echo $id_actividad ?>)"></div>
                    <?php } else { ?>
                        <div id="btn_asistire" class="abtn_evento active"><a title="Yo no asistiré" id="cancel_evento" href="javascript: cancel_evento(<?php echo $id_actividad ?>);"></a></div>
                        <?php
                    }
                } else {
                    if ($ajax->verificar_asistencia_evento($_GET["id_evento"], $this->_cuenta->__get("_cliente")->__get("_id")) != 1) {
                        ?>
                        <div id="btn_estuve" class="abtn_evento" onclick="javascript: btn_eventos(<?php echo $id_actividad ?>)"></div>
                    <?php } else { ?>
                        <div id="btn_estuve" class="abtn_evento active"><a title="Yo no estuve aqui" id="cancel_evento" href="javascript: cancel_evento(<?php echo $id_actividad ?>);"></a></div>
                        <?php
                    }
                }
                ?>



                <h1><?php echo $obj_actividad->getPadre('_nombre'); ?></h1> 
        
                <div class="imgae_evento_desc" align="center">
                    <?php if ($obj_actividad->getPadre('_imagen') != "") { ?>
                        <div><img src="<?php echo _url_evento_img_ . $obj_actividad->getPadre('_imagen') ?>"  class="img-responsive" /></div>
                    <?php } else { ?>
                        <div><img src="<?php echo _imgs_ . 'img_no.png' ?>"  class="img-responsive" /></div>
                    <?php } ?>
                </div>
                <p><b>Lugar:</b> <?php echo $obj_actividad->getPadre('_lugar'); ?> <br/> <b>Fecha:</b> <?php echo $obj_actividad->getPadre('_fecha'); ?></p>
                <br/> 
                <div id="descripcion_panel"><?php echo $obj_actividad->getPadre('_descripcion'); ?></div>
                <br/><br/>
                
                    <?php
                     //	Tags de Evento
                if($obj_evento->__get("_tags")){
                         
                    $tageos = explode(",",$obj_evento->__get("_tags"));
                    $ntageos = count($tageos) - 1;
                    $coma = "";
                    echo "<p id='parrafo_tags'>Tags: ";
                    for ($i = 0; $i < $ntageos; $i++) {
                         if($i > 0){ $coma = ", ";}
                    echo $coma.' <a id="q' . url_friendly(trim($tageos[$i]), 1) . '" href="q=' . url_friendly(trim($tageos[$i]), 1) . '">' . trim($tageos[$i]) . '</a>';
                    }
                    echo "</p>";
                }
                    ?>
                    
                
                <a href="<?php echo 'eventos-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a Eventos</a><br></br>
                
                <div id="face_coments_evento">
                    <div class="fb-comments" data-href="<?php echo dameURL() ?>" data-num-posts="5" data-width="690"></div>
                </div>
            </div>
        </div> 

        <?php
    }
    

    public function rutas() {
        $this->top_deportes();
        $deporte = new Deporte($_GET["cat"]);
        $deporte->getRutas();
        ?>
        <?php $this->right_deportes($deporte->__get('_descripcion')); ?>
        <div id="panel_left">
            <div class="mapTitle">
                <?php
                $array2 = $deporte->__get('_rutas');
                if (count($array2) > 0) {
                    foreach ($array2 as $indice) {
                        echo '<a href="rutas-de-' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '/' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '-en-' . url_friendly($indice['nombre_lugar'], 1) . '">' . $deporte->__get("_nombre_deporte") . ' en ' . $indice['nombre_lugar'] . '</a>';
                    }
                } else {
                    echo '&nbsp; No se encontraron ubicaciones para este deporte.';
                }
                ?>
            </div>
            <div id="map_canvas" style="width:741px;height:675px;float:left;margin:13px 0px;"></div>
            <script type="text/javascript">
                            var map;
                            var infoWindow = new google.maps.InfoWindow;

                            directionsDisplay = new google.maps.DirectionsRenderer();
                            var myOptions = {
                                zoom: 6,
                                center: new google.maps.LatLng(-8.841651, -75.940796),
                                mapTypeId: google.maps.MapTypeId.ROADMAP //Tipo de Mapa
                            };

                            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);


                            var lugares = [
                            <?php
                            $array = $deporte->__get('_rutas');
                            if (count($array) > 0) {
                                $ms = "";
                                foreach ($array as $indice) {
                                    $ms .= "['" . $indice['nombre_lugar'] . "',";
                                    $ms .= "'" . $indice['ubicacion_lugar'] . "',";
                                    $ms .= $indice['lat_lugar'] . ",";
                                    $ms .= $indice['lng_lugar'] . ",";
                                    $ms .= $indice['id_lugar'] . "],";
                                }
                                echo rtrim($ms, ',');
                            }
                            ?>
                            ];

                            setMarkers(map, lugares);

                            function bindInfoWindow(marker, map, infoWindow, html) {
                                google.maps.event.addListener(marker, 'click', function() {
                                    infoWindow.setContent(html);
                                    infoWindow.open(map, marker);
                                });
                            }

                            function setMarkers(map, locations) {
                                console.log(locations)
                                var pos = 0;
                                var image = new google.maps.MarkerImage('<?php echo _catalogo_ . $deporte->__get('_icon_map'); ?>');
                                for (var i = 0; i < locations.length; i++) {
                                    var arr = locations[i];
                                    var myLatLng = new google.maps.LatLng(arr[2], arr[3]);
                                    var marker = new google.maps.Marker({
                                        position: myLatLng,
                                        map: map,
                                        draggable: false, //Para que no se pueda mover
                                        animation: google.maps.Animation.DROP,
                                        icon: image,
                                        title: arr[0],
                                        zIndex: pos++
                                    });
                                    var html = '<b>' + arr[0] + '</b><br/>' + arr[1] + '<br/><a href="<?php echo 'rutas-de-' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '/' . url_friendly($deporte->__get("_nombre_deporte"), 1) . '-en-' ?>' + arr[0].toLowerCase().replace(/ /g, '-') + '" class="minititulo">Ver detalle</a>';
                                    bindInfoWindow(marker, map, infoWindow, html);
                                }
                            }

            </script>




        </div>

        <div class="clear"></div>
        <?php
    }

    public function descripcion_rutas() {
        $this->top_deportes();
        if ($_GET["cat"] != 15) {
            $obj_ruta = new Ruta($_GET["cat"], $_GET["id_ruta"]);
        } else {
            $obj_ruta = new Ruta($_GET["mod_rutas"], $_GET["id_ruta"], FALSE);
        }

        $obj = new Deporte($_GET["cat"]);
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($obj->__get('_descripcion')); ?>
        <div id="panel_left">
            <div class="panel_desc2" id="ruta_desc">
                <h1><?php echo str_replace("-", " ", $_GET["titulo"]) ?></h1>
                <p id="lugar">Lugar: <?php echo $obj_ruta->__get('_lugar_ruta'); ?></p>
                <div id="descripcion_panel1"><?php echo $obj_ruta->__get('_descripcion_ruta_p'); ?></div>

                <?php if ($obj_ruta->__get('_image') != "") { ?>
                    <div class="image_evento_desc" align="center">
                        <div><img src="aplication/utilities/timthumb.php?src=<?php echo _url_rutas_img_ . $obj_ruta->__get('_image') ?>&h=268&w=340&zc=1"></div>
                    </div>
                <?php } ?>

                <div id="map_ruta" <?php if ($obj_ruta->__get('_image') == "") echo 'style="width:710px"' ?>></div>

                <script type="text/javascript">
                    var myLatlng = new google.maps.LatLng(<?php echo $obj_ruta->__get('_lat_ruta'); ?>,<?php echo $obj_ruta->__get('_lng_ruta'); ?>);
                    var myOptions = {
                        zoom: 10,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                    var map = new google.maps.Map(document.getElementById("map_ruta"), myOptions);

                    var beachMarker = new google.maps.Marker({
                        position: myLatlng,
                        map: map
                    });
                </script>
                <div class="clear"></div>
                <div id="descripcion_panel2"><?php echo $obj_ruta->__get('_descripcion_ruta_s'); ?></div>
                <br/><br/>
                
                <?php
                // Tags de ruta
		if($obj_ruta->_tags){
                     
                    $tageos = explode(",",$obj_ruta->_tags);
                    $ntageos = count($tageos) - 1;
                    $coma = "";
                    echo "<p id='parrafo_tags'>Tags: ";
                    for ($i = 0; $i < $ntageos; $i++) {
                        if($i > 0){ $coma = ", ";}
                        echo $coma.'<a id="q' . url_friendly(trim($tageos[$i]), 1) . '" href="q=' . url_friendly(trim($tageos[$i]), 1) . '">' . trim($tageos[$i]) . '</a>';
                    }
                    echo "</p><br/>";
                }
                ?>
                
                <a href="<?php echo 'rutas-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a Rutas</a>
            </div>

        </div>

        <div class="clear"></div>

        <?php
    }

    public function organizaciones() {
        $this->top_deportes();
        $org = new Deporte($_GET['cat']);
        $org->getOrganizaciones();
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($org->__get('_descripcion')); ?>
        <div id="panel_left">
            
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 728x90, creado 5/04/10 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9292585337121975"
     data-ad-slot="9290713462"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

            <?php
            $array2 = $org->__get('_organizaciones');

            if (count($array2) > 0) {
                foreach ($array2 as $indice) {
                    $ms = '<div class="panel_desc2 evento_desc">';

                    $file = _url_organizacion_img_ . trim($indice["imagen_organizacion"]);
                    $ext = "";
                    if (file_exists($file)) {
                        $arr = getimagesize($file);
                        if ($arr[0] > 180)
                            $ext = 'width="180"';
                        else if ($arr[1] > 110)
                            $ext = 'height="110"';
                    }

                    $ms.= '<div class="foto_panel_desc2"><div><img src="' . $file . '" ' . $ext . ' onerror="this.src=\'aplication/webroot/imgs/img_no.png\';"/></div></div>';


                    $ms.= '<h2>' . ucwords($indice['nombre_organizacion']) . '</h2>';

                    if ($indice['telefono_organizacion'] != "")
                        $ms.= '<p><b>Teléfono:</b> ' . $indice['telefono_organizacion'] . '<br/>';
                    if ($indice['email_organizacion'] != "")
                        $ms.= '<p><b>Email:</b> ' . $indice['email_organizacion'] . '<br/>';

                    $ms.= '<b>Website:</b> <a href="' . $indice['website_organizacion'] . '" target="_blank">' . $indice['website_organizacion'] . '</a></p>';
                    $ms.= '<a title="Información de ' . $indice['nombre_organizacion'] . '" href="clubes-de-' . url_friendly($org->__get("_nombre_deporte"), 1) . '/' . url_friendly($indice['nombre_organizacion'], 1) . '">Ver descripción ></a>';
                    $ms.= '<div class="clear"></div>';
                    $ms.= '</div>';
                    echo $ms;
                }
            } else {
                echo '<br/><div align="center">No se encontraron organizaciones para este deporte.</div>';
            }
            ?>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function descripcion_organizaciones() {
        $this->top_deportes();
        $result = new Organizacion($_GET['id_org']);
        $obj = new Deporte($_GET['cat']);
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($obj->__get('_descripcion')); ?>
        <div id="panel_left">

            <div class="panel_desc2 evento_desc">
                <h1><?php echo ucfirst($result->__get('_nombre_organizacion')); ?></h1>                    

                <div class="org_desc">
                    <div class="foto_panel_org">
                        <?php if ($result->__get('_imagen_organizacion') != "") { ?>
                            <div><img src="<?php echo _url_organizacion_img_ . $result->__get('_imagen_organizacion') ?>"></div>
                        <?php } else { ?>
                            <div><img src="<?php echo _imgs_ . 'img_no.png' ?>"/></div>
                        <?php } ?>
                    </div>
                    <p>
                        <?php
                        if ($result->__get('_telefono_organizacion') != "")
                            echo '<b>Teléfono: </b>' . $result->__get('_telefono_organizacion') . '<br/>';
                        if ($result->__get('_direccion_organizacion') != "")
                            echo '<b>Dirección: </b>' . $result->__get('_direccion_organizacion') . '<br/>';
                        if ($result->__get('_website_organizacion') != "")
                            echo '<b>Website: </b><a href="' . $result->__get('_website_organizacion') . '" target="_blank">' . $result->__get('_website_organizacion') . '</a><br/>';
                        if ($result->__get('_email_organizacion') != "")
                            echo '<b>Email: </b>' . $result->__get('_email_organizacion') . '<br/>';
                        ?>
                    </p>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div id="descripcion_panel"><?php echo $result->__get('_descripcion_organizacion'); ?></div>
                <br/><br/>
                <a title="Volver" href="<?php echo 'clubes-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a los Clubs</a>
            </div>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function proveedores() {
        $this->top_deportes();
        $result = new Deporte($_GET['cat']);
        $result->getProveedores();
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($result->__get('_descripcion')); ?>
        <div id="panel_left">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 728x90, creado 5/04/10 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9292585337121975"
     data-ad-slot="9290713462"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

            <?php
            $array2 = $result->__get('_proveedores');
            if (count($array2) > 0) {
                foreach ($array2 as $indice) {
                    $ms = '<div class="panel_desc2 evento_desc">';
                    $file = _url_proveedor_img_ . trim($indice["imagen_proveedor"]);

                    $ext = "";
                    if (file_exists($file)) {
                        $arr = getimagesize($file);
                        if ($arr[0] > 180)
                            $ext = 'width="180"';
                        else if ($arr[1] > 110)
                            $ext = 'height="110"';
                    }

                    $ms.= '<div class="foto_panel_desc2"><div><img src="' . $file . '" ' . $ext . ' onerror="this.src=\'aplication/webroot/imgs/img_no.png\';"/></div></div>';
                    $ms.= '<h2><a title="Información de ' . $indice['nombre_proveedor'] . '" href="tiendas-de-' . url_friendly($result->__get("_nombre_deporte"), 1) . '/' . url_friendly($indice['nombre_proveedor'], 1) . '">' . ucwords($indice['nombre_proveedor']) . '</a></h2>';

                    if ($indice['telefono_proveedor'] != "")
                        $ms.= '<b>Teléfono:</b> ' . $indice['telefono_proveedor'] . '<br/>';

                    $ms.= '<p>' . substr($indice['descripcion_proveedor'],0,260) . '...</p>'; 
                    $ms.= '<div class="clear"></div>';
                    $ms.= '</div>';
                    echo $ms;
                }
            } else {
                echo '<br/><div align="center">No se encontraron proveedores para este deporte.</div>';
            }
            ?>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function descripcion_proveedores() {
        $this->top_deportes();
        $obj_proveedor = new Proveedor($_GET['id_proveedor']);
        $obj = new Deporte($_GET["cat"]);
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($obj->__get('_descripcion')); ?>
        <div id="panel_left">
            <div class="panel_desc2 evento_desc">
                <h1><?php echo ucfirst($obj_proveedor->__get('_nombre_proveedor')); ?></h1>                    

                <div class="org_desc">
                    <div class="foto_panel_org">
                        <?php if ($obj_proveedor->__get('_imagen_proveedor') != "") { ?>
                            <div><img src="<?php echo _url_proveedor_img_ . $obj_proveedor->__get('_imagen_proveedor') ?>"></div>
                        <?php } else { ?>
                            <div><img src="<?php echo _imgs_ . 'img_no.png' ?>"/></div>
                        <?php } ?>

                    </div>
                    <p>
                        <?php
                        if ($obj_proveedor->__get('_telefono_proveedor') != "")
                            echo '<b>Teléfono: </b>' . $obj_proveedor->__get('_telefono_proveedor') . '<br/>';
                        if ($obj_proveedor->__get('_direccion_proveedor') != "")
                            echo '<b>Dirección: </b>' . $obj_proveedor->__get('_direccion_proveedor') . '<br/>';
                        if ($obj_proveedor->__get('_website_proveedor') != "")
                            echo '<b>Website: </b><a href="' . $obj_proveedor->__get('_website_proveedor') . '" target="_blank">' . $obj_proveedor->__get('_website_proveedor') . '</a><br/>';
                        if ($obj_proveedor->__get('_email_proveedor') != "")
                            echo '<b>Email: </b>' . $obj_proveedor->__get('_email_proveedor') . '<br/>';
                        ?>
                    </p>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div id="descripcion_panel"><?php echo $obj_proveedor->__get('_descripcion_proveedor'); ?></div>

                <br/><br/>
                <?php
                // Tags de proveedor
                if($obj_proveedor->__get("_tags_proveedor")){
                        echo " <p> Tags: ";
                        $tageos = explode(",",$obj_proveedor->__get("_tags_proveedor"));
                        $ntageos = count($tageos);
                        $coma = "";
                        for ($i = 0; $i < $ntageos; $i++) {
                             if($i > 0){ $coma = ", ";}
                        echo $coma.' <a id="q' . url_friendly(trim($tageos[$i]), 1) . '" href="q=' . url_friendly(trim($tageos[$i]), 1) . '">' . trim($tageos[$i]) . '</a>';
                        }
                    echo " </p>";
                } ?>
                <a title="Volver" href="<?php echo 'tiendas-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a Tiendas</a>
            </div>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function agencias() {
        $this->top_deportes();
        $result = new Deporte($_GET['cat']);
        $result->getAgencias();
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($result->__get('_descripcion')); ?>
        <div id="panel_left">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 728x90, creado 5/04/10 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9292585337121975"
     data-ad-slot="9290713462"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
            <?php 
            $array2 = $result->__get('_agencias');
            if (count($array2) > 0) {
                foreach ($array2 as $indice) {
                    $ms = '<div class="panel_desc2 evento_desc">';
                    $file = _url_agencia_img_ . $indice["imagen_agencia"];

                    $ext = "";
                    if (file_exists($file)) {
                        $arr = getimagesize($file);
                        if ($arr[0] > 180)
                            $ext = 'width="180"';
                        else if ($arr[1] > 110)
                            $ext = 'height="110"';
                    }

                    $ms.= '<div class="foto_panel_desc2"><div><img src="' . $file . '" ' . $ext . ' onerror="this.src=\'aplication/webroot/imgs/img_no.png\';"/></div></div>';

                    $ms.= '<h2><a title="Información de ' . $indice['nombre_agencia'] . '" href="agencias-de-' . url_friendly($result->__get("_nombre_deporte"), 1) . '/' . url_friendly($indice['nombre_agencia'], 1) . '">'.
 ucwords($indice['nombre_agencia']) . '</a></h2>';
                    if ($indice['telefono_agencia'] != "") {
                        $ms.= '<b>Teléfono:</b> ' . $indice['telefono_agencia'] . '<br/>';
                    }
                    $ms.= '<p>' . substr($indice['descripcion_agencia'], 0, 260) . '...</p>';
                    $ms.= '<div class="clear"></div>';
                    $ms.= '</div>';
                    echo $ms;
                }
            } else {
                echo '<br/><div align="center">No se encontraron agencias para este deporte.</div>';
            }
            ?>

        </div>

        <div class="clear"></div>
        <?php
    }
    
    /*
     * Show page home Agencia
     * @xample : http://deaventura.local/agencias-de-canotaje/lunahuan%C3%A1-rafting-per%C3%BA
     */
    public function descripcion_agencias() {
        $this->top_deportes();
        $obj_agencia = new Agencia($_GET['id_agencia']);
        $obj = new Deporte($_GET["cat"]); ?>
        <div class="clear"></div>

        <?php $this->right_deportes($obj->__get('_descripcion')); ?>
        <div id="panel_left">
            <div class="panel_desc2 evento_desc">
                <h1><?php echo ucfirst($obj_agencia->__get('_nombre_agencia')); ?></h1>                    

                <div class="org_desc">
                    <div class="foto_panel_org">
                        <?php if ($obj_agencia->__get('_imagen_agencia') != "") { ?>
                            <div><img src="<?php echo _url_agencia_img_ . $obj_agencia->__get('_imagen_agencia') ?>"/></div>
                        <?php } else { ?>
                            <div><img src="<?php echo _imgs_ . 'img_no.png' ?>"/></div>
                        <?php } ?>
                    </div>
                    <p>
                        <?php
                        if ($obj_agencia->__get('_telefono_agencia') != "")
                            echo '<b>Teléfono: </b>' . $obj_agencia->__get('_telefono_agencia') . '<br/>';
                        if ($obj_agencia->__get('_direccion_agencia') != "")
                            echo '<b>Dirección: </b>' . $obj_agencia->__get('_direccion_agencia') . '<br/>';
                        if ($obj_agencia->__get('_website_agencia') != "")
                            echo '<b>Website: </b><a href="' . $obj_agencia->__get('_website_agencia') . '" target="_blank">' . $obj_agencia->__get('_website_agencia') . '</a><br/>';
                        if ($obj_agencia->__get('_email_agencia') != "")
                            echo '<b>Email: </b>' . $obj_agencia->__get('_email_agencia') . '<br/>';
                        ?>
                    </p>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div id="descripcion_panel"><?php echo $obj_agencia->__get('_descripcion_agencia'); ?></div>

                <br/><br/> 
                <?php
                // Tags de Evento
                if($obj_agencia->__get("_tags_agencia")){
                        echo " <p> Tags: ";
                        $tageos = explode(",",$obj_agencia->__get("_tags_agencia"));
                        $ntageos = count($tageos);
                        $coma = "";
                        for ($i = 0; $i < $ntageos; $i++) {
                             if($i > 0){ $coma = ", ";}
                        echo $coma.' <a id="q' . url_friendly(trim($tageos[$i]), 1) . '" href="q=' . url_friendly(trim($tageos[$i]), 1) . '">' . trim($tageos[$i]) . '</a>';
                        }
                    echo " </p>";
                } ?>
                <a href="<?php echo 'agencias-de-' . url_friendly($obj->__get("_nombre_deporte"), 1) ?>">< Volver a Agencias</a>
            </div>

        </div>

        <div class="clear"></div>
        <?php
    }

    public function descripcion_aventura() {

        $miAventura = new Aventura($_GET["aventura"]);
        $favoritos = new Favoritos();

        //Actualizar likes y comentarios
        //Aventuras::update_likes_comments(dameURL(), $_GET["aventura"]); Se demora mas en cargar los datos
        ?>
        
        <section id="detalle-aventura-left">
            
            <div id="showcase" class="showcase" style="width:100%">

                <?php
                $array_arch = $miAventura->__get('_archivos');

                foreach ($array_arch as $indice) {
                    if ($indice["tipo_aventuras_archivo"] == 'F') {

                        $file = _url_avfiles_users_ . $indice["nombre_aventuras_archivos"];
                        $imagen = getimagesize($file);
                        if ($imagen) {
                            $ancho = $imagen[0];
                            $ratio = 548 / $imagen[1];
                        }

                        $mss = '<div class="showcase-slide">';
                        $mss.= '<div class="showcase-content">';
                        $mss.= '<div class="showcase-content-wrapper">'; 
                        $mss.= '<img src="' . $file . '" height="548" width="' . ($ratio * $ancho) . '"  />';
                        //$mss.= '<img src="'._url_.'aplication/utilities/timthumb.php?src='.$file.'&h=548&zc=1"/>';
                        $mss.= '</div>';
                        $mss.= '</div>';
                        $mss.= '<div class="showcase-tooltips"><a href="#" coords="35,35">' . $indice['comentario_aventuras_archivo'] . '</a></div>';
                        $mss.= '</div>';

                        echo $mss;
                    }
                    if ($indice["tipo_aventuras_archivo"] == 'V') {

                        $mss = '<div class="showcase-slide">';
                        $mss.= '<div class="showcase-content">';
                        $mss.= '<div class="showcase-content-wrapper" style="width:800px;height:490px;">';
                        $mss .='<iframe width="800" height="548" src="http://www.youtube.com/embed/' . $indice["nombre_aventuras_archivos"] . '" frameborder="0" allowfullscreen></iframe>';
                        $mss.= '</div>';
                        $mss.= '</div>';
                        $mss.= '<div class="showcase-tooltips"><a href="#" coords="35,35">' . $indice['comentario_aventuras_archivo'] . '</a></div>';
                        $mss.= '</div>';
                        echo $mss;
                    }
                }
                ?>
            </div>
            <section id="info"  > 
                <h1 id="info_titulo"><?php echo $miAventura->__get('_titulo_aventura'); ?></h1>
            </section>
            <section id="descp_persona">
                <!-- INFORMACIÓN TEXTUAL DE LA AVENTURA -->
                <div id="content_descp"> 
                    <p><?php echo $miAventura->__get('_descripcion_aventura'); ?></p> </br>
                    <p id="fecha">Fecha de publicación: <?php echo fecha_long($miAventura->__get('_fecha_creacion_aventura')); ?></p>
                    <?php
                    $clientes = new Clientes();
                    $url = $clientes->getURL($miAventura->__get('_cliente')->__get('_id'), $miAventura->__get('_cliente')->__get('_nombre'));  ?>
                </div>
                <!-- INFORMACIÓN DE USUARIO -->
                <section id="info">  
                    <div id="bottom_info">
                       <div id="foto">
                        <?php
                        $tipo2 = $miAventura->__get('_cliente')->__get('_tipo_foto');
                        if ($tipo2 == 'F') {
                            ?>
                            <img src="https://graph.facebook.com/<?php echo $miAventura->__get('_cliente')->__get('_idFacebook'); ?>/picture" width="50" height="50">
                        <?php } else if ($tipo2 == 'C') { //90 90                       ?>
                            <img src="<?php echo _url_files_users_ . $miAventura->__get('_cliente')->__get('_foto'); ?>" width="50" />
                        <?php } ?>
                        </div>
                        <div id="nombre">
                            <?php echo $miAventura->__get('_cliente')->__get('_nombre')."  ". $miAventura->__get('_cliente')->__get('_apellidos'); ?></br>
                            <a href="<?php echo $url ?>">Ver todas sus Aventuras ></a></br>
                        </div>
                    </div>
                    
                    <?php  
                    $idAgencia = (int) $miAventura->__get('_id_agencia');
                    if ($idAgencia > 0) {
                        $linkAgencia = 'agencias-de-' 
                            . url_friendly($miAventura->__get('_deporte')->__get('_nombre_deporte'), 1)
                            . '/'
                            . url_friendly($miAventura->__get('_agencia')->__get('_nombre_agencia'), 1);
                    ?>
                        <div id="bottom_info">
                            Agencia : <a href="<?php echo $linkAgencia ?>"><?php echo $miAventura->__get('_agencia')->__get('_nombre_agencia') ?></a>
                        </div>
                    <?php } ?>
                </section>
                
                <!-- BOTONES SOCIALES -->
                <section class="aventura-info" uid-av="18<?php echo $_GET["aventura"] ?>">
                    <ul class="info_social">
                            <li class="photo"><?php echo $miAventura->__get('_cant_images'); ?></li>
<!--                            <li class="coment"><?php echo $miAventura->__get('_cant_coments_aventura'); ?></li>
                            <li class="like"><?php echo $miAventura->__get('_cant_likes_aventura'); ?></li>-->
                            <?php if ($this->_cuenta->__get("_cliente")->__get("_logeado") != 1) { ?>       
                                <li class="add_favoritos" title="Agregar a Favoritos" onclick="javascript: alert('Debe logearse primero, para poder agregar esta aventura asus favoritos');
                                    return false;"></li>
                                    <?php
                                } else {
                                    if ($favoritos->verificar_favoritos($_GET["aventura"], $this->_cuenta->__get("_cliente")->__get("_id")) == 1) {
                                        ?>
                                    <li class="add_favoritos active" title="Agregar a Favoritos"></li>
                                <?php } else { ?>
                                    <li class="add_favoritos" title="Agregar a Favoritos" onclick="javascript: agregarfavoritos(<?php echo $_GET["aventura"] ?>);
                                        return false;"></li> <?php
                                    }
                                } ?>
                        </ul>
                     <div class="socials">
                        <ul>
                            <li><div id="fb-root"></div><div class="fb-like" data-href="<?php echo dameURL() ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></li>
                            <li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo dameURL() ?>" data-text="Visita este sitio" data-lang="es">Twittear</a>
                                <script>!function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, "script", "twitter-wjs");</script>
                            </li>
                            <li><a class="pinterest" data-pin-config="beside" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fdeaventura.pe%2F&media=&description="><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></li>
                            <li><div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo dameURL() ?>"></div></li>
                            <li><a id="email_bt" href="mailto:"></a>   </li>
                        </ul>
                    </div>
                </section>
            </section>
            <div id="ubigep_persona">
                <h2>Lugar: <?php echo $miAventura->__get('_lugar_aventura'); ?></h2>
                <div id="map_ubigeo" style="height:322px"></div>

                <script type="text/javascript">
                    /*MAP DESCRIPCION*/
                    var myLatlng = new google.maps.LatLng(<?php echo $miAventura->__get('_lat_aventura'); ?>,<?php echo $miAventura->__get('_lng_aventura'); ?>);
                    var myOptions1 = {
                        zoom: 12,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP //Tipo de Mapa
                    };
                    var map1 = new google.maps.Map(document.getElementById("map_ubigeo"), myOptions1);

                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: map1,
                        icon: new google.maps.MarkerImage('<?php echo _catalogo_ . $miAventura->__get('_deporte')->__get('_icon_map'); ?>'),
                        animation: google.maps.Animation.DROP
                    });
                </script>
            </div>

            <div id="face_coments">
                <div class="fb-comments" data-href="<?php echo dameURL() ?>" data-num-posts="8" data-width="670"></div>
            </div>
            
        </section>  
        <section id="detalle-aventura-right">
            <?php  
            $aventuras_left = Aventuras::getAventurasMenosUno($miAventura->__get('_id_aventura'),"0,10");
            $total_aventuras_left = count($aventuras_left);
            $obj_aventuras = new Aventuras();
            $obj_clientes = new Clientes();
            ?>
            <!--      Aventuras -->
            <div id="list_aventuras"> 
                <ul><?php
                    for($i = 0; $i < $total_aventuras_left; $i++){
                       $url_aventura = $obj_aventuras->url_Aventura($aventuras_left[$i]["nombre_deporte"], $aventuras_left[$i]["id_aventura"], $aventuras_left[$i]["titulo_aventura"]);     ?>
                        <li>
                            <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/aventuras_img_usuarios/<?php echo $aventuras_left[$i]["imagen_aventura"]; ?>&h=80&w=90&zc=1"/>
                            <article>
                                <a href="<?php echo  $url_aventura; ?>"> <?php echo $aventuras_left[$i]["titulo_aventura"] ?> </a>
                                <span><?php echo $aventuras_left[$i]["fecha_aventura"] ?></span> </br>
                                <span>Por <a href="<?php echo $obj_clientes->getURL($aventuras_left[$i]["id_cliente"], $aventuras_left[$i]["nombre_cliente"])  ?>"><?php echo $aventuras_left[$i]["nombre_cliente"] ?></a></span></br>
                                <span> <?php echo $aventuras_left[$i]["cantidad_visitas"] ?> Visitas</span>   
                            </article> 
                        </li><?php
                    }
                ?> 
                </ul>
                <aside id="publicidad-right">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- anumciamas-250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:250px;height:250px"
                         data-ad-client="ca-pub-9292585337121975"
                         data-ad-slot="5760024414"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </aside>
            </div>
        </section>
        

        
        <div class="clear"></div>
        <?php
    }

    public function sitios_web() {
        $this->top_deportes();
        $sitios = new Deporte($_GET['cat']);
        $sitios->getSitiosWeb();
        ?>
        <div class="clear"></div>
        <?php $this->right_deportes($sitios->__get('_descripcion')); ?>
        <div id="panel_left">
            <br>
            <?php
            $array2 = $sitios->__get('_sitios_web');

            if (count($array2) > 0) {

                foreach ($array2 as $indice) {
                    $ms = '<div class="panel_desc2 evento_desc">';
                    $file = _url_sitios_web_img_ . trim($indice["imagen_sitio"]);

                    $ext = "";
                    if (file_exists($file)) {
                        $arr = getimagesize($file);
                        if ($arr[0] > 180)
                            $ext = 'width="180"';
                        else if ($arr[1] > 110)
                            $ext = 'height="110"';
                    }

                    $ms.= '<div class="foto_panel_desc2"><div><img src="' . $file . '" ' . $ext . ' onerror="this.src=\'aplication/webroot/imgs/img_no.png\';"/></div></div>';

                    $ms.= '<div class="panel_left">';
                    $ms.= '<h2><a href="' . $indice['url_sitio_web'] . '" title="Click aqui" target="_blank">' . ucwords($indice['titulo_sitio_web']) . '</a></h2>';
                    $ms.= '<b>Website:</b> <a href="' . $indice['url_sitio_web'] . '" target="_blank">' . $indice['url_sitio_web'] . '</a></p>';
                    $ms.= '<p>' . $indice['descripcion_sitio_web'] . '</p>';
                    $ms.= '</div>';

                    $ms.= '<div class="clear"></div>';
                    $ms.= '</div>';
                    echo $ms;
                }
            } else {
                echo '<br/><div align="center">No se encotro sitios web para este deporte.</div>';
            }
            ?>

        </div>

        <div class="clear"></div>
        <?php
    }
    public function eventosAgenda() { 
        $this->top_deportes();
        $obj_eventos = new Eventos();
        $eventos = $obj_eventos->getEventos(TRUE);
        $total_eventos=count($eventos);           
            for ($i=0;$i<$total_eventos;$i++) {    ?>   
            <div class="flipwrapper">
                <article class="pnl" id="av<?php echo $eventos[$i]["id_evento"] ?>">
                    <div class="front_evento">
                        <a href="eventos-de-<?php echo strtolower($eventos[$i]['nombre_deporte'])?>/<?php echo url_friendly(str_replace("Ñ","ñ",$eventos[$i]['titulo_evento']), 1) ?>">
                            <img src="aplication/utilities/timthumb.php?src=<?php echo  _url_evento_img_ .$eventos[$i]["imagen_evento"] ?>&h=275&w=275&zc=1"/>
                            <div class="titulo_panel_evento"> <a title="Ver detalle de la aventura" href="eventos-de-<?php echo strtolower($eventos[$i]['nombre_deporte'])?>/<?php echo url_friendly(str_replace("Ñ","ñ",$eventos[$i]['titulo_evento']), 1) ?>"><?php echo $eventos[$i]["titulo_evento"] ?></a></div>
                            <div class="fecha_panel_evento">  <?php echo substr($eventos[$i]["fecha_evento"],8,2); ?> - <?php echo Month($eventos[$i]["fecha_evento"]) ?></div>
                            <div class="lugar_panel_evento"><?php echo $eventos[$i]["lugar_evento"] ?></div>
                            <div class="lugar_panel_evento"><?php echo $eventos[$i]["nombre_agencia"] ?></div>
<!--                            <div class="btn_registro_panel_evento"><a href="eventos-de-<?php echo strtolower($eventos[$i]['nombre_deporte'])?>/<?php echo url_friendly(str_replace("Ñ","ñ",$eventos[$i]['titulo_evento']), 1) ?>?registro=1"><img src="aplication/webroot/imgs/btn_registrarme.jpg"></a></div>-->
                        </a>
                   </div>
                </article>
             </div>
                <?php
            }
            ?>
        <div class="clear"></div>
        <?php
    } 
    
    public function aventurasComunidad() { 
        $this->top_deportes();
        $obj_aventuras = new Aventuras();
        $pagina_actual = 1;
        $inicio = 0;
        $items = 32;
        if($_GET["pag"] && $_GET["pag"] > $pagina_actual){ 
            $pagina_actual = $_GET["pag"];
            $inicio = (($items * ($pagina_actual - 1)) + 1); 
        }
        $limite = $inicio.",".$items;
        
        $aventuras = $obj_aventuras->getAventurasUltimas($limite);  
        $total_aventuras=count($aventuras);           
            for ($i=0; $i < $total_aventuras; $i++) { 
                $nfecha = explode("-", $aventuras[$i]['fecha_aventura']);
                $file = _url_avfiles_users_ . $aventuras[$i]["nombre_aventuras_archivos"];
                $url_aventura = 'http://www.deaventura.pe/3b1' . $aventuras[$i]["id_aventura"] . '0b/aventura-de-' . url_friendly($aventuras[$i]["nombre_deporte"], 1) . '/' . url_friendly($aventuras[$i]["titulo_aventura"], 1);
                //$url_aventura = $this->url_Aventura($rowp["nombre_deporte"], $rowp["id_aventura"], $rowp['titulo_aventura']); ?>   
            <div class="flipwrapper">
                <article class="pnl" id="av<?php echo $eventos[$i]["id_aventura"] ?>">
                    <div class="front_evento">
                        <a href="<?php echo $url_aventura ?>">
                            <img src="aplication/utilities/timthumb.php?src=<?php echo $file ?>&h=275&w=275&zc=1"/>
                            <div class="fecha_panel_evento">  <span><?php echo substr($aventuras[$i]["fecha_aventura"],8,2); ?></span> <?php echo Month($aventuras[$i]["fecha_aventura"]) ?></div>
                            <div class="titulo_panel_evento"> <a title="Ver detalle de la aventura" href="<?php echo $url_aventura ?>"><?php echo $aventuras[$i]["titulo_aventura"] ?></a></div>
                            <div class="lugar_panel_evento"><?php echo "<b>".$aventuras[$i]['nombre_cliente']."</b> en ".$aventuras[$i]["lugar_aventura"] ?></div>    
                            <div class="social_panel_evento">
<!--                        <a class="twitter-share-button" href="http://twitter.com/share" data-count="horizontal" data-url="<?php echo $url_aventura ?>" data-counturl="<?php echo $url_aventura ?>" data-text="<?php echo $aventuras[$i]["titulo_aventura"] ?>" data-via="de aventura" data-lang="es">Tweet</a>-->
                            <div class="g-plusone" data-size="medium" data-href="<?php echo $url_aventura ?>" data-callback="gplusClickHandler" ></div>
                            <div href="<?php echo $url_aventura ?>" class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false"></div>
                          </div> 
                        </a>
                   </div>
                </article>
             </div>
            
                <?php
            }
            
            ?>
         <div class="clear"></div>    
        <?php
            $total_aventuras = $obj_aventuras->getAventurasTotal();
            $enlace = "aventuras/";
            echo paginar_aventuras($pagina_actual, $total_aventuras, $items, $enlace);
    }
    
    public function inspiraciones() {
        //echo 'hola mundo';
        $this->top_deportes();
        $obj_inspiraciones = new Inspiraciones();
        $inspiraciones = $obj_inspiraciones->getInspiraciones(TRUE); ?>
            <br>  <?php
            $total_eventos=count($inspiraciones);           
            for ($i=0;$i<$total_eventos;$i++){ ?> 
                <div class="pnl panel_inspiracion" id="av<?php echo $inspiraciones[$i]["id_inspiracion"] ?>">
                    <a title="Ver detalle de inspiración" href="inspiraciones-de-<?php echo strtolower($inspiraciones[$i]['deporte']->__get("_nombre_deporte"))?>/<?php echo url_friendly($inspiraciones[$i]['url'], 1) ?>">
                        <img src="aplication/utilities/timthumb.php?src=<?php echo _url_inspiracion_img_.$inspiraciones[$i]["imagen"] ?>&h=290&w=275&zc=1"/>
                    </a>
                    <div class="lugar_panel_evento"><?php echo $inspiraciones[$i]["insight"] ?></div>
               </div>
                <?php
            } ?>
        <div class="clear"></div>
        <?php
    }
    
        public function articulos() {
        //echo 'hola mundo';
        $this->top_deportes();
        $obj_articulos = new Articulos();
        $articulos = $obj_articulos->getArticulos(TRUE); ?>
            <br>  <?php
            $total_eventos=count($articulos);           
            for ($i=0;$i<$total_eventos;$i++){ ?> 
                <div class="pnl panel_inspiracion" id="av<?php echo $articulos[$i]["id_inspiracion"] ?>">
                    <a title="Ver detalle de articulo" href="articulos-de-<?php echo strtolower($articulos[$i]['deporte']->__get("_nombre_deporte"))?>/<?php echo url_friendly($articulos[$i]['url'], 1) ?>">
                        <img src="aplication/utilities/timthumb.php?src=<?php echo _url_articulo_img_.$articulos[$i]["imagen"] ?>&h=290&w=275&zc=1"/>
                    </a>
                    <div class="lugar_panel_evento"><?php echo $articulos[$i]["nombre"] ?></div>
               </div>
                <?php
            } ?>
        <div class="clear"></div>
        <?php
    }
    
    public function mapa_web() {
        $this->top_deportes();
        ?>
        <div class="clear"></div><br/>
        <div id="mapa_sitio">
            <?php
            $deportes = new Deportes();
            $arr = $deportes->getDeportes();

            $sec_sitios = new Secciones_sitio();
            $arr2 = $sec_sitios->get_secciones_sitio();
            ?>
            <ul>
                <?php
                foreach ($arr as $value) {
                    ?>
                    <li><a href="<?php echo url_friendly($value["nombre_deporte"], 1) ?>"><?php echo $value["nombre_deporte"] ?></a>
                        <ul>
                            <li><h4>Secciones:</h4>
                                <ul>
                                    <?php
                                    foreach ($arr2 as $value2) {
                                        ?>
                                        <li><a href="<?php echo url_friendly($value2["nombre_seccion_sitio"], 1) . '-de-' . url_friendly($value["nombre_deporte"], 1) ?>"><?php echo $value2["nombre_seccion_sitio"] . ' de ' . $value["nombre_deporte"] ?></a>

                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li><a href="<?php echo 'sitios-web-sobre-' . url_friendly($value["nombre_deporte"], 1) ?>"><?php echo 'Sitios web sobre de ' . $value["nombre_deporte"] ?></a></li>
                                </ul>
                            </li>
                            <?php
                            $xdeporte = new Deporte($value["id_deporte"]);
                            $arrMod = $xdeporte->__get("_modalidades");

                            if (count($arrMod) > 0) {
                                ?>
                                <li><h4>Modalidades:</h4>
                                    <ul>
                                        <?php
                                        for ($i = 0; $i < count($arrMod); $i++) {
                                            ?>
                                            <li><a href="<?php echo url_friendly($value["nombre_deporte"], 1) . '/' . url_friendly($arrMod[$i]['nombre_modalidad'], 1) ?>"><?php echo $arrMod[$i]['nombre_modalidad'] ?></a></li>
                                            <?php
                                        }

                                        /* foreach ($arrMod as $value3) {
                                          ?>
                                          <li><a href="<?php echo url_friendly($value["nombre_deporte"], 1) . '/' . url_friendly($value3["nombre_modalidad"], 1) ?>"><?php echo $value3["nombre_modalidad"] ?></a></li>
                                          <?php
                                          } */
                                        ?>
                                    </ul> 
                                </li>
                            <?php } ?>

                        </ul> 
                    </li>
                    <?php
                }
                ?>
            </ul>      
        </div>
        <?php
    }

    public function busqueda() {

        $busqueda = new Busqueda();
        $data = $busqueda->buscar($_GET["q"]);
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
        ?>
        <div id="panel_left" class="busqueda">
            <div class="titulo"><span>Resultados de búqueda:</span><h1><?php echo ucfirst(str_replace("-", " ", $_GET["q"])) ?></h1></div>
            <div class="clear"></div>
            <ul id="nav_search">
                <?php
                //Aqui son los la lista de nombre (submenu) //Tags(0)   Rutas(1)
                for ($i = 0; $i < count($data); $i++) {
                    if (!empty($data[$i]) || $data[$i] != "") {
                        if ($data[$i][1] != 0) {
                            ?>
                            <li rel="#list_<?php echo $data[$i][0] ?>"><?php echo ucwords($data[$i][0]) . ' (' . $data[$i][1] . ')' ?></li>
                            <?php
                        }
                    }
                }
                ?>
            </ul>

            <div id="search_results_content">
                <?php
                if (!empty($data)) {
                    for ($i = 0; $i < count($data); $i++) {
                        if (!empty($data[$i]) || $data[$i] != "") {
                            if ($data[$i][1] != 0) {
                                ?>
                                <div class="search_results" id="list_<?php echo $data[$i][0] ?>">
                                    <ul>
                                        <?php
                                        $cc = 0;
                                        foreach ($data[$i] as $value) {
                                            if ($cc > 1) {
                                                //rutas-de-canotaje/canotaje-en-lunahuana
                                                if ($data[$i][0] == "Tags") {
                                                    $data_ur = $busqueda->generadorUrl($value['tipo_seccion'], $value["nombre_deporte"], $value["nombre_tipo"]);
                                                    $titulo = $data_ur[0];
                                                    $url = $data_ur[1];
                                                    $sitio = ucfirst($value['tipo_seccion']);
                                                } else {
                                                    $data_ur = $busqueda->generadorUrl($data[$i][0], $value["nombre_deporte"], $value["nombre_tipo"]);
                                                    $titulo = $data_ur[0];
                                                    $url = $data_ur[1];
                                                    $sitio = $data[$i][0];
                                                }
                                                ?>
                                                <li>
                                                    <div class="image">
                                                        <a href="<?php echo $url ?>">
                                                            <?php
                                                            if ($value["image"] != "") {
                                                                $csitio = strtolower($sitio);
                                                                //Aqui voy a saber la ruta de la imagen segun la seccion
                                                                if ($csitio == "agencias") {
                                                                    $arr = img_resize(_url_agencia_img_, $value["image"]);
                                                                } else if ($csitio == "clubes") { //u organizaciones
                                                                    $arr = img_resize(_url_organizacion_img_, $value["image"]);
                                                                } else if ($csitio == "tiendas") {
                                                                    $arr = img_resize(_url_proveedor_img_, $value["image"]);
                                                                } else if ($csitio == "rutas") {
                                                                    $arr = img_resize(_url_rutas_img_, $value["image"]);
                                                                } else if ($csitio == "eventos") {
                                                                    $arr = img_resize(_url_eventos_img_, $value["image"]);
                                                                }
                                                                ?>
                                                                <img alt="<?php echo $value["nombre_tipo"] ?>" src="<?php echo $arr[0] ?>" <?php echo $arr[1] ?> onerror="this.src='aplication/webroot/imgs/img_no.png'"/>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <img src="aplication/webroot/imgs/img_no.png"/>
                                                                <?php
                                                            }
                                                            ?>

                                                        </a>
                                                    </div>
                                                    <div class="detail">
                                                        <h3><span id="sec_2" class="minititulo"><?php echo $sitio ?></span><a href="<?php echo $url ?>"><?php echo $titulo ?></a></h3>
                                                        <p><?php echo ellipsisP($value["descripcion_tipo"], 40) ?></p>
                                                        <a href="<?php echo $url ?>">Ver contenido ></a>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            $cc++;
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <?php
                            }
                        }
                    }
                } else {
                    echo 'No se encontró datos.';
                }
                ?>
            </div>
            <div class="clear"></div>

            <?php ?>
        </div>
        <div id="panel_right">
            <?php $this->right_principal(); ?>
        </div>
        <div class="clear"></div>
        <?php
    }
    
    public function contactanos() {
        $this->top_deportes();
        $id_pagina = 7; // es el ID en la tabla paginas
        $obj_pagina = new Pagina($id_pagina);
        ?>
        <div class="clear"></div><br/>
        <div id="mapa_sitio"> 
            <section>
                <article><p><?php echo $obj_pagina->__get("_descripcion") ?></p></article>
            </section>      
        </div>
        <?php
    }



    public function nosotros() {
        $this->top_deportes();
        $id_pagina = 4; // es el ID en la tabla paginas
        $obj_pagina = new Pagina($id_pagina);
        ?>
        <div class="clear"></div><br/>
        <div id="mapa_sitio"> 
            <section>
                <article><p><?php echo $obj_pagina->__get("_descripcion") ?></p> </article>
            </section>      
        </div>
        <?php
    }
    
    public function terminos() {
        $this->top_deportes();
        $id_pagina = 9; // es el ID en la tabla paginas
        $obj_pagina = new Pagina($id_pagina);
        ?>
        <div class="clear"></div><br/>
        <div id="mapa_sitio"> 
            <section>
                <article><p><?php echo $obj_pagina->__get("_descripcion") ?></p></article>
            </section>      
        </div>
        <?php
    }


    
    public function descripcion_inspiracion() {
       // $this->top_deportes();
        $obj_inspiracion = new Inspiracion($_GET['id_inspiracion']);  ?>
        
        <section id="detalle-aventura-left">
            
            <div id="showcase" class="showcase" style="width:100%">
                <img src="<?php echo _url_inspiracion_img_.$obj_inspiracion->__get('_imagen'); ?>" width="700" >
            </div>
            <section id="info"  > 
                <h1 id="info_titulo"><?php echo $obj_inspiracion->__get("_insight") ?></h1>
            </section>
            <section id="descp_persona">
                <!-- BOTONES SOCIALES -->
                <section class="aventura-info" uid-av="18<?php echo $_GET["aventura"] ?>">
                     <div class="socials">
                        <ul>
                            <li><div id="fb-root"></div><div class="fb-like" data-href="<?php echo dameURL() ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></li>
                            <li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo dameURL() ?>" data-text="Visita este sitio" data-lang="es">Twittear</a>
                                <script>!function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, "script", "twitter-wjs");</script>
                            </li>
                            <li><a class="pinterest" data-pin-config="beside" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fdeaventura.pe%2F&media=&description="><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></li>
                            <li><div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo dameURL() ?>"></div></li>
                            <li><a id="email_bt" href="mailto:"></a>   </li>
                        </ul>
                    </div>
                </section>
            </section>

            <div id="face_coments">
                <div class="fb-comments" data-href="<?php echo dameURL() ?>" data-num-posts="2" data-width="670"></div>
            </div>
            
        </section>  
        <section id="detalle-aventura-right">
            <?php  
            $obj_inspiraciones = new Inspiraciones();
            $inspiraciones = $obj_inspiraciones->getInspiraciones();
            $total_inspiraciones = count($inspiraciones);
            ?>
            <!--      Aventuras -->
            <div id="list_aventuras"> 
                <ul><?php
                for($i = 0; $i < 10; $i++){     ?>
                    <li>
                        <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/image_inspiraciones/<?php echo $inspiraciones[$i]["imagen"]; ?>&h=80&w=90&zc=1"/>
                        <article>
                            <a href="inspiraciones-de-<?php echo strtolower($inspiraciones[$i]['deporte']->__get("_nombre_deporte"))?>/<?php echo url_friendly($inspiraciones[$i]['url'], 1); ?>"> <?php echo $inspiraciones[$i]["insight"] ?> </a>
                        </article> 
                    </li><?php
                }
                ?> 
                </ul>
                <aside id="publicidad-right">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- anumciamas-250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:250px;height:250px"
                         data-ad-client="ca-pub-9292585337121975"
                         data-ad-slot="5760024414"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </aside>
            </div>
        </section>
        

        
        <div class="clear"></div> <?php
        
        
        
    }
    
     
    public function descripcion_articulo() {
       // $this->top_deportes();
        $obj_articulo = new Articulo($_GET['id_articulo']);  ?>
        
        <section id="detalle-aventura-left">
            
            <div id="showcase" class="showcase" style="width:100%">
                <img src="<?php echo _url_articulo_img_.$obj_articulo->__get('_imagen'); ?>" width="700" >
            </div>
            <section id="info"  > 
                <h1 id="info_titulo"><?php echo $obj_articulo->__get("_nombre") ?></h1>
            </section>
            <section id="descp_persona">
                <div id="content_descp"> 
                    <p><?php echo nl2br($obj_articulo->__get('_descripcion')); ?></p> </br> 
                </div>
                <!-- BOTONES SOCIALES -->
                <section class="aventura-info" uid-av="18<?php echo $_GET["aventura"] ?>">
                     <div class="socials">
                        <ul>
                            <li><div id="fb-root"></div><div class="fb-like" data-href="<?php echo dameURL() ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></li>
                            <li><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo dameURL() ?>" data-text="Visita este sitio" data-lang="es">Twittear</a>
                                <script>!function(d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (!d.getElementById(id)) {
                                js = d.createElement(s);
                                js.id = id;
                                js.src = "//platform.twitter.com/widgets.js";
                                fjs.parentNode.insertBefore(js, fjs);
                            }
                        }(document, "script", "twitter-wjs");</script>
                            </li>
                            <li><a class="pinterest" data-pin-config="beside" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url=http%3A%2F%2Fdeaventura.pe%2F&media=&description="><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></li>
                            <li><div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo dameURL() ?>"></div></li>
                            <li><a id="email_bt" href="mailto:"></a>   </li>
                        </ul>
                    </div>
                </section>
            </section>

            <div id="face_coments">
                <div class="fb-comments" data-href="<?php echo dameURL() ?>" data-num-posts="2" data-width="670"></div>
            </div>
            
        </section>  
        <section id="detalle-aventura-right">
            <?php  
            $obj_articulos = new Articulos();
            $articulos = $obj_articulos->getArticulos();
            $total_articulos = count($articulos);
            ?>
            <!--      Aventuras -->
            <div id="list_aventuras"> 
                <ul><?php
                for($i = 0; $i < 10; $i++){     ?>
                    <li>
                        <img src="aplication/utilities/timthumb.php?src=aplication/webroot/imgs/catalogo/image_articulos/<?php echo $articulos[$i]["imagen"]; ?>&h=80&w=90&zc=1"/>
                        <article>
                            <a href="articulos-de-<?php echo strtolower($articulos[$i]['deporte']->__get("_nombre_deporte"))?>/<?php echo url_friendly($articulos[$i]['url'], 1); ?>"> <?php echo $articulos[$i]["nombre"] ?> </a>
                        </article> 
                    </li><?php
                }
                ?> 
                </ul>
                <aside id="publicidad-right">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- anumciamas-250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:250px;height:250px"
                         data-ad-client="ca-pub-9292585337121975"
                         data-ad-slot="5760024414"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </aside>
            </div>
        </section>
        

        
        <div class="clear"></div> <?php
        
        
        
    }

    public function __get($atributo){
        
        return $this->$atributo;
        
    }
}
?>