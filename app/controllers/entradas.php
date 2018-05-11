<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entradas extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('model_pedido_compra', 'model_pedido_compra'); 
		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('model_entradas', 'model_entrada');  
		$this->load->model('modelo', 'modelo'); 

		$this->load->library(array('email')); 
		$this->load->library('Jquery_pagination');//-->la estrella del equipo	
	}


public function consecutivo_entrada(){
	$data=$_POST;
	$data['conse_general']  	= $this->catalogo->consecutivo_general($data);
	echo json_encode($data['conse_general']);

}	

//***********************Todos los recepciones**********************************//
	

	//1- mostrar las entradas
	public function listado_entradas(){
		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }
       	       	$data['medidas']        = $this->catalogo->listado_medidas();
		       	$data['estatuss']   	= $this->catalogo->listado_estatus(-1,-1,'1');
		       	$data['lotes']      	= $this->catalogo->listado_lotes(-1,-1,'1');

		       	$data['consecutivo']  	= $this->catalogo->listado_consecutivo(1);


		       	$data['movimientos']  	= $this->model_entrada->listado_movimientos_temporal();
		       	$data['val_proveedor']  = $this->model_entrada->valores_movimientos_temporal();
		       	//print_r($data['val_proveedor']); die;

		       	$data['id_operacion']  =  1; //entrada
		       	if  ($data['val_proveedor']) {
		       		$data['conse_general']  	=$data['val_proveedor'];
		       	} else {

			       	  
			        	$data['id_almacen']  =  1; //bod.1
			       	    $data['id_factura']  =  1;  //factura
			       	     $data['id_pedido']  =  0; //no tiene pedido
				     	 $data['conse_general']  	= $this->catalogo->consecutivo_general($data);
		       	}



		       	$data['productos']   	= $this->catalogo->listado_productos_unico_activo();
    	        $data['almacenes']   	= $this->modelo->coger_catalogo_almacenes(2);
    	        $data['facturas']   	= $this->catalogo->catalogo_tipos_facturas();
    	        $data['pagos']   		= $this->catalogo->listado_tipos_pagos();

 				$dato['id'] = 7;
		      	$data['configuracion'] = $this->catalogo->coger_configuracion($dato); 


		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'entradas/entradas',$data );
		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array($data['id_operacion'], $data['coleccion_id_operaciones']))  {                 
		                        $this->load->view( 'entradas/entradas',$data );
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




  //2- esto es para agregar los productos a temporal
  function validar_agregar_producto(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
 	
	 	 if ($this->input->post('editar_proveedor')) {
	 	 	     
	 	 	     $data['descripcion'] = ltrim($this->input->post('editar_proveedor'));
	 	 	     $data['idproveedor'] = "1";

				$data['id_proveedor'] =  $this->catalogo->checar_existente_proveedor($data);
				if (!($data['id_proveedor'])){
					print "el proveedor no existe";
				}
	     }


		$d_conf['id'] = 7;
		$d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

		if (($d_conf['configuracion']->activo==1)) {  
			$this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');	

			$data['fact_revision']   = $this->input->post('factura');

			$existe_factura = $this->model_entrada->existencia_factura($data);

			if (!($existe_factura)) {
					print "El número de factura ya existe";
			}

		}	

      $this->form_validation->set_rules( 'editar_proveedor', 'Proveedor', 'required|xss_clean'); //callback_valid_option|
      
      
      //$this->form_validation->set_rules( 'prod_entrada', 'Producto', 'required|xss_clean'); //callback_valid_option
      $this->form_validation->set_rules( 'producto', 'Producto', 'required|callback_check_default'); //callback_valid_option
      $this->form_validation->set_rules( 'color', 'Color', 'required|callback_check_default'); 
      $this->form_validation->set_rules( 'composicion', 'Composición', 'required|callback_check_default');
      $this->form_validation->set_rules( 'calidad', 'Calidad', 'required|callback_check_default');
      
      $this->form_validation->set_rules( 'peso_real', 'Peso Real',  'required|callback_valid_cero|callback_importe_valido|xss_clean');
      $this->form_validation->set_rules( 'cantidad_um', 'Cantidad',  'required|callback_valid_cero|callback_importe_valido|xss_clean');
      $this->form_validation->set_rules( 'cantidad_royo', 'Cantidad por Rollo',  'required|is_natural_no_zero|xss_clean');
      $this->form_validation->set_rules( 'ancho', 'Ancho',  'required|callback_valid_cero|callback_importe_valido|xss_clean');


	  $this->form_validation->set_rules( 'precio', 'Precio', 'required|callback_importe_valido|xss_clean');

	  
	  $this->form_validation->set_rules( 'num_partida', 'No. de Partida', 'required|trim|min_length[1]|max_lenght[180]|xss_clean');       

	  $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|min_length[3]|max_lenght[180]|xss_clean');       



      if (($this->form_validation->run() === TRUE) and ($data['id_proveedor']) and ($existe_factura) ) {
          
			if (($d_conf['configuracion']->activo==1)) {  
				$data['factura']   = $this->input->post('factura');
			}	

          $data['id_empresa']   = $data['id_proveedor'];
          $data['fecha']   = $this->input->post('fecha');
          $data['movimiento']   = $this->input->post('movimiento');
          $data['movimiento_unico']   = $this->input->post('movimiento_unico');
          
          $data['id_almacen']   = $this->input->post('id_almacen');
          $data['id_factura']   = $this->input->post('id_factura');

          if ($data['id_factura'] ==1) {
	         $data['iva'] = $this->catalogo->remision_iva(2)->valor;
          } else {
          	$data['iva'] = 0;
          } 



          $data['id_tipo_pago']   = $this->input->post('id_tipo_pago');
          

          $data['id_descripcion']   = $this->input->post('prod_entrada');
          $data['id_color']   = $this->input->post('color');
          $data['id_composicion']   = $this->input->post('composicion');
          $data['id_calidad']   = $this->input->post('calidad');
          $data['referencia']   = $this->input->post('referencia');

          $data['id_medida']   = $this->input->post('id_medida');

          $data['peso_real']   = $this->input->post('peso_real');
          $data['cantidad_um']   = $this->input->post('cantidad_um');
          $data['cantidad_royo']   = intval($this->input->post('cantidad_royo'));
          $data['ancho']   = $this->input->post('ancho');
          $data['precio']   = $this->input->post('precio');

          $data['num_partida']   = $this->input->post('num_partida');
          
          $data['comentario']   = $this->input->post('comentario');

          $data['codigo']   = $this->input->post('codigo');
          $data['id_estatus']   = $this->input->post('id_estatus');

          $data['id_lote']   		= $this->input->post('id_lote');


          $data['c1']   		= $this->input->post('c1');
          $data['c2']   		= $this->input->post('c2');
          $data['c1234']   		= $this->input->post('c1234');
          $data['c234']   		= $this->input->post('c234');
          $data['c34']   		= $this->input->post('c34');

          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->model_entrada->anadir_producto_temporal( $data );
          //print_r($guardar); die;
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - El producto no pudo ser agregado</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }
  

	//3- Esta es la Regilla de los productos
	public function procesando_productos_temporales(){
		$data=$_POST;
		$busqueda = $this->model_entrada->buscador_productos_temporales($data);
		echo $busqueda;
	}	

/////////////////////
	//4- Eliminar los productos desde la regilla
	public function eliminar_prod_temporal($id = '', $nombrecompleto=''){
	    if ( $this->session->userdata('session') !== TRUE ) {
	      redirect('');
	    } else {
	      $id_perfil=$this->session->userdata('id_perfil');

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 

			$data['nombrecompleto'] 	= base64_decode($nombrecompleto);
			$data['id'] 				= $id;
			$data['id_operacion']= 1;
	 
	      switch ($id_perfil) {    
	        case 1:
					$this->load->view( 'entradas/temporales/eliminar_producto', $data );

	          break;
	        case 2:
	        case 3:
	        case 4:
	             if  (in_array($data['id_operacion'], $data['coleccion_id_operaciones']))  { 

					$this->load->view( 'entradas/temporales/eliminar_producto', $data );
	                 
	              }  else  {
	                redirect('');
	              } 
	          break;
	        default:  
	          redirect('');
	          break;
	      }
	   }   
	}

	//5- quien valida la eliminación
	function validar_eliminar_prod_temporal(){
		if (!empty($_POST['id'])){ 
			$data['id'] = $_POST['id'];
		}

		$dato = $this->model_entrada->valores_reordenar($data );
		
		$eliminado = $this->model_entrada->eliminar_prod_temporal(  $data );
		$reordenar = $this->model_entrada->reordenar_prod_temporal( $dato );

		
		if ( $eliminado !== FALSE ){
			echo TRUE;
		} else {
			echo '<span class="error">No se ha podido eliminar la recepcion</span>';
		}
	}	

/////////////////////
	//validando si se puede procesar la entrada, en caso de que se puede se procesa

	public function validar_proceso(){ 
		
		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');


              
                
              $data['id_operacion'] =1;	     
              $data['id_pedido']  =  0; //no tiene pedido
		      $data['id_almacen']   = $this->input->post('id_almacen');
		      $data['id_factura']   = $this->input->post('id_factura');
		      $data['id_estatus']   = 0; //$this->input->post('id_estatus');

		      $data['dev'] = 0; 

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 
		      //si existe elemento en la tabla temporal
		      $existe = $this->model_entrada->existencia_temporales();
		     
		      if (($existe)) {


		      		//copiar a tabla "registros" e "historico_registros_entradas"
		      		
	      			$data['num_mov'] = $this->model_entrada->procesando_operacion($data);
	      			
			        $this->load->library('ciqrcode');
			        //hacemos configuraciones

			        $data['tipo_entrada'] = 'E';
					$data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);

			        //print_r($data['movimientos']); die;
			        foreach ($data['movimientos'] as $key => $value) {
			          
				        $params['data'] = $value->codigo;
				        $params['level'] = 'H';
				        $params['size'] = 30;
				        $params['savename'] = FCPATH.'qr_code/'.$value->codigo.'.png';
				        $this->ciqrcode->generate($params);    
				      
			        }

						$data['exito']  = true;
						echo json_encode($data);


			  } else { 

					$data['exito']  = false;
					$data['error'] = '<span class="error">No existen producto seleccionado.</span>';
					echo json_encode($data);

		          
			  }  

			      
		    }
		    else{ 
		      redirect('');
		    }  
	}


	//para imprimir la factura despues de procesada
	public function procesar_entrar($num_mov,$id_factura,$id_estatus){ 

		 if($this->session->userdata('session') === TRUE ){

			$data['dev'] = 0;

 			  $porciones = explode("-", base64_decode($num_mov));
		      $data['tipo_entrada'] = $porciones[0];
		      $data['num_mov'] = $porciones[1];
		      
			$data['id_factura'] = base64_decode($id_factura);
			//$data['id_estatus'] = base64_decode($id_estatus);
			 $data['id_estatus']   = 0; 
			 $data['id_operacion']= 1;

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }		

		       $data['etiq_mov'] ="de Entrada";

		       



		      $id_perfil=$this->session->userdata('id_perfil');
			      switch ($id_perfil) {    
			        case 1:          
						    $data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
						    //echo 'sad';
						   // print_r($data['movimientos']);die;
			                $this->load->view( 'pdfs/pdfs_view',$data );
			          break;
			        case 2:
			        case 3:
			        case 4:
			              if  (in_array($data['id_operacion'], $data['coleccion_id_operaciones']))  {                 
						    $data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
			                $this->load->view( 'pdfs/pdfs_view',$data );
			             }   
			          break;


			        default:  
			          redirect('');
			          break;
			      }		
		 }	      

	}	



    //para imprimir la factura desde los reportes
	public function procesar_entradas($id_movimiento=-1,$dev=0,$retorno,$id_factura,$id_estatus){


		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

		      $porciones = explode("-", base64_decode($id_movimiento));
		      $tipo_entrada= $porciones[0];
		      $id_movimiento= $porciones[1];
		      $data['tipo_entrada'] = $tipo_entrada;

		      $data['dev']= base64_decode($dev);
		      $data['id_factura']= base64_decode($id_factura);
		      
		      $data['id_estatus']= base64_decode($id_estatus);
		      //print_r($data['id_estatus']); die;


		      
		      
		      $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
	                $data['coleccion_id_operaciones'] = array();
	           } 
		      $existe = $this->model_entrada->existencia_temporales();

		     
		      if (($existe) or ($id_movimiento!=-1) ) {


		      		//ESTE ES PARA EL CASO QUE SE ESTA HACIENDO UNA "ENTRADA"
		      		if (($id_movimiento)==-1)	{

		      			$data['num_mov'] = $this->model_entrada->procesando_operacion(1);

				        $this->load->library('ciqrcode');
				        //hacemos configuraciones

						$data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
						

				        
				        foreach ($data['movimientos'] as $key => $value) {
				          
					        $params['data'] = $value->codigo;
					        $params['level'] = 'H';
					        $params['size'] = 30;
					        $params['savename'] = FCPATH.'qr_code/'.$value->codigo.'.png';
					        $this->ciqrcode->generate($params);    
					      
				        }
				        
		      		} else { //ESTE ES PARA EL CASO EN QUE SE VA A LOS DETALLES DE UNA ENTRADA EN "REPORTES-->listado_notas"

		      			$data['num_mov'] = $id_movimiento;

						$data['retorno'] ="listado_notas";




		      		}

				  $data['retorno'] = base64_decode($retorno);




         if  ($data['dev'] == 0) {
                $data['etiq_mov'] ="de Entrada";
          }
          
          if  ($data['dev'] == 1) {
                $data['etiq_mov'] ="de Devolución";
          }


				  

			      switch ($id_perfil) {    
			        case 1:          

						       //
						       $data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
						       //print_r($data);die;
						       //print_r($data);
						       //print_r($data['movimientos']);
						       //die;
			                   $this->load->view( 'pdfs/pdfs_view',$data );
			          break;
			        case 2:
			        case 3:
			        case 4:
			        		//solo el que tiene 9 porque nos lleva a un detalle de reporte, este es para los botones que
			        		//aparecen en todo el sistema que tiene el numero de entrada
			              if ( (in_array(9, $data['coleccion_id_operaciones'])) || (in_array(50, $data['coleccion_id_operaciones']))   )  {   //los 
						       $data['movimientos']  = $this->model_entrada->listado_movimientos_registros($data);
			                   $this->load->view( 'pdfs/pdfs_view',$data );
			              } else {
			          		 redirect('');    	
			              }  
			          break;


			        default:  
			          redirect('');
			          break;
			      }
			  } else { 
		          redirect('entradas');
			  }  

			      
		    }
		    else{ 
		      redirect('');
		    }  
	}







