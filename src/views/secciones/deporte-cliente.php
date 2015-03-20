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
            <h2 class="margin-bottom-5">Mis Datos:</h2>
            <ul class="mis-datos">
                <li><span class="ico-profile ico-profile-location"></span>Vivo en Huaraz, Ancash.</li>
                <li><span class="ico-profile ico-profile-star"></span>Practico Deportes de Aventuras desde el 2001.</li>
                <li><span class="ico-profile ico-profile-compass"></span>Parte del DeAventura desde Mayo 2014.</li>
            </ul>

            <h3>Deportes Favoritos:</h3>

            <div class="btn btn-sport-yellow"><span class="ico-sport ico-sport-1"></span>ESACALADA</div>
            <div class="btn btn-sport-yellow"><span class="ico-sport ico-sport-1"></span>TREKKING</div>
            <div class="btn btn-sport-yellow"><span class="ico-sport ico-sport-1"></span>CICLISMO</div>



            <h3>Equipo que utilizo:</h3>

            <div class="btn btn-sport-blue-small">Equipo de Escalada</div>
            <div class="btn btn-sport-blue-small">Equipo de montaña</div>

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
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places" type="text/javascript" ></script>
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
                        ['Cajamarquilla','San Juan de Lurigancho, Lima',-11.969529,-76.986694,27],['Canchacalla','Canchacalla, Lima',-11.916810,-76.533463,35],['Canchaque','Canchaque, Piura',-5.376133,-79.608261,133],['Caral','Lima, Perú',-10.887985,-77.532410,29],['Chinchero','Cuzco, Perú',-13.391028,-72.051140,33],['Cordillera Blanca','Huaraz y Huari, Áncash, Perú',-9.159677,-77.573433,134],['Lomas de Atiquipa','Caravelí, Arequipa',-15.660102,-73.690781,130],['Lunahuana','Lunahuana, Lima, Perú',-13.016957,-76.127426,22],['Machu Picchu','Cuzco, Perú',-13.169154,-72.542915,34],['Pachacamac','Lima, Perú',-12.188703,-76.850166,30],['Platanales de Totoritas','Mala, Cañete, Perú',-12.685083,-76.656227,132],['Santo Domingo de los Olleros','Santo Domingo de los Olleros, Lima',-12.217665,-76.514732,131],['Tingo María','Huanuco, Perú',-9.305021,-76.005035,28]                            ];



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
                            var image = new google.maps.MarkerImage('http://www.deaventura.pe/aplication/webroot/imgs/catalogo/thumb_1353683228icon-ciclismo.png');
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
                                var html = '<b>' + arr[0] + '</b><br/>' + arr[1] + '<br/><a href="rutas-de-ciclismo/ciclismo-en-' + arr[0].toLowerCase().replace(/ /g, '-') + '" class="minititulo">Ver detalle</a>';
                                bindInfoWindow(marker, map, infoWindow, html);
                            }
                        }

        </script>

    </div>


            
            
    </div>
        
        
    <div class="row_ profile">aasas
        <h2> Aventuras de <?php echo $cliente->__get("_nombre").' '.$cliente->__get("_apellidos") ?></h2>
    </div>
        

        
        
        

        
        <!--
        <section id="seccion-right">

            <div id="cuerpo_top">
                <div id="foto" style="margin-left:10px;">
                    <?php if ($tipo2 == 'F') { ?>
                        <img src="https://graph.facebook.com/<?php echo $cliente->__get('_idFacebook'); ?>/picture?width=100&height=100" width="100" height="100">
                    <?php } else if ($tipo2 == 'C') { //90 90                 ?>
                        <img src="<?php echo _url_files_users_ . $cliente->__get('_foto'); ?>" width="100" />
                    <?php } ?>
                </div>
                <h1><?php echo $cliente->__get("_nombre").' '.$cliente->__get("_apellidos") ?></h1>
            </div>
            
            <?php $aventuras->listAventuras($_GET['cliente'], "cliente"); ?>
        </section>-->