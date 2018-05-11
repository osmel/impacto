<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consultas extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_pedido', 'modelo_pedido');
		$this->load->model('model_pedido_compra', 'model_pedido_compra'); 

		$this->load->model('model_entradas', 'model_entrada');
		
		$this->load->model('model_consulta', 'model_consulta');

		$this->load->model('catalogo', 'catalogo');  
		$this->load->model('modelo', 'modelo');  

	
	}



 function cargar_dependencia_totales(){
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_ancho']        = $this->input->post('val_ancho');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_proveedor']        = $this->input->post('val_proveedor');

    $data['dependencia']        = $this->input->post('dependencia');

    switch ($data['dependencia']) {
        case "producto_totales": //nunca será una dependencia
            $elementos  = $this->model_consulta->listado_productos_unico();
            break;
        case "composicion_totales":
            $elementos  = $this->model_consulta->lista_composiciones($data);
            break;
        case "ancho_totales":
            $elementos  = $this->model_consulta->lista_ancho($data);
            break;
        case "color_totales":
            $elementos  = $this->model_consulta->lista_colores_dep($data);
            break;

        case "proveedor_totales":
            $elementos  = $this->model_consulta->lista_proveedores($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            if ($data['dependencia']=="color_totales"){
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            } else {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            }
       }
    }  

     echo json_encode($variables);
  }






  public function procesando_consulta_totales(){
    $data=$_POST;
    $busqueda = $this->model_consulta->buscador_consulta_totales($data);
    echo $busqueda;
  } 


  public function consulta_totales(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 

			
		       $data['productos'] = $this->model_consulta->listado_productos_unico();
		       $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);



		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'consultas/consulta_totales',$data );
		          break;
		        case 2:
		        case 4:
		                        $this->load->view( 'consultas/consulta_totales',$data );
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



	/////////////////////////////////////////***************************//////////////////////

	  public function procesando_consulta_producto(){
	    $data=$_POST;
	    $busqueda = $this->model_consulta->buscador_consulta_producto($data);
	    echo $busqueda;
	  } 







  function cargar_dependencia_producto(){
    
    $data['valor']        = $this->input->post('valor');

    $elementos  = $this->model_consulta->lista_colores($data);



    $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            //  array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            
       }
    }  

     echo json_encode($variables);
  }





	public function consulta_producto(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 

			
			 $data['val_proveedor']  = '';

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'consultas/consulta_producto',$data );
		          break;
		        case 2:
		        case 4:
		                        $this->load->view( 'consultas/consulta_producto',$data );
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



	  public function procesando_consulta_proveedor(){
	    $data=$_POST;
	    $busqueda = $this->model_consulta->buscador_consulta_proveedor($data);
	    echo $busqueda;
	  } 

	public function consulta_proveedor(){

		 if($this->session->userdata('session') === TRUE ){
		      $id_perfil=$this->session->userdata('id_perfil');

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 
           
			
				$data['val_proveedor']  = $this->model_entrada->valores_movimientos_temporal();				

		      switch ($id_perfil) {    
		        case 1:          
		                    $this->load->view( 'consultas/consulta_proveedor',$data );
		          break;
		        case 2:
		        case 4:
		        
		              $this->load->view( 'consultas/consulta_proveedor',$data );
		          
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

/////////////////validaciones/////////////////////////////////////////	


	public function valid_cero($str)
	{
		return (  preg_match("/^(0)$/ix", $str)) ? FALSE : TRUE;
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
			$this->form_validation->set_message('valid_date', '<b class="requerido">*</b> El campo <b>%s</b> debe tener una fecha válida con el formato DD/MM/YYYY.');
			return FALSE;
		}
	}

	public function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

////////////////////////////////////////////////////////////////
	//salida del sistema
	public function logout(){
		$this->session->sess_destroy();
		redirect('');
	}	

}

/* End of file main.php */
/* Location: ./app/controllers/main.php */