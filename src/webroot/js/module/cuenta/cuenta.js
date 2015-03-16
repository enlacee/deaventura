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
            var sysPath = 'aplication/webroot/imgs/catalogo/aventuras_img_usuarios/' + data.files[0].name;
            var pathImage = URLS.siteUrl + sysPath;
            $('#myCuentaFilePathServer').val(sysPath);
            $('#myCuentaImage').attr('src', pathImage); 
            data.submit();
          
        },
        done: function (e, data) {
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
    
    if (false /*form.fecha_nacimiento_cliente.value == ''*/) {
        inlineMsg('birthday', 'Debe ingresar una fecha de cumpleaños valido', 2);
        return false;
    }

    if (false/*form.direccion.value == ''*/) {
        inlineMsg('birthday', 'Debe ingresar su dirección', 2);
        return false;
    }
    
    if (false/*form.telefono.value == ''*/) {
        inlineMsg('telefono', 'Debe ingresar telefono', 2);
        return false;
    }
    
    // validation checkbox deportes
    
}

function validateCuentaMisDatosTab2(form) {
    
}
