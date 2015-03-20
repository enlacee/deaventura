<?php

class Aventuras {

    private $_msgbox;
    private $_cuenta;

    public function __construct(Msgbox $msg = NULL, Cuenta $cuenta = NULL) {
        $this->_msgbox = $msg;
        $this->_cuenta = $cuenta;
    }

    public function addAventura() { 

        if (!isset($_SESSION["miaventura"]['idModalidad']) && $_POST['lat_pos'] != "" && $_POST['lng_pos'] != "") {
            location("cuenta.php?cuenta=compartir");
        }
        $sql = "INSERT INTO aventuras(id_cliente,
                                      id_modalidad,
                                      id_agencia,
                                      titulo_aventura,
                                      lugar_aventura,
                                      descripcion_aventura,
                                      lat_aventura,
                                      lng_aventura,
                                      fecha_creacion_aventura,
                                      cant_visitas_aventura) 
                VALUES (
                   '" . $this->_cuenta->__get("_cliente")->__get("_id") . "',
                   '" . $_SESSION["miaventura"]['idModalidad'] . "',
                   '" . $_SESSION["miaventura"]['idAgencia'] . "',
                   '" . addslashes(trim($_SESSION["miaventura"]['titulo'])) . "',
                   '" . addslashes($_SESSION["miaventura"]['lugar']) . "',
                   '" . addslashes($_SESSION["miaventura"]['descripcion']) . "',
                   '" . $_POST['lat_pos'] . "',
                   '" . $_POST['lng_pos'] . "',
                   '" . date('Y-m-d') . "',
                   '137')";



        $query = new Consulta($sql);
        $id = $query->nuevoId();
        $_SESSION["miaventura"]["id_aventura"] = $id;

        $array_arch = $_SESSION["miaventura"]['archivos'];
        $count = 0;
        $img_toface = "";
        foreach ($array_arch as $valor) {
            if ($count == 0)
                $img_toface = $valor[0];
                
                $nombre_imagen_solo_nuevo = $id."_".$count."_".time().".jpg";
                $nombre_imagen_actual = _host_avfiles_users_.$valor[0];
                $nombre_imagen_nuevo = _host_avfiles_users_.$nombre_imagen_solo_nuevo;
                
                $nombre_imagen_actual_thumbnail = _host_avfiles_users_."thumbnail/".$valor[0];
                $nombre_imagen_nuevo_thumbnail = _host_avfiles_users_."thumbnail/".$nombre_imagen_solo_nuevo;

                rename($nombre_imagen_actual, $nombre_imagen_nuevo);
                rename($nombre_imagen_actual_thumbnail, $nombre_imagen_nuevo_thumbnail);
				//$img_up_face = $nombre_imagen_solo_nuevo;

                $sql2 = "INSERT INTO aventuras_archivos(id_aventura,
                                          orden_aventuras_archivos,
                                          nombre_aventuras_archivos,
                                          titulo_aventuras_archivo,
                                          comentario_aventuras_archivo,
                                          tipo_aventuras_archivo) 
                    VALUES (
                       '" . $id . "',
                       '" . $count . "',                   
                       '" . $nombre_imagen_solo_nuevo . "',
                       '" . $valor[1] . "',
                       '" . $valor[2] . "',
                       '" . $valor[3] . "')";

