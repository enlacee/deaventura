<?php

error_reporting(E_ALL ^ E_NOTICE);
include_once("../inc.core.php");
require_once(_model_ . "Conexion.php");
require_once(_model_ . "Mysql.php");
require_once(_model_ . "Configuration.php");
require_once(_model_ . "Usuario.php");
require_once(_model_ . "Sesion.php");
require_once(_model_ . "Seccion.php");
require_once(_model_ . "Secciones.php");
require_once(_model_ . "Usuarios.php");
require_once(_model_ . "Roles.php");
require_once(_model_ . "Msgbox.php");
require_once(_model_ . "Idioma.php");
require_once(_model_ . "Idiomas.php");
require_once(_model_ . "Ajax.php");
require_once(_model_ . "Form.php");
require_once(_model_ . "Listado.php");

require_once(_model_ . "Deportes.php");
require_once(_model_ . "Deporte.php");
require_once(_model_ . "Rutas.php");
require_once(_model_ . "Rutas_otros.php");
require_once(_model_ . "Ruta.php");
require_once(_model_ . "Proveedores.php");
require_once(_model_ . "Proveedor.php");
require_once(_model_ . "Organizaciones.php");
require_once(_model_ . "Organizacion.php");
require_once(_model_ . "Eventos.php");
require_once(_model_ . "Evento.php");
require_once(_model_ . "Agencias.php");
require_once(_model_ . "ArticulosBlog.php");
require_once(_model_ . "Agencia.php");
require_once(_model_ . "Modalidades.php");
require_once(_model_ . "Sitios_web.php");
require_once(_model_ . "Tags.php");
require_once(_model_ . "Lugar.php");
require_once(_model_ . "Lugares.php");
require_once(_model_ . "Cliente.php");
require_once(_model_ . "Clientes.php");
require_once(_model_ . "Secciones_sitio.php");
require_once(_model_ . "SeccionTag.php");
require_once(_model_ . "SeccionesTags.php");
require_once(_model_ . "Paginas.php");
require_once(_model_ . "Pagina.php");
require_once(_model_ . "Inspiracion.php");
require_once(_model_ . "Inspiraciones.php");
require_once(_model_ . "Articulo.php");
require_once(_model_ . "Articulos.php");

require_once(_util_ . "ThumbnailBlobArray.php");
require_once(_util_ . "ThumbnailBlob.php");

require_once(_util_ . "Upload.php");
require_once(_util_ . "Libs.php");
require_once(_util_ . "tags_html.php");

ini_set('post_max_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('max_execution_time', '1000');
ini_set('max_input_time', '1000');

$link = new Conexion($_config['bd']['server'], $_config['bd']['user'], $_config['bd']['password'], $_config['bd']['name']);
session_start();

//idioma
if ($_SESSION['idioma']) {
    $idioma = $_SESSION['idioma'];
} else {
    $idioma = new Idioma();
}
//cuando hay que cambiar idioma
if (isset($_GET['switch'])) {
    $idioma->switchs($_GET['switch']);
}
//incluimos el archivo de variables del idioma
define("ID_IDIOMA", $idioma->__get('_id'));

$sesion = new Sesion($idioma);
if (isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])) {
    $sesion->validaAcceso($_POST['login'], $_POST['password']);
}
if ($_GET['action'] == "logout") {
    $sesion->logout();
}

//msgbox
if (!(isset($_SESSION['msg']))) {
    $msgbox = new Msgbox();
} else {
    $msgbox = $_SESSION['msg'];
}

$config_site = new Configuration($msgbox, $sesion->getUsuario());
$configs = $config_site->getData();

foreach ($configs as $clave => $valor) {
    define($clave, $valor);
}

if (!is_object($sesion->getUsuario()->getRol()) && !preg_match("/login.php/", $_SERVER['PHP_SELF'])) {
    header("location:login.php");
}
if ($sesion->getUsuario()->getLogeado() == FALSE && !preg_match("/login.php/", $_SERVER['PHP_SELF'])) {
    header("location:login.php");
}
?>