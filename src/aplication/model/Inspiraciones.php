<?php

class Inspiraciones {

    private $_msgbox, $_idioma, $_usuario;

    public function __construct($msg = '', $idioma = '', $user = '') {
        $this->_msgbox = $msg;
        $this->_idioma = $idioma;
        $this->_usuario = $user;
    }

    public function newInspiraciones() {
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $i = Form::select($deportes,"deporte","id_deporte","nombre_deporte");
        $matrix	= array(1 => $i);
        
        $query = new Consulta("SELECT id_inspiracion ,id_deporte ,insight_inspiracion ,imagen_inspiracion ,tags_inspiracion FROM  inspiraciones ");
        echo "<div class='success' style='padding:10px;'> &nbsp;&nbsp;&nbsp;&nbsp; Subir imagenes con tamaño aproximado w = 600px x h 350px  pixeles. </div>";
        $obj_form = new Form();
        $obj_form->getForm($query, 'new', 'inspiraciones.php', $matrix, '', 'img');
    }

    public function editInspiraciones() {
        
        $obj_inspiracion = new Inspiracion($_GET['id']);
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $i = Form::select($deportes,"deporte","id_deporte","nombre_deporte",$obj_inspiracion->__get("_deporte")->__get("_id_deporte"));
        $matrix	= array(1 => $i);
        
        $query = new Consulta("SELECT id_inspiracion ,id_deporte ,insight_inspiracion ,imagen_inspiracion ,tags_inspiracion FROM  inspiraciones WHERE id_inspiracion = '" . $_GET['id'] . "'   ");
        $obj_form = new Form();
        $obj_form->getForm($query, 'edit', 'inspiraciones.php', $matrix, '', 'img',_catalogo_);
    }

    public function addInspiraciones() {
        if (isset($_FILES['imagen_inspiracion']) && ($_FILES['imagen_inspiracion']['name'] != '')) {
            $obj = new Upload();
            $destino = "../aplication/webroot/imgs/catalogo/image_inspiraciones/";
            $name3 = strtolower(date("ymdhis") . $_FILES['imagen_inspiracion']['name']);
            $temp = $_FILES['imagen_inspiracion']['tmp_name'];
            $type = $_FILES['imagen_inspiracion']['type'];
            $size = $_FILES['imagen_inspiracion']['size'];
            $obj->upload_imagen($name3, $temp, $destino, $type, $size);
        }
        
        $insight_url = url_friendly($_POST['insight_inspiracion'],2);
        $insight_url = trim($insight_url);
        $insight_url = str_replace('"', "", $insight_url);
        $insight_url = str_replace('!', "", $insight_url);

        $query = new Consulta("INSERT INTO  inspiraciones(id_inspiracion ,id_deporte ,insight_inspiracion ,url_inspiracion, order_inspiracion ,tags_inspiracion ,imagen_inspiracion) VALUES (
 '" . $_POST['id_inspiracion'] . "'  ,
 '" . $_POST['id_deporte'] . "'  ,
 '" . $_POST['insight_inspiracion'] . "'  ,
 '" . $insight_url . "'  ,    
 '" . $_POST['order_inspiracion'] . "'  ,
 '" . $_POST['tags_inspiracion'] . "'  ,
 '" . $name3 . "'  )");
        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("inspiraciones.php");
    }

