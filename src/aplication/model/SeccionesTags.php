<?php
class SeccionesTags {

    private $_msgbox, $_idioma, $_usuario;

    public function __construct($msg = '', $idioma = '', $user = '') {
        $this->_msgbox = $msg;
        $this->_idioma = $idioma;
        $this->_usuario = $user;
    }

    public function newSeccionesTags() {

        //$deportes = new Deportes();
        $array_deportes = $this->getDeportesConSecciones();
        $secciones_sitio = new secciones_sitio();
        $array_secciones_sitio = $secciones_sitio->get_secciones_sitio();
        ?>
            <form name="secciones_tags" method="post" action="" enctype="multipart/form-data">

                <fieldset id="form">
                    <legend> Nuevo Registro</legend>	
                    <div class="button-actions">
                        <input type="button" name="" value="GUARDAR" onclick="return valida_secciones_tags('add', '')"  />
                        <input type="reset" name="" value="CANCELAR" />
                    </div><br/><br/>
                    <ul>
                        <li><label><strong> Deporte : </strong></label><?php echo Form::select($array_deportes, "deporte_seccion", "id_deporte_seccion", "nombre_deporte_seccion"); ?> </li> 
                         <li><label><strong>Tags : </strong></label><input type="text" name="tags" id="tags" value="" size="59" maxlength="100"></li> 
                        <li><label><strong> Titulo : </strong></label><input type="text" name="titulo" id="titulo" value="" size="59" maxlength="50"></li> 
                        <li><label><strong> Descripción : </strong></label><input type="text" name="descripcion" value="" size="59" maxlength="50"></li> 
                        <li><label><strong> Fuente de Tags : </strong></label>
                        <div id="fuente_tags"></div>
                        </li> 
                       
                    </ul>

                </fieldset>

                    <br> <br>
                </form>
        <?php
    }

    public function editSeccionesTags() {
        $id_seccion_tag = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $obj_seccion_tag = new SeccionTag($id_seccion_tag);
        $deportes = new Deportes();
        $array_deportes = $deportes->getDeportes();
        $secciones_sitio = new secciones_sitio();
        $array_secciones_sitio = $secciones_sitio->get_secciones_sitio();
        ?>
            <form name="secciones_tags" method="post" action="" enctype="multipart/form-data">

                <fieldset id="form">
                    <legend> Editar Registro</legend>	
                    <div class="button-actions">
                        <input type="button" name="" value="GUARDAR" onclick="return valida_secciones_tags('update', '<?php echo $obj_seccion_tag->__get("_id") ?>')"  />
                        <input type="reset" name="" value="CANCELAR" />
                    </div><br/><br/>
                    <ul>
                        <li><label><strong> Deporte : </strong></label><?php echo Form::select($array_deportes, "deporte", "id_deporte", "nombre_deporte", $obj_seccion_tag->__get("_id_deporte")); ?> </li> 
                        <li><label><strong> Tags : </strong></label><input type="text" name="tags" id="tags" value="<?php echo $obj_seccion_tag->__get("_tag"); ?>" size="59" maxlength="100"></li> 
                        <li><label><strong> Titulo : </strong></label><input type="text" name="titulo" id="titulo" value="" size="59" maxlength="50"></li> 
                        <li><label><strong> Descripción : </strong></label><input type="text" name="descripcion" value="" size="59" maxlength="50"></li> 
                        
                    </ul>

                </fieldset>

                    <br> <br>
                </form>
        <?php
    }

