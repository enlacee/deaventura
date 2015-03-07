// JavaScript Document
$(document).ready(function(){


    $('.row_h').live('mouseover',function(){
        $(this).attr('bgcolor','#FEF4D8');
    })

    $('.row_h').live('mouseout',function(){
        $(this).attr('bgcolor','#C9C9C9');
    })

	
    /*tinyMCE.init({
			mode:"specific_textareas",
			editor_selector : "tinymce",
			theme : "advanced",
			plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,safari,advlink,imagemanager",
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "tablecontrols,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,code,|,forecolor",
theme_advanced_buttons3 : "",
theme_advanced_buttons4 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left"
	});*/
	

    tinyMCE.init({
        mode:"specific_textareas",
        editor_selector : "tinymce",
        theme : "advanced",
        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,safari,advlink,imagemanager",
        paste_text_sticky : true,
        setup : function(ed) {
            ed.onInit.add(function(ed) {
                ed.pasteAsPlainText = true;
            });
        },
        theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,styleprops",
        theme_advanced_buttons2 : "tablecontrols,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,code,|,forecolor,|,insertimage",
        theme_advanced_buttons3 : "",
        theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        paste_preprocess : function(pl, o) {
            o.content = o.content.replace(/<\S[^><]*>/g, "");
        }
    });

    //$("textarea").addClass("tinymce");
	//$("#descripcion_articulo").addClass("tinyMCE");

	
	
    var dates = $('#fechai, #fechaf').datepicker({
        showOn: "button",
        buttonImage: "../aplication/webroot/imgs/calendar.png",
        buttonImageOnly: true,
        maxDate: '+3M',
        dateFormat: 'dd/mm/yy',
        onSelect: function(selectedDate) {
            var option = this.id == "fechai" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
	
    $('.date').datepicker({
        showOn: "button",
        buttonImage: "../aplication/webroot/imgs/calendar.png",
        buttonImageOnly: true,
        dateFormat: 'yy/mm/dd',
    });
	
    $(".solo_numero").keyup(function(){
        if ($(this).val() != '')
            $(this).val($(this).attr('value').replace(/[^0-9]/g, ""));
    });

    $("#precio_publico").keyup( function(){

        /*  Precio nacional =Pn
          Precio privado = PP
          Precio extranjero= Pe
          Tipo de cambio dólar = T/C dólar

Si Pn <= 10, entonces Pp = Pn + 0.5(T/C dólar)
Si  10 < Pn <= 20, entonces Pp = Pn + 0.75(T/C dólar)
Si  20 < Pn <= 30, entonces Pp =Pn + 1(T/C dólar)
Si 30 < Pn <=40, entonces Pp =Pn + 1.3(T/C dólar)
Si 40 < Pn, entonces Pp =Pn + 1.6(T/C dólar)

En todo los casos, el Pe = Pp / (T/C dólar)

Todos los resultados deberan redeondearse a enteros por exceso. Ejemplo si me sale  un valor de 5.85, debera redondearse a 6.*/

        var Pn = parseFloat($("#precio_publico").val());
        var PP = parseFloat($("#precio_privado").val());
        var Pe = parseFloat($("#precio_extranjero").val());
        var tc = parseFloat($("#tipo_cambio").val());

        if(Pn<=10){
            PP = Pn + (0.5 *tc);
        }else if(Pn > 10 && Pn <= 20 ){
            PP = Pn + (0.75 * tc);
        }else if(Pn > 20 && Pn <= 30 ){
            PP = Pn + (1* tc);
        }else if(Pn > 30 && Pn <= 40 ){
            PP = Pn + (1.3 * tc);
        }else if(Pn > 40){
            PP = Pn + (1.6 * tc);
        }

        Pe = PP / tc;

        $("#precio_privado").val(PP.toFixed(1));
        $("#precio_extranjero").val(Pe.toFixed(1));

    });
	
    $('.edit').editable('ajax.php?action=ConfirmarSaldoCliente', {
        indicator : 'Guardando...',
        tooltip   : '',
        callback : function(data) {
            //alert(data);
            if(data==0){
                alert("ERROR: No se acepta numeros negativos")
               
            }else{
                ConfirmarRecarga(data);
            }
            
        }
    });
	 
    //$('.edit2').editable('ajax.php?action=ingresarSaldoCliente', {
    //indicator : 'Guardando...',
    //tooltip   : '',
    ////	  cancel    : 'Cancel',
    // submit    : 'OK',
    //});
	 
	 
    $("#listadoul").sortable({
        handle : '.handle',
        update : function () {
            var order = $('#listadoul').sortable('serialize');
            pintar();
            $.get("ajax.php?"+order,{
                action:'ordenar'
            },function(data){
                });
        }
    });
	
	
	
	
    $('.chk_horario').live('click', function(){

        if($(this).is(":checked")){
            $.get('ajax.php',{
                action:'saveHorarios', 
                id:$(this).val()
            },function(data){
			   
			   
                });
            $(this).parent().attr('bgcolor','#8DB9DC');
        }else{
            $.get('ajax.php',{
                action:'deleteHorarios', 
                id:$(this).val()
            },function(data){});
            $(this).parent().attr('bgcolor','#C9C9C9');
        }
		
    })	
	
	
    $('.tooltip').tipsy({
        gravity: 'n',
        fade: true
    });
	
	
    function pintar(){
        $("#listadoul li").each(function(x) {
            $(this).removeClass("fila1").removeClass("fila2");
            var w = 0;
            if(x%2==0){
                w = 2;
            }else{
                w = 1;
            }
            $(this).addClass("fila"+w);
        });
    }	
	
    $( "button, input:submit, input:reset, input:button" ).button();
	
    $( "select" ).combobox();
	
    $("input:file").filestyle({ 
        image: "../aplication/webroot/imgs/admin/examinar.jpg",
        imageheight : 27,
        imagewidth : 92,
        width : 250
    });
	  
	
    setInterval(function() {
        $(".notification").fadeOut(200);
    }, 3000);

    $("#info_user").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        resizable:false,
        buttons: {
            Cerrar: function() {
                $( this ).dialog( "close" );
            }
        }
    });	
	
    $("#saldo_cliente").dialog({
        autoOpen: false,
        height: 390,
        width: 370,
        resizable:false,
        buttons: {
            Cerrar: function() {
                $( this ).dialog( "close" );
            }
        }
    });	
	
    $("#ver_horario").dialog({
        autoOpen: false,
        height: 770,
        width: 550,
        resizable:false,
        buttons: {
            Cerrar: function() {
                $( this ).dialog( "close" );
            }
        }
    });	
	
	
	
    $('#welcome a').hover(function(){
        $(this).find('img').animate({
            top:'-5px'
        },{
            queue:false,
            duration:110
        });
    }, function(){
        $(this).find('img').animate({
            top:'0px'
        },{
            queue:false,
            duration:110
        });
    });

    /*-------------Edit NOW--------------
     *---------------TAGS----------------*/
    
    $("b.edit_now").click(function(){
        if($(".nom_edit").is(':visible')){
            $(".nom_edit").hide();
            $(".save_event").hide();
            $(".edit_now").show();
        };
        $parent = $(this).parent().parent();
        if(!$parent.find(".nom_edit").is(':visible')){
            $(this).hide();
            $(this).siblings(".nom_edit").show();
            $(this).siblings(".save_event").show();
        //var data = $(this).text();
        //$(this).parent().append('<input type="text" id="nom_edit" value="'+data+'"><img title="Guardar" class="save_event" src="../aplication/webroot/imgs/admin/save.png">');
        }
        return false;
    });
    
    $(".save_event").click(function(e){
        $elem = $(this).siblings("b");
        var nombre = $(this).siblings(".nom_edit").val();
        if(nombre != ""){
            var id = $(this).parent().parent().attr("id").replace("list_item_","");
        
            $this = $(this);
            $this.hide();
            $this.siblings(".img_load").show();
            $.post("ajax.php",{
                action:'update',
                id:id, 
                nombre:nombre
            },function(data){
                $this.siblings(".img_load").hide();
                $this.siblings("b.edit_now").show().text(nombre);
                $this.siblings(".nom_edit").hide();
                $this.hide();
            })
            e.stopPropagation();
        }else{
            alert("Debe ingresar el nombre del Tags")
        }
        
    });
    $(".nom_edit").click(function(e){
        e.stopPropagation();
    });
    
    $('html').click(function() {
        if($(".nom_edit").is(':visible')){
            $(".nom_edit").hide();
            $(".save_event").hide();
            $(".edit_now").show();
        };
    });
    
    /*----------------------
     *--------addTags-------
     *----------------------*/
    
    //Agregar un tag
    $(".tag_panel #panel_tags").delegate(".icadd","click",function(){
        $this = $(this);
        var span = $this.siblings("span").text();
        var id = $this.parent().attr("rel");
        var idsec = $("#id_sec").val();
        
        if(idsec != ""){ //En caso de que sea una modificaion
            $("#loader img").show();
            $.post("ajax.php",{
                action:'addTag',
                idtag:id,
                idsec:idsec,
                nom_sin:$("#id_sec").attr("rels"),
                nom_plu:$("#id_sec").attr("relp")
            },function(data){
                if(data == 1){
                    $("#tags_list").append('<p rel="'+id+'"><span>'+span+'</span><em class="deltag" title="Eliminar">X</em></p>');
                    $this.parent().remove();
                }else{
                    alert("No se agregó correctamente.");
                }
                $("#loader img").hide();
            })
        }else{ //En caso de que sea uno nuevo
            $("#tags_list").append('<p rel="'+id+'"><input type="hidden" value="'+id+'" name="tags[]"/><span>'+span+'</span><em class="deltag" title="Eliminar">X</em></p>');
            $this.parent().remove();
        }
        
    });
    
    //Eliminar un tag
    $(".tag_panel #tags_list").delegate(".deltag","click",function(){
        $this = $(this);
        var span = $this.siblings("span").text();
        var id = $this.parent().attr("rel");
        var idsec = $("#id_sec").val();
        
        if(idsec != ""){
            $("#loader img").show();
            $.post("ajax.php",{
                action:'deleteTag',
                idtag:id,
                idsec:idsec,
                nom_sin:$("#id_sec").attr("rels"),
                nom_plu:$("#id_sec").attr("relp")
            },function(data){
                if(data == 1){
                    $("#panel_tags ul").append('<li rel="'+id+'"><span>'+span+'</span><div class="icadd" title="Agregar">+</div></li>');
                    $this.parent().remove();
                }else{
                    alert("No se eliminó correctamente.");
                }
                $("#loader img").hide();
            })
        }else{
            $("#panel_tags ul").append('<li rel="'+id+'"><span>'+span+'</span><div class="icadd" title="Agregar">+</div></li>');
            $this.parent().remove();
        }
    });
    
    /* ------ addTags--- SOLO Rutas ----*/
    //Es diferente a los demas, porque las tablas cambian
    
    $(".tag_panel_rutas #panel_tags").delegate(".icadd","click",function(){
        $this = $(this);
        var span = $this.siblings("span").text();
        var id = $this.parent().attr("rel");
        
        var iddep = $this.parents("#tags").find("#id_dep").val();
        var idmod = $this.parents("#tags").find("#id_mod").val();
        var idlug = $this.parents("#tags").find("#id_lugar").val();
        
        if(iddep != "" && $("#id_dep").length != 0){ // Para las rutas normales
            $("#loader img").show();
            $.post("ajax.php",{
                action:'addTagRuta',
                idtag:id,
                idlug:idlug,
                iddep:iddep
            },function(data){
                if(data == 1){
                    $this.parents("#tags").find("#tags_list").append('<p rel="'+id+'"><span>'+span+'</span><em class="deltag" title="Eliminar">X</em></p>');
                    $this.parent().remove();
                }else{
                    alert("No se agregó correctamente.");
                }
                $("#loader img").hide();
            })
        }else if(idmod != "" && $("#id_mod").length != 0){ //Para las rutas de otros
            $("#loader img").show();
            $.post("ajax.php",{
                action:'addTagRuta_otros',
                idtag:id,
                idlug:idlug,
                idmod:idmod
            },function(data){
                if(data == 1){
                    $this.parents("#tags").find("#tags_list").append('<p rel="'+id+'"><span>'+span+'</span><em class="deltag" title="Eliminar">X</em></p>');
                    $this.parent().remove();
                }else{
                    alert("No se agregó correctamente.");
                }
                $("#loader img").hide();
            })
        }else{
            $this.parents("#tags").find("#tags_list").append('<p rel="'+id+'"><input type="hidden" value="'+id+'" name="tags[]"/><span>'+span+'</span><em class="deltag" title="Eliminar">X</em></p>');
            $this.parent().remove();
        }
        
    });

    
    $(".tag_panel_rutas #tags_list").delegate(".deltag","click",function(){
        $this = $(this);
        var span = $this.siblings("span").text();
        var id = $this.parent().attr("rel");
        
        var iddep = $this.parents("#tags").find("#id_dep").val();
        var idmod = $this.parents("#tags").find("#id_mod").val();
        var idlug = $this.parents("#tags").find("#id_lugar").val();
        
        if(iddep != "" && $("#id_dep").length != 0){ //Para las rutas normales
            $("#loader img").show();
            $.post("ajax.php",{
                action:'deleteTagRuta',
                idtag:id,
                idlug:idlug,
                iddep:iddep
            },function(data){
                if(data == 1){
                    $this.parents("#tags").find("#panel_tags ul").append('<li rel="'+id+'"><span>'+span+'</span><div class="icadd" title="Agregar">+</div></li>');
                    $this.parent().remove();
                }else{
                    alert("No se eliminó correctamente.");
                }
                $("#loader img").hide();
            })
        }else if(idmod != "" && $("#id_mod").length != 0){ //Para las rutas de otros
            $("#loader img").show();
            $.post("ajax.php",{
                action:'deleteTagRuta_otros',
                idtag:id,
                idlug:idlug,
                idmod:idmod
            },function(data){
                if(data == 1){
                    $this.parents("#tags").find("#panel_tags ul").append('<li rel="'+id+'"><span>'+span+'</span><div class="icadd" title="Agregar">+</div></li>');
                    $this.parent().remove();
                }else{
                    alert("No se eliminó correctamente.");
                }
                $("#loader img").hide();
            })
        }else{
            $this.parents("#tags").find("#panel_tags ul").append('<li rel="'+id+'"><span>'+span+'</span><div class="icadd" title="Agregar">+</div></li>');
            $this.parent().remove();
        }
    });
    
    
    
    /*----------- SUBMIT FORMS-----------------*/
    
    /*---------------
     *----LUGARES----
     *---------------*/
    $('#lugares').submit(function(){
        $data1 = $("#nombre_ruta");
        $data2 = $("#ubicacion_lugar");
        $data3 = $("#lat_lugar");
        $data4 = $("#lng_lugar");

        if($data1.val()==""){
            alert('ERROR: El campo nombre ruta debe llenarse');
            $data1.focus(); 
            return false;
        }
        if(!$data1.val().match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
            alert('ERROR: El campo nombre no es valido');
            $data1.focus(); 
            return false;
        }
               
        if($data2.val()==""){
            alert('ERROR: El campo lugar de la ruta debe llenarse');
            $data2.focus(); 
            return false;
        }   
                        
        if($data3.value==""){
            alert('ERROR: El campo lat de la ruta debe llenarse');
            $data3.focus(); 
            return false;
        }      
                        
        if($data4.value==""){
            alert('ERROR: El campo lng de la ruta debe llenarse');
            $data4.focus(); 
            return false;
        }
    });
    
    
    
    /*------Google Maps-------*/
    if($("#lat_lugar").length !== 0 && $("#lng_lugar").length !== 0){
        var lat,lng; 
        ($("#lat_lugar").val() === "") ? lat = -11.54932570 : lat = $("#lat_lugar").val();
        ($("#lng_lugar").val() === "") ? lng = -77.541503906 : lng = $("#lng_lugar").val();
    
        var myPos = new google.maps.LatLng(lat,lng);
        var mapOptions = {
            center: myPos,
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        var input = /** @type {HTMLInputElement} */(document.getElementById('address'));
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map
        });
                            
                            
        var markerNew = new google.maps.Marker({
            map:map,
            draggable:true,
            icon: new google.maps.MarkerImage('../aplication/webroot/imgs/icon_location.png'),
            position: new google.maps.LatLng(lat,lng)
        });
        google.maps.event.addListener(markerNew, 'mouseout', toggleBounce);
                            
                            

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            input.className = '';
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // Inform the user that the place was not found and return.
                input.className = 'notfound';
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(/** @type {google.maps.Icon} */({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });

        /*Animación para seleccionar ubicación*/
        google.maps.event.addListener(map, 'click', function(e) {
            placeMarker(e.latLng, map);
        });
    }
         
    function placeMarker(position, map) {
        marker.setMap(null)
        markerNew.setMap(null);
        markerNew = new google.maps.Marker({
            map:map,
            draggable:true,
            icon: new google.maps.MarkerImage('../aplication/webroot/imgs/icon_location.png'),
            position: position
        });
        google.maps.event.addListener(markerNew, 'mouseout', toggleBounce);//map.panTo(position); Efecto movimiento con el click
    }
                                                
    function toggleBounce() {
        //Capturo lo posicion al mover el icono del mapa
        var count = 0;
        $.each(markerNew.getPosition(), function (i,v){
            if(count == 0){//LB
                $("#lat_lugar").val(v);
            }else if(count == 1){//KB
                $("#lng_lugar").val(v);
                return false;
            }
            count++;
        });
    }
    
    /*---------------
     *----RUTAS----
     *---------------*/
    $('#rutas:visible').submit(function(){
        $data1 = $("#nombre_ruta");

        if($data1.val()==""){
            alert('ERROR: El campo nombre ruta debe llenarse');
            $data1.focus(); 
            return false;
        }
    });
	
	jQuery(function($){
		$.datepicker.regional['es-PE'] = {
			closeText: 'Cerrar',
			prevText: '&#x3c;Ant',
			nextText: 'Sig&#x3e;',
			currentText: 'Hoy',
			monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
			'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
			monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
			'Jul','Ago','Sep','Oct','Nov','Dic'],
			dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
			dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
			dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['es-PE']);
	});
	
});

