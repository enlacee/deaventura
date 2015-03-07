<?php

class Cuenta {

    private $_cliente;
    private $_facebook;

    public function __construct($cliente = NULL) {
        $this->_cliente = $cliente;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $value) {
        $this->$atributo = $value;
    }

    public function getLogeado() {
        return $this->_cliente->__get("_logeado");
    }

    public function cerrarSesion() {
        $this->_cliente->__set('_logeado', FALSE);
        unset($_SESSION["aventura"]);
        unset($_SESSION["miaventura"]);
        if (isset($_SESSION['files_act'])) {
            $temp_files = $_SESSION['files_act'];
            foreach ($temp_files as $value) {
                $nombre_ant = _host_avfiles_users_ . $value;
                if (file_exists($nombre_ant)) {
                    unlink($nombre_ant);
                }
            }
            unset($_SESSION['files_act']);
        }

        //location($this->_facebook->getLogoutUrl()); //Para que también se cierrer facebook
        echo '<script>window.location = "http://www.deaventura.pe/"</script>';
    }

    public function cuentaAdd($uid, $name, $lastname, $sexo, $email, $link) {
        if ($uid != "" && $name != "" && $lastname != "" && $sexo != "" && $email != "" && $link != "") {
            $sql = "INSERT INTO clientes (id_facebook_cliente,nombre_cliente,apellidos_cliente,fecha_registro_cliente,sexo_cliente,email_cliente,url_cliente) VALUES('" . $uid . "','" . $name . "','" . $lastname . "',NOW(),'" . $sexo . "','" . $email . "','" . $link . "')";
            $query = new Consulta($sql);
            if ($query->registrosAfectado() > 0) {
                //Agrego para el registro de actividades
                $sql3 = "INSERT INTO registro_actividad(id_aventura,
                                      id_cliente,
                                      estado_registro_actividad,
                                      fecha_registro_actividad )
                VALUES (0,'" . $query->nuevoId() . "','registro',NOW())";
                $query2 = new Consulta($sql3);


                $this->cuentaAcceso($uid);
            }
        }
    }

    public function validarUsuario($id) {
        $sql_ver = "SELECT id_cliente FROM clientes WHERE id_facebook_cliente=" . $id . "";

        $query_ver = new Consulta($sql_ver);
        if ($query_ver->NumeroRegistros() > 0) {
            return 1; //1 existe la cuenta
        } else {
            return 0;
        }
    }

    public function cuentaAcceso($idface) {

        $sql_ver = "SELECT * FROM clientes WHERE id_facebook_cliente='" . $idface . "'";

        $query_ver = new Consulta($sql_ver);
        if ($query_ver->NumeroRegistros() > 0) {

            $row = $query_ver->VerRegistro();
            $this->_cliente->__set("_id", $row["id_cliente"]);
            $this->_cliente->__set("_logeado", TRUE);
            $this->_cliente->__set("_idFacebook", $row["id_facebook_cliente"]);
            $this->_cliente->__set("_nombre", $row["nombre_cliente"]);
            $this->_cliente->__set("_apellidos", $row["apellidos_cliente"]);
            $this->_cliente->__set("_foto", $row['image']);
            $this->_cliente->__set("_sexo", $row["sexo_cliente"]);
            $this->_cliente->__set("_email", $row["email_cliente"]);
            $this->_cliente->__set("_url", $row["url_cliente"]);
        }
    }

    public function get_registroActividades() {
        $sql = new Consulta("SELECT ra.id_aventura, ra.id_cliente, nombre_cliente, id_facebook_cliente, image, tipo_foto_cliente, estado_registro_actividad, fecha_registro_actividad FROM registro_actividad ra 
            INNER JOIN clientes c ON ra.id_cliente = c.id_cliente ORDER BY fecha_registro_actividad DESC LIMIT 0,15");
        if ($sql->NumeroRegistros() > 0) {
            while ($row2 = $sql->VerRegistro()) {
                $query = new Consulta("SELECT titulo_aventura FROM aventuras WHERE id_aventura = " . $row2['id_aventura']);
                $row = $query->VerRegistro();
                $arr[] = array(
                    'id_aventura' => $row2['id_aventura'],
                    'titulo_aventura' => ucfirst(strtolower($row["titulo_aventura"])),
                    'id_cliente' => $row2['id_cliente'],
                    'nombre_cliente' => $row2['nombre_cliente'],
                    'id_facebook_cliente' => $row2['id_facebook_cliente'],
                    'image' => $row2['image'],
                    'tipo_foto_cliente' => $row2['tipo_foto_cliente'],
                    'estado_registro_actividad' => $row2['estado_registro_actividad'],
                    'fecha_registro_actividad' => $row2['fecha_registro_actividad']
                );
            }
            return $arr;
        }
    }

    public function cuentaUpdate() {

        $imagen = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {

            $query = new Consulta("SELECT image FROM clientes WHERE id_cliente = '" . $this->_cliente->__get("_id") . "'");
            $row = $query->VerRegistro();

            if ($row['image'] != '') {
                $nombre_ant = _host_files_users_ . $row['image'];
                if (file_exists($nombre_ant)) {
                    unlink($nombre_ant);
                }
            }

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _host_files_users_);
            $thumbnail->CreateThumbnail(90, 90, $nombre);

            $imagen = ", image = '" . $nombre . "'";
        }


        $sql = "UPDATE clientes SET 
                    nombre_cliente='" . addslashes($_POST['name']) . "',
                    apellidos_cliente='" . addslashes($_POST['lastname']) . "',
                    sexo_cliente='" . $_POST['sexo'] . "',
                    tipo_foto_cliente='" . $_POST['foto'] . "',
                    email_cliente='" . addslashes($_POST['email']) . "'
                    " . $imagen . " 
                WHERE id_cliente='" . $this->_cliente->__get("_id") . "' ";

        $query = new Consulta($sql);
        // location("cuenta.php?cuenta=misdatos");
    }

    public function misdatos_cuenta() {

        $sql_cliente = " SELECT * FROM clientes WHERE id_cliente = '" . $this->_cliente->__get("_id") . "' ";
        $queryCliente = new Consulta($sql_cliente);
        $row = $queryCliente->VerRegistro();
        ?>
        <div id="steps">
            <div id="titu_step"><img src="aplication/webroot/imgs/icon_user.png" width="9" height="12"> Mis datos<span></span></div>
            <div id="panel_step">
                <form action="cuenta.php?cuenta=misdatos" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Nombre:</label><input name="name" id="name" type="text" value="<?php echo $row['nombre_cliente'] ?>" size="23"/></div>
                        <div class="rowElem"><label>Apellidos:</label><input name="lastname" id="lastname" type="text" value="<?php echo $row['apellidos_cliente'] ?>" size="23"></div>
                        <div class="rowElem"><label>Email:</label><input name="email" id="email" type="text" value="<?php echo $row['email_cliente'] ?>" size="23"></div>
                        <div class="rowElem">
                            <label>Sexo: </label>
                            <div id="reg_left_se">
                                <input type="radio" name="sexo" value="M" id="sexo_0" <?php if ($row['sexo_cliente'] == 'M') echo 'checked="checked"' ?> >
                                <p>Hombre</p>
                            </div>
                            <div id="reg_right_se">
                                <input type="radio" name="sexo" value="F" id="sexo_1" <?php if ($row['sexo_cliente'] == 'F') echo 'checked="checked"' ?> >
                                <p>Mujer</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="rowElem">
                            <label>Foto: </label>
                            <div id="reg_left">
                                <input type="radio" name="foto" value="F" id="foto_0" <?php if ($row['tipo_foto_cliente'] == 'F') echo 'checked="checked"' ?> >
                                <p>Usar foto actual del Facebook</p>
                                <div class="foto_face"><img src="https://graph.facebook.com/<?php echo $this->_cliente->__get("_idFacebook") ?>/picture" width="50" height="50"></div>
                            </div>
                            <div id="reg_right">
                                <input type="radio" name="foto" value="C" id="foto_1" <?php if ($row['tipo_foto_cliente'] == 'C') echo 'checked="checked"' ?> >
                                <p>Usar otra foto:</p>
                                <?php if ($row['image'] != "" || file_exists(_url_files_users_ . $row['image'])) { ?>
                                    <div class="foto_face"><img src="<?php echo _url_files_users_ . $row['image'] ?>" width="50" height="50"></div>
                                <?php } ?>
                                <div class="custom-input-file">
                                    <input type="file" name="image" id="image" class="input-file" />
                                    + Subir mi foto..
                                </div>​
                                <div class="archivo">...</div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <div class="pnl_btn" align="center"><input class="btn_style1" type="submit" value="Guardar Cambios >"></div>
                </form>
                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

    public function comparteAventura_step1() {
        unset($_SESSION["miaventura"]);
        unset($_SESSION["step1"]);
        unset($_SESSION["step2"]);
        unset($_SESSION["step3"]);

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
        ?>
        <div id="steps" class="new_upload">
            <div id="titu_step">Comparte tu Aventura<span>(Paso 1 de 3)</span></div>
            <div id="panel_step">

                <form action="cuenta.php?cuenta=compartir" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_step1" id="fileupload" onsubmit="return validate_step1(this, 'step2')">
                    <input type="hidden" value="step2" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Elegir el Deporte:</label>
                            <select name="cbo_deportes" id="cbo_deportes">
                                <option value="0">Elegir Deporte ...</option>
                            </select>
                        </div>
                        <div class="rowElem"><label>Elegir Modalidad:</label>
                            <select name="cbo_modalidad" id="cbo_modalidad">
                                <option value="0">Elegir modalidad ...</option>
                            </select>
                        </div>
                        <div class="rowElem"><label>Ponle un Título a tu Aventura:</label><input name="titulo" id="titulo" type="text" size="23" maxlength="35"/></div>
                        <div class="rowElem"><label>Lugar, Provincia y Departamento:</label><input name="lugar" id="lugar" type="text" size="30" maxlength="40"></div>
                        <div class="rowElem"><label>Descripción de la aventura:</label><textarea name="descripcion" id="descripcion" rows="5"></textarea></div>
                        <div class="rowElem fileupload-container">
                            <noscript><input type="hidden" name="redirect" value="www.deaventura.pe"></noscript>
                            <div class="container">
                                
                                <div class="rowElem">
                                    <label>SUBIR TUS FOTOS:</label>
                                    <div class="row fileupload-buttonbar">
                                        <div class="span7">
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="btn btn-success fileinput-button ax-browse-c">
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
                                </div>
                                <div class="rowElem youtube">
                                    <label><img style="padding-right: 7px;vertical-align: text-top;" src="aplication/webroot/imgs/icon_youtube.jpg" width="17"/>Si tienes un video de youtube:</label>
                                    <input id="video_txt" type="text" size="30" placeholder="Ejm: http://www.youtube.com/watch?v=H542nLTTbu0">
                                    <input id="btn_svideo" class="btn_style3" type="button" value="+ Añadir video..">
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
                                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
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
                        <div class="clear"></div>
                    </div>

                    <div class="pnl_btn" align="center">

                        <input class="btn_style1" type="submit" value="Continuar >">
                    </div>
                </form>


                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

    public function comparteAventura_step2() {

        $array_img = $_SESSION["miaventura"]["name_images"];
        $array_video = $_SESSION["miaventura"]["name_videos"];

        if ($_POST["action"] != "step2") {
            location("cuenta.php?cuenta=compartir");
        } else {
            unset($_POST["action"]);
        }
        /*
          echo '<pre>';
          print_r($_SESSION["miaventura"]);
          echo '</pre>'; */
        ?>
        <div id="steps">
            <div id="titu_step">Comparte tu Aventura<span>(Paso 2 de 3)</span></div>
            <div id="panel_step">
                <p>Si quieres ponle título a tu foto o video. También puedes cambiar el orden seleccionando la imagen y moviéndola donde quieras:</p>
                <form action="cuenta.php?cuenta=compartir" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_step2" id="form_step2" onsubmit="return validate_step2(this)">
                    <input type="hidden" value="step3" name="action">
                    <ul id="list_item">
                        <?php
                        if (count($array_img) > 0) {
                            foreach ($array_img as $valor) {
                                ?>
                                <li>
                                    <div class="panel_comparte">
                                        <div class="delete_step"><input class="id_delete" type="hidden" value="<?php echo $valor ?>"/></div>
                                        <div class="left_com"></div>
                                        <div class="img_block">
                                            <input name="src_files[]" type="hidden" value="<?php echo $valor ?>"/>
                                            <input name="tipo_files[]" type="hidden" value="F"/>
                                            <div class="img_comparte">
                                                <img src="aplication/utilities/timthumb.php?src=<?php echo _url_avfiles_users_ . $valor ?>&h=119&w=171&zc=1"/>
                                            </div>
                                        </div>
                                        <div class="descrp_comparte">
                                            <div class="rowElem"><input name="titulo_files[]" id="titulo_files" type="text" placeholder="Ponle un Título" maxlength="45"/></div>
                                            <div class="rowElem"><textarea name="descripcion_files[]" id="descripcion_files" placeholder="Cuéntanos más Aquí..."></textarea></div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>


                    <?php
                    if (count($array_video) > 0) {
                        foreach ($array_video as $valor2) {
                            ?>
                            <div class="panel_comparte">
                                <div class="delete"></div>
                                <div class="left_com"></div>
                                <div class="img_block">
                                    <div class="img_comparte">
                                        <input name="src_files[]" type="hidden" value="<?php echo $valor2 ?>"/>
                                        <input name="tipo_files[]" type="hidden" value="V"/>
                                        <a href="http://www.youtube.com/watch?v=<?php echo $valor2 ?>" target="_blank"><img src="<?php echo _imgs_ ?>icon_video_ver.jpg"></a>
                                    </div>
                                </div>
                                <div class="descrp_comparte">
                                    <div class="rowElem"><input name="titulo_files[]" id="titulo_files" type="text" placeholder="Ponle un Título"/></div>
                                    <div class="rowElem"><textarea name="descripcion_files[]" id="descripcion_files" placeholder="Cuéntanos más Aquí..."></textarea></div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>



                    <div class="pnl_btn" align="center"><input class="btn_style1" type="submit" value="Continuar >"></div>
                </form>
                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

    public function comparteAventura_step3() {

        if ($_POST["action"] != "step3") {
            location("cuenta.php?cuenta=compartir");
        } else {
            unset($_POST["action"]);
        }

        for ($i = 0; $i < count($_POST["src_files"]); $i++) {
            $array[$i][0] = $_POST["src_files"][$i];
            $array[$i][1] = $_POST["titulo_files"][$i];
            $array[$i][2] = $_POST["descripcion_files"][$i];
            $array[$i][3] = $_POST["tipo_files"][$i];
        }

        $_SESSION["miaventura"]["archivos"] = $array;
        /*
          echo '<pre>';
          print_r($_SESSION["miaventura"]);
          echo '</pre>'; */
        ?>
        <div id="steps">
            <div id="titu_step">Comparte tu Aventura<span>(Paso 3 de 3)</span></div>
            <div id="panel_step" align="center">
                <p>Busca y/o mueve el pin rojo al lugar donde realizaste tu aventura y presiona el botón Finalizar.<br/> 
                    Puedes acercarte o alejarte usando los controles de la izquierda.</p>
                <br>
                <input type="text" name="origen" id="address" style="width: 400px" />
                <br>
                <form action="cuenta.php?cuenta=compartir" method="post">
                    <input type="hidden" value="step4" name="action">
                    <div id="mi_ubic" style="width: 802px;height: 393px;margin:30px auto 0 auto;"></div>
                    <input type="hidden" id="lat_pos" name="lat_pos"><input type="hidden" id="lng_pos" name="lng_pos">
                    <div class="pnl_btn" align="center"><input class="btn_style1" type="submit" value="FINALIZAR >"></div>
                </form>

                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

    public function comparteAventura_step4() {

        if ($_POST["action"] != "step4") {
            location("cuenta.php?cuenta=compartir");
        } else {
            unset($_POST["action"]);
        }

        $deporte = new Deporte($_SESSION["miaventura"]["idDeporte"]);
        $aventura = new Aventuras();
        $url_nuevo = _url_ . $aventura->url_Aventura($deporte->__get("_nombre_deporte"), $_SESSION["miaventura"]["id_aventura"], $_SESSION["miaventura"]['titulo']);
        ?>
        <div id="steps">
            <div id="titu_step">Comparte tu Aventura<span></span></div>
            <div id="panel_step">
                <div class="felicidades">
                    <div id="mensaje_termino">
                        <h1>Felicidades! Compartiste tu Aventura con el Mundo:</h1>
                        <p>y de paso ayudaste a difundir los deportes de aventura en el Perú.</p>
                        <br/>
                        <p>Tu Aventura se mostrará en tu Muro del Facebook y en el website DeAventura.pe en:</p>
                        <a href="<?php echo $url_nuevo ?>"><?php echo $url_nuevo ?></a>

                        <p>Si quieres puedes compartirlo en otras redes sociales:</p>
                        <br/>
                        <div class="socials">
                            <ul>
                                <li><div class="fb-like" data-href="<?php echo $url_nuevo ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></li>
                                <li><a class="twitter-share-button" href="https://twitter.com/share" data-url="<?php echo $url_nuevo ?>" data-text="Aventura" data-lang="es">Twittear</a>
                                    <script>!function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script></li>
                                <li><a class="pinterest" data-pin-config="beside" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url=<?php echo $url_nuevo ?>%2F&media=&description="><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></li>
                                <li><div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo $url_nuevo ?>"></div></li>
                                <li><a id="email_bt" href="mailto:"></a>   </li>
                            </ul>
                        </div>
                        <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-509974205a5b2732"></script>
                        <!-- AddThis Button END -->   
                    </div>
                </div>
                <div class="clear"></div>
            </div>

        </div>
        <?php
        unset($_SESSION["miaventura"]);
        unset($_SESSION['files_act']);
    }

    public function misAventuras_cuenta() {
        ?>
        <div id="steps">
            <div id="titu_step"><img src="aplication/webroot/imgs/icon_adv_b.jpg" width="16" height="16"> Mis Aventuras<span></span></div>
            <div id="panel_step">

                <?php
                $aventura = new Aventuras();
                $aventura->listAventuras_usuario($this->_cliente->__get("_id"));
                ?>

                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

    public function favoritos_cuenta() {
        ?>
        <div id="steps">
            <div id="titu_step"><img src="aplication/webroot/imgs/icon_star_b.png" width="16" height="16"> Mis Favoritos<span></span></div>
            <div id="panel_step">
                <?php
                $favoritos = new Favoritos();
                $favoritos->listFavoritos($this->_cliente->__get("_id"));
                ?>
                <div class="clear"></div>
            </div>

        </div>
        <?php
    }

}
?>
