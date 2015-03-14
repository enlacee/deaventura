

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
        <div id="tabs-1">
            <form class="form-horizontal">
                <fieldset class=" bloque1">
                    <div class="titulo-cuadrado">
                        <p class="titulo">Actualiza tus datos aquí</p>
                        <p class="mini-letra-color">(Los datos marcos con * son obligatorios)</p>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group col-md-12'>
                                <label for="name">Nombres:<span class="mini-letra-color">*</span></label>
                                <input class="form-control" id="name" name="name" placeholder="Nombre"/>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group col-md-12'>
                                <label for="lastname">Apellidos:<span class="mini-letra-color">*</span></label>
                                <input class="form-control" id="lastname" name="lastname"  placeholder="Apellidos"/>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group col-md-4'>
                                <label>Email: <span class="mini-letra-color">*</span></label>
                                <input type="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                    </div>
                </fieldset>
                  
                
                <fieldset class=" bloque1">
                    <div class="titulo-ckek"> Sexo: <span class="mini-letra-color">(opcional)</span></div>
                    
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Masculino
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Femenino
                    </label>
                    
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='form-group col-md-12'>
                                <label for="birthday">Fecha de Nacimiento: <span class="mini-letra-for">(opcional)</span></label>
                                <input type="text" id="birthday" name="birthday" class="form-control" placeholder="Ej: Huaraz, Ancash">
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group col-md-12'>
                                <label for="address">Vivo en:<span class="mini-letra-color">(opcional)</span></label>
                                <input class="form-control" id="address" name="address"  placeholder="Ej: Huaraz, Ancash"/>
                            </div>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class='form-group col-md-6'>
                                <label>Telefono: <span class="mini-letra-color">(opcional)</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="">
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
        
        <!-- TAB SPORT-->
        <div id="tabs-2">
            <form class="form-horizontal">
                <fieldset class=" bloque1">
                    <div class='form-group '>
                        <div class="col-md-12">
                            <label for="name">Practico Deportes de Aventura desde el Año:<span class="mini-letra-color">(opcional)</span></label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="sportAt" id="sportAt">
                                <option>2015</option>
                                <option>2014</option>
                                <option>2013</option>
                                <option>2012</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                
                
                <fieldset class=" bloque1">
                    <div class='form-group '>
                        <div class="col-md-12">
                            <label for="name">Mis Deportes de Aventura favoritos son:<span class="mini-letra-color">(opcional)</span></label>
                        </div>
                        
                        <div class="col-md-12">
                            <span class="chk-item-deport"><input type="checkbox" id="inlineCheckbox1" value="1">4x4</span>
                            <span class="chk-item-deport"><input type="checkbox" id="inlineCheckbox1" value="2">Andinismo</span>
                            <span class="chk-item-deport"><input type="checkbox" id="inlineCheckbox1" value="3">Buceo</span>
                            <span class="chk-item-deport"><input type="checkbox" id="inlineCheckbox1" value="4">Canotaje</span>
                        </div>
                    </div>
                </fieldset>
                
                <div class="text-center">
                    <input type="submit" class="btn-lg btn-primary" value="Guardar Cambios"/>
                </div>
            </form>
        </div>
        
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

