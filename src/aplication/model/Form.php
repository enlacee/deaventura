<?php

class Form {

    private $_sql;
    private $_tabla;
    private $_tipo;

    /*
      $img['file']['img'];
      $img['width'];
     
     @query (sql) consulta sql 
     @opcion (new/edit) si es nuevo registro o edicion 
     @url la url que procesará el archivo
     @array_embed normalmente es un "select" que se inserta 
     @array_relation 
     @file es el nombre de la imagen
     @ruta es la ruta de la imagen
     *  */

        
    public function getForm($query, $opcion, $url = '', $array_embed = '', $array_relation = '', $file = '', $ruta = '') {
        $row = mysql_fetch_row($query->__get("Consulta_ID"));

        $table = $query->Nombretabla();

        if ($opcion == "edit") {
            $action = "update";
            $input = "ACTUALIZAR";
            $titulo = "Editar Registro";
        } else if ($opcion == "new") {
            $action = "add";
            $input = "GUARDAR";
            $titulo = "Nuevo Registro";
        }
        ?>
        <script type="text/javascript"> <?php if ($opcion == "edit") { ?>
                function load_imgs(){
            <?php
            for ($i = 1; $i < $query->NumeroCampos(); $i++) {
                if ($query->tamaniocampo($i) == 71) {
                    PHP_EOL
                    ?> document.<?php echo $table . "." . $query->nombrecampo($i) ?>.value="<?php echo $row[$i] ?>"; <?php
                }
            }
            ?>			   				
                    } 
        <?php } ?>
            function valida_<?php echo $table ?>(){ 			   				
        <?php
        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = $query->nombrecampo($i);
            $flags = $query->flagscampo($i);
            $type = $query->tipocampo($i);
            $len = $query->tamaniocampo($i);
            $validar = explode(' ', $flags);
            $second_nom = explode("_", $name);

            //echo "validar= '".$validar[0]."'";
            if ($len == 71) {
                $enctype = "enctype=\"multipart/form-data\"";
            }
            if ($validar[0] == "not_null") {
                if ($opcion == "edit" && $len == 71) {
                    //dejar pasar
                } else {
                    ?>					
                           
						   
						  
						  
						  
						   
						   
						      
							  
				    <?php
                    if($name==="descripcion_articulo"){
                    ?>                         
                        var descripcion = tinyMCE.activeEditor.getContent();
                        if(descripcion===""){  
                            alert('ERROR: El campo <?php echo str_replace("_", " ", str_replace('id_', ' ', $name)) ?> debe llenarse');
                            document.<?php echo $table . "." . $name ?>.focus(); 
                            return false;
                        }                              
                    <?php      
                    }
                    else{
                    ?>			  
							
							  
							            
                    if(document.<?php echo $table . "." . $name ?>.value==""){
                        alert('ERROR: El campo <?php echo str_replace("_", " ", str_replace('id_', ' ', $name)) ?> debe llenarse');
                        document.<?php echo $table . "." . $name ?>.focus(); 
                        return false;
                    }
					
					
					
					
					<?php     
                    } 
                    ?> 
					
					
					
					
					
					
					
					
					
					
					
					

                    <?php
                }
            }
        }
        ?>
                var c_email = $(".email").val();
                if(c_email != undefined && c_email != ""){
                    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                    if(!c_email.match(emailRegex)) {
                        alert('Error: Ha introducido una dirección de correo electrónico no válida.');
                        $(".email").focus(); 
                        return false;
                    }
                }
                        
                document.<?php echo $table ?>.action="<?php echo $url ?>?action=<?php echo $action ?>&id=<?php echo $row[0] ?>";
                document.<?php echo $table ?>.submit();
            }			
        </script>  <?php
        echo "
			<fieldset id='form'>
			<legend> " . $titulo . "</legend>			
			<form name='" . $table . "' method='post' action='' " . $enctype . " > \n 
				<div class='button-actions'>
					<input type='reset' name='cancelar' value='CANCELAR' class='button' >  
					<input type='button' name='actualizar' value='" . $input . "' onclick='return valida_" . $table . "()' class='button'><br clear='all' />\n  				</div>
				<ul>";

