<?php

include("inc.aplication_top.php");

$obj = new Ajax($idioma);
if ($_GET['action']) {
    $accion = $_GET['action'] . "Ajax";
    $obj->$accion();
}

if (isset($_POST["buscarxId"])) {
    switch ($_POST["filtro"]) {
        case 'proveedores':
            $obj = new Proveedores();
            $obj->listProveedoresxDeporte($_POST["id"]);
            break;

        case 'eventos':
            $obj = new Eventos();
            $obj->listEventosxDeporte($_POST["id"]);
            break;

        case 'rutas':
            $obj = new Rutas();
            $obj->listRutasxDeporte($_POST["id"]);
            break;

        case 'agencias':
            $obj = new Agencias();
            $obj->listAgenciasxDeporte($_POST["id"]);
            break;

        case 'organizaciones':
            $obj = new Organizaciones();
            $obj->listOrganizacionesxDeporte($_POST["id"]);
            break;

        case 'sitios_web':
            $obj = new Sitios_web();
            $obj->listSitiosxDeporte($_POST["id"]);
            break;

        default:
            break;
    }
}

if (isset($_POST["action"])) {
    switch ($_POST["action"]) {
        case "update":
            if ($_POST["id"] != "" && $_POST["nombre"] != "") {
                $tag = new Tags();
                $tag->updateTags($_POST["id"], $_POST["nombre"]);
            }
            break;
        case "addTag":
            if ($_POST["idsec"] != "" && $_POST["idtag"] != "" && $_POST["nom_sin"] != "" && $_POST["nom_plu"] != "") {
                echo Tags::mantenerTags((int) $_POST["idsec"], (int) $_POST["idtag"], $_POST["nom_sin"], $_POST["nom_plu"], "add");
            }
            break;
        case "deleteTag":
            if ($_POST["idsec"] != "" && $_POST["idtag"] != "" && $_POST["nom_sin"] != "" && $_POST["nom_plu"] != "") {
                echo Tags::mantenerTags((int) $_POST["idsec"], (int) $_POST["idtag"], $_POST["nom_sin"], $_POST["nom_plu"], "delete");
            }
            break;
        case "addTagRuta":
            if ($_POST["idtag"] != "" && $_POST["idlug"] != "" && $_POST["iddep"] != "") {
                echo Rutas::mantenerTags((int) $_POST["idtag"], (int) $_POST["idlug"], (int) $_POST["iddep"], "add");
            }
            break;
        case "deleteTagRuta":
            if ($_POST["idtag"] != "" && $_POST["idlug"] != "" && $_POST["iddep"] != "") {
                echo Rutas::mantenerTags((int) $_POST["idtag"], (int) $_POST["idlug"], (int) $_POST["iddep"], "delete");
            }
            break;    
        case "addTagRuta_otros":
            if ($_POST["idtag"] != "" && $_POST["idlug"] != "" && $_POST["idmod"] != "") {
                echo Rutas_otros::mantenerTags((int) $_POST["idtag"], (int) $_POST["idlug"], (int) $_POST["idmod"], "add");
            }
            break;
        case "deleteTagRuta_otros":
            if ($_POST["idtag"] != "" && $_POST["idlug"] != "" && $_POST["idmod"] != "") {
                echo Rutas_otros::mantenerTags((int) $_POST["idtag"], (int) $_POST["idlug"], (int) $_POST["idmod"], "delete");
            }
            break;
        default:
            break;
    }
}
?>	