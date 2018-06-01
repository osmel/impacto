<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Salida_directa extends CI_Controller { //Claseprueba1

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('model_pedido_compra', 'model_pedido_compra'); 
		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('modelo', 'modelo');  
		$this->load->library(array('email')); 
        $this->load->library('Jquery_pagination');//-->la estrella del equipo	 	
        $this->load->library('miclase');

	}
////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////
				//salida_directa
	////////////////////////////////////////////////////////////
///mostrar el pedido
public function listado_salida_directa(){ 
		 if($this->session->userdata('session') === TRUE ){
		       $id_perfil=$this->session->userdata('id_perfil');
		       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
		                $data['coleccion_id_operaciones'] = array();
		       }	

		       //filtros 	       
		       $data['consecutivo']   = $this->catalogo->listado_consecutivo(4);  //consecutivo viejo
		       $data['val_proveedor'] = $this->modelo_pedido->valores_movimientos_temporal();//valor del cliente, cargador, factura,
		       $data['productos']     = $this->modelo_pedido->listado_productos_unico(); //para filtro de producto
		       $data['almacenes']     = $this->modelo->coger_catalogo_almacenes(2);   //para filtro de almacen
		       $data['pedidos']       = $this->catalogo->listado_tip_pedidos($data);  //para filtro de tipo_pedido
		       $data['facturas']   	  = $this->catalogo->catalogo_tipos_facturas();   //para filtro de tipo_factura
		       
		       
		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'salidas_pedidos/salida_directa',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:  //2-CLiente, 94->tienda, 95->bodega
		              if  ((in_array(2, $data['coleccion_id_operaciones'])) || (in_array(94, $data['coleccion_id_operaciones'])) || (in_array(95, $data['coleccion_id_operaciones'])) )   {                 
		                        $this->load->view( 'salidas_pedidos/salida_directa',$data );
		             }  else {
		          			redirect('');   	
		             } 
		          break;
		        default:  
		          redirect('');
		          break;
		      }
		    }
		    else{ 
		      redirect('');
		    }  		
}


	 //1ra regilla de "/salida_directa"
	public function procesando_entrada_directa(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_entrada_pedido($data);
		echo $busqueda;
	}

	//2da regilla de "/salida_directa"
	public function procesando_salida_directa(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_salida_pedido($data);
		echo $busqueda;
	}



	//agregar al pedido
	function agregar_salida_directa(){
	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {
	    	$dato['mensaje']='';
			  if ($this->input->post('id_cliente')) {
						$data['descripcion'] = $this->input->post('id_cliente');
						$data['idproveedor'] = "3";
					      switch ($this->input->post('on_off')) {    
					        case 0:  	//cliente normal        
					              $data['id_cliente'] =  $this->catalogo->checar_existente_proveedor($data);
					          break;
					        case 1:  	//tienda
					              $data['id_cliente'] =  $this->catalogo->checar_existente_tienda($data);
					          break;
					        case 2: 	//bodega
					              $data['id_cliente'] =  $this->catalogo->checar_existente_bodega($data);
					          break;
					      } //fin del case
						if (!($data['id_cliente'])){
							$dato['mensaje'] .= "El cliente no existe";
						}
			  } else {
				  	$data['id_cliente']=null;
				  	$dato['mensaje'] .=  "Campo <b>cliente</b> obligatorio. ";
			  }	 	


 				if ($this->input->post('id_cargador')) {
							$data['id_cargador'] =  $this->catalogo->check_existente_cargador_entrada($this->input->post('id_cargador'));
							if (!($data['id_cargador'])){
								$dato['mensaje'] .=  "El cargador no existe";
							}
				 } else {
				  	$data['id_cargador']=null;
				    $dato['mensaje'] .= "Campo <b>cargador</b> obligatorio. ";

				  }		        


			if  ( ($data['id_cliente']) && ($data['id_cargador']) ) {
		 		$data['id'] = $this->input->post('identificador');
		 		$data['id_movimiento'] = $this->input->post('movimiento');
		 		$data['movimiento_unico'] = $this->input->post('movimiento_unico');
		 		$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
		 		$data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
		 		$data['on_off'] = $this->input->post('on_off');

				$actualizar = $this->modelo_pedido->actualizar_pedido($data);
				$dato['exito']  = true;
			} else {      
	       		$dato['exito'] = validation_errors('<span class="error">','</span>');
	      	}		
			echo json_encode($dato);
		}	
    }


	//quitar_prod_salida
	function quitar_salida_directa(){
	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {
	 		$data['id'] = $this->input->post('identificador');
			$actualizar = $this->modelo_pedido->quitar_pedido($data);
			$dato['exito']  = true;
			echo json_encode($dato);
		}	
   }

   ///////////////////////////////////////////////////confirmacion de pedido

	//confirmacion pedido
	public function salida_directa_definitiva(){

		if($this->session->userdata('session') === TRUE ){
				//$datos['exito'] = validation_errors('<span class="error">','</span>');
				$datos['exito'] =false;	
		        $id_perfil=$this->session->userdata('id_perfil');


		      		         



  		        $data['num_mov'] = $this->input->post('num_mov'); //movimiento
  		        $data['id_tipo_pedido'] = $this->input->post('id_tipo_pedido');
  		        $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
				$data['factor_salida'] = $this->input->post('factor_salida'); //A,B,S,G
				$datos['descripcion'] = $this->input->post('cliente'); //proveedor

  		        $data['id_operacion_pedido'] = $this->input->post('id_operacion_pedido'); //pedido normal =4 , tienda=97, bodega=98
  		        //id_cargador 
			    $data['consecutivo_unico'] = $this->modelo_pedido->pedido_definitivamente($data);

				if ( $data['consecutivo_unico']  !== FALSE ){
					//actualizar el consecutivo único de pedido, por almacenes

					 $datos['almacenes'] = $this->modelo_pedido->actualizar_consecutivo_pedido_multiples_almacenes($data);
					 
					 //esto es para imprimir pedido
					 //$datos['movimientos'] = $this->modelo_pedido->imprimir_pedido_definitivamente($data);
				     //$datos['totales'] = $this->modelo_pedido->imprimir_total_campos($data);  
					 $datos['campos'] = $this->modelo_pedido->campos($data);

					$datos['exito'] =true;	
				} else {
					$datos['exito'] = '<span class="error">No se han podido apartar los productos</span>';
				}

		
		} else {      
			
   			 $datos['exito'] = validation_errors('<span class="error">','</span>');

  		}		

  		echo json_encode($datos);
	
	}	





