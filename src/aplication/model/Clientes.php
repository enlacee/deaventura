<?php

class Clientes {

    public function __construct() {
        //$this->_msgbox = $msbx;
    }

    public function getClientes($arr) {
        if ($arr != NULL) {
            foreach ($arr as $val) {
                $addSQL .= $val . ',';
            }
            $sql = "SELECT " . substr($addSQL, 0, -1) . " FROM clientes LIMIT 0,20";
            $query = new Consulta($sql);
            if ($query->NumeroRegistros() > 0) {
                while ($row = $query->VerRegistro()) {
                    foreach ($arr as $value) {
                        $valores[$value] = $row[$value];
                    }
                    $data[] = $valores;
                }
            }
        }
        return $data;
    }

    public function getFotos() {
        $sql = "SELECT id_cliente,id_facebook_cliente,nombre_cliente FROM clientes ORDER BY fecha_registro_cliente DESC LIMIT 0,28";
        $query = new Consulta($sql);
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_cliente' => $row['id_cliente'],
                    'id_facebook_cliente' => $row['id_facebook_cliente'],
                    'nombre_cliente' => $row['nombre_cliente']
                );
            }
        }
        return $data;
    }

    public function getURL($id, $nombre) {
        return 'aventurero_' . $id . '-' . url_friendly($nombre, 1);
    }
	
	public function listClientes() {
		
		$sql = "SELECT * FROM clientes ORDER BY fecha_registro_cliente DESC";

        $query = new Consulta($sql);
        ?>
        <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th class='titulo blank' align="left">Clientes</th>
                        <th class='titulo' align="center" width="100">Opciones</th>
                    </tr>
                </thead>
            </table>
            <ul id="listadoul">
                <?php
                $y = 1;
                while ($rowp = $query->VerRegistro()) {
                    ?>
                    <li class="<?php echo ($y % 2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowp['id_cliente'] . "|prod"; ?>">
                        <div class="data"> <img src="<?php echo _admin_.($rowp['tipo_cliente']==0?'user_.png':'tienda.png');?>" class="handle">   <?php echo $rowp['fecha_registro_cliente']. " | <b>" . sql_htm(utf8_decode($rowp['nombre_cliente'].", ".$rowp['apellidos_cliente'])) . "</b> | ".$rowp['email_cliente']." " ?></div>
                        <div class="options">
                            <a title="Editar" class="tooltip" href="gestionusuarios.php?id=<?php echo $rowp['id_cliente'] ?>&action=edit"><img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;<!--
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('gestionusuarios.php','<?php// echo $rowp['id_lugar'] ?>','delete'); return false"  href="#"> <img src="<?php// echo _admin_ ?>delete.png"></a>&nbsp;-->
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
	
	public function editClientes() {
        settype($_GET['id'],'int');
        $data = new Cliente($_GET['id']);
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";/**/
        $obj_agencias = new Agencias();
        $agencias = $obj_agencias->getAgencias();
        
        ?>
        <fieldset id="form">
            <legend> Editar Registro</legend>			
            <form id="lugares" method="post" action="gestionusuarios.php?action=update&id=<?php echo $_GET['id'] ?>"> 
                <div class="button-actions">
                    <input type="reset" name="cancelar" value="CANCELAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
                    <input type="submit" name="actualizar" value="GUARDAR" class="button ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false"><br clear="all">
                </div><br/><br/>
                <ul> 
                    <li><label><strong> Nombre: </strong></label><input type="text" name="nombre" id="nombre" value="<?php echo sql_htm($data->_nombre); ?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="45" ></li>
                    <li><label><strong> Apellidos: </strong></label><input type="text" name="apellido" id="apellido" value="<?php echo sql_htm($data->_apellidos);?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Correo: </strong></label><input type="text" name="correo" value="<?php echo $data->_email;?>" class="text ui-widget-content ui-corner-all" size="59" maxlength="45"></li> 
                    <li><label><strong> Tipo: </strong></label>&nbsp;&nbsp;&nbsp;&nbsp;<strong>Normal : </strong> <input type="radio" name="tipo" value="0" <?php echo ($data->_tipo_usuario==0?'checked':'');?>> <strong>Agencia :</strong> <input type="radio" name="tipo" value="1" <?php echo ($data->_tipo_usuario==1?'checked':'');?>></li>
                    <li><label><strong> Agencia: </strong></label>
                        <select name="agencia">
                            <option value="Seleccione Agencia"></option>
                            <?php 
                            foreach($agencias as $key=>$value){  ?>
                            <option value="<?php echo $value["id"] ?>"><?php echo utf8_encode($value["nombre"])?></option>
                            <?php } ?>
                        </select>
                    </li>
                </ul>
            </form>
        </fieldset>
        <?php
    }

    public function updateClientes() {
        $sql = "UPDATE clientes SET tipo_cliente='" . $_POST['tipo'] . "' WHERE id_cliente = '" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        
        $query_delete = new Consulta("DELETE FROM clientes_agencias WHERE id_cliente = '".$_POST['agencia']."' ");
        $sql = "INSERT INTO clientes_agencias VALUES('','" . $_GET['id']. "','".$_POST['agencia']."' )";
        $query = new Consulta($sql);
        
        //$this->_msgbox->setMsgbox('Se actualizó correctamente.', 2);
        location("gestionusuarios.php");
    }
	
}

?>