/////////////////validaciones/////////////////////////////////////////	

	function check_default1($post_string)
	{
		print_r("-> ".$post_string.'</br>');
	  return  (($post_string == '') || ($post_string == 0)) ? FALSE : TRUE;
	}


	function check_default($str)
	{
		//print_r("-> ".$post_string.'</br>');

		 if ( (trim($str)=="0") || (trim($str)=="") || (empty($str ) ) ) {	
				$this->form_validation->set_message( 'check_default','<b class="requerido">*</b> El <b>%s</b> es obligatorio.' );
				return FALSE;
	     } else {
	     	return TRUE;
	     }
		  //return  (($post_string == '') || ($post_string == 0)) ? FALSE : TRUE;


	}


	public function valid_cero($str)
	{
		
		 $regex = "/^([-0])*$/ix";
		if ( preg_match( $regex, $str ) ){			
			$this->form_validation->set_message( 'valid_cero','<b class="requerido">*</b> El <b>%s</b> no puede ser cero.' );
			return FALSE;
		} else {
			return TRUE;
		}

	}
	

	function importe_valido( $str ){
		 
		if ((trim($str)=="") || (empty($str)) ) {
			$str = "";
			$regex = "/^$/";
		} else
		{
			//$regex =  '/^[-+]?(((\\\\d+)\\\\.?(\\\\d+)?)|\\\\.\\\\d+)([eE]?[+-]?\\\\d+)?$/'; 	
			$regex = "/^[+-]?(\d*\.?\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/";
		}

		if ( ! preg_match( $regex, $str ) ){			
			$this->form_validation->set_message( 'importe_valido','<b class="">*</b> La información introducida en <b>%s</b> no es válida.' );
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function nombre_valido( $str ){
		 $regex = "/^([A-Za-z ñáéíóúÑÁÉÍÓÚ]{2,60})$/i";
		//if ( ! preg_match( '/^[A-Za-zÁÉÍÓÚáéíóúÑñ \s]/', $str ) ){
		if ( ! preg_match( $regex, $str ) ){			
			$this->form_validation->set_message( 'nombre_valido','<b class="requerido">*</b> La información introducida en <b>%s</b> no es válida.' );
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function valid_phone( $str ){
		if ( $str ) {
			if ( ! preg_match( '/\([0-9]\)| |[0-9]/', $str ) ){
				$this->form_validation->set_message( 'valid_phone', '<b class="requerido">*</b> El <b>%s</b> no tiene un formato válido.' );
				return FALSE;
			} else {
				return TRUE;
			}
		}
	}

	function valid_option( $str ){
		if ($str == 0) {
			$this->form_validation->set_message('valid_option', '<b class="requerido">*</b> Es necesario que selecciones una <b>%s</b>.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function valid_date( $str ){

		$arr = explode('-', $str);
		if ( count($arr) == 3 ){
			$d = $arr[0];
			$m = $arr[1];
			$y = $arr[2];
			if ( is_numeric( $m ) && is_numeric( $d ) && is_numeric( $y ) ){
				return checkdate($m, $d, $y);
			} else {
				$this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD-MM-YYYY.');
				return FALSE;
			}
		} else {
			$this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD-MM-YYYY.');
			return FALSE;
		}
	}

	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}	


}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */