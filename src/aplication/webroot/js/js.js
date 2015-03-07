function abrir_detalle (id_venta) {

  $("#abrir_detalle_"+id_venta).css('display','none');
  $("#cerrar_detalle_"+id_venta).css('display','block');
  

  $("#add_detalle_"+id_venta).show('slow');   
        $.ajax({              
              url: 'ventas_detalle.php?id_venta='+id_venta,             
              success: function(data) {  
                $("#add_detalle_"+id_venta).html(data);   
              },     
              beforeSend: function(objeto){ 
              },
              complete: function(){
              }
        });  
}

function cerrar_menu (id_venta) {   

  $("#abrir_detalle_"+id_venta).css('display','block');
  $("#cerrar_detalle_"+id_venta).css('display','none');

  $("#add_detalle_"+id_venta).css('display','none');
}

$(document).ready(function() {

	$list = $("#list_item");

    if ($list.length != 0) {

        $list.sortable();

        $list.disableSelection();

    }
	
	$(".delete_step").click(function() {

        var $this = $(this);

        var idx = $this.find(".id_delete").val();

        if (idx == undefined) {

            $this.parent().remove();

        } else {

            $.post("ajax.php", {

                nombre_image: idx

            }, function(data) {

                $this.parent().remove();

            })

        }

    });
	
	
	tinyMCE.init({

            //mode:"specific_textareas",
            mode: "exact", 

            elements: "descripcion",

            editor_selector : "tinymce",

            theme : "advanced",

            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,safari,advlink,imagemanager",

            theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect",

            theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,forecolor",

            theme_advanced_buttons3 : "",

            theme_advanced_buttons4 : "",

            theme_advanced_toolbar_location : "top",

            theme_advanced_toolbar_align : "left",

            height:"350px"

            // width:"100px"

    });    

    $("#descripcion").addClass("tinymce");
	
	$(".panel_comparte .delete").click(function() {
       

        var $this = $(this);

        $this.css({

            'cursor': 'wait'

        });

        var idx = $this.find(".id_delete").val();

        if (idx == undefined) {

            $this.parent().remove();

        } else {

            $.post("ajax.php", {

                id_image: idx

            }, function(data) {

                //$this.parent().slideDown();

                $this.parent().remove();

                //$("#cbo_deportes").append(data);

            })

        }

    });

    $(".custom-input-file input:file").change(function() {

        $(".archivo").html($(this).val());

    });
    if($( ".date" ).length>0){
            $( ".date" ).datepicker({ 
                dateFormat: 'dd-mm-yy' 
            });
    }
    $(".eliminar").click(function(){
            if(!confirm("Desea realmente eliminar este Evento")){
                    return false;
            }
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() != 0) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });

    $('#scroll').click(function() {
        $('body,html').stop().animate({
            scrollTop: 0
        }, 200);
        return false;
    });
	

    /*---Metodo relleno de DIVS---*/
    var total_tipos = $("#cuerpo .pnl").length;
    var div_tipo1 = $("#cuerpo .panel1").length;
    var div_tipo2 = $("#cuerpo .panel2").length;
    var res = div_tipo2 + div_tipo1 * 2;

    var action;
    if ($('#login').length != 0) {
        action = "javascript: login();";
    } else if ($('#logout').length != 0) {
        action = "cuenta.php?cuenta=compartir";
    }
    if (total_tipos % 2 != 0) { // por ahora no
        // $('<a href="' + action + '" class="panel_ingresa"><p>INGRESA AQUÍ</p><p>TU HISTORIA DE AVENTURA</p></a>').insertAfter("#cuerpo .pnl:last");
    }

    /*----Menu----*/
    $("#menu ul li").each(function(i, e) {
        var rel = $("#menu ul li").eq(i).find("a").attr("rel");

        var image = new Image();
        image.src = 'aplication/webroot/imgs/catalogo/' + rel + '.png';
        image.onload = function() {
            $("#menu ul li").eq(i).find("a").prepend('<p><img src="' + image.src + '" width="24" height="23"/></p>');
        }


        var image2 = new Image();

        image2.src = 'aplication/webroot/imgs/catalogo/' + rel + '_hover.png';

        image2.onload = function() {

            $("#menu ul li").eq(i).find("a").prepend('<img class="img_hover" src="' + image2.src + '" />');

        }

    });



    $("#menu ul li a").hover(function() {

        if (!$(this).parent().hasClass("active")) {

            $(this).find(".img_hover").stop(true, true).show().animate({

                'top': '-11px',

                'opacity': 1

            }, 300);

            $(this).find("img:not(.img_hover)").css({

                'opacity': 0

            });

        }

    }, function() {

        if (!$(this).parent().hasClass("active")) {

            $(this).find("img:not(.img_hover)").css({

                'opacity': 1

            });

            $(this).find(".img_hover").hide().stop(true, true).animate({

                'top': '-6px'

            }, 400).fadeOut(400);

        }

    });



    /*--Panel right: Efecto de rutas y Eventos --*/



    $(".view_more").click(function() {

        if ($(this).text() == "+")

            $(this).text("-");

        else

            $(this).text("+");

        $(this).siblings("ul").toggle(500);

    });



    /*--------------GALLERY----------------*/

    if ($("#showcase").length != 0) {

        $("#showcase").awShowcase({

            content_height: 540,

            fit_to_parent: true,

            auto: false,

            interval: 3000,

            continuous: true,

            loading: true,

            tooltip_width: 200,

            tooltip_icon_width: 32,

            tooltip_icon_height: 32,

            tooltip_offsetx: 18,

            tooltip_offsety: 0,

            arrows: true,

            buttons: false,

            btn_numbers: true,

            keybord_keys: true,

            mousetrace: false, /* Trace x and y coordinates for the mouse */

            pauseonover: true,

            stoponclick: true,

            transition: 'hslide', /* hslide/vslide/fade */

            transition_speed: 500,

            transition_delay: 300,

            show_caption: 'onhover', /* onload/onhover/show */

            thumbnails: false,

            thumbnails_position: 'outside-last', /* outside-last/outside-first/inside-last/inside-first */

            thumbnails_direction: 'horizontal', /* vertical/horizontal */

            thumbnails_slidex: 0, /* 0 = auto / 1 = slide one thumbnail / 2 = slide two thumbnails / etc. */

            dynamic_height: false, /* For dynamic height to work in webkit you need to set the width and height of images in the source. Usually works to only set the dimension of the first slide in the showcase. */

            speed_change: false, /* Set to true to prevent users from swithing more then one slide at once. */

            viewline: true /* If set to true content_width, thumbnails, transition and dynamic_height will be disabled. As for dynamic height you need to set the width and height of images in the source. It's OK with only the width. */

        });

    }



    /*--------------------------------

     *---CREACION DE UNA AVENTURA-----

     *--------------------------------*/



    if ($("#cbo_deportes").length !== 0) {

        $.post("ajax.php", {

            deportes: '1'

        }, function(data) {

            if (data != "") {

                $("#cbo_deportes").append(data);

            }

        })



        /*Cbo deporte*/

        $("#cbo_deportes").change(function() {

            $.post("ajax.php", {

                idCbo: $(this).val()

            }, function(data) {

                if (data != "") {

                    $("#cbo_modalidad").html(data);

                } else {

                    $("#cbo_modalidad").html('<option value="0">No disponible</option>');

                }

            })

        });



        /*Cbo*/

        $("#cboDeportes").change(function() {

            $.post("ajax.php", {

                idCbo: $(this).val()

            }, function(data) {

                if (data != "") {

                    $("#cbo_modalidad").html(data);

                } else {

                    $("#cbo_modalidad").html('<option value="0">No disponible</option>');

                }

            })

        });
        
            // load combobox agentes
        if ($("#cbo_agencias").length !== 0) {
            $.post("ajax.php", {
                agencias: '1'
            }, function(data) {
                if (data != "") {
                    $("#cbo_agencias").append(data);
                }
            })  
        }

    }









    /* Subida de archivos*/

    /*Subir enlaces de videos - para campartir o modificar aventura*/

    var nvideo = 1;

    var myvideos = new Array();

    $("#btn_svideo").click(function() {

        $obj = $('#video_txt');

        url = $obj.val();

        var matches = url.match(/watch\?v=([a-zA-Z0-9\-_]+)/);

        if (matches) {

            var idPeli = url.split('?v=');

            if (idPeli[1] !== "") {

                myvideos.push(idPeli[1]);

                $(".fileupload-loading").show();

                $.get('https://gdata.youtube.com/feeds/api/videos/' + idPeli[1] + '?v=2&alt=json', function(data) {

                    title = data.entry.title.$t;

                    author = data.entry.author[0].name.$t;

                    mms = '<tr class="template-upload fade in" style="display: table-row;">';

                    mms += '<td><img src="aplication/webroot/imgs/icon_video.png"/></td>';

                    mms += '<td><p class="name">' + title + '<input type="hidden" name="video' + (nvideo++) + '" value="' + idPeli[1] + '"></p></td>';

                    mms += '<td width="240">Autor: ' + author + '</td>';

                    mms += '<td width="100"><button class="btn btn-warning cancel cvideo" title="Eliminar"></button></td>';

                    mms += '</tr>';

                    $("#table_imgs tbody").append(mms);

                    $(".fileupload-loading").hide();

                });

                $("#video_txt").val('');

            } else {

                alert("Debe introducir el link del video");

            }



        } else {

            alert('Url no válida');

            $obj.val('').focus();

        }





    });



    $("#table_imgs").delegate(".cvideo", "click", function() {

        $(this).parent().parent().fadeOut(function() {

            $(this).remove();

        });

    });





    //------------------------------------

    //Inicio del Jquery Plugin File upload



    if ($('.new_upload #fileupload').length !== 0) {

        $('#fileupload').fileupload({

            // Uncomment the following to send cross-domain cookies:

            //xhrFields: {withCredentials: true},

            url: 'aplication/utilities/fileUpload/server/',

            disableImageResize: /Android(?!.*Chrome)|Opera/

                    .test(window.navigator && navigator.userAgent),

            imageMaxHeight: 548,

            previewCrop: true

        });



        // Enable iframe cross-domain access via redirect option:

        var files_names = [];

        $('#fileupload').fileupload({

            maxFileSize: 10000000, // 10MB

            maxNumberOfFiles: 10,

            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,

            process: [

                {

                    action: 'loadImage',

                    fileTypes: /^image\/(gif|jpeg|png)$/,

                    maxFileSize: 10000000 // 20MB

                },

                {

                    action: 'resizeImage',

                    maxHeight: 540,

                    imageCrop: true

                },

                {

                    action: 'saveImage'

                }

            ],

            done: function(e, data) {

                files_names.push(data.result.files[0].name);

            }

        });

        $('#fileupload').fileupload(

                'option',

                'redirect',

                window.location.href.replace(/\/[^\/]*$/, 'aplication/utilities/fileUpload/cors/result.html?%s')

                );



        $('#fileupload').bind('fileuploadstart', function(e) {

            $("body").css("cursor", "progress");

        })



        $('#fileupload').bind('fileuploadstop', function(e) {

            $.ajax({

                type: "POST",

                url: "ajax.php",

                data: "step=1" + "&" + "files=" + files_names.toString() + "&" + "videos=" + myvideos.toString() + "&" + $("#fileupload").serialize(),

                success: function(data) {

                    $("body").css("cursor", "default");

                    document.form_step1.submit();

                }

            });

        })

    }



    // !---- Fin Jquery File Upload    



//    $("#clear_video").live("click", function() {
//
//        $(this).parent().parent().fadeOut(function() {
//
//            $(this).remove();
//
//        });
//
//        return false;
//
//    });



    /*----------------------------------

     * ---------scripts paso 2----------*/

//
//
//    $(".delete_step").live("click", function() {
//
//        var $this = $(this);
//
//        var idx = $this.find(".id_delete").val();
//
//        if (idx == undefined) {
//
//            $this.parent().remove();
//
//        } else {
//
//            $.post("ajax.php", {
//
//                nombre_image: idx
//
//            }, function(data) {
//
//                $this.parent().remove();
//
//            })
//
//        }
//
//    });





    /*----------------------------------

     * ---------scripts paso 3----------*/



    if ($("#lat_pos").length !== 0 && $("#lng_pos").length !== 0) {

        var lat, lng;

        ($("#lat_pos").val() === "") ? lat = -11.54932570 : lat = $("#lat_pos").val();

        ($("#lng_pos").val() === "") ? lng = -77.541503906 : lng = $("#lng_pos").val();





        var myPos = new google.maps.LatLng(lat, lng);

        var mapOptions = {

            center: myPos,

            zoom: 12,

            mapTypeId: google.maps.MapTypeId.ROADMAP

        };

        var map = new google.maps.Map(document.getElementById('mi_ubic'), mapOptions);

        var input = /** @type {HTMLInputElement} */(document.getElementById('address'));

        var autocomplete = new google.maps.places.Autocomplete(input);



        autocomplete.bindTo('bounds', map);



        var infowindow = new google.maps.InfoWindow();



        var markerNew = new google.maps.Marker({

            map: map,

            draggable: true,

            icon: new google.maps.MarkerImage('aplication/webroot/imgs/icon_location.png'),

            position: new google.maps.LatLng(lat, lng)

        });

        google.maps.event.addListener(markerNew, 'mouseout', toggleBounce);







        google.maps.event.addListener(autocomplete, 'place_changed', function() {

            infowindow.close();

            markerNew.setVisible(false);

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



            markerNew.setPosition(place.geometry.location);

            toggleBounce();



            markerNew.setVisible(true);



            var address = '';

            if (place.address_components) {

                address = [

                    (place.address_components[0] && place.address_components[0].short_name || ''),

                    (place.address_components[1] && place.address_components[1].short_name || ''),

                    (place.address_components[2] && place.address_components[2].short_name || '')

                ].join(' ');

            }



            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);

            infowindow.open(map, markerNew);



        });



        /*Animación para seleccionar ubicación*/

        google.maps.event.addListener(map, 'click', function(e) {

            placeMarker(e.latLng, map);

        });



        $("#address").keypress(function(e) {

            if (e.keyCode == 13) {

                e.stopPropagation();

                return false;

            }

        });

    }



    function placeMarker(position, map) {



        markerNew.setMap(null)

        markerNew.setMap(null);

        markerNew = new google.maps.Marker({

            map: map,

            draggable: true,

            icon: new google.maps.MarkerImage('aplication/webroot/imgs/icon_location.png'),

            position: position

        });

        google.maps.event.addListener(markerNew, 'mouseout', toggleBounce);//map.panTo(position); Efecto movimiento con el click

    }



    function toggleBounce() {

        //Capturo lo posicion al mover el icono del mapa

        var count = 0;

        $.each(markerNew.getPosition(), function(i, v) {

            if (count == 0) {//LB

                $("#lat_pos").val(v);

            } else if (count == 1) {//KB

                $("#lng_pos").val(v);

                return false;

            }

            count++;

        });

    }



    /*-----------ELIMINAR AVENTURA--------------*/



    $(".elim_aventura").click(function() {

        $this = $(this);

        if (confirm("¿Seguro que quiere eliminar la aventura ?")) {



            var id = $this.find('input[type="hidden"]').val();

            $.post("ajax.php", {

                elim_av: '1',

                id: id

            }, function(data) {

                $this.parent().parent().remove();

                if ($(".aventura_panel").length == 0) {

                    $("#panel_step").append('<br/><div align="center">No tienes aventuras para mostrar</div>');

                }

            })

        }

        return false;

    });





    $(".elim_favoritos").click(function() {

        $this = $(this);

        if (confirm("¿Seguro que quiere eliminar la aventura de sus favoritos?")) {



            var id = $this.attr("rel");

            $.post("ajax.php", {

                elim_favorito: '1',

                id: id

            }, function(data) {

                $this.parent().parent().remove();

                if ($(".aventura_panel").length == 0) {

                    $("#panel_step").append('<br/><div align="center">Aún no tienes aventuras para  mostrar como favoritos</div>');

                }

            })

        }

        return false;

    });









    /*---------------------------------

     * -----Modificar aventura---------

     * --------------------------------*/



    if ($("#form_update").length !== 0) {

        $('#form_update').fileupload({

            // Uncomment the following to send cross-domain cookies:

            //xhrFields: {withCredentials: true},

            url: 'aplication/utilities/fileUpload/server/',

            disableImageResize: /Android(?!.*Chrome)|Opera/

                    .test(window.navigator && navigator.userAgent),

            imageMaxHeight: 540,

            previewCrop: true

        });



        // Enable iframe cross-domain access via redirect option:



        $('#form_update').fileupload({

            maxFileSize: 10000000, // 10MB

            maxNumberOfFiles: 10,

            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,

            process: [

                {

                    action: 'loadImage',

                    fileTypes: /^image\/(gif|jpeg|png)$/,

                    maxFileSize: 10000000 // 20MB

                },

                {

                    action: 'resizeImage',

                    maxHeight: 540,

                    imageCrop: true

                },

                {

                    action: 'saveImage'

                }

            ]

        });

        $('#form_update').fileupload(

                'option',

                'redirect',

                window.location.href.replace(/\/[^\/]*$/, 'aplication/utilities/fileUpload/cors/result.html?%s')

                );





        files_names = [];

        $('#form_update').bind('fileuploaddone', function(e, data) {

            files_names.push(data.result.files[0].name);

            var html = '';

            html += '<li>';

            html += '<div class="panel_comparte">';

            html += '<div class="delete_ajax"><input class="id_delete" type="hidden" value="' + data.result.files[0].name + '"></div>';

            html += '<div class="left_com"></div>';



            html += '<div class="img_block">';

            html += '<input name="id_files[]" type="hidden" value=""/>';

            html += '<input name="src_files[]" type="hidden" value="' + data.result.files[0].name + '"/>';

            html += '<input name="tipo_files[]" type="hidden" value="F"/>';

            html += '<div class="img_comparte"><img src="aplication/webroot/imgs/catalogo/aventuras_img_usuarios/' + data.result.files[0].name + '" width="171"></div>';

            html += '</div>';



            html += '<div class="descrp_comparte">';

            html += '<div class="rowElem"><input name="titulo_files[]" type="text" /></div>';

            html += '<div class="rowElem"><textarea name="descripcion_files[]"></textarea></div>';

            html += '</div>';

            html += '</div>';

            html += '</li>';



            $(html).insertAfter('#list_item li:last-child');



            $.ajax({

                type: "POST",

                url: "ajax.php",

                data: "imagenMd=1" + "&" + "img=" + data.result.files[0].name,

                success: function(data) {

                    //alert(data);

                }

            });

        })



        $('#form_update').bind('fileuploadstop', function(e) {

            $("#table_imgs tbody tr").remove();

            $(".fileupload-progress").remove();

        })



    }

    // -----------------FIN the jQuery File Upload widget----------------------  



    $("#subir_archivos").click(function() {

        $(".btn-primary.start").click();



        /*Solo para los videos*/

        var arch = String(myvideos);

        var arr = arch.split(',');



        var html = '';

        if (myvideos.length > 0) {

            for (var i = 0; i < arr.length; i++) {

                html += '<div class="panel_comparte">';

                html += '<div class="delete"></div>';

                html += '<div class="left_com"></div>';

                html += '<div class="img_block">';

                html += '<input name="id_files[]" type="hidden" value=""/>';

                html += '<input name="src_files[]" type="hidden" value="' + arr[i] + '"/>';

                html += '<input name="tipo_files[]" type="hidden" value="V"/>';

                html += '<div class="img_comparte"><img src="aplication/webroot/imgs/icon_video_ver.jpg"></div>';

                html += '</div>';

                html += '<div class="descrp_comparte">';

                html += '<div class="rowElem"><input name="titulo_files[]" type="text" /></div>';

                html += '<div class="rowElem"><textarea name="descripcion_files[]"></textarea></div>';

                html += '</div>';

                html += '</div>';

            }

            $(html).insertBefore('#listado_archivos .clear');

            $('.ax-file-list .video_upload').remove();

        }



        myvideos = new Array();

        /*$("#table_imgs tbody tr").remove();

         $(".fileupload-progress").remove();*/



    });


    /*
    $(".panel_comparte .delete").live("click", function() {

        var $this = $(this);

        $this.css({

            'cursor': 'wait'

        });

        var idx = $this.find(".id_delete").val();

        if (idx == undefined) {

            $this.parent().remove();

        } else {

            $.post("ajax.php", {

                id_image: idx

            }, function(data) {

                //$this.parent().slideDown();

                $this.parent().remove();

                //$("#cbo_deportes").append(data);

            })

        }

    });

    */

    $(".panel_comparte .delete_ajax").live("click", function() {

        var $this = $(this);

        $this.css({

            'cursor': 'wait'

        });

        var idx = $this.find(".id_delete").val();

        $.post("ajax.php", {

            src_image: idx

        }, function(data) {

            $this.parent().remove();

        })

    });



    



    /*--------------------------------------------------

     ------------------Google Maps-----------------------

     --------------------------------------------------*/





    //Actualizar likes y commnets

    if ($("#cuerpo .pnl").length > 0) {

        var datos_av = $("#cuerpo .pnl");

        $(datos_av).each(function(index) {

            var $this = $(this);

            var url = $this.find('.titulo_panel a:first-child').attr("href");

            $.ajax({

                type: "GET",

                url: 'https://api.facebook.com/method/fql.query?query=SELECT%20like_count,comment_count,share_count,total_count%20FROM%20link_stat%20WHERE%20url=%22' + url + '%22',

                dataType: "xml",

                success: function(xml) {

                    $(xml).find('link_stat').each(function() {

                        var likes = $(this).find('like_count').text();

                        var comments = $(this).find('comment_count').text();



                        $this.find('.social_panel .coment').text(comments);

                        $this.find('.social_panel .like').text(likes);

                    });

                }

            });

        });

    }





    //AutoMArginTop - para sitios web por deporte





    $(".evento_desc").each(function(i) {

        var ha = parseInt($(".evento_desc").eq(i).height());

        var hb = parseInt($(".evento_desc").eq(i).find(".foto_panel_desc2").height());

        $(".evento_desc").eq(i).find(".foto_panel_desc2").css('margin-top', (ha / 2) - (hb / 2));

    });



    //Busqueda

    $("#nav_search li").click(function() {

        var rel = $(this).attr("rel");

        $(rel).show().siblings(".search_results").hide();

    });

    $("#btn_buscar").click(function(){	

        var texto = $("#busqueda").val();
        if(texto != "" ){

                texto = texto.replace(/(\s)/gi,"-");
                busqueda("http://www.deaventura.pe/",texto);

        }		

    });
    
    function busqueda(url,texto){

        document.fbuscar.action = url+'q=' + texto;
        document.fbuscar.submit();

    }



    /*-----------------------------

     * ------Ver Aventura----------

     * ----------------------------*/



    if ($(".aventura-info").length !== 0) {

        var url = document.URL;

        var uid = $(".aventura-info").attr("uid-av"); //ID de la aventura



        window.fbAsyncInit = function() {

            FB.init({

                appId: '466105603427618',

                status: true,

                cookie: true,

                xfbml: true,

                oauth: true

            });

            FB.Event.subscribe('edge.create', function(response) {

                var pp = parseInt($(".like").text());

                $(".like").text(pp + 1);

                data_facebook(url, uid, "likes");

            });

            FB.Event.subscribe('edge.remove', function(response) {

                var pp = parseInt($(".like").text());

                $(".like").text(pp - 1);

                data_facebook(url, uid, "likes");

            });

            FB.Event.subscribe('comment.create', function(response) {

                var pp = parseInt($(".coment").text());

                $(".coment").text(pp + 1);

                data_facebook(url, uid, "coments");

            });

            FB.Event.subscribe('comment.remove', function(response) {

                var pp = parseInt($(".coment").text());

                $(".coment").text(pp - 1);

                data_facebook(url, uid, "coments");

            });

        }

        //data_facebook(url, uid, "all");

    }

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





