<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pedido_compra extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_entradas', 'model_entrada');  
    $this->load->model('model_salida', 'modelo_salida'); 

    $this->load->model('model_traspaso', 'model_traspaso'); 
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    $this->load->model('model_impresion_compra', 'model_impresion_compra'); 
    $this->load->model('model_exportar_compra', 'model_exportar_compra'); 
    
    
    

    $this->load->model('modelo_borrar_datos', 'modelo_borrar_datos'); 

    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }



public function pendiente_revision(){
  $data['modulo'] = 1;
  $data['titulo'] = "Revisión - Admin.";
  self::modulo_pedido_compra($data);
}

public function solicitar_modificacion(){
  $data['modulo'] = 2;
  $data['titulo'] = "Revisión - Almacén.";
  self::modulo_pedido_compra($data);
}

public function aprobado(){
  $data['modulo'] = 3;
  $data['titulo'] = "Aprobados";
  self::modulo_pedido_compra($data);
}

public function cancelado(){
  $data['modulo'] = 4;
  $data['titulo'] = "Cancelados";
  self::modulo_pedido_compra($data);
}

public function gestionar_pedido_compra(){
  $data['modulo'] = 5;
  $data['titulo'] = "Histórico";
  self::modulo_pedido_compra($data);
}


public function modulo_pedido_compra($data){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   
           

           //no. movimiento
           $data['consecutivo']  = $this->catalogo->listado_consecutivo(26);
           //valor del cliente, cargador, factura, 
           $data['val_compra']  = $this->model_pedido_compra->valores_movimientos_temporal();
           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
           $data['proveedores']   = $this->modelo->coger_catalogo_proveedores(2);

           $data['id_almacen']=$this->session->userdata('id_almacen');
           $data['mod']=1;      
           $data['cant'][1]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=2;      
           $data['cant'][2]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=3;      
           $data['cant'][3]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=4;      
           $data['cant'][4]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=5;      
           $data['cant'][5]   =  $this->model_pedido_compra->total_modulo($data);

           $dato['id'] = 11;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
           

          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'pedido_compra/pedido_compra',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(39, $data['coleccion_id_operaciones']))  {                 
                           $this->load->view( 'pedido_compra/pedido_compra',$data );
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


//realizar nuevo pedido de compra
public function nuevo_pedido_compra($url){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   
           

           //no. movimiento
           $data['consecutivo']  = $this->catalogo->listado_consecutivo(26);
           //valor del cliente, cargador, factura, 
           $data['val_compra']  = $this->model_pedido_compra->valores_movimientos_temporal(); //para encabezado
           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
            $data['medidas']  = $this->catalogo->listado_medidas();
           
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
           $data['proveedores']   = $this->modelo->coger_catalogo_proveedores(2);

           $data['retorno'] = base64_decode($url);

            $dato['id'] = 11;
            $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 





          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'pedido_compra/nuevo_pedido',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(39, $data['coleccion_id_operaciones']))  {                 
                           $this->load->view( 'pedido_compra/nuevo_pedido',$data );
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

  //regilla1 "nuevo_pedido_compra"
  public function procesando_entrada_pedido_compra(){
      $data=$_POST;
       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_pedido_compra->buscador_entrada_compra($data);
      echo $busqueda;

  }


 //regilla2 "nuevo_pedido_compra"
  public function procesando_salida_pedido_compra(){
      $data=$_POST;
            $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_pedido_compra->buscador_salida_compra($data);
      echo $busqueda;
      
  }




function agregar_salida_compra(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {

          $d_conf['id'] = 11;
      $d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

      if (($d_conf['configuracion']->activo==1)) {  
        $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');
      }  


      if ( ($this->form_validation->run() === TRUE) || ($d_conf['configuracion']->activo==0)  ) {

            $data['id'] = $this->input->post('identificador');
            $data['movimiento'] = $this->input->post('movimiento');
            if (($d_conf['configuracion']->activo==1)) {  
              $data['factura'] = $this->input->post('factura');
            }  
            $data['comentario'] = $this->input->post('comentario');
            $data['id_almacen'] = $this->input->post('id_almacen');
            $data['id_proveedor'] = $this->input->post('id_proveedor');

            $data['id_medida'] = $this->input->post('id_medida');

            $existe=$this->model_pedido_compra->checar_salida_compra($data);

            if ($existe==false) {
               $this->model_pedido_compra->enviar_salida_compra($data);  
            } 

            
           $actualizar = true; //$this->model_pedido_compra->enviar_salida_compra($data);  

            if ( $actualizar !== FALSE ){
              echo TRUE;
            } else {
              echo '<span class="error">No se ha podido añadir el producto</span>';
            }
  
      } else {      
        
             echo validation_errors('<span class="error">','</span>');

      }     

    } 
}


function quitar_salida_compra(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {
      
      $data['id'] = $this->input->post('identificador');
      $data['id_medida'] = $this->input->post('id_medida');
      $actualizar =  $this->model_pedido_compra->quitar_salida_compra( $data );
      $dato['val_compra']  = $this->model_pedido_compra->valores_movimientos_temporal();
      
      $actualizar=true;
      if ( $actualizar !== FALSE ){
        $dato['exito']  = true;
        echo json_encode($dato);
        
      } else {
        $dato['exito']  = false;
        $dato['error'] = '<span class="error">No se ha podido actualizar el producto</span>';
        echo json_encode($dato);
      }
    } 
   }




 //procesar "nuevo pedido compra"
  public function proc_pedido_compra(){


       if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }  

            
            $existe  = $this->model_pedido_compra->valores_movimientos_temporal();

            $errores='';

        

            $d_conf['id'] = 11;
        $d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

        if (($d_conf['configuracion']->activo==1)) {  
          $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');
        }  

            
        if ( ($this->form_validation->run() === TRUE) || ($d_conf['configuracion']->activo==0)  ) {
           if  (!($existe)) {
            $errores= "Debe agregar al menos un producto";
           } else {  //si estan agregados los productos entonces checar si tienen el peso real
              
              //actualizar peso real
              $data['cantidad'] =  json_decode(json_encode( $this->input->post('arreglo_pedido_compra') ),true  );
              $this->model_pedido_compra->actualizar_pedido_compra($data);

              //verificar si hay cantidades a pedir en cero 
              $existe = $this->model_pedido_compra->existencia_temporales_cantidad();
              if  (!($existe)) {
                $errores= "Existen productos sin especificar cantidades a pedir";
              } 

           }
           
        }  
        
          $data['id_almacen'] = $this->input->post('id_almacen');

          $data['consecutivo']  = ($this->catalogo->listado_consecutivo(26)->consecutivo)+1;

          if ( ($this->form_validation->run() === TRUE) || ($d_conf['configuracion']->activo==0)  ) {
                //verificar si los apartados estan siendo totales o parciales
                    
                    $this->model_pedido_compra->enviar_historico_pedido_compra($data);

                    $dato['exito'] = true;
                    echo json_encode($dato);
          } else {
                $dato['exito']  = false;
                $dato['errores'] =$errores;
                $dato['error'] = validation_errors('<span class="error">','</span>');
                echo json_encode($dato);
          }   
    

    } else { //fin de session
      redirect('');
    }   
    
  }

