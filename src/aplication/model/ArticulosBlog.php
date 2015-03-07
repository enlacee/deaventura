<?php

class ArticulosBlog {

    private $_msgbox;
    private $_id_deporte;

    public function __construct(Msgbox $msg = NULL, $id_deporte = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id_deporte;
    }

    public function newArticulosBlog() {
        ?>
        <form name="ArticulosBlog" method="post" action="" enctype="multipart/form-data"> 
            <fieldset id="form">
                <legend> Nuevo Registro</legend>			


                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_ArticulosBlog('add', '')">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_articulo_blog" id="titulo_articulo_blog" value="" size="59" maxlength="150"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Enlace : </strong></label><input type="text" name="enlace_articulo_blog" value="" size="59" maxlength="100"></li> 
                    <li><label><strong> Fecha : </strong></label><input type="text" name="fecha_articulo_blog" id="fecha_articulo_blog" size="12" class="date"></li>
                    <li><label><strong> Deporte: </strong></label><br/>
                        <?php
                        $deportes = new Deportes();
                        $deportes->get_lista_check();
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_articulo_blog"  class="textarea tinymce" id="descripcion_proveedor" style="height: 230px"></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tags_articulo_blog" id="tagsd" size="80" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                </ul>

            </fieldset>
            <br><br>
              
        </form>
        <?php
    }

    public function addArticulosBlog() {
        $nombre = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, _link_articulo_blog_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(412, 275, $nombre);
        }
        
        $user = $_SESSION['usuario'];
		
        $fecha = explode("/", $_POST['fecha_articulo_blog']); 
        foreach ($_POST['deporte'] as $value) {  $id_deporte = $value; }
        
