<header>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo _url_; ?>">
                  <img alt="Brand" src="<?php echo _url_; ?>aplication/webroot/imgs/logo_deaventura.png" id="logo-deaventura">
              </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Comunidad <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu"  class="active">
                    <li><a href="aventuras">Aventuras</a></li>
                    <li><a href="inspiraciones">Inspiracion</a></li>
                    <li><a href="articulos">Productos</a></li>
                  </ul>
              </li>
                  <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Deportes <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu"  class="active">
                    <li><a href="Trekking">Trekking</a></li>
                    <li><a href="Ciclismo">Ciclismo</a></li>
                    <li><a href="Canotaje">Canotaje</a></li>
                    <li><a href="Escalada">Escalada</a></li>
                    <li><a href="Parapente">Parapente</a></li>
                    <li><a href="Puenting">Puenting</a></li>
                    <li><a href="Surf">Surf</a></li>
                    <li><a href="Sandboard">Sandboard</a></li>
                    <li><a href="4x4">Todo Terreno</a></li>
                    <li><a href="otros-deportes">+ Deportes</a></li>
                  </ul>
              </li>
              <li><a href="eventos">Eventos</a></li>
<!--          <li><a href="aventuras">Aventuras</a></li>
              <li><a href="eventos">Destinos</a></li>-->
              <li><a href="blog">Blog</a></li>
              </ul>
<!-- 
             <form class="navbar-form navbar-left" role="search" name="fbuscar">
                <div class="form-group">
                  <input type="text" class="form-control" id="busqueda" placeholder="">
                </div>
                <button type="submit" class="btn btn-default" id="btn_buscar">Buscar</button>
              </form>
              -->
              <ul class="nav navbar-nav navbar-right">
                <?php if ($cuenta->__get("_cliente")->__get("_logeado")) { ?>  
                 
                  <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $cuenta->__get("_cliente")->__get("_nombre"); ?><span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="cuenta.php?cuenta=compartir">Compartir Aventura</a></li>
                    <li class="divider"></li>  
                    <li><a href="cuenta.php?cuenta=misdatos">Mi Cuenta</a></li>
                    <li><a href="cuenta.php?cuenta=misAventuras">Mis Aventuras</a></li>
                    <li><a href="cuenta.php?cuenta=favoritos">Mis Favoritos</a></li>
                    <?php if($cuenta->__get("_cliente")->__get("_tipo_usuario")=='1'){?>
                        <li><a href="cuenta.php?cuenta=miseventos">Mis Eventos</a></li> <?php
                    }?>
                    <li class="divider"></li>
                    <li><a href="cuenta.php?cuenta=cerrar">Salir</a></li>
                  </ul>
                  
                </li>
                 <li>
                      <a href="cuenta.php"><span class="glyphicon glyphicon-home blue_menu_bg icon_menu_top" ></span></a>
                  </li>
                <?php } else { 
                        $evento_boton = _EVENTO_BOTON_;
                        if( $evento_boton == "SI" ){ 
                            $boton_top = "Comparte tu Evento"; 
                        }else{ 
                            $boton_top = "Comparte tu Aventura";
                        } ?>       
                    <li><a id="login" title="Logéate con tu Facebook" href="#" onclick="login();return false;"><img src="<?php echo _url_ ?>aplication/webroot/imgs/icon_user1.png" width="12" height="12">Entrar</a></li>
                    <li><a id="comparte" href="#" onclick="login();return false;" class="gradient"><span class="btn btn_compartir_aventura"> <?php echo $boton_top; ?></span></a></li>
                <?php } ?>  
              </ul>
              
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    
    
    
    
<!--    <div id="header-top">
        <h1>
            <a href="index.php?ref=logo">
                <img src="aplication/webroot/imgs/logo_deaventura.png" id="logo-deaventura" />
            </a>
        </h1> 
        <nav>
            
            <ul  class="flexnav">
                <li><a href="deportes">Deportes</a>
                    <ul>
                        <li><a href="">Canotaje</a></li>
                        <li><a href="">Ciclismo</a></li>
                        <li><a href="">Trekking</a></li>
                        <li><a href="">Puenting</a></li>
                        <li><a href="">Todo Terreno</a></li>
                    </ul>
                </li>
                <li><a href="aventuras">Aventuras</a></li>
                <li><a href="eventos">Agenda</a></li>
                <li><a href="tienda">Tienda</a></li>
                <li><a href="blog">Blog</a></li>
            </ul>
        </nav>
        <div id="buscar-bloque" style="display:inline-block; width:auto;border-left:1px solid #D7D7D7; margin-left:2%; width:20%;" >
            <img src="aplication/webroot/imgs/icons/boton-buscar.png" style="display:inline-block">
            <input type="text" name="buscar" id="buscar" style="width:70%;margin-left:5%;padding-left:6%;border:0;background: white;box-shadow:none;display:inline-block; margin:0" placeholder="Ejemplo: Canetaje en lunahuana" >
        </div>
        <div id="usuario-perfil">
            <div class="usuario-perfil-parrafo">Únete a la comunidad de aventureros</div>
            <div class="usuario-perfil-boton">Unirme Ahora</div>
            <a href="" >Walter</a>
        </div>
    </div>-->
</header>
