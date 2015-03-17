
<div role="tabpanel" class="red">

    <div class="titulo-for">
        <h2><span class="glyphicon glyphicon-user ico-account"></span> MI PERFIL</h2>
    </div>    
    
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#one">Datos personales</a></li>
        <li><a data-toggle="tab" href="#two">Deportes</a></li>
        <li><a data-toggle="tab" href="#three">Foto</a></li>
    </ul>

  <!-- Tab panes -->
    <div class="tab-content">
        
        <div role="tabpanel" class="tab-pane active" id="one">

        <form class="form-horizontal" 
          action="<?php echo _url_ ?>cuenta.php?cuenta=misdatos" 
          method="post" enctype="multipart/form-data" 
          accept-charset="utf-8" 
          name="form_datos" 
          id="form_datos" 
          onsubmit="return validateCuentaMisDatosTab1(this)">
        <input type="hidden" value="update-tab1" name="action">
        
            <div class="titulo-cuadrado">
                <p class="titulo">Actualiza tus datos aquí</p>
                <p class="mini-letra-color">(Los datos marcos con * son obligatorios)</p>
            </div>                

            <fieldset class=" bloque1">

                <div class='row'>
                   <div class='col-md-12'>
                        <div class='form-group col-md-12'>
                            <label for="name">Descríbete:<span class="mini-letra-color">*</span></label>
                            <textarea name="describete" class="form-control required" rows="2" aria-required="true"><?php echo $row['describete'] ?></textarea>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-4'>
                        <div class='form-group col-md-12'>
                            <label for="name">Nombres:<span class="mini-letra-color">*</span></label>
                            <input class="form-control" id="name" name="name" placeholder="Nombre"
                                   value="<?php echo $row['nombre_cliente'] ?>"/>
                        </div>
                    </div>
                    <div class='col-md-4'>
                        <div class='form-group col-md-12'>
                            <label for="lastname">Apellidos:<span class="mini-letra-color">*</span></label>
                            <input class="form-control" id="lastname" name="lastname"  placeholder="Apellidos"
                                value="<?php echo $row['apellidos_cliente'] ?>"/>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class='form-group col-md-4'>
                            <label>Email: <span class="mini-letra-color">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off"
                                value="<?php echo $row['email_cliente'] ?>">
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class=" bloque1">
                <label>Sexo: <span class="mini-letra-color">(opcional)</span></label><br />
                <label class="radio-inline">
                    <input type="radio" name="sexo" value="M"
                        <?php echo ($row['sexo_cliente'] == 'M') ? 'checked="checked"': '' ?>> Masculino
                </label>
                <label class="radio-inline">
                    <input type="radio" name="sexo" value="F" 
                        <?php echo ($row['sexo_cliente'] == 'F') ? 'checked="checked"': '' ?> > Femenino
                </label>

                <div class='row'>
                    <div class='col-md-4'>
                        <div class='form-group col-md-12'>
                            <label for="fecha_nacimiento_cliente">Fecha de Nacimiento: <span class="mini-letra-color">(opcional)</span></label>
                            <input type="text" id="birthday" name="fecha_nacimiento_cliente" class="form-control disabledKeyDate" placeholder="17-12-1990"
                                value="<?php echo ($row['fecha_nacimiento_cliente'] != 'f') ? $row['fecha_nacimiento_cliente'] : '' ?>">
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group col-md-12'>
                            <label for="vivo_en">Vivo en:<span class="mini-letra-color">(opcional)</span></label>
                            <input class="form-control" id="vivo_en" name="vivo_en"  placeholder="Ej: Huaraz, Ancash"
                                   value="<?php echo $row['vivo_en'] ?>"/>
                        </div>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class='form-group col-md-6'>
                            <label for="telefono">Telefono: <span class="mini-letra-color">(opcional)</span></label>
                            <input type="number" class="form-control" id="telefono" name="telefono" placeholder=""
                                value="<?php echo $row['telefono'] ?>">
                        </div>

                        <div class='form-group col-md-6'>
                            <br>
                            <br>
                            <span class="col-md-12">No se mostrará tu teléfono en tu perfil</span>
                        </div>


                    </div>
                </div>
            </fieldset>

            <div class="text-center">
                <input type="submit" class="btn-lg btn-primary" value="Guardar Cambios"/>
            </div>
            
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="two">
            <fieldset class=" bloque1">
                <div class='form-group '>
                    <div class="col-md-12">
                        <label for="deporte_desde">Practico Deportes de Aventura desde el Año:<span class="mini-letra-color">(opcional)</span></label>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" name="deporte_desde" id="deporte_desde">
                            <option value="">Elegir Año</option>
                            <option value="2015" <?php echo ($row['deporte_desde'] == '2015') ? 'selected="selected"' : '' ?>>2015</option>
                            <option value="2014" <?php echo ($row['deporte_desde'] == '2014') ? 'selected="selected"' : '' ?>>2014</option>
                            <option value="2013" <?php echo ($row['deporte_desde'] == '2013') ? 'selected="selected"' : '' ?>>2013</option>
                            <option value="2012" <?php echo ($row['deporte_desde'] == '2012') ? 'selected="selected"' : '' ?>>2012</option>
                            <option value="2011" <?php echo ($row['deporte_desde'] == '2011') ? 'selected="selected"' : '' ?>>2011</option>
                            <option value="2010" <?php echo ($row['deporte_desde'] == '2010') ? 'selected="selected"' : '' ?>>2010</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class=" bloque1">
                <div class='form-group '>
                    <div class="col-md-12">
                        <label for="deporte_favorito">Mis Deportes de Aventura favoritos son:<span class="mini-letra-color">(opcional)</span></label>
                    </div>

                    <div class="col-md-12">
                        <?php if (is_array($listSports) && count($listSports) > 0 ) : ?>
                            <?php foreach ($listSports as $key => $value) : ?>
                            <span class="chk-item-deport">
                                <input type="checkbox" name="deporte_favorito[]" value="<?php echo $listSports[$key]['id_deporte'] ?>"><?php echo $listSports[$key]['nombre_deporte'] ?>
                            </span>
                            <?php endforeach;?>
                        <?php else: ?>
                            <span class="chk-item-deport">No existen deportes para mostrar.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class='form-group '>
                    <div class="col-md-12">
                        <label for="deporte_equipo_que_utilizo">Equipo que utilizo:<span class="mini-letra-color">(opcional)</span></label>
                        <input type="text" id="birthday" name="deporte_equipo_que_utilizo" id ="deporte_equipo_que_utilizo" class="form-control"
                            placeholder="Bicicleta  Montañera, Equipo de Escalar" value="<?php echo $row['deporte_equipo_que_utilizo'] ?>">
                    </div>
                </div>
            </fieldset>

            <div class="text-center">
                <input type="submit" class="btn-lg btn-primary" value="Guardar Cambios"/>
            </div>
        </div>
        
        <div role="tabpanel" class="tab-pane" id="three">
            <div class="titulo-cuadrado">
                <p class="titulo">Tu Foto</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <img id='myCuentaImage' src="https://graph.facebook.com/<?php echo $this->_cliente->__get("_idFacebook") ?>/picture?type=large" width="200px" height="200PX">
                    <br /><br />
                </div>
                <div class="col-md-8">                    
                    <fieldset class="bloque1" >
                        <h2>Sube una foto aquí:</h2><br />
                        <div class="text-center">                            
                            <span class="btn  btn-lg fileinput-button">
                                <span>Subir Foto</span>
                                <!-- The file input field used as target for the file upload widget -->
                                <input id="myCuentaFileupload" type="file" name="files[]" data-url="<?php _url_?>aplication/utilities/fileUpload/server/" multiple>
                                <input id="myCuentaFilePathServer" type="hidden" value=""/>
                            </span>
                            <br>
                            <br>
                            <!-- The global progress bar -->
                            <div id="progress" class="progress">
                                <div class="progress-bar progress-bar-success"></div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="text-center">
                        <input type="submit" class="btn-lg btn-primary" value="Guardar Cambios"/>
                </div>                    
            </div>
        </div>
        </div>
    
    </div>

</div>