/*DEAVENTURA*/
function buscarXfiltro(tipo){
    var value = $("#cbo_filtro").val();
    $("#btn_filtro").after(' <img id="loader" src="../aplication/webroot/imgs/ajax-loader.gif">');
    
    $.post("ajax.php",{
        buscarxId:'1',
        filtro:tipo,
        id:value
    },function(data){
        $("#listadoul li").remove();
        $("#loader").remove();
        $("#listadoul").append(data);
    })
}






function search_cliente(){
    var r = window.prompt('Buscar Cliente - Ingresa (apellido, nombre, email, universidad, especialidad)','');
    if(r != null){
        location.href = 'clientes.php?q='+r;
    }
}

function search_cliente_recarga(){
    var r = window.prompt('Buscar Cliente - Ingresa (Apellido o Nombre)','');
    if(r != null){
        location.href = 'historial_recargas.php?q='+r;
    }
}

function view_user(user){
    $.get('ajax.php',{
        action:'viewUser', 
        id:user
    },function(data){
        $( "#info_user" ).html( data );
        $( "#info_user" ).dialog( "open" );
    });
}

function valida_solucionarios(opcion, id){
    var nombre = document.solucionarios.elements['nombre[]'];
	
    if(nombre.value == ""){ 
        alert("Ingrese el titulo");
        nombre.focus();
        return false;
    }else if(document.solucionarios.precio_publico.value == ""){
        alert("Ingrese el precio");
        document.solucionarios.precio_publico.focus();
        return false;
    }
	
    document.solucionarios.action="solucionarios.php?action="+opcion+"&id="+id;
    document.solucionarios.submit();
}	