/*-----------------------------

 * ------Ver Aventura----------

 * ----------------------------*/



function data_facebook(url, id, tipo) {

    var likes, comments;

    $.ajax({

        type: "GET",

        url: 'https://api.facebook.com/method/fql.query?query=SELECT%20like_count,comment_count,share_count,total_count%20FROM%20link_stat%20WHERE%20url=%22' + url + '%22',

        dataType: "xml",

        success: function(xml) {

            $(xml).find('link_stat').each(function() {

                likes = $(this).find('like_count').text();

                comments = $(this).find('comment_count').text();



                $(".like").text(likes);

                $(".coment").text(comments);

            });

        },

        complete: function() {

            $.ajax({

                type: "POST",

                url: 'ajax.php',

                data: {

                    type: tipo,

                    id: id,

                    likes: likes,

                    comments: comments,

                    updateFace: '1'

                }

            });

        }

    });

}





/*------------------

 *---More Scroll Content-----

 *------------------*/



$(window).scroll(function() {

    if ($(window).scrollTop() === $(document).height() - $(window).height()) {

        $content = $("#seccion-right");

        $loadMore = $("#loadMoreContent");

       

        if ($loadMore.lenght !== 0) {

            //Agregar o quitar la clase active del PADRE para que solo se ejecute una vez

            //La comprobación de mpás contenido
             
            if ($content.hasClass('active')) {
               
                return false;

            } else {

                $content.addClass("active");
                
            }



            $loadMore.show();
            // alert("si leeeeelo");
            $.ajax({

                url: "ajax.php",

                type: "POST",

                data: {

                    id: $(".pnl.panel2:last").attr('id'),

                    action: 'loadMore',

                    type: $content.attr("post-type"),

                    uid: $content.attr("post-id")

                },

                success: function(html) {

                    if (html && html != 0) {

                        $(html).insertBefore('#loadMoreContent');

                        $loadMore.hide();

                        $content.removeClass("active");

                    } else {

                        $loadMore.remove();

                    }

                }

            });

        }



    }

});







