<?php

class Agencias {

    private $_msgbox;

    public function __construct(Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
    }

    public function newAgencias() {
        ?>
        <form name="agencias" method="post" action="" enctype="multipart/form-data">
            <fieldset id="form">
                <legend> Nuevo Registro</legend>
                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_agencias('add', '')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre : </strong></label><input type="text" name="nombre_agencia" id="nombre_agencia" value="" size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Website : </strong></label><input type="text" name="website_agencia" value="" size="59" maxlength="45"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label><strong> Descripcion : </strong></label><textarea name="descripcion_agencia"  class="textarea tinymce" id="descripcion_proveedor"></textarea></li>
                    <li><label><strong> Telefono : </strong></label><input type="text" name="telefono_agencia" value="" size="59" maxlength="39"></li> 
                    <li><label><strong> Direccion : </strong></label><input type="text" name="direccion_agencia" value="" size="59" maxlength="70"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" name="email_agencia" value="" size="59" maxlength="45"></li> 
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                    <li><label><strong> Tags : </strong></label><input type="text" name="tags_agencia" value="" size="59" maxlength="105"></li> 
                </ul>
            </fieldset>

            <br><br>
            <?php
            /* Para ver los tags */
            $tags = new Tags();
            $tags->viewTags_user("", "agencia", "agencias");
            ?>
            <br><br>

        </form>
        <?php
    }