function valida_deportes(opcion, id){
    var nombre = document.form_deportes.nom_deporte;
    if(nombre.value == ""){ 
        alert("Ingrese el nombre del deporte");
        nombre.focus();
        return false;
    }
    
	
    document.form_deportes.action="deportes.php?action="+opcion+"&id="+id;
    document.form_deportes.submit();
}

function valida_categorias(opcion, id){
    var nombre = document.categorias.elements['nombre_categoria[]'];
    if(nombre.length > 0){
        for(i = 0; i< nombre.length; i++){
            if(nombre[i].value == ""){ 
                alert("Ingrese el nombre de la categoria");
                nombre[i].focus();
                return false;
            }
        }
    }else{
        if(nombre.value == ""){ 
            alert("Ingrese el nombre de la categoria");
            nombre.focus();
            return false;
        }
    }
	
    document.categorias.action="solucionarios.php?actioncat="+opcion+"&id="+id;
    document.categorias.submit();
}	




function valida_proveedor(opcion, id){   
    var nombre = $("#nombre_proveedor").val();
    if(nombre==""){
        alert('ERROR: El campo nombre proveedor debe llenarse');
        document.proveedores.nombre_proveedor.focus(); 
        return false;
    }
    if(!nombre.match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
        alert('ERROR: El campo nombre no es valido');
        document.proveedores.nombre_proveedor.focus(); 
        return false;
    }

    if(document.proveedores.direccion_proveedor.value==""){
        alert('ERROR: El campo direccion debe llenarse');
        document.proveedores.direccion_proveedor.focus(); 
        return false;
    }

    if(!$(".chk_deporte").is(':checked')){
        alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
        return false;
    }

    var c_email = $(".email").val();
    if(c_email != undefined && c_email != ""){
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if(!c_email.match(emailRegex)) {
            alert('Error: Ha introducido una dirección de correo electrónico no válida.');
            $(".email").focus(); 
            return false;
        }
    }
    document.proveedores.action="proveedores.php?action="+opcion+"&id="+id;
    document.proveedores.submit();
}