////////////////////////////////////////////////////////////////////////////////////////////////
////////////////revision////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////


//regilla externa pendiente_revision
public function procesando_pedido_compra(){
      $data=$_POST;

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
          } 

          $data['modulo']        = $this->input->post('modulo');      

          switch ($data['modulo']) {    
            case 1:          
            case 2:   
            case 3:   
                  $busqueda = $this->model_pedido_compra->buscador_pedido_compra($data);
              break;
            case 4:
                  $busqueda = $this->model_pedido_compra->buscador_cancela_compra($data);
              break;
            case 5:   
                  $busqueda = $this->model_pedido_compra->buscador_historial_compra($data);
              break;


            default:  
                 $busqueda = $this->model_pedido_compra->buscador_pedido_compra($data);
              break;
          }




      
      echo $busqueda;
      
  }




//interior de pendiente de revision
public function detalle_revision($movimiento, $modulo){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

                   

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
           } 


           $data['modulo']  = base64_decode($modulo); 
           $data['movimiento']  = base64_decode($movimiento); 

           

           switch ($data['modulo']) {
             case 1: //revisión admin
                  $data['retorno'] ='/pendiente_revision';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 2:
                  $data['retorno'] ='/solicitar_modificacion';
                  $revisar = 'revisar_compra';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 3:
                  $data['retorno'] ='/aprobado';
                  $revisar = 'revisar_aprobado';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_temporal($data);
               break;
             case 4:
                  $data['retorno'] ='/cancelado';
                  $revisar = 'revisar_compra_cancelada';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_cancelar($data);
               break;
             case 5:  //Historico
                  $data['retorno'] ='/gestionar_pedido_compra';   
                  $revisar = 'revisar_historial';
                  $data['val_compra']  = $this->model_pedido_compra->valores_revision_historial($data);
               break;
             
             default:
                  $revisar = 'revisar_compra';
               break;
           }

           
           //Aqui busca el encabezado del pedido de compra


          $dato['id'] = 11;
          $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
           $data['proveedores']   = $this->modelo->coger_catalogo_proveedores(2);
           //$data['medidas']  = $this->catalogo->listado_medidas();




          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'pedido_compra/'.$revisar,$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(39, $data['coleccion_id_operaciones']))  {                 
                           $this->load->view( 'pedido_compra/'.$revisar,$data );

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





//cdo confirma cambios en la revision
public function proc_pedido_cambio(){


       if($this->session->userdata('session') === TRUE ){
            $id_perfil=$this->session->userdata('id_perfil');

          

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
          } 

            $errores='';

        
                $d_conf['id'] = 11;
                $d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

                if (($d_conf['configuracion']->activo==1)) {  
                  $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');
                }  

                if ( ($this->form_validation->run() === TRUE) || ($d_conf['configuracion']->activo==0)  ) {
                      //actualizar cantidad aprobada
                      $data['movimiento'] =   $this->input->post('movimiento');
                      $data['comentario'] =   $this->input->post('comentario');
                      $data['cant_solicitada'] =  json_decode(json_encode( $this->input->post('arreglo_cant_solicitada') ),true  );
                      $data['cant_aprobada'] =  json_decode(json_encode( $this->input->post('arreglo_cant_aprobada') ),true  );
                      $data['precio_aprobado'] =  json_decode(json_encode( $this->input->post('arreglo_precio_aprobado') ),true  );
                        
                      $dato['aprobado'] = $this->model_pedido_compra->actualizar_cantidad_aprobado($data);
                      $dato['exito'] = true;
                      echo json_encode($dato);
                }  else {
                    $dato['exito']  = false;
                    //$dato['errores'] =$errores;
                    $dato['error'] = validation_errors('<span class="error">','</span>');
                    echo json_encode($dato);
                }          

    } else { //fin de session
      redirect('');
    }   
    
  }




