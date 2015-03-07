<?php

class Modalidades {

    private $_msgbox;
    private $_id_deporte;

    public function __construct($id, Msgbox $msg = NULL) {
        $this->_msgbox = $msg;
        $this->_id_deporte = $id;
    }

    public function newModalidades() {
        $query = new Consulta("SELECT id_modalidad,nombre_modalidad,descripcion_modalidad FROM modalidades");
        Form::getForm($query, "new", "modalidades.php", "", "", "");
    }

    public function addModalidades() {
        $sql = "INSERT INTO modalidades(id_deporte,nombre_modalidad,descripcion_modalidad)
                VALUES ('" . $this->_id_deporte . "',
                '" . addslashes(htm_sql($_POST['nombre_modalidad'])) . "',
                '" . addslashes($_POST['descripcion_modalidad']) . "')";
        $query = new Consulta($sql);
        location("modalidades.php?id_dep=" . $this->_id_deporte);
    }

    public function editModalidades() {
        $query = new Consulta("SELECT id_modalidad,nombre_modalidad,descripcion_modalidad FROM modalidades WHERE id_modalidad = '" . $_GET['id'] . "'");
        //Form::getForm($query, "edit", "modalidades.php", "", "", "");
        $row = $query->VerRegistro();
        ?>
        <script type="text/javascript">
        function valida_modalidades(){               
            if(document.modalidades.nombre_modalidad.value==""){
                alert('ERROR: El campo nombre modalidad debe llenarse');
                document.modalidades.nombre_modalidad.focus(); 
                return false;
            }            
            document.modalidades.action="modalidades.php?action=update&id=<?php echo $_GET['id'] ?>";
            document.modalidades.submit();
        }			
        </script>  
        <fieldset id='form'>
            <legend> Editar Registro</legend>			
            <form name='modalidades' method='post' action=''  > 

                <div class='button-actions'>
                    <input type='reset' name='cancelar' value='CANCELAR' class='button' >  
                    <input type='button' name='actualizar' value='ACTUALIZAR' onclick='return valida_modalidades()' class='button'><br clear='all' />
                </div>
                <ul> 
                    <li><label> Nombre Modalidad: </label><input type='text' name='nombre_modalidad' value='<?php echo sql_htm($row["nombre_modalidad"])  ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=45 ></li> 
                    <li><label> Descripcion Modalidad: </label><textarea name='descripcion_modalidad' class='textarea tinymce'><?php echo sql_htm($row["descripcion_modalidad"])  ?></textarea> </li> 
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function updateModalidades() {
        $query = new Consulta("UPDATE modalidades SET  
                            descripcion_modalidad='" . addslashes($_POST['descripcion_modalidad']) . "',
                            nombre_modalidad='" . addslashes(htm_sql($_POST['nombre_modalidad'])) . "'
                            WHERE id_modalidad = '" . $_GET['id'] . "'");

        $this->_msgbox->setMsgbox('Se actualizó correctamente la Modalidad.', 2);
        location("modalidades.php?id_dep=" . $this->_id_deporte);
    }

    public function deleteModalidades() {
        $query = new Consulta("DELETE  FROM modalidades WHERE id_modalidad = '" . $_GET['id'] . "'");
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("modalidades.php?id_dep=" . $this->_id_deporte);
    }

    public function listModalidades() {

        $sql = "SELECT * FROM modalidades WHERE id_deporte = " . $this->_id_deporte . " ORDER BY orden_modalidad ASC";

        $query = new Consulta($sql);
        ?>
        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Modalidades</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_modalidad'] . "|mod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_ ?>file.png" class="handle">   <?php echo "<b>" . sql_htm($rowp['nombre_modalidad']) . "</b>" ?></div>
                        <div class="options">
                            <a class="tooltip move"  title="Ordenar ( Click + Arrastrar )"><img src="<?php echo _admin_ ?>move.png" class="handle"></a> &nbsp;
                            <a title="Editar" class="tooltip" href="modalidades.php?id=<?php echo $rowp['id_modalidad'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('modalidades.php','<?php echo $rowp['id_modalidad'] ?>','delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
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
