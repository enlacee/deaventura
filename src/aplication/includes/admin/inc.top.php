<?php
if ($sesion->getUsuario()->getLogeado() === TRUE) {
    $secciones = $sesion->getUsuario()->getSeccionesId();
    $modulos = explode(",", $sesion->getUsuario()->getModulos());
    sort($modulos);
} else {
    $secciones = array($modulos);
}
?>
<ul class="dropdown">
    <?php
    if (is_array($modulos)) {
        $seccions = new Secciones();
        reset($modulos);
        $index = 1;
        foreach ($modulos as $key => $value) {
            $modulo = new Modulo($value);
            ?>
            <li>
                <a href="javascript:;"> <?php echo $modulo->getNombre(); ?> </a>
                <?php
                $sections = $seccions->getSeccionesPorModulo($modulo->getId());
                $total = count($sections);
                if ($total > 0) {
                    ?>
                    <ul>
                        <?php
                        for ($s = 0; $s < $total; $s++) {
                            if (in_array($sections[$s]['id'], $secciones)) {
                                $explode = explode("/", $_SERVER['PHP_SELF']);
                                $self = end($explode);

                                if (preg_match("/reporte.php/", $sections[$s]['url'])) {
                                    ?>
                                    <li><a href="<?php echo $sections[$s]['url'] ?>">  <?php echo $sections[$s]['nombre'] ?> </a></li>
                                    <?php
                                } else {

                                    if (preg_match("/".$self."/", $sections[$s]['url'])) {
                                        ?>
                                        <li><span><?php echo $sections[$s]['nombre'] ?></span></li> <?php } else {
                                        ?>
                                        <li><a href="<?php echo $sections[$s]['url'] ?>">  <?php echo $sections[$s]['nombre'] ?> </a></li>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </ul>			
                    <?php
                }
                ?></li>
            <?php
        }
    }
    ?>
    <li class="last"></li>
</ul>
<div class="actions">
    <a href="index.php" class="user">Administrador - <?php echo $sesion->getUsuario()->getNombre() ?></a>
    <a href="<?php echo $_SERVER['PHP_SELF'] . "?action=logout" ?>" class="salir">Salir</a>
</div>
<div class="develoweb">
    <a href="http://www.develoweb.net" target="_blank"><img src="../aplication/webroot/imgs/admin/develoweb.png" /></a>
</div>