        $sql = "INSERT INTO articulos_blog( id_articulo_blog,
                                            id_deporte,
                                            titulo_articulo_blog, 
                                            descripcion_articulo_blog, 
                                            enlace_articulo_blog, 
                                            fecha_articulo_blog, 
                                            imagen_articulo_blog, 
                                            tags_articulo_blog) 
                VALUES (   '','".$id_deporte."',
                           '" . clean_esp(htm_sql($_POST['titulo_articulo_blog'])) . "',
                           '" . addslashes(html_entity_decode($_POST['descripcion_articulo_blog'])) . "',
                           '" . addslashes($_POST['enlace_articulo_blog']) . "',
                           '" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "',
                           '" . $nombre . "',
                           '" . addslashes($_POST['tags_articulo_blog'])."'
                                           )";
        $query = new Consulta($sql);
        $id = $query->nuevoId();

        $this->_msgbox->setMsgbox('Se agregó correctamente.', 2);
        location("articulosdeblog.php?");
    }

    public function editArticulosBlog() {
        $sql = "SELECT * FROM articulos_blog WHERE id_articulo_blog = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $row = $query->VerRegistro();

        $time = explode("-", $row['fecha_articulo_blog']);
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form name="ArticulosBlog" method="post" action="" enctype="multipart/form-data"> 

                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR">  
                    <input type="button" name="actualizar" value="GUARDAR" onclick="return valida_ArticulosBlog('update', '<?php echo $_GET['id'] ?>')"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Titulo : </strong></label><input type="text" name="titulo_articulo_blog" id="titulo_articulo_blog" value="<?php echo sql_htm($row['titulo_articulo_blog']) ?>" size="59" maxlength="150"><em>* Solo números y letras</em></li> 
                    <li><label><strong> Enlace : </strong></label><input type="text" name="enlace_articulo_blog" value="<?php echo $row['enlace_articulo_blog'] ?>" size="59" maxlength="100"></li> 
                    <li><label><strong> Fecha : </strong></label><input type="text" name="fecha_articulo_blog" id="fecha_articulo_blog" value="<?php echo $time[0] . '/' . $time[1] . '/' . $time[2] ?>" size="12" class="date"></li>
                    <li><label><strong> Deporte: </strong></label> 
                        <?php
                        $deporte = new Deporte($row['id_deporte']);
                        echo $deporte->__get("_nombre_deporte");
                        ?>
                    </li>
                    <li><label><strong> Descripción : </strong></label><textarea name="descripcion_articulo_blog"  class="textarea tinymce" id="descripcion_proveedor" style="height: 230px"><?php echo sql_htm($row['descripcion_articulo_blog']) ?></textarea></li>
                    <li><label><strong> Tags : </strong></label><input name="tags_articulo_blog" id="tagsd" size="80" value="<?php echo sql_htm($row['tags_articulo_blog']) ?>" /></li>
                    <li><label><strong> Imagen : </strong></label> <input name="image" id="image" type="file" /></li>
                    <li><label></label>
                        <?php
                        if ($row['imagen_articulo_blog'] != "") {
                            echo '<img src="../' . _url_articulo_blog_img_ . $row['imagen_articulo_blog'] . '" />';
                        }
                        ?></li>
                </ul>
            </form>
        </fieldset>
        <br><br> 
        <?php
    }

    public function updateArticulosBlog() {
        $image = "";
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            $this->deleteArchivo($_GET['id']);

            $ext = explode('.', $_FILES['image']['name']);
            $nombre_file = time() . sef_string($ext[0]);
            $type_file = typeImage($_FILES['image']['type']);
            $nombre = $nombre_file . $type_file;

            define("NAMETHUMB", "/tmp/thumbtemp");
            $thumbnail = new ThumbnailBlob(NAMETHUMB, '../' . _url_articulo_blog_img_);
            $thumbnail->SetTransparencia(false); // Si tiene transparencia habilitar esta opcion con 'true'
            $datos = $thumbnail->CreateThumbnail(412, 275, $nombre);
            $image = ",image='" . $nombre . "'";
        }

        $fecha = explode("/", $_POST['fecha_articulo_blog']);
        $query = new Consulta("UPDATE articulos_blog SET titulo_articulo_blog='" . clean_esp(htm_sql($_POST['titulo_articulo_blog'])) . "',
                            enlace_articulo_blog='" . addslashes($_POST['enlace_articulo_blog']) . "',
                            descripcion_articulo_blog='" . addslashes(html_entity_decode($_POST['descripcion_articulo_blog'])) . "',
                            tags_articulo_blog = '".addslashes($_POST['tagsd'])."',
                            fecha_articulo_blog='" . $fecha[0] . '-' . $fecha[1] . '-' . $fecha[2] . "' 
                            " . $image . " WHERE id_articulo_blog = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la organización.', 2);
        location("articulosdeblog.php");
    }

    public function deleteArticulosBlog() {
        $this->deleteArchivo($_GET['id']);

        $query1 = new Consulta("DELETE FROM articulos_blog WHERE id_articulo_blog = '" . $_GET['id'] . "'"); //Son los ArticulosBlog() por cliente Si estará ahi o no
        
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("articulosdeblog.php");
    }

    public function deleteArchivo($id) {
        $query = new Consulta("SELECT image FROM articulos_blog WHERE id_articulo_blog = '" . $id . "'");
        $row = $query->VerRegistro();

        if ($row['image'] != '') {
            $nombre = _link_articulo_blog_img_ . $row['image'];
            if (file_exists($nombre)) {
                unlink($nombre);
            }
        }
    }

     

    public function listArticulosBlog(){  ?>
        <div id="content-area">
    
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Articulos de Blog</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $sql = "SELECT * FROM articulos_blog ORDER BY titulo_articulo_blog ASC";
                $query = new Consulta($sql);
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_articulo_blog'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['titulo_articulo_blog']) . "</b>" ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="articulosdeblog.php?id=<?php echo $rowp['id_articulo_blog'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('ArticulosBlog().php', '<?php echo $rowp['id_articulo_blog'] ?>', 'delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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

    public function getArticulosBlog() {
       
        $sql = "SELECT id_articulo_blog, titulo_articulo_blog, fecha_articulo_blog, nombre_deporte
                FROM articulos_blog 
                INNER JOIN deportes_ArticulosBlog USING(id_articulo_blog)
                INNER JOIN deportes USING(id_deporte) " . $ext ."
                ORDER BY fecha_articulo_blog ASC ";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_articulo_blog' => $row['id_articulo_blog'],
                    'titulo_articulo_blog' => sql_htm($row['titulo_articulo_blog']),
                    'fecha_articulo_blog' => $row['fecha_articulo_blog'],
                    'nombre_deporte' => sql_htm($row['nombre_deporte'])
                );
            }
        }
        return $data;
    }

    /* PARTE USUARIO */

    public function listarArticulosBlog_usuario($tipo) {

        if ($tipo == 'pasados') {
            $tipo = '>';
        } else if ($tipo == 'futuros') {
            $tipo = '<';
        } else {
            $tipo = '>';
        }


        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $dia = array('Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miercoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sabado', 'Sunday' => 'Domingo');

        $sqq = "SELECT YEAR(fecha_articulo_blog) AS anio FROM deportes_ArticulosBlog()
                INNER JOIN ArticulosBlog USING(id_articulo_blog)
                WHERE id_deporte = " . $this->_id_deporte . "
                GROUP BY anio DESC";
        $query2 = new Consulta($sqq);

        while ($row = $query2->VerRegistro()) {
            $arr_anios[] = $row["anio"];
        }

        $dep = new Deporte($this->_id_deporte);
        $ms = "";
        $flag = true;
        for ($i = 0; $i < count($arr_anios); $i++) {
            for ($j = 0; $j <= 11; $j++) {
                $sqln = "SELECT *, DAYNAME(fecha_articulo_blog) AS dia 
                        FROM deportes_ArticulosBlog INNER JOIN ArticulosBlog USING(id_articulo_blog) 
                        WHERE id_deporte = " . $this->_id_deporte . " AND fecha_articulo_blog " . $tipo . " NOW() AND DATE_FORMAT( fecha_articulo_blog,  '%m' ) = " . ($j + 1) . " AND DATE_FORMAT( fecha_articulo_blog,  '%Y' ) = " . $arr_anios[$i];

                $query = new Consulta($sqln);
                while ($row = $query->VerRegistro()) {
                    $nfecha = explode("-", $row['fecha_articulo_blog']);
                    if ($flag == true) {
                        $ms.= '<div class="titulo_eve">' . $meses[$j] . ', ' . $arr_anios[$i] . '</div>';
                        $flag = false;
                    }
                    $link = 'ArticulosBlog-de-' . url_friendly(sql_htm($dep->__get("_nombre_deporte")), 1) . '/' . url_friendly(sql_htm($row['titulo_articulo_blog']), 1);
                    $ms.= '<div class="eve_d"><div class="eve_fech">' . $dia[$row['dia']] . '<span>' . $nfecha[2] . '</span></div><div class="eve_titulo"><a href="' . $link . '">' . sql_htm($row['titulo_articulo_blog']) . '</a></div></div>';
                }
                $flag = true;
            }
        }
        return $ms;
    }
	
	public function getArticulosBlogDeporte($id_deporte){
        $sql = "SELECT id_articulo_blog, titulo_articulo_blog, fecha_articulo_blog, deportes.nombre_deporte, enlace_articulo_blog
                FROM articulos_blog  
                INNER JOIN deportes USING(id_deporte)
                WHERE articulos_blog.id_deporte='".$id_deporte."'
                GROUP BY id_articulo_blog
                ORDER BY fecha_articulo_blog DESC ";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_articulo_blog' => $row['id_articulo_blog'],
                    'titulo_articulo_blog' => sql_htm($row['titulo_articulo_blog']),
                    'enlace_articulo_blog' => $row['enlace_articulo_blog'],
                    'fecha_articulo_blog'  => $row['fecha_articulo_blog'],
                    'nombre_deporte'       => sql_htm($row['nombre_deporte']),
                    'imagen_articulo_blog' => $row['image']					
                );
            }
        }
        return $data;
	}
	
	public function url_articulo_blog($deporte, $id, $titulo) {
        $url_aventura = 'ArticulosdeBlog-de-' . url_friendly($deporte, 1) . '/' . url_friendly($titulo, 1);
        return $url_aventura;
    }

}
?>