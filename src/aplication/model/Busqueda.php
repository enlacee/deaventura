<?php

class Busqueda {

    function __construct() {
        
    }

    public function buscar($q) {
        if ($q != "") {
            $q = str_replace("-", " ", $q);
            //Se declara el array busquedas para retornar los resultados
            //-----------------------
            //--------Tags-----------
            //-----------------------

            $cant_tags = 0;
            //Rutas
            $sql = "SELECT nombre_lugar, descripcion_ruta, ubicacion_lugar, r.image, d.nombre_deporte
                    FROM rutas_tags rt
                    INNER JOIN deportes d on rt.id_deporte=d.id_deporte
                    INNER JOIN tags t on t.id_tag=rt.id_tag
                    INNER JOIN rutas r on r.id_lugar=rt.id_lugar
                    INNER JOIN lugares l on l.id_lugar=rt.id_lugar
                    WHERE t.nombre_tag = '" . htm_sql($q) . "' 
                    AND r.id_deporte = (SELECT id_deporte FROM tags WHERE nombre_tag = '" . htm_sql($q) . "')
                    GROUP BY r.id_ruta    
                    ";
            $consulta = new Consulta($sql);
            $tags_rutas = array();
            $num_rutas = $consulta->NumeroRegistros();
            if ($num_rutas > 0) {
                while ($row = $consulta->VerRegistro()) {
                    $tags_rutas[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_lugar']),
                        'descripcion_tipo' => sql_htm($row['descripcion_ruta']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image'],
                        'tipo_seccion' => 'rutas'
                    );
                }
                $cant_tags += $num_rutas;
            }

            //Agencias
            $sql = "SELECT nombre_agencia, descripcion_agencia, a.image, nombre_deporte
                    FROM agencias_tags 
                    INNER JOIN tags t USING(id_tag)
                    INNER JOIN agencias a USING(id_agencia)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE t.nombre_tag = '" . htm_sql($q) . "'";

            $consulta = new Consulta($sql);
            $tags_agen = array();
            $num_agen = $consulta->NumeroRegistros();
            if ($consulta->NumeroRegistros() > 0) {
                while ($row = $consulta->VerRegistro()) {
                    $tags_agen[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_agencia']),
                        'descripcion_tipo' => sql_htm($row['descripcion_agencia']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image'],
                        'tipo_seccion' => 'agencias'
                    );
                }

                $cant_tags += $num_agen;
            }


            //Clubes u organizacion
            $sql = "SELECT nombre_organizacion, descripcion_organizacion, o.image, nombre_deporte
                    FROM organizaciones_tags 
                    INNER JOIN tags t USING(id_tag)
                    INNER JOIN organizaciones o USING(id_organizacion)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE t.nombre_tag = '" . htm_sql($q) . "'";

            $consulta = new Consulta($sql);
            $tags_org = array();
            $num_org = $consulta->NumeroRegistros();
            if ($consulta->NumeroRegistros() > 0) {
                while ($row = $consulta->VerRegistro()) {
                    $tags_org[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_organizacion']),
                        'descripcion_tipo' => sql_htm($row['descripcion_organizacion']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image'],
                        'tipo_seccion' => 'clubes'
                    );
                }

                $cant_tags += $num_org;
            }

            //Tiendas o proveedores
            $sql = "SELECT nombre_proveedor, descripcion_proveedor, p.image, nombre_deporte
                    FROM proveedores_tags 
                    INNER JOIN tags t USING(id_tag)
                    INNER JOIN proveedores p USING(id_proveedor)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE t.nombre_tag = '" . htm_sql($q) . "'";

            $consulta = new Consulta($sql);
            $tags_prov = array();
            $num_prov = $consulta->NumeroRegistros();
            if ($consulta->NumeroRegistros() > 0) {
                while ($row = $consulta->VerRegistro()) {
                    $tags_prov[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_proveedor']),
                        'descripcion_tipo' => sql_htm($row['descripcion_proveedor']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image'],
                        'tipo_seccion' => 'tiendas'
                    );
                }
                $cant_tags += $num_prov;
            }



            //-----------------------------
            //--------Seccciones-----------
            //-----------------------------
            //Rutas
            $sql = "SELECT nombre_lugar, descripcion_ruta, ubicacion_lugar, r.image, nombre_deporte
                    FROM rutas r INNER JOIN lugares USING(id_lugar)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE nombre_lugar LIKE '%" . htm_sql($q) . "%' 
					OR descripcion_ruta LIKE '%" . htm_sql($q) . "%' 
					OR tags like '%".($q)."%'";
			
			//echo $sql;
            $consulta = new Consulta($sql);
            if ($consulta->NumeroRegistros() > 0) {
                $rutas = array('Rutas', $consulta->NumeroRegistros());
                while ($row = $consulta->VerRegistro()) {
                    $rutas[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_lugar']),
                        'descripcion_tipo' => sql_htm($row['descripcion_ruta']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image']
                    );
                }
            }

            //Clubes u organizaciones
            $sql = "SELECT nombre_organizacion, descripcion_organizacion, o.image, nombre_deporte
                    FROM deportes_organizaciones INNER JOIN organizaciones o USING(id_organizacion)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE nombre_organizacion LIKE '%" . htm_sql($q) . "%' OR descripcion_organizacion LIKE '%" . htm_sql($q) . "%' GROUP BY id_organizacion";

            $consulta = new Consulta($sql);
            if ($consulta->NumeroRegistros() > 0) {
                $organizacion = array('Clubes', $consulta->NumeroRegistros());
                while ($row = $consulta->VerRegistro()) {
                    $organizacion[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_organizacion']),
                        'descripcion_tipo' => sql_htm($row['descripcion_organizacion']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image']
                    );
                }
            }

            //Tiendas o proveedores
            $sql = "SELECT nombre_proveedor, descripcion_proveedor, p.image, nombre_deporte
                    FROM deportes_proveedores INNER JOIN proveedores p USING(id_proveedor)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE nombre_proveedor LIKE '%" . htm_sql($q) . "%' OR descripcion_proveedor LIKE '%" . htm_sql($q) . "%' GROUP BY id_proveedor";

            $consulta = new Consulta($sql);
            if ($consulta->NumeroRegistros() > 0) {
                $proveedor = array('Tiendas', $consulta->NumeroRegistros());
                while ($row = $consulta->VerRegistro()) {
                    $proveedor[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_proveedor']),
                        'descripcion_tipo' => sql_htm($row['descripcion_proveedor']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image']
                    );
                }
            }

            //Agencias
            $sql = "SELECT nombre_agencia, descripcion_agencia, a.image, nombre_deporte
                    FROM deportes_agencias INNER JOIN agencias a USING(id_agencia)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE nombre_agencia LIKE '%" . htm_sql($q) . "%' OR descripcion_agencia LIKE '%" . htm_sql($q) . "%' GROUP BY id_agencia";

            $consulta = new Consulta($sql);
            if ($consulta->NumeroRegistros() > 0) {
                $agencias = array('Agencias', $consulta->NumeroRegistros());
                while ($row = $consulta->VerRegistro()) {
                    $agencias[] = array(
                        'nombre_tipo' => sql_htm($row['nombre_agencia']),
                        'descripcion_tipo' => sql_htm($row['descripcion_agencia']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image']
                    );
                }
            }
			
			//Eventos
            $sql = "SELECT titulo_evento, descripcion_evento, lugar_evento, e.image, nombre_deporte
                    FROM eventos e INNER JOIN deportes_eventos USING(id_evento)
                    INNER JOIN deportes USING(id_deporte)
                    WHERE titulo_evento LIKE '%" . htm_sql($q) . "%' 
					OR descripcion_evento LIKE '%" . htm_sql($q) . "%' 
					OR lugar_evento LIKE '%" . htm_sql($q) . "%' 
					OR tags like '%".($q)."%'";
			
			//echo $sql;
            $consulta = new Consulta($sql);
            if ($consulta->NumeroRegistros() > 0) {
                $eventos = array('Eventos', $consulta->NumeroRegistros());
                while ($row = $consulta->VerRegistro()) {
                    $eventos[] = array(
                        'nombre_tipo' => sql_htm($row['titulo_evento']),
                        'descripcion_tipo' => sql_htm($row['descripcion_evento']),
                        'nombre_deporte' => sql_htm($row['nombre_deporte']),
                        'image' => $row['image']
                    );
                }
				
            }



            if (isset($tags_rutas) || isset($tags_agen) || isset($tags_org) || isset($tags_prov)) {
                $tags = array_merge($tags_rutas, $tags_agen, $tags_org, $tags_prov);
                array_unshift($tags, 'Tags', $cant_tags);
            }

            //array_merge($eventos,$organizacion,$proveedor);
            if (isset($rutas) || isset($organizacion) || isset($proveedor) || isset($agencias) || !empty($tags) || !empty($eventos)) {
                return array($tags, $rutas, $organizacion, $proveedor, $agencias, $eventos);
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function generadorUrl($tipo, $nom_depor, $nom_tipo) {
        if (strtolower($tipo) == "rutas") {
            $url = $tipo . '-de-' . $nom_depor . '/' . $nom_depor . '-en-' . url_friendly($nom_tipo, 1);
            $url = strtolower($url);
            $titulo = ucfirst($nom_depor.' en '.$nom_tipo);
            //print_r(array($titulo, $url));
            return array($titulo, $url);
        } else {
            $url = $tipo . '-de-' . $nom_depor . '/' . url_friendly($nom_tipo, 1);
            $url = strtolower($url);
            $titulo = ucfirst($nom_tipo);
            //print_r(array($titulo, $url));
            return array($titulo, $url);
        }
    }

}

?>
