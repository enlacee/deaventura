// form validation function //
function validate(form, opcion) {
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


    document.form_login.action = 'cuenta.php?cuenta=' + opcion;
    document.form_login.submit();

    return false;
}


 
function validate_step1(form, opcion) {
    var cbo_deportes = form.cbo_deportes.value;
    var cbo_modalidad = form.cbo_modalidad.value;
    var titulo = form.titulo.value;
    var lugar = form.lugar.value;
    var descripcion = form.descripcion.value;


    var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    var message = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ,. \s\t\n\r]+$/;
    var numletter = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ]+$/;
    var numlettercoma = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ,]+$/;

    if (cbo_deportes === 0) {
        inlineMsg('cbo_deportes', 'Usted debe seleccionar el deporte de la aventura.', 2);
        return false;
    }

    if (cbo_modalidad === 0) {
        inlineMsg('cbo_modalidad', 'Usted debe seleccionar la modalidad de la aventura.', 2);
        return false;
    }

    if (titulo === "") {
        inlineMsg('titulo', 'Usted debe ingresar el titulo de la aventura.', 2);
        return false;
    }

    if (!titulo.match(numletter)) {
        inlineMsg('titulo', 'Solo se admiten números y letras', 2);
        return false;
    }

    if (lugar === "") {
        inlineMsg('lugar', 'Usted debe ingresar el lugar para la aventura.', 2);
        return false;
    }

    if (!lugar.match(numlettercoma)) {
        inlineMsg('lugar', 'Solo se admiten números, letras y comas (,)', 2);
        return false;
    }

    if ($("#table_imgs tr").length === 0) {
        alert("Debe ingresar por lo menos 1 imagen");
        return false;
    }
    
    // this button SUBMIT THIS FORM
    //$('#demo1').ajaxupload('start');
    $(".btn-primary.start").click();

     document.form_login.action = 'cuenta.php?cuenta='+opcion;
     document.form_login.submit();
     
    return false;
}


function validate_step2(form) {

    //var titulo_files = form.titulo_files[1].value;  
    var descripcion_files = form.descripcion_files.value;
    var message = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ,. \s\t\n\r]+$/;
    var numletter = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ]+$/;

    var cantidad_filas = form.cantidad_filas.value;  

    for (i=0; i<cantidad_filas; i++) {

        var cantidad="titulo_files_"+i;        
        var titulo_files = document.getElementById(cantidad).value;  
        //if (titulo_files === "") {
        if (!titulo_files.match(numletter) || titulo_files === "") {    
            inlineMsg(cantidad, 'Solo se admiten números y letras', 2);
            return false;
        }        
    }
	
	document.form.action = 'cuenta.php?cuenta="compartir" ';
    document.form.submit();
      
   
}

/*
function validate_step2(form) {
	
		
    var titulo_files = form.titulo_files.value;
    var descripcion_files = form.descripcion_files.value;
    var message = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ,. \s\t\n\r]+$/;
    var numletter = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ]+$/;

    if (!titulo_files.match(numletter) && titulo_files !== "") {
        inlineMsg('titulo_files', 'Solo se admiten números y letras', 2);
        return false;
    }

    if (!descripcion_files.match(message) && descripcion_files !== "") {
        inlineMsg('descripcion_files', 'Solo se admiten números, letras y separadores como comas (,) y puntos (.)', 2);
        return false;
    }
    
    $.post("ajax.php", {
        "step": "2"
    }, function(data){		
        document.forms[1].submit();		
    });
	

    return false;
	
}
*/


function validate_updateAv(form, opcion) { 

    var cbo_deportes = (typeof(form.cbo_deportes.value) === 'undefined') ? 0 : parseInt(form.cbo_deportes.value);
    var cbo_modalidad = (typeof(form.cbo_modalidad.value) === 'undefined') ? 0 : parseInt(form.cbo_modalidad.value);
    var titulo = form.titulo.value;
    var lugar = form.lugar.value;
    var descripcion = form.descripcion.value;

    var message = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ,.;: \s\t\n\r\-\(\)]+$/;
    var numletter = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ]+$/;
    var numlettercoma = /^[a-znÑA-Z0-9áéíóúÁÉÍÓÚ ,]+$/;
