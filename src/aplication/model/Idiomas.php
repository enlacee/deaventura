<?php 
class Idiomas
{
	
	public function getIdiomas(){
		$query = new Consulta("SELECT * FROM idiomas WHERE estado_idioma = '1' ORDER BY id_idioma");
		while($row = $query->VerRegistro())
		{
			$idiomas[] = array(
				'id' 	  => $row['id_idioma'],
				'nombre'  => $row['nombre_idioma'],
				'imagen'  => $row['imagen_idioma'],
				'archivo' => $row['archivo_idioma']
			);
		}
		return $idiomas;
	}
}
?>