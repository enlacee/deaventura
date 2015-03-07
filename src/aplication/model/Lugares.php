<?php

class Lugares {

    private $_msgbox;

    public function __construct(Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
    }

    public function newLugares() {
        /* $query = new Consulta("SELECT * FROM rutas");
          Form::getForm($query, "new", "rutas.php", "", "", ""); */
        ?>
        <fieldset id="form">
            <legend> Nuevo Registro</legend>			
            <form id="lugares" method="post" action="lugares.php?action=add&id=<?php echo $_GET['id'] ?>"> 
                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="submit" name="guardar" value="GUARDAR"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre: </strong></label><input type="text" name="nombre_lugar" id="nombre_ruta" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar: </strong></label><input type="text" name="ubicacion_lugar" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Latitud: </strong></label><input type="text" name="lat_lugar" id="lat_lugar" readonly ></li> 
                    <li><label><strong> Longitud: </strong></label><input type="text" name="lng_lugar" id="lng_lugar" readonly></li> 
                </ul>
            </form>
            <p class="edit_information"><img src="<?php echo _admin_ ?>exclamation.png" style="vertical-align: sub;"> Puede dar click y/o arrastrar la viñeta en el mapa.</p>
            <ul>
                <li><label><strong> Buscar Lugar: </strong></label><input type="text" name="origen" id="address" size="59" /><input type="button" name="Submit" value="Buscar la Ruta" onclick="calcularRuta($('#address').val())"/></li>
            </ul>
            <div id="map_canvas" style="width:843px;height:380px;float:left;"></div>
        </fieldset>
        <?php
    }

    public function addLugares() {
        $sql = "INSERT INTO lugares(nombre_lugar,ubicacion_lugar,lat_lugar,lng_lugar) 
                VALUES (
                   '" . clean_esp(htm_sql($_POST['nombre_lugar'])) . "',
                   '" . addslashes($_POST['ubicacion_lugar']) . "',
                   '" . $_POST['lat_lugar'] . "',
                   '" . $_POST['lng_lugar'] . "')";
        $query = new Consulta($sql);
        location("lugares.php");
    }

    public function editLugares() {
        $query = new Consulta("SELECT nombre_lugar, ubicacion_lugar, lat_lugar, lng_lugar FROM lugares WHERE id_lugar = '" . $_GET['id'] . "'");
        $row = $query->VerRegistro();
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form id="lugares" method="post" action="lugares.php?action=update&id=<?php echo $_GET['id'] ?>"> 
                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="submit" name="actualizar" value="GUARDAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre: </strong></label><input type="text" name="nombre_lugar" id="nombre_ruta" value="<?php echo sql_htm($row['nombre_lugar']) ?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Lugar: </strong></label><input type="text" name="ubicacion_lugar" value="<?php echo $row['ubicacion_lugar'] ?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Latitud: </strong></label><input type="text" name="lat_lugar" id="lat_lugar" readonly value="<?php echo $row['lat_lugar'] ?>"></li> 
                    <li><label><strong> Longitud: </strong></label><input type="text" name="lng_lugar" id="lng_lugar" readonly value="<?php echo $row['lng_lugar'] ?>"></li> 
                </ul>
            </form>
            <p class="edit_information"><img src="<?php echo _admin_ ?>exclamation.png" style="vertical-align: sub;"> Puede dar click y/o arrastrar la viñeta en el mapa.</p>
            <ul>
                <li><label><strong> Buscar Lugar: </strong></label><input type="text" name="origen" id="address" size="59" /><input type="button" name="Submit" value="Buscar la Ruta" onclick="calcularRuta($('#address').val())"/></li>
            </ul>
            <div id="map_canvas" style="width:843px;height:380px;float:left;"></div>
        </fieldset>
        <?php
    }

    public function updateLugares() {
        $sql = "UPDATE lugares SET  
                            nombre_lugar='" . clean_esp(htm_sql($_POST['nombre_lugar'])) . "',
                            ubicacion_lugar='" . addslashes($_POST['ubicacion_lugar']) . "',
                            lat_lugar='" . $_POST['lat_lugar'] . "',
                            lng_lugar='" . $_POST['lng_lugar'] . "'
                            WHERE id_lugar = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);

        $this->_msgbox->setMsgbox('Se actualizó correctamente.', 2);
        location("lugares.php");
    }

    public function deleteLugares() {
        $query = new Consulta("DELETE FROM lugares WHERE id_lugar = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("lugares.php");
    }

    public function listLugares() {

        $sql = "SELECT * FROM lugares ORDER BY nombre_lugar ASC";

        $query = new Consulta($sql);
        ?>
        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Lugares</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_lugar'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_lugar']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="lugares.php?id=<?php echo $rowp['id_lugar'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('lugares.php','<?php echo $rowp['id_lugar'] ?>','delete'); return false"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                            <a title="Rutas" class="tooltip" href="rutas.php?idl=<?php echo $rowp['id_lugar'] ?>"> <img src="<?php echo _admin_ ?>rutas.png"></a>&nbsp;
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
