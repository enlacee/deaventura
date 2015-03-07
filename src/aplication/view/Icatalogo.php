<?php 
class Icatalogo extends Catalogo{

	private $k = 1;
	private $_archivo = "productos.php";
	private $_idioma;
	private $_cuenta;
	
	
	
	public function __construct(Idioma $idioma, $cuenta=''){
		$this->_idioma = $idioma ;
		$this->_cuenta = $cuenta ;
               
	}

	function listado(&$rows){

		$content = $this->Contenido();	
		

		$cats    = $content[0];
		$prods   = $content[1];
		$autores = $content[2];
                $libros  = $content[3];
		$desde   = $content[4];
		$hasta   = $content[5];
		$pag     = $content[6];
               

		$x = 0;		

		//suma de productos y categorias
		$rows = sizeof($cats) + sizeof($prods);

		//saco en cuantas paginas salen de la cantidad de actegorias, segun items por pagina
		$pagscat = ceil(sizeof($cats) / $this->_items_x_pagina);	

		//primer registro de categoria 
		$preg_cats = ($pag - 1) * $this->_items_x_pagina ;

		//si la pagina actual es menor o igual a las paginas de la categoria 
		if($pag <= $pagscat){	
			$y = 0;		
		}else{		
			$cat_rest = sizeof($cats) - $preg_cats;				
			$pagsprod = ceil(sizeof($prods) / $this->_items_x_pagina);	
			$preg_prods = ($pag-1)* $this->_items_x_pagina;
			$y = $cat_rest - ($cat_rest + $cat_rest);	
		}

		//si existe un array de items en categorias o productos 

		if(is_array($cats) || is_array($prods) || is_array($autores) || is_array($libros)){
			for($c = $desde; $c < $hasta; $c++){ 				 
				if($c < sizeof($cats)){ 
					echo $this->categoria($cats[$c]['id']); 
				}
                                if(isset($prods[$y]['id'])) {
					echo $this->producto($prods[$y]['id']);	
					$y++;
				}
                                if(isset($autores[$y]['id'])) {
                                    
                                        $array_aut = $this->getAutores_idp($autores[$y]['id']);
                                       	if(count($array_aut)>0)
										{
                                        foreach ($array_aut as $value):
                                            echo $this->producto($value['id']);
                            
                                        endforeach;
										}
										else
											echo "No se encontraron coinsidencias...";
					$y++;
					

                                	
				}


                                if(isset($libros[$y]['id'])) {

                                        $array_lib = $this->getLibros_idp($libros[$y]['id']);

                                        foreach ($array_lib as $value2):
                                            echo $this->producto($value2['id']);

                                        endforeach;
					$y++;



				}
                        
				$x++;				
			
                        
                        }
			if($this->k == 2 || $this->k == 3 || $this->k == 4){ echo ' '; }		
		}


	}	

		

	function cuerpo(){
	?>
		<div> 	<?php

		if(isset($_GET['prod']) && !empty($_GET['prod'])){				

			$this->detalle($_GET['prod']);

		}else{ 

			$rows = 0; 					

			$this->listado($rows); 
			
			$pagina = $_GET['pag'] - 1;
			?>			

			<div class="clear"></div>
            <input type="hidden" id="total_v" value="<?php echo $rows ?>" />
            <input type="hidden" id="desde_v" value="<?php echo (($pagina * 2) +1) ?>" />
            <input type="hidden" id="total_page" value="<?php echo ($pagina * 2) ?>" />
			<?php			
			if($rows > $this->_items_x_pagina){ $this->paginado($rows); } 
		} ?>

		</div> <?php 

	}

