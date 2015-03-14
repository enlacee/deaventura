<?php 

/**
 * Clase principal donde definiremos variables staticas para reutilizar en los
 * modelos que generan vistas
 */
class MainModel 
{
    private $config;
    
    public function __construct() {
     

    }
    
    /*
     * get values of configuration inc.config.php
     */
    public function config() {
    
        // Load file of configuration
        $REAL_PATH = realpath(__DIR__ . '/../');
        
        if (is_file($REAL_PATH . '/inc.config.php')) {
            include $REAL_PATH . '/inc.config.php';
        }
        
        if (isset($_config) && is_array($_config)) {
            $this->config = new stdClass();
            foreach($_config as $key => $value) {
                
                if(is_array($value) ) {
                    $this->config->$key = new stdClass();
                    foreach ($value as $indice => $valor) {
                        $this->config->$key->$indice = $valor;
                    }
                }
            }
        }
        
        return $this->config;
    }
    
}