        for ($i = 1; $i < $query->NumeroCampos(); $i++) {
            $name = $query->nombrecampo($i);
            $type = $query->tipocampo($i);
            $len = $query->tamaniocampo($i);
            if ($opcion == "edit") {
                $r = $row[$i];
            } else {
                $r = "";
            }
            echo " 
					<li><label> " . ucwords(str_replace("_", " ", str_replace("id_", " ", $query->nombrecampo($i)))) . ": </label>";
            if (is_array($array_embed) && array_key_exists($i, $array_embed)) {
                echo $array_embed[$i];
            } else {
                switch ($type) {
                    case 'int':
                        if ($len == 1) {
                            echo " <input type='radio' name='" . $name . "' value=1 ";
                            if ($r == 1 && $r != 0) {
                                echo "checked='checked'";
                            } echo"> SI &nbsp; &nbsp; ";
                            echo " <input type='radio' name='" . $name . "' value=0 ";
                            if ($r == 0 && $r != 1) {
                                echo "checked='checked'";
                            } echo"> NO ";
                        } else {
                            echo "<input type='text' name='" . $name . "' dir='rtl'  value='" . $r . "' onKeyPress='return validnum(event)' class='num' >";
                        }

                        break;
                    case 'real':
                        echo "<input type='text' name='" . $name . "' value='" . $r . "'  class='num solo_numero'  dir='rtl'>";
                        break;
                    case 'string':

                        $first_nom = explode("_", $name);
                        if ($len == 71) {
                            echo "<input type='file' name='" . $name . "' value='" . $r . "'  class='text ui-widget-content ui-corner-all'>&nbsp;";
                            if (!empty($r)) {
                                $ruta_ok = ( $ruta != '' ) ? $ruta : '';
                                echo "<div align='center'><br><img src='../aplication/utilities/" . $file . ".php?imagen=" . $r . "&w=200&h=120&ruta=" . $ruta_ok . "' /></div>";
                            }
                        } else if ($first_nom[0] == 'email') {
                            echo "<input type='text' name='" . $name . "' value='" . $r . "' class='text ui-widget-content ui-corner-all email' size='59'  maxlength=" . $len . " >";
                        } else {
                            echo "<input type='text' name='" . $name . "' value='" . $r . "' class='text ui-widget-content ui-corner-all' size='59'  maxlength=" . $len . " >";
                        }

                        break;
                    case 'blob':
                        echo "<textarea name='" . $name . "' id='" . $name . "' value='' class='textarea tinymce'>" . $r . "</textarea> ";
                        break;
                    case 'date' || 'datetime':
                        if (!empty($r)) {
                            $r = formato_slash("-", $r);
                        } else {
                            $r = date("d/m/Y");
                        }
                        echo '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $r . '" 
						 size="12" class="date">';
                        break;
                }
            }
            echo "</li>";
        }

        echo " 
					</ul>
				</form>
			</fieldset>\n";
    }

    static public function select($items, $nombre, $key, $value, $id = 0) {

        $total_items = count($items);
        $seleccione = str_replace("_", " ", $nombre);
        $r = "<select name='id_$nombre' id='id_$nombre'>
				";
        $r.= "<option value=' '> Seleccione $seleccione</option>
				";
        for ($i = 0; $i < $total_items; $i++) {
            $r.= "<option value='" . $items[$i][$key] . "' ";
            if ($items[$i][$key] == $id) {
                $r.= "selected='selected'";
            }
            $r.= "> " . $items[$i][$value] . " </option>
				";
        }
        $r.="</select>";
        return $r;
    }

    static public function selectMultiple($items, $nombre, $key, $value, $ids) {

        $total_items = count($items);
        $r = "<select name='id_" . $nombre . "[]' multiple size='7'>";
        $r.= "<optgroup label='Seleccione $nombre'> Seleccione $nombre</optgroup>";
        for ($i = 0; $i < $total_items; $i++) {
            $r.= "
				<option value='" . $items[$i][$key] . "'";
            if (is_array($ids) && in_multi_array($items[$i][$key], $ids)) {
                $r.= " selected ";
            }
            $r.="> " . $items[$i][$value] . " </option>";
        }
        $r.="</select>";
        return $r;
    }

    static public function selectOpcionParaAccionMasiva($array_opciones) {
        $r = "Para los elementos seleccionados: ";
        $r .= "<select name='opcion' id='opcion'>
		";
        $r.= "<option value=''> Seleccione Accion </option>
		";
        $total_opciones = count($array_opciones);
        if (is_array($array_opciones) && count($total_opciones) > 0) {
            foreach ($array_opciones as $key => $value) {
                $r.= "<option value='" . $value . "'> " . $key . " </option>
				";
            }
        }

        $r.="</select>";
        return $r;
    }

}
?>