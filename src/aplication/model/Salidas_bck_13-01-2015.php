<?php

class Salidas {

    private $_msgbox;
    private $_id_deporte;

    public function __construct(Msgbox $msg = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id_deporte;
    }


    public function addSalidas() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_salida_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(700, 700, $nombre);
        }
		$user = $_SESSION['usuario'];
		
        $fecha = explode("/", $_POST['fecha_salida']);
        $sql = "INSERT INTO salidas(id_cliente,titulo_salida, fecha_salida, lugar_salida, descripcion_salida, image,tags) 
                VALUES (null,
                   '" . clean_esp(htm_sql($_POST['titulo_salida'])) . "',
                   '" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "',
                   '" . addslashes($_POST['lugar_salida']) . "',
                   '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_salida']))) . "',
                   '" . $nombre . "',
				   '" . addslashes($_POST['tagsd'])."'
				   )";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_salidas(id_deporte,id_salida) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO salidas_tags(id_salida,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("salidas.php?");
    }

    public function editSalidas() {
        $sql = "SELECT * FROM deportes_salidas INNER JOIN salidas USING(id_salida) WHERE id_salida = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();

        $time = explode("-", $row['fecha_salida']);
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="salidas" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_salidas('update', '<?php echo $_GET['id'] ?>')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_salida" id="titulo_salida" value="<?php echo sql_htm($row['titulo_salida']) ?>" size="59" maxlength="50"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar : </strong></label><input type="text" name="lugar_salida" value="<?php echo $row['lugar_salida'] ?>" size="59" maxlength="45"></li> 
                    <li><label><strong> Fecha Evento: </strong></label><input type="text" name="fecha_salida" id="fecha_salida" value="<?php echo $time[0] . '/' . $time[1] . '/' . $time[2] ?>" size="12" class="date"></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_salidas', 'id_salida', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_salida"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"><?php echo sql_htm($row['descripcion_salida']) ?></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tagsd" id="tagsd" size="80" value="<?php echo sql_htm($row['tags']) ?>" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../' . _url_salida_img_ . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br>
        <?php
        /* Para ver los tags */
//        $tags = new Tags();
//        $tags->viewTags_user($_GET["id"], "evento", "salidas");
//        ?>
        <br><br>
        <?php
    }

    public function updateSalidas() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_salida_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(700, 700, $nombre);
            $image = ",image='" . $nombre . "'";
        }


        $anquery = new Consulta("DELETE FROM deportes_salidas WHERE id_salida = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_salidas(id_deporte,id_salida) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $fecha = explode("/", $_POST['fecha_salida']);
        $query = new Consulta("UPDATE salidas SET titulo_salida='" . clean_esp(htm_sql($_POST['titulo_salida'])) . "',
                            lugar_salida='" . addslashes($_POST['lugar_salida']) . "',
                            descripcion_salida='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_salida']))). "',
							tags = '".addslashes($_POST['tagsd'])."',
                            fecha_salida='" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "' 
                            " . $image . " WHERE id_salida = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la organización.', 2);
        location("salidas.php");
    }

    public function deleteSalidas() {
        $this->deleteArchivo($_GET['id']);

        $query1 = new Consulta("DELETE FROM clientes_salidas WHERE id_salida = '" . $_GET['id'] . "'"); //Son los salidas por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM salidas WHERE id_salida = '" . $_GET['id'] . "'");
        $query3 = new Consulta("DELETE FROM salidas_tags WHERE id_salida = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("salidas.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM salidas WHERE id_salida = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_salida_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }
 
    
    

    public function listSalidasAdmin() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_salidas") as $value) {
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                }
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('salidas')"/><br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Salidas</th> 
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM salidas ORDER BY fecha_salida DESC limit 100";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_salida'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle"> 
                            <?php echo "<b>" . sql_htm($rowp['fecha_salida']) . "</b>" ?> /
                            <?php echo "<b>" . sql_htm($rowp['titulo_salida']) . "</b>" ?>
                        </div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="salidas.php?id=<?php echo $rowp['id_salida'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('salidas.php', '<?php echo $rowp['id_salida'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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

    public function getSalidas($actuales = FALSE) {
        if ($actuales) {
            $ext = "WHERE fecha_salida >= NOW() ";
        }

        $sql = "SELECT id_salida, titulo_salida, fecha_salida, nombre_deporte, salidas.image, lugar_salida
                FROM salidas 
                INNER JOIN deportes_salidas USING(id_salida)
                INNER JOIN deportes USING(id_deporte) " . $ext ."
                GROUP BY id_salida
                ORDER BY fecha_salida ASC ";
        //echo $sql;
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_salida' => $row['id_salida'],
                    'titulo_salida' => sql_htm($row['titulo_salida']),
                    'fecha_salida' => $row['fecha_salida'],
                    'lugar_salida' => $row['lugar_salida'],
                    'nombre_deporte' => sql_htm($row['nombre_deporte']),
                    'imagen_salida' => $row['image']
                );
            }
        }
        return $data;
    }

    	
    public function getSalidasCuenta($id_cliente){
        $sql = "SELECT id_actividad_salida, nombre_actividad, fecha_actividad, lugar_actividad, nombre_tipo_actividad, ( SELECT COUNT(i.id_inscripcion)
                                                                                                                            FROM inscripciones i  WHERE i.id_actividad = actividades.id_actividad
                                                                                                                    ) AS inscritos
                FROM actividades_salidas s
                INNER JOIN actividades USING(id_actividad) 
                INNER JOIN tipos_actividades USING(id_tipo_actividad) 
                    WHERE id_cliente='".$id_cliente."'
                    GROUP BY id_actividad_salida
                ORDER BY fecha_actividad DESC ";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_salida'     => $row['id_actividad_salida'],
                    'titulo'        => sql_htm($row['nombre_actividad']),
                    'fecha'         => $row['fecha_actividad'], 
                    'inscritos'         => $row['inscritos'], 
                    'tipo_actividad'=> $row['nombre_tipo_actividad'], 
                    'lugar'         => $row['lugar_actividad']
                );
            }
        }
        return $data;
    }
    
 
    
    public function newSalidasCuenta() {
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $ndeportes = count($deportes);?>
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Crear Salida Grupal <a class="btn btn_nuevo" href="cuenta.php?actividades=salidas" title="listar Salidas">Regresar</a></div>
            <div id="panel_step">
                
                <section style="display:inline-block;width:30%;vertical-align:top;padding:10px ">
                    <h3 style="color:#0074cc">Recomendaciones</h3>
                    <section>
                        <h4 style="color:#0074cc">Deporte</h4>
                        <p style="font-size:0.80em">Llenar la descripción considerando los titulos que aparecen, poner bajo cada uno de ellos el contenido.</p>
                    </section>
                    <section>
                        <h4 style="color:#0074cc">Descripción</h4>
                        <p style="font-size:0.80em">Llenar la descripción considerando los titulos que aparecen, poner bajo cada uno de ellos el contenido.</p>
                    </section>
                    
                </section>
                <section  style="display:inline-block; width:67%; padding-top:10px; padding-left:10px ">
                    <form action="cuenta.php?actividades=salidas&action=add" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" class="form-horizontal" id="form_datos" onsubmit="return validate2(this, 'add')" >
                    <input type="hidden" value="update" name="action">
                    <div class="form-group" >
                        <div class="col-xs-4" style="padding-left:0px">
                            <select name="id_deporte" class="form-control">
                                <option value="">Selecciona Deporte</option>
                                <?php for($i=0;$i<$ndeportes;$i++){?>
                                <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <select class="form-control" name="dificultad_salida">
                                <option>Seleccione Dificultad</option>
                                <option value="0">Fácil</option>
                                <option value="1">Moderado</option>
                                <option value="2">Dificil</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <input type="text" name="fecha_salida" placeholder="01/01/2015" class="form-control date" >
                        </div>
                    </div> 
                    
                    <div class="form-group"><input type="text" name="titulo_salida" placeholder="Titulo" class="form-control" ></div>
                    <div class="form-group"><input type="text" name="lugar_salida" class="form-control"  placeholder="Lugar"></div>
                        
                    <div class="form-group"><textarea name="descripcion_salida" id="descripcion_evento" class="form-control" rows="10" >
Descripción:

Inlusiones:

No Incluye:

Requisitos:

Recomendaciones:

Que Llevar:
                            </textarea>
                        </div> 
                        
                        <div class="form-group"> <input name="video_salida" id="video_youtube" type="text" class="form-control" placeholder="Video Youtube: http://youtu.be/3QDDzaY1LtU"></div>
                        <div class="form-group"> <label class="control-label">Subir Foto:</label><input  name="image" type="file" /> </div>
                        <div class="form-group"> <input name="tags_salida" type="text" class="form-control" placeholder="tags: caminatas, carreras, trek"></div>
                        
                        <hr color="#b1b1b1" />
                        <div class="form-group">
                             
                            <div class="col-xs-3" >  
                                <div class="input-group">
                                    <div class="input-group-addon">Cupos</div>
                                    <input name="cupos_salida" type="text"  class="form-control" >
                                </div>
                            </div>
                            <div class="col-xs-4" >
                                <div class="input-group">
                                    <div class="input-group-addon">Cupo Mínimo</div>
                                    <input name="cupo_minimo_salida" type="text" class="form-control" >
                                </div>
                            </div>
                            <div class="col-xs-5" >
                                <div class="input-group">
                                    <div class="input-group-addon">Fecha Cierre</div>
                                    <input type="text" name="fecha_cierre" placeholder="01/01/2015" class="form-control date" >
                                  </div>
                            </div>
                        </div>
                        
                        <hr color="#b1b1b1" />
                        <div class="form-group">
                            <div class="col-xs-9"><label class="control-label">Tarifas:</label></div>
                            <div class="col-xs-6" >  
                                    <input name="nombre_tarifa" type="text" placeholder="Todo incluido" class="form-control" > 
                            </div>
                            <div class="col-xs-3" >
                                <div class="input-group">
                                    <div class="input-group-addon">S/.</div>
                                    <input name="precio_tarifa" type="text" placeholder="00.00" class="form-control" >
                                </div>
                            </div>
                            <div class="col-xs-3" >
                                <select class="form-control" name="comision_tarifa">
                                    <option>Comisión</option>
                                    <option value="0">10%</option>
                                    <option value="1">11%</option>
                                    <option value="2">12%</option>
                                    <option value="3">13%</option>
                                    <option value="4">14%</option>
                                    <option value="5">15%</option>
                                    <option value="6">16%</option>
                                    <option value="6">17%</option>
                                    <option value="6">18%</option>
                                    <option value="6">19%</option>
                                    <option value="6">20%</option>
                            </select>
                            </div>
                    </div>
                        
                      
                        
                        <div class="pnl_btn" align="center">
                            <input class="cancel_blanck" type="reset" value="x Cancelar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="btn btn_guardar" type="submit" value="Publicar Salida">
                        </div>
                    </form>
                    
                </section>
                
            </div>
        </div>     
 
        <?php
    }


    public function addSalidasCuenta(Cliente $cliente){
        
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file; 
            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _imgs_."actividades/salidas/");
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(800, 500, $nombre);
        }
        
        $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_salida'])));
        
        $sql = "INSERT INTO actividades(id_cliente,id_deporte,id_tipo_actividad,nombre_actividad, descripcion_actividad, fecha_actividad, imagen_actividad,video_actividad, lugar_actividad, latitud_actividad, longitud_actividad,estado_actividad,tags_actividad) 
                VALUES ('".$cliente->__get("_id")."',
                   '" . $_POST['id_deporte'] . "',
                   '2',
                   '" . $_POST['titulo_salida'] . "',
                   '" . clean_esp(htm_sql($_POST['descripcion_salida'])) . "',
                   '" . $fecha . "',
                   '" . $nombre . "',
                   '" . $_POST['video_actividad'] . "',
                   '" . addslashes($_POST['lugar_salida']) . "',
                   '" . $_POST['latitud_salida'] . "',
                   '" . $_POST['longitud_salida'] . "',
                   '1',
                   '" . $_POST['tags_salida']. "')";
        $query = new Consulta($sql);
        $id_actividad = $query->nuevoId();
        
        $sql2 = "INSERT INTO actividades_salidas(id_actividad,dificultad_actividad_salida, cupos_actividad_salida, cupos_minimos_actividad_salida, cierre_inscripciones_actividad_salida)  
                                         VALUES ('" . $id_actividad . "','" . $_POST['dificultad_salida'] . "','" . $_POST['cupos_salida'] . "','" . $_POST['cupo_minimo_salida'] . "','" . $_POST['fecha_cierre'] . "' )";
        $query2 = new Consulta($sql2);
        
        $sql3 = "INSERT INTO actividades_deportes(id_actividad,id_deporte)  VALUES ('" . $id_actividad . "','" . $_POST['deporte'] . "')";
        $query3 = new Consulta($sql3);
        
        $sql4 = "INSERT INTO tarifas(id_actividad,nombre_tarifa,precio_tarifa,comision_fija_tarifa) VALUES('". $id_actividad ."','". $_POST['nombre_tarifa'] ."','". $_POST['precio_tarifa'] ."','". $_POST['comision_tarifa'] ."')";
        $query4 = new Consulta($sql4);

        //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("cuenta.php?actividades=salidas");
        
    }
    
    public function url_salida($deporte, $id, $titulo) {
        $url_aventura = 'salidas-de-' . url_friendly($deporte, 1) . '/' . url_friendly($titulo, 1);
        return $url_aventura;
    }

    	
    public function editSalidasCuenta(){
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
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Editar Evento <a class="btn btn_nuevo" href="cuenta.php?cuenta=missalidas" title="Nuevo Evento">Regresar</a></div>
            <div id="panel_step">
                
                <form action="cuenta.php?cuenta=missalidas&action=update&id=<?php echo $data->_id_salida;?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Categoría de Deporte:</label><select name="deporte" id="deporte">
                        	<?php for($i=0;$i<$ndeportes;$i++){?>
                            <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                            <?php }?>
                        </select><script>document.getElementById("deporte").value = '<?php echo $deporte;?>'; </script></div>
                        <div class="rowElem"><label>Titulo del Evento:</label><input name="titulo_salida" class="inputtext-large" id="titulo_salida" type="text" value="<?php echo $data->_titulo_salida;?>"/></div>
                        <div class="rowElem"><label>Fecha del Evento:</label><input name="fecha_salida" id="fecha_salida" class="date" type="text" size="15" style="width:150px;" value="<?php echo implode("-",array_reverse(explode("-",$data->_fecha_salida)));?>">&nbsp;&nbsp;<img src="aplication/webroot/imgs/icon_cal2.png"/></div>
                        <div class="rowElem"><label>Lugar del Evento:</label><input name="lugar_salida" id="lugar_salida" type="text" size="23" value="<?php echo $data->_lugar_salida;?>"></div>
                        <div class="rowElem"><label>Descripción del Evento:</label><textarea name="descripcion_salida" id="descripcion_salida"><?php echo br2nl($data->_descripcion_salida);?></textarea></div>
                        <div class="rowElem"><label>Tags:</label><input name="tagsd" id="tagsd" type="text" size="80" value="<?php echo $data->_tags;?>"  /> <i>(caminatas, carreras, trekking)</i></div>
                        <div class="rowElem">
                            <label><img src="aplication/webroot/imgs/icon_image.png"> SUBIR FOTO: </label>
                                <div class="custom-input-file">
                                    <input type="file" name="image" id="image" class="input-file" />
                                    + Subir foto..
                                </div>​
                            <div class="clear"></div>
                            <div align="center">
                            	<img src="<?php echo  _imgs_prod_."image_salidas/".$data->_imagen_salida;?>" />
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
	
    public function updateSalidasCuenta(Cliente $cliente){
		$nombre = "";
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $ext = explode('.', $_FILES['image']['name']);
                $nombre_file = time() . sef_string($ext[0]);
                $type_file = typeImage($_FILES['image']['type']);
                $nombre = $nombre_file . $type_file;

                define("NAMETHUMB", "/tmp/thumbtemp");
                $thumbnail = new ThumbnailBlob(NAMETHUMB, _imgs_prod_."image_salidas/");
                $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
                $datos = $thumbnail->CreateThumbnail(412, 275, $nombre);
                            $image = ",image='" . $nombre . "'";
            }


            $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_salida'])));	
            $anquery = new Consulta("DELETE FROM deportes_salidas WHERE id_salida = '" . $_GET['id'] . "'");
            $sql2 = "INSERT INTO deportes_salidas(id_deporte,id_salida) 
                    VALUES (
                       '" . $_POST['deporte'] . "',
                       '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);

            $query = new Consulta("UPDATE salidas SET titulo_salida='" . clean_esp(htm_sql($_POST['titulo_salida'])) . "',
                                lugar_salida='" . addslashes($_POST['lugar_salida']) . "',
                                descripcion_salida='" . addslashes(utf8_decode(nl2br($_POST['descripcion_salida']))). "',
                                tags='" . addslashes($_POST['tagsd']) . "',
                                fecha_salida='" . $fecha . "' 
                                " . $image . " WHERE id_salida = '" . $_GET['id'] . "' AND id_cliente = '".$cliente->__get("_id")."'");


                    //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
                    location("cuenta.php?cuenta=missalidas");
	}
    
    public function deleteSalidasCuenta(Cliente $cliente) {
        $query1 = new Consulta("DELETE FROM clientes_salidas WHERE id_salida = '" . $_GET['id'] . "'"); //Son los salidas por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM actividades_salidas WHERE id_salida = '" . $_GET['id'] . "' AND id_cliente='".$cliente->__get("_id")."'");
        $query3 = new Consulta("DELETE FROM salidas_tags WHERE id_salida = '" . $_GET['id'] . "'");

        //$this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("cuenta.php?cuenta=missalidas");
    }
    
    public function listSalidasCuenta(Cliente $cliente){
         
        $salidas = $this->getSalidasCuenta($cliente->__get("_id"));
        $nsalidas = count($salidas);  ?>
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span> Salidas Grupales <a class="btn btn_nuevo" href="cuenta.php?actividades=salidas&action=new" title="Nueva Salida">Nueva Salida</a></div>
            <div id="panel_step">
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Fecha</th>
                          <th>Tipo</th>
                          <th>Ins.</th>
                          <th>Actividad</th>
                          <th>Opciones</th>
                        </tr>
                    </thead>
                <?php 
                    for($i = 0; $i < $nsalidas; $i++){
                        $link = _url_ . $this->url_salida($salidas[$i]["nombre_deporte"],$salidas[$i]['id_salida'], $salidas[$i]['titulo']); ?>
                    
                      <tr>
                          <td> <?php echo $salidas[$i]['id_salida'];?>   </td>
                          <td class="fecha"><?php echo formato_slash("-",$salidas[$i]['fecha']);?></td>
                          <td> <?php echo $salidas[$i]['tipo_actividad'];?> </td>
                          <td> <?php echo $salidas[$i]['inscritos'];?>  </td>
                        <td><?php echo $salidas[$i]['titulo'];?> <a href="<?php echo $link;?>" target="_blank" class="href"><img src="aplication/webroot/imgs/icon_mas.jpg" /></a></td>    
                        <td>
                            <a href="cuenta.php?actividades=salidas&action=inscripciones&id_actividad=<?php echo $salidas[$i]['id_salida']?>" class="btn-circle btn-registro glyphicon glyphicon-th-list" title="Inscritos"> </a>                       
                            <a href="cuenta.php?actividades=salidas&action=edit&id=<?php echo $salidas[$i]['id_salida']?>" class="btn-circle btn-edit glyphicon glyphicon-pencil" title="Editar"> </a>
                            <a href="cuenta.php?actividades=salidas&action=del&id=<?php echo $salidas[$i]['id_salida']?>" class="btn-circle btn-delete glyphicon glyphicon-remove" title="Eliminar"></a>
                            <a href="cuenta.php?actividades=salidas&action=estaditica&id=<?php echo $salidas[$i]['id_salida']?>" class="btn-circle btn-reporte glyphicon glyphicon-stats" title="Estadistica"> </a>   
                        </td>
                      </tr>
                        
                    <?php }?>
               </table>
            	<div class="clear"></div>
            </div>
        </div>
        
        <?php
    } 
}
?>