/*--------------------------------------------------

 ------------------LOGIN-----------------------

 --------------------------------------------------*/



function login() {

    LodingAnimate();
/*
    FB.login(function(response) {

        if (response.status == 'connected') {

            AjaxResponse();

        } else {

            alert("No se pudo identificar al usuario");

            ResetAnimate();

        }

    }, {

        scope: 'email,publish_stream'

    });
    */

    AjaxResponse();

}



function AjaxResponse() {

    var myData = 'connect=1'; //For demo, we will pass a post variable, Check process_facebook.php

    jQuery.ajax({

        type: "POST",

        url: "validateUser.php",

        dataType: "html",

        data: myData,

        success: function(response) {

            $("#welcome_b").html(response); //Result

            //$(response).insertBefore('#welcome_b');

            //window.location.reload();
            window.location.replace("http://www.deaventura.pe/cuenta.php?cuenta=bienvenido");

        },

        error: function(xhr, ajaxOptions, thrownError) {

            $("#welcome_b").html(thrownError); //Error

        }

    });

}



function LodingAnimate() {//Show loading Image

    $('<span id="welcome_b"><img src="aplication/webroot/imgs/ajax-loader.gif" /> Conectando...</span>').insertBefore('#login');

}



function ResetAnimate() {//Reset User button

    $("#welcome_b").remove(); //reset element html

}



