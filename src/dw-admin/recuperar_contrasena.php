<?php include("inc.aplication_top.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>RECUPERAR CONTRASE&Ntilde;A :: <?php echo NOMBRE_SITIO ?> :: </title>
        <link rel="stylesheet" type="text/css" href="aplication/webroot/css/admin.css">
            <script language="javascript" src="aplication/webroot/js/jquery.js"></script>
            <script type="text/javascript" src="aplication/webroot/js/admin.js"></script>
    </head>
    <body>
        <?php if (!isset($enviar)) { ?>
            <div class="texto_enviar">
                INGRESE SU EMAIL, EN BREVE LE ENVIAREMOS SU USUARIO Y PASSWORD :<br />
                <HR />
            </div>
            <div align="center" class="msn">
                <form name="frmc" action="recuperar_contrasena.php" method="post">
                    <input type="text" name="txtemail"  class="caja"/><input type="submit" name="enviar" id="enviar" value="Enviar"  class="boton"/>
                </form>
            </div>


        <?php
        } else {

            $admin = new Usuario();
            if ($admin->recuperar_contrasena() == TRUE) {
                ?>
                <div class="texto_enviar">
                    SISTEMA <br />
                    <HR />
                </div>
                <div align="center" class="msn">
                    Sus datos fueron enviados correctamente  a su email.
                    <form name="f11" >
                        <input type="button" name="cerrar" value="Cerrar"  onclick="javascript:window.close()" class="button"/>
                    </form>
                </div>
        <?php } else {
        ?>
                <div class="texto_enviar">
                    ERROR <br />
                    <HR />
                </div>
                <div align="center" class="msn">
                    El correo ingresado no existe. <form name="f11" >
                        <input type="button" name="cerrar" value="Cerrar"  onclick="javascript:window.close()" class="button"/>
                    </form>
                </div>

                <?php
            }
            ?>



<?php } ?>
        <div class="pies">Powered by<a href="http://www.develoweb.net" target="_blank"> Develoweb </a>&copy; 2008</div>
    </body>
</html><?php include("inc.aplication_bottom.php"); ?>
