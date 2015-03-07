<?php 
class Configuration{
	
	var $data = array();
	private $_msgbox;
	private $_usuario;
	
	public function __construct($msg='', Usuario $user)
	{
		$this->_msgbox = $msg;
		$this->_usuario = $user;
	}

	function editConfiguration(){ ?>
		
		<fieldset id="form" > 
			<legend class="titulo"> Editar Configuración  </legend> 
			<form name="f1" action="<?php echo $_SERVER['PHP_SELF']."?action=update"?>" method="post">
            <div class="button-actions">
                 <input type="reset" name="limpia" value="LIMPIAR" class="button" />  
                 <input type="submit" name="guarda" value="GUARDAR" class="button" />
            </div>
            <ul>
			<?php
			foreach($this->getData() as $clave => $valor){ ?> 
				 <li>
                 <label style="width:140px"> <?php echo str_replace("_"," ",$clave); ?> : </label> 
                 <?php if(($clave=="CUENTA_BANCARIA")or($clave=="CONDICIONES_TERMINOS")){ ?>
                  <textarea  name="<?php echo $clave; ?>" class="text ui-widget-content ui-corner-all tinymce" rows="10" cols="61"> <?php echo $valor; ?></textarea>
                  <?php }else{ ?>
				 <input type="text" name="<?php echo $clave; ?>" class="text ui-widget-content ui-corner-all" size="59" value="<?php echo $valor; ?>"/>
                 <?php } ?>
                 </li><?php				
			} ?>
			</ul>      
			</form>
		</fieldset>		
		<?php				
	}
	
	function updateConfiguration(){
		
		foreach($_POST as $nombre => $valor){
			$sql = "UPDATE configuracion SET valor_configuracion = '".$valor."' WHERE nombre_configuracion = '".$nombre."' ";	
			$query = new Consulta($sql);
		}	
		
		$this->_msgbox->setMsgbox('Actualizado correctamente',2);
		location("configuracion.php");	
		
	}
	
	function listConfiguration(){
		$sql = "SELECT * FROM configuracion  ";
		$query = new Consulta($sql);				
		echo Listado::VerListado($query, "configuracion.php","","",$this->_usuario);	
	}
	
	function getData(){
		
		$sql   = "SELECT * FROM configuracion ";
		$query = new Consulta($sql);
		
		while($row = $query->VerRegistro()){
			$this->data[$row['nombre_configuracion']] = $row['valor_configuracion'];			
		}
		
		return $this->data;		
	}
}

?>