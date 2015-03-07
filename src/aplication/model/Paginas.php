<?php	
class Paginas{
	
	private $_msgbox;
	
	public function __construct(Msgbox $msg = NULL){
		$this->_msgbox = $msg;			
	}
	
	public function newPaginas(){
		$query = new Consulta("SELECT id_pagina,titulo_pagina,descripcion_pagina  FROM paginas");
		Form::getForm($query, "new", "paginas.php");
	} 
	public function addPaginas() {
		 $query = new Consulta("INSERT INTO paginas VALUES('','".$_POST['titulo_pagina']."','".$_POST['descripcion_pagina']."','".$this->orderPaginas()."')");
		 $this->_msgbox->setMsgbox('Se grabo correctamente la Pagina.',2);	
         location("paginas.php?action=list");
    }	
	public function editPaginas(){
		$query = new Consulta("SELECT id_pagina,titulo_pagina,descripcion_pagina FROM paginas WHERE id_pagina = '".$_GET['id']."'");
		Form::getForm($query, "edit", "paginas.php");
	} 
	public function updatePaginas() {
		$query = new Consulta("UPDATE paginas SET titulo_pagina='".$_POST['titulo_pagina']."',												
												descripcion_pagina='".$_POST['descripcion_pagina']."'
                                     	        WHERE id_pagina = '".$_GET['id']."'");
		$this->_msgbox->setMsgbox('Se actualizo correctamente la Pagina.',2);
		location("paginas.php?action=list");
    }
	
	public function deletePaginas(  ){
		$query = new Consulta("DELETE  FROM paginas WHERE id_pagina = '".$_GET['id']."'");						
		$this->_msgbox->setMsgbox('Se elimino correctamente la Pagina.',2);
		location("paginas.php?action=list");
	}
	
	
	public function ordenarPaginasAjax(){
		foreach($_GET['list_item'] as $position => $item){
			$type_val = explode("|",$item);			
			if($type_val[1] == 'pag'){
				$query = new Consulta("UPDATE  paginas  SET orden_pagina = '".$position."'  WHERE id_pagina = '".$type_val[0]."'"); 	
			}
		}
	}
	
	public function listPaginas(){
		$query = new Consulta("SELECT id_pagina, titulo_pagina FROM paginas ORDER BY orden_pagina ASC");  
		?>
         <div id="content-area">
            <table cellspacing="1" cellpading="1" class="listado">
                <thead>
                    <tr class="head">
                        <th align="left">Páginas</th>
                        <th align="center" width="100" class="titulo">Opciones</th>
                   </tr>
                </thead>
            </table>	
            <ul id="listadoul">
			 <?php
			 $y = 1;
				while($rowa = $query->VerRegistro()){
					?>
					 <li class="<?php echo ($y%2 == 0) ? "fila1" : "fila2"; ?>" id="list_item_<?php echo $rowa['id_pagina']."|pag"; ?>">
						<div class="data"> <img src="<?php echo _admin_ ?>icon-paginas.png" class="handle">   <?php echo $rowa['titulo_pagina'] ?></div>
						<div class="options"> 
							
                            <a class="tooltip move"  title="Ordenar ( Click + Arrastrar )"><img src="<?php echo _admin_ ?>move.png" class="handle"></a>&nbsp;
                            
                            
							<a title="Editar" class="tooltip" href="#" onclick="mantenimiento('paginas.php','<?php echo $rowa['id_pagina'] ?>','edit')">
							<img src="<?php echo _admin_ ?>edit.png"></a> &nbsp;
                            
                            <a title="Eliminar" class="tooltip" onClick="mantenimiento('paginas.php','<?php echo $rowa['id_pagina'] ?>','delete')"  href="#"> <img src="<?php echo _admin_ ?>delete.png"></a>&nbsp;
                          
                            </div>
						 </li>
				<?php
					$y++;
					}
	} 
	
	public function orderPaginas(){
		$query = new Consulta("SELECT MAX(orden_pagina) max_orden 
									FROM paginas");
		
		$row   = $query->VerRegistro();
		return (int)($row['max_orden']+1);
	}
	
	public static function libroReclamaciones(){
		?>
        <div id="pagRight">
        	<h1 class="cat_flores">Libro de Reclamaciones</h1>
            <div id="descripcion_pagina">
				<!--<form>
                	<ul class="tipo-persona">
                    	<li><input type="radio" name="tipo_persona" value="pn"><label>Persona Natural</label></li>
                        <li><input type="radio" name="tipo_persona" value="pj"><label>Persona Juridica</label></li>
                    </ul>
                    <ul class="left">
                    	<li><label>Nombre y Apellido:</label><input type="text" name="nombre_y_apellido"></li>
                        <li><label>Razón Social:</label><input type="text" name="razon_social"></li>
                        <li><label>Domicilio:</label><input type="text" name="domicilio"></li>
                    </ul>
                    <ul class="right">                    
                        <li><label>Doc. Identidad</label>
                        	<select name="doc_identidad">
                            	<option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select></li>                                                    
                        <li><label>N. Documento:</label><input type="text" name="n_documento"></li>
                        <li><label>Teléfono:</label><input type="text" name="telefono"></li>
                    </ul>
                    <ul class="bottom-left">
                    	<li><label>Departamento:</label>
                        	<select name="departamento">
                            	<option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select></li>
                        <li><label>Distrito:</label>
                        	<select name="distrito">
                            	<option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select></li>    
                    </ul>
                    <ul class="bottom-right">
                    	<li><label>Provincia:</label>
                        	<select name="provincia">
                            	<option value="DNI">DNI</option>
                                <option value="RUC">RUC</option>
                            </select></li>
                        <li><label>Email:</label><input type="text" name="email"></li>    
                    </ul>
                    
                </form>-->
                
              <form class="reclamacion" onsubmit="return validate_form_reclamo(this)" name="form_reclamacion" method="post" id="form_reclamacion"> 
              
              <input type="hidden" name="action" value="sendMailReclamacion">
		<h2 class="righttd">Identificador del consumidor reclamante</h2>
        <table width="100%" border="0">
        <tbody><tr>
        <td class="righttd" colspan="4">
        
        <label class="label_radio" for="natural">
        <input name="persona_natural" onclick="show_empresa();" id="persona_natural" value="1" type="radio" checked="checked"> 
        Persona Natural</label>
        
        <label style="margin-left:40px;" class="label_radio" for="juridica">
        <input name="persona_natural" onclick="show_empresa();" id="persona_natural" value="2" type="radio"> 
        Persona Juridica</label></td>
        </tr>
        <tr>
        <td class="righttd" width="189">Nombre y Apellido:</td>
        <td width="261">
        <input name="nombres" type="text" class="inputgrande" id="nombres"></td>
        <td width="134" align="right">Doc. Identidad:</td>
        <td width="520">
        <select name="tipo_documento" style="width:129px" class="seleccionar" id="tipo_documento">
                <option value="1">DNI</option>
                <option value="2">RUC</option>
                </select></td>
        </tr>
        <tr>
          <td class="righttd">Razón Social:</td>
          <td>
            <input name="razon_social" type="text" class="inputgrande" id="razon_social"></td>
          <td align="right">N. Documento:</td>
          <td><input name="numero_documento" type="text" class="inputchico" id="numero_documento"></td>
        </tr>
        <tr>
          <td class="righttd">Domicilio:</td>
          <td><input name="direccion2" type="text" class="inputgrande" id="direccion2" /></td>
          <td align="right">Teléfono:</td>
          <td><input name="telefonos2" type="text" class="inputchico" id="telefonos2" /></td>
        </tr>
        <tr>
          <td class="righttd">Distrito:</td>
          <td><div style="float:left">
            <select id="distrito" style="width: 232px;" name="distrito" class="seleccionar">		
     <option value="--">[Distrito]</option><?php
     $ubigeos = new Ubigeos();
	 $distritos = $ubigeos->getDistritosConCobertura();
	 foreach($distritos as $dis):
	 	?><option value="<?php echo str_replace('Ã‘','Ñ',$dis['nombre'])?>"><?php echo str_replace('Ã‘','Ñ',$dis['nombre'])?></option><?php
	 endforeach;
	 ?></select>
            </div></td>
          <td align="right">E-mail:</td>
          <td><input name="email" style="width:184px" type="text" class="inputchico" id="email" /></td>
        </tr>
        </tbody></table>
        <table width="100%" border="0">
        <tbody>
        <tr>
          <td width="1104" class="righttd"><span><strong>¿Eres menor de Edad? </strong></span>
            <label style="margin-left:40px;" class="label_radio" for="SI">
              <input name="menor_edad" onclick="show_menor_edad();" id="menor_edad" value="1" type="radio">
              Si</label>
            <label style="margin-left:40px;" class="label_radio" for="NO">
              <input name="menor_edad" id="menor_edad" onclick="show_menor_edad();" value="0" type="radio" checked="checked">
              No</label>
            </td>
        </tr>
        <tr>
        <td class="righttd">Datos del Padre, Madre o Autor</td>
        </tr>
        </tbody></table>
        <table width="100%" border="0">
        <tbody><tr>
        <td width="206" class="righttd">Nombre y Apellido:</td>
        <td width="300"><input name="nombres_tutor" readonly="readonly" type="text" class="inputgrande" id="nombres_tutor"></td>
        <td width="113" align="right">DNI/CE:&nbsp;</td>
        <td width="531"><input name="documento_tutor" readonly="readonly" type="text" class="inputchico" id="documento_tutor"></td>
        </tr>
        <tr>
        <td colspan="4"><h2 class="righttd">Identificador del reclamo</h2></td>
        </tr>
        <tr>
        <td class="righttd" colspan="4">
        Tipo : 
          <label title="Disconformidad relacionada a los productos o servicios" style="margin-left:130px;" class="label_radio" for="reclamo"><input type="radio" name="tipo_solicitud" id="tipo_solicitud" checked="checked" value="1">
        Reclamo</label>
        <label title="Disconformidad no relacionada a los productos o servicios; o malestar o descontento respecto a la atención al público." style="margin-left:40px;" class="label_radio" for="queja"><input type="radio" name="tipo_solicitud" id="tipo_solicitud" value="2">
        Queja</label></td>
        </tr>
        <tr>
        <td class="righttd" colspan="4">
        Bien Contratado : 
          <label style="margin-left:65px;" class="label_radio" for="PRODUCTO"><input checked="checked" type="radio" name="contrato" id="contrato" value="1">
        </label>
          Producto
          <label style="margin-left:40px;" class="label_radio" for="SERVICIO"><input type="radio" name="contrato" id="contrato" value="2">
        </label>
          Servicio</td>
        </tr>
        </tbody></table>
        <table width="100%" border="0">
        <tbody><tr>
        <td class="righttd">Comprobante de Pago:</td>
        <td width="208">
        <select name="comprobante_pago" class="seleccionar" id="comprobante_pago">
                <option value="1">FACTURA</option>
                <option value="2">BOLETA</option>
                <option value="3">RECIBO</option>
                <option value="4">SIN COMPROBANTE</option>
                </select></td>
        <td width="155" align="right">N°</td>
        <td width="486"><input name="documento_numero_pago" style="width:190px" type="text" class="inputchico" id="documento_numero_pago"></td>
        </tr>
        <tr>
          <td class="righttd">Sede/Sucursal:</td>
          <td>            <select name="sedes" class="seleccionar" id="sedes">
              <option value="0">Ninguno</option>
                            <option value="3">
                Sede Principal                </option>
                          </select></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
        <td class="righttd" width="301">Detalle del Producto o Servicio</td>
        <td class="righttd" colspan="3"><label for="textarea"></label>
        <textarea class="areatextarea" name="descripcion_servicio" id="descripcion_servicio" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
        <td class="righttd">Detalle de la Reclamación o Queja</td>
        <td class="righttd" colspan="3"><textarea class="areatextarea" name="descripcion_reclamacion" id="descripcion_reclamacion" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
        <td class="righttd" colspan="4">
        <label class="label_check" for="checkbox"><input type="checkbox" name="condicion" id="condicion">
        </label>Declaro ser el titular del contenido del presente formulario,manifestando bajo declaración jurada los hechos descritos en él.
        <span id="MensajeError"></span>
        </td>
        </tr>
        <tr>
        <td class="righttd" colspan="4" align="center">        
        <input type="button" class="btn_reclamo" onclick="javascript:window.location='<?php echo _url_web_?>';" id="btn-cancel-reclamo" value="">        
        <input type="submit" class="btn_reclamo" id="btn-send-reclamo" value="">
        </td>
        
        </tr>
        </tbody></table>
  </form>
                
            </div>
        </div> 
        <?php
	}
	
	public static function cuerpo($getid){
		$pagina = new Pagina($getid);
		?>
        <div id="pagRight">
        	<h1 class="cat_flores"><?php echo $pagina->__get("_titulo")?></h1>
            <div id="descripcion_pagina">
			<?php echo str_replace('../','',$pagina->__get("_decripcion"))?>
            </div>
        </div>
        <?php
	}
	
	public static function NavegacionFront($getid){
		
		if(!empty($getid) && $getid>0){
			$pagina = new Pagina($getid);
		}
		?>
        <a href="#">Servicio al Cliente</a> > <?php echo $pagina->__get("_titulo");?>
        <?php
	}
	
	public static function getIdByName($nombre){
		$sql = "SELECT id_pagina FROM paginas WHERE titulo_pagina = '".trim($nombre)."' ";            
		$query = new Consulta($sql);
		$row = $query->VerRegistro();
		return $row['id_pagina'];         
	}
	
		
	public function getPaginas(){
		$query = new Consulta("SELECT * FROM paginas ORDER BY orden_pagina ASC");		
		while($row = $query->VerRegistro()){
			$datos[] = array(
				'id' 			=> $row['id_pagina'],
				'nombre' 		=> $row['titulo_pagina'],
				'descripcion' 	=> $row['descripcion_pagina']
			);
		}
		return $datos;	
	} 
	
}
?>