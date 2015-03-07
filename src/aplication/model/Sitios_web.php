<?php

class Sitios_web {

    private $_msgbox;
    private $_id_deporte;

    public function __construct($id = NULL, Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id;
    }

    public function newSitios() {
        $query = new Consulta("SELECT id_sitio_web,titulo_sitio_web,url_sitio_web,descripcion_sitio_web FROM sitios_web");
        //Form::getForm($query, "new", "sitios_web.php", "", "", "");
        ?>
        <fieldset id="form">
            <legend> Nuevo Registro</legend>			
            <form name="sitios_web" method="post" action=""> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_sitios_web('add','')" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label> Titulo Sitio Web: </label><input type='text' name='titulo_sitio_web' id="titulo_sitio_web" value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label> Url Sitio Web: </label><input type='text' name='url_sitio_web' id="url_sitio_web" value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=70 ></li> 
                    <li><label> Descripcion Sitio Web: </label><textarea name='descripcion_sitio_web' style="height: 250px" class='textarea tinymce'></textarea> </li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" class="text ui-widget-content ui-corner-all" /></li><br>
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function addSitios() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _url_sitios_web_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
        }
        
        $sql = "INSERT INTO sitios_web(titulo_sitio_web,url_sitio_web,descripcion_sitio_web,image)
                VALUES ('" . addslashes($_POST['titulo_sitio_web']) . "',
                '" . addslashes($_POST['url_sitio_web']) . "',
                '" . addslashes($_POST['descripcion_sitio_web']) . "',
                '" . $nombre . "')";
        $query = new Consulta($sql);

        $id = $query->nuevoId();
        $array = $_POST['deporte'];

        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_sitios_web(id_deporte,id_sitio_web) 
                VALUES (
                   '" . $value . "',
                   '" . $id . "')";
            $query2 = new Consulta($sql2);
        }

        location("sitios_web.php");
    }

    public function editSitios() {
        $query = new Consulta("SELECT id_sitio_web,titulo_sitio_web,url_sitio_web,descripcion_sitio_web,image FROM sitios_web WHERE id_sitio_web = '" . $_GET['id'] . "'");
        $row = $query->VerRegistro();

        //Form::getForm($query, "edit", "organizaciones.php", "", "", "img", "image_organizaciones");
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="sitios_web" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_sitios_web('update','<?php echo $_GET['id'] ?>')" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label> Titulo Sitio Web: </label><input type='text' name='titulo_sitio_web' id="titulo_sitio_web" value='<?php echo $row["titulo_sitio_web"] ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li>
                    <li><label><strong> Deportes vinculados al evento: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_deportes_vinculados('deportes_sitios_web', 'id_sitio_web', $_GET['id']);
                        ?>
                    </li>
                    <li><label> Url Sitio Web: </label><input type='text' name='url_sitio_web' id="url_sitio_web" value='<?php echo $row["url_sitio_web"] ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=70 ></li> 
                    <li><label> Descripcion Sitio Web: </label><textarea name='descripcion_sitio_web' style="height: 250px" class='textarea tinymce'><?php echo $row["descripcion_sitio_web"] ?></textarea> </li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" class="text ui-widget-content ui-corner-all" /></li>
                    <li><label></label>
                        <?php
                        if ($row['image'] != "") {
                            echo '<img src="../'._url_sitios_web_img_. $row['image'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function updateSitios() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../'._url_sitios_web_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(240, 165, $nombre);
            $image = ",image='".$nombre."'";
        }
        
        $anquery = new Consulta("DELETE FROM deportes_sitios_web WHERE id_sitio_web = '" . $_GET['id'] . "'");
        $array = $_POST['deporte'];
        foreach ($array as $value) {
            $sql2 = "INSERT INTO deportes_sitios_web(id_deporte,id_sitio_web) 
                VALUES (
                   '" . $value . "',
                   '" . $_GET['id'] . "')";
            $query2 = new Consulta($sql2);
        }

        $query = new Consulta("UPDATE sitios_web SET  
                            titulo_sitio_web='" . addslashes($_POST['titulo_sitio_web']) . "',
                            url_sitio_web='" . addslashes($_POST['url_sitio_web']) . "',
                            descripcion_sitio_web='" . addslashes($_POST['descripcion_sitio_web']) . "'
                            " . $image . " WHERE id_sitio_web = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente.', 2);
        location("sitios_web.php");
    }

    public function deleteSitios() {
        $this->deleteArchivo($_GET['id']);
        
        $query2 = new Consulta("DELETE  FROM sitios_web WHERE id_sitio_web = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("sitios_web.php");
    }
    
    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM sitios_web WHERE id_sitio_web = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _url_sitios_web_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

    public function listSitiosxDeporte($idDeporte) {
        if ($idDeporte == 0) {
            $sql = "SELECT * FROM sitios_web";
        } else {
            $sql = "SELECT * FROM deportes_sitios_web INNER JOIN sitios_web USING(id_sitio_web)
                    WHERE id_deporte = " . $idDeporte;
        }

        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            $y = 1;
            while ($rowp = $query->VerRegistro()) {
                ?>
                <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_sitio_web'] . "|mod"; ?>">
                    <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['titulo_sitio_web']) . "</b>" ?></div>
                    <div class="options">
                        <a title="Editar" class="tooltip" href="sitios_web.php?id=<?php echo $rowp['id_sitio_web'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a title="Eliminar" class="tooltip" onClick="mantenimiento('sitios_web.php','<?php echo $rowp['id_sitio_web'] ?>','delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                    </div>
                </li>
                <?php
                $y++;
            }
        } else {
            echo "<li>No se encontró ningún registro</li>";
        }
    }

    public function listSitios() {
        ?>
        <div id="content-area">
            Listar por: 
            <select id="cbo_filtro">
                <option value="0">Todos</option>
                <?php
                $deportes = new Deportes();
                foreach ($deportes->getDeportes("", "deportes_sitios_web") as $value) {
                    echo '<option value="' . $value['id_deporte'] . '">' . $value['nombre_deporte'] . '</option>';
                }
                ?>
            </select>
            <input type="button" id="btn_filtro" value="Buscar" onclick="javascript: buscarXfiltro('sitios_web')"/><br/><br/>
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Sitios web</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM sitios_web ";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_sitio_web'] . "|mod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['titulo_sitio_web']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="sitios_web.php?id=<?php echo $rowp['id_sitio_web'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('sitios_web.php','<?php echo $rowp['id_sitio_web'] ?>','delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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