    public function updateInspiraciones() {
        if (isset($_FILES['imagen_inspiracion']) && ($_FILES['imagen_inspiracion']['name'] != '')) {
            $obj = new Upload();
            $destino = "../aplication/webroot/imgs/catalogo/image_inspiraciones/";
            $name3 = strtolower(date("ymdhis") . $_FILES['imagen_inspiracion']['name']);
            $temp = $_FILES['imagen_inspiracion']['tmp_name'];
            $type = $_FILES['imagen_inspiracion']['type'];
            $size = $_FILES['imagen_inspiracion']['size'];
            $obj->upload_imagen($name3, $temp, $destino, $type, $size);
            $update = " imagen_inspiracion = '" . $name3 . "' ";
            $query = new Consulta("UPDATE  inspiraciones SET " . $update . " WHERE id_inspiracion = '" . $_GET['id'] . "'");
        }
        
        $insight_url = url_friendly($_POST['insight_inspiracion'],2);
        $insight_url = trim($insight_url);
        $insight_url = str_replace('"', "", $insight_url);
        $insight_url = str_replace('!', "", $insight_url);
        
        $query = new Consulta("UPDATE inspiraciones SET 
                                    id_deporte = '" . $_POST['id_deporte'] . "', 
                                    insight_inspiracion = '" . $_POST['insight_inspiracion'] . "', 
                                    url_inspiracion = '" . $insight_url . "', 
                                    tags_inspiracion = '" . $_POST['tags_inspiracion'] . "'  
                               WHERE  id_inspiracion = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se actualizo correctamente.', 2);
        location("inspiraciones.php");
    }

    public function deleteInspiraciones() {
        $this->deleteFilesInspiraciones($_GET['id']);
        $query = new Consulta("DELETE FROM inspiraciones WHERE id_inspiracion = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("inspiraciones.php");
    }

    public function deleteFilesInspiraciones($id) {
        $query = new Consulta("SELECT * FROM inspiraciones WHERE id_inspiracion = '" . $id . "'");
        $row = $query->VerRegistro();
        if ($row['imagen_inspiracion'] != '') {
            if (file_exists(_link_file_ . $row['imagen_inspiracion'])) {
                unlink(_link_file_ . $row['imagen_inspiracion']);
            }
        }
    }

    public function listInspiraciones() {
        $generico = array();
        $generico = $this->getInspiraciones();
        ?><div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th align="left">Inspiraciones</th>
                        <th align="center" width="100" class="titulo">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul" data-orden="ordenarInspiraciones"><!-- COPIAR  EN aplication/model/Ajax.php 
            function ordenarInspiracionesAjax(){
            foreach($_GET['list_item'] as $position => $item){
            $query = new Consulta("UPDATE inspiraciones SET order_inspiracion = $position WHERE id_inspiracion = $item"); 
            }
            }
                -->
                <?php
                $y = 1;
                foreach ($generico as $b) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $b['id']; ?>"> 
                        <div class="data"><img style="vertical-align: middle;" src="<?php echo _admin_ ?>icon_banner.png" class="handle"> <?php echo $b['insight'] ?></div>
                        <div class="options">
                            <a class="tooltip move" title="Ordenar ( Click + Arrastrar )"><img src="<?php echo _admin_ ?>move.png" class="handle"></a>&nbsp;
                            <a title="Editar" class="tooltip" href="inspiraciones.php?id=<?php echo $b['id'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a>&nbsp;
                            <a title="Eliminar"  href="#" class="tooltip" onClick="mantenimiento('inspiraciones.php','<?php echo $b['id'] ?>','delete')"><img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;    
                        </div>
                    </li>
                    <?php
                    $y++;
                }
                ?>
            </ul>
        </div>
        <?php
    }

    public function getInspiraciones() {
        $sql = " SELECT * FROM inspiraciones ORDER BY id_inspiracion DESC";
        $query = new Consulta($sql);
        $datos = array();

        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id' => $row['id_inspiracion'],
                'deporte'   => new Deporte($row['id_deporte']),
                'insight'   => $row['insight_inspiracion'],
                'url'       => $row['url_inspiracion'],
                'imagen'    => $row['imagen_inspiracion'],
                'order'     => $row['order_inspiracion'],
                'tags'      => $row['tags_inspiracion']);
        }
        return $datos;
    }

    public function orderInspiraciones($id=0) {
        $query = new Consulta("SELECT MAX(order_inspiracion) max_orden FROM inspiraciones WHERE id_inspiracion = '" . $id . "'");
        $row = $query->VerRegistro();
        return (int) ($row['max_orden'] + 1);
    }

}
?>