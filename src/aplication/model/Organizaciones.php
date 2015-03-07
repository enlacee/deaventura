<?php

class Organizaciones {

    private $_msgbox;

    public function __construct(Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
    }

    public function newOrganizaciones() {
        ?>
        <form name="organizaciones" method="post" action="" enctype="multipart/form-data"> 
            <fieldset id="form">
                <legend> Nuevo Registro</legend>			


                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_organizaciones('add', '')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre : </strong></label><input type="text" name="nombre_organizacion" id="nombre_organizacion" value="" size="59" maxlength="45"> <em>* Solo números y letras</em></li> 
                    <li><label><strong> Website : </strong></label><input type="text" name="website_organizacion" value="" size="59" maxlength="45"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_organizacion"  class="textarea tinymce" id="descripcion_proveedor" style="height: 230px"></textarea></li>
                    <li><label><strong> Telefono : </strong></label><input type="text" name="telefono_organizacion" value="" size="59" maxlength="39"></li> 
                    <li><label><strong> Dirección : </strong></label><input type="text" name="direccion_organizacion" value="" size="59" maxlength="70"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" name="email_organizacion" value="" size="59" maxlength="45"></li> 
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file"></li><br>
                </ul>

            </fieldset>

            <br><br>
            <?php
            /* Para ver los tags */
            $tags = new Tags();
            $tags->viewTags_user("", "organizacion", "organizaciones");
            ?>
            <br><br>
        </form>
        <?php
    }

    public function addOrganizaciones() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_organizacion_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
        }

        $sql = "INSERT INTO organizaciones(nombre_organizacion,website_organizacion,descripcion_organizacion,telefono_organizacion, direccion_organizacion,email_organizacion, image) 
                VALUES (
                   '" . clean_esp(htm_sql($_POST['nombre_organizacion'])) . "',
                   '" . addslashes($_POST['website_organizacion']) . "',
                   '" . addslashes(html_entity_decode($_POST['descripcion_organizacion'])) . "',
                   '" . addslashes($_POST['telefono_organizacion']) . "',
                   '" . addslashes($_POST['direccion_organizacion']) . "',
                   '" . $_POST['email_organizacion'] . "',
                   '" . $nombre . "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_organizaciones(id_deporte,id_organizacion) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO organizaciones_tags(id_organizacion,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        location("organizaciones.php");
    }

    public function editOrganizaciones() {
        $sql = "SELECT id_organizacion, nombre_organizacion, website_organizacion, descripcion_organizacion, telefono_organizacion, direccion_organizacion, email_organizacion, image FROM deportes_organizaciones
                    INNER JOIN organizaciones USING(id_organizacion)
                    WHERE id_organizacion = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="organizaciones" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_organizaciones('update', '<?php echo $_GET['id'] ?>')" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre : </strong></label><input type="text" value="<?php echo sql_htm($row['nombre_organizacion']) ?>" id="nombre_organizacion" name="nombre_organizacion"  class="text ui-widget-content ui-corner-all" size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Website : </strong></label><input type="text" value="<?php echo $row['website_organizacion'] ?>" name="website_organizacion" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_organizaciones', 'id_organizacion', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripcion : </strong></label><textarea name="descripcion_organizacion"  class="textarea tinymce" id="descripcion_proveedor" style="height: 230px"><?php echo sql_htm($row['descripcion_organizacion']) ?></textarea></li>
                    <li><label><strong> Telefono : </strong></label><input type="text" value="<?php echo $row['telefono_organizacion'] ?>" name="telefono_organizacion"  class="text ui-widget-content ui-corner-all" size="59" maxlength="39"></li> 
                    <li><label><strong> Direccion : </strong></label><input type="text" value="<?php echo $row['direccion_organizacion'] ?>" name="direccion_organizacion" class="text ui-widget-content ui-corner-all" size="59" maxlength="70"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" value="<?php echo $row['email_organizacion'] ?>" name="email_organizacion"  class="text ui-widget-content ui-corner-all email" size="59" maxlength="45"></li> 
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" class="text ui-widget-content ui-corner-all" /></li>
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../aplication/webroot/imgs/catalogo/image_organizaciones/' . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>

        <br><br>
        <?php
        /* Para ver los tags */
        $tags = new Tags();
        $tags->viewTags_user($_GET["id"], "organizacion", "organizaciones");
        ?>
        <br><br>
        <?php
    }

    public function updateOrganizaciones() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_organizacion_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
            $image = ",image='" . $nombre . "'";
        }

        $anquery = new Consulta("DELETE FROM deportes_organizaciones WHERE id_organizacion = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_organizaciones(id_deporte,id_organizacion) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }
        $aql = "UPDATE organizaciones SET nombre_organizacion='" . clean_esp(htm_sql($_POST['nombre_organizacion'])) . "',
                            website_organizacion='" . addslashes($_POST['website_organizacion']) . "',
                            descripcion_organizacion='" . addslashes(html_entity_decode($_POST['descripcion_organizacion'])) . "',
                            telefono_organizacion='" . addslashes($_POST['telefono_organizacion']) . "',
                            direccion_organizacion='" . addslashes($_POST['direccion_organizacion']) . "',
                            email_organizacion='" . addslashes($_POST['email_organizacion']) . "'
                            " . $image . " WHERE id_organizacion = '" . $_GET['id'] . "'";
        $query = new Consulta($aql);

        $this->_msgbox->setMsgbox('Se actualizó correctamente la organización.', 2);
        location("organizaciones.php");
    }

    public function deleteOrganizaciones() {
        $this->deleteArchivo($_GET['id']);

        $query = new Consulta("DELETE FROM organizaciones WHERE id_organizacion = '" . $_GET['id'] . "'");
        $query = new Consulta("DELETE FROM organizaciones_tags WHERE id_organizacion = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("organizaciones.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM organizaciones WHERE id_organizacion = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_organizacion_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listOrganizacionesxDeporte($idDeporte) {
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM organizaciones ORDER BY nombre_organizacion ASC";
        } else {
            $sql = "SELECT * FROM deportes_organizaciones INNER JOIN organizaciones USING(id_organizacion)
                    WHERE id_deporte = " . $idDeporte;
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_organizacion'] . "|prod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_organizacion']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="organizaciones.php?id=<?php echo $rowp['id_organizacion'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('organizaciones.php', '<?php echo $rowp['id_organizacion'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listOrganizaciones() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_organizaciones") as $value)
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                ?>
            </select> 
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('organizaciones')"/>
            <br/><br/>

            <table cellspacing="1" cellpadding="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Organizaciones</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM organizaciones ORDER BY nombre_organizacion ASC";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_organizacion'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_organizacion']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="organizaciones.php?id=<?php echo $rowp['id_organizacion'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('organizaciones.php', '<?php echo $rowp['id_organizacion'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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

}
?>
