<?php 
class Msgbox{
	
	private $_text, $_type;
	
	public function setMsgbox($text, $type){
		$this->_text = $text;
		$this->_type = $type;
	}
	
	public function getMsgbox(){
		
		switch($this->_type){
			case 1:
				$msg = '<p class="error notification">'.$this->_text.'</p>';
			break;
			case 2:
				$msg = '<p class="success notification">'.$this->_text.'</p>';
			break;
		}
		$this->clearMsgbox();
		return $msg;
	}
	
	public function clearMsgbox(){
		$this->_text = '';
		$this->_type = '';
	}	
}
?>