/////////////////////////////Hasta aqui la regilla de INICIO ///////////////////////////////////////

	public function imprimir_reportes_pedido(){

		 if($this->session->userdata('session') === TRUE ){
		 		
			    $misdatos = json_decode($this->input->post('datos'));
			    
		 				    
                $data['almacenes'] 	 = json_decode($misdatos->almacenes,true);
			    $data['movimientos'] = ($misdatos->movimientos);
                $data['totales'] 	 = ($misdatos->totales);
                $data['descripcion'] = $misdatos->descripcion;

			    $dato['id'] = 7;
                $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 


                $html = $this->load->view('pdfs/apartados/informe_pedido', $data, true);


			   		set_time_limit(0); 
			        ignore_user_abort(1);
			        ini_set('memory_limit','512M'); 

			        $this->load->library('Pdf');
			        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
			        $pdf->SetCreator(PDF_CREATOR);
			        $pdf->SetTitle('Titulo Generación de Etiqueta');
			        $pdf->SetSubject('Subtitulo');
			        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			 
			        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			 

			        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			 
			        $pdf->setFontSubsetting(true);

			        
			        $pdf->SetFont('Times', '', 8,'','true');

			 
			        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
			 
			        $pdf->setPrintHeader(false);
			        $pdf->setPrintFooter(false);

			        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			        $pdf->SetMargins(10, 10, 10,true);
			        
			        $pdf->SetAutoPageBreak(true, 10);

			        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



			        
			        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
			        $nombre_archivo = utf8_decode("informe".".pdf");
			        $pdf->Output($nombre_archivo, 'I');




				
		  
		}
	}	



  //cuando selecciona los filtros  de producto, composicion, ancho, color, proveedor de generar_pedido	
 function cargar_dependencia_pedido() {
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_prod_id']        = $this->input->post('val_prod_id');

    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_ancho']        = (float)$this->input->post('val_ancho');
    $data['val_ancho_cad']        = $this->input->post('val_ancho');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_proveedor']        = $this->input->post('val_proveedor');

    $data['dependencia']        = $this->input->post('dependencia');


			$elementos['producto_pedido']  = $this->modelo_pedido->listado_productos_completa($data);
        	$elementos['composicion_pedido']  = $this->modelo_pedido->lista_composiciones_completa($data);
            $elementos['ancho_pedido']  = $this->modelo_pedido->lista_ancho_completa($data);
            $elementos['color_pedido']  = $this->modelo_pedido->lista_colores_completa($data);
            $elementos['proveedor_pedido']  = $this->modelo_pedido->lista_proveedores_completa($data);

    echo json_encode($elementos); 


  }


