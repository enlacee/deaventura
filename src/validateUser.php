<?php

include("inc.aplication_top.php");

if (isset($_POST["connect"]) && $_POST["connect"] == 1) {
    
    $return_url = 'http://deaventura.develoweb.pe/';  //path to script folder
    //Call Facebook API
    //require 'aplication/utilities/sdk-facebook/src/facebook.php';
    $facebook = new Facebook(array(
                'appId' => '244715988912141',
                'secret' => '77ffafc45dad5ca6cc1591a0cc867dc7',
                'cookie' => true
            ));
    

    $fbuser = $facebook->getUser();
    if ($fbuser) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $me = $facebook->api('/me'); //user
            $uid = $facebook->getUser();
        } catch (FacebookApiException $e) {
            //echo error_log($e);
            $fbuser = null;
        }
    }

    // redirect user to facebook login page if empty data or fresh login requires
    if (!$fbuser) {
        $loginUrl = $facebook->getLoginUrl(array('redirect_uri' => $return_url, false));
        header('Location: ' . $loginUrl);
    }
            
    //user details
    $name = $me['first_name'];
    $lastname = $me['last_name'];
    $email = $me['email'];
    $sexo = ($me['gender'] == 'male') ? 'M' : 'F' ;
    $link = $me['link'];
    $fecha_nacimiento = $me['birthday'];
        
    
    //$uid = '1031113918';
    $resukt = $cuenta->validarUsuario($uid);
    if ($resukt > 0) {
        //El usuario existe
        echo 'Bienvenido, ' . $me['first_name'] . ' ' . $me['last_name'] . '';
        $cuenta->cuentaAcceso($uid);
        
    } else {
        //User is nuevo y lo ingreso a la bd
        echo 'Bienvenido, ' . $me['first_name'] . ' ' . $me['last_name'] . '';
        $cuenta->cuentaAdd($uid, $name, $lastname, $sexo, $email, $link, $fecha_nacimiento);
    }
    
    $cuenta->__set("_facebook",$facebook);
}
