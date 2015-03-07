<?php

 if(basename($_SERVER['PHP_SELF'])=="conexion.php")exit;

class Conexion{
var $link	= 	0;
var $host ;	
var $user ;
var $psw  ;
var $db   ;


	function Conexion($host, $user, $psw, $db){
		$this->host=	$host;
		$this->user=	$user;
		$this->psw	=	$psw;
		$this->db	=	$db;	
	
		$this->link = @mysql_connect($this->host,$this->user,$this->psw) or die(mysql_error() ."error al conectarse al servidor");
                
		if($db==""){
			@mysql_select_db($this->db,$this->link);
                        @mysql_query($this->link, "SET NAMES 'utf8' ");
		}else{
			@mysql_select_db($db,$this->link);
                        @mysql_query($this->link, "SET NAMES 'utf8' ");
		}
		
		return $this->link;
	}
}

?>
