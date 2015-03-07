<?php
// Proyecto: Sistema Develoweb
// Version: 1.0
// Programador: Walter Meneses
// Framework: Develoweb Version: 2.0
// Clase: Proyectos

/** relaciones */
require_once _model_ . 'Usuario.php';

class Usuarios {

    private $_msgbox;

    public function __construct($msg = '') {//	CONSTRUCTOR DE LA CLASE USUARIOS
        $this->_msgbox = $msg;
    }

    public function newUsuarios($idioma) {//	FORMULARIO DE INGRESO DE NUEVO USUARIO AL SISTEMA
        $roles = new Roles();
        $rols = $roles->getRoles();
        ?>	
        <fieldset id='form'> 
            <legend> Nuevo Registro</legend>			
            <form name='usuarios' method='post' action='' enctype="multipart/form-data" > 
                <div class='button-actions'> 
                    <input type='reset' name='cancelar' value='CANCELAR' class='button' >  
                    <input type='button' name='actualizar' value='GUARDAR' onclick='return valida_usuarios("add","")' class='button'><br clear='all' /> 
                </div> 
                <ul> 
                    <li><label>  Rol: </label><select name='id_rol' id='id_rol'> 
                            <option value=' '> Seleccione rol</option> 
                            <?php foreach ($rols as $key): ?>
                                <option value='<?php echo $key['id'] ?>'><?php echo $key['nombre'] ?></option> 
                            <?php endforeach ?>
                        </select></li> 
                    <li><label> Nombre Usuario: </label><input type='text' name='nombre_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Apellidos Usuario: </label><input type='text' name='apellidos_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Email Usuario: </label><input type='text' name='email_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Login Usuario: </label><input type='text' name='login_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=20 ></li> 
                    <li><label> Password Usuario: </label><input type='text' name='password_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=20 ></li> 
                    <li><label> Foto Usuario: </label><input type='file' name='foto_usuario' value=''  class='text ui-widget-content ui-corner-all'>&nbsp;</li> 
                </ul> 
            </form> 
        </fieldset> 
        <?php
    }

    public function addUsuarios() {//	INSERCION DE DATOS DE UN NUEVO USUARIO
        if (isset($_FILES['foto_usuario']) && ($_FILES['foto_usuario']['name'] != "")) {

            $obj = new Upload();
            $destino = "../aplication/webroot/imgs/catalogo/";

            $name = time() . $_FILES['foto_usuario']['name'];
            $temp = $_FILES['foto_usuario']['tmp_name'];
            $type = $_FILES['foto_usuario']['type'];
            $size = $_FILES['foto_usuario']['size'];

            $obj->upload_imagen($name, $temp, $destino, $type, $size);
        }
        $sql = "INSERT INTO usuarios(`id_rol`,`nombre_usuario`,`apellidos_usuario`,`email_usuario`,`foto_usuario`,`login_usuario`,`password_usuario`,`fecha_ingreso_usuario`,`lectura_usuario`,`escritura_usuario`) VALUES('" . $_POST['id_rol'] . "',
	 								'" . $_POST['nombre_usuario'] . "',
									'" . $_POST['apellidos_usuario'] . "',
									'" . $_POST['email_usuario'] . "',
									'" . $name . "', 
									'" . $_POST['login_usuario'] . "', 
									'" . $_POST['password_usuario'] . "', 
									'" . date("Y-m-d") . "')";