function valida_organizaciones(opcion, id){
    var nombre = $("#nombre_organizacion").val();
    if(nombre==""){
        alert('ERROR: El campo nombre organizacion debe llenarse');
        document.organizaciones.nombre_organizacion.focus(); 
        return false;
    }
    if(!nombre.match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
        alert('ERROR: El campo nombre no es valido');
        document.organizaciones.nombre_organizacion.focus(); 
        return false;
    }


    if(!$(".chk_deporte").is(':checked')){
        alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
        return false;
    }

    var c_email = $(".email").val();
    if(c_email != undefined && c_email != ""){
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if(!c_email.match(emailRegex)) {
            alert('Error: Ha introducido una dirección de correo electrónico no válida.');
            $(".email").focus(); 
            return false;
        }
    }
            
    document.organizaciones.action="organizaciones.php?action="+opcion+"&id="+id;
    document.organizaciones.submit();
}


function valida_agencias(opcion, id){
    var nombre = $("#nombre_agencia").val();
    if(nombre==""){
        alert('ERROR: El campo nombre agencia debe llenarse');
        document.agencias.nombre_agencia.focus(); 
        return false;
    }
    if(!nombre.match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
        alert('ERROR: El campo nombre no es valido');
        document.agencias.nombre_agencia.focus(); 
        return false;
    } 
    /*                   
    if(document.agencias.website_agencia.value==""){
        alert('ERROR: El campo website agencia debe llenarse');
        document.agencias.website_agencia.focus(); 
        return false;
    }*/

    if(!$(".chk_deporte").is(':checked')){
        alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
        return false;
    }
    

    var c_email = $(".email").val();
    if(c_email != undefined && c_email != ""){
        var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        if(!c_email.match(emailRegex)) {
            alert('Error: Ha introducido una dirección de correo electrónico no válida.');
            $(".email").focus(); 
            return false;
        }
    }
    document.agencias.action="agencias.php?action="+opcion+"&id="+id;
    document.agencias.submit();
}  


