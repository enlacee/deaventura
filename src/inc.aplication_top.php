<?php

error_reporting(E_ALL ^ E_NOTICE);

include_once("inc.core.php");
require_once(_model_ . "Conexion.php");
require_once(_model_ . "Mysql.php");

require_once(_model_ . "Configuration.php");
require_once(_model_ . "MainModel.php");

require_once(_model_ . "Idioma.php");
require_once(_model_ . "Msgbox.php");
require_once(_model_ . "Clientes.php");
require_once(_model_ . "Cliente.php");
require_once(_model_ . "Usuarios.php");
require_once(_model_ . "Usuario.php");
require_once(_model_ . "Cuenta.php");
require_once(_model_ . "Ajax.php");

require_once(_model_ . "Seccion_sitio.php");
require_once(_model_ . "Secciones_sitio.php");
require_once(_model_ . "Deporte.php");
require_once(_model_ . "Deportes.php");
require_once(_model_ . "Eventos.php");
require_once(_model_ . "Evento.php");
require_once(_model_ . "EventosActividades.php");
require_once(_model_ . "EventoActividad.php");
require_once(_model_ . "Agencia.php");
require_once(_model_ . "Agencias.php");
require_once(_model_ . "Aventura.php");
require_once(_model_ . "Aventuras.php");
require_once(_model_ . "Articulo.php");
require_once(_model_ . "Articulos.php");
require_once(_model_ . "Favoritos.php");
require_once(_model_ . "Modalidad.php");
require_once(_model_ . "Tags.php");
require_once(_model_ . "Busqueda.php");
require_once(_model_ . "ArticulosBlog.php");
require_once(_model_ . "SeccionesTags.php");
require_once(_model_ . "Pagina.php");
require_once(_model_ . "Inspiracion.php");
require_once(_model_ . "Inspiraciones.php");

require_once(_model_ . "Actividad.php");
require_once(_model_ . "Actividades.php");
require_once(_model_ . "Salida.php");
require_once(_model_ . "Salidas.php");
require_once(_model_ . "Paquete.php");
require_once(_model_ . "Paquetes.php");
require_once(_model_ . "Inscripciones.php");



require_once(_model_ . "facebook/facebook.php");

require_once(_util_ . "Libs.php");
require_once(_util_ . "ThumbnailBlobArray.php");
require_once(_util_ . "ThumbnailBlob.php");
require_once(_util_ . "tags_html.php");
require_once(_util_ . "img_resize.php");
require_once(_view_ . "Secciones.php");
require_once(_model_ . "Posts.php");


//Conexion de a la BD
$link = new Conexion($_config['bd']['server'], $_config['bd']['user'], $_config['bd']['password'], $_config['bd']['name']);
session_start();


if (!isset($_SESSION["aventurak"]) ) {
    $cliente = new Cliente();
    $_SESSION["aventurak"] = new Cuenta($cliente);
    $cuenta = $_SESSION["aventurak"];
    
} else {
    $cuenta = $_SESSION["aventurak"];
}
//echo 'estado : '.$cuenta->__get("_cliente")->__get("_logeado").'|||   ';
$_SESSION["aventurak"] = $cuenta;
/*
  echo '<pre>';
  print_r($_SESSION["aventura"]);
  echo '</pre>';
 */
//echo "id-inspiracion".$_GET["id_inspiracion"];
//Comprobar si escribe sin WWW
if ($_SERVER['HTTP_HOST'] == 'deaventura.pe')
    header('location: http://www.deaventura.pe/');


$_GET["titulo"] = "Comunidad de Deportes de Aventura en Perú";
$descripcion = "Deportes de Aventura en Perú, somos una comunidad de aventureros amantes de los deportes de aventura en Perú, únete y comparte tu experiencia, Canotaje, Surf, Trekking, Ciclismo de Montaña, Escalada, Ala Delta, Buceo, Parapente, Sandboard, Snowboard ";
$image_face = 'deaventura.jpg';
$evento_boton = "NO"; //fija si estamos en evento o NO para poner el boton de TOP en aventura o evento

