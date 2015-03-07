<?php
include 'inc.aplication_top.php';
if (isset($_POST['id'])) {
    $sql = 'SELECT * FROM deportes WHERE id_deporte > "' . mysql_real_escape_string($_POST['id']) . '" ORDER BY id_deporte ASC LIMIT 0,5';
    $query = new Consulta($sql);

    $queryn = new Consulta('SELECT MAX(id_deporte) AS maximo FROM deportes');
    $rown = $queryn->VerRegistro();
    if ($rown['maximo'] != $_POST['id']) {
        while ($row = $query->VerRegistro()) {
            echo "<div class='info' id='" . $row['id_deporte'] . "'>
            <center><b>Nombre : </b>" . $row['nombre_deporte'] . " <br/><h1>" . $row['id_deporte'] . "</h1><br/>
            <b>Desc : </b>" . $row['descripcion_deporte'] . "<br/> <i style=\"font-size:small;color:blue\">Index : $id</i>
            <hr /></center></div>";
        }
    }
}

