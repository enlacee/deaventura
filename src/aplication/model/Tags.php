<?php

class Tags {

    private $_msgbox;
    private $_usuario;
    private $_id_dep;

    public function __construct(Msgbox $msg = NULL, Usuario $usuario = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_usuario = $usuario;
        $this->_id_dep = $id_deporte;
    }

    public function listTags() {
        $sql = " SELECT * FROM tags WHERE id_deporte = " . $this->_id_dep . " ORDER BY visitas_tag";
        $query = new Consulta($sql);
        ?>
        <p class="edit_information"><img src="<?php echo _admin_ ?>edit.png"> Para editar debe dar click en el nombre.</p>

        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Tags</th>
                        <th class='titulo' align="left" width="284"># Busquedas</th>
                        <th class='titulo' align="center" width="84">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_tag']; ?>">
                        <div class="data"> 
                            <img src="<?php echo _admin_ ?>tag.png" class="handle">   
                            <b class="edit_now"><?php echo sql_htm($rowp['nombre_tag']) ?></b>
                            <input type="text" class="nom_edit" value="<?php echo sql_htm($rowp['nombre_tag']) ?>">
                            <img title="Guardar" class="save_event" src="../aplication/webroot/imgs/admin/save.png"/>
                            <img class="img_load" src="../aplication/webroot/imgs/ajax-loader.gif">
                        </div>
                        <div class="options">
                            <a title="Eliminar" class="tooltip" onClick="mantenimientoTag('tags.php','<?php echo $rowp['id_tag'] ?>','delete','<?php echo $this->_id_dep ?>')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;&nbsp;
                        </div>
                        <div class="cant_busq"><?php echo $rowp['visitas_tag']; ?></div>
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

    public function newTags() {
        ?>
        <fieldset id="form">
            <legend>Nuevo Tag</legend>
            <div class="button-actions">
                <input type="button" name="" value="GUARDAR" onclick="return valida_tags('add','')"  />
            </div>
            <form action="" method="post" name="form_tags"> 
                <ul>
                    <li>
                        <label><strong> Nombre del Tag: </strong></label>
                        <input type="text" class="text ui-widget-content ui-corner-all" size="50" id="nombre" name="nombre">
                        <input type="hidden" name="id_deporte" value="<?php echo $this->_id_dep ?>"/>
                    </li>
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function addTags() {
        $sqla = "INSERT INTO tags(id_deporte,nombre_tag) 
                VALUES (
                   '" . $_POST['id_deporte'] . "',
                   '" . htm_sql($_POST['nombre']) . "')";

        $query = new Consulta($sqla);

        $this->_msgbox->setMsgbox('Tag grabado correctamente', 2);
        location("tags.php?id_dep=" . $_POST['id_deporte']);
    }

    public function updateTags($id, $nombre) {
        $query = new Consulta("UPDATE tags SET  nombre_tag='" . htm_sql($nombre) . "' 
                               WHERE id_tag = '" . $id . "'");
    }

    public function deleteTags() {
        $query = new Consulta("DELETE  FROM tags WHERE id_tag = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location('tags.php?id_dep=' . $this->_id_dep);
    }

    public function getTags($id = "",$limit = 20) {
        if($id != ""){
            $sql = " SELECT * FROM tags WHERE id_deporte = " . $id . " ORDER BY nombre_tag DESC LIMIT 0,".$limit;
        }else{
            $sql = " SELECT * FROM tags  ORDER BY nombre_tag, visitas_tag DESC LIMIT 0,".$limit; //WHERE visitas_tag > 1
        }
        
        $query = new Consulta($sql);
        while ($row = $query->VerRegistro()) {
            $data[] = array(
                'id' => $row['id_tag'],
                'nombre_tag' => sql_htm($row['nombre_tag']),
                'visitas_tag' => $row['visitas_tag']
            );
        }
        return $data;
    }
    
    public function getTagsResto($datos) {
        if ($datos[0] != "") {
            $sql = " SELECT * FROM tags WHERE id_tag NOT IN (SELECT id_tag FROM " . $datos[2] . "_tags WHERE id_" . $datos[1] . " = " . $datos[0] . ")";
        } else { //solo para el caso de ingresar 
            $sql = "SELECT * FROM tags";
        }

        $query = new Consulta($sql);
        while ($row = $query->VerRegistro()) {
            $data[] = array(
                'id' => $row['id_tag'],
                'nombre_tag' => sql_htm($row['nombre_tag']),
                'visitas_tag' => $row['visitas_tag']
            );
        }
        return $data;
    }

    public function getTabsxTipo($datos) { //Metodo para imprimir tags que ya tiene 
        if ($datos[0] != "") {
            $sql = " SELECT id_tag, nombre_tag FROM " . $datos[2] . "_tags INNER JOIN tags USING(id_tag) WHERE id_" . $datos[1] . " = " . $datos[0];
            $query = new Consulta($sql);

            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id' => $row['id_tag'],
                    'nombre_tag' => sql_htm($row['nombre_tag'])
                );
            }
            return $data;
        }
    }

    public function viewTags_user($id, $vin_sin, $vin_plur) {
        ?>
        <fieldset id="tags">
            <legend> Vincular Tags</legend>
            <input type="hidden" value="<?php echo $id ?>" id="id_sec" rels="<?php echo $vin_sin ?>" relp ="<?php echo $vin_plur ?>">
            <div id="panel_tags">
                <ul>
                    <?php
                    $err = $this->getTagsResto(array($id,$vin_sin,$vin_plur));
                    if (!empty($err) && $err != "") {
                        foreach ($err as $value) {
                            ?>
                            <li rel="<?php echo $value['id'] ?>">
                                <div class="icadd" title="Agregar">+</div>
                                <span><?php echo $value['nombre_tag'] ?></span>
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
                $arr = $this->getTabsxTipo(array($id,$vin_sin,$vin_plur));
                if (!empty($arr) && $arr != "") {
                    foreach ($arr as $value) {
                        ?>
                        <p rel="<?php echo $value['id'] ?>"><span><?php echo $value['nombre_tag'] ?></span><em class="deltag" title="Eliminar">X</em></p>
                        <?php
                    }
                }
                ?>
            </div>
        </fieldset>
        <?php
    }

    static function mantenerTags($idSec, $idTag, $nom_sin, $nom_plu, $tipo) {
        if ($tipo == "add") {
            $sql = new Consulta("INSERT INTO " . $nom_plu . "_tags(id_" . $nom_sin . ",id_tag) VALUES ('" . $idSec . "','" . $idTag . "')");
            if ($sql->registrosAfectado()) {
                return 1;
            }
        } else if ($tipo == "delete") {
            $sql = new Consulta("DELETE FROM " . $nom_plu . "_tags WHERE id_" . $nom_sin . "='" . $idSec . "' AND id_tag='" . $idTag . "'");
            return 1;
        }
    }

}
?>
