<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Devoluciones extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('model_entradas', 'model_entrada');  
    $this->load->model('model_salida', 'modelo_salida'); 
    $this->load->model('model_devolucion', 'modelo_devolucion'); 
    $this->load->model('modelo', 'modelo'); 
    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }



// 
 public function devolucion(){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   
              $data['medidas']  = $this->catalogo->listado_medidas();
              $data['estatuss']  = $this->catalogo->listado_estatus(-1,-1,'3');
              $data['lotes']  = $this->catalogo->listado_lotes(-1,-1,'1');
              $data['productos'] = $this->catalogo->listado_productos();
              $data['consecutivo']  = $this->catalogo->listado_consecutivo(23);
              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
  
              $dato['id'] = 7;
              $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 


          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'devoluciones/editar',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(23, $data['coleccion_id_operaciones']))  {                 
                            $this->load->view( 'devoluciones/editar',$data );
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



//regilla donde estaran todos los productos en devolucion
 public function procesando_servidor_devolucion(){
    $data=$_POST;
    $busqueda = $this->modelo_devolucion->buscador_devolucion($data);
    echo $busqueda;
 }



  //Agregando Producto del estatus de devolucion
  function validar_devolucion_producto(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
  

     if ($this->input->post('editar_prod_devolucion')) {
        $data['codigo'] =  $this->modelo_devolucion->check_existente_codigo($this->input->post('editar_prod_devolucion'));
        if (!($data['codigo'])){
          print "el codigo no existe";
        }
       }


     if ($this->input->post('proveedor')) {

           $data['descripcion'] = $this->input->post('proveedor');
           $data['idproveedor'] = "1";

        $data['id_proveedor'] =  $this->catalogo->checar_existente_proveedor($data);
        if (!($data['id_proveedor'])){
          print "el proveedor no existe";
        }
       }

      $this->form_validation->set_rules( 'editar_prod_devolucion', 'Código Producto', 'required|xss_clean'); //callback_valid_option| 

      $this->form_validation->set_rules( 'proveedor', 'Proveedor', 'required|xss_clean'); //callback_valid_option|
      $d_conf['id'] = 7;
      $d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

      
      if (($d_conf['configuracion']->activo==1)) {  
        $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');
        $this->form_validation->set_rules( 'cod_devolucion', 'Devolución', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');
      }    

      $this->form_validation->set_rules( 'producto', 'Producto', 'required|xss_clean'); //callback_valid_option
      $this->form_validation->set_rules( 'color', 'Color', 'required|xss_clean'); 
      $this->form_validation->set_rules( 'composicion', 'Composición', 'required|xss_clean');
      $this->form_validation->set_rules( 'calidad', 'Calidad', 'required|xss_clean');
      
      $this->form_validation->set_rules( 'peso_real', 'Peso Real',  'required|callback_valid_cero|callback_importe_valido|xss_clean');
      
      $this->form_validation->set_rules( 'cantidad_um', 'Cantidad',  'required|callback_valid_cero|callback_importe_valido|xss_clean');
      $this->form_validation->set_rules( 'ancho', 'Ancho',  'required|callback_valid_cero|callback_importe_valido|xss_clean');
      $this->form_validation->set_rules( 'precio', 'Precio', 'required|callback_importe_valido|xss_clean');

      $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|required|min_length[3]|max_lenght[180]|xss_clean');       


      if (($this->form_validation->run() === TRUE) and ($data['id_proveedor']) and ($data['codigo']) ) {
     

          $data['consecutivo']   = $this->input->post('consecutivo');
          $data['codigo']   = $data['codigo'];
          if ($d_conf['configuracion']->activo==1) {  
            $data['cod_devolucion']   = $this->input->post('cod_devolucion');
          }  
          $data['comentario']   = $this->input->post('comentario');
          $data['peso_real_devolucion']   = $this->input->post('peso_real');
       
          
          $data         =   $this->security->xss_clean($data);  
          
          $guardar            = $this->modelo_devolucion->actualizar_producto_devolucion( $data );
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




/////Quitar Producto del estatus de devolucion


  public function quitar_devolucion($id = '', $nombrecompleto=''){


    if ( $this->session->userdata('session') !== TRUE ) {
      redirect('');
    } else {
      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }

    $data['id']         = base64_decode($id); 
    $data['nombrecompleto']   = base64_decode($nombrecompleto);
 
      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'devoluciones/quitar_prod_devolucion', $data );
            break;
        case 2:
        case 3:
        case 4:
             if  (in_array(23, $data['coleccion_id_operaciones']))  { 
                  $this->load->view( 'devoluciones/quitar_prod_devolucion', $data );
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



  function validar_quitar_devolucion(){
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }

    $quitando            = $this->modelo_devolucion->quitar_producto_devolucion( $data );

    echo TRUE;
  
  } 

///////////////////////////////////////////////////////////////////////////////////////////  

  //validando si existe los productos a devolver
  public function validar_conf_devolucion(){
      $existe = $this->modelo_devolucion->existencia_temporales();

      if (!$existe) {
        echo 'No existen producto seleccionado.';  
      } else {
        echo true;  
      }
  } 

 //procesando la devolucion
  public function procesar_devoluciones($id_movimiento=-1){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $id_movimiento= base64_decode($id_movimiento);

         $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 

   
    
          $data['num_mov'] = $this->modelo_devolucion->procesando_operacion();
          $data['id_factura'] = 0; //porque pdfs_views espera un valor
          $data['id_estatus'] = 13; //status de devolucion=13
          $this->load->library('ciqrcode');
          $data['movimientos']  = $this->modelo_devolucion->listado_movimientos_registros($data);

                foreach ($data['movimientos'] as $key => $value) {
                  $params['data'] = $value->codigo;
                  $params['level'] = 'H';
                  $params['size'] = 30;
                  $params['savename'] = FCPATH.'qr_code/'.$value->codigo.'.png';
                  $this->ciqrcode->generate($params);    
                }


            $data['etiq_mov'] ="de Devolución";    
            
            switch ($id_perfil) {    
              case 1:          
      
                   $data['movimientos']  = $this->modelo_devolucion->listado_movimientos_registros($data);
                   
                         $this->load->view( 'pdfs/pdfs_view',$data );
                break;
              case 2:
              case 3:
              case 4:
                  if  (in_array(23, $data['coleccion_id_operaciones']))  {                 
                         $data['movimientos']  = $this->modelo_devolucion->listado_movimientos_registros($data);
                         
                         $this->load->view( 'pdfs/pdfs_view',$data );
                  }  else {
                    redirect('');      
                  } 
                break;


              default:  
                redirect('');
                break;
            }

            
        } else{ 
          redirect('');
        }  
  }


//////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////NoSE pero al parecer no se usa
  //Filtro La lista que es dependiente a un elemento padre
  function cargar_dependencia(){
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_calida']        = $this->input->post('val_calida');

    $data['dependencia']        = $this->input->post('dependencia');

    switch ($data['dependencia']) {
        case "producto": //nunca será una dependencia
            $elementos  = $this->catalogo->listado_productos();
            break;
        case "color":
            $elementos  = $this->catalogo->lista_colores($data);
            break;

        case "composicion":
            $elementos  = $this->catalogo->lista_composiciones($data);
            break;
        case "calidad":
            $elementos  = $this->catalogo->lista_calidad($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id)); 
       }
    }  

     echo json_encode($variables);
  }

////////////////////////////////////////////////////////////////////////////////////////




   //esto es para agregar los productos a temporal
  function validar_impresion(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {

     $codigo= '<span class="error"><b>E01</b> - Debe especificar un correcto código de producto</span>';
     if ($this->input->post('codigo')) {
        $codigo =  $this->catalogo->check_existente_codigo($this->input->post('codigo'));
        if ($codigo == false) {
           $codigo = '<span class="error"><b>E01</b> - Debe especificar un correcto código de producto</span>';
        } else 
        {
          $codigo = true;
        }
        
     }
     echo $codigo;

    }
  }


/////////////////validaciones/////////////////////////////////////////  


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