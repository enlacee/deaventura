<?php 
abstract class Main{
	
	var $_notificacion = "";
			
	function __construct(){}
	
	function setNotificacion($var="", $type=0){
		switch($type){
			case 1:
				$this->_notificacion = '<div class="error">'.$var.'</div>';
			break;
			case 2:
				$this->_notificacion = '<div class="success">'.$var.'</div>';
			break;
			default:
				$this->_notificacion = '';
			break;
			
		}
	}
	
 	public function printNotificacion(){			
		return $this->_notificacion;
	}
	
	public function getNotification(){
		return $this->_notificacion;
	}
	
}
?>