        $query = new Consulta($sql);
        $this->_msgbox->setMsgbox('Se grabo correctamente.', 2);
        location("usuarios.php");
    }

    public function editUsuarios($idioma) {//	FORMULARIO DE EDICION DE DATOS DEL USUARIO
        $usuario = new Usuario($_GET['id']);
        $roles = new Roles();
        $rols = $roles->getRoles();
        ?>
        <fieldset id='form'> 
            <legend> Nuevo Registro</legend>			
            <form name='usuarios' method='post' action='' enctype="multipart/form-data" > 

                <div class='button-actions'> 
                    <input type='reset' name='cancelar' value='CANCELAR' class='button' >  
                    <input type='button' name='actualizar' value='ACTUALIZAR' onclick='return valida_usuarios("update", "<?php echo $usuario->getId() ?>")' class='button'><br clear='all' /> 
                </div> 
                <ul> 
                    <li><label>  Rol: </label><select name='id_rol' id='id_rol'> 
                            <option value=' '> Seleccione rol</option> 
                            <?php foreach ($rols as $key): ?>
                                <option value='<?php echo $key['id'] ?>' <?php
                    if ($key['nombre'] == $usuario->getRol()) {
                        echo "selected";
                    }
                                ?>><?php echo $key['nombre'] ?></option> 
                                    <?php endforeach ?>
                        </select></li> 
                    <li><label> Nombre Usuario: </label><input type='text' name='nombre_usuario' value='<?php echo $usuario->getNombre(); ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Apellidos Usuario: </label><input type='text' name='apellidos_usuario' value='<?php echo $usuario->getApellidos(); ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Email Usuario: </label><input type='text' name='email_usuario' value='<?php echo $usuario->getEmail(); ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=50 ></li> 
                    <li><label> Login Usuario: </label><input type='text' name='login_usuario' value='<?php echo $usuario->getLogin(); ?>' class='text ui-widget-content ui-corner-all' size='59'  maxlength=20 ></li> 
                    <li><label> Password Usuario: </label><input type='text' name='password_usuario' value='' class='text ui-widget-content ui-corner-all' size='59'  maxlength=20 ></li> 
                    <li><label> Foto Usuario: </label><input type='file' name='foto_usuario' value=''  class='text ui-widget-content ui-corner-all'>&nbsp;</li> 
                    <div align="center" style="width:500px;"><img src="../aplication/webroot/imgs/catalogo/<?php echo $usuario->getFoto(); ?>" width="90" /></div>
                </ul> 
            </form> 
        </fieldset>
        <?php
    }

    public function updateUsuarios($id, Usuario $usuario) {//	ACTUALIZACION DE LA INFORMACION DE LOS USUARIOS
        if ($_POST['nombre_usuario']) {

            if (isset($_FILES['foto_usuario']) && ($_FILES['foto_usuario']['name'] != "")) {

                $obj = new Upload();
                $destino = "../aplication/webroot/imgs/catalogo/";

                $name = time() . $_FILES['foto_usuario']['name'];
                $temp = $_FILES['foto_usuario']['tmp_name'];
                $type = $_FILES['foto_usuario']['type'];
                $size = $_FILES['foto_usuario']['size'];

                $upload = ", foto_usuario = '" . $name . "'";

                $obj->upload_imagen($name, $temp, $destino, $type, $size);
            }
            if (isset($_POST['password_usuario']) && $_POST['password_usuario'] != '')
                $pass = ", password_usuario='" . $_POST['password_usuario'] . "' ";

            $sql = "UPDATE usuarios SET 
						id_rol		='" . $_POST['id_rol'] . "',
						nombre_usuario='" . $_POST['nombre_usuario'] . "',
						apellidos_usuario='" . $_POST['apellidos_usuario'] . "',
						email_usuario='" . $_POST['email_usuario'] . "',
						login_usuario='" . $_POST['login_usuario'] . "'
						" . $pass . "
						" . $upload . "
				WHERE id_usuario = '" . $_GET['id'] . "' ";
            $query = new Consulta($sql);
        }else {
            $this->UpdatePassword($id, $usuario);
        }

        $this->_msgbox->setMsgbox('Usuarios actualizado.', 2);
        location("usuarios.php");
    }

    public function editPassword($id, Usuario $usuario) {

        if ($usuario->getId() == $id) {
            $sql = "SELECT id_usuario, email_usuario, password_usuario FROM usuarios WHERE id_usuario='" . $id . "' ";
            $query = new Consulta($sql);
            Form::getForm($query, "edit", "usuarios.php");
        } else {
            echo "<div id=error> Usted no puede cambiar el password de otros usuarios </div>";
        }
    }

    public function UpdatePassword($id, Usuario $usuario) {
        $sql = "UPDATE usuarios SET email_usuario='" . $_POST['email_usuario'] . "', password_usuario='" . $_POST['password_usuario'] . "' WHERE id_usuario='" . $id . "'";
        $query = new Consulta($sql);
    }

    public function deleteUsuarios() {//	ELIMINA::Eliminar el usuario seleccionado
        $sql = "DELETE FROM usuarios WHERE id_usuario='" . $_GET['id'] . "'";
        $query = new Consulta($sql);
        $this->_msgbox->setMsgbox('Se elimino correctamente.', 2);
        location("usuarios.php");
    }

    public function listUsuarios() { //	LISTAR LOS USUARIOS DEL SISTEMA	
        $sql = " SELECT id_usuario, nombre_usuario, apellidos_usuario, email_usuario, login_usuario, nombre_rol FROM usuarios INNER JOIN roles USING(id_rol)";
        $query = new Consulta($sql);
        ?>
        <div id="info_user" title="Datos del Usuario"><!--	Campo para mostrar los datos del Usuario -->
        </div>	 	 		
        <table class="listado" cellpadding="0">
            <tr class="head">
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Opciones</th>
            </tr>
            <?php
            $w = 1;
            while ($row = $query->VerRegistro()) {
                $class = ($w % 2 == 0) ? "odl" : "";
                ?>
                <tr class="row <?php echo $class ?>">
                    <td><?php echo $row['nombre_usuario'] ?></td>
                    <td><?php echo $row['apellidos_usuario'] ?></td>
                    <td><?php echo $row['email_usuario'] ?></td>
                    <td><?php echo $row['nombre_rol']; ?></td>
                    <td align="center">
                        <a class="tooltip" title="Editar" onclick="mantenimiento('usuarios.php',<?php echo $row['id_usuario'] ?>,'edit')" href="#">
                            <img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                        <a class="tooltip" title="Eliminar" onclick="mantenimiento('usuarios.php',<?php echo $row['id_usuario'] ?>,'delete')" href="#">
                            <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp; <a class="tooltip" title="Detalle" href="accesos.php?action=list&id1=<?php echo $row['id_usuario'] ?>">
                            <img src="<?php echo _icons_ ?>index.gif"></a>
                        <a title="Vista Previa" href="javascript:;" onclick="view_user('<?php echo $row['id_usuario'] ?>')">
                            <img src="<?php echo _icons_ ?>view.gif"></a></td>
                </tr>
                <?php
                $w++;
            }
            ?>
        </table>
        <?php
    }

    function AccesosAddUsuarios($id) {//	FUNCION DE ACCESOS A LOS USUARIOS
        $DelQuery = new Consulta("DELETE FROM usuarios_secciones WHERE id_usuario=" . $id . "");
        for ($j = 0; $j < sizeof($_POST['seccion']); $j++) {
            if ($_POST['seccion'][$j]) {
                $Query = new Consulta($sql = "INSERT INTO usuarios_secciones VALUES('" . $id . "' ,'" . $_POST['seccion'][$j] . "') ");
            }
        }

        $this->_msgbox->setMsgbox('Se guardaron los cambios correctamente.', 2);
        location("accesos.php?id1=" . $id);
    }

    public function getUsuarios() {//	DATOS DE LOS USUARIOS
        $sql = "SELECT * FROM usuarios ORDER BY nombre_usuario DESC ";
        $query = new Consulta($sql);
        while ($row = $query->VerRegistro()) {
            $data[] = array(
                'id' => $row['id_usuario'],
                'rol' => $row['id_rol'],
                'usuario' => $row['nombre_usuario'] . ' ' . $row['apellidos_usuario'],
                'email' => $row['email_usuario'],
                'foto' => $row['foto_usuario'],
                'login' => $row['login_usuario'],
                'fecha_ingreso' => $row['fecha_ingreso_usuario']
            );
        }
        return $data;
    }

    function AccesoslistUsuarios($id) {//	ACCESO DE LOS USUARIOS A LAS SECCIONES DEL SISTEMA 
        if (!$id) {
            echo "<br /><div id=error>ERROR: no se encontro ningun usuario con ese Id ó le falta Id  </div>";
        } else {
            $sql = "SELECT s.id_seccion, m.nombre_modulo as MODULO, s.nombre_seccion as PAGINAS, s.url_seccion as URL 
					FROM  secciones s, modulos m 
					WHERE s.id_modulo = m.id_modulo";
            $Query = new Consulta($sql);
            ?>
            <div id="content-area">
                <form name="f1" action="" method="post">		
                    <table class="listado">
                        <tr class="head"> <?php for ($i = 1; $i < $Query->NumeroCampos(); $i++) { ?>
                                <th class="titulo"> <?php echo $Query->nombrecampo($i) ?> </th><?php }
            ?>
                            <th class="titulo">Activar</td></th><?php
            $x = 0;
            while ($row = mysql_fetch_row($Query->Consulta_ID)) {
                ?>
                            <tr <?php if ($x == 0) { ?>class="row" <?php } else { ?> class="row odl" <?php } ?> > <?php for ($i = 1; $i < $Query->NumeroCampos(); $i++) { ?>
                                    <td align=left class="celda"><?php echo $row[$i] ?></td><?php
                }
                $Query_SA = mysql_query("SELECT * FROM usuarios_secciones WHERE id_usuario=" . $id . " AND id_seccion=" . $row[0] . "");
                $NUM = mysql_num_rows($Query_SA);
                ?>
                                <td align="center"><input type="checkbox" name="seccion[]" value="<?php echo $row[0] ?>" <?php
                if ($NUM == 1) {
                    echo "checked";
                }
                ?>></td>
                            </tr><?php
                    if ($x == 0) {
                        $x++;
                    } else {
                        $x = 0;
                    }
                }
            ?>					
                        <tr bgcolor="#F2F2F2">
                            <td colspan="4" align="center"  style="padding-top:20px; padding-bottom:20px" >
                                <input type="submit" name="guardar" value="GUARDAR" onClick="void(document.f1.action='accesos.php?id1=<?php echo $id ?>&action=add');void(document.f1.submit())"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="reset" name="cancelar" value="CANCELAR"></td>
                        </tr>					
                    </table>

                </form>	
            </div><?php
        }
    }

}
?>