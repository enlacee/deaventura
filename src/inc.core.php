<?php

include("aplication/inc.config.php");

//		include($_SERVER['DOCUMENT_ROOT']."/inc.config.php");

define("_url_", $_config['server']['url']); //define("_url_", "http://www.deaventura.pe/");
define("_ruta_", $_config["server"]["host"]);
define("_includes_", $_config["server"]["host"] . "aplication/includes/");


define("_imgs_", $_config["server"]["url"] . "aplication/webroot/imgs/");
define("_vouchers_", $_config["server"]["url"] . "aplication/webroot/voucher/");
define("_catalogo_", $_config["server"]["url"] . "aplication/webroot/imgs/catalogo/");
define("_link_files_", $_config["server"]["host"] . "aplication/webroot/imgs/catalogo/");



define("_host_files_users_", $_config["server"]["host"] . "aplication/webroot/imgs/catalogo/image_users/"); /* ES PARA SOLO USO USUARIO COMUN */

define("_url_files_users_", $_config["server"]["url"] . "aplication/webroot/imgs/catalogo/image_users/");

define("_host_avfiles_users_", $_config["server"]["host"] . "aplication/webroot/imgs/catalogo/aventuras_img_usuarios/"); /* ES PARA SOLO USO USUARIO COMUN */



define("_url_avfiles_users_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/aventuras_img_usuarios/");

define("_url_proveedor_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_proveedores/");

define("_url_organizacion_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_organizaciones/");

define("_url_sitios_web_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_sitios_web/");

define("_url_evento_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_eventos/");

define("_url_agencia_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_agencias/");

define("_url_inspiracion_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_inspiraciones/");
define("_url_articulo_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_articulos/");

define("_url_rutas_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_rutas/");

define("_url_eventos_img_", /* $_config["server"]["url"] . */ "aplication/webroot/imgs/catalogo/image_eventos/");



define("_icons_", _imgs_ . "icons/");

define("_admin_", _imgs_ . "admin/");

define("_flash_", $_config["server"]["url"] . "aplication/webroot/flash/");





define("_model_", $_config["server"]["host"] . "aplication/model/");

define("_view_", $_config["server"]["host"] . "aplication/view/");

define("_util_", $_config["server"]["host"] . "aplication/utilities/");





define("_img_file_", "aplication/utilities/img.php");

define("_imagen_", "aplication/utilities/imagen.php");

define("_imgs_prod_", "aplication/webroot/imgs/catalogo/");

define("_language_", $_config["server"]["host"] . "aplication/language/");
?>