////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
				// <| pedido     http://inventarios.dev.com/pedidos
////////////////////////////////////////////////////////////
	//muestra las 3 regillas de "/pedidos"
	public function listado_apartados(){
		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
		       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
		                $data['coleccion_id_operaciones'] = array();
		       }		       $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);

		       $data['facturas']   	= $this->catalogo->catalogo_tipos_facturas();
		       $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');
		       
		       //no. movimiento $data

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'pedidos/pedidos' ,$data );     
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(10, $data['coleccion_id_operaciones']))  {            
		              			$this->load->view( 'pedidos/pedidos' ,$data );     
		              } else {
		              	redirect('');
		              }   
		          break;
		        default:  
		          redirect('');
		          break;
		      } //fin del case
		}
		else{ 
		  redirect('');
		}  		
		
	}


	//1ra Regilla PARA "Pedidos de vendedores"
	public function procesando_apartado_pendiente(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_apartados_pendientes($data);
		echo $busqueda;
	}	

	//2da Regilla PARA "Pedidos de tiendas"
	public function procesando_pedido_pendiente(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_pedidos_pendientes($data);
		echo $busqueda;
	}


	//3ra Regilla PARA "Histórico de Pedidos"
	public function procesando_pedido_completo(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_pedidos_completo($data);
		echo $busqueda;
	}



	 //listado detalle 1ra Regilla PARA "Pedidos de vendedores"
	public function apartado_detalle($num_mov,$id_almacen,$id_operacion_pedido){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }		       
		       //no. movimiento $data
		       	//$data['id_usuario'] = base64_decode($id_usuario);
				$data['num_mov'] = base64_decode($num_mov);
				$data['id_almacen'] = base64_decode($id_almacen);
				$data['id_operacion_pedido'] = base64_decode($id_operacion_pedido);
				

				$data['id']=$data['id_almacen'];
				if ($data['id']==0){
					$data['almacen'] = 'Todos';	
				} else {
					$data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
				}

				//$data['consecutivo_venta'] = base64_decode($consecutivo_venta);				
				


		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'pedidos/apartado_detalle',$data);   
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(10, $data['coleccion_id_operaciones']))  {            
		              			$this->load->view( 'pedidos/apartado_detalle',$data);
		              } else {
		              	redirect('');
		              }   
		          break;
		        default:  
		          redirect('');
		          break;
		      } //fin del case
		}
		else{ 
		  redirect('');
		}  		


	}


 	//detalle "Regilla" de la 2da PARA  "Pedidos de tiendas" 

	public function pedido_detalle($num_mov,$id_almacen,$id_operacion_pedido){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }		       
		       //no. movimiento $data
				$data['num_mov'] = base64_decode($num_mov);
				$data['id_almacen'] = base64_decode($id_almacen);
				$data['id_operacion_pedido'] = base64_decode($id_operacion_pedido);


				$data['id']=$data['id_almacen'];
				if ($data['id']==0){
					$data['almacen'] = 'Todos';	
				} else {
					$data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
				}


		      switch ($id_perfil) {    
		        case 1:          
		                   $this->load->view( 'pedidos/pedido_detalle',$data);
		          break;
		        case 2:
		        case 3:
		        case 4:  //todos tienen derecho a verlo, si tienen marcado el 10(mostrar el pedido)
		              if  (in_array(10, $data['coleccion_id_operaciones']))  {            
		              	  $this->load->view( 'pedidos/pedido_detalle',$data);
		              } else {
		              	redirect('');
		              }   
		          break;
		        default:  
		          redirect('');
		          break;
		      } //fin del case
		}
		else{ 
		  redirect('');
		}  		
	}

    //listado detalle "Regilla" de la 3ra PARA "Histórico de Pedidos"
	public function pedido_completado_detalle($mov_salida_unico,$id_almacen,$id_operacion_salida){


		if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }		       
		       //no. movimiento $data
				$data['mov_salida_unico'] = base64_decode($mov_salida_unico);
				$data['id_almacen'] = base64_decode($id_almacen);
				$data['id_operacion_salida'] = base64_decode($id_operacion_salida);
				

				$data['id']=$data['id_almacen'];
				if ($data['id']==0){
					$data['almacen'] = 'Todos';	
				} else {
					$data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
				}

				//$data['consecutivo_venta'] = base64_decode($consecutivo_venta);				

		      switch ($id_perfil) {    
		        case 1:          
		                   $this->load->view( 'pedidos/pedido_completo_detalle',$data);
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array(10, $data['coleccion_id_operaciones']))  {            
		              	  $this->load->view( 'pedidos/pedido_completo_detalle',$data);
		              } else {
		              	redirect('');
		              }   
		          break;
		        default:  
		          redirect('');
		          break;
		      } //fin del case
		}
		else{ 
		  redirect('');
		}  		



	}


	//detalle 1ra Regilla PARA "Pedidos de vendedores"
	public function procesando_detalle(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
    	if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
            $data['coleccion_id_operaciones'] = array();
        } 
		$busqueda = $this->modelo_pedido->buscador_apartados_detalle($data);
		echo $busqueda;
	}

    //detalle "Regilla" de la 2da PARA  "Pedidos de tiendas" 
	public function procesando_pedido_detalle(){
		$data=$_POST;

		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 

		$busqueda = $this->modelo_pedido->buscador_pedido_especifico($data);
		echo $busqueda;
	}

    //detalle "Regilla" de la 3ra PARA "Histórico de Pedidos"
	public function procesando_completo_detalle(){
		$data=$_POST;
		$data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	      if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	            $data['coleccion_id_operaciones'] = array();
	       } 
		$busqueda = $this->modelo_pedido->buscador_completo_especifico($data);
		echo $busqueda;
	}





	//Eliminar 1ra Regilla PARA "Pedidos de vendedores"
	
	//public function eliminar_apartado_detalle($id_usuario,$id_cliente,$id_almacen,$consecutivo_venta,$id_operacion_pedido){
	public function eliminar_apartado_detalle($num_mov,$id_almacen,$id_operacion_pedido){


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           $data['num_mov'] = base64_decode($num_mov);
		   $data['id_almacen'] = base64_decode($id_almacen);
		   $data['id_operacion_pedido'] = base64_decode($id_operacion_pedido);

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   

				
          switch ($id_perfil) {    
            case 1:
                      $this->load->view( 'pedidos/eliminar_apartado', $data );                
              break;
            case 2:
            case 3:
            case 4:
                 if  (in_array(10, $data['coleccion_id_operaciones']))  { 
	                      $this->load->view( 'pedidos/eliminar_apartado', $data );
                 } else {
		              	redirect('');
		         }   
              break;
            default:  
              redirect('');
              break;
          }
        }
        else{ 
          redirect('');
        }
		
	}



	function validar_eliminar_apartado_detalle(){
		$data['num_mov'] = $this->input->post('num_mov');
		$data['id_almacen'] = $this->input->post('id_almacen');
		$data['id_operacion_pedido'] = $this->input->post('id_operacion_pedido');


		$this->modelo_pedido->cancelar_traspaso_apartado_detalle($data);

		$cancelar = $this->modelo_pedido->cancelar_apartados_detalle($data);
		if ( $cancelar !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar al usuario</span>';
		}
	}	



	//Eliminar "Regilla" de la 2da PARA  "Pedidos de tiendas" 
	public function eliminar_pedido_detalle($num_mov,$id_almacen,$id_operacion_pedido){	


	    if ($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');
			
		   $data['num_mov'] = base64_decode($num_mov);
		   $data['id_almacen'] = base64_decode($id_almacen);
		   $data['id_operacion_pedido'] = base64_decode($id_operacion_pedido);			
				
		   


       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   

          switch ($id_perfil) {    
            case 1:
                      $this->load->view( 'pedidos/eliminar_pedido', $data );                
              break;
            case 2:
            case 3:
            case 4:
                 if  (in_array(10, $data['coleccion_id_operaciones']))  { 
	                      $this->load->view( 'pedidos/eliminar_pedido', $data );
                 } else {
		              	redirect('');
		         }   
 
              break;


            default:  
              redirect('');
              break;
          }
        }
        else{ 
          redirect('');
        }
		
	}


	function validar_eliminar_pedido_detalle(){
		$data['num_mov'] = $this->input->post('num_mov');
		$data['id_almacen'] = $this->input->post('id_almacen');
		$data['id_operacion_pedido'] = $this->input->post('id_operacion_pedido');
		
				$this->modelo_pedido->cancelar_traspaso_pedido_detalle($data);
				
		$cancelar = $this->modelo_pedido->cancelar_pedido_detalle($data);
		if ( $cancelar !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar al usuario</span>';
		}
	}







