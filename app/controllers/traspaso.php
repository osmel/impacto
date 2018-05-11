<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Traspaso extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    $this->load->model('catalogo', 'catalogo');  
    $this->load->model('modelo', 'modelo');  
    $this->load->model('model_entradas', 'model_entrada');  
    $this->load->model('model_salida', 'modelo_salida'); 

    $this->load->model('model_traspaso', 'model_traspaso'); 

    $this->load->model('modelo_borrar_datos', 'modelo_borrar_datos'); 

    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }


public function borrardatos() {
  
  print_r($this->modelo_borrar_datos->eliminar_todos());
  echo '<br/>';
 // print_r($this->modelo_borrar_datos->eliminar_remoto());
} 

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
      ///////////////////////modulo de traspaso
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//mostrar todos los traspasos
public function modulo_traspaso(){


     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

           
           $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 
           
           //no. movimiento
           $data['consecutivo']  = $this->catalogo->listado_consecutivo(26);
           //valor del cliente, cargador, factura, 
           $data['val_proveedor']  = $this->model_traspaso->valores_movimientos_temporal();
           //print_r($data['val_proveedor']);
           //die;
           $data['productos'] = $this->catalogo->listado_productos_unico();
           $data['colores'] = $this->catalogo->listado_colores_unico();
           $data['destinos'] = $this->catalogo->lista_destino();
           $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);



           $data['facturas']    = $this->catalogo->catalogo_tipos_facturas();
           $data['pedidos']   = $this->catalogo->listado_tipos_pedidos(-1,-1,'1');
           $dato['id'] = 12;
           $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

           

          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'traspaso/modulo_traspaso',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(26, $data['coleccion_id_operaciones']))  {                 
                            $this->load->view( 'traspaso/modulo_traspaso',$data );
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

  //mostrar la 1ra regilla
  public function procesando_entrada_traspaso(){
      $data=$_POST;
      $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_traspaso->buscador_entrada($data);
      echo $busqueda;
  }


  //mostrar la 2da regilla
  public function procesando_salida_traspaso(){
      $data=$_POST;
       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_traspaso->buscador_salida($data);
      echo $busqueda;
  }


  //agregar prod al traspaso
  function agregar_prod_salida_traspaso(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {


      $d_conf['id'] = 12;
      $d_conf['configuracion'] = $this->catalogo->coger_configuracion($d_conf); 

      if (($d_conf['configuracion']->activo==1)) {  
        $this->form_validation->set_rules( 'factura', 'Factura', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');
      }  
     $this->form_validation->set_rules( 'comentario', 'comentario', 'trim|required|min_length[2]|max_lenght[180]|xss_clean');


      if ( ($this->form_validation->run() === TRUE)  ) {

            $data['id'] = $this->input->post('identificador');
            $data['id_almacen'] = $this->input->post('id_almacen');
            $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');

            if (($d_conf['configuracion']->activo==1)) {  
              $data['factura'] = $this->input->post('factura');
            }  
            $data['comentario'] = $this->input->post('comentario');


            /*
            
            $data['id_movimiento'] = $this->input->post('movimiento');
            $data['id_destino'] = $this->input->post('id_destino');
            */
           
            $this->model_traspaso->establecer_productos_traspasado($data);  
              
            $actualizar = true;
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


  //quitar_prod_salida
  

  function quitar_prod_salida_traspaso(){

      if ($this->session->userdata('session') !== TRUE) {
        redirect('');
      } else {

            $data['id'] = $this->input->post('identificador');
            $data['id_almacen'] = $this->input->post('id_almacen');
            $data['id_tipo_factura'] = $this->input->post('id_tipo_factura');
           
               $actualizar = $this->model_traspaso->quitar_productos_traspasado($data);  
            $dato['total'] = $this->model_traspaso->total_registros_traspaso();

          
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


  // procesar "moduLO_traspaso" 
  public function procesando_traspaso_definitivo(){
      $data['id_factura'] = $this->input->post('id_factura');
      $data['consecutivo']  =$this->model_traspaso->consecutivo_operacion(26,$data['id_factura']); //old
      $data['consecutivo_unico'] = $this->model_traspaso->consecutivo_operacion_unico(26);  //new
      $actualizar = $this->model_traspaso->procesando_traspaso_definitivo($data);
      $this->model_traspaso->eliminar_traspaso_definitivo($data);

          if ( $actualizar !== FALSE ){
              $data['exito']  = true;
              echo json_encode($data);
          } else {
              $data['exito']  = false;
              $data['error'] = '<span class="error">No se ha podido actualizar el producto</span>';
              echo json_encode($data);
          }
  } 


  //impresion del traspaso que se realizo al confirmar
  public function imprimir_detalle_traspaso_post(){ 

     if($this->session->userdata('session') === TRUE ){
        
        $misdatos = json_decode($this->input->post('datos'));
          
        $data['consecutivo_unico'] =base64_encode($misdatos->consecutivo_unico);         
        $data['consecutivo'] =base64_encode($misdatos->consecutivo);         
        $data['id_factura'] =base64_encode($misdatos->id_factura);         

        $this->imprimir_detalle_historico_traspaso($data['consecutivo_unico'],$data['id_factura']);

    }
  } 


   // impresion de "confirmar" y de  "historico detalles"
 public function imprimir_detalle_historico_traspaso($consecutivo_traspaso,$id_factura) {

        $data['consecutivo_traspaso'] = base64_decode($consecutivo_traspaso);
        $data['id_factura'] = base64_decode($id_factura);

        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 



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
        
          $dato['id'] = 5;
          $data['configuracion'] = $this->catalogo->coger_configuracion($dato);

          $data['movimientos'] = $this->model_traspaso->imprimir_traspaso_historico_detalle($data);        
          $data['totales'] = $this->model_traspaso->totales_imprimir_traspaso_historico_detalle($data);        
          $html = $this->load->view('pdfs/traspasos/detalles_historicos', $data, true);


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("traspaso_".$data['consecutivo_traspaso'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
}    

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
      ///////////////////////Reporte de traspaso
/////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////Listado de traspaso///////////////////////////
  public function listado_traspaso(){

      if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         }   
         $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
         
         $data['facturas']    = $this->catalogo->catalogo_tipos_facturas();
           
          switch ($id_perfil) {    
            case 1:          
                        $this->load->view( 'traspaso/listado_traspaso',$data );
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(26, $data['coleccion_id_operaciones']))  {                 
                            $this->load->view( 'traspaso/listado_traspaso',$data );
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



  //regilla1 reporte traspaso
  public function procesando_general_traspaso(){  //907 consecutivo_traspaso_unico
    $data=$_POST;
      $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
    $busqueda = $this->model_traspaso->buscador_general_traspaso($data); //ok no
    echo $busqueda;
  } 

  //regilla2 reporte traspaso
  public function procesando_traspaso_historico(){
    $data=$_POST;
      $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
    $busqueda = $this->model_traspaso->buscador_traspaso_historico($data); //ok no
    echo $busqueda;
  } 





// resumen de impresion
  public function impresion_traspaso_historico() {

        $data=$_POST;
        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 

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
        
          $data['movimientos'] = $this->model_traspaso->imprimir_traspaso_historico($data);        

          $data['totales'] = $this->model_traspaso->totales_imprimir_traspaso_historico($data);

        
          $html = $this->load->view('pdfs/traspasos/resumen_historico', $data, true);


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("traspaso_resumen".".pdf");
        $pdf->Output($nombre_archivo, 'I');
}    


  //1ra regilla "detalle traspaso en proceso"
  public function traspaso_general_detalle_manual($id_usuario,$id_almacen,$id_factura){ 
     if($this->session->userdata('session') === TRUE ){
              $id_perfil=$this->session->userdata('id_perfil');

                    
            $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
              if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                    $data['coleccion_id_operaciones'] = array();
               } 

               
               //no. movimiento $data
            $data['id_usuario'] = base64_decode($id_usuario);
            $data['id_almacen'] = base64_decode($id_almacen);
            $data['id_factura'] = base64_decode($id_factura);

            $data['id']=$data['id_almacen'];
            if ($data['id']==0){
              $data['almacen'] = 'Todos'; 
            } else {
              $data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
            }


              switch ($id_perfil) {    
                case 1:          
                           $this->load->view( 'traspaso/traspaso_general_detalle_manual',$data);
                  break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(26, $data['coleccion_id_operaciones']))  {            
                          $this->load->view( 'traspaso/traspaso_general_detalle_manual',$data);
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

  //2da regilla "detalle Historico de traspaso"
  public function traspaso_detalle($consecutivo_traspaso,$id_factura){
        if($this->session->userdata('session') === TRUE ){
              
              $id_perfil=$this->session->userdata('id_perfil');

                            
            $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
              if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                    $data['coleccion_id_operaciones'] = array();
               } 
    
            $data['consecutivo_traspaso'] = base64_decode($consecutivo_traspaso);       
            $data['id_factura'] = base64_decode($id_factura);       
           
              switch ($id_perfil) {    
                case 1:          
                           $this->load->view('traspaso/traspaso_detalle',$data);
                  break;
                case 2:
                case 3:
                case 4:
                      if  (in_array(26, $data['coleccion_id_operaciones']))  {            
                          $this->load->view('traspaso/traspaso_detalle',$data);
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

  
  //regilla interior de  "detalle traspaso en proceso"
  public function procesando_traspaso_general_detalle_manual(){ //**aqui
      $data=$_POST;
        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_traspaso->buscador_traspaso_general_detalle_manual($data);
      echo $busqueda;
  }


  //regilla interior de "historico detalles"
  public function traspaso_historico_detalle(){ //**
      $data=$_POST;
        $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
        if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
              $data['coleccion_id_operaciones'] = array();
         } 
      $busqueda = $this->model_traspaso->buscador_traspaso_historico_detalle($data);
      echo $busqueda;
  }


 //imprimir el traspaso interior que hay en  "detalle traspaso en proceso"
 public function imprimir_detalle_general_traspaso_manual($id_usuario,$id_almacen,$id_factura) {

           $data['id_usuario'] = base64_decode($id_usuario);
            $data['id_almacen'] = base64_decode($id_almacen);
            $data['id_factura'] = base64_decode($id_factura);

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
        
          $dato['id'] = 5;
          $data['configuracion'] = $this->catalogo->coger_configuracion($dato);

          $data['movimientos'] = $this->model_traspaso->imprimir_detalle_general_traspaso_manual($data);        
          $data['totales'] = $this->model_traspaso->total_imprimir_detalle_general_traspaso_manual($data); 
          $html = $this->load->view('pdfs/traspasos/detalles_generales_manual', $data, true);


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("traspaso_detalle_traspaso".$data['id_almacen'].".pdf");
        $pdf->Output($nombre_archivo, 'I');

 }







///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////
                  //NO SE
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////

//nose para cual caso se utiliza, quizas para cuando vengan de clientes de tipo tienda
  public function traspaso_general_detalle($num_movimiento,$id_apartado,$id_almacen,$id_factura){ 
    if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }   
           
           //no. movimiento $data
        $data['num_movimiento'] = base64_decode($num_movimiento);
        $data['id_apartado'] = base64_decode($id_apartado);
        $data['id_almacen'] = base64_decode($id_almacen);
        $data['id_factura'] = base64_decode($id_factura);

        $data['id']=$data['id_almacen'];
        if ($data['id']==0){
          $data['almacen'] = 'Todos'; 
        } else {
          $data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
        }


          switch ($id_perfil) {    
            case 1:          
                       $this->load->view( 'traspaso/traspaso_general_detalle',$data);
              break;
            case 2:
            case 3:
            case 4:
                  if  (in_array(26, $data['coleccion_id_operaciones']))  {            
                      $this->load->view( 'traspaso/traspaso_general_detalle',$data);
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

  public function procesando_traspaso_general_detalle(){ //**
      $data=$_POST;
      $busqueda = $this->model_traspaso->buscador_traspaso_general_detalle($data);
      echo $busqueda;
  }


 public function imprimir_detalle_general_traspaso($num_movimiento,$id_apartado,$id_almacen,$id_factura) {

        $data['num_movimiento'] = base64_decode($num_movimiento);
           $data['id_apartado'] = base64_decode($id_apartado);
            $data['id_almacen'] = base64_decode($id_almacen);
            $data['id_factura'] = base64_decode($id_factura);

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

          $dato['id'] = 5;
          $data['configuracion'] = $this->catalogo->coger_configuracion($dato);
        
          $data['movimientos'] = $this->model_traspaso->imprimir_traspaso_general_detalle($data);        
          $html = $this->load->view('pdfs/traspasos/detalles_generales', $data, true);


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("traspaso_".$data['num_movimiento'].".pdf");
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