$depo = new Deportes();
if (isset($_GET["cat"]) && $_GET["cat"] != '') {

    $items = array();
    $nombre_seccion = url_friendly($_GET["cat"], 2);
    if ($nombre_seccion == "mapa de sitio" OR  $nombre_seccion == "contactanos" OR $nombre_seccion == "nosotros"  OR $nombre_seccion == "terminos y condiciones") { //Solo si es mapar de si . ya que hay un conflicto si encuentra "de" en la URL
        $sql = "SELECT * FROM secciones_sitio WHERE rel_deporte_secciones_sitio = '0' AND nombre_seccion_sitio = '" . url_friendly($_GET["cat"], 2) . "'";
        $arrSec = $depo->consultaUrl($sql, array('id_seccion_sitio', 'nombre_seccion_sitio'));
        $id_sec = $arrSec['id_seccion_sitio'];
        if ($id_sec > 0) {
            $_GET['sec'] = $id_sec;
            $_GET["titulo"] = ucfirst($arrSec['nombre_seccion_sitio']);
            $descripcion = $_GET["titulo"] . ", " . $descripcion;
        }
    } else {
        $items = explode(' de ', url_friendly($_GET["cat"], 2));
    }

    if (count($items) > 1) {
        $sql = "SELECT id_deporte, nombre_deporte, descripcion_deporte FROM deportes WHERE nombre_deporte = '" . url_friendly($items[1], 2) . "'";
        $arrD = $depo->consultaUrl($sql, array('id_deporte', 'nombre_deporte', 'descripcion_deporte'));
        $id_cat = $arrD['id_deporte'];
        if ($id_cat > 0){
            $_GET["cat"] = $id_cat;
        }
        /* Obtener rutas, org, eventos, tiendas */
        $sqls = "SELECT id_seccion_sitio, nombre_seccion_sitio FROM secciones_sitio WHERE nombre_seccion_sitio = '" . url_friendly($items[0], 2) . "'";
        $arrSS = $depo->consultaUrl($sqls, array('id_seccion_sitio', 'nombre_seccion_sitio'));
        $id_sec = $arrSS['id_seccion_sitio'];
        if ($id_sec > 0)
            $_GET["sec"] = $id_sec; else
            unset($_GET['cat']);

        $_GET["titulo"] = ucfirst(sql_htm($arrSS['nombre_seccion_sitio'])) . ' de ' . ucfirst(sql_htm($arrD['nombre_deporte']));
        $descripcion = $_GET["titulo"] . ", " . $arrD['descripcion_deporte'];
	
        /* Obtener la descripcion de una empresa, evento, org... */
        if (isset($_GET["nombre"]) && $_GET["nombre"] != '') {
            $descripcion = $_GET["titulo"] . ", " . $arrD['descripcion_deporte'];
            if ($_GET["sec"] == 1) {
                $ruta = explode(' en ', url_friendly($_GET["nombre"], 2));
                $sqld = "SELECT id_lugar, nombre_lugar, descripcion_ruta FROM rutas INNER JOIN lugares USING(id_lugar) WHERE nombre_lugar = '" . url_friendly($ruta[1], 2) . "' AND id_deporte = " . $_GET["cat"];
                $arr = $depo->consultaUrl($sqld, array('id_lugar', 'nombre_lugar', 'descripcion_ruta'));
                $id = $arr['id_lugar'];
                $nom = $arrD['nombre_deporte'] . ' en ' . $arr['nombre_lugar'];
                
                if ($id > 0){
                    $_GET["id_ruta"] = $id;
                    $_GET["titulo"] = $nom;
                    
                    
                }
                    
                if ($id_cat == 15) { // Referencia al ID de otros deportes en la table Deportes
                    $sqld = "SELECT id_modalidad FROM modalidades WHERE nombre_modalidad = '" . url_friendly($ruta[0], 2) . "' AND id_deporte = " . $_GET["cat"];
                    $err = $depo->consultaUrl($sqld, array('id_modalidad'));
                    $nom = $ruta[0] . ' en ' . $arr['nombre_lugar'];
                    $_GET["mod_rutas"] = $err['id_modalidad'];
                }
                $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_ruta']));
                
            } else if ($_GET["sec"] == 2) {
                $sqld = "SELECT id_evento, titulo_evento, descripcion_evento FROM eventos WHERE titulo_evento = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_evento', 'titulo_evento', 'descripcion_evento'));
                $id = $arr['id_evento'];
                $nom = $arr['titulo_evento'];
                if ($id > 0) {
                    $_GET["id_evento"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_evento']));
                    $evento_boton = "SI";
                }
            } else if ($_GET["sec"] == 3) {
                $sqld = "SELECT id_organizacion, nombre_organizacion, descripcion_organizacion FROM organizaciones WHERE nombre_organizacion = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_organizacion', 'nombre_organizacion', 'descripcion_organizacion'));
                $id = $arr['id_organizacion'];
                $nom = $arr['nombre_organizacion'];
                if ($id > 0) {
                    $_GET["id_org"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_organizacion']));
                }
            } else if ($_GET["sec"] == 4) {
                $sqld = "SELECT id_proveedor, nombre_proveedor, descripcion_proveedor FROM proveedores WHERE nombre_proveedor = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_proveedor', 'nombre_proveedor', 'descripcion_proveedor'));
                $id = $arr['id_proveedor'];
                $nom = $arr['nombre_proveedor'];
                if ($id > 0) {
                    $_GET["id_proveedor"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_proveedor']));
                }
            } else if ($_GET["sec"] == 5) {
                $sqld = "SELECT id_agencia, nombre_agencia, descripcion_agencia FROM agencias WHERE nombre_agencia = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_agencia', 'nombre_agencia', 'descripcion_agencia'));
                $id = $arr['id_agencia'];
                $nom = $arr['nombre_agencia'];
                if ($id > 0) {
                    $_GET["id_agencia"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_agencia']));
                }
            }else if ($_GET["sec"] == 12) {
                $sqld = "SELECT id_inspiracion, insight_inspiracion, tags_inspiracion FROM inspiraciones WHERE url_inspiracion = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_inspiracion', 'insight_inspiracion', 'tags_inspiracion'));
                $id = $arr['id_inspiracion'];
                $nom = $arr['insight_inspiracion'];
                if ($id > 0) {
                    $_GET["id_inspiracion"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['tags_inspiracion']));
                }
            }else if ($_GET["sec"] == 15) {
                $sqld = "SELECT id_articulo, nombre_articulo, tags_articulo, descripcion_articulo FROM articulos WHERE url_articulo = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arr = $depo->consultaUrl($sqld, array('id_articulo', 'nombre_articulo', 'tags_articulo'));
                $id = $arr['id_articulo'];
                $nom = $arr['nombre_articulo'];
                if ($id > 0) {
                    $_GET["id_articulo"] = $id;
                    $descripcion = $_GET["titulo"] . ", " . strip_tags(sql_htm($arr['descripcion_articulo']));
                }
            }

            $_GET["titulo"] = ucfirst(sql_htm($nom));
        }
    } else {
        /* Obtener el deporte */
        $sql = "SELECT id_deporte, nombre_deporte, descripcion_deporte FROM deportes WHERE nombre_deporte = '" . url_friendly($_GET["cat"], 2) . "'";
        $arrD = $depo->consultaUrl($sql, array('id_deporte', 'nombre_deporte', 'descripcion_deporte'));
        $id_cat = $arrD['id_deporte'];
        if ($id_cat > 0) {
            $_GET["titulo"] = ucfirst(sql_htm($arrD['nombre_deporte']));
            $descripcion = $_GET["titulo"] . ", " . $arrD['descripcion_deporte'];
            $image_face = url_friendly(url_friendly($_GET["titulo"], 2), 1) . '.jpg';
            $_GET["cat"] = $id_cat;

            /* Obtener la modalidad del deporte */
            if (isset($_GET["nombre"]) && $_GET["nombre"] != '') {
                $sql = "SELECT id_modalidad, nombre_modalidad FROM modalidades WHERE nombre_modalidad = '" . url_friendly($_GET["nombre"], 2) . "'";
                $arrM = $depo->consultaUrl($sql, array('id_modalidad', 'nombre_modalidad'));
                $id_mod = $arrM['id_modalidad'];
                if ($id_mod > 0) {
                    $_GET["titulo"] = ucfirst(sql_htm($arrM['nombre_modalidad']));
                    $descripcion = $_GET["titulo"] . ", " . $descripcion;
                    $_GET["mod"] = $id_mod;
                }
            }
        } else {

            $arr = explode("sitios web sobre ", url_friendly($_GET["cat"], 2));
            if (count($arr) > 1) {
                $sql = "SELECT id_deporte, nombre_deporte, descripcion_deporte FROM deportes WHERE nombre_deporte = '" . $arr[1] . "'";
                $arrD = $depo->consultaUrl($sql, array('id_deporte', 'nombre_deporte', 'descripcion_deporte'));
                $id_cat = $arrD['id_deporte'];
                if ($id_cat > 0) {
                    $_GET["cat"] = $id_cat;
                    $_GET['sec'] = 6; //Sitios web sobre
                    $_GET["titulo"] = ucfirst("sitios web sobre " . $arrD['nombre_deporte']);
                    $descripcion = $_GET["titulo"] . ", " . $descripcion;
                }
            } else { //Sitios simples
                $sql = "SELECT * FROM secciones_sitio WHERE rel_deporte_secciones_sitio = '0' AND nombre_seccion_sitio = '" . url_friendly($_GET["cat"], 2) . "'";
                $arrSec = $depo->consultaUrl($sql, array('id_seccion_sitio', 'nombre_seccion_sitio'));
                $id_sec = $arrSec['id_seccion_sitio'];
                if ($id_sec > 0) {
                    $_GET['sec'] = $id_sec;
                    $_GET["titulo"] = ucfirst($arrSec['nombre_seccion_sitio']);
                    $descripcion = $_GET["titulo"] . ", " . $descripcion;
                }
            }
        }
    }
}

if ($_GET["q"] != "") {  
    
    $_GET["titulo"] = ucwords(str_replace("-"," ",$_GET["q"]));
    $_GET["q"] = str_replace(" ", "-", strtolower($_GET["q"]));

}

if ($_GET["sec"] != "" && $_GET["sec"] == 13) {  //titulo para los sventos (AGENDA)
    
    $_GET["titulo"] = "Eventos de Aventura "; 
    $descripcion = $_GET["titulo"] . ", Eventos de deportes de aventura en Perú";
    $evento_boton = "SI";
}

if ($_GET["sec"] != "" && $_GET["sec"] == 14) {  //titulo para los aventuras
    
    $_GET["titulo"] = "Aventuras de la Comunidad "; 
    $descripcion = $_GET["titulo"] . ", aevnturas de deportes de aventura en Perú";
}

if ($_GET["sec"] != "" && $_GET["sec"] == 12) {  //titulo para los sventos (AGENDA)
    
    $_GET["titulo"] = "Inspiración de Aventura"; 
    $descripcion = $_GET["titulo"] . ", Zona de Inspiración de deportes de aventura en Perú";
}

if (!empty($_GET["id_cliente"]) && isset($_GET["id_cliente"])) {
    $sql = "SELECT id_cliente, nombre_cliente FROM clientes WHERE id_cliente = '" . $_GET["id_cliente"] . "'";
    $arrC = $depo->consultaUrl($sql, array('id_cliente', 'nombre_cliente'));
    $id = $arrC['id_cliente'];
    if ($id > 0)
        $_GET["cliente"] = $id;
    $_GET["titulo"] = "Aventuras de " . $arrC['nombre_cliente'];
    $descripcion = $_GET["titulo"] . ", " . $descripcion;
}

if (!empty($_GET["id_aventura"]) && isset($_GET["id_aventura"])) {
    $sql = "SELECT id_aventura, titulo_aventura FROM aventuras WHERE id_aventura = '" . $_GET["id_aventura"] . "'";
    $arrA = $depo->consultaUrl($sql, array('id_aventura', 'titulo_aventura'));
    $id = $arrA['id_aventura'];
    if ($id > 0)
        $_GET["aventura"] = $_GET["id_aventura"];
    $_GET["titulo"] = $arrA['titulo_aventura'];
    $descripcion = "Aventura, " . $_GET["titulo"] . ", " . $descripcion;
    Aventuras::update_cantidad_visitas($_GET["id_aventura"]);
}

if (!empty($_GET["id_inspiracion"]) && isset($_GET["id_inspiracion"])) {
    $sql = "SELECT id_inspiracion, insight_inspiracion FROM inspiraciones WHERE id_inspiracion = '" . $_GET["id_inspiracion"] . "'";
    $arrA = $depo->consultaUrl($sql, array('id_inspiracion', 'insight_inspiracion'));
    $id = $arrA['id_inspiracion'];
    if ($id > 0)
        $_GET["inspiracion"] = $_GET["id_inspiracion"];
    $_GET["titulo"] = $arrA['insight_inspiracion'];
    $descripcion = "Aventura, " . $_GET["titulo"] . ", " . $descripcion;
    //Aventuras::update_cantidad_visitas($_GET["id_inspiracion"]);
}

/*

  $idAv = Deportes::getIdAventura($_GET["cat"]);

  if ($idAv > 0) {

  $_GET["aventura"] = $idAv;

  }

 */





/*

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

  define("ID_IDIOMA", $idioma->__get("_id"));



  require_once(_language_ . $idioma->__get("_archivo"));

 */
//echo "<br><br><br>".$_SERVER["QUERY_STRING"];

//msgbox

if (!(isset($_SESSION['msg']))) {

    $msgbox = new Msgbox();
} else {

    $msgbox = $_SESSION['msg'];
}

define(_EVENTO_BOTON_,$evento_boton); 

//configuracion del sitio

$user = new Usuario();
$config_site = new Configuration($msgbox, $user);
$configs = $config_site->getData();



foreach ($configs as $clave => $valor) {

    define($clave, $valor);
}

?>