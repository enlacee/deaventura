<?php

class Listado {
    /* Muestra un listado con opciones */

    function simple(Consulta $query, $url = "", $sub_url = "") {
        $return = "";

        $colspan = $query->NumeroCampos();
        $return.= "
		<div id='content-area'>			
		<table id='listado' cellpading='1' cellspacing='1' >\n";

        $return.= "<tr>";
        $tab = substr($query->nombrecampo(0), 3, 30);
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = str_replace($tab, "", $query->nombrecampo($i));
            $name = str_replace("id_", "", $name);
            $name = str_replace("_", " ", $name);
            $return.= "<td class='titulo'><a href='" . $_SERVER['PHP_SELF'] . "?orden=" . $query->nombrecampo($i) . "'>" . ucwords($name) . "</a></td>\n";
        }
        $return.= "<td class='titulo' align='center' width='80'>Opciones</td></tr>\n";
        $x = 0;
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $id1 = $row[1];

            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class=fila1";
            } else {
                $return.= "class=fila2";
            } $return.= "> \n";

            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td align=left class=celda>" . substr($row[$i], 0, 50) . "</td>\n";
            }




            $return .= "<td align='center'>
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'edit') title=Editar class='tooltip'><img src='" . _icons_ . "edit.png' ></a> &nbsp; 
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'delete') title=Eliminar class='tooltip'> <img src='" . _icons_ . "button_drop.png' ></a>&nbsp;";
            if (!empty($sub_url)) {
                $return .=" <a href='#Detalle' title='" . $sub_url . "?id=" . $id . "' class='tooltip'><img src='" . _icons_ . "customers.png'></a>";
            }
            $return .="</td>
					</tr>";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return .= '</table>
			</div>';
        return $return;
    }

    /* Muestra un listado con solo la opcion lupa, ideal para visualizacion de datos */

    function simpleConVisor(Consulta $query, $url = "") {
        $return = "";

        $colspan = $query->NumeroCampos();
        $return.= "
		<div id='content-area'>			
			<table id='listado' cellpading='1' cellspacing='1' >\n";

        $return.= "<tr>";
        $tab = substr($query->nombrecampo(0), 3, 30);
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = str_replace($tab, "", $query->nombrecampo($i));
            $name = str_replace("id_", "", $name);
            $name = str_replace("_", " ", $name);
            $return.= "<td class='titulo'><a href='" . $_SERVER['PHP_SELF'] . "?orden=" . $query->nombrecampo($i) . "'>" . ucwords($name) . "</a></td>\n";
        }
        $return.= "<td class='titulo' align='center' width='80'>Opciones</td></tr>\n";
        $x = 0;
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class='fila1'";
            } else {
                $return.= "class='fila2'";
            } $return.= "> \n";
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td>" . substr($row[$i], 0, 50) . "</td>\n";
            }
            $return .= "<td align='center'><a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'view') title='Ver' ><img src='" . _icons_ . "zoom.png' ></a> </td>\n";
            $return .="</tr>
			";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return .= '</table>
		</div>';
        return $return;
    }

    /* Muestra un listado */

    public static function VerListado(Consulta $query, $url = "", $sub_url = "", $preview = "", Usuario $user) {
        $return = "";
        $colspan = $query->NumeroCampos();
        $return.= "
				<div id='content-area'>
				<table class='listado' cellpading='1' cellspacing='1' >\n";
        $return.= "<thead><tr class='head'>";
        $tab = substr($query->nombrecampo(0), 3, 30);
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = str_replace($tab, "", $query->nombrecampo($i));
            $name = str_replace("id_", "", $name);
            $name = str_replace("_", " ", $name);
            $return.= "<th class='titulo' align='left'><a>" . ucwords($name) . "</a></th>\n";
        }
        $return.= "<th class='titulo' align='center' width='98'>Opciones</th></tr></thead>\n";
        $x = 0;
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $id1 = $row[1];
            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class='row'";
            } else {
                $return.= "class='row odl'";
            } $return.= "> \n";
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td align=left class=celda>" . substr($row[$i], 0, 50) . "</td>\n";
            }

            if ($user->getRol()->getNombre() == "Administrador") {
                $eliminar = "<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'delete') title=Eliminar class='tooltip'>
					<img src='" . _admin_ . "delete.png' ></a>&nbsp;";
            } else {
                $eliminar = "";
            }
            $return .= "<td align='center'>
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'edit') title=Editar class='tooltip'>
					<img src='" . _admin_ . "edit.png' ></a> &nbsp; 
					" . $eliminar;
            if (!empty($sub_url)) {
                $return .=" <a href='" . $sub_url . "?action=list&id1=" . $id . "' title='Detalle' class='tooltip'>
									<img src='" . _icons_ . "index.gif'  ></a>";
            }
            if (!empty($preview)) {
                $return .=" <a href='" . $preview . "?action=view&id=" . $id . "' target='vista' title='Vista Previa'>
									<img src='" . _icons_ . "view.gif'  ></a>";
            }
            $return .="</td>
					</tr>";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return.= '</table>';
        $return.= '</div>';
        return $return;
    }

    /* Muestra un listado con check al lado izquierdo de cada registro */

    function VerListadoConCheck(Consulta $query, $url = "", $sub_url = "") {

        $return = "";
        $return .= "<form name='form' method='post' > \n";
        $colspan = $query->NumeroCampos();
        $return.= "<div id='content-area'>\n";
        $return.= "<table id='listado' cellpading='1' cellspacing='1' >\n";

        $return.= "<tr>";
        $return.= "<td class='titulo' align='center' width='20'> <input type='checkbox' id='chkall'> </td>\n";
        $tab = substr($query->nombrecampo(0), 3, 30);
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = str_replace($tab, "", $query->nombrecampo($i));
            $name = str_replace("id_", "", $name);
            $name = str_replace("_", " ", $name);
            $return.= "<td class='titulo'><a href='" . $_SERVER['PHP_SELF'] . "?orden=" . $query->nombrecampo($i) . "'>" . ucwords($name) . "</a></td>\n";
        }
        $return.= "<td class='titulo' align='center' width='80'>Opciones</td></tr>\n";
        $x = 0;
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $id1 = $row[1];

            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class=fila1";
            } else {
                $return.= "class=fila2";
            } $return.= "> \n";
            $return.= "<td align='center' width='20'><input type='checkbox' name='chklst[]' value='" . $id . "'> </td>\n";
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td align=left class=celda>" . substr($row[$i], 0, 50) . "</td>\n";
            }

            $return .= "<td align='center'>
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'edit') title=Editar >
					<img src='" . _icons_ . "button_edit.png' ></a> &nbsp; 
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'delete') title=Eliminar>
					<img src='" . _icons_ . "button_drop.png' ></a>&nbsp;";
            if (!empty($sub_url)) {
                $return .=" <a href='" . $sub_url . "?action=list&id1=" . $id . "' title='Detalle'>
									<img src='" . _icons_ . "index.gif'></a>";
            }
            $return .="</td>
					</tr>";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return .= '</table>';
        $return .= '</div>';
        $return .= "</form> \n";
        return $return;
    }

    /* Muestra un listado con check al lado izquierdo de cada registro y con un boton que muestra al lado derecho contenido */

    function checkConLadoDerecho(Consulta $query, $url = "", $sub_url = "") {
        $return = "";
        $return .= "<form name='form' method='post' > \n";
        $colspan = $query->NumeroCampos();
        $return.= "<div id='content-area'>\n";
        $return.= "<table id='listado' cellpading='1' cellspacing='1' >\n";

        $return.= "<tr>";
        $return.= "<td class='titulo' align='center' width='20'> <input type='checkbox' id='chkall'> </td>\n";
        $tab = substr($query->nombrecampo(0), 3, 30);
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = str_replace($tab, "", $query->nombrecampo($i));
            $name = str_replace("id_", "", $name);
            $name = str_replace("_", " ", $name);
            $return.= "<td class='titulo'><a href='" . $_SERVER['PHP_SELF'] . "?orden=" . $query->nombrecampo($i) . "'>" . ucwords($name) . "</a></td>\n";
        }
        $return.= "<td class='titulo' align='center' width='80'>Opciones</td></tr>\n";
        $x = 0;
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $id1 = $row[1];

            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class=fila1";
            } else {
                $return.= "class=fila2";
            } $return.= "> \n";
            $return.= "<td align='center' width='20'><input type='checkbox' name='chklst[]' value='" . $id . "'> </td>\n";
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td align=left class=celda>" . substr($row[$i], 0, 50) . "</td>\n";
            }

            $return .= "<td align='center'>
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'edit') title=Editar ><img src='" . _icons_ . "edit.png' ></a> &nbsp; 
					<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'delete') title=Eliminar> <img src='" . _icons_ . "button_drop.png' ></a>&nbsp;";
            if (!empty($sub_url)) {
                $return .=" <a href='#Detalle' title='" . $sub_url . "?id=" . $id . "'><img src='" . _icons_ . "customers.png'></a>";
            }
            $return .="</td>
					</tr>";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return .= '</table>';
        $return .= '</div>';
        $return .= "</form> \n";
        return $return;
    }

    public static function VerListadoAjax($sql = "", Consulta $query, $cabecera = array(), $url = "", $sub_url = "", $type = "") {
        $return = "";
        $colspan = $query->NumeroCampos();
        $return.='
		<input type="hidden" name="tgrid" id="tgrid" value="0" />
		<script type="text/javascript">
			$(document).ready(function() {
				$(\'.listaAdvanced\').dataTable( {
					"bLengthChange": false,
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "ajax.php?action=filtersAdvanced&query=' . encriptar($sql) . '&url=' . $url . '&sub_url=' . $sub_url . '",
					"sPaginationType": "full_numbers",
					"aoColumnDefs": [{ "bSortable": false, "aTargets": [ ' . ($query->NumeroCampos() - 1) . ' ] }]
				} );
			} );
		</script>
		';
        $return.= "
				<div id='content-area'>	
				<table id='listado' class='listaAdvanced' cellpading='1' cellspacing='1' >\n";
        $return.= "<thead><tr>";
        $tab = substr($query->nombrecampo(0), 3, 30);
        if (count($cabecera) > 0) {
            for ($i = 0; $i < count($cabecera); $i++) {
                $name = $cabecera[$i];
                $return.= "<th class='titulo'><a>" . ucwords($name) . "</a></th>\n";
            }
        } else {
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $name = str_replace($tab, "", $query->nombrecampo($i));
                $name = str_replace("id_", "", $name);
                $name = str_replace("_", " ", $name);
                $return.= "<th class='titulo'><a>" . ucwords($name) . "</a></th>\n";
            }
        }

        $return.= "<th class='titulo' align='center' width='80'>Opciones</th></tr></thead>\n";
        $x = 0;
        $return.="<tbody>";
        while ($row = mysql_fetch_row($query->Consulta_ID)) {
            $id = $row[0];
            $id1 = $row[1];
            $return.= "<tr ";
            if ($x == 0) {
                $return.= "class=fila1";
            } else {
                $return.= "class=fila2";
            } $return.= "> \n";
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                $return.= "<td align=left class=celda>" . substr($row[$i], 0, 50) . "</td>\n";
            }
            $return .= "<td align='center'>
						<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'edit') title=Editar >
						<img src='" . _icons_ . "button_edit.png' ></a> &nbsp; 
						<a href='#' onClick=mantenimiento('" . $url . "'," . $id . ",'delete') title=Eliminar>
						<img src='" . _icons_ . "button_drop.png' ></a>&nbsp;";
            if (!empty($sub_url)) {
                $return .=" <a href='" . $sub_url . "?action=list&id1=" . $id . "' title='Detalle'>
										<img src='" . _icons_ . "index.gif'  ></a>";
            }
            $return .="</td>
					</tr>";
            if ($x == 0) {
                $x++;
            } else {
                $x = 0;
            }
        }
        $return.= '</tbody></table>';
        $return.= '</div>';
        return $return;
    }

}

?>