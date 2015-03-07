// JavaScript Document
$(document).ready( function(){						   

	$("#opcion").change(function() {
        var src = $("option:selected", this).val();		
        document.form.action = src;
		document.form.submit();
		//location.replace(src);
		//$("#imagePreview").html(src ? "<img src='" + src + "'>" : "");
    });
 
	$("#id_proyecto").change(function(){		  	
         $.post("carga_select.php",{ idUsuario:$(this).val() },function(data){$("#id_usuario").html(data);})
	});
	
	$("#id_contrato").change(function(){		  	
         $.post("carga_select.php",{ idContrato:$(this).val() },function(data){$("#id_contacto").html(data);})
	});
	
	$("#id_empresa").change(function(){		  	
         $.post("carga_select.php",{ idEmpresa:$(this).val() },function(data){$("#id_contacto").html(data);})
	});
	
	$("#id_operacion").change(function(){		  	
         $.post("carga_select.php",{ idOperacion:$(this).val() },function(data){$("#id_servicio").html(data);})
	});

	
	//abrir una tarea cuando se esta en detalle de tarea
	$(".abrir_tarea").click(function(){ 
		$.post("carga_select.php",{ action:"crear-select" },function(data){ $(".estado-tarea").html(data); })
	});
	
	$("#enviarc").click(function(){
		$.post("carga_select.php",
				{ comentar:$("#comentario").val(),idTarea:$("#id_tarea").val() },function(data)
				{ $("#lista-comentarios").append("<p>  <strong>Yo:</strong> "+$("#comentario").val()+"</p>"); $("#comentario").val(""); }
		)
	});
	
	//cambiar de situacion por parte de usuario cuando se esta en detalle de tarea
	$("#id_situacion").change(function(){	 
         $.post("carga_select.php",{ id_Situacion:$(this).val(), id_Tarea:$("#id_tarea").val() },function(data){ 
			alert("Gracias por terminar la tarea");			
			$(".estado-tarea").html(data);
		});
	});
	
	
	
	
/*	$(".fila_check :input[type=checkbox]").click( function() { 
		this.checked=true;
	}); */
	
}); 


































