<?php

class Rutas {

    private $_msgbox;
    private $_id_lugar;

    public function __construct(Msgbox $msg = NULL, $id_lugar = NULL) {
        $this->_msgbox = $msg;
        $this->_id_lugar = $id_lugar;
    }

    public function newRutas() {
        $id_deporte = $_GET["idd"];
        $id_lugar = $_GET["idl"];
        ?>
        <form name="rutas" method="post" action="rutas.php?action=add&idl=<?php echo $_GET["idl"] ?>&idd=<?php echo $_GET["idd"] ?>" enctype="multipart/form-data"> 
            <fieldset id='form'>
                <legend> Editar Registro</legend>			


                <div class='button-actions'>
                    <input type="submit" value="AGREGAR" class="button">
                </div>
                <br/><br/>
                <ul> 
                    <li><label> Descripcion Principal: </label><textarea style="height: 250px;" name="descripcion_ruta_p" class="textarea tinymce"></textarea></li>
                    <li><label> Descripcion Secundaria: </label><textarea style="height: 250px;" name="descripcion_ruta_s" class="textarea tinymce"></textarea></li>
                    <li><label> TAGs: </label><input name="tagsp" id="tagsp" type="text" size="100" style="padding:3px;" /></li>
                    <li><label> Imagen: </label><input name="image" id="image" type="file" /></li>
                </ul>

            </fieldset>
            <br/><br/>
            <fieldset id="tags">
                <legend> Vincular Tags</legend>
                <input type="hidden" id="id_dep">
                <input type="hidden" id="id_lugar">
                <div id="panel_tags">
                    <ul>
                        <?php
                        $sql = "SELECT * FROM tags t WHERE t.id_deporte = " . $id_deporte . " AND t.id_tag 
                                NOT IN (SELECT id_tag FROM rutas_tags rt 
                                        WHERE rt.id_deporte = " . $id_deporte . " 
                                        AND rt.id_lugar = " . $id_lugar . ")";

                        $query = new Consulta($sql);

                        if ($query->NumeroRegistros() > 0) {
                            while ($row = $query->VerRegistro()) {
                                ?>
                                <li rel="<?php echo $row['id_tag'] ?>">
                                    <div class="icadd" title="Agregar">+</div>
                                    <span><?php echo sql_htm($row['nombre_tag']) ?></span>
                                </li>
                                <?php
                            }
                        } else {
                            echo '<li>No hay Tags disponibles</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div id="loader"><img src="../aplication/webroot/imgs/loader2.gif"></div>
                <div id="tags_list">
                    <h3>Tags vinculados:</h3>
                    <?php
                    $sql = "SELECT id_tag, nombre_tag FROM rutas_tags rt INNER JOIN tags USING(id_tag) 
                            WHERE rt.id_deporte = " . $id_deporte . " 
                            AND rt.id_lugar = " . $id_lugar;
                    $query = new Consulta($sql);

                    if ($query->NumeroRegistros() > 0) {
                        while ($row = $query->VerRegistro()) {
                            ?>
                            <p rel="<?php echo $row['id_tag'] ?>"><span><?php echo sql_htm($row['nombre_tag']) ?></span><em class="deltag" title="Eliminar">X</em></p>
                            <?php
                        }
                    }
                    ?>
                </div>
            </fieldset>
        </form>
        <br/><br/>
        <?php
    }

    public function addRutas() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_rutas_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(420, 263, $nombre);
        }

