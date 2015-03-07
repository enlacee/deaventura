<?php
include 'inc.aplication_top.php';
include(_includes_ . "inc.header.php");

/*VER POR IMPORTA CON TODO Y INCLUDE*/
?>
<div id="postedComments">
    <h1> Welcome , Scroll to the bottom </h1>
    <?php
    
    $query = new Consulta("SELECT * FROM deportes ORDER BY id_deporte ASC LIMIT 0,5");
    while ($row = $query->VerRegistro()) {
        echo "<div class='info' id='".$row['id_deporte']."'>
        <center><b>Nombre : </b>".$row['nombre_deporte']." <br/><h1>".$row['id_deporte']."</h1><br/>
        <b>Desc : </b>".$row['descripcion_deporte']."<br/> <i style=\"font-size:small;color:blue\">Index : $id</i>
        <hr /></center></div>";
    }
    ?>
</div>
<div id="loadMoreContent" style="display:none;"><img src="aplication/webroot/imgs/ajax-loader.gif"></div>
<script type="text/javascript">
    $(document).ready(function() { 
        $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
                
                var content = $("#postedComments");
                var loadMore = $("#loadMoreContent");
                //Agregar o quitar la clase active del PADRE para que solo se ejecute una vez
                //La comprobación de mpás contenido
                if($(content).hasClass('active')){
                    return false;
                }else{
                    $(content).addClass("active");
                }
                
                $(loadMore).show();
                $.ajax({
                    url: "loadMoreContent.php",
                    type: "POST",
                    data: {id : $(".info:last").attr('id')},
                    success: function(html) {
                        if(html){
                            $(content).append(html);
                            $(loadMore).hide();
                            $(content).removeClass("active");
                        }else{
                            $(loadMore).replaceWith("<center><h1 style='color:red'>No hay más</h1></center>");
                        }
                    }
                });
            }
        });
    });
</script>
