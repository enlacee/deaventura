/***
*
* 03 : Script for load button jquer upload
*/


$(function () {
    // 01 : load image with efeect load
    $('#myCuentaFileupload').fileupload({
        
        dataType: 'json',
        singleFileUploads : true,
        limitMultiFileUploads : 1,
        add: function (e, data) {
            
            // jqXHR
            // data.files[0].name;
            $('#myCuentaImage').attr('src', '');
            
            //data.submit();
            var jqXHR = data.submit()
                .success(function (result, textStatus, jqXHR) {
                    var obj = result.files[0];
                    $('#myCuentaImage').attr('src', obj.url);
                    $('#myCuentaFilePathServer').val(obj.url);
                })
                .error(function (jqXHR, textStatus, errorThrown) {/* ... */})
                .complete(function (result, textStatus, jqXHR) {/* ... */});
          
        },
        done: function (e, data) {
            console.log('done', data);
        },
        always : function(e, data) {
           console.log('always', data); 
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    
    // 02 :  

});




// #############
// Functions

/**
 * Validation of form 'Account datos generales'
 * @returns void validate messages
 */
function validateCuentaMisDatosTab1(form) {
    
    //console.log('form',form);
    //console.log('form', form.deporte);
   
    
    
    var name = form.name.value;
    var lastname = form.lastname.value;
    var email = form.email.value;

    var nameRegex = /^[a-zA-Zñáéíóú]+(([\'\,\.\- ][a-zA-Z ])?[a-zA-Z]*)*$/;
    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    var messageRegex = new RegExp(/<\/?\w+((\s+\w+(\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>/gim);
    var numRegex = new RegExp(/^(?:\+|-)?\d+$/);

    if (name == "") {
        inlineMsg('name', 'Usted debe ingresar su nombre.', 2);
        return false;
    }
    if (!name.match(nameRegex)) {
        inlineMsg('name', 'Ha introducido un nombre no v&aacute;lido.', 2);
        return false;
    }

    if (lastname == "") {
        inlineMsg('lastname', 'Usted debe ingresar sus apellidos.', 2);
        return false;
    }
    if (!lastname.match(nameRegex)) {
        inlineMsg('lastname', 'Ha introducido un nombre no v&aacute;lido.', 2);
        return false;
    }

    if (email == "") {
        inlineMsg('email', '<strong>Error</strong><br />Usted debe ingresar su correo electr&oacute;nico.', 2);
        return false;
    }
    if (!email.match(emailRegex)) {
        inlineMsg('email', '<strong>Error</strong><br />Ha introducido una direcci&oacute;n de correo electr&oacute;nico no v&aacute;lida.', 2);
        return false;
    }
    
    if (form.fecha_nacimiento_cliente.value != '') {
        var patternBd =/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/;
        if (!patternBd.test(form.fecha_nacimiento_cliente.value)) {
            inlineMsg('birthday', 'Formato fecha de Nacimiento incorrecto [dia-mes-año]', 4);
            return false;
        }

    }
    
    if (form.telefono.value != '') {
        if (form.telefono.value.length != 9) {
            inlineMsg('telefono', 'Debe ingresar un telefono de 9 digitos', 2);
            return false;     
        }
    }
    
    // validation checkbox deportes
    
}

function validateCuentaMisDatosTab2(form) {
    /*var form = form.parentNode.parentNode;
    console.log('form',form.parentNode.parentNode);
    **/

 //console.log(form.elements["deporte_favorito[]"]);
 var data = form.elements["deporte_favorito[]"]; 
 var contador = 0;
 for (var i = 0; i<data.length; i++) {
     if (data[i].checked) {contador++;}
 }
 
 if (contador > 2) {
     inlineMsg('mi_deporte_favorito', 'Solo se permite seleccionar 2 Deportes', 4);
    return false;     
 }

    
}


// #############
// Initialize

// block leeter and number for date (fecha nacimiento)
    document.querySelector(".disabledKeyDate").addEventListener("keypress", function (evt) {
        if (evt.which < 48 && evt.which!=45 || evt.which > 57){
            evt.preventDefault();
        }
    }); 