        $sql = "INSERT INTO rutas(id_deporte,id_lugar,descripcion_ruta,descripcion_ruta_s,image,tags) 
                VALUES (
                    '" . $_GET['idd'] . "',
                    '" . $_GET['idl'] . "',
                    '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_ruta_p']))) . "',
                    '" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_ruta_s']))) . "',
                    '" . $nombre . "',
					'" . $_POST['tagsp'] . "')";

        $query = new Consulta($sql);

        if ($query->registrosAfectado() > 0) {
            $id = $query->nuevoId();
            foreach ($_POST['tags'] as $value) {
                $sql3 = "INSERT INTO rutas_tags(id_deporte,id_lugar,id_tag) 
                VALUES (
                   '" . $_GET['idd'] . "',
                   '" . $_GET['idl'] . "',
                   '" . $value . "')";
                $query3 = new Consulta($sql3);
            }
        }
        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("rutas.php?idl=" . $_GET['idl']);
    }

    public function editRutas() {
        $id_deporte = $_GET["idd"];
        $id_lugar = $_GET["idl"];

        $sql = "SELECT * FROM rutas WHERE id_deporte = " . $id_deporte . " AND id_lugar = " . $id_lugar;
        $query = new Consulta($sql);
        $rowp = $query->VerRegistro();
        ?>

        <fieldset id='form'>
            <legend> Editar Registro</legend>			
            <form name="rutas" method="post" action="rutas.php?action=update&idl=<?php echo $id_lugar ?>&idd=<?php echo $_GET["idd"] ?>" enctype="multipart/form-data"> 

                <div class='button-actions'>
                    <input type="submit" value="MODIFICAR" class="button">
                </div>
                <br/><br/>
                <ul>
                    <li><label> Descripcion Principal: </label><textarea style="height: 250px;" name="descripcion_ruta_p" class="textarea tinymce"><?php echo sql_htm($rowp['descripcion_ruta']) ?></textarea></li>
                    <li><label> Descripcion Secundaria: </label><textarea style="height: 250px;" name="descripcion_ruta_s" class="textarea tinymce"><?php echo sql_htm($rowp['descripcion_ruta_s']) ?></textarea></li>
                    <li><label> TAGs: </label><input name="tagsp" id="tagsp" type="text" size="100" style="padding:3px;" value="<?php echo $rowp['tags'];?>" /></li>
                    <li><label> Imagen: </label>
                        <input name="image" id="image" type="file" /><br/><br/>
                        <div align="center">
						<?php if ($rowp['image'] != "") { ?> 
                            <img src="../<?php echo _url_rutas_img_ . $rowp["image"] ?>"> 
                        <?php } ?>
                        </div>
                    </li>
                </ul>
            </form>
        </fieldset>
        <br/><br/>
        <fieldset id="tags">
            <legend> Vincular Tags</legend>
            <input type="hidden" value="<?php echo $id_deporte ?>" id="id_dep">
            <input type="hidden" value="<?php echo $id_lugar ?>" id="id_lugar">
            <div id="panel_tags">
                <ul>
                    <?php
                    $sql = "SELECT * FROM tags t WHERE t.id_deporte = " . $id_deporte . " AND t.id_tag 
                                NOT IN (SELECT id_tag FROM rutas_tags rt 
                                        WHERE rt.id_deporte = " . $id_deporte . " 
                                        AND rt.id_lugar = " . $id_lugar . ")";

                    $query = new Consulta($sql);

                    if ($query->NumeroRegistros() > 0) {
                        while ($row = $query->VerRegistro()) {
                            ?>
                            <li rel="<?php echo $row['id_tag'] ?>">
                                <div class="icadd" title="Agregar">+</div>
                                <span><?php echo sql_htm($row['nombre_tag']) ?></span>
                            </li>
                            <?php
                        }
                    } else {
                        echo '<li>No hay Tags disponibles</li>';
                    }
                    ?>
                </ul>
            </div>
            <div id="loader"><img src="../aplication/webroot/imgs/loader2.gif"></div>
            <div id="tags_list">
                <h3>Tags vinculados:</h3>
                <?php
                $sql = "SELECT id_tag, nombre_tag FROM rutas_tags rt INNER JOIN tags USING(id_tag) 
                            WHERE rt.id_deporte = " . $id_deporte . " 
                            AND rt.id_lugar = " . $id_lugar;
                $query = new Consulta($sql);

                if ($query->NumeroRegistros() > 0) {
                    while ($row = $query->VerRegistro()) {
                        ?>
                        <p rel="<?php echo $row['id_tag'] ?>"><span><?php echo sql_htm($row['nombre_tag']) ?></span><em class="deltag" title="Eliminar">X</em></p>
                        <?php
                    }
                }
                ?>
            </div>
        </fieldset>
        <br/><br/>
        <?php
    }

    public function updateRutas() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_rutas_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(420, 263, $nombre);
            $image = ",image='" . $nombre . "'";
        }

        $sql = "UPDATE rutas SET descripcion_ruta ='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_ruta_p']))) . "',
                                 descripcion_ruta_s='" . addslashes(utf8_decode(html_entity_decode($_POST['descripcion_ruta_s']))) . "',
								 tags = '".$_POST['tagsp']."'
                            " . $image . " WHERE id_lugar = '" . $_GET['idl'] . "' AND id_deporte = '" . $_GET['idd'] . "'";
        $query = new Consulta($sql);

        $this->_msgbox->setMsgbox('Se actualizó correctamente.', 2);
        location("rutas.php?idl=" . $_GET['idl']);
    }

    public function deleteRutas() {
        $idLugar = $_GET['idl'];
        $idDeporte = $_GET['idd'];
        $this->deleteArchivo($idLugar, $idDeporte);
        $query = new Consulta("DELETE FROM rutas WHERE id_lugar = '" . $idLugar . "' AND id_deporte = '" . $idDeporte . "'");
        $query = new Consulta("DELETE FROM rutas_tags WHERE id_lugar = '" . $idLugar . "' AND id_deporte = '" . $idDeporte . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("rutas.php?idl=" . $idLugar);
    }

    public function deleteArchivo($idLugar, $idDeporte) {
        $query = new Consulta("SELECT image FROM rutas WHERE id_lugar = '" . $idLugar . "' AND id_deporte = '" . $idDeporte . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _url_rutas_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listRutasxDeporte($idDeporte) {
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM rutas ORDER BY nombre_ruta ASC";
        } else {
            $sql = "SELECT * FROM deportes_rutas INNER JOIN rutas USING(id_ruta)
                    WHERE id_deporte = " . $idDeporte;
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_ruta'] . "|prod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_ruta']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="rutas.php?id=<?php echo $rowp['id_ruta'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('rutas.php', '<?php echo $rowp['id_ruta'] ?>', 'delete');
                            return false"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listRutas() {
        $query2 = new Consulta("SELECT id_deporte, nombre_deporte FROM deportes WHERE estado_deporte = 1");
        while ($row = $query2->VerRegistro()) {
            $arr[] = array('id_deporte' => $row['id_deporte'], 'nombre_deporte' => $row['nombre_deporte']);
        }
        ?>
        <img src="<?php echo _admin_ . 'ways.jpg' ?>">
        <div id="content-area">
            <div style="clear: both"></div>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Rutas</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                foreach ($arr as $value) {
                    $sql = "SELECT id_deporte, nombre_deporte, id_lugar FROM rutas r INNER JOIN deportes d USING(id_deporte) WHERE id_lugar = " . $_GET["idl"] . " AND estado_deporte = 1 AND id_deporte = " . $value["id_deporte"];
                    $query = new Consulta($sql);
                    if (strtolower($value["nombre_deporte"]) != "otros deportes") { //se hace esta comparación porque para otros deportes cambia de estructura
                        if ($query->NumeroRegistros() != 0) { //Para editar
                            ?>
                            <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?> edit_dep" id="list_item_<?php echo $value['id_deporte']; ?>">
                                <div class="data"> <img src="<?php echo _catalogo_ . sql_htm(strtolower($value["nombre_deporte"])) . '.png' ?>" width="12">   <?php echo "<b>" . sql_htm($value["nombre_deporte"]) . "</b>" ?></div>
                                <div class="options">
                                    <a title="Editar" class="tooltip" href="rutas.php?idl=<?php echo $_GET["idl"] ?>&idd=<?php echo $value['id_deporte'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                                    <a title="Eliminar" class="tooltip" onClick="mantenimientoRutas('rutas.php','delete','<?php echo $value['id_deporte'] ?>','<?php echo $_GET["idl"] ?>')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                                </div>
                            </li>
                            <?php
                        } else { //Para uno nuevo
                            ?>
                            <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $value['id_ruta']; ?>">
                                <div class="data"> <img src="<?php echo _catalogo_ . sql_htm(strtolower($value["nombre_deporte"])) . '.png' ?>" width="12">  <?php echo "<b>" . sql_htm($value["nombre_deporte"]) . "</b>" ?></div>
                                <div class="options">
                                    <a title="Nuevo" class="tooltip" href="rutas.php?idl=<?php echo $_GET["idl"] ?>&idd=<?php echo $value['id_deporte'] ?>&action=new"><img src="<?php echo _admin_ ?>icon_new.png"></a> &nbsp;
                                </div>
                            </li>
                            <?php
                        }
                    } else {
                        $queryo = new Consulta("SELECT id_modalidad, id_deporte, nombre_modalidad FROM modalidades WHERE id_deporte = " . $value['id_deporte']);
                    }
                }
                ?>
            </ul>
            <br/>

            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Rutas de Otros Deportes</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                if ($queryo->NumeroRegistros() != 0) {
                    while ($value = $queryo->VerRegistro()) {
                        $sql = "SELECT id_modalidad, id_lugar FROM rutas_otros WHERE id_lugar = " . $_GET["idl"] . " AND id_modalidad = " . $value["id_modalidad"];
                        $query = new Consulta($sql);

                        if ($query->NumeroRegistros() != 0) { //Para editar
                            ?>
                            <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?> edit_dep" id="list_item_<?php echo $value['id_modalidad']; ?>">
                            <div class="data">  <?php echo "<b>" . sql_htm($value["nombre_modalidad"]) . "</b>" ?></div>
                            <div class="options">
                                <a title="Editar" class="tooltip" href="rutas_otros.php?idl=<?php echo $_GET["idl"] ?>&idm=<?php echo $value['id_modalidad'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                                <a title="Eliminar" class="tooltip" onClick="mantenimientoRutas('rutas_otros.php','delete','<?php echo $value['id_modalidad'] ?>','<?php echo $_GET["idl"] ?>')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                            </div>
                        </li>
                            <?php
                        } else { //Para uno nuevo
                            ?>
                            <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $value['id_modalidad']; ?>">
                            <div class="data">  <?php echo "<b>" . sql_htm($value["nombre_modalidad"]) . "</b>" ?></div>
                            <div class="options">
                                <a title="Nuevo" class="tooltip" href="rutas_otros.php?idl=<?php echo $_GET["idl"] ?>&idm=<?php echo $value['id_modalidad'] ?>&action=new"><img src="<?php echo _admin_ ?>icon_new.png"></a> &nbsp;
                            </div>
                        </li>
                            <?php
                        }
                        ?>
                        
                        <?php
                    }
                }
                ?>
            </ul>

            <br class="clear" />
        </div>
        <?php
    }

    static function mantenerTags($idTag, $idLug, $idDep, $tipo) {
        if ($tipo == "add") {
            $sql = "INSERT INTO rutas_tags (id_deporte, id_lugar, id_tag) VALUES ('" . $idDep . "','" . $idLug . "','" . $idTag . "')";
            $sql = new Consulta($sql);
            if ($sql->registrosAfectado()) {
                return 1;
            }
        } else if ($tipo == "delete") {
            $sql = new Consulta("DELETE FROM rutas_tags WHERE id_deporte='" . $idDep . "' AND id_lugar='" . $idLug . "' AND id_tag='" . $idTag . "'");
            return 1;
        }
    }

}
?>