 	function producto($id){
		$producto = new Producto($id, $this->_idioma);
		$cu = new Cuenta($this->_cuenta);
			
		?>
		<div class="solucionario" style="height:156px">            	
       	    	<a href="solucionarios.php?prod=<?php echo $id?>"  class="personPopupTrigger" rel="<?php echo $id ?>,0"><img src="aplication/webroot/imgs/catalogo/<?php echo $producto->__get("_imagen")?>" width="54" height="80" alt="productos" /></a>
                <h1><a href="solucionarios.php?prod=<?php echo $id?>"><?php echo $producto->__get("_nombre") ?></a></h1>
                <p><?php echo $producto->__get("_autor")->__get("_nombre") ?></p>
                <p><?php echo $producto->__get("_libro")->__get("_titulo") ?></p>
                
                <?php 
						if($this->_cuenta->getCliente()->getLogeado()==true){
                           if($cu->MostraPrecio($this->_cuenta, $id) == 0){
                            ?>
                            <div class="precio_gratis">gratis</div>
                        <?php
						 
                            }else{ ?>
                            <div class="precio"><?php echo $cu->MostraPrecio($this->_cuenta, $id);?></div>
                            <?php
                            }
						}?>
            </div>
		<?php
		if($this->k%3==0){
			?><?php
		}
		$this->k++;
		
	}

	function categoria($id){ 
		$categoria = new Categoria($id, $this->_idioma);
		 ?>
        <div class="producto">
            <div class="photo"><a href="productos.php?cat=<?php echo $id; ?>">
					<?php
                    if($categoria->__get("_imagen") != ''){?>
                    <img src="<?php echo _img_file_;?>?imagen=<?php echo $categoria->__get("_imagen"); ?>&w=178&h=140"/>
                    <?php } ?>
           		</a>
            </div>
            <div class="name"><a href="productos.php?cat=<?php echo $id; ?>"><?php echo $categoria->__get("_nombre") ?></a></div>
        </div>
		<?php
       }


      

	function detalle($id){ 
	
		$producto   = new Producto($id, $this->_idioma);
		$imagenes   = $producto->__get("_imagenes");

	}
	

	function paginado($rows){
		
		$pag = isset($_GET['pag']) ? $_GET['pag'] : 1;  
		$url = basename($_SERVER['PHP_SELF'])."?";
		$url .= isset($_GET['cat']) ? "cat=".$_GET['cat']."&" : "";
		$url .= isset($_GET['q']) ? "q=".$_GET['q']."&" : "";
		$url .= isset($_GET['promociones']) ? "promociones&" : "";
		$url .= "pag=";	?>
		<div id="paginacion" align="right"><?php echo paginar_catalogo($pag, $rows, $this->_items_x_pagina, $url);?></div>
		<?php	
		
	}

	

	function navegacion(){  

		$navegador = new NavegadorFront($this->_idioma);
		$idp = isset($_GET['prod']) ? $_GET['prod'] : 0;
		$idc = isset($_GET['cat'])  ? $_GET['cat']  : 0;
		$id_actual = $idp > 0 ? $idp : $idc;
		$navegador->bucleCatTrail($idc, $idp);		
		return  $navegador->display($id_actual);

	}	
	
	function categoryActual(){  

		$navegador = new NavegadorFront();
		$idp = isset($_GET['j']) ? $_GET['j'] : 0;
		$idc = isset($_GET['cat'])  ? $_GET['cat']  : 0;
		$id_actual = $idp > 0 ? $idp : $idc;
		$navegador->bucleCatTrail($idc, $idp);		
		return  $navegador->dislplayCategoria();

	}


        

        function getAutores_idp($id=0){
		$query = new Consulta("SELECT id_producto FROM productos where id_autor='".$id."'");
		while($rowa = $query->VerRegistro()){
			$datos[] = array('id' 	 => $rowa['id_producto']);
		}

		return $datos;
	}

        function getLibros_idp($id=0){
		$query2 = new Consulta("SELECT id_producto FROM productos where id_libro='".$id."'");
		while($rowl = $query2->VerRegistro()){
			$datos[] = array('id' 	 => $rowl['id_producto']);
		}

		return $datos;
	}


} ?>