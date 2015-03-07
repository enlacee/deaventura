<?php

class Proveedores {

    private $_msgbox;

    public function __construct(Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
    }

    public function newProveedores() {  ?>
        <form name="proveedores" method="post" action="" enctype="multipart/form-data">

            <fieldset id="form">
                <legend> Nuevo Registro</legend>			

                <div class="button-actions">
                    <input type="button" name="" value="GUARDAR" onclick="return valida_proveedor('add', '')"  />
                    <input type="reset" name="" value="CANCELAR" />
                </div><br/><br/>
                <ul>
                    <li><label><strong> Nombre : </strong></label><input type="text" name="nombre_proveedor" id="nombre_proveedor" value="" size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Direccion : </strong></label><input type="text" name="direccion_proveedor" value="" size="59" maxlength="70"></li> 
                    <li><label><strong> Telefono : </strong></label><input type="text" name="telefono_proveedor" value="" size="59" maxlength="40"></li> 
                    <li><label><strong> Website : </strong></label><input type="text" name="website_proveedor" value="" size="59" maxlength="50"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" name="email_proveedor" value="" size="59" maxlength="50"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label><strong> Descripcion Proveedor: </strong></label><textarea name="descripcion_proveedor" value="" class="textarea tinymce" id="descripcion_proveedor" style="height: 230px"></textarea>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" size="50"  /></li>
                    <li><label><strong> Tags : </strong></label><input type="text" name="tags_proveedor" value="" size="59" maxlength="50"></li><br>
                </ul>

            </fieldset>

            <br><br>
            <?php
            /* Para ver los tags */
            $tags = new Tags();
            $tags->viewTags_user("", "proveedor", "proveedores");
            ?>
            <br><br>
        </form>
        <?php
    }

