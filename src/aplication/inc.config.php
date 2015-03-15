<?php

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
                                  getenv('APPLICATION_ENV') : 
                                  'production'));

$i = 0;
if ($i == 1) {
    $_config['server']['url'] = "http://www.deaventura.pe/";
    $_config['server']['host'] = $_SERVER['DOCUMENT_ROOT'] . "/";
    $_config['bd']['server'] = "localhost";
    $_config['bd']['name'] = "localhost";
    $_config['bd']['user'] = "root";
    $_config['bd']['password'] = "root";
} else {
    $_config['server']['url'] = "http://deaventura.local/";   //	Ruta de la carpeta del Proyecto
    $_config['server']['host'] = $_SERVER['DOCUMENT_ROOT'] . "/"; //	Ruta de la carpeta y URL del Host
    $_config['bd']['server'] = "localhost";                                        //	Nombre del Host
    $_config['bd']['name'] = "nuevode";     //	Nombre de la BD
    $_config['bd']['user'] = "root";      //	Nombre del Usuario de la BD
    $_config['bd']['password'] = "123456";
}