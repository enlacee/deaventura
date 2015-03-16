

<div class="red">
    <div class="titulo-for">
        <h2><span class="glyphicon glyphicon-user ico-account"></span> MI PERFIL</h2>
    </div>


    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Datos personales</a></li>
            <li><a href="#tabs-2">Deportes</a></li>
            <li><a href="#tabs-3">Foto</a></li>
        </ul>
        
        <!-- TAB General-->
<form class="form-horizontal" 
      action="<?php echo _url_ ?>cuenta.php?cuenta=misdatos" 
      method="post" enctype="multipart/form-data" 
      accept-charset="utf-8" 
      name="form_datos" 
      id="form_datos" 
      onsubmit="return validateCuentaMisDatosTab1(this)">
    <input type="hidden" value="update" name="action">        
        <div id="tabs-1">

                
                <div class="titulo-cuadrado">
                    <p class="titulo">Actualiza tus datos aquí</p>
                    <p class="mini-letra-color">(Los datos marcos con * son obligatorios)</p>
                </div>                
                
                <fieldset class=" bloque1">
                    
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
                                <label for="birthday">Fecha de Nacimiento: <span class="mini-letra-color">(opcional)</span></label>
                                <input type="text" id="birthday" name="birthday" class="form-control" placeholder=""
                                    value="<?php echo ($row['fecha_nacimiento_cliente'] != 'f') ? $row['fecha_nacimiento_cliente'] : '' ?>">
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group col-md-12'>
                                <label for="direccion">Vivo en:<span class="mini-letra-color">(opcional)</span></label>
                                <input class="form-control" id="direccion" name="direccion"  placeholder="Ej: Huaraz, Ancash"
                                       value="<?php echo $row['direccion'] ?>"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group col-md-6'>
                                <label for="telefono">Telefono: <span class="mini-letra-color">(opcional)</span></label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder=""
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
                
              
        </div>
        
        <!-- TAB SPORT-->
        <div id="tabs-2">
                
                <fieldset class=" bloque1">
                    <div class='form-group '>
                        <div class="col-md-12">
                            <label for="deporteDesde">Practico Deportes de Aventura desde el Año:<span class="mini-letra-color">(opcional)</span></label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="deporteDesde" id="deporteDesde">
                                <option value="">Elegir Año</option>
                                <option value="2015" <?php echo ($row['deporte_desde'] == '2015') ? 'selected="selected"' : '' ?>>2015</option>
                                <option value="2014" <?php echo ($row['deporte_desde'] == '2014') ? 'selected="selected"' : '' ?>>2014</option>
                                <option value="2013" <?php echo ($row['deporte_desde'] == '2013') ? 'selected="selected"' : '' ?>>2013</option>
                                <option value="2012" <?php echo ($row['deporte_desde'] == '2012') ? 'selected="selected"' : '' ?>>2012</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                
                
                <fieldset class=" bloque1">
                    <div class='form-group '>
                        <div class="col-md-12">
                            <label for="deporte">Mis Deportes de Aventura favoritos son:<span class="mini-letra-color">(opcional)</span></label>
                        </div>
                        
                        <div class="col-md-12">
                            <?php if (is_array($listSports) && count($listSports) > 0 ) : //var_dump($listSports);exit; ?>
                                <?php foreach ($listSports as $key => $value) : ?>
                                <span class="chk-item-deport">
                                    <input type="checkbox" name="deporte" value="<?php echo $listSports[$key]['id_deporte'] ?>"
                                           ><?php echo $listSports[$key]['nombre_deporte'] ?>
                                </span>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>
                
                <div class="text-center">
                    <input type="submit" class="btn-lg btn-primary" value="Guardar Cambios"/>
                </div>
            
        </div>
        
</form>        
        <!-- TAB IMAGE-->
        <div id="tabs-3">
            
            <div class="titulo-cuadrado">
                <p class="titulo">Tu Foto</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <img src="image.jpg" class="img-responsive"/>
                </div>
                <div class="col-md-8">
                    <fieldset class="bloque1" >
                    
                        <h2>Sube una foto aquí:</h2>
                        
                        
                        <div class="text-center">
                            <input type="button" class="btn-lg" value="Subir Foto" />
                        </div>
                    </fieldset>
                    
                </div>
                
            </div>
            
            
            
            
        </div>
        
    </div>

</div>

