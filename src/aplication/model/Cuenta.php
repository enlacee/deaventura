<?php

class Cuenta extends MainModel {

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
        unset($_SESSION["aventurak"]);
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
        echo '<script>window.location = "/"</script>';
    }

    /**
     * Registrar Cuenta de Facebook
     */
    public function cuentaAdd ($uid, $name, $lastname, $sexo, $email, $link, $fecha_nacimiento) {
        if ($uid != "" && $name != "" && $lastname != "" && $sexo != "" && $email != "" && $link != "") {
            $sql = "INSERT INTO clientes (id_facebook_cliente,nombre_cliente,apellidos_cliente,fecha_registro_cliente,sexo_cliente,email_cliente,url_cliente,tipo_cliente,fecha_nacimiento_cliente) ". "VALUES('" . $uid . "','" . $name . "','" . $lastname . "',NOW(),'" . $sexo . "','" . $email . "','" . $link . "',0,'".$fecha_nacimiento."')";
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
        } else {
            return false;
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
            $this->_cliente->__set("_tipo_foto",$row['tipo_foto_cliente']);
            $this->_cliente->__set("_sexo", $row["sexo_cliente"]);
            $this->_cliente->__set("_email", $row["email_cliente"]);
            $this->_cliente->__set("_url", $row["url_cliente"]);
            $this->_cliente->__set("_tipo_usuario", $row["tipo_cliente"]);
            
            $sql_agencia = "SELECT * FROM clientes_agencias WHERE id_cliente = '".$row["id_cliente"]."'";
            $query_agencia = new Consulta($sql_agencia);
            
            if($query_agencia->NumeroRegistros() > 0){
                $row_agencia = $query_agencia->VerRegistro();  
                $obj_agencia = new Agencia($row_agencia["id_agencia"]);
                $this->_cliente->__set("_agencia",$obj_agencia) ;
            }
            
        }
    }

    public function get_registroActividades() {
        $sql = new Consulta("SELECT ra.id_aventura, ra.id_cliente, nombre_cliente, id_facebook_cliente, image, tipo_foto_cliente, estado_registro_actividad, fecha_registro_actividad FROM registro_actividad ra 
            INNER JOIN clientes c ON ra.id_cliente = c.id_cliente ORDER BY fecha_registro_actividad DESC LIMIT 0,20");
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

    /**
     * Update accound perfil (tab1)
     * @return void
     */
    public function cuentaUpdate() {

        $sql = "UPDATE clientes SET 
                    nombre_cliente='" . addslashes($_POST['name']) . "',
                    apellidos_cliente='" . addslashes($_POST['lastname']) . "',
                    sexo_cliente='" . $_POST['sexo'] . "',
                    tipo_foto_cliente='" . $_POST['foto'] . "',
                    email_cliente='" . addslashes($_POST['email']) . "',
                    fecha_nacimiento_cliente = '". $_POST['fecha_nacimiento_cliente'] ."',
                    vivo_en = '". $_POST['vivo_en'] ."',
                    telefono = '". $_POST['telefono'] ."',
                    describete = '". $_POST['describete'] ."'
                    
                WHERE id_cliente='" . $this->_cliente->__get("_id") . "' ";

        $query = new Consulta($sql);
        // location("cuenta.php?cuenta=misdatos");
    }
    
    /**
     * Update accound perfil (tab2 : data deporte)
     * @return void
     */    
    public function cuentaUpdateTab2() {
    
        $deporteFavorito = $this->_formatedDeporteFavorito($_POST['deporte_favorito']);
        
        $sql = "UPDATE clientes SET 
                    deporte_desde = '" . ($_POST['deporte_desde']) . "',
                    deporte_favorito='" . ($deporteFavorito) . "',
                    deporte_equipo_que_utilizo = '" . $_POST['deporte_equipo_que_utilizo'] . "'                    
                WHERE id_cliente='" . $this->_cliente->__get("_id") . "' ";

        $query = new Consulta($sql);
    }
    
    /**
     * Update accound perfil (tab3 : data image)
     * @return void
     */    
    public function cuentaUpdateTab3() {
       
        $urlImage = $_POST['myCuentaFilePathServer'];
        
        if (!empty($urlImage)) {
            $urlImage = urldecode($urlImage);
            $arrayImage = explode(_url_, $urlImage);
            $pathImage = $arrayImage[1];
            
            if (strpos($pathImage, '/',0) >= 0 ) {
                $pathImage = substr($pathImage, 1,(strlen($pathImage)));
            }

            // step 02
            $meImage = $this->config()->server->host . $pathImage;
            $meNewImage = _host_files_users_;
            $flag = false;
            if (is_file($meImage)) {
                $ext = pathinfo($meImage, PATHINFO_EXTENSION);
                $baseName = basename($meImage, '.'.$ext);
                $nombre_file = time() . sef_string($baseName) . '.' . $ext;

                //$filePathOut = str_replace("$baseName.$ext", $nombre_file, $meImage);
                $filePathOut = $meNewImage . $nombre_file;
                // create and delete image
                ThumbnailBlob::makeThumbnails($meImage, $filePathOut, 200, 200);
                unlink($meImage);
                $flag = $nombre_file;
            }
            
            // step 03
            if ($flag) {
                $sql = "UPDATE clientes SET image = '{$flag}'                  
                        WHERE id_cliente='" . $this->_cliente->__get("_id") . "' ";
                $query = new Consulta($sql);
            }
            
        }
        
        
    }
    

    
    /*
     * format (string to array)
     * REGEX (,) 'texto 1, texto 2'
     * @return mix (false or array)
     */
    private function _formatedEquipoQueUtilizo($string) {
        
        $newArray = false;
        if (!empty($string)) {
            if (preg_match('#,#', $string)) {
                $newArray = preg_split('#,#', $string);
            }
        }

        return $newArray;
    }
    
    /*
     * Format data to serial
     * @return Array|Boolean
     */
    private function _formatedDeporteFavorito($array) {
        $dataSerial = false;
        if (!empty($array) && count($array > 0)) {
            $dataSerial = serialize($array);
        }
        
        return $dataSerial;
    }
    
    /*
     * Validate and return array data accounts (perfil)
     */
    private function _getExtraFieldsCuenta($post) {
        
        $data = array();
        if (is_array($post) && count($post) > 0) {
            
            foreach ($post as $key => $value) {
                if (!empty($post[$key]) == 'describete') {
                    $data[$key] = $value;
                }
            }
        }
        
        return $data;
        
    }

    public function bienvenido_cuenta() {

        $sql_cliente = " SELECT * FROM clientes WHERE id_cliente = '" . $this->_cliente->__get("_id") . "' ";
        $queryCliente = new Consulta($sql_cliente);
        $row = $queryCliente->VerRegistro();  ?>
        <div id="steps">
            <section id="panel_step">
                <section class="bienvenido_left">
                    <h2>Bienvenido a: </h2>
                    <img src="aplication/webroot/imgs/logo-sticker-mediano.jpg" class="img-responsive"/>
                     </section>
                <section class="bienvenido_right">
                    
                    <h3> Hola <?php echo $row['nombre_cliente'] ?>, empieza a participar:</h3>
                    <article class="bienvenido_texto_cuerpo">Maneja tu perfil de aventurero.</article>
                    <article><a class="bienvenido_btn btn" title="Mis Datos" href="cuenta.php?cuenta=misdatos"> Mis Datos</a></article></br>
                    <article class="bienvenido_texto_cuerpo">Comparte tus experiencias, fotos o videos con otros aventureros.</article>
                    <article><a class="bienvenido_btn btn" title="Comparte tu Aventura" href="cuenta.php?cuenta=compartir">Compartir Aventura</a></article></br>
                    <article class="bienvenido_texto_cuerpo">Si eres una agencia u organización que organiza eventos.</article>
                    <article><a class="bienvenido_btn btn" title="Solicitar Publicar Eventos" href="cuenta.php?cuenta=solicitar-eventos">Solicita Publicar Eventos</a></article></br>
                    
                    
                </section>
            </section>

        </div>
        <?php
    }
    
    
    public function dashboard_cuenta() {
//        echo "<pre>";
//        print_r($this->_cliente);
//        echo "<pre>";
        $sql_cliente = " SELECT * FROM clientes WHERE id_cliente = '" . $this->_cliente->__get("_id") . "' ";
        $queryCliente = new Consulta($sql_cliente);
        $row = $queryCliente->VerRegistro();  ?>
        <div id="steps">
            <?php
                $tipo2 = $this->_cliente->__get('_tipo_foto');
                $foto_perfil = "";
                if ($tipo2 == 'F') { 
                  $foto_perfil  = "https://graph.facebook.com/". $this->_cliente->__get('_idFacebook')."/picture"; 
                } else if ($tipo2 == 'C') {  //90 90     
                  $foto_perfil = _url_files_users_ . $this->_cliente->__get('_foto'); 
                } ?>
                </br>
                <h2>
                    <span class="member_image thumb_32" style="background-image: url('<?php echo $foto_perfil; ?>')"></span>
                    Hola <?php echo $row['nombre_cliente'] ?>!
                </h2> </br>
                <div class="card home_card clearfix">

                    <a href="cuenta.php?cuenta=misdatos" class="plastic_row">
                                <span class="glyphicon glyphicon-chevron-right chevron"></span>
                                <span class="glyphicon glyphicon-user clear_blue_bg icono_glyphicon"></span>
                                <h3>Mi Cuenta</h3>
                                <span class="description">Edita tu perfil, actualiza tu nombre y foto, y gestiona otras configuraciones de tu cuenta.</span>
                        </a>
                        <a href="cuenta.php?cuenta=misAventuras" class="plastic_row">
                                <span class="glyphicon glyphicon-chevron-right chevron"></span>
                                <span class="glyphicon glyphicon-picture yolk_orange_bg icono_glyphicon"></span>
                                <h3>Aventuras</h3>
                                <span class="description">Comparte tus historias de aventura, con fotos y videos.</span>
                        </a>
                        <a href="cuenta.php?cuenta=favoritos" class="plastic_row">
                                <span class="glyphicon glyphicon-chevron-right chevron"></span>
                                <span class="glyphicon glyphicon-heart-empty candy_red_bg icono_glyphicon"></span>
                                <h3>Favoritos</h3>
                                <span class="description">Revisa tus favoritos, todo lo que te has marcado como favorito.</span>
                        </a>

                </div>
                 <?php //if($this->_cliente->__get("_tipo_usuario")=='1'){ ?>
                      
                <div class="card home_card clearfix">

                    <a href="cuenta.php?cuenta=miseventos" class="plastic_row">
                        <span class="glyphicon glyphicon-chevron-right chevron"></span>
                        <span class="glyphicon glyphicon-calendar green_bg icono_glyphicon"></span>
                        <h3>Eventos</h3>
                        <span class="description">Publica tus eventos, salidas y paquetes de aventura en nuestra plataforma.</span>
                    </a>
                    <?php
                    if($this->_cliente->__get("_tipo_usuario") == '1' && $this->_cliente->__get("_agencia")){ 
                    ?>
                    <a href="cuenta.php?cuenta=agencia" class="plastic_row">
                        <span class="glyphicon glyphicon-chevron-right chevron"></span>
                        <span class="glyphicon glyphicon-ok-circle violet_bg icono_glyphicon"></span>
                        <h3>Mi Agencia</h3>
                        <span class="description">Gestiona la información de tu agencia de aventura.</span>
                    </a>
                    <?php } ?>
                    
<!--                     <a href="cuenta.php?cuenta=mispaquetes" class="plastic_row">
                        <span class="glyphicon glyphicon-chevron-right chevron"></span>
                        <span class="glyphicon glyphicon-lock yolk_orange_bg icono_glyphicon"></span>
                        <h3>Gestiona tus Paquetes</h3>
                        <span class="description">Sube tus Paquetes a nuestra plataforma.</span>
                    </a>
                       <a href="cuenta.php?cuenta=miseventos" class="plastic_row">
                        <a href="cuenta.php?cuenta=favoritos" class="plastic_row">
                                <span class="glyphicon glyphicon-chevron-right chevron"></span>
                                <span class="glyphicon glyphicon-heart-empty candy_red_bg icono_glyphicon"></span>
                                <h3>Tus Favoritos</h3>
                                <span class="description">Revisa tus favoritos, todo lo que te has marcado como favorito.</span>
                        </a> -->

                </div>
                <?php //}  ?>
        </div>
        <?php
    }
    

	    
    /*
     * Vew data (perfil aventurero)
     */
    public function misdatos_cuenta() {
         
        $cache = $this->getConfigCache();        
        
        $sql_cliente = " SELECT * FROM clientes WHERE id_cliente = '" . $this->_cliente->__get("_id") . "' ";
        $queryCliente = new Consulta($sql_cliente);
        $row = $queryCliente->VerRegistro();
        
        $idCache = "list_sports";
        $listSports = $cache->get($idCache);
        
        if($listSports == null) {
            $sql = " SELECT id_deporte, nombre_deporte FROM deportes; ";
            $query = new Consulta($sql);
            $listSports = array();
            $cnt = 0;
            while ($data = $query->VerRegistro()) { 
                $listSports[$cnt]['id_deporte'] = $data['id_deporte'];
                $listSports[$cnt]['nombre_deporte'] = $data['nombre_deporte'];
                $cnt++;
            }
            $cache->set($idCache, $listSports , MainModel::CACHE_TIME);
        }
        
        $listSports = $this->_checkOutDeporteFavorito($listSports, $row['deporte_favorito']);

        $includeFile = $this->config()->server->host . 'views/cuenta/mis-datos-cuenta.php';
        if (is_file($includeFile)) {
            include_once $includeFile;
        }

    }
    
    /*
     * Get deport favorite including to flag in result array ['checked'] = true
     * @return Array data refactor array
     */
    private function _checkOutDeporteFavorito($listSports, $dataDeporteFavorito) {
        $df = unserialize($dataDeporteFavorito);        
        $rs = false;
        if (is_array($listSports) && count($listSports > 0)
                && !empty($df) ) {
            
            foreach ($df as $key => $value) {
                foreach ($listSports as $indice => $valor) {
                    if ($df[$key] == $listSports[$indice]['id_deporte']) {
                        $listSports[$indice]['checked'] = true;
                        break;
                    }
                }
            }
            
            $rs = $listSports;
        } else {
           $rs = $listSports; 
        }
        
        return $rs;
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
             <div class="alert alert-warning" role="alert" style="font-size:86%">Por favor toma en cuenta lo siguiente: una aventura es una experiencia o historia pasada que haz realizado y quieres compartirlo, puedes ver nuestra sección <a href="aventuras" target="_blank">Aventuras</a>, si no es así tu aventura no sera mostrado, si lo que realmente deseas es publicar un evento entonces haz click <a href="cuenta.php?cuenta=miseventos&action=new">aquí.</a> </div>
            
            <div id="titu_step"><span class="glyphicon glyphicon-picture"></span> Comparte tu Aventura<span>(Paso 1 de 3)</span>  <a class="btn btn_nuevo" href="cuenta.php?cuenta=misAventuras" title="Nueva aventura">Regresar</a></div>    
           <div id="panel_step">
                
                <form action="cuenta.php?cuenta=compartir" method="post" enctype="multipart/form-data" accept-charset="utf-8" 
                      name="form_step1" id="fileupload" onsubmit="return validate_step1(this, 'step2')">
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
                        <div class="rowElem"><label>Elegir Agencia (opcional):</label>
                            <select name="cbo_agencias" id="cbo_agencias">
                                <option value="0">Elegir Agencia ...</option>
                            </select>
                        </div>                        
                        <div class="rowElem"><label>Título de tu Aventura:</label><input name="titulo" id="titulo" type="text" size="23" maxlength="45"/>
                        <span style="color:red; font-size:11px">(Solo números y letras)</span></div>
                        <div class="rowElem"><label>Lugar, Provincia y Dpto.:</label><input name="lugar" id="lugar" type="text" size="30" maxlength="45">
                        <span style="color:red; font-size:11px">(Solo números y letras)</span></div>
						<div style="display:table;width: 80%;"> 
                            <div style="float: left;">
                                <label style="width: 200px;">Descripción de la aventura:</label>
                            </div>
                            <div style="float: right;">
                                <textarea type="text" name="descripcion" id="descripcion" rows="5" style="width: 567px;height: 378px;"></textarea>
                            </div>
                        </div> 
						<!--
                        <div class="rowElem"><label>Descripción de la aventura:</label><textarea name="descripcion" id="descripcion" rows="5"></textarea>	
							
						<div style="display:inline-block;width: 150px;height: 400px;">

                                <div style="display:table;height: 100%;">    
                                   <div style="display: table-cell;vertical-align: middle;color: red;font-size: 11px;">
                                        (Solo se admiten números, letras  y separadores como: comas (,) , punto y coma (;) , dos puntos (:) , punto (.) , guion (-) , y parentesis () )
                                   </div>
                                </div>

                            </div>
							-->
							
                        </div> 
                        <div class="rowElem"> <label>Video YOUTUBE:</label><input id="video_txt" type="text" size="30" placeholder="Ejm: http://www.youtube.com/watch?v=H542nLTTbu0">
<!--                                    <input id="btn_svideo" class="btn btn_subir_archivo" type="button" value="Añadir Video"> <i>(Si lo tuvieras)</i>-->
                                </div><br></br>
                        <div class="rowElem fileupload-container">
                            <noscript><input type="hidden" name="redirect" value="www.deaventura.pe"></noscript>
                            <div class="container">
                                
                                <div class="rowElem">
                                    <label>SUBIR TUS FOTOS:</label>
                                    <div class="btn row fileupload-buttonbar btn_subir_archivo"style="display:inline-block;vertical-align: bottom; margin-top: 5px ">
                                        <div class="span7">
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="fileinput-button">
                                                <span class="icon-plus icon-white"></span>
                                                <span> Escoger Fotos</span>
                                                <input type="file" name="files[]" multiple>
                                            </span>
                                            <button type="submit" class="btn btn-primary start">
                                                <i class="icon-upload icon-white"></i>
                                                <span>Start upload</span>
                                            </button>
                                        </div>
                                    </div>
									<span style="color:red; font-size:11px;margin-left: 20px;">(El número máximo de fotos permitidos: 10)</span>                                    
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
                        <div class="clear"></div>
                    </div>

                    <div class="pnl_btn" align="center">

                        <input class="glyphicon glyphicon-floppy-disk btn btn_guardar" type="submit" value="Subir y Continuar">
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
           
            <div id="panel_step">
                 <div id="titu_step">Comparte tu Aventura<span>(Paso 2 de 3)</span></div>
                <p>Si quieres ponle título a tu foto o video. También puedes cambiar el orden seleccionando la imagen y moviéndola donde quieras:</p>
                <!--<form action="cuenta.php?cuenta=compartir" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_step2" id="form_step2" onsubmit="return validate_step2(this)">-->
				<form action="cuenta.php?cuenta=compartir" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_step2" id="form_step2" ">
                    <input type="hidden" value="step3" name="action">
                    <ul id="list_item">
                        <?php
						
                        if (count($array_img) > 0) {
                            foreach ($array_img as $valor){
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
                                        <a href="http://www.youtube.com/watch?v=<?php echo $valor2 ?>" target="_blank"><img src="<?php echo _imgs_?>icon_video_ver.jpg"></a>
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



                    <div class="pnl_btn" align="center"><input class="btn btn_guardar" type="submit" value="Continuar"></div>
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
            
            <div id="panel_step" align="center">
                <div id="titu_step">Comparte tu Aventura<span>(Paso 3 de 3)</span></div>
                <p>Busca y/o mueve el pin rojo al lugar donde realizaste tu aventura y presiona el botón Finalizar.<br/> 
                    Puedes acercarte o alejarte usando los controles de la izquierda.</p>
                <br>
                <input type="text" name="origen" id="address" style="width: 400px;border:1px solid #cecece; padding:3px" />
                <br>
                <form action="cuenta.php?cuenta=compartir" method="post">
                    <input type="hidden" value="step4" name="action">
                    <div id="mi_ubic" style="width: 802px;height: 393px;margin:30px auto 0 auto;"></div>
                    <input type="hidden" id="lat_pos" name="lat_pos"><input type="hidden" id="lng_pos" name="lng_pos">
                    <div class="pnl_btn" align="center"><input class="btn btn_guardar" type="submit" value="FINALIZAR"></div>
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
            <div id="panel_step">
                <div id="titu_step">Comparte tu Aventura<span></span></div>
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
            <div id="titu_step"> <span class="glyphicon glyphicon-picture"></span> Mis Aventuras   <a class="btn btn_nuevo" href="cuenta.php?cuenta=compartir" title="Nueva aventura">Nueva Aventura</a></div>
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