function valida_ArticulosBlog(opcion, id){
    var titulo = $("#titulo_articulo_blog").val();
    var enlace = $("#enlace_articulo_blog").val();
    if(titulo==""){
        alert('ERROR: El campo titulo debe llenarse');
        document.agencias.nombre_agencia.focus(); 
        return false;
    }
    if(!titulo.match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
        alert('ERROR: El campo nombre no es valido');
        document.agencias.nombre_agencia.focus(); 
        return false;
    }
    if(enlace==""){
        alert('ERROR: El campo enlace debe llenarse');
        document.agencias.nombre_agencia.focus(); 
        return false;
    } 
    if(opcion == "add"){
        if(!$(".chk_deporte").is(':checked')){
            alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
            return false;
        }
    }
    
    
    document.ArticulosBlog.action="articulosdeblog.php?action="+opcion+"&id="+id;
    document.ArticulosBlog.submit();
}  



function valida_eventos(opcion, id){     
    var nombre = $("#titulo_evento").val();
    if(nombre==""){
        alert('ERROR: El campo nombre evento debe llenarse');
        document.eventos.titulo_evento.focus(); 
        return false;
    }
    if(!nombre.match(/^[ a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ]+$/)){
        alert('ERROR: El campo nombre no es valido');
        document.eventos.titulo_evento.focus(); 
        return false;
    }

    if(!$(".chk_deporte").is(':checked')){
        alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
        return false;
    }
                        
    if(document.eventos.lugar_evento.value==""){
        alert('ERROR: El campo website agencia debe llenarse');
        document.eventos.lugar_evento.focus(); 
        return false;
    }

    document.eventos.action="eventos.php?action="+opcion+"&id="+id;
    document.eventos.submit();
}   



    