    public function addSeccionesTags() {
        
        $ids = explode(",",$_POST['id_deporte_seccion']);
        $id_seccion = $ids[1];
        $id_deporte = $ids[0];
        $query = new Consulta("INSERT INTO  secciones_tags(id_seccion_tag, id_deporte, id_seccion_sitio, tag_seccion_tag, order_seccion_tag) 
                                    VALUES ('', '" . $id_deporte . "', '" . $id_seccion . "','" . $_POST['tags'] . "'  ,'0')");
        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("secciones_tags.php");
    }

    public function updateSeccionesTags() {
        $id_seccion_tag = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING);
        $query = new Consulta("UPDATE secciones_tags SET 
     tag_seccion_tag = '" . $tags . "'  WHERE  id_seccion_tag = '" . $id_seccion_tag . "'");
        $this->_msgbox->setMsgbox('Se actualizo correctamente.', 2);
        location("secciones_tags.php");
    }

    public function deleteSeccionesTags() {
        $this->deleteFilesSeccionesTags($_GET['id']);
        $query = new Consulta("DELETE FROM secciones_tags WHERE id_seccion_tag = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("secciones_tags.php");
    }

    public function deleteFilesSeccionesTags($id) {
        $query = new Consulta("SELECT * FROM secciones_tags WHERE id_seccion_tag = '" . $id . "'");
        $row = $query->VerRegistro();
    }

    public function listSeccionesTags() {
        $sql = "SELECT id_seccion_tag, nombre_deporte, nombre_seccion_sitio, tag_seccion_tag, order_seccion_tag FROM deportes d, secciones_sitio ss, secciones_tags st WHERE d.id_deporte = st.id_deporte AND ss.id_seccion_sitio = st.id_seccion_sitio GROUP BY st.id_seccion_tag ORDER BY d.nombre_deporte";
        $query = new Consulta($sql);
        ?> 
        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
            <thead>
            <tr class="head">
            <th align="left">Secciones Tags</th>
            <th align="center" width="100" class="titulo">Opciones</th>
            </tr>
            </thead>
            </table>
            <ul id="listadoul" data-orden="ordenarSeccionesTags"> <?php
        $y = 1;
        while ($row = $query->VerRegistro()) {
            ?>
            <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $row['id_seccion_tag']; ?>"> 
            <div class="data"><img style="vertical-align: middle;" src="<?php echo _admin_ ?>icon_banner.png" class="handle"> <?php echo $row['nombre_deporte'] ?> > <?php echo $row['nombre_seccion_sitio'] ?> > <?php echo $row['tag_seccion_tag'] ?> </div>
            <div class="options">
            <a class="tooltip move" title="Ordenar ( Click + Arrastrar )"><img src="<?php echo _admin_ ?>move.png" class="handle"></a>&nbsp;
            <a title="Editar" class="tooltip" href="secciones_tags.php?id=<?php echo $row['id_seccion_tag'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a>&nbsp;
            <a title="Eliminar"  href="#" class="tooltip" onClick="mantenimiento('secciones_tags.php', '<?php echo $row['id_seccion_tag'] ?>', 'delete')"><img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;    
            </div>
            </li>  <?php
            $y++;
        }
        ?>
                                        </ul>
                                        </div>  <?php
    }

    public function getSeccionesTags() {
        $sql = " SELECT * FROM secciones_tags ORDER BY order_seccion_tag ASC";
        $query = new Consulta($sql);
        $datos = array();

        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id' => $row['id_seccion_tag'],
                'id_deporte' => $row['id_deporte'],
                'id_seccion_sitio' => $row['id_seccion_sitio'],
                'tag' => $row['tag_seccion_tag']);
        }
        return $datos;
    }

    public function getSeccionesTagsPorDeporte($id_deporte = 0, $id_seccion_sitio = 0) {


        $where = "";
        $where .= $id_deporte > 0 ? "AND id_deporte = '" . $id_deporte . "'" : "";
        $where .= $id_seccion_sitio > 0 ? " AND id_seccion_sitio = '" . $id_seccion_sitio . "'" : "";

        $sql = " SELECT * FROM secciones_tags WHERE id_seccion_tag > 0 " . $where . " ORDER BY order_seccion_tag ASC";
        //echo $sql;
        $query = new Consulta($sql);
        $datos = array();

        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id' => $row['id_seccion_tag'],
                'id_deporte' => $row['id_deporte'],
                'id_seccion_sitio' => $row['id_seccion_sitio'],
                'tag' => $row['tag_seccion_tag']);
        }
        return $datos;
    }

    public function orderSeccionesTags($id = 0) {
        $query = new Consulta("SELECT MAX(order_seccion_tag) max_orden FROM secciones_tags WHERE id_seccion_tag = '" . $id . "'");
        $row = $query->VerRegistro();
        return (int) ($row['max_orden'] + 1);
    }
    
    public function getDeportesConSecciones(){
        $sql = "SELECT concat(d.id_deporte,',',ss.id_seccion_sitio) as id, concat(d.nombre_deporte,' > ',ss.nombre_seccion_sitio) as nombre FROM deportes d, secciones_sitio ss ORDER BY nombre";
        $query = new Consulta($sql);
        
        $datos = array();
        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id_deporte_seccion'        => $row['id'],
                'nombre_deporte_seccion'  => $row['nombre']);
        }
        return $datos;
        
        
    }

}
?>