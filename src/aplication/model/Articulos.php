<?php

class Articulos {

    private $_msgbox, $_idioma, $_usuario;

    public function __construct($msg = '', $idioma = '', $user = '') {
        $this->_msgbox = $msg;
        $this->_idioma = $idioma;
        $this->_usuario = $user;
    }

    public function newArticulos() {
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $i = Form::select($deportes,"deporte","id_deporte","nombre_deporte");
        $matrix	= array(1 => $i);
        
        $query = new Consulta("SELECT id_articulo ,id_deporte ,nombre_articulo ,descripcion_articulo ,imagen_articulo ,tags_articulo FROM  articulos ");		
        echo "<div class='success' style='padding:10px;'> &nbsp;&nbsp;&nbsp;&nbsp; Subir imagenes con tamaño aproximado w = 600px x h 350px  pixeles. </div>";
        $obj_form = new Form();
        $obj_form->getForm($query, 'new', 'articulos.php', $matrix, '', 'img');
    }

    public function editArticulos() {
        
        $obj_articulo = new Articulo($_GET['id']);
        $obj_deportes = new Deportes();
        $deportes = $obj_deportes->getDeportes();
        $i = Form::select($deportes,"deporte","id_deporte","nombre_deporte",$obj_articulo->__get("_deporte")->__get("_id_deporte"));
        $matrix	= array(1 => $i);
        
        $query = new Consulta("SELECT id_articulo ,id_deporte ,nombre_articulo , descripcion_articulo ,imagen_articulo ,tags_articulo FROM articulos WHERE id_articulo = '" . $_GET['id'] . "'   ");
        $obj_form = new Form();
        $obj_form->getForm($query, 'edit', 'articulos.php', $matrix, '', 'img',_catalogo_);
    }

    public function addArticulos() {
        if (isset($_FILES['imagen_articulo']) && ($_FILES['imagen_articulo']['name'] != '')) {
            $obj = new Upload();
            $destino = "../aplication/webroot/imgs/catalogo/image_articulos/";
            $name3 = strtolower(date("ymdhis") . $_FILES['imagen_articulo']['name']);
            $temp = $_FILES['imagen_articulo']['tmp_name'];
            $type = $_FILES['imagen_articulo']['type'];
            $size = $_FILES['imagen_articulo']['size'];
            $obj->upload_imagen($name3, $temp, $destino, $type, $size);
        }
        
        $nombre_url = url_friendly($_POST['nombre_articulo'],2);
        $nombre_url = trim($nombre_url);
        $nombre_url = str_replace('"', "", $nombre_url);
        $nombre_url = str_replace('!', "", $nombre_url);

        $query = new Consulta("INSERT INTO  articulos(id_articulo ,id_deporte ,nombre_articulo, descripcion_articulo ,url_articulo, order_articulo ,tags_articulo ,imagen_articulo) VALUES (
 '" . $_POST['id_articulo'] . "'  ,
 '" . $_POST['id_deporte'] . "'  ,
 '" . $_POST['nombre_articulo'] . "'  ,
 '" . $_POST['descripcion_articulo'] . "'  ,
 '" . $nombre_url . "'  ,    
 '" . $_POST['order_articulo'] . "'  ,
 '" . $_POST['tags_articulo'] . "'  ,
 '" . $name3 . "'  )");
        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("articulos.php");
    }

    public function updateArticulos() {
        if (isset($_FILES['imagen_articulo']) && ($_FILES['imagen_articulo']['name'] != '')) {
            $obj = new Upload();
            $destino = "../aplication/webroot/imgs/catalogo/image_articulos/";
            $name3 = strtolower(date("ymdhis") . $_FILES['imagen_articulo']['name']);
            $temp = $_FILES['imagen_articulo']['tmp_name'];
            $type = $_FILES['imagen_articulo']['type'];
            $size = $_FILES['imagen_articulo']['size'];
            $obj->upload_imagen($name3, $temp, $destino, $type, $size);
            $update = " imagen_articulo = '" . $name3 . "' ";
            $query = new Consulta("UPDATE articulos SET " . $update . " WHERE id_articulo = '" . $_GET['id'] . "'");
        }
        
        $nombre_url = url_friendly($_POST['nombre_articulo'],2);
        $nombre_url = trim($nombre_url);
        $nombre_url = str_replace('"', "", $nombre_url);
        $nombre_url = str_replace('!', "", $nombre_url);
        
        $query = new Consulta("UPDATE articulos SET 
                                    id_deporte = '" . $_POST['id_deporte'] . "', 
                                    nombre_articulo = '" . $_POST['nombre_articulo'] . "', 
                                    descripcion_articulo = '" . $_POST['descripcion_articulo'] . "', 
                                    url_articulo = '" . $nombre_url . "', 
                                    tags_articulo = '" . $_POST['tags_articulo'] . "'  
                               WHERE id_articulo = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se actualizo correctamente.', 2);
        location("articulos.php");
    }

    public function deleteArticulos() {
        $this->deleteFilesArticulos($_GET['id']);
        $query = new Consulta("DELETE FROM articulos WHERE id_articulo = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("articulos.php");
    }

    public function deleteFilesArticulos($id) {
        $query = new Consulta("SELECT * FROM articulos WHERE id_articulo = '" . $id . "'");
        $row = $query->VerRegistro();
        if ($row['imagen_articulo'] != '') {
            if (file_exists(_link_file_ . $row['imagen_articulo'])) {
                unlink(_link_file_ . $row['imagen_articulo']);
            }
        }
    }

    public function listArticulos() {
        $generico = array();
        $generico = $this->getArticulos();
        ?><div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th align="left">Articulos</th>
                        <th align="center" width="100" class="titulo">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul" data-orden="ordenarArticulos"><!-- COPIAR  EN aplication/model/Ajax.php 
            function ordenarArticulosAjax(){
            foreach($_GET['list_item'] as $position => $item){
            $query = new Consulta("UPDATE articulos SET order_articulo = $position WHERE id_articulo = $item"); 
            }
            }
                -->
                <?php
                $y = 1;
                foreach ($generico as $b) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $b['id']; ?>"> 
                        <div class="data"><img style="vertical-align: middle;" src="<?php echo _admin_ ?>icon_banner.png" class="handle"> <?php echo $b['nombre'] ?></div>
                        <div class="options">
                            <a class="tooltip move" title="Ordenar ( Click + Arrastrar )"><img src="<?php echo _admin_ ?>move.png" class="handle"></a>&nbsp;
                            <a title="Editar" class="tooltip" href="articulos.php?id=<?php echo $b['id'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a>&nbsp;
                            <a title="Eliminar"  href="#" class="tooltip" onClick="mantenimiento('articulos.php','<?php echo $b['id'] ?>','delete')"><img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;    
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

    public function getArticulos() {
        $sql = " SELECT * FROM articulos ORDER BY id_articulo DESC";
        $query = new Consulta($sql);
        $datos = array();

        while ($row = $query->VerRegistro()) {
            $datos[] = array(
                'id' => $row['id_articulo'],
                'deporte'   => new Deporte($row['id_deporte']),
                'nombre'   => $row['nombre_articulo'],
                'url'       => $row['url_articulo'],
                'imagen'    => $row['imagen_articulo'],
                'order'     => $row['order_articulo'],
                'tags'      => $row['tags_articulo']);
        }
        return $datos;
    }

    public function orderArticulos($id=0) {
        $query = new Consulta("SELECT MAX(order_articulo) max_orden FROM articulos WHERE id_articulo = '" . $id . "'");
        $row = $query->VerRegistro();
        return (int) ($row['max_orden'] + 1);
    }

}
?>