function valida_sitios_web(opcion, id){     
    var nombre = $("#titulo_sitio_web").val();
    if(nombre==""){
        alert('ERROR: El campo titulo del sitio web debe llenarse');
        document.eventos.titulo_sitio_web.focus(); 
        return false;
    }

    if(!$(".chk_deporte").is(':checked')){
        alert('Debe seleccionar almenos una opción de los deportes a la cual pertenece su servicio');
        return false;
    }
                        
    if(document.sitios_web.url_sitio_web.value==""){
        alert('ERROR: El campo url del sitio web debe llenarse');
        document.sitios_web.url_sitio_web.focus(); 
        return false;
    }

    document.sitios_web.action="sitios_web.php?action="+opcion+"&id="+id;
    document.sitios_web.submit();
}


function valida_tags(opcion, id){     
    var nombre = $("#nombre").val();
    if(nombre==""){
        alert('ERROR: El campo nombre del tag debe llenarse');
        document.form_tags.nombre.focus(); 
        return false;
    }

    document.form_tags.action="tags.php?action="+opcion+"&id="+id;
    document.form_tags.submit();
}




var testresults
function checkemail(value){
    var str = value
    var filter=/^.+@.+\..{2,3}$/
    if (filter.test(str))
        testresults=true
    else{
        alert("Por favor ingrese un e-mail valido...");
        testresults=false
    }
    return (testresults)
}


