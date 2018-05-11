<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Transferencias extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('model_pedido_compra', 'model_pedido_compra'); 
		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('model_entradas', 'model_entrada');  
		$this->load->model('modelo', 'modelo');
		$this->load->model('model_transferencia', 'model_transferencia');  

		$this->load->library(array('email')); 
		$this->load->library('Jquery_pagination');//-->la estrella del equipo	
	}

	//mostrar las transferencias
	public function transferencia_recibida(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }


		       	$data['consecutivo']  	= $this->catalogo->listado_consecutivo(70);
		       	$data['val_proveedor']  = $this->model_entrada->valores_movimientos_temporal();

				$data['id_operacion']  =  70; //entrada

	            if  ($data['val_proveedor']) {
	              $data['conse_general']    =$data['val_proveedor'];
	            } else {
	                $data['id_almacen']  =  1; //bod.1
	                  $data['id_factura']  =  1;  //factura
	                   $data['id_pedido']  =  0; //no tiene pedido
	               $data['conse_general']   = $this->catalogo->consecutivo_general($data);
	            }


    	        $data['almacenes']   	= $this->modelo->coger_catalogo_almacenes(2);
    	        //$data['facturas']   	= $this->catalogo->listado_tipos_facturas(-1,-1,'1');
    	        $data['facturas']   	= $this->catalogo->catalogo_tipos_facturas();
    	        $data['pagos']   		= $this->catalogo->listado_tipos_pagos();

    	        $data['transferencias']   		= $this->model_transferencia->listado_transferencias();
    	        
    	        
 				$dato['id'] = 7;
		      	$data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

		      switch ($id_perfil) {    
		        case 1:          

		                    $this->load->view( 'transferencias/recibida',$data );
		                    //print_r('a');die;

		          break;
		        case 2:
		        case 3:
		        case 4:
		              if  (in_array($data['id_operacion'], $data['coleccion_id_operaciones']))  {                 
		                         $this->load->view( 'transferencias/recibida',$data );
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

	//regilla de transferencia recibidda
	public function procesando_transferencia_recibida(){
		$data=$_POST;
		$busqueda = $this->model_transferencia->buscando_transferencia_recibida($data);
		echo $busqueda;
		
	}	

    //validar cuando procese entrada de transferencia
 	public function validar_proceso_transferencia(){ 

		 if($this->session->userdata('session') === TRUE ){

		      $id_perfil=$this->session->userdata('id_perfil');

		      $data['factura']   = $this->input->post('factura');
		      $data['mov_salida_unico']   = $this->input->post('mov_salida_unico');
		      $data['id_almacen']   = $this->input->post('id_almacen');
		      $data['id_tipo_pago']   = $this->input->post('id_tipo_pago');
		      $data['id_factura']   = $this->input->post('id_factura');

		      $data['movimiento']   = $this->input->post('movimiento');
		      $data['movimiento_unico']   = $this->input->post('movimiento_unico');
		      $data['id_tienda_origen']   = $this->input->post('id_tienda_origen');

		      $data['id_operacion'] =70;	     //71
              $data['id_pedido']  =  0; //no tiene pedido
		      
		      
		      //$data['id_estatus']   = 0; //$this->input->post('id_estatus');

		      $data['dev'] = 0; 


		      //si existe elemento en la tabla temporal
		      $existe = $this->model_transferencia->existencia_transferencia($data);

		      $existe_factura = $this->model_transferencia->existencia_factura($data);
		      /*
			   if (!($existe_factura)) {
						print "El número de factura ya existe";
			   }*/

   		       $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');	


		      //echo json_encode($existe); die;
		     
		            if ( ($this->form_validation->run() === TRUE) and ($existe) && ($existe_factura) ) {

		            //actualizar precio nuevo	
		            $data['precios'] =  json_decode(json_encode( $this->input->post('arreglo_precio') ),true  );	
		            $this->model_transferencia->actualizar_precio_transferencia($data);	

		            //actualizar PESO nuevo	
		            $data['pesos'] =  json_decode(json_encode( $this->input->post('arreglo_peso') ),true  );	
		            $this->model_transferencia->actualizar_peso_transferencia($data);	


		      		//copiar a tabla "registros" e "historico_registros_entradas"
		      		//$data['id_operacion'] =70;
	      			$data['num_mov'] = $this->model_transferencia->procesando_operacion_transferencia($data);
	      			//echo json_encode($data['num_mov']);
	      			//die;
	      			
			        $this->load->library('ciqrcode');
			        //hacemos configuraciones

					$data['movimientos']  = $this->model_transferencia->listado_movimientos_transferencia($data);

			        //print_r($data['movimientos']); die;
			        foreach ($data['movimientos'] as $key => $value) {
			        	//print_r($value->codigo); die;
			          
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
					$data['error'] = '<span class="error">Seleccionar transferencia y número de factura correcta.</span>';
					echo json_encode($data);

		          
			  }  

			      
		    }
		    else{ 
		      redirect('');
		    }  
	}


	


//para imprimir la factura despues de procesada
	public function procesar_entrar_transferencia($num_mov,$id_factura,$id_estatus){ 

		 if($this->session->userdata('session') === TRUE ){

			$data['dev'] = 0;

 			  $porciones = explode("-", base64_decode($num_mov));
		      $data['tipo_entrada'] = $porciones[0];
		      $data['num_mov'] = $porciones[1];
		      
			$data['id_factura'] = base64_decode($id_factura);
			//$data['id_estatus'] = base64_decode($id_estatus);
			 $data['id_estatus']   = 0; 
			 $data['id_operacion']= 70;

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


	


//***********************Todos los recepciones**********************************//
	

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