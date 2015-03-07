<?php

class Rutas_otros {

    private $_msgbox;
    private $_id_lugar;

    public function __construct(Msgbox $msg = NULL, $id_lugar = NULL) {
        $this->_msgbox = $msg;
        $this->_id_lugar = $id_lugar;
    }

    public function newRutas_otros() {
        $id_mod = $_GET["idm"];

        $query = new Consulta("SELECT id_deporte FROM modalidades WHERE id_modalidad = " . $id_mod);
        $row = $query->VerRegistro();
        $id_deporte = $row["id_deporte"];
        $id_lugar = $_GET["idl"];
        ?>
        <form name="rutas" method="post" action="rutas_otros.php?action=add&idl=<?php echo $id_lugar ?>&idm=<?php echo $id_mod ?>" enctype="multipart/form-data"> 
            <fieldset id='form'>
                <legend> Editar Registro</legend>			


                <div class='button-actions'>
                    <input type="submit" value="AGREGAR" class="button">
                </div>
                <br/><br/>
                <ul> 
                    <li><label> Descripcion Principal: </label><textarea style="height: 250px;" name="descripcion_rutas_otros" class="textarea tinymce"></textarea></li>
                    <li><label> Descripcion Secundaria: </label><textarea style="height: 250px;" name="descripcion_rutas_otros_s" class="textarea tinymce"></textarea></li>
                    <li><label> Imagen: </label><input name="image" id="image" type="file" /></li>
                </ul>

            </fieldset>
            <br/><br/>
            <fieldset id="tags">
                <legend> Vincular Tags</legend>
                <input type="hidden" id="id_mod">
                <input type="hidden" id="id_lugar">
                <div id="panel_tags">
                    <ul>
                        <?php
                        $sql = "SELECT * FROM tags t WHERE t.id_deporte = " . $id_deporte . " AND t.id_tag 
                                NOT IN (SELECT id_tag FROM rutas_otros_tags rt 
                                        WHERE rt.id_modalidad = " . $id_mod . " 
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
                    $sql = "SELECT id_tag, nombre_tag FROM rutas_otros_tags rt INNER JOIN tags USING(id_tag) 
                            WHERE rt.id_modalidad = " . $id_mod . " 
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

    public function addRutas_otros() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_rutas_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
        }

        $sql = "INSERT INTO rutas_otros(id_modalidad,id_lugar,descripcion_rutas_otros,descripcion_rutas_otros_s,image) 
                VALUES (
                    '" . $_GET['idm'] . "',
                    '" . $_GET['idl'] . "',
                    '" . addslashes(html_entity_decode($_POST['descripcion_rutas_otros'])) . "',
                    '" . addslashes(html_entity_decode($_POST['descripcion_rutas_otros_s'])) . "',
                    '" . $nombre . "')";

        $query = new Consulta($sql);

        if ($query->registrosAfectado() > 0) {
            $id = $query->nuevoId();
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $value) {
                    $sql3 = "INSERT INTO rutas_otros_tags(id_modalidad,id_lugar,id_tag) 
                VALUES (
                   '" . $_GET['idm'] . "',
                   '" . $_GET['idl'] . "',
                   '" . $value . "')";
                    $query3 = new Consulta($sql3);
                }
            }
        }
        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("rutas.php?idl=" . $_GET['idl']);
    }

    public function editRutas_otros() {
        $id_mod = $_GET["idm"];

        $query = new Consulta("SELECT id_deporte FROM modalidades WHERE id_modalidad = " . $id_mod);
        $row = $query->VerRegistro();
        $id_deporte = $row["id_deporte"];
        $id_lugar = $_GET["idl"];

        $sql = "SELECT * FROM rutas_otros WHERE id_modalidad = " . $id_mod . " AND id_lugar = " . $id_lugar;
        $query = new Consulta($sql);
        $rowp = $query->VerRegistro();
        ?>

        <fieldset id='form'>
            <legend> Editar Registro</legend>			
            <form name="rutas" method="post" action="rutas.php?action=update&idl=<?php echo $id_lugar ?>&idm=<?php echo $id_mod ?>" enctype="multipart/form-data"> 

                <div class='button-actions'>
                    <input type="submit" value="MODIFICAR" class="button">
                </div>
                <br/><br/>
                <ul>  
                    <li><label> Descripcion Principal: </label><textarea style="height: 250px;" name="descripcion_rutas_otros" class="textarea tinymce"><?php echo sql_htm($rowp['descripcion_rutas_otros']) ?></textarea></li>
                    <li><label> Descripcion Secundaria: </label><textarea style="height: 250px;" name="descripcion_rutas_otros_s" class="textarea tinymce"><?php echo sql_htm($rowp['descripcion_rutas_otros_s']) ?></textarea></li>
                    <li><label> Imagen: </label>
                        <input name="image" id="image" type="file" /><br/><br/>
                        <?php if ($rowp['image'] != "") { ?> 
                            <img src="../<?php echo _url_rutas_img_ . $rowp["image"] ?>"> 
                        <?php } ?>
                    </li>
                </ul>
            </form>
        </fieldset>
        <br/><br/>
        <fieldset id="tags">
            <legend> Vincular Tags</legend>
            <input type="hidden" value="<?php echo $id_mod ?>" id="id_mod">
            <input type="hidden" value="<?php echo $id_lugar ?>" id="id_lugar">
            <div id="panel_tags">
                <ul>
                    <?php
                    $sql = "SELECT * FROM tags t WHERE t.id_deporte = " . $id_deporte . " AND t.id_tag 
                                NOT IN (SELECT id_tag FROM rutas_otros_tags rt 
                                        WHERE rt.id_modalidad = " . $id_mod . " 
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
                $sql = "SELECT id_tag, nombre_tag FROM rutas_otros_tags rt INNER JOIN tags USING(id_tag) 
                            WHERE rt.id_modalidad = " . $id_mod . " 
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

    public function updateRutas_otros() {
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
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
            $image = ",image='" . $nombre . "'";
        }

        $sql = "UPDATE rutas SET descripcion_ruta='" . addslashes(html_entity_decode($_POST['descripcion_ruta'])) . "'
                            " . $image . " WHERE id_lugar = '" . $_GET['idl'] . "' AND id_deporte = '" . $_GET['idd'] . "'";
        $query = new Consulta($sql);

        $this->_msgbox->setMsgbox('Se actualizó correctamente.', 2);
        location("rutas.php?idl=" . $_GET['idl']);
    }

    public function deleteRutas_otros() {
        $idLugar = $_GET['idl'];
        $idModalidad = $_GET['idd']; //Por usar el mismo metodo del javascript : Es el id de la modalidad
        $this->deleteArchivo($idLugar, $idModalidad);
        $query = new Consulta("DELETE FROM rutas_otros WHERE id_lugar = '" . $idLugar . "' AND id_modalidad = '" . $idModalidad . "'");
        $query = new Consulta("DELETE FROM rutas_otros_tags WHERE id_lugar = '" . $idLugar . "' AND id_modalidad = '" . $idModalidad . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("rutas.php?idl=" . $idLugar);
    }

    public function deleteArchivo($idLugar, $idModalidad) {
        $query = new Consulta("SELECT image FROM rutas_otros WHERE id_lugar = '" . $idLugar . "' AND id_modalidad = '" . $idModalidad . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _url_rutas_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listRutas_otros() {
        location("index.php");
    }

    static function mantenerTags($idTag, $idLug, $idMod, $tipo) {
        if ($tipo == "add") {
            $sql = "INSERT INTO rutas_otros_tags (id_modalidad, id_lugar, id_tag) VALUES ('" . $idMod . "','" . $idLug . "','" . $idTag . "')";
            $sql = new Consulta($sql);
            if ($sql->registrosAfectado()) {
                return 1;
            }
        } else if ($tipo == "delete") {
            $sql = new Consulta("DELETE FROM rutas_otros_tags WHERE id_modalidad = '" . $idMod . "' AND id_lugar='" . $idLug . "' AND id_tag='" . $idTag . "'");
            return 1;
        }
    }

}
?>
