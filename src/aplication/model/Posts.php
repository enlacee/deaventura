<?php 

class Posts{


	public function __construct(){


	}


	public function getPosts(){
		$sql = "SELECT * FROM wp_posts WHERE post_type = 'post' AND post_title NOT LIKE '%Borrador%' AND post_title !='' ORDER BY post_date DESC LIMIT 0,5 ";
		$query = new Consulta($sql);

		$data = array();
		if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_post' 			=> $row['ID'],
                    'titulo_post' 		=> sql_htm($row['post_title']),
                    'fecha_post' 		=> $row['post_date'],
                    'descripcion_post'  => sql_htm($row['post_content']),
                    'url_post' 			=> $row['post_name'],
                    'imagen_post' 		=> $row['post_image']
                );
            }
        }
        return $data;
	}
        
    public function getImagen($id_post){
        $sql = "SELECT * FROM wp_posts 
                WHERE post_type = 'attachment' 
                AND post_mime_type = 'image/jpeg' 
                AND post_parent ='".$id_post."' ";  
				

        $query = new Consulta($sql);
        $row = $query->VerRegistro();
        $imagen = $row['guid']; 
        return $imagen;
    }
    
    public function getPostsPorDeporte($deporte){
        $sql = "SELECT ID, p.post_title, p.post_content, p.post_name, p.post_date FROM wp_posts p, wp_terms t, wp_term_relationships tr, wp_term_taxonomy tt 
                WHERE t.name = '".$deporte."' 
                AND tr.object_id = p.ID 
                AND tr.term_taxonomy_id = tt.term_taxonomy_id 
                AND t.term_id = tt.term_id 
                AND tt.taxonomy = 'category'
                GROUP BY p.ID 
                ORDER BY p.ID DESC
                LIMIT 0,3";
        $query = new Consulta($sql);

        $data = array();
        if ($query->NumeroRegistros() > 0) {
            while ($row = $query->VerRegistro()) {
                $data[] = array(
                    'id_post' 		=> $row['ID'],
                    'titulo_post' 	=> sql_htm($row['post_title']),
                    'fecha_post' 	=> $row['post_date'],
                    'descripcion_post'  => sql_htm($row['post_content']),
                    'url_post' 		=> $row['post_name']
                );
            }
        }
        return $data;
    }
}
?>