////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////
				// <| pedido     http://inventarios.dev.com/pedidos
////////////////////////////////////////////////////////////




	function marcando_prorroga_venta(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario_apartado'] = base64_decode($this->input->post('id_usuario_apartado'));
	    	$data['id_cliente_apartado'] = base64_decode($this->input->post('id_cliente_apartado'));
	    			 $data['id_almacen'] = $this->input->post('id_almacen');
	    	  $data['consecutivo_venta'] = base64_decode($this->input->post('consecutivo_venta'));

	    	$actualizar = $this->modelo_pedido->marcando_prorroga_venta($data);

	    	echo  $actualizar;

		}	
   }



	function marcando_prorroga_tienda(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_cliente_apartado'] = base64_decode($this->input->post('id_cliente_apartado'));
	    			 $data['id_almacen'] = $this->input->post('id_almacen');

	    	$actualizar = $this->modelo_pedido->marcando_prorroga_tienda($data);

	    	echo  $actualizar;

		}	
   }


	//////////////////////////Incluir pedido a la salida///////////////////////////////////

	function incluir_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['num_mov'] = $this->input->post('num_mov');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['id_apartado'] = 6;

	    	$data['id_tipo_pedido']  = $this->input->post('id_tipo_pedido');
	    	$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

	    	$actualizar = $this->modelo_pedido->incluir_pedido($data);

	    	if ($data['id_tipo_factura']!=0) {
	    		$this->modelo_pedido->traspaso_pedido($data);
	    	}
	    	
	    	

	    	echo  json_encode($actualizar);

		}	
   }


	function excluir_pedido(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

			$data['num_mov'] = $this->input->post('num_mov');
			$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['id_apartado'] = 5;

	    	$actualizar = $this->modelo_pedido->incluir_pedido($data);

	    	echo  json_encode($actualizar);

		}	
   }






	    	