    public function addProveedores() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_proveedor_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
        }

        $sql = "INSERT INTO proveedores(nombre_proveedor,direccion_proveedor,telefono_proveedor,website_proveedor,email_proveedor, descripcion_proveedor, image, tags_proveedor) 
                VALUES (
                   '" . clean_esp(htm_sql($_POST['nombre_proveedor'])) . "',
                   '" . addslashes(htm_sql($_POST['direccion_proveedor'])) . "',
                   '" . addslashes(htm_sql($_POST['telefono_proveedor'])) . "',
                   '" . addslashes(htm_sql($_POST['website_proveedor'])) . "',
                   '" . htm_sql($_POST['email_proveedor']) . "',
                   '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_proveedor']))) . "',
                   '" . $nombre . ",
                   '" . htm_sql($_POST['tags_proveedor']) . "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();

        foreach ($_POST['deporte'] as $value) {
            $sql2 = "INSERT INTO deportes_proveedores(id_deporte,id_proveedor) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        foreach ($_POST['tags'] as $value) {
            $sql3 = "INSERT INTO proveedores_tags(id_proveedor,id_tag) 
                VALUES (
                   '" . $id . "',
                   '" . $value . "')";
            $query3 = new Consulta($sql3);
        }

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("proveedores.php");
    }

    public function editProveedores() {
        $sql = "SELECT id_proveedor, nombre_proveedor,direccion_proveedor,telefono_proveedor,website_proveedor,email_proveedor, descripcion_proveedor, image 
                                    FROM deportes_proveedores INNER JOIN proveedores USING(id_proveedor) WHERE id_proveedor = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();

        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="proveedores" method="post" action="" enctype="multipart/form-data"> 
                <div class="button-actions">
                    <input type="button" name="" value="GUARDAR" onclick="return valida_proveedor('update', '<?php echo $_GET['id'] ?>')"  />
                    <input type="reset" name="" value="CANCELAR" />
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre : </strong></label><input type="text" name="nombre_proveedor" id="nombre_proveedor" value="<?php echo sql_htm($row['nombre_proveedor']) ?>"  size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Direccion : </strong></label><input type="text" name="direccion_proveedor" value="<?php echo $row['direccion_proveedor'] ?>"  size="59" maxlength="70"></li> 
                    <li><label><strong> Telefono : </strong></label><input type="text" name="telefono_proveedor" value="<?php echo $row['telefono_proveedor'] ?>"  size="59" maxlength="40"></li> 
                    <li><label><strong> Website : </strong></label><input type="text" name="website_proveedor" value="<?php echo $row['website_proveedor'] ?>"  size="59" maxlength="50"></li> 
                    <li><label><strong> Email : </strong></label><input type="text" name="email_proveedor" value="<?php echo $row['email_proveedor'] ?>" class="text ui-widget-content ui-corner-all email" size="59" maxlength="50"></li> 
                    <li><label><strong> Deportes al cual brinda su servicio: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_proveedores', 'id_proveedor', $_GET['id']);
                        ?>
                    </li>
                    <li><label><strong> Descripcion Proveedor: </strong></label><textarea name="descripcion_proveedor" class="textarea tinymce" id="descripcion_proveedor"><?php echo sql_htm($row['descripcion_proveedor']) ?></textarea>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file"  /></li>
                     <li><label><strong> Tags : </strong></label><input type="text" name="tags_proveedor" value="<?php echo $row['tags_proveedor'] ?>" class="text ui-widget-content ui-corner-all email" size="59" maxlength="50"></li> 
                    <li><label></label><?php
                        if ($row['image'] != "") {
                            echo '<img src="../aplication/webroot/imgs/catalogo/image_proveedores/' . $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br>
        <?php
        /* Para ver los tags */
        $tags = new Tags();
        $tags->viewTags_user($_GET["id"], "proveedor", "proveedores");
        ?>
        <br><br>
        <?php
    }

    public function updateProveedores() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_proveedor_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
            $image = ",image='" . $nombre . "'";
        }


        $anquery = new Consulta("DELETE FROM deportes_proveedores WHERE id_proveedor = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_proveedores(id_deporte,id_proveedor) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $query = new Consulta("UPDATE proveedores SET  
                            nombre_proveedor='" . clean_esp(htm_sql($_POST['nombre_proveedor'])) . "',
                            direccion_proveedor='" . addslashes($_POST['direccion_proveedor']) . "',
                            telefono_proveedor='" . addslashes($_POST['telefono_proveedor']) . "',
                            website_proveedor='" . addslashes($_POST['website_proveedor']) . "',
                            email_proveedor='" . $_POST['email_proveedor'] . "',
                            descripcion_proveedor='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_proveedor']))) . "'
                            " . $image . ",
                            email_proveedor = '" . $_POST['email_proveedor'] . "' 
                            WHERE id_proveedor = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente el Proveedor.', 2);
        location("proveedores.php");
    }

    public function deleteProveedores() {
        $this->deleteArchivo($_GET['id']);

        $query = new Consulta("DELETE FROM proveedores WHERE id_proveedor = '" . $_GET['id'] . "'");
        $query = new Consulta("DELETE FROM proveedores_tags WHERE id_proveedor = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("proveedores.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM proveedores WHERE id_proveedor = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_proveedor_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listProveedoresxDeporte($idDeporte) {
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM proveedores ORDER BY nombre_proveedor ASC";
        } else {
            $sql = "SELECT * FROM deportes_proveedores INNER JOIN proveedores USING(id_proveedor)
                    WHERE id_deporte = " . $idDeporte;
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_proveedor'] . "|prod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_proveedor']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="proveedores.php?id=<?php echo $rowp['id_proveedor'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('proveedores.php', '<?php echo $rowp['id_proveedor'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listProveedores() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_proveedores") as $value)
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('proveedores')"/>

            <br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Proveedores</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM proveedores ORDER BY nombre_proveedor ASC";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_proveedor'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_proveedor']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="proveedores.php?id=<?php echo $rowp['id_proveedor'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('proveedores.php', '<?php echo $rowp['id_proveedor'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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