            $query1 = new Consulta($sql2);
            $count++;
        }

        //Agrego para el registro de actividades
        $sql3 = "INSERT INTO registro_actividad(id_aventura,
                                      id_cliente,
                                      estado_registro_actividad,
                                      fecha_registro_actividad )
                VALUES (
                   '" . $id . "',
                   '" . $this->_cuenta->__get("_cliente")->__get("_id") . "',                   
                   'aventura',
                   NOW())";
        $query2 = new Consulta($sql3);

        $modalidad = new Modalidad($_SESSION["miaventura"]['idModalidad']);
        /* Publicar en su muro de Facebook */
        $idFace = $this->_cuenta->__get('_facebook')->getUser();
        $access_token = $this->_cuenta->__get('_facebook')->getAccessToken();
        $this->_cuenta->__get('_facebook')->api("/" . $idFace . "/feed", "post", array(
            "message" => "",
            "link" => _url_ . $this->url_Aventura($modalidad->__get('_deporte')->__get('_nombre_deporte'), $id, $_SESSION["miaventura"]['titulo']),
            "picture" => _url_ . _url_avfiles_users_ . $nombre_imagen_solo_nuevo,
			//"picture" => _host_avfiles_users_.'254_0_1419982623.jpg',
            "name" => $_SESSION["miaventura"]['titulo'],
            "description" => "Publiqué mi aventura en Deaventura.pe",
            "access_token" => $access_token));

        $_SESSION["step3"] = 'step3';


        //location("cuenta.php?cuenta=step4");
    }

    public function editAventura() {
        /*
          echo '<pre>';
          print_r($_SESSION['files_act']);
          echo '</pre>';
         */
        $aventura = new Aventura($_GET["idAventura"]);
        if (count($aventura) == 1) {
            //location('http://www.deaventura.pe/cuenta.php?cuenta=misAventuras');
        }
        /*
          echo '<pre>';
          print_r($aventura);
          echo '</pre>';
         */
        $array_files = $aventura->__get('_archivos');
        $modalidad = new Modalidad($aventura->__get("_id_modalidad"));
        $url_aventura = _url_ . $this->url_Aventura($modalidad->__get("_deporte")->__get("_nombre_deporte"), $aventura->__get("_id_aventura"), $aventura->__get('_titulo_aventura'));
        ?>
        <div id="steps">
            <div id="titu_step"><span class="glyphicon glyphicon-picture"></span> Mis Aventuras <a class="btn btn_nuevo" href="cuenta.php?cuenta=misAventuras" title="Nueva aventura">Regresar</a></div>
            <div id="panel_step">
                
                <div class="aventura_panel modify">
                    <div class="pnl1"><img src="aplication/utilities/timthumb.php?src=<?php echo _url_avfiles_users_ . $array_files[0]['nombre_aventuras_archivos'] ?>&h=100&w=100&zc=1"/></div>
                    <div class="pnl2">
                        <h1><?php echo $aventura->__get('_titulo_aventura') ?><span><?php echo fecha_long($aventura->__get('_fecha_creacion_aventura')) ?></span></h1>
                        <ul class="info_social">
                            <li class="photo"><?php echo $aventura->__get('_cant_images') ?></li>
                            <li class="coment"><?php echo $aventura->__get('_cant_coments_aventura') ?></li>
                            <li class="like"><?php echo $aventura->__get('_cant_likes_aventura') ?></li>
                        </ul><br/>
                        <a href="<?php echo $url_aventura ?>"><?php echo $url_aventura ?><img src="aplication/webroot/imgs/icon_mas.jpg" width="16" height="17"></a>
                    </div>
                    <div class="pnl3">
                        <a class="btn_style2" href="cuenta.php?cuenta=misAventuras">< Volver a mis aventuras</a>
                    </div>					
                </div>

                <div class="aventura_descInfo">
				<p style="font-family:'MuseoSans-500';font-size: 12px;"><span style="color: #2AB27B;">Sugerencia: </span><span style="color: black;">Una aventura debe tener al menos una foto si quiere mantenerlo activa.</span></p><br/>
                
                    <form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_update" id="form_update" onsubmit="return validate_updateAv(this, 'updateAventura')">
                        <input name="id_aventura" id="id_aventura" type="hidden" value="<?php echo $aventura->__get("_id_aventura") ?>">
                        <div class="rowElem"><label>Elegir el Deporte:</label>
                            <select name="cboDeportes" id="cboDeportes">
                                <option value="0">Elegir Deporte ...</option>
                            </select>
                            <script type="text/javascript">
                        $.post("ajax.php", {deportes: '1'}, function(data) {
                            if (data != "") {
                                $("#cboDeportes").append(data);
                                $("#cboDeportes").val(<?php echo $aventura->__get('_deporte')->__get('_id_deporte') ?>);
                            }
                        })
                            </script>
                        </div>
                        <div class="rowElem"><label>Elegir Modalidad:</label>
                            <select name="cbo_modalidad" id="cbo_modalidad">
                                <option value="0">Elegir modalidad ...</option>
                            </select>
                            <script type="text/javascript">
                                $.post("ajax.php", {idCbo:<?php echo $aventura->__get('_deporte')->__get('_id_deporte') ?>}, function(data) {
                                    if (data != "") {
                                        $("#cbo_modalidad").append(data);
                                        $("#cbo_modalidad").val(<?php echo $aventura->__get('_id_modalidad') ?>);
                                    }
                                })
                            </script>
                        </div>
                        <div class="rowElem"><label>Elegir Agencia (opcional):</label>
                            <select name="cbo_agencias" id="cbo_agencias">
                                <option value="0">Elegir Agencia ...</option>
                            </select>
                            <script type="text/javascript">
                        $.post("ajax.php", {agencias: '1'}, function(data) {
                            if (data != "") {
                                $("#cbo_agencias").append(data);
                                $("#cbo_agencias").val(<?php echo $aventura->__get('_id_agencia') ?>);
                            }
                        });
                            </script>
                        </div>                        
                        
                        <div class="rowElem"><label>Título:</label><input name="titulo" id="titulo" type="text" size="23" value="<?php echo $aventura->__get('_titulo_aventura') ?>"/></div>
                        <div class="rowElem"><label>Lugar, Provincia y Departamento:</label><input name="lugar" id="lugar" type="text" size="30" value="<?php echo $aventura->__get('_lugar_aventura') ?>"></div>
						<!--
                        <div class="rowElem"><label>Descripción de la aventura:</label><textarea name="descripcion" id="descripcion" rows="5" style="width: 467px;height: 178px;"><?php echo $aventura->__get('_descripcion_aventura') ?></textarea></div>
						-->
						<div style="display:table;width: 85%;"> 
                            <div style="float: left;">
                                <label style="width: 212px;">Descripción de la aventura:</label>
                            </div>
                            <div style="float: right;">
                                <textarea type="text" name="descripcion" id="descripcion" rows="5" style="width: 567px;height: 178px;"><?php echo $aventura->__get('_descripcion_aventura') ?></textarea>
                            </div>
                        </div>

                        <!-- PANEL -->
                        <div class="panel_info_aventura" style="border-top: 1px solid #DDD;padding-top: 13px;margin-top: 13px;">
                            <div class="rowElem">
                                <p>Busca y/o mueve el pin rojo al lugar donde realizaste tu aventura y presiona el botón Finalizar.<br/> 
                                    Puedes acercarte o alejarte usando los controles de la izquierda.</p>
                                <br>
                                <input type="text" name="origen" id="address" style="width: 400px" />
                                <br>
                                <div id="mi_ubic" style="width: 698px;height: 393px;margin:15px auto 0px 0;"></div>
                                <input type="hidden" id="lat_pos" name="lat_pos" value="<?php echo $aventura->__get('_lat_aventura') ?>">
                                <input type="hidden" id="lng_pos" name="lng_pos" value="<?php echo $aventura->__get('_lng_aventura') ?>">                            

                            </div>
                        </div>

                        <div class="panel_info_aventura" id="listado_archivos">
                            <ul id="list_item">
                                <?php
                                $arr = $aventura->__get('_archivos');
                                foreach ($arr as $valor) {
                                    ?>
                                    <li id="arch<?php echo $valor['id_aventuras_archivo'] ?>">
                                        <div class="panel_comparte">
                                            <div class="delete"><input class="id_delete" type="hidden" value="<?php echo $valor['id_aventuras_archivo'] ?>"/></div>
                                            <div class="left_com"></div>
                                            <div class="img_block">
                                                <input name="id_files[]" type="hidden" value="<?php echo $valor['id_aventuras_archivo'] ?>"/>
                                                <input name="src_files[]" type="hidden" value="<?php echo $valor['nombre_aventuras_archivos'] ?>"/>
                                                <input name="tipo_files[]" type="hidden" value="<?php echo $valor['tipo_aventuras_archivo'] ?>"/>
                                                <?php if ($valor['tipo_aventuras_archivo'] == 'F') { ?>
                                                    <div class="img_comparte">
                                                        <img src="aplication/utilities/timthumb.php?src=<?php echo _url_avfiles_users_ . $valor['nombre_aventuras_archivos'] ?>&h=119&w=171&zc=1"/>
                                                    <?php } else if ($valor['tipo_aventuras_archivo'] == 'V') { ?>
                                                        <div class="img_comparte"><a href="http://www.youtube.com/watch?v=<?php echo $valor['nombre_aventuras_archivos'] ?>" target="_blank"><img src="<?php echo _imgs_ ?>icon_video_ver.jpg"></a></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="descrp_comparte">
                                                <div class="rowElem"><input name="titulo_files[]" type="text" value="<?php echo $valor['titulo_aventuras_archivo'] ?>" maxlength="45"/></div>
                                                <div class="rowElem"><textarea name="descripcion_files[]"><?php echo $valor['comentario_aventuras_archivo'] ?></textarea></div>
                                            </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <div class="clear"></div>
                            <br/>
                        </div>
                        <div class="panel_info_aventura2"></div>
                        <br/><br/>
                        <!-- PANEL -->

                        <div class="rowElem fileupload-container" style="margin: 0 auto;width: 748px;">
                            <noscript><input type="hidden" name="redirect" value="www.deaventura.pe"></noscript>
                            <div class="container">
                                
                                <div class="rowElem">
                                    <label>SUBIR TUS FOTOS:</label>
                                    <div class="btn row fileupload-buttonbar btn_subir_archivo">
                                        <div class="span7">
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="fileinput-button">
                                                <i class="icon-plus icon-white"></i>
                                                <span>+ Añadir Fotos</span>
                                                <input type="file" name="files[]" multiple>
                                            </span>
                                            <button type="submit" class="btn btn-primary start">
                                                <i class="icon-upload icon-white"></i>
                                                <span>Start upload</span>
                                            </button>
                                        </div>
                                    </div>
									<span style="color:red; font-size:11px;margin-left: 20px;">(Puede subir sus fotos de 10 en 10)</span>                                    
                                </div>
                                <div class="rowElem youtube">
                                    <label><img style="padding-right: 7px;vertical-align: text-top;" src="aplication/webroot/imgs/icon_youtube.jpg" width="17"/>Si tienes un video de youtube:</label>
                                    <input id="video_txt" type="text" size="30" placeholder="Ejm: http://www.youtube.com/watch?v=H542nLTTbu0">
                                    <input id="btn_svideo" class="btn btn_subir_archivo" type="button" value="+ Añadir video..">
                                </div>
                                <!-- The loading indicator is shown during file processing -->
                                <br>
                                <div class="fileupload-loading"></div>
                                <!-- The table listing the files available for upload/download -->
                                <div class="tableTop"><div class="td1">Foto o Video</div><div class="td3">¿Eliminar?</div></div>
                                <table id="table_imgs" role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
                                <!--</form>-->
                                <br>
                            </div>
                            <!-- The template to display files available for upload -->
                            <script id="template-upload" type="text/x-tmpl">
                                {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-upload fade">
                                <td>
                                <span class="preview"></span>
                                </td>
                                <td>
                                <p class="name">{%=file.name%}</p>
                                {% if (file.error) { %}
                                <div><span class="label label-important">Error</span> {%=file.error%}</div>
                                {% } %}
                                </td>
                                <td width="240">
                                <p class="size">{%=o.formatFileSize(file.size)%}</p>
                                {% if (!o.files.error) { %}
                                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
                                {% } %}
                                </td>
                                <td width="100">
                                {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                                <button class="btn btn-primary start">
                                <i class="icon-upload icon-white"></i>
                                <span>Start</span>
                                </button>
                                {% } %}
                                {% if (!i) { %}
                                <button class="btn btn-warning cancel">
                                <i class="icon-ban-circle icon-white"></i>
                                <span></span>
                                </button>
                                {% } %}
                                </td>
                                </tr>
                                {% } %}
                            </script>
                            <!-- The template to display files available for download -->
                            <script id="template-download" type="text/x-tmpl">
                                {% for (var i=0, file; file=o.files[i]; i++) { %}
                                <tr class="template-download fade">
                                <td>
                                <span class="preview">
                                {% if (file.thumbnail_url) { %}
                                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
                                {% } %}
                                </span>
                                </td>
                                <td>
                                <p class="name">
                                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}"> {%=file.name%}</a>
                                </p>
                                {% if (file.error) { %}
                                <div><span class="label label-important">Error</span> {%=file.error%}</div>
                                {% } %}
                                </td>
                                <td>
                                <span class="size">{%=o.formatFileSize(file.size)%}</span>
                                </td>
                                <td>
                                <button class="btn btn-danger delete" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                <i class="icon-trash icon-white"></i>
                                <span>Delete</span>
                                </button>
                                <input type="checkbox" name="delete" value="1" class="toggle">
                                </td>
                                </tr>
                                {% } %}
                            </script>
                        </div>
						<div style="display:table;width: 90%;">    
                            <div style="float: right;">
                                 <input class="btn btn_subir_archivo" id="subir_archivos" type="button" value="Subir archivos">
                            </div>
                        </div>

                        <div class="clear"></div>
                        <br/><br/>
                        <div align="center">
                            <!--<input class="btn_style2" type="button" value="x Cancelar" onclick="javascript: window.location = 'cuenta.php?cuenta=misAventuras'">-->
							<input class="btn btn_cancelar_archivo" type="button" value="x Cancelar" onclick="javascript: window.location = 'cuenta.php?cuenta=misAventuras'">
                            <!--<input class="btn btn_subir_archivo" id="subir_archivos" type="button" value="Subir archivos">-->
                            <input class="btn btn_guardar" type="submit" value="Guardar Cambios >">
                        </div>
                    </form>
                </div>

                <div class="clear"></div>
            </div>

        </div>
        <?php
        if (isset($_SESSION['files_act'])) {
            $temp_files = $_SESSION['files_act'];
            foreach ($temp_files as $value) {
                $nombre_ant = _host_avfiles_users_ . $value;
                if (file_exists($nombre_ant))
                    unlink($nombre_ant);

                $thumb = _host_avfiles_users_ . 'thumbnail/' . $value;
                if (file_exists($thumb))
                    unlink($thumb);
            }
            unset($_SESSION['files_act']);
        }
    }

    public function updateAventura() {
        $sql = "UPDATE aventuras SET id_modalidad='" . $_POST['cbo_modalidad'] . "',
                        id_agencia='" . $_POST['cbo_agencias'] . "',
                        titulo_aventura ='" . addslashes($_POST['titulo']) . "',
                        lugar_aventura ='" . addslashes($_POST['lugar']) . "',
                        descripcion_aventura ='" . addslashes($_POST['descripcion']) . "',
                        lat_aventura ='" . $_POST['lat_pos'] . "',
                        lng_aventura ='" . $_POST['lng_pos'] . "' 
                     WHERE id_aventura = '" . $_POST["id_aventura"] . "'";

        $query1 = new Consulta($sql);

        $array = array();
        for ($i = 0; $i < count($_POST["src_files"]); $i++) {
            $array[$i][0] = $_POST["id_files"][$i];
            $array[$i][1] = $_POST["src_files"][$i];
            $array[$i][2] = $_POST["titulo_files"][$i];
            $array[$i][3] = $_POST["descripcion_files"][$i];
            $array[$i][4] = $_POST["tipo_files"][$i];
        }

        foreach ($array as $position => $valor) {
            if ($valor[0] != "") {
                $sql2 = "UPDATE aventuras_archivos SET nombre_aventuras_archivos='" . addslashes($valor[1]) . "',
                        orden_aventuras_archivos ='" . $position . "',    
                        titulo_aventuras_archivo ='" . addslashes($valor[2]) . "',
                        tipo_aventuras_archivo ='" . $valor[4] . "',
                        comentario_aventuras_archivo ='" . addslashes($valor[3]) . "'
                        WHERE id_aventuras_archivo = '" . $valor[0] . "'";

                $query2 = new Consulta($sql2);
            } else {
			
			    //inicio
				
				$sql4 = "SELECT * FROM aventuras_archivos WHERE id_aventura = '" . $_POST["id_aventura"] . "' ORDER BY id_aventuras_archivo desc LIMIT 1";
                $query4 = new Consulta($sql4);
                $row4 = $query4->VerRegistro();
                $position_update=$row4['orden_aventuras_archivos']+1;            
                $valor_inicial=explode('_',$row4['nombre_aventuras_archivos']);  
                $valor_final=explode('_',$valor_inicial[1]);
                $count=$valor_final[0]+1;    

                //$nombre_imagen_solo_nuevo = $_POST["idAventura"]."_".$count."_".time().".jpg";
				$nombre_imagen_solo_nuevo = $row4["id_aventura"]."_".$count."_".time().".jpg";
                $nombre_imagen_actual = _host_avfiles_users_.$valor[1];
                $nombre_imagen_nuevo = _host_avfiles_users_.$nombre_imagen_solo_nuevo; 
                rename($nombre_imagen_actual, $nombre_imagen_nuevo);
				
				//fin
			
                $sql3 = "INSERT INTO aventuras_archivos(id_aventura,
                                      orden_aventuras_archivos,
                                      nombre_aventuras_archivos,
                                      titulo_aventuras_archivo,
                                      comentario_aventuras_archivo,
                                      tipo_aventuras_archivo) 
                VALUES (
                   '" . $_POST["id_aventura"] . "',
                   '" . $position_update . "',
                   '" . addslashes($nombre_imagen_solo_nuevo) . "',
                   '" . addslashes($valor[2]) . "',
                   '" . addslashes($valor[3]) . "',
                   '" . $valor[4] . "')";

                $query3 = new Consulta($sql3);
            }
        }

        unset($_SESSION['files_act']);
        location("cuenta.php?cuenta=misAventuras");
    }

    public function deleteAventura($id) {
        $query = new Consulta("SELECT nombre_aventuras_archivos FROM aventuras_archivos WHERE id_aventura = '" . $id . "'");

        while ($row = $query->VerRegistro()) {
            $nombre = _host_avfiles_users_ . $row['nombre_aventuras_archivos'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
        $query = new Consulta("DELETE FROM aventuras WHERE id_aventura = '" . $id . "'");
        $query2 = new Consulta("DELETE FROM favoritos WHERE id_aventura = '" . $id . "'");
        $query3 = new Consulta("DELETE FROM registro_actividad WHERE id_aventura = '" . $id . "'");
    }

    public function url_Aventura($deporte, $id, $titulo) {
        $url_aventura = '3b1' . $id . '0b/aventura-de-' . url_friendly($deporte, 1) . '/' . url_friendly($titulo, 1);
        return $url_aventura;
    }

    public function getAventuras_valoradas() {
        $sql = "SELECT id_aventura, nombre_deporte, cant_likes_aventura, titulo_aventura, nombre_aventuras_archivos, nombre_cliente 
                    FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE orden_aventuras_archivos = '0' AND cant_likes_aventura > 0 GROUP BY id_aventura ORDER BY cant_likes_aventura DESC LIMIT 0,6";

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_aventura' => $row['id_aventura'],
                    'titulo_aventura' => $row['titulo_aventura'],
                    'nombre_deporte' => $row['nombre_deporte'],
                    'nombre_cliente' => $row['nombre_cliente'],
                    'cant_likes_aventura' => $row['cant_likes_aventura'],
                    'nombre_aventuras_archivos' => $row['nombre_aventuras_archivos']
                );
            }
        }
        return $data;
    }
    
    public function getAventurasUltimas($limite = "0,50") {
        $sql = "SELECT id_aventura, nombre_deporte, cant_likes_aventura, titulo_aventura, nombre_aventuras_archivos, nombre_cliente, cant_visitas_aventura, lugar_aventura, fecha_creacion_aventura 
                FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE orden_aventuras_archivos = '0' 
                    AND estado_aventura = 1
                          
                GROUP BY id_aventura 
                ORDER BY fecha_creacion_aventura DESC 
                LIMIT ".$limite;

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_aventura' => $row['id_aventura'],
                    'titulo_aventura' => $row['titulo_aventura'],
                    'nombre_deporte' => $row['nombre_deporte'],
                    'nombre_cliente' => $row['nombre_cliente'],
                    'cant_likes_aventura' => $row['cant_likes_aventura'],
                    'cant_visitas_aventura' => $row['cant_visitas_aventura'],
                    'lugar_aventura' => $row['lugar_aventura'],
                    'fecha_aventura' => $row['fecha_creacion_aventura'],
                    'nombre_aventuras_archivos' => $row['nombre_aventuras_archivos']
                );
            }
        }
        return $data;
    }
    
    public function getAventurasTotal($limite = "0,50") {
        $sql = "SELECT * FROM aventuras";
        $query = new Consulta($sql);
        $total_registros = $query->NumeroRegistros();
        return $total_registros;
    }
    
    static public function getAventurasMenosUno($id_aventura, $limite = "0,10") {
        $sql = "SELECT id_aventura, nombre_deporte, cant_likes_aventura, titulo_aventura,cant_visitas_aventura, nombre_aventuras_archivos, id_cliente, nombre_cliente, lugar_aventura, fecha_creacion_aventura 
                FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                WHERE id_aventura !='".$id_aventura."'
                GROUP BY id_aventura 
                ORDER BY cant_likes_aventura DESC 
                LIMIT ".$limite;
         $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_aventura' => $row['id_aventura'],
                    'titulo_aventura' => $row['titulo_aventura'],
                    'nombre_deporte' => $row['nombre_deporte'],
                    'lugar_aventura' => $row['lugar_aventura'],
                    'fecha_aventura' => $row['fecha_creacion_aventura'],
                    'id_cliente' => $row['id_cliente'],
                    'nombre_cliente' => $row['nombre_cliente'],
                    'cant_likes_aventura' => $row['cant_likes_aventura'],
                    'imagen_aventura' => $row['nombre_aventuras_archivos'],
                    'cantidad_visitas' => $row['cant_visitas_aventura']
                );
            }
        }
        return $data;
    }

    public function listAventuras($idtipo = "", $tipo = "all") {

        if ($tipo === "deporte")
            $filtro = "id_deporte ='" . $idtipo . "' AND";
        else if ($tipo === "modalidad")
            $filtro = "id_modalidad ='" . $idtipo . "' AND";
        else if ($tipo === "cliente")
            $filtro = "id_cliente ='" . $idtipo . "' AND";
        else if ($tipo === "all")
            $filtro = "";


        $sql = "SELECT id_aventura, id_deporte, nombre_deporte, cant_coment_aventura, cant_likes_aventura, titulo_aventura, fecha_creacion_aventura, nombre_aventuras_archivos, nombre_cliente, id_cliente, url_cliente, nombre_deporte 
                FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE " . $filtro . " orden_aventuras_archivos = '0' 
                    AND estado_aventura = 1
                GROUP BY id_aventura 
                ORDER BY fecha_creacion_aventura DESC 
                LIMIT 0,8";

        $queryp = new Consulta($sql);
        
            if ($queryp->NumeroRegistros() > 0 ) {
                ?>

                <?php
                while ($rowp = $queryp->VerRegistro()) {
                    $nfecha = explode("-", $rowp['fecha_creacion_aventura']);
                    $file = _url_avfiles_users_ . $rowp["nombre_aventuras_archivos"];

                    // URLS
                    $url_aventura = $this->url_Aventura($rowp["nombre_deporte"], $rowp["id_aventura"], $rowp['titulo_aventura']);
                    $clientes = new Clientes();
                    //$likes = $this->count_LikesFB($url_aventura, $rowp['id_aventura']);
                    //$comments = $this->count_commentFB($url_aventura, $rowp['id_aventura']);

                    $sql2 = "SELECT COUNT(id_aventuras_archivo) AS cant_images FROM aventuras_archivos WHERE id_aventura = '" . $rowp["id_aventura"] . "'";
                    $query2 = new Consulta($sql2);
                    $rowI = $query2->VerRegistro();
                    ?>
                    <div class="pnl panel2" id="av<?php echo $rowp["id_aventura"] ?>">
                        <img src="aplication/utilities/timthumb.php?src=<?php echo $file ?>&h=290&w=415&zc=1"/>
                        <div class="fecha_panel"><?php echo Month($rowp['fecha_creacion_aventura']) ?><br/><span><?php echo $nfecha[2]; ?></span></div>
                        <ul class="social_panel">
                            <li class="photo"><?php echo $rowI['cant_images'] ?></li> 
                        </ul>
                        <div class="titulo_panel">
                            <a title="Ver detalle de la aventura" href="<?php echo $url_aventura ?>"><?php echo ucfirst(strtolower($rowp['titulo_aventura'])) ?></a><br/>
                            <a title="Ver aventuras de <?php echo $rowp['nombre_cliente'] ?>" href="<?php echo $clientes->getURL($rowp['id_cliente'], $rowp['nombre_cliente']) ?>">por <?php echo ucfirst($rowp['nombre_cliente']) ?></a>
                        </div>
                    </div>

                    <?php
                    
                    
                }
                
                if ($queryp->NumeroRegistros() > 8) { ?>
                <div id="loadMoreContent" style="display:none;" align="center"><img src="aplication/webroot/imgs/ajax-bar.gif"></div>
                    <?php
                } 
            }else{
                if ($tipo == "modalidad" || $tipo == "deporte") {
                    if ($this->_cuenta->__get("_cliente")->__get("_logeado")) {
                        ?>
                        <a href="cuenta.php?cuenta=compartir" class="panel_ingresa"><p>INGRESA AQUÍ</p><p>TU HISTORIA DE AVENTURA</p></a>
                        <a href="cuenta.php?cuenta=compartir" class="panel_ingresa"><p>INGRESA AQUÍ</p><p>TU HISTORIA DE AVENTURA</p></a>
                        <?php
                    } else {
                        ?>
                        <a href="javascript: login()" class="panel_ingresa"><p>INGRESA AQUÍ</p><p>TU HISTORIA DE AVENTURA</p></a>
                        <a href="javascript: login()" class="panel_ingresa"><p>INGRESA AQUÍ</p><p>TU HISTORIA DE AVENTURA</p></a>
                        <?php
                    }
                } else {
                    echo '<br/><div align="center">No se encontraron historias de Aventura </div><br/><br/>';
                }
            }
    }

    /**
     * Function just USEFULL In class 'Secciones.php' function 'deportes_cliente'
     * @param type $id_cliente
     */
    public function listAventurasCliente($id_cliente) {
        
        $sql = "SELECT id_aventura, id_deporte, nombre_deporte, cant_coment_aventura, cant_likes_aventura, titulo_aventura, fecha_creacion_aventura, nombre_aventuras_archivos, nombre_cliente, id_cliente, url_cliente, nombre_deporte,
            lugar_aventura,
            lat_aventura,
            lng_aventura,
            id_deporte
                FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE id_cliente = {$id_cliente}  AND orden_aventuras_archivos = '0' 
                    AND estado_aventura = 1
                GROUP BY id_aventura 
                ORDER BY fecha_creacion_aventura DESC 
                LIMIT 0,8";

        $queryp = new Consulta($sql);
        $dataJsLugares = '';
        if ($queryp->NumeroRegistros() > 0 ) {
            while ($rowp = $queryp->VerRegistro()) {
                $nfecha = explode("-", $rowp['fecha_creacion_aventura']);
                $file = _url_avfiles_users_ . $rowp["nombre_aventuras_archivos"];

                // URLS
                $url_aventura = $this->url_Aventura($rowp["nombre_deporte"], $rowp["id_aventura"], $rowp['titulo_aventura']);
                //array js
                $dataJsLugares = $dataJsLugares . '["'. $rowp['titulo_aventura'] .'", '.$rowp['lat_aventura'].', '.$rowp['lng_aventura'].', '.$rowp['id_deporte'].'],';
                
                $clientes = new Clientes();
                $sql2 = "SELECT COUNT(id_aventuras_archivo) AS cant_images FROM aventuras_archivos WHERE id_aventura = '" . $rowp["id_aventura"] . "'";
                $query2 = new Consulta($sql2);
                $rowI = $query2->VerRegistro();
            ?>
        <div class="flipwrapper">
            <article class="pnl" id="av">
                <div class="front_evento text-center">
                    <a href="<?php echo $url_aventura ?>">
                        <img src="<?php echo _url_ ?>aplication/utilities/timthumb.php?src<?php echo $file ?>&h=275&amp;w=275&amp;zc=1" width="275" height="275">
                        <div class="fecha_panel_evento">
                            <?php echo Month($rowp['fecha_creacion_aventura']) ?><span><?php echo $nfecha[2]; ?></span>
                        </div>
                    </a>
                    <div class="titulo_panel_evento">                            
                        <a title="<?php echo ucfirst(strtolower($rowp['titulo_aventura'])) ?>" href="<?php echo ucfirst(strtolower($rowp['titulo_aventura'])) ?>">
                            <?php echo ucfirst(strtolower($rowp['titulo_aventura'])) ?>
                        </a>
                    </div>
                    <div class="text-size-5">
                        <b><?php echo $rowp['nombre_cliente'] ?></b> <?php echo !empty($rowp['lugar_aventura']) ? 'en '. $rowp['lugar_aventura'] : '' ?>
                    </div>

                    <div class="">
                        <div class="col-md-6">
                            <div href="<?php echo $url_aventura ?>" class="fb-like" data-send="false" data-layout="button_count" data-show-faces="false"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="g-plusone" data-size="medium" data-href="<?php echo $url_aventura ?>" data-callback="gplusClickHandler" ></div>
                        </div>                                
                    </div>

               </div>
            </article>
         </div>                        
        <?php
            }
            

            $dataJsLugares = substr($dataJsLugares, 0, (strlen($dataJsLugares)-1));
            
            
        } else {
        ?>
                        <div class="text-center">No se encontraron historias de Aventura</div>
        <?php
        }
        
        ?>


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

                            <?php if (!empty($dataJsLugares)) : ?>
                            var lugares = [<?php echo $dataJsLugares ?>];
                            <?php else : ?>
                                var lugares = [];
                            <?php endif; ?>


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
                                //var image = new google.maps.MarkerImage('http://www.deaventura.pe/aplication/webroot/imgs/catalogo/thumb_1353683228icon-ciclismo.png');
                                for (var i = 0; i < locations.length; i++) {
                                    var arr = locations[i];
                                    var image = new google.maps.MarkerImage(getIconoDeporte(arr[3]));
                                    var myLatLng = new google.maps.LatLng(arr[1], arr[2]);
                                    var marker = new google.maps.Marker({
                                        position: myLatLng,
                                        map: map,
                                        draggable: false, //Para que no se pueda mover
                                        animation: google.maps.Animation.DROP,
                                        icon: image,
                                        title: arr[0],
                                        zIndex: pos++
                                    });
                                    var html = '<b>' + arr[0] + '</b><br/>';
                                    bindInfoWindow(marker, map, infoWindow, html);
                                }
                            }

                            // function get ico map
                            // link reference : http://www.deaventura.pe/aplication/webroot/imgs/catalogo/thumb_1353683228icon-ciclismo.png
                            function getIconoDeporte(id_deporte) {
                                console.log(id_deporte);
                                var url = 'http://www.deaventura.pe/aplication/webroot/imgs/catalogo/';
                                var id_deporte = parseInt(id_deporte);
                                if (id_deporte === 1) { alert("hi");
                                    url = url + 'andinismo.png';
                                } else if( id_deporte == 2) {
                                   url = url + '4x4_hover.png';
                                } else if(id_deporte == 3) {
                                   url = url + 'thumb_1353683194icon-buceo.png';
                                } else if(id_deporte == 4) {
                                   url = url + 'puenting_hover.png';
                                } else if(id_deporte == 5) {
                                   url = url + 'thumb_1353683220icon-canotaje.png';
                                } else if(id_deporte == 6) {
                                   url = url + 'thumb_1353683228icon-ciclismo.png';
                                } else if(id_deporte == 7) {
                                   url = url + 'thumb_1353683238icon-escala.png';
                                } else if(id_deporte == 8) {
                                   url = url + 'thumb_1353683248icon-parapente.png';
                                } else if(id_deporte == 9) {
                                   url = url + 'sandboard_hover.png';
                                } else if(id_deporte == 10) {
                                   url = url + 'sandboard_hover.png';
                                } else if(id_deporte == 11) {
                                   url = url + 'thumb_1353683262icon-surf.png';
                                } else if(id_deporte == 12) {
                                   url = url + 'trekking_hover.png';
                                } else if(id_deporte == 13) {
                                   url = url + 'thumb_1353683194icon-buceo.png';
                                } else if(id_deporte == 14) {
                                   url = url + 'thumb_1353683338icon-triatlon.png';
                                } else if(id_deporte == 15) {
                                   url = url + 'otros-deportes.png';
                                }
                                
                                return url;
                            }
            </script>
        <?php
 
        
    }
    
    function load_more_content($id, $tipo, $uid) {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT) . PHP_EOL;
        $uid = filter_var(substr($uid, 2), FILTER_SANITIZE_NUMBER_INT) . PHP_EOL;

        if ($tipo === "deporte")
            $filtro = "id_deporte ='" . $uid . "' AND";
        else if ($tipo === "modalidad")
            $filtro = "id_modalidad ='" . $uid . "' AND";
        else if ($tipo === "cliente")
            $filtro = "id_cliente ='" . $uid . "' AND";
        else if ($tipo === "all")
            $filtro = "";

        $sql = "SELECT id_aventura, id_deporte, nombre_deporte, cant_coment_aventura, cant_likes_aventura, titulo_aventura, fecha_creacion_aventura, nombre_aventuras_archivos, nombre_cliente, id_cliente, url_cliente, nombre_deporte 
                    FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE " . $filtro . " orden_aventuras_archivos = '0' AND id_aventura < " . $id . " GROUP BY id_aventura ORDER BY fecha_creacion_aventura DESC LIMIT  0,6";

        $query = new Consulta($sql);

        if ($query->NumeroRegistros() > 0) {
            while ($rowp = $query->VerRegistro()) {
                $nfecha = explode("-", $rowp['fecha_creacion_aventura']);
                $file = _url_avfiles_users_ . $rowp["nombre_aventuras_archivos"];

                // URLS
                $url_aventura = $this->url_Aventura($rowp["nombre_deporte"], $rowp["id_aventura"], $rowp['titulo_aventura']);
                $clientes = new Clientes();

                $sql2 = "SELECT COUNT(id_aventuras_archivo) AS cant_images FROM aventuras_archivos WHERE id_aventura = '" . $rowp["id_aventura"] . "'";
                $query2 = new Consulta($sql2);
                $rowI = $query2->VerRegistro();
                ?>
                <div class="pnl panel2" id="av<?php echo $rowp["id_aventura"] ?>">
                    <img src="aplication/utilities/timthumb.php?src=<?php echo $file ?>&h=240&w=360&zc=1"/>
                    <div class="fecha_panel"><?php echo Month($rowp['fecha_creacion_aventura']) ?><br/><span><?php echo $nfecha[2]; ?></span></div>
                    <ul class="social_panel">
                        <li class="photo"><?php echo $rowI['cant_images'] ?></li> 
                    </ul>
                    <div class="titulo_panel">
                        <a title="Ver detalle de la aventura" href="<?php echo $url_aventura ?>"><?php echo ucfirst(strtolower($rowp['titulo_aventura'])) ?></a><br/>
                        <a title="Ver aventuras de <?php echo $rowp['nombre_cliente'] ?>" href="<?php echo $clientes->getURL($rowp['id_cliente'], $rowp['nombre_cliente']) ?>">por <?php echo ucfirst($rowp['nombre_cliente']) ?></a>
                    </div>
                </div>

                <?php
            }
        }
        else
            echo 0;
    }
    
    public function listAventuras_usuario($idCliente) { //Panel de administracion del usuario
        $sql = "SELECT id_aventura, id_modalidad, id_deporte, nombre_deporte, cant_coment_aventura, cant_likes_aventura, titulo_aventura, fecha_creacion_aventura, nombre_aventuras_archivos, nombre_cliente, id_cliente, url_cliente, nombre_deporte,cant_visitas_aventura 
                    FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    INNER JOIN modalidades USING(id_modalidad)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE id_cliente ='" . $idCliente . "' AND orden_aventuras_archivos = '0' GROUP BY id_aventura";
        $queryp = new Consulta($sql);

        if ($queryp->NumeroRegistros() != 0) { ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                      <th>#</th>
                      <th>Publicado</th>
                      <th>Fotos</th>
                      <th>Vistas</th>
                      <th>Aventura</th>
                      <th>Opciones</th>
                    </tr>
                </thead>
            <?php
            while ($rowp = $queryp->VerRegistro()) {
                $sql2 = "SELECT COUNT(id_aventuras_archivo) AS cant_images FROM aventuras_archivos WHERE id_aventura = '" . $rowp["id_aventura"] . "'";
                $query2 = new Consulta($sql2);
                $rowI = $query2->VerRegistro();

                $modalidad = new Modalidad($rowp["id_modalidad"]);
                $url_aventura = _url_ . $this->url_Aventura($modalidad->__get("_deporte")->__get("_nombre_deporte"), $rowp["id_aventura"], $rowp['titulo_aventura']); ?>
                <tr>
<!--                    <td>
                        <img src="aplication/utilities/timthumb.php?src=<?php echo _url_avfiles_users_ . $rowp['nombre_aventuras_archivos'] ?>&h=100&w=100&zc=1"/>
                    </td>-->
                    <td> <?php echo $rowp['id_aventura']; ?> </td>
                    <td class="fecha"> <?php echo formato_slash("-",$rowp['fecha_creacion_aventura']) ?> </td>
                    <td> <ul class="info_social"> <li class="photo"><?php echo $rowI['cant_images'] ?></li> </ul> </td>
                    <td> <?php echo $rowp['cant_visitas_aventura']; ?> </td>
                    <td> <a href="<?php echo $url_aventura ?>" target="_blank"><img src="aplication/webroot/imgs/icon_mas.jpg" width="16" height="17"></a> <?php echo $rowp['titulo_aventura'] ?></td>
                    <td>
                        <a class="btn-circle btn-edit glyphicon glyphicon-pencil" href="cuenta.php?cuenta=edit&idAventura=<?php echo $rowp['id_aventura'] ?>" title="editar"></a>
                        <a class="btn-circle btn-delete glyphicon glyphicon-remove" href="#" title="eliminar"><input type="hidden" value="<?php echo $rowp['id_aventura'] ?>"></a>
                    </td>
                </tr>
                <?php
            }
            ?>      
          </table>
            <?php
        } else {
            echo '<br/><div align="center">No tienes aventuras para mostrar.</div>';
        }
    }
    
    public function listUsuariosAdmin(){
        
    }
    static function update_likes_comments($id, $tipo, $likes, $comments) {
        //http://www.nahuelsanchez.com/45/estadisticas-del-me-gusta-y-urls-compartidas-en-facebook/

        /* $xml = simplexml_load_file('https://api.facebook.com/method/fql.query?query=SELECT%20like_count,comment_count,share_count,total_count%20FROM%20link_stat%20WHERE%20url=%22' . $url . '%22');
          $like_count = $xml->link_stat->like_count;
          $coment_count = $xml->link_stat->comment_count; */

        $id = filter_var(substr($id, 2), FILTER_SANITIZE_NUMBER_INT) . PHP_EOL;
        if ($tipo === "coments") {
            $sql = "UPDATE aventuras SET cant_coment_aventura='" . $comments . "' WHERE id_aventura = '" . $id . "'";
        } else if ($tipo === "likes") {
            $sql = "UPDATE aventuras SET cant_likes_aventura='" . $likes . "' WHERE id_aventura = '" . $id . "'";
        } else if ($tipo === "all") {
            $sql = "UPDATE aventuras SET cant_coment_aventura='" . $comments . "', 
                                         cant_likes_aventura='" . $likes . "' WHERE id_aventura = '" . $id . "'";
        }
// 
        $query = new Consulta($sql);
    }
    
    static function update_cantidad_visitas($id_aventura) {
        $sql = "UPDATE aventuras SET cant_visitas_aventura = cant_visitas_aventura + 1 WHERE id_aventura='".$id_aventura."'";
        $query = new Consulta($sql);
    }

}
?>