//////////////////////////Incluir  y excluir ya no se tienen en cuenta esto es antiguo///////////////////////////////////

	function incluir_apartado(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario'] = $this->input->post('id_usuario');
	    	$data['id_cliente'] = $this->input->post('id_cliente');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['consecutivo_venta'] = $this->input->post('consecutivo_venta');
	    	$data['id_apartado'] = 3;

	    	$data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

	    	$actualizar = $this->modelo_pedido->incluir_apartado($data);

	    	

	    	if ($data['id_tipo_factura']!=0) {
	    		$this->modelo_pedido->traspaso_apartado($data);
	    	}

	    	echo  json_encode($actualizar);

		}	
   }



	function excluir_apartado(){

	    if ($this->session->userdata('session') !== TRUE) {
	      redirect('');
	    } else {

	    	$data['id_usuario'] = $this->input->post('id_usuario');
	    	$data['id_cliente'] = $this->input->post('id_cliente');
	    	$data['id_almacen'] = $this->input->post('id_almacen');
	    	$data['consecutivo_venta'] = $this->input->post('consecutivo_venta');
	    	$data['id_apartado'] = 2;

	    	$actualizar = $this->modelo_pedido->incluir_apartado($data);

	    	echo  json_encode($actualizar);

		}	
   }



public function conteo_tienda(){


          if ($this->session->userdata('id_almacen') != 0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$this->session->userdata('id_almacen').' ) ';  
          } else {
              $id_almacenid = '';
          } 

          $perfil= $this->session->userdata('id_perfil'); 
          $id_session = $this->session->userdata('id');
          
         if ( ( $perfil == 3 ) OR ( $perfil == 4 ) ) { 
            $restriccion  =' AND (m.id_usuario_apartado = "'.$id_session.'")';
         } else {
         	$restriccion = '';
         }



          
         if (  $perfil != 4 ) {
	     		$where_total = '(( m.id_apartado = 2 ) or ( m.id_apartado = 3 ))'.$id_almacenid.$restriccion;
				$dato['vendedor'] = (string)$this->modelo_pedido->total_apartados_pendientes($where_total);
         } else {
         	$dato['vendedor'] ="0";
         }

        if (  $perfil != 3 ) {
			$where_total = '(( m.id_apartado = 5 ) or ( m.id_apartado = 6 ))'.$id_almacenid.$restriccion;
			$dato['tienda'] = (string)$this->modelo_pedido->total_pedidos_pendientes($where_total);  
         } else {
         	$dato['tienda'] ="0";
         }


         	//$data['modulo']=$this->session->userdata('id_perfil'); 
         	$data['modulo'] = ($this->session->userdata('id_perfil')!=2) ? 1: 2; 
         	$dato['compra'] = (string)$this->model_pedido_compra->notificador_pedido_compra($data);  
	
			echo  json_encode($dato);
		}	



}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */