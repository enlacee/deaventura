<?php

class Inscripciones{

    private $_msgbox;
    private $_id_deporte;

    public function __construct(Msgbox $msg = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id_deporte;
    }

    public function newInscripciones() {  ?>
        <form name="Inscripciones" method="post" action="" enctype="multipart/form-data"> 
            <fieldset id="form">
                <legend> Nuevo Registro</legend>			


                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_inscripciones('add', '')">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_inscripcion" id="titulo_inscripcion" value="" size="59" maxlength="50"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar : </strong></label><input type="text" name="lugar_inscripcion" value="" size="59" maxlength="45"></li> 
                    <li><label><strong> Fecha Evento: </strong></label><input type="text" name="fecha_inscripcion" id="fecha_inscripcion" size="12" class="date"></li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_inscripcion"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tagsd" id="tagsd" size="80" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                </ul>

            </fieldset>
        </form>
        <?php
    }

    public function addInscripciones() {
        $nombre = "";
 
	$user = $_SESSION['usuario'];
		
        $fecha = explode("/", $_POST['fecha_inscripcion']);
        $sql = "INSERT INTO Inscripciones(id_cliente,titulo_inscripcion, fecha_inscripcion, lugar_inscripcion, descripcion_inscripcion, image,tags) 
                VALUES (null,
                   '" . clean_esp(htm_sql($_POST['titulo_inscripcion'])) . "',
                   '" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "',
                   '" . addslashes($_POST['lugar_inscripcion']) . "',
                   '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_inscripcion']))) . "',
                   '" . $nombre . "',
				   '" . addslashes($_POST['tagsd'])."'
				   )";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_inscripciones(id_deporte,id_inscripcion) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO Inscripciones_tags(id_inscripcion,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("Inscripciones.php?");
    }

    public function editInscripciones() {
        $sql = "SELECT * FROM deportes_inscripciones INNER JOIN Inscripciones USING(id_inscripcion) WHERE id_inscripcion = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();

        $time = explode("-", $row['fecha_inscripcion']);
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="Inscripciones" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_inscripciones('update', '<?php echo $_GET['id'] ?>')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_inscripcion" id="titulo_inscripcion" value="<?php echo sql_htm($row['titulo_inscripcion']) ?>" size="59" maxlength="50"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar : </strong></label><input type="text" name="lugar_inscripcion" value="<?php echo $row['lugar_inscripcion'] ?>" size="59" maxlength="45"></li> 
                    <li><label><strong> Fecha Evento: </strong></label><input type="text" name="fecha_inscripcion" id="fecha_inscripcion" value="<?php echo $time[0] . '/' . $time[1] . '/' . $time[2] ?>" size="12" class="date"></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_inscripciones', 'id_inscripcion', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_inscripcion"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"><?php echo sql_htm($row['descripcion_inscripcion']) ?></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tagsd" id="tagsd" size="80" value="<?php echo sql_htm($row['tags']) ?>" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../' . _url_inscripcion_img_ . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br>
        <?php
    }

    public function updateInscripciones() {
        $image = "";
 
        $anquery = new Consulta("DELETE FROM deportes_inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_inscripciones(id_deporte,id_inscripcion) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $fecha = explode("/", $_POST['fecha_inscripcion']);
        $query = new Consulta("UPDATE Inscripciones SET titulo_inscripcion='" . clean_esp(htm_sql($_POST['titulo_inscripcion'])) . "',
                            lugar_inscripcion='" . addslashes($_POST['lugar_inscripcion']) . "',
                            descripcion_inscripcion='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_inscripcion']))). "',
							tags = '".addslashes($_POST['tagsd'])."',
                            fecha_inscripcion='" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "' 
                            " . $image . " WHERE id_inscripcion = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la organización.', 2);
        location("inscripciones.php");
    }

    public function deleteInscripciones() {
        $this->deleteArchivo($_GET['id']);

        $query1 = new Consulta("DELETE FROM clientes_inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "'"); //Son los Inscripciones por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "'");
        $query3 = new Consulta("DELETE FROM inscripciones_tags WHERE id_inscripcion = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("Inscripciones.php");
    }


    public function listInscripciones() { ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_inscripciones") as $value) {
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                }
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('inscripciones')"/><br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Inscripciones</th> 
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM inscripciones ORDER BY fecha_inscripcion DESC limit 100";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_inscripcion'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle"> 
                            <?php echo "<b>" . sql_htm($rowp['fecha_inscripcion']) . "</b>" ?> /
                            <?php echo "<b>" . sql_htm($rowp['titulo_inscripcion']) . "</b>" ?>
                        </div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="inscripciones.php?id=<?php echo $rowp['id_inscripcion'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('Inscripciones.php', '<?php echo $rowp['id_inscripcion'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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

    public function getInscripciones($actuales = FALSE) {
        if ($actuales) {
            $ext = "WHERE fecha_inscripcion >= NOW() ";
        }

        $sql = "SELECT id_inscripcion, titulo_inscripcion, fecha_inscripcion, nombre_deporte, Inscripciones.image, lugar_inscripcion
                FROM inscripciones 
                INNER JOIN deportes_inscripciones USING(id_inscripcion)
                INNER JOIN deportes USING(id_deporte) " . $ext ."
                GROUP BY id_inscripcion
                ORDER BY fecha_inscripcion ASC ";
        //echo $sql;
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_inscripcion' => $row['id_inscripcion'],
                    'titulo_inscripcion' => sql_htm($row['titulo_inscripcion']),
                    'fecha_inscripcion' => $row['fecha_inscripcion'],
                    'lugar_inscripcion' => $row['lugar_inscripcion'],
                    'nombre_deporte' => sql_htm($row['nombre_deporte']),
                    'imagen_inscripcion' => $row['image']
                );
            }
        }
        return $data;
    }

    /* PARTE USUARIO */

   
	
    public function getInscripcionesPorSalida($id_salida){
        $sql = "SELECT * FROM inscripciones 
                INNER JOIN ventas USING(id_venta)
                WHERE ventas.id_actividad='".$id_salida."' ";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_inscripcion'        => $row['id_inscripcion'],
                    'id_venta'              => $row['id_venta'],
                    'id_tarifa'             => $row['id_tarifa'],
                    'nombre'                => $row['nombre_inscripcion'],
                    'apellidos'             => $row['apellidos_inscripcion'],
		    'documento_identidad'   => $row['documento_identidad_inscripcion'],
                    'edad'                  => $row['edad_inscripcion']
                );
            }
        }
        return $data;
   }
    	
    public function newInscripcionesCuenta(){
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $ndeportes = count($deportes);
        ?>
         
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Crear Evento <a class="btn btn_nuevo" href="cuenta.php?cuenta=misInscripciones" title="Nuevo Evento">Regresar</a></div>
            <div id="panel_step">
                <form action="cuenta.php?cuenta=misInscripciones&action=add" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Categoría de Deporte:</label>
                            <select name="deporte" id="deporte">
                                <option value="">Selecciona Deporte</option>
                                <?php for($i=0;$i<$ndeportes;$i++){?>
                                <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                                <?php } ?>
                            </select></div>
                        <div class="rowElem"><label>Titulo del Evento:</label><input name="titulo_inscripcion" id="titulo_inscripcion" class="inputtext-large" type="text"></div>
                        <div class="rowElem"><label>Fecha del Evento:</label><input name="fecha_inscripcion" id="fecha_inscripcion" class="date" type="text" size="15" style="width:150px;"></div>
                        <div class="rowElem"><label>Lugar del Evento:</label><input name="lugar_inscripcion" id="lugar_inscripcion" type="text" size="50"></div>
                        <div class="rowElem"><label>Descripción del Evento:</label><textarea name="descripcion_inscripcion" id="descripcion_inscripcion"></textarea></div>
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
	
    public function addInscripcionesCuenta(Cliente $cliente){
        
        $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_inscripcion'])));
        $sql = "INSERT INTO Inscripciones(id_cliente,titulo_inscripcion, fecha_inscripcion, lugar_inscripcion, descripcion_inscripcion, image,tags) 
                VALUES ('".$cliente->__get("_id")."',
                   '" . clean_esp(htm_sql($_POST['titulo_inscripcion'])) . "',
                   '" . $fecha . "',
                   '" . addslashes($_POST['lugar_inscripcion']) . "',
                   '" . addslashes(utf8_decode(nl2br($_POST['descripcion_inscripcion']))) . "',
                   '" . $nombre . "',
				   '" . $_POST['tagsd']. "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();
        $sql2 = "INSERT INTO deportes_inscripciones(id_deporte,id_inscripcion) 
                        VALUES (
                        '" . $_POST['deporte'] . "',
                        '" . $id . "')";
        $query2 = new Consulta($sql2);

        //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("cuenta.php?cuenta=misInscripciones");
        
    }
    	
    public function editInscripcionesCuenta(){
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
        
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Editar Evento <a class="btn btn_nuevo" href="cuenta.php?cuenta=misInscripciones" title="Nuevo Evento">Regresar</a></div>
            <div id="panel_step">
                
                <form action="cuenta.php?cuenta=misInscripciones&action=update&id=<?php echo $data->_id_inscripcion;?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1">
                        <div class="rowElem"><label>Categoría de Deporte:</label><select name="deporte" id="deporte">
                        	<?php for($i=0;$i<$ndeportes;$i++){?>
                            <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                            <?php }?>
                        </select><script>document.getElementById("deporte").value = '<?php echo $deporte;?>'; </script></div>
                        <div class="rowElem"><label>Titulo del Evento:</label><input name="titulo_inscripcion" class="inputtext-large" id="titulo_inscripcion" type="text" value="<?php echo $data->_titulo_inscripcion;?>"/></div>
                        <div class="rowElem"><label>Fecha del Evento:</label><input name="fecha_inscripcion" id="fecha_inscripcion" class="date" type="text" size="15" style="width:150px;" value="<?php echo implode("-",array_reverse(explode("-",$data->_fecha_inscripcion)));?>">&nbsp;&nbsp;<img src="aplication/webroot/imgs/icon_cal2.png"/></div>
                        <div class="rowElem"><label>Lugar del Evento:</label><input name="lugar_inscripcion" id="lugar_inscripcion" type="text" size="23" value="<?php echo $data->_lugar_inscripcion;?>"></div>
                        <div class="rowElem"><label>Descripción del Evento:</label><textarea name="descripcion_inscripcion" id="descripcion_inscripcion"><?php echo br2nl($data->_descripcion_inscripcion);?></textarea></div>
                        <div class="rowElem"><label>Tags:</label><input name="tagsd" id="tagsd" type="text" size="80" value="<?php echo $data->_tags;?>"  /> <i>(caminatas, carreras, trekking)</i></div>
                        <div class="rowElem">
                            <label><img src="aplication/webroot/imgs/icon_image.png"> SUBIR FOTO: </label>
                                <div class="custom-input-file">
                                    <input type="file" name="image" id="image" class="input-file" />
                                    + Subir foto..
                                </div>​
                            <div class="clear"></div>
                            <div align="center">
                            	<img src="<?php echo  _imgs_prod_."image_inscripciones/".$data->_imagen_inscripcion;?>" />
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
	
    public function updateInscripcionesCuenta(Cliente $cliente){
		$nombre = "";
             

            $fecha = implode("-",array_reverse(explode("-", $_POST['fecha_inscripcion'])));	
            $anquery = new Consulta("DELETE FROM deportes_inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "'");
            $sql2 = "INSERT INTO deportes_inscripciones(id_deporte,id_inscripcion) 
                    VALUES (
                       '" . $_POST['deporte'] . "',
                       '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);

            $query = new Consulta("UPDATE Inscripciones SET titulo_inscripcion='" . clean_esp(htm_sql($_POST['titulo_inscripcion'])) . "',
                                lugar_inscripcion='" . addslashes($_POST['lugar_inscripcion']) . "',
                                descripcion_inscripcion='" . addslashes(utf8_decode(nl2br($_POST['descripcion_inscripcion']))). "',
                                tags='" . addslashes($_POST['tagsd']) . "',
                                fecha_inscripcion='" . $fecha . "' 
                                " . $image . " WHERE id_inscripcion = '" . $_GET['id'] . "' AND id_cliente = '".$cliente->__get("_id")."'");


                    //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
                    location("cuenta.php?cuenta=misInscripciones");
	}
        
    public function delInscripcionesCuenta(Cliente $cliente) {
        $query1 = new Consulta("DELETE FROM clientes_inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "'"); //Son los Inscripciones por cliente Si estará ahi o no
        $query2 = new Consulta("DELETE FROM inscripciones WHERE id_inscripcion = '" . $_GET['id'] . "' AND id_cliente='".$cliente->__get("_id")."'");
        $query3 = new Consulta("DELETE FROM inscripciones_tags WHERE id_inscripcion = '" . $_GET['id'] . "'");

        //$this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("cuenta.php?cuenta=misInscripciones");
    }
    
    public function listInscripcionesCuenta(){
      
        $Ventas = $this->getVentasPorSalida($_GET["id_actividad"],$_GET["id_tipo_actividad"]);
        $nVentas = count($Ventas);  ?>
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span> Ventas 
			
				<?php if($_GET["id_tipo_actividad"]==2):?>
                    <a class="btn btn_nuevo" href="cuenta.php?actividades=salidas" title="listar Salidas">Regresar</a></div>
                <?php elseif($_GET["id_tipo_actividad"]==3):?>
                    <a class="btn btn_nuevo" href="cuenta.php?actividades=paquetes" title="listar Salidas">Regresar</a></div>
                <?php else:?>
                    <a class="btn btn_nuevo" href="cuenta.php?actividades=eventos" title="listar Salidas">Regresar</a></div>
                <?php endif;?>
			
            <div id="panel_step">
                
                <table class="table table-hover">
                    <thead>
                        <tr>
                          <th>#</th>
                          <th>Responsable</th>
                          <th>Ins.</th>
                          <th>Monto (s./)</th>
                          <th>Estado</th>
                          <th>Fecha</th>
                          <th>Opciones</th>
                        </tr>
                    </thead>
                <?php 
                    for($i = 0; $i < $nVentas; $i++){  ?>                      
                      <tr>           
                          <td><?php echo $Ventas[$i]['id_venta'];?></td>
                          <td><?php echo $Ventas[$i]['nombre_cliente'].' '.$Ventas[$i]['apellidos_cliente'];?></td>
                          <td><?php echo $Ventas[$i]['inscritos'];?> </td>
                          <td><?php echo $Ventas[$i]['monto_venta'];?> </td>
                          <td><?php if($Ventas[$i]['estado_venta'] == "1") { echo "Activo";}else{echo "Inactivo";}?>  </td>
                          <td class="fecha"><?php echo formato_slash("-",$Ventas[$i]['fecha_venta']);?></td>
                          <td>
                            <a href="cuenta.php?cuenta=misInscripciones&action=edit&id=<?php echo $Inscripciones[$i]['id_inscripcion']?>" class="btn-circle btn-edit glyphicon glyphicon-pencil" title="Editar"> </a>
                            <a href="cuenta.php?cuenta=misInscripciones&action=del&id=<?php echo $Inscripciones[$i]['id_inscripcion']?>" class="btn-circle btn-delete glyphicon glyphicon-remove" title="Eliminar"></a>
                            <!--<a href="cuenta.php?actividades=salidas&action=detalle&id_venta=<?php echo $Ventas[$i]['id_venta']?>" class="btn-circle btn-info glyphicon glyphicon-chevron-down" title="Detalle"></a>-->
                            <input type="button" value='detalle' onclick="abrir_detalle('<?php echo $Ventas[$i]['id_venta'];?>')" />
                      </tr>
                      <tr>           
                          <td colspan="7" id="add_detalle_<?php echo $Ventas[$i]['id_venta'];?>" ></td>
                      </tr>
                      
                    <?php }?>                    
               </table>
               <!--<div id="add_detalle"></div>-->
            <div class="clear"></div>
            </div>
        </div>
        
        <?php
    }

    public function getVentasPorSalida($id_actividad,$id_tipo_actividad){
	   //$sql = "SELECT *,( SELECT COUNT(i.id_inscripcion) FROM inscripciones i  WHERE i.id_actividad = actividades.id_actividad ) AS inscritos 
        $sql = "SELECT *,( SELECT COUNT(i.id_inscripcion) FROM inscripciones i  WHERE i.id_venta = ventas.id_venta ) AS inscritos
                FROM ventas
                    INNER JOIN clientes USING(id_cliente)
                    INNER JOIN actividades USING(id_actividad)
                WHERE actividades.id_tipo_actividad='".$id_tipo_actividad."' AND ventas.id_actividad='".$id_actividad."'
                ORDER BY fecha_venta ASC ";

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_venta'        => $row['id_venta'],
                    'estado_venta'    => $row['estado_venta'],
                    'fecha_venta'     => $row['fecha_venta'],
                    'monto_venta'     => $row['monto_venta'],
                    'inscritos'     => $row['inscritos'],
                    'id_cliente'      => $row['id_cliente'],
                    'nombre_cliente'  => $row['nombre_cliente'],
                    'apellidos_cliente'  => $row['apellidos_cliente']
                );
            }
        }
        return $data;
   }
    
}
?>