    public function addAgencias() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_agencia_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
        }

        $sql = "INSERT INTO agencias(nombre_agencia, website_agencia, descripcion_agencia, telefono_agencia, direccion_agencia, email_agencia, image, tags_agencia) 
                VALUES (
                   '" . clean_esp(htm_sql($_POST['nombre_agencia'])) . "',
                   '" . $_POST['website_agencia'] . "',
                   '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_agencia']))) . "',
                   '" . addslashes($_POST['telefono_agencia']) . "',
                   '" . addslashes($_POST['direccion_agencia']) . "',
                   '" . $_POST['email_agencia'] . "',
                   '" . $nombre . "',
                   '" . $_POST['tags_agencia'] . "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_agencias(id_deporte,id_agencia) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO agencias_tags(id_agencia,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("agencias.php");
    }

    public function editAgencias() {
        $sql = "SELECT id_agencia, nombre_agencia, website_agencia, descripcion_agencia, telefono_agencia, direccion_agencia, email_agencia, image FROM deportes_agencias
                    INNER JOIN agencias USING(id_agencia)
                    WHERE id_agencia = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="agencias" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_agencias('update', '<?php echo $_GET['id'] ?>')" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div>
                <ul> 
                    <li><label><strong> Nombre : </strong></label><input type="text" value="<?php echo sql_htm($row['nombre_agencia']) ?>" name="nombre_agencia" id="nombre_agencia" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Website : </strong></label><input type="text" value="<?php echo $row['website_agencia'] ?>" name="website_agencia" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_agencias', 'id_agencia', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripcion : </strong></label><textarea name="descripcion_agencia"  class="textarea tinymce" id="descripcion_proveedor" style="height: 400px"><?php echo sql_htm($row['descripcion_agencia']) ?></textarea></li>
                    <li><label><strong> Telefono : </strong></label><input type="text" name="telefono_agencia" value="<?php echo $row['telefono_agencia'] ?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="39"></li> 
                    <li><label><strong> Direccion : </strong></label><input type="text" name="direccion_agencia" value="<?php echo $row['direccion_agencia'] ?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="70"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" name="email_agencia" value="<?php echo $row['email_agencia'] ?>" class="text ui-widget-content ui-corner-all email" size="59" maxlength="45"></li> 
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" class="text ui-widget-content ui-corner-all" /></li>
                    <li><label><strong> Tags : </strong></label><input type="text" name="tags_agencia" value="<?php echo $row['tags_agencia'] ?>" class="text ui-widget-content ui-corner-all email" size="59" maxlength="105"></li> 
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../aplication/webroot/imgs/catalogo/image_agencias/' . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br>
        <?php
        /* Para ver los tags */
        $tags = new Tags();
        $tags->viewTags_user($_GET["id"], "agencia", "agencias");
        ?>
        <br><br>
        <?php
    }

    public function updateAgencias() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_agencia_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
            $image = ",image='" . $nombre . "'";
        }

        $anquery = new Consulta("DELETE FROM deportes_agencias WHERE id_agencia = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_agencias(id_deporte,id_agencia) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $query = new Consulta("UPDATE agencias SET nombre_agencia='" . clean_esp(htm_sql($_POST['nombre_agencia'])) . "',
                            website_agencia='" . $_POST['website_agencia'] . "',
                            descripcion_agencia='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_agencia']))) . "',
                            telefono_agencia='" . addslashes($_POST['telefono_agencia']) . "',
                            direccion_agencia='" . addslashes($_POST['direccion_agencia']) . "',
                            email_agencia='" . $_POST['email_agencia'] . "'
                            " . $image . ",
                            tags_agencia='" . $_POST['tags_agencia'] . "' 
                            WHERE id_agencia = '" . $_GET['id'] . "' ");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la agencia.', 2);
        location("agencias.php");
    }

    public function deleteAgencias() {
        $this->deleteArchivo($_GET['id']);

        $query = new Consulta("DELETE FROM agencias WHERE id_agencia = '" . $_GET['id'] . "'");
        $query = new Consulta("DELETE FROM agencias_tags WHERE id_agencia = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("agencias.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM agencias WHERE id_agencia = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_agencia_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listAgenciasxDeporte($idDeporte) {
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM agencias ORDER BY nombre_agencia ASC";
        } else {
            $sql = "SELECT * FROM deportes_agencias INNER JOIN agencias USING(id_agencia)
                    WHERE id_deporte = " . $idDeporte;
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_agencia'] . "|prod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_agencia']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="agencias.php?id=<?php echo $rowp['id_agencia'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('agencias.php', '<?php echo $rowp['id_agencia'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listAgencias() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_agencias") as $value)
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('agencias')"/>

            <br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Agencias</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM agencias ORDER BY nombre_agencia ASC";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_agencia'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_agencia']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="agencias.php?id=<?php echo $rowp['id_agencia'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('agencias.php', '<?php echo $rowp['id_agencia'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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
    
     	
    public function editAgenciasCuenta(Agencia $agencia){
         
//        echo "<pre>";
//        print_r($agencia);
//        echo "</pre>";
        
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $ndeportes = count($deportes);
        list($deporte)=explode(",",$agencia->__get("_deportes"));
        ?>
<!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
        
        <div id="steps">
            <div id="titu_step"> <span class="glyphicon glyphicon-calendar"></span>Editar Agencia <a class="btn btn_nuevo" href="cuenta.php?cuenta=agencia" title="Actualizar Agencia">Regresar</a></div>
            <div id="panel_step">
                
                <form action="cuenta.php?cuenta=agencia&action=update&id=<?php echo $agencia->__get("_id_agencia");?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" name="form_datos" id="form_datos" onsubmit="return validate2(this, 'update')">
                    <input type="hidden" value="update" name="action">
                    <div id="div_input1"> 
                        <div class="rowElem"><label>Nombre:</label><input name="nombre_agencia" class="inputtext-large" id="nombre_agencia" type="text" value="<?php echo $agencia->__get("_nombre_agencia");?>"/></div>
                        <div class="rowElem"><label>Deporte de Aventura:</label><select name="deporte" id="deporte">
                        <?php for($i=0;$i<$ndeportes;$i++){?>
                            <option value="<?php echo $deportes[$i]['id_deporte'];?>"><?php echo $deportes[$i]['nombre_deporte'];?></option>
                        <?php }?>
                        </select><script>document.getElementById("deporte").value = '<?php echo $deporte;?>'; </script></div>
                        <div class="rowElem"><label>Dirección:</label><input name="direccion_agencia" id="direccion_agencia" class="date" type="text" value="<?php echo $agencia->__get("_direccion_agencia");?>"></div>
                        <div class="rowElem"><label>E-mail:</label><input name="email_agencia" id="email_agencia" type="text" size="23" value="<?php echo $agencia->__get("_email_agencia");?>"></div>
                        <div class="rowElem"><label>Teléfono:</label><input name="telefono_agencia" id="telefono_agencia" class="date" type="text" value="<?php echo $agencia->__get("_telefono_agencia");?>"></div>
                        
                        <div class="rowElem"><label>Sito Web:</label><input name="website_agencia" id="website_agencia" class="date" type="text" value="<?php echo $agencia->__get("_website_agencia");?>"></div>
                        <div style="display:table;width: 80%;">
                            <div style="float:left; width:15%; text-align: right">
                                <label>Descripción:</label>
                            </div>
                            <div style="float:right;width: 75%; text-align:left">
                                <textarea name="descripcion_agencia" id="descripcion" style="width:100%;height: 378px;"><?php echo sql_htm($agencia->__get("_descripcion_agencia"));?></textarea>
                            </div>
                        </div>
                        <div class="rowElem">
                            <label><img src="aplication/webroot/imgs/icon_image.png"> SUBIR FOTO: </label>
                            <div class="custom-input-file"> <input type="file" name="image" id="image" class="input-file" />  Subir foto</div>​
                            <div class="clear"></div>
                            <div align="center"> <img src="<?php echo  _imgs_prod_."image_agencias/".$agencia->__get("_imagen_agencia");?>" /> </div>
                        </div>

                        <div class="clear"></div>
                    </div>
                    <hr color="#b1b1b1"/>
                    <div class="pnl_btn" align="center">
                    	<input class="cancel_blanck" type="reset" value="x Cancelar">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    	<input class="btn btn_guardar" type="submit" value="Actualizar Información >">
					</div>
                </form>
                <div class="clear"></div>
            </div>

        </div>
        <?php
    }
    
    
    	
	public function updateAgenciasCuenta(Cliente $cliente){
		$nombre = "";
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $ext = explode('.', $_FILES['image']['name']);
                $nombre_file = time() . sef_string($ext[0]);
                $type_file = typeImage($_FILES['image']['type']);
                $nombre = $nombre_file . $type_file;

                define("NAMETHUMB", "/tmp/thumbtemp");
                $thumbnail = new ThumbnailBlob(NAMETHUMB, _imgs_prod_."image_agencias/");
                $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
                $datos = $thumbnail->CreateThumbnail(412, 275, $nombre);
                $image = ",image='" . $nombre . "'";
            }
             
            $anquery = new Consulta("DELETE FROM deportes_agencias WHERE id_agencia = '" . $_GET['id'] . "'");
            $query2  = new Consulta("INSERT INTO deportes_agencias(id_deporte,id_agencia) VALUES ('". $_POST['deporte'] . "', '" . $_GET['id'] ."')");
            $query   = new Consulta("UPDATE agencias 
                                    SET nombre_agencia      = '" . addslashes($_POST['nombre_agencia']) . "',
                                        telefono_agencia    = '" . addslashes($_POST['telefono_agencia']) . "',
                                        email_agencia       = '" . addslashes($_POST['email_agencia']) . "',
                                        direccion_agencia   = '" . addslashes($_POST['direccion_agencia']) . "',
                                        website_agencia     = '" . addslashes($_POST['website_agencia']) . "',
                                        descripcion_agencia = '" . addslashes(utf8_decode(nl2br($_POST['descripcion_agencia']))). "' 
                                        " . $image . " 
                                    WHERE id_agencia = '" . $_GET['id'] . "' ");
            

                    //$this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
                    //location("cuenta.php?cuenta=agencia");
    }
        
    public function getAgencias() {
        $sql = " SELECT * FROM agencias ORDER BY nombre_agencia DESC";
        $query = new Consulta($sql);
        $datos = array();

        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id' => $row['id_agencia'], 
                'nombre'        => $row['nombre_agencia'],
                'descripcion'   => $row['descripcion_agencia'],
                'webite'        => $row['website_agencia']);
        }
        return $datos;
    }

}
?>
