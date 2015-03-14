<?php

function dameURL() {
    $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}

function url_friendly($url, $tipo) {
    if ($tipo == 1) {
        return str_replace(" ", "-", strtolower($url));
    } else if ($tipo == 2) {
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace($find, $repl, $url);
        $url = preg_replace('[^ A-Za-z0-9_-]', '', $url);
        return str_replace("-", " ", $url);
    }
}

function sql_htm($string) {
    //$xml_str = mb_convert_encoding($string, "UTF-8", "ISO-8859-1");
    return $string;
}

function htm_sql($string) {
    $xml_str = mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
    return $xml_str;
}

function ellipsisP($string, $limit, $break = ".", $pad = "...") {
// return with no change if string is shorter than $limit 
    if (strlen($string) <= $limit)
        return $string;
// is $break present between $limit and the end of the string? 
    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if ($breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    return $string;
}

function img_resize($ruta,$nombre) {
    $file = $ruta . trim($nombre);
    $ext = "";
    if (file_exists($file)) {
        $arr = getimagesize($file);
        if ($arr[0] > 180)
            $ext = 'width="180"';
        else if ($arr[1] > 110)
            $ext = 'height="110"';
    }
    return array($file,$ext);
}

function clean_esp($cadena) { /* Limpiar espacios */
    return preg_replace('/\s\s+/', ' ', $cadena);
    ;
}


function check_diff_multi($array1, $array2){
    $result = array();
    foreach($array1 as $key => $val) {
         if(isset($array2[$key])){
           if(is_array($val) && $array2[$key]){
               $result[$key] = check_diff_multi($val, $array2[$key]);
           }
       } else {
           $result[$key] = $val;
       }
    }

    return $result;
}

function paginar($actual, $total, $por_pagina, $enlace) {
    $total_paginas = ceil($total / $por_pagina);
    $anterior = $actual - 1;
    $posterior = $actual + 1;
    if ($actual > 1)
        $texto = "<a href=\"$enlace$anterior\">&laquo;</a>";
    else
        $texto = "<a href='#'>&laquo;</a>";
    for ($i = 1; $i < $actual; $i++)
        $texto .= "<a href=\"$enlace$i\">$i</a> ";
    $texto .= "<b>$actual</b>";
    for ($i = $actual + 1; $i <= $total_paginas; $i++)
        $texto .= "<a href=\"$enlace$i\">$i</a> ";
    if ($actual < $total_paginas)
        $texto .= "<a href=\"$enlace$posterior\">&raquo;</a>";
    else
        $texto .= "<a href='#'>&raquo;</a>";
    return $texto;
}

function paginar_aventuras($actual, $total, $por_pagina, $enlace) {
    $total_paginas = ceil($total / $por_pagina);
    $anterior = $actual - 1;
    $posterior = $actual + 1;
    $texto = " ";
    $texto .= '<nav>
              <ul class="pager">
             ';
                
                
    if ($actual > 1)
        $texto .= '<li class="previous"><a href="' . $enlace . $anterior . '"><span aria-hidden="true">&larr;</span> Anterior</a></li>';
    else
        $texto .= '<li class="previous disabled"><a href="aventuras"><span aria-hidden="true">&larr;</span> Anterior</a></li>';
    $texto .= " ";
//    for ($i = 1; $i < $actual; $i++)
//        $texto .= " <a href=\"$enlace$i\">$i</a>  ";
//    $texto .= "<a id='active_paginado'>$actual</a> ";
//    for ($i = $actual + 1; $i <= $total_paginas; $i++)
//        $texto .= " <a href=\"$enlace$i\">$i</a> ";
    $texto .= " ";
    if ($actual < $total_paginas)
        $texto .= '<li class="next"><a href="' . $enlace . $posterior . '">Siguiente <span aria-hidden="true">&rarr;</span></a></li>';
    else
        $texto .= '<li class="next disabled"><a href="aventuras">Siguiente <span aria-hidden="true">&rarr;</span></a></li>';
    
    $texto .= '</ul>
        </nav>';
    
    return $texto;
}

function comillas_inteligentes($valor) {
    // Retirar las barras
    if (get_magic_quotes_gpc()) {
        $valor = stripslashes($valor);
    }

    // Colocar comillas si no es entero
    if (!is_numeric($valor)) {
        $valor = "'" . mysql_real_escape_string($valor) . "'";
    }

    //utilizar con sprintf($consulta)
    return $valor;
}

function formato_date($comodin, $fecha) {
    $nfecha = explode($comodin, $fecha);
    $dia = $nfecha[0];
    $mes = $nfecha[1];
    $anio = $nfecha[2];
    $ufecha = anio . "-" . $mes . "-" . $dia;
    return $ufecha;
}

function formato_slash($comodin, $fecha) {
    $nfecha = explode($comodin, $fecha);
    $dia = $nfecha[2];
    $mes = $nfecha[1];
    $anio = $nfecha[0];
    $ufecha = $dia . "/" . $mes . "/" . $anio;
    return $ufecha;
}

function comparar_fechas($fecha, $fecha_comparar = null) {
    if ($fecha_comparar == null) {
        $fecha_comparar = date("Y-m-d H:i:s");
    }

    $fecha = strtotime($fecha);
    $fecha_comparar = strtotime($fecha_comparar);

    if ($fecha == $fecha_comparar) {
        return 0;
    } else if ($fecha < $fecha_comparar) {
        return -1; //-1 si la primer fecha es menor
    } else if ($fecha > $fecha_comparar) {
        return 1; //1 si la primer fecha es mayor a la segunda
    }

    return false;
}

function send($text) {
    header("Content-type: text/html; charset=utf-8");
    echo utf8_encode($text);
}

function passcont($psw) {
    $txt = strlen($psw);
    $txt1 = substr($psw, 0, 3);
    $txt2 = substr($psw, 3, 3);
}

function impSelect($tabla, $extra, $idd) {

    if ($tabla == "provincias") {
        $cat = "departamento";
    } else if ($tabla == "distritos") {
        $cat = "provincia";
    }

    $where = " WHERE id_$cat = '" . $idd . "' ";

    $sql = "SELECT * FROM " . $tabla . " " . $where;
    $query = mysql_query($sql);

    $retur = "";
    $retur.= '<select name = "' . $tabla . '" ' . $extra . '   >
		<option value="">Seleccionar... </option>';
    while ($row = mysql_fetch_array($query)) {
        $retur .= " <option value='" . $row[0] . "' > " . $row[2] . " ";
    }
    //$retur.= $nuevo_valor; 
    $retur .= "</select>";
    // echo  $retur;
    echo $retur;
}

function passencode($password) {
    $newpass = ( md5($password) . '&' . strrev(strlen($password)) );
    return $newpass;
}

function passdecode($password) {
    $newpass = strrev($password);
    $newpass = explode('&', $newpass);
    $newpass = $newpass[0];
    return $newpass;
}

function encriptar($valor) {
    $cad = strlen($valor);
    $subcad = ceil($cad / 2);
    $prev_valor = substr(strrev($valor), 0, $subcad);
    $next_valor = substr(strrev($valor), $subcad, $cad);
    $pcad = $cad * 647667904564;
    $pass = $pcad . '|' . $prev_valor . '$' . $subcad . '|' . $next_valor . '$w3809245n0t9';
    return str_replace("'", "?", $pass);
}

function desencriptar($valor) {
    $cad = strlen($valor);
    $subcad = ceil($cad / 2);
    $new_valor = explode("|", $valor);

    $pvalor = explode("$", $new_valor[1]);
    $prev_valor = $pvalor[0];

    $nvalor = explode("$", $new_valor[2]);
    $next_valor = $nvalor[0];

    $pass = strrev($prev_valor . $next_valor);
    return str_replace('?', "'", $pass);
}

function in_multi_array($needle, $haystack) {
    $in_multi_array = false;
    if (in_array($needle, $haystack)) {
        $in_multi_array = true;
    } else {
        for ($i = 0; $i < sizeof($haystack); $i++) {
            if (is_array($haystack[$i])) {
                if (in_multi_array($needle, $haystack[$i])) {
                    $in_multi_array = true;
                    break;
                }
            }
        }
    }
    return $in_multi_array;
}

function encode_json($array) {
    $array_claves = array_keys($array);
    $filas = count($array, COUNT_RECURSIVE);
    $filas_array = count($array);
    if ($filas == 0 or $filas == "")
        return false;
    else {
        if ($filas > $filas_array) {
            $coma = "";
            for ($j = 0; $j < $filas_array; $j++) {
                $array_claves = array_keys($array[$j]);
                $filas = count($array[$j]);
                $array_array = $array[$j];
                $vector = $vector . $coma . recuperar_array($array_claves, $filas, $array_array);
                $coma = ", ";
            }
            $vector = '[' . $vector . ']';
            return $vector;
        } else {
            $vector = recuperar_array($array_claves, $filas, $array);
        }
    }
}

function recuperar_array($array_claves, $filas, $array) {
    for ($i = 0; $i < $filas; $i++) {
        $coma = ", ";
        if (($i + 1) == $filas)
            $coma = "";
        $vector = $vector . '"' . $array_claves[$i] . '":"' . eregi_replace("[\n|\r|\n\r]", ' ', utf8_encode($array[$array_claves[$i]])) . '"' . $coma;
    }
    $vector = "{" . $vector . "}";
    return $vector;
}

function Month($fecha) {
    $nfecha = explode("-", $fecha);
    $dia = $nfecha[2];
    $mes = $nfecha[1];
    $ano = $nfecha[0];
    $meses = array('01' => 'ENE', '02' => 'FEB', '03' => 'MAR', '04' => 'ABR', '05' => 'MAY', '06' => 'JUN', '07' => 'JUL', '08' => 'AGO', '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DIC');
    return $meses[$mes];
}

function fecha_min_long($fecha) {
    $nfecha = explode("-", $fecha);
    $dia = $nfecha[2];
    $mes = $nfecha[1];
    $ano = $nfecha[0];
    $meses = array('01' => 'ENE', '02' => 'FEB', '03' => 'MAR', '04' => 'ABR', '05' => 'MAY', '06' => 'JUN', '07' => 'JUL', '08' => 'AGO', '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DIC');
    return $dia . ' ' . $meses[$mes];
}

function fecha_long($fecha) {
    $nfecha = explode("-", $fecha);
    $dia = $nfecha[2];
    $mes = $nfecha[1];
    $ano = $nfecha[0];
    $meses = array('01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre');
    return $dia . " de " . $meses[$mes] . " del " . $ano;
}

function ext($archivo) {
    $trozos = explode(".", $archivo);
    $ext = $trozos[count($trozos) - 1];
    return (string) $ext;
}

function formatVideo($video, $w, $h) {
    $nvideo = str_replace('width="640" height="385"', 'width="' . $w . '" height="' . $h . '"', $video);
    $nvideo = str_replace('width="560" height="340"', 'width="' . $w . '" height="' . $h . '"', $nvideo);
    $nvideo = str_replace('width="480" height="385"', 'width="' . $w . '" height="' . $h . '"', $nvideo);
    $nvideo = str_replace('allowfullscreen="true"', 'allowfullscreen="true" wmode="transparent"', $nvideo);
    return $nvideo;
}

function validateUser($id) {
    $objca = new ClienteAdmin($id);
    $plan = $objca->__get("_planes");

    if ($plan[0]['estado'] == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

function aumentarMonth($desde, $cant) {
    $fecha = $desde;
    return date("Y-m-d", strtotime("$fecha +" . $cant . " month"));
}

function updateEstatus() {
    $query = new Consulta("SELECT * FROM clientes_planes WHERE fecha_finaliza < '" . date("Y-m-d") . "'");
    while ($row = $query->VerRegistro()) {
        $queryU = new Consulta("UPDATE clientes_planes SET estado = '0' 
										WHERE id_cliente_plan = '" . $row['id_cliente_plan'] . "'");
    }
}

function inCategories($id_cat = 0) {

    $sql = "SELECT * FROM categorias c, categorias_idiomas ci 
					WHERE c.id_categoria = ci.id_categoria
					AND  ci.id_idioma = '" . ID_IDIOMA . "' ORDER BY c.id_categoria";

    $query = new Consulta($sql);

    while ($row = $query->VerRegistro()) {
        $data[$row['id_categoria']] = array("parent_id" => $row['id_parent'], "name" => $row['nombre_categoria']);
    }
    $array = $data;

    return createTree($array, $id_cat);
}

function createTree($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
    foreach ($array as $categoryId => $category) {
        if ($currentParent == $category['parent_id']) {

            return $categoryId . ",";

            if ($currLevel > $prevLevel) {
                $prevLevel = $currLevel;
            }
            $currLevel++;
            createTree($array, $categoryId, $currLevel, $prevLevel);
            $currLevel--;
        }
    }
}

function getUrlActual() {
    $numero = count($_GET);
    $tags = array_keys($_GET);
    $valores = array_values($_GET);

    for ($i = 0; $i < $numero; $i++) {
        $cad .=$tags[$i] . "=" . $valores[$i] . "&";
    }
    $url = ($cad == '') ? basename($_SERVER['PHP_SELF']) : basename($_SERVER['PHP_SELF']) . '?' . substr($cad, 0, strlen($cad) - 1);
    return $url;
}

function location($url) {
    echo '<script type="text/javascript">location.href="' . $url . '";</script>';
}

function get_uid_producto($prid, $params) {
    $uprid = $prid;
    if ((is_array($params)) && (!strstr($prid, '{'))) {
        while (list($option, $value) = each($params)) {
            $uprid = $uprid . '{' . $option . '}' . $value;
        }
    }
    return $uprid;
}

function get_id_producto($uprid) {
    $pieces = explode('{', $uprid);

    return $pieces[0];
}

function sef_string($str) {
    // Eliminar entidades HTML
    $search = array('&lt;', '&gt;', '&quot;', '&amp;');
    $str = str_replace($search, '', $str);
    $str = preg_replace('/&(?!#[0-9]+;)/s', '', $str);

    // Convertir acentos y tildes
    $search = array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ü', 'ü', 'Ñ', 'ñ', '_');
    $replace = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'u', 'u', 'n', 'n', ' ');
    $str = str_replace($search, $replace, strtolower(trim($str)));

    // Eliminar todo lo que no sea letras, numeros o espacios y eliminar espacios dobles
    $str = preg_replace("/[^a-zA-Z0-9\s]/", "", $str);
    $str = preg_replace('/\s\s+/', ' ', $str);

    // Convertir espacios en guiones
    $str = str_replace(' ', '-', $str);
    return strtolower($str);
}

function typeImage($type) {
    $type_file = "";
    switch ($type) {
        case 'image/jpeg': case 'image/pjpeg':
            $type_file = ".jpg";
            break;
        case 'image/gif':
            $type_file = ".gif";
            break;
        case 'image/png':
            $type_file = ".png";
            break;
    }
    return $type_file;
}

function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}
?>