/*...............*/





function agregarfavoritos(aventura) {

    $(".add_favoritos").css({

        'background': 'url(http://www.deaventura.pe/aplication/webroot/imgs/ajax-loader.gif) no-repeat'

    });

    $.post("http://www.deaventura.pe/ajax.php", {

        favoritos: '1',

        id: aventura

    }, function(data) {

        console.log(data);

        $(".add_favoritos").css({

            'background': 'url(http://www.deaventura.pe/aplication/webroot/imgs/icon_favoritos.png) no-repeat 0px -20px'

        });

        $(".add_favoritos").addClass("active").removeAttr("onclick");

    })

}



function btn_eventos(evento) {//Asistiré, estuve aqui

    $.post("http://www.deaventura.pe/ajax.php", {

        mod_evento: '0',

        id: evento

    }, function(data) {

        if (data == 0) {

            alert("Debe iniciar sesión para poder realizar esta acción");

        } else if (data == 1) {

            alert("El evento ya está registrado");

        } else {

            $(".abtn_evento").removeAttr("onclick").addClass('active');

            $(".abtn_evento").html('<a id="cancel_evento" href="javascript: cancel_evento(' + evento + ');"></a>');

        }

    })

}



function cancel_evento(evento) {

    $.post("http://www.deaventura.pe/ajax.php", {

        mod_evento: '1',

        id: evento

    }, function(data) {

        $(".abtn_evento").removeClass("active")

                .attr("onclick", "javascript: btn_eventos(" + evento + ")")

                .find("a").remove();

    });

    return false;

}