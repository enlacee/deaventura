<?php
include("inc.aplication_top.php");

if ($_GET['action'] == 'acceso') {
    if ($sesion->enviarContrasena()) {
        header("Location:login.php?msg=success");
    } else {
        header("Location:login.php?action=recuperar_c&msg=error");
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>ADMINISTRACION <?php echo NOMBRE_SITIO; ?></title> 
        <link rel="stylesheet" type="text/css" href="../aplication/webroot/css/admin/login.css">
    </head>
    <body id="login" style="background: url('../aplication/webroot/imgs/admin/bg_admin.jpg') repeat-x #f8dc9e;">

        <div id="sitewrapper">
            <div id="header"><a id="logo" href="http://www.develoweb.net/">Tick &raquo; Track time - Hit budgets</a></div>
            <!-- /header -->
            <div id="content" >
                <div id="main">
                    <?php
                    if ($_GET['action'] == 'recuperar_c') {
                        ?>
                        <div id="forgot-password-flash" class="flash bad" style="display:none;">
                            <div class="inner">
                                <h3>Did you forget your password?</h3>
                                Shame shame shame... Not really - It's actually no problem at all. Just enter your email in the box below and we'll send it right along.                    </div>
                        </div>
                        <?php if ($_GET['msg']) { ?>
                            <span id="errors">El correo electronico  no existe en el sistema.</span>
    <?php } ?>
                        <h1>Recuperar Contraseña</h1>
                        <form name="login" action="<?php echo $_SERVER['PHP_SELF'] ?>?action=acceso" method="post">
                            <div id="email">
                                <fieldset id="user-email">
                                    <p>
                                        <label>E-mail:<span><input type="text" name="login" tabindex="1" /></span></label><br />
                                        <input type="image" src="<?php echo _icons_ ?>enviar.jpg" style="float:left; margin-right:20px" /><label style="margin:20px 0 0 0"> <a href="login.php">Iniciar Sesión</a></label>
                                    </p>
                                </fieldset>
                            </div>


                        </form>  
                        <?php
                    } else {
                        ?>
                        <div id="forgot-password-flash" class="flash bad" style="display:none;">
                            <div class="inner">
                                <h3>Did you forget your password?</h3>
                                Shame shame shame... Not really - It's actually no problem at all. Just enter your email in the box below and we'll send it right along.                    </div>
                        </div>
                        <?php if ($_GET['msg']) { ?>
                            <span id="exito">Los datos de acceso a su cuenta senviaron a su email.</span>
    <?php } ?>
                        <h1>Acceso Administrador</h1>
                        <form name="login" action="index.php" method="post">
                            <div id="email">
                                <fieldset id="user-email">
                                    <p>
                                        <label>Usuario:<span><input type="text" name="login" value="<?php echo $_COOKIE['email_MKD'] ?>" tabindex="1" /></span></label>
                                    </p>
                                </fieldset>
                            </div>
                            <div id="password">
                                <fieldset id="user-password">
                                    <p>
                                        <strong>Oops - <a href="login.php?action=recuperar_c">Olvidé mi contraseña</a></strong>
                                        <label>Password:<span><input type="password"  value="<?php echo $_COOKIE['pass_MKD'] ?>"  name="password" tabindex="2" /></span></label>
                                    </p>
                                </fieldset>
                                <p>
                                    <label id="remember-password">
                                        <input  name="recordar_si_MKD" value="si" <?php if (isset($_COOKIE['email_MKD']) && isset($_COOKIE['pass_MKD'])) {
        echo 'checked="checked"';
    } ?> type="checkbox"  />
                                        Recordarme por 30 días</label>
                                    <input type="image" id="sign-in" name="enviar" src="<?php echo _icons_ ?>entrar.jpg" alt="Sign In" tabindex="3" />
                                </p>
                            </div>
                            <div id="forgot-password" style="display:none;">
                                <input name="commit" type="submit" value="Email me my password" />
                                <label>Just kidding <a href="#" onclick="remember(); return false;">Recordarme por 30 días</a></label>
                            </div>
                        </form>  

    <?php
}
?>

                </div><!-- /main -->
            </div>
            <!-- /content -->

        </div><!-- /sitewrapper -->
    </body>
</html>
