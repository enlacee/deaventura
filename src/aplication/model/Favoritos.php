<?php

class Favoritos {

    private $_msgbox;
    private $_cuenta;

    public function __construct(Msgbox $msg = NULL,Cuenta $cuenta = NULL) {
        $this->_msgbox = $msg;
        $this->_cuenta = $cuenta;
    }

    public function addFavoritos($id) {
        $query = new Consulta("INSERT INTO favoritos (id_cliente,id_aventura) VALUES('" . $this->_cuenta->__get("_cliente")->__get("_id") . "','" . $id . "')");
    }

    public function listFavoritos($idCliente) {
        $sqlA = "SELECT * FROM favoritos WHERE id_cliente ='" . $idCliente . "'"; 
        $queryA = new Consulta($sqlA);
        if ($queryA->NumeroRegistros() != 0) {
            while ($row = $queryA->VerRegistro()) {
                $sql = "SELECT id_aventura, id_modalidad, cant_coment_aventura, cant_likes_aventura, COUNT(id_aventura) AS cant_images, nombre_cliente, titulo_aventura, fecha_creacion_aventura, nombre_aventuras_archivos 
                    FROM clientes 
                    INNER JOIN aventuras USING(id_cliente)
                    INNER JOIN aventuras_archivos USING(id_aventura)
                    WHERE id_aventura ='" . $row['id_aventura'] . "'";
                
                $queryp = new Consulta($sql);
                $rowp = $queryp->VerRegistro();
                
                $modalidad = new Modalidad($rowp['id_modalidad']);
                $aventura = new Aventuras();
                $link = _url_.$aventura->url_Aventura($modalidad->__get('_deporte')->__get('_nombre_deporte'), $rowp['id_aventura'], $rowp['titulo_aventura']);
                ?>
                <div class="aventura_panel favorite">
                    <div class="pnl1">
                        <img src="aplication/utilities/timthumb.php?src=<?php echo _url_avfiles_users_ . $rowp['nombre_aventuras_archivos'] ?>&h=100&w=100&zc=1"/></div>
                    <div class="pnl2">
                        <h1><?php echo $rowp['titulo_aventura'] ?><span><?php echo fecha_long($rowp['fecha_creacion_aventura']) ?></span></h1>
                        <ul class="info_social">
                            <li class="photo"><?php echo $rowp['cant_images'] ?></li><li class="coment"><?php echo $rowp['cant_coment_aventura'] ?></li><li class="like"><?php echo $rowp['cant_likes_aventura'] ?></li>
                        </ul><br/>
                        <a href="<?php echo $link ?>"><?php echo $link ?><img src="aplication/webroot/imgs/icon_mas.jpg" width="16" height="17"></a>
                        <br/>
                        <a class="elim_favoritos" href="#" title="Click para eliminar" rel="<?php echo $rowp['id_aventura'] ?>"><img src="aplication/webroot/imgs/icon_delete.png"> Sacar de mis favoritos</a>
                    </div>
                </div>
                <?php
            }
        }else{
            echo '<br/><div align="center">Aún no tienes aventuras para mosrtrar como favoritos</div>';
        }
        
    }

    function verificar_favoritos($id, $id_cliente) {
        $query = new Consulta("SELECT * FROM favoritos WHERE id_aventura = '" . $id . "' AND id_cliente = '" . $id_cliente . "'");
        if ($query->NumeroRegistros() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteFavoritos($id) {
        $query = new Consulta("DELETE  FROM favoritos WHERE id_aventura = '" . $id . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("deportes.php");
    }

}
?>
