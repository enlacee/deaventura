
<?php
function active($id) {
    if (isset($_GET["cuenta"])) {
        if ($_GET["cuenta"] == $id) {
            return ' class="active"';
        }
    }
}
?> 
<div id="header" class="usu_header">
    <div id="top_login">
        <span id="welcome_b">Bienvenido, <a title="Ir a mi cuenta" href="cuenta.php?cuenta=misdatos"><?php echo $cuenta->__get("_cliente")->__get("_nombre") . ' ' . $cuenta->__get("_cliente")->__get("_apellidos") ?></a></span>
        <a title="Cerrar Sesión" id="logout" href="<?php echo _url_ ?>cuenta.php?cuenta=cerrar"> <img src="<?php echo _url_ ?>aplication/webroot/imgs/cross.png" width="12" height="12"> Salir</a>
    </div>
    <div id="header_b">
        <a href="#" id="scroll"></a>
        <div id="header_color_usu"></div>
        <a href="index.php" id="logo"></a>
        <div id="menu_usu">
            <ul>
                <li <?php echo active('misdatos') ?>><a href="cuenta.php?cuenta=misdatos"><span><img src="aplication/webroot/imgs/icon_person.png" width="11" height="12">Mis datos</span></a></li>
                <li <?php echo active('compartir') ?>><a href="cuenta.php?cuenta=compartir"><span><img src="aplication/webroot/imgs/icon_star1.png" width="12" height="12">Comparte tu aventura</span></a></li>
                <li <?php echo active('misAventuras') ?>><a href="cuenta.php?cuenta=misAventuras"><span><img src="aplication/webroot/imgs/icon_adv.png" width="12" height="11">Mis Aventuras</span></a></li>
                <li <?php echo active('favoritos') ?>><a href="cuenta.php?cuenta=favoritos"><span><img src="aplication/webroot/imgs/icon_star1.png" width="12" height="12">Favoritos</span></a></li>
                <?php if($cuenta->__get("_cliente")->__get("_tipo_usuario")=='1'){?>
                <li <?php echo active('miseventos') ?>><a href="cuenta.php?cuenta=miseventos"><span><img src="aplication/webroot/imgs/icon_cal.png" width="12" height="12">Mis Eventos</span></a></li>
                <?php
				}?>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
</div>