/*console.log('form.cbo_deportes.value', form.cbo_deportes.value);
console.log('cbo_deportes', cbo_deportes)
*/
    if (cbo_deportes === 0) {
        inlineMsg('cbo_deportes', 'Usted debe seleccionar el deporte de la aventura.', 2);
        
        return false;
    }

    if (cbo_modalidad === 0) {
        inlineMsg('cbo_modalidad', 'Usted debe seleccionar la modalidad de la aventura.', 2);
        return false;
    }

    if (titulo === "") {
        inlineMsg('titulo', 'Usted debe ingresar el titulo de la aventura.', 2);
        return false;
    }

    if (!titulo.match(numletter)) {
        inlineMsg('titulo', 'Solo se admiten números y letras', 2);
        return false;
    }

    if (lugar === "") {
        inlineMsg('lugar', 'Usted debe ingresar el lugar para la aventura.', 2);
        return false;
    }

    if (!lugar.match(numlettercoma)) {
        inlineMsg('lugar', 'Solo se admiten números, letras y comas (,)', 2);
        return false;
    }

    if (descripcion === "") {
        inlineMsg('descripcion', 'Usted debe ingresar la descripcion de la aventura.', 2);
        return false;
    }
    /*
    if (!descripcion.match(message)) {
        //inlineMsg('descripcion', 'Solo se admiten números, letras y separadores como comas (,) y puntos (.)', 2);
		inlineMsg('descripcion', 'Solo se admiten números, letras y separadores como: comas (,) , punto y coma (;) , dos puntos (:) , punto (.) , guion (-) , y parentesis () ', 2);
        return false;
    }
    */

    //validate_step2(form);
    
    document.form_update.action = 'cuenta.php?cuenta=' + opcion;
    document.form_update.submit();
    return false;
}





















function validate2(form, opcion) {
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

/*document.form_datos.action = '';
     document.form_datos.submit();
     return false;*/
}


// START OF MESSAGE SCRIPT //

var MSGTIMER = 20;
var MSGSPEED = 5;
var MSGOFFSET = 3;
var MSGHIDE = 3;

// build out the divs, set attributes and call the fade function //
function inlineMsg(target, string, autohide) {
    var msg;
    var msgcontent;
    if (!document.getElementById('msg')) {
        msg = document.createElement('div');
        msg.id = 'msg';
        msgcontent = document.createElement('div');
        msgcontent.id = 'msgcontent';
        document.body.appendChild(msg);
        msg.appendChild(msgcontent);
        msg.style.filter = 'alpha(opacity=0)';
        msg.style.opacity = 0;
        msg.alpha = 0;
    } else {
        msg = document.getElementById('msg');
        msgcontent = document.getElementById('msgcontent');
    }
    msgcontent.innerHTML = string;
    msg.style.display = 'block';
    var msgheight = msg.offsetHeight;
    var targetdiv = document.getElementById(target);
    targetdiv.focus();
    var targetheight = targetdiv.offsetHeight;
    var targetwidth = targetdiv.offsetWidth;
    var topposition = topPosition(targetdiv) - ((msgheight - targetheight) / 2);
    var leftposition = leftPosition(targetdiv) + targetwidth + MSGOFFSET;
    msg.style.top = topposition + 'px';
    msg.style.left = leftposition + 'px';
    clearInterval(msg.timer);
    msg.timer = setInterval("fadeMsg(1)", MSGTIMER);
    if (!autohide) {
        autohide = MSGHIDE;
    }
    window.setTimeout("hideMsg()", (autohide * 1000));
}

// hide the form alert //
function hideMsg(msg) {
    var msg = document.getElementById('msg');
    if (!msg.timer) {
        msg.timer = setInterval("fadeMsg(0)", MSGTIMER);
    }
}

// face the message box //
function fadeMsg(flag) {
    if (flag == null) {
        flag = 1;
    }
    var msg = document.getElementById('msg');
    var value;
    if (flag == 1) {
        value = msg.alpha + MSGSPEED;
    } else {
        value = msg.alpha - MSGSPEED;
    }
    msg.alpha = value;
    msg.style.opacity = (value / 100);
    msg.style.filter = 'alpha(opacity=' + value + ')';
    if (value >= 99) {
        clearInterval(msg.timer);
        msg.timer = null;
    } else if (value <= 1) {
        msg.style.display = "none";
        clearInterval(msg.timer);
    }
}

// calculate the position of the element in relation to the left of the browser //
function leftPosition(target) {
    var left = 10;
    if (target.offsetParent) {
        while (1) {
            left += target.offsetLeft;
            if (!target.offsetParent) {
                break;
            }
            target = target.offsetParent;
        }
    } else if (target.x) {
        left += target.x;
    }
    return left;
}

// calculate the position of the element in relation to the top of the browser window //
function topPosition(target) {
    var top = 0;
    if (target.offsetParent) {
        while (1) {
            top += target.offsetTop;
            if (!target.offsetParent) {
                break;
            }
            target = target.offsetParent;
        }
    } else if (target.y) {
        top += target.y;
    }
    return top;
}

// preload the arrow //
if (document.images) {
    arrow = new Image(7, 80);
    arrow.src = "aplication/webroot/imgs/msg_arrow.gif";
}