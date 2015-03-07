<?php

class Eventos {

    private $_msgbox;
    private $_id_deporte;

    public function __construct(Msgbox $msg = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id_deporte;
    }

    public function newEventos() {
        ?>
        <form name="eventos" method="post" action="" enctype="multipart/form-data"> 
            <fieldset id="form">
                <legend> Nuevo Registro</legend>			


                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_eventos('add', '')">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_evento" id="titulo_evento" value="" size="59" maxlength="80"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar : </strong></label><input type="text" name="lugar_evento" value="" size="59" maxlength="80"></li> 
                    <li><label><strong> Fecha Evento: </strong></label><input type="text" name="fecha_evento" id="fecha_evento" size="12" class="date"></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_evento"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tagsd" id="tagsd" size="80" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                </ul>

            </fieldset>
            <br><br>
            <?php
            /* Para ver los tags */
//            $tags = new Tags();
//            $tags->viewTags_user("", "evento", "eventos");
//            ?>
            <br><br>
        </form>
        <?php
    }

    public function addEventos() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_evento_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(700, 700, $nombre);
        }
		$user = $_SESSION['usuario'];
		
        $fecha = explode("/", $_POST['fecha_evento']);
        $sql = "INSERT INTO eventos(id_cliente,titulo_evento, fecha_evento, fecha_publicacion_evento, lugar_evento, descripcion_evento, image,tags) 
                VALUES (null,
                   '" . clean_esp(htm_sql($_POST['titulo_evento'])) . "',
                   '" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "',
                   now(),
                   '" . addslashes($_POST['lugar_evento']) . "',
                   '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_evento']))) . "',
                   '" . $nombre . "',
				   '" . addslashes($_POST['tagsd'])."'
				   )";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_eventos(id_deporte,id_evento) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO eventos_tags(id_evento,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("eventos.php?");
    }

    public function editEventos() {
        $sql = "SELECT * FROM deportes_eventos INNER JOIN eventos USING(id_evento) WHERE id_evento = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();

        $time = explode("-", $row['fecha_evento']);
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="eventos" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_eventos('update', '<?php echo $_GET['id'] ?>')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_evento" id="titulo_evento" value="<?php echo sql_htm($row['titulo_evento']) ?>" size="59" maxlength="80"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar : </strong></label><input type="text" name="lugar_evento" value="<?php echo $row['lugar_evento'] ?>" size="59" maxlength="80"></li> 
                    <li><label><strong> Fecha Evento: </strong></label><input type="text" name="fecha_evento" id="fecha_evento" value="<?php echo $time[0] . '/' . $time[1] . '/' . $time[2] ?>" size="12" class="date"></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_eventos', 'id_evento', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_evento"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"><?php echo sql_htm($row['descripcion_evento']) ?></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tagsd" id="tagsd" size="80" value="<?php echo sql_htm($row['tags']) ?>" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../' . _url_evento_img_ . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br>
        <?php
        /* Para ver los tags */
//        $tags = new Tags();
//        $tags->viewTags_user($_GET["id"], "evento", "eventos");
//        ?>
        <br><br>
        <?php
    }

    public function updateEventos() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_evento_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(700, 700, $nombre);
            $image = ",image='" . $nombre . "'";
        }


        $anquery = new Consulta("DELETE FROM deportes_eventos WHERE id_evento = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_eventos(id_deporte,id_evento) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $fecha = explode("/", $_POST['fecha_evento']);
        $query = new Consulta("UPDATE eventos SET titulo_evento='" . clean_esp(htm_sql($_POST['titulo_evento'])) . "',
                            lugar_evento='" . addslashes($_POST['lugar_evento']) . "',
                            descripcion_evento='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_evento']))). "',
							tags = '".addslashes($_POST['tagsd'])."',
                            fecha_evento='" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "' 
                            " . $image . " WHERE id_evento = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la organización.', 2);
        location("eventos.php");
    }

    public function deleteEventos() {
        $this->deleteArchivo($_GET['id']);

        $query1 = new Consulta("DELETE FROM clientes_eventos WHERE id_evento = '" . $_GET['id'] . "'"); //Son los eventos por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM eventos WHERE id_evento = '" . $_GET['id'] . "'");
        $query3 = new Consulta("DELETE FROM eventos_tags WHERE id_evento = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("eventos.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM eventos WHERE id_evento = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_evento_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listEventosxDeporte($idDeporte) { //Para el combobox del select en listEventos
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM eventos ORDER BY  fecha_evento DESC";
        } else {
            $sql = "SELECT * FROM deportes_eventos INNER JOIN eventos USING(id_evento)
                    WHERE id_deporte = " . $idDeporte."
                    ORDER BY  fecha_evento DESC
                ";
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_evento'] . "|prod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['titulo_evento']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="eventos.php?id=<?php echo $rowp['id_evento'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('eventos.php', '<?php echo $rowp['id_evento'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listEventos() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_eventos") as $value) {
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                }
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('eventos')"/><br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Eventos</th> 
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM eventos ORDER BY fecha_publicacion_evento DESC limit 100";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_evento'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle"> 
                            <?php echo "<b>" . sql_htm($rowp['fecha_publicacion_evento']) . "</b>" ?> | 
                            <?php echo "<b>" . sql_htm($rowp['fecha_evento']) . "</b>" ?> | 
                            <?php echo "<b>" . sql_htm($rowp['titulo_evento']) . "</b>" ?>
                        </div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="eventos.php?id=<?php echo $rowp['id_evento'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('eventos.php', '<?php echo $rowp['id_evento'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                        </div>
                    </li>
                    <?php
                    $y++;
                }
                ?>
            </ul>
            <br class="clear" />
        </div>
        <?php
    }

    public function getEventos($actuales = FALSE) {
        if ($actuales) {
            $ext = "WHERE fecha_evento >= NOW() ";
        }

        $sql = "SELECT id_evento, titulo_evento, fecha_evento, nombre_deporte, eventos.image, lugar_evento,
(SELECT a.nombre_agencia FROM agencias AS a
 LEFT JOIN clientes_agencias AS ca ON a.id_agencia = ca.id_agencia
 WHERE ca.id_cliente = eventos.id_cliente LIMIT 1) AS nombre_agencia
                FROM eventos 
                INNER JOIN deportes_eventos USING(id_evento)
                INNER JOIN deportes USING(id_deporte) " . $ext ."
                GROUP BY id_evento
                ORDER BY fecha_evento ASC ";
        //echo $sql;
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_evento' => $row['id_evento'],
                    'titulo_evento' => sql_htm($row['titulo_evento']),
                    'fecha_evento' => $row['fecha_evento'],
                    'lugar_evento' => $row['lugar_evento'],
                    'nombre_deporte' => sql_htm($row['nombre_deporte']),
                    'imagen_evento' => $row['image'],
                    'nombre_agencia' => $row['nombre_agencia']
                );
            }
        }
        return $data;
    }

    /* PARTE USUARIO */

    public function listarEventos_usuario($tipo) {

        if ($tipo == 'pasados') {
            $tipo = '>';
        } else if ($tipo == 'futuros') {
            $tipo = '<';
        } else {
            $tipo = '>';
        }


        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $dia = array('Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miercoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sabado', 'Sunday' => 'Domingo');

        $sqq = "SELECT YEAR(fecha_evento) AS anio FROM deportes_eventos
                INNER JOIN eventos USING(id_evento)
                WHERE id_deporte = " . $this->_id_deporte . "
                GROUP BY anio DESC
                ORDER BY fecha_evento DESC";
        $query2 = new Consulta($sqq);

        while ($row = $query2->VerRegistro()) {
            $arr_anios[] = $row["anio"];
        }

        $dep = new Deporte($this->_id_deporte);
        $ms = "";
        $flag = true;
        for ($i = 0; $i < count($arr_anios); $i++) {
            for ($j = 0; $j <= 11; $j++) {
                $sqln = "SELECT *, DAYNAME(fecha_evento) AS dia 
                        FROM deportes_eventos INNER JOIN eventos USING(id_evento) 
                        WHERE id_deporte = " . $this->_id_deporte . " 
                            AND fecha_evento " . $tipo . " NOW() 
                            AND DATE_FORMAT( fecha_evento,  '%m' ) = " . ($j + 1) . " 
                            AND DATE_FORMAT( fecha_evento,  '%Y' ) = " . $arr_anios[$i]."
                        ORDER BY fecha_evento ASC";

                $query = new Consulta($sqln);
                while ($row = $query->VerRegistro()) {
                    $nfecha = explode("-", $row['fecha_evento']);
                    if ($flag == true) {
                        $ms.= '<div class="titulo_eve">' . $meses[$j] . ', ' . $arr_anios[$i] . '</div>';
                        $flag = false;
                    }
                    $link = 'eventos-de-' . url_friendly(sql_htm($dep->__get("_nombre_deporte")), 1) . '/' . url_friendly(sql_htm($row['titulo_evento']), 1);
                    $ms.= '<div class="eve_d"><div class="eve_fech">' . $dia[$row['dia']] . '<span>' . $nfecha[2] . '</span></div><div class="eve_titulo"><a href="' . $link . '">' . sql_htm($row['titulo_evento']) . '</a></div></div>';
                }
                $flag = true;
            }
        }
        return $ms;
    }
	
    public function getEventosUsuario($id){
        $sql = "SELECT id_evento, titulo_evento, fecha_evento, nombre_deporte, eventos.image
                FROM eventos 
                INNER JOIN deportes_eventos USING(id_evento)
                INNER JOIN deportes USING(id_deporte)
				WHERE id_cliente='".$id."'
				GROUP BY id_evento
                ORDER BY fecha_evento DESC ";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_evento' => $row['id_evento'],
                    'titulo_evento' => sql_htm($row['titulo_evento']),
                    'fecha_evento' => $row['fecha_evento'],
                    'nombre_deporte' => sql_htm($row['nombre_deporte']),
					'imagen_evento' => $row['image']
					
                );
            }
        }
        return $data;
	}
	
    public function url_Evento($deporte, $id, $titulo) {
        $url_aventura = 'eventos-de-' . url_friendly($deporte, 1) . '/' . url_friendly($titulo, 1);
        return $url_aventura;
    }
    
    
    /* del lado de la cuenta */
    
    	
   public function neweventos_cuenta(){
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $ndeportes = count($deportes);
        ?>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Crear Evento <a class="btn btn_nuevo" href="cuenta.php?cuenta=miseventos" title="Nuevo Evento">Regresar</a></div>
            <div id="panel_step">
                <form action="cuenta.php?cuenta=miseventos&action=add" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Categoría de Deporte:</label>
                            <select name="deporte" id="deporte">
                                <option value="">Selecciona Deporte</option>
                                <?php for($i=0;$i<$ndeportes;$i++){?>
                                <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                                <?php } ?>
                            </select></div>
                        <div class="rowElem"><label>Titulo del Evento:</label><input name="titulo_evento" id="titulo_evento" class="inputtext-large" type="text"></div>
                        <div class="rowElem"><label>Fecha del Evento:</label><input name="fecha_evento" id="fecha_evento" class="date" type="text" size="15" style="width:150px;"></div>
                        <div class="rowElem"><label>Lugar del Evento:</label><input name="lugar_evento" id="lugar_evento" type="text" size="50"></div>
                        <div class="rowElem"><label>Descripción del Evento:</label><textarea name="descripcion_evento" id="descripcion_evento"></textarea></div>
                        <div class="rowElem"><label>Tags:</label><input name="tagsd" id="tagsd" type="text" size="80"> <i>(caminatas, carreras, trek)</i></div>
                        <div class="rowElem">
                            <label><img src="aplication/webroot/imgs/icon_image.png"> SUBIR FOTO: </label>
                                <div class="custom-input-file"> <input type="file" name="image" id="image" class="input-file" /> + Subir foto... </div>​
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <hr color="#b1b1b1"/>
                    <div class="pnl_btn" align="center">
                    	<input class="cancel_blanck" type="reset" value="x Cancelar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    	<input class="btn btn_guardar" type="submit" value="Publicar Evento">
					</div>
                </form>
                <div class="clear"></div>
            </div>

        </div>
        <?php
    }
	
    public function addeventos_cuenta(Cliente $cliente){
        
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _imgs_prod_."image_eventos/");
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(800, 500, $nombre);
        }
        
        $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_evento'])));
        $sql = "INSERT INTO eventos(id_cliente,titulo_evento, fecha_evento, fecha_publicacion_evento, lugar_evento, descripcion_evento, image,tags) 
                VALUES ('".$cliente->__get("_id")."',
                   '" . clean_esp(htm_sql($_POST['titulo_evento'])) . "',
                   '" . $fecha . "',
                    now(),
                   '" . addslashes($_POST['lugar_evento']) . "',
                   '" . addslashes(utf8_decode(nl2br($_POST['descripcion_evento']))) . "',
                   '" . $nombre . "',
				   '" . $_POST['tagsd']. "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();
        $sql2 = "INSERT INTO deportes_eventos(id_deporte,id_evento) 
                        VALUES (
                        '" . $_POST['deporte'] . "',
                        '" . $id . "')";
        $query2 = new Consulta($sql2);

        //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("cuenta.php?cuenta=miseventos");
        
    }
    
    	
    public function editeventos_cuenta(){
        settype($_GET['id'],'int');
        $data = new Evento($_GET['id']);
        /*echo "<pre>";
        print_r($data);
        echo "</pre>";/**/
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $ndeportes = count($deportes);
        list($deporte)=explode(",",$data->_deportes);
        ?>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Editar Evento <a class="btn btn_nuevo" href="cuenta.php?cuenta=miseventos" title="Nuevo Evento">Regresar</a></div>
            <div id="panel_step">
                
                <form action="cuenta.php?cuenta=miseventos&action=update&id=<?php echo $data->_id_evento;?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Categoría de Deporte:</label><select name="deporte" id="deporte">
                        <?php for($i=0;$i<$ndeportes;$i++){?>
                            <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                        <?php }?>
                        </select><script>document.getElementById("deporte").value = '<?php echo $deporte;?>'; </script></div>
                        <div class="rowElem"><label>Titulo del Evento:</label><input name="titulo_evento" class="inputtext-large" id="titulo_evento" type="text" value="<?php echo $data->_titulo_evento;?>"/></div>
                        <div class="rowElem"><label>Fecha del Evento:</label><input name="fecha_evento" id="fecha_evento" class="date" type="text" size="15" style="width:150px;" value="<?php echo implode("-",array_reverse(explode("-",$data->_fecha_evento)));?>">&nbsp;&nbsp;<img src="aplication/webroot/imgs/icon_cal2.png" /></div>
                        <div class="rowElem"><label>Lugar del Evento:</label><input name="lugar_evento" id="lugar_evento" type="text" size="23" value="<?php echo $data->_lugar_evento;?>"></div>
                        <div class="rowElem"><label>Descripción del Evento:</label><textarea name="descripcion_evento" id="descripcion_evento"><?php echo br2nl($data->_descripcion_evento);?></textarea></div>
                        <div class="rowElem"><label>Tags:</label><input name="tagsd" id="tagsd" type="text" size="80" value="<?php echo $data->_tags;?>"  /> <i>(caminatas, carreras, trekking)</i></div>
                        <div class="rowElem">
                            <label><img src="aplication/webroot/imgs/icon_image.png"> SUBIR FOTO: </label>
                                <div class="custom-input-file">
                                    <input type="file" name="image" id="image" class="input-file" />
                                    + Subir foto..
                                </div>​
                            <div class="clear"></div>
                            <div align="center">
                            	<img src="<?php echo  _imgs_prod_."image_eventos/".$data->_imagen_evento;?>" />
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <hr color="#b1b1b1"/>
                    <div class="pnl_btn" align="center">
                    	<input class="cancel_blanck" type="reset" value="x Cancelar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    	<input class="btn btn_guardar" type="submit" value="Guardar Evento >">
					</div>
                </form>
                <div class="clear"></div>
            </div>

        </div>
        <?php
	}
	
	public function updateeventos_cuenta(Cliente $cliente){
		$nombre = "";
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $ext = explode('.', $_FILES['image']['name']);
                $nombre_file = time() . sef_string($ext[0]);
                $type_file = typeImage($_FILES['image']['type']);
                $nombre = $nombre_file . $type_file;

                define("NAMETHUMB", "/tmp/thumbtemp");
                $thumbnail = new ThumbnailBlob(NAMETHUMB, _imgs_prod_."image_eventos/");
                $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
                $datos = $thumbnail->CreateThumbnail(412, 275, $nombre);
                            $image = ",image='" . $nombre . "'";
            }


            $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_evento'])));	
            $anquery = new Consulta("DELETE FROM deportes_eventos WHERE id_evento = '" . $_GET['id'] . "'");
            $sql2 = "INSERT INTO deportes_eventos(id_deporte,id_evento) 
                    VALUES (
                       '" . $_POST['deporte'] . "',
                       '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);

            $query = new Consulta("UPDATE eventos SET titulo_evento='" . clean_esp(htm_sql($_POST['titulo_evento'])) . "',
                                lugar_evento='" . addslashes($_POST['lugar_evento']) . "',
                                descripcion_evento='" . addslashes(utf8_decode(nl2br($_POST['descripcion_evento']))). "',
                                tags='" . addslashes($_POST['tagsd']) . "',
                                fecha_evento='" . $fecha . "' 
                                " . $image . " WHERE id_evento = '" . $_GET['id'] . "' AND id_cliente = '".$cliente->__get("_id")."'");


                    //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
                    location("cuenta.php?cuenta=miseventos");
	}


    
    public function deleventos_cuenta(Cliente $cliente) {
        $query1 = new Consulta("DELETE FROM clientes_eventos WHERE id_evento = '" . $_GET['id'] . "'"); //Son los eventos por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM eventos WHERE id_evento = '" . $_GET['id'] . "' AND id_cliente='".$cliente->__get("_id")."'");
        $query3 = new Consulta("DELETE FROM eventos_tags WHERE id_evento = '" . $_GET['id'] . "'");

        //$this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("cuenta.php?cuenta=miseventos");
    }
    
    public function miseventos_cuenta(Cliente $cliente){
         
        $eventos = $this->getEventosUsuario($cliente->__get("_id"));
        $neventos = count($eventos);
        $aventura = new Aventuras();
		?>
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span> Mis Eventos <a class="btn btn_nuevo" href="cuenta.php?cuenta=miseventos&action=new" title="Nuevo Evento">Nuevo Evento</a></div>
            <div id="panel_step">
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha</th>
                          <th>Tipo</th>
                          <th>Actividad</th>
                          <th>Opciones</th>
                        </tr>
                    </thead>
                <?php 
                    for($i = 0; $i < $neventos; $i++){
//                        if(file_exists('aplication/webroot/imgs/catalogo/image_eventos/'.$eventos[$i]['imagen_evento'])){
//                            $imge = $eventos[$i]['imagen_evento'];
//                        }else{
//                            $imge = 'sin_imagen.png';
//                        }
                        $link = _url_ . $this->url_Evento($eventos[$i]["nombre_deporte"],$eventos[$i]['id_evento'], $eventos[$i]['titulo_evento']); ?>
                    
                      <tr>
                          <td> <?php echo $eventos[$i]['id_evento'];?>  
<!--                              <a href="cuenta.php?cuenta=miseventos&action=edit&id=<?php echo $eventos[$i]['id_evento']?>" class="btn-circle btn-estado glyphicon glyphicon-ok" title="Editar Evento"> </a>-->
                          </td>
                          <td class="fecha"><?php echo formato_slash("-",$eventos[$i]['fecha_evento']);?></td>
                          <td> <?php echo $eventos[$i]['nombre_deporte'];?> </td>
                        <td><?php echo $eventos[$i]['titulo_evento'];?> <a href="<?php echo $link;?>" target="_blank" class="href"><img src="aplication/webroot/imgs/icon_mas.jpg" /></a></td>    
                        <td>
                            
<!--                            <a href="cuenta.php?cuenta=miseventos&action=edit&id=<?php echo $eventos[$i]['id_evento']?>" class="btn-circle btn-registro glyphicon glyphicon-th-list" title="Inscritos"> </a>
                            <a href="cuenta.php?cuenta=miseventos&action=edit&id=<?php echo $eventos[$i]['id_evento']?>" class="btn-circle btn-reporte glyphicon glyphicon-stats" title="Estadistica"> </a>-->
                            <a href="cuenta.php?cuenta=miseventos&action=edit&id=<?php echo $eventos[$i]['id_evento']?>" class="btn-circle btn-edit glyphicon glyphicon-pencil" title="Editar"> </a>
                            <a href="cuenta.php?cuenta=miseventos&action=del&id=<?php echo $eventos[$i]['id_evento']?>" class="btn-circle btn-delete glyphicon glyphicon-remove" title="Eliminar"></a>
                        </td>
                      </tr>
                        
                    <?php }?>
               </table>
            	<div class="clear"></div>
            </div>
        </div>
        
        <?php
    }
    
    public function solicitar_eventos_cuenta(Cliente $cliente) {

        $sql_cliente = " SELECT * FROM clientes WHERE id_cliente = '" . $cliente->__get("_id") . "' ";
        $queryCliente = new Consulta($sql_cliente);
        $row = $queryCliente->VerRegistro();
        
        $msg = "
Estimados Srs. 
            
Solicito publicación de eventos en su plataforma DE AVENTURA:
            
Nombres: ".$row["nombre_cliente"].$row["apellidos_cliente"]."
Correo: ".$row["email_cliente"]."
    
---
Sistema De Aventura
www.deaventura.pe

";
        @mail("admin@deaventura.pe","Solicitud de Publicación de Eventos",$msg);
        
        ?>
        <div id="steps">
            <section id="panel_step">
                <section class="bienvenido_left"><img src="aplication/webroot/imgs/bienvenido-aventurero.jpg"/></section>
                <section class="bienvenido_right">
                    <h2>Hemos recibido tu solicitud para la publicación de eventos en nuestra plataforma.</h2>
                    <h4>En Breve validaremos tu solicitud y nos podremos en contacto contigo mediante un email con información de acceso.  </h4><br></br>
                    <article class="bienvenido_texto_cuerpo">Mientras tanto de invitamos a seguir publicando tus aventuras:</article><br>
                    <article><a class="bienvenido_btn" title="Comparte tu Aventura" href="cuenta.php?cuenta=compartir">Compartir Aventura</a></article>
                    
                    
                </section>
                
                
<!--                <form action="cuenta.php?cuenta=misdatos" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <ul id="div_input1">
                        <li class="rowElem"><label>Trekking</label> <input type="checkbox" name="delete" value="1" class="toggle" /> </li>
                            
                        <div class="clear"></div>
                    </ul>
                    <div class="pnl_btn" align="center"><input class="btn_style1" type="submit" value="Mis Datos"></div>
                </form>-->
                 
            </section>

        </div>
        <?php
    }
    
    
    
    

}
?>