function mantenimiento(url,id,opcion){
    if(opcion!="delete"){ 
        location.replace(url+'?action='+opcion+'&id='+id);			
    }else if(opcion=="delete"){
        if(!confirm("Esta Seguro que desea Eliminar el Registro")){
            return false;	
        }else{
            location.replace(url+'?action='+opcion+'&id='+id);
        }		
    }
}

function mantenimientoTag(url,id,opcion,idDep){
    if(!confirm("Esta Seguro que desea Eliminar el Registro")){
        return false;	
    }else{
        location.replace(url+'?action='+opcion+'&id='+id+'&id_dep='+idDep);
    }
}

function mantenimientoRutas(url,opcion,idd,idl){
    if(!confirm("Esta Seguro que desea Eliminar el Registro")){
        return false;	
    }else{
        location.replace(url+'?action='+opcion+'&idl='+idl+'&idd='+idd);
    }
}

function ConfirmarRecarga(datos){
    $(".edit").html("0.00");
    if(!confirm("Esta seguro de realizar la recarga de S/. "+datos.split('-')[2]+".00")){
        return false;
    }else{

        $.post("ajax.php?action=editarSaldoCliente",{
            data:datos
        },function(data){
            $(".edit2").html();
            $(".edit2").html(data);
        });
                
    }
}


function mantenimiento_cat(url,id,opcion){
    if(!confirm("Esta Seguro que desea Eliminar el Registro")){
        return false;	
    }else{
        location.replace(url+'?actioncat='+opcion+'&id='+id);			
    }		
}

function mantenimiento_det(url, id1){	
    location.replace(url+'?id1='+id1);			
}


function validar_delete(){
    if(!confirm("Esta Seguro que desea Eliminar el Registro")){
        return false;	
    }else{
        return true;	
    }	
}



function validnum(e) { 
    tecla = (document.all) ? e.keyCode : e.which; 
    //alert(tecla)
    if (tecla == 8 || tecla == 46 || tecla == 0) return true; //Tecla de retroceso (para poder borrar) 
    // dejar la l�nea de patron que se necesite y borrar el resto 
    //patron =/[A-Za-z]/; // Solo acepta letras 
    patron = /[\d]/; // Solo acepta n�meros
    //patron = /\w/; // Acepta n�meros y letras 
    //patron = /\D/; // No acepta n�meros 
    // patron = /[\d.-]/; numeros el punto y el signo -
    te = String.fromCharCode(tecla); 
    return patron.test(te);  
// uso  onKeyPress="return validnum(event)"
}

function validar_tutoria(){
    if(document.f1.tutores.value==0){
        alert("Asigne un Tutor");
        return false;  
    }
    document.f1.submit();
}


function valida_usuarios(action,id){ 					
    if(document.usuarios.id_rol.value==""){
        alert('ERROR: El campo  rol debe llenarse');
        document.usuarios.id_rol.focus(); 
        return false;
    }						
													
    if(document.usuarios.nombre_usuario.value==""){
        alert('ERROR: El campo nombre usuario debe llenarse');
        document.usuarios.nombre_usuario.focus(); 
        return false;
    }						
													
    if(document.usuarios.apellidos_usuario.value==""){
        alert('ERROR: El campo apellidos usuario debe llenarse');
        document.usuarios.apellidos_usuario.focus(); 
        return false;
    }						
													
    if(document.usuarios.email_usuario.value==""){
        alert('ERROR: El campo email usuario debe llenarse');
        document.usuarios.email_usuario.focus(); 
        return false;
    }						
													
    if(document.usuarios.login_usuario.value==""){
        alert('ERROR: El campo login usuario debe llenarse');
        document.usuarios.login_usuario.focus(); 
        return false;
    }						
    if(action=="add"){					
        if(document.usuarios.password_usuario.value==""){
            alert('ERROR: El campo password usuario debe llenarse');
            document.usuarios.password_usuario.focus(); 
            return false;
        }						
    }
    document.usuarios.action="usuarios.php?action="+action+"&id="+id;
    document.usuarios.submit();
}			