//modal para aprobacion de compra
public function pedido_compra_modal($aprobado,$movimiento,$modulo,$retorno){


      if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {

         

         
          $id_perfil=$this->session->userdata('id_perfil');
          

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
          }  
          
          $data['aprobado']         = base64_decode($aprobado);
          $data['movimiento']         = base64_decode($movimiento);
          $data['modulo']         = base64_decode($modulo);
          $data['retorno']         = base64_decode($retorno);

         $dato['id'] = 11;
         $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

          switch ($id_perfil) {    
            case 1:
                  
                    $this->load->view( 'pedido_compra/pedido_compra_modal', $data );
              break;
            case 2:
            case 3:
            case 4:
                 if  (in_array(39, $data['coleccion_id_operaciones']))  {                 
                     $this->load->view( 'pedido_compra/pedido_compra_modal', $data );
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






//para pasar al historico desde aprobado
public function proc_pedido_aprobado(){
   if($this->session->userdata('session') === TRUE ){
          
          $data['movimiento'] =   $this->input->post('movimiento');
          $data['retorno'] =   $this->input->post('retorno');
          $data['status_compra'] = 5; //para que pase al historial
            
          $this->model_pedido_compra->confirmando_aprobado($data);

          redirect($data['retorno']);
    } else { 
      redirect('');
    }   

}  

///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////cancelando pedido compra
///////////////////////////////////////////////////////////////////////////////////////////////////////////



  function cancelar_pedido_compra($movimiento, $modulo){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }  

      
           $data['modulo']  = base64_decode($modulo); 
           $data['movimiento']  = base64_decode($movimiento); 

           $dato['id'] = 11;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

           //$data['retorno'] = base64_decode($modulo);

           switch ($data['modulo']) {
             case 1:
                  $data['retorno'] ='/pendiente_revision';
               break;
             case 2:
                  $data['retorno'] ='/solicitar_modificacion';
               break;
             case 3:
                  $data['retorno'] ='/aprobado';
               break;
             case 4:
                  $data['retorno'] ='/cancelado';
               break;
             case 5:
                  $data['retorno'] ='/gestionar_pedido_compra';
               break;
             
             default:
                // $data['retorno'] ='/pendiente_revision';
               break;
           }


      switch ($id_perfil) {    
        case 1:           
                    $this->load->view( 'pedido_compra/eliminar_pedido_compra',$data );
          break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(39, $data['coleccion_id_operaciones']))  {                 
                         $this->load->view( 'pedido_compra/eliminar_pedido_compra',$data );
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


//validando la cancelacion del pedido de compra
function validar_cancelar_pedido_compra(){

    $data['movimiento']        = $this->input->post('movimiento');

    $cancelar = $this->model_pedido_compra->cancelar_pedido_compra(  $data );
    
    if ( $cancelar !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar la almacen</span>';
    }
  }   














///////////////////////////////////////////////////////////////////////////////////////////////////////////
/*public function proc_pedido_aprobado(){

   if($this->session->userdata('session') === TRUE ){
          
          $data['movimiento'] =   $this->input->post('movimiento');
          $data['status_compra'] = 5; //para que pase al historial
            
          $this->model_pedido_compra->confirmando_aprobado($data);
          $dato['exito'] = true;
          echo json_encode($dato);
    } else { 
          $dato['exito']  = false;
          $dato['error'] = validation_errors('<span class="error">','</span>');
          echo json_encode($dato);
    }   

}  
*/


//prueba "la modal" de confirmar compra
public function confirmar_pedido_compra(){
  //Array ( [aprobado] => false [movimiento] => 9 [procesando_confirmar_pedido] => [modulo] => 1 [retorno] => /pendiente_revision )
  //print_r($_POST);
  //redirect($data['retorno']);

 if ( $this->session->userdata('session') !== TRUE ) {
          redirect('');
        } else {
         
          $id_perfil=$this->session->userdata('id_perfil');

          $data['aprobado'] =   $this->input->post('aprobado');
          $data['movimiento'] =   $this->input->post('movimiento');
          $data['modulo'] =   $this->input->post('modulo');
          $data['retorno'] =   $this->input->post('retorno');


          

          
          switch ($data['modulo']) {    
            case 1: //cambios del admin
                  if  ($data['aprobado']!="false")  { 
                      $data['status_compra'] = 3; //aprobado
                  } else {
                    $data['status_compra'] = 2; //modificado
                  } 
              break;
            case 2: //cambios del almacenista
                  if  ($data['aprobado']!="false")  { 
                      $data['status_compra'] = 3; //aprobado
                  } else {
                    $data['status_compra'] = 1; //modificado
                  } 
              break;
            default:  
               redirect($data['retorno']);
              break;
          }

            $this->model_pedido_compra->confirmar_cambio($data);
          //  print_r($data);
          redirect($data['retorno']);
          
       }     

 
}  

////////////////////////////////////////////////////////////////////////////////////////////////////////



public function procesando_revisar_pedido_compra(){
      $data=$_POST;


        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 

          $data['modulo']        = $this->input->post('modulo');      

          switch ($data['modulo']) {    
            case 1:          
            case 2:   
            case 3:   
                  $busqueda = $this->model_pedido_compra->buscador_revisar_pedido_compra($data);
              break;
            case 4:
                  $busqueda = $this->model_pedido_compra->buscador_revisar_cancela_compra($data); //12
              break;
            case 5:   
                  $busqueda = $this->model_pedido_compra->buscador_revisar_historial_compra($data); //14
              break;              
            default:  
                 $busqueda = $this->model_pedido_compra->buscador_revisar_pedido_compra($data);
              break;
          }




      
      echo $busqueda;
      
  }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////




   public function notificacion_compra(){

            //$data['id_almacen']=$this->session->userdata('id_almacen');
            $data['id_almacen'] = $this->input->post('id_almacen');

           $data['mod']=1;      
           $data['cant'][1]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=2;      
           $data['cant'][2]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=3;      
           $data['cant'][3]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=4;      
           $data['cant'][4]   =  $this->model_pedido_compra->total_modulo($data);
           $data['mod']=5;      
           $data['cant'][5]   =  $this->model_pedido_compra->total_modulo($data);
        
        echo  json_encode($data);
    }            






  
  


  

function cargar_dependencia_compra(){
    
    $data['campo']        = $this->input->post('campo');

    $data['val_prod']        = $this->input->post('val_prod');
    $data['val_color']        = $this->input->post('val_color');
    $data['val_comp']        = $this->input->post('val_comp');
    $data['val_calida']        = $this->input->post('val_calida');

    $data['dependencia']        = $this->input->post('dependencia');

    switch ($data['dependencia']) {
        case "producto_catalogo_compra": //nunca será una dependencia
            $elementos  = $this->catalogo->listado_productos_unico();
            break;
        case "color_catalogo_compra":
            $elementos  = $this->catalogo->lista_colores($data);
            
            break;

        case "composicion_catalogo_compra":
            $elementos  = $this->catalogo->lista_composiciones($data);
            break;
        case "calidad_catalogo_compra":
            $elementos  = $this->catalogo->lista_calidad($data);
            break;

        default:
    }



      $variables = array();
    if ($elementos != false)  {     
         foreach( (json_decode(json_encode($elementos))) as $clave =>$valor ) {
            if ($data['dependencia']=="color_catalogo_compra"){
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => $valor->hexadecimal_color)); 
            } else {
              array_push($variables,array('nombre' => $valor->nombre, 'identificador' => $valor->id, 'hexadecimal_color' => "FFFFFF"));  
            }
       }
    }  

     echo json_encode($variables);
  }
















/*
exportar_reportes_compra
impresion_reporte_compra
*/



 public function exportar_reportes_compra() {
      $this->load->library('export');
        $data = $_POST;

        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 

          switch ($data['modulo']) {
            case 1:          
            case 2:   
            case 3:   
                  $data['movimientos'] = $this->model_exportar_compra->buscador_revisar_pedido_compra($data);
              break;
            case 4:
                   $data['movimientos'] =  $this->model_exportar_compra->buscador_revisar_cancela_compra($data);
              break;
            case 5:   
                   $data['movimientos'] =  $this->model_exportar_compra->buscador_revisar_historial_compra($data);
              break;              
            default:  
                  $data['movimientos'] =  $this->model_exportar_compra->buscador_revisar_pedido_compra($data);
              break;             
             default:
                   $data['movimientos'] = $this->model_exportar_compra->buscador_revisar_pedido_compra($data);
               break;
           }

          $this->export->to_excel($data['movimientos'], 'reporte_compras_'.date("Y-m-d_H-i-s")); 

}


 public function impresion_reporte_compra() {

        $data = $_POST;
          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 

          switch ($data['modulo']) {
            case 1:          
            case 2:   
            case 3:   
                  $data['movimientos'] = $this->model_impresion_compra->buscador_revisar_pedido_compra($data);
              break;
            case 4:
                   $data['movimientos'] =  $this->model_impresion_compra->buscador_revisar_cancela_compra($data);
              break;
            case 5:   
                   $data['movimientos'] =  $this->model_impresion_compra->buscador_revisar_historial_compra($data);
              break;              
            default:  
                  $data['movimientos'] =  $this->model_impresion_compra->buscador_revisar_pedido_compra($data);
              break;             
             default:
                   $data['movimientos'] = $this->model_impresion_compra->buscador_revisar_pedido_compra($data);
               break;
           }



         $dato['id'] = 11;
         $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

          //$data['movimientos'] = $this->model_traspaso->imprimir_detalle_general_traspaso_manual($data);        
          $html = $this->load->view('pdfs/pedido_compra/detalles_historico', $data, true);



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
        $pdf->SetFont('freemono', '', 14, '', true);
 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm
        


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("detalle_pedido_compra".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');

 }






/////////////////validaciones/////////////////////////////////////////  

  

  public function valid_selector($str)
  {
    
     $regex = "/^([-0])*$/ix";
    if ( preg_match( $regex, $str ) ){      
      $this->form_validation->set_message( 'valid_selector','<b class="requerido">*</b> El <b>%s</b> tiene que contener valor.' );
      return FALSE;
    } else {
      return TRUE;
    }

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