function removerDiv(HijoE){
    $("#"+HijoE).fadeOut('slow', function() {
        $(this).remove();
    }); 
}

function delete_imagen(opcion){
    var f1 = eval("document.f1");
    $("#msg_delete").hide();
    if(f1.chkimag.length > 0){
        for(var i=0; i < f1.chkimag.length; i++){
            if(f1.chkimag[i].checked == 1){			
                var id = f1.chkimag[i].value;
                $(".imagen" + id).fadeOut('slow');
                $("#msg_delete").load("delete_imagen.php?id="+id+"&opcion="+opcion).fadeIn("slow");
                $("#imgp").fadeOut("slow");
            }
        }
    }else{
        if(f1.chkimag.checked == 1){			
            var id = f1.chkimag.value;
            $(".imagen" + id).fadeOut('slow');
            $("#msg_delete").load("delete_imagen.php?id="+id+"&opcion="+opcion).fadeIn("slow");
            $("#imgp").fadeOut("slow");
        }	
    }	 			
}

function saldo_cliente(id, name){
    $.get('ajax.php',{
        action:'viewSaldoCliente', 
        id:id
    },function(data){
		
        $( "#saldo_cliente" ).html( data );
        $( "#saldo_cliente" ).dialog( "open" );
        $( "#saldo_cliente" ).attr( "title", name );
    });
}

function verHorario(id){
    $.get('ajax.php',{
        action:'viewHorario', 
        id:id
    },function(data){
		
        $( "#ver_horario" ).html( data );
        $( "#ver_horario" ).dialog( "open" );
        $( "#ver_horario" ).attr( "title", name );
    });
}

function searchPedidos(){
    $.post('ajax.php?action=reportePedidos',{
        numero:$("#numero").val(), 
        estado:$("#estado").val(), 
        fechai:$("#fechai").val(),  
        fechaf:$("#fechaf").val()
    } ,function(data){
           
        $("#listado_pedidos").html(data);
    });		
}


function searchPedidosExt(){
    $.post('ajax.php?action=reportePedidosExt',{
        numero:$("#numero").val(), 
        estado:$("#estado").val(), 
        fechai:$("#fechai").val(),  
        fechaf:$("#fechaf").val()
    } ,function(data){

        $("#listado_pedidos").html(data);
    });
}

function searchProductos(){
    $.post('ajax.php?action=reporteProductos',{
        nombre:$("#nombre").val(), 
        categorias:$("#categorias").val(), 
        signo:$("#signo").val(), 
        precio:$("#precio").val()
    } ,function(data){
        $("#listado_prods").html(data);
    });		
}

function searchTutorias(){
    $.post('ajax.php?action=reporteTutorias',{
        estado:$("#estado").val(), 
        fechai:$("#fechai").val(),  
        fechaf:$("#fechaf").val()
    } ,function(data){
        $("#listado_tutorias").html(data);
    });
}


function cargarProducto(){ 
    //var d1,contenedor; 
    alert("hola");
    contenedor = document.getElementById('listado_prods'); 
    d1 = $("#categorias").val(); 
    ajax = nuevoAjax(); 
    ajax.open("GET", "procesa_categoria.php?edo="+d1+"&id="+$("#id_producto").val(),true); 
    ajax.onreadystatechange=function(){ 
        if (ajax.readyState==4) { 
            contenedor.innerHTML = ajax.responseText 
        } 
    } 
    ajax.send(null)
} 


function saveRelacion(val){
    $.post('ajax.php?action=saveRelacion',{
        id:$("#id_producto").val(), 
        id_p:val
    } ,function(data){
			
        });
}

function guardaValor(id){
	
/*if($('#'+id).is(":checked")){
		$('#CELDA-'+id).attr('bgcolor','#000000');
	}else{
		$('#CELDA-'+id).attr('bgcolor','#C9C9C9');
	}*/
	
}