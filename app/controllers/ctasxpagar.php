<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ctasxpagar extends CI_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->model('model_pedido', 'modelo_pedido');
    $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
    $this->load->model('modelo_reportes', 'modelo_reportes');  
    $this->load->model('modelo_costo_inventario', 'modelo_costo_inventario');  
    $this->load->model('modelo_ctasxpagar', 'modelo_ctasxpagar');  
    
    
      $this->load->model('catalogo', 'catalogo');  
      $this->load->model('modelo', 'modelo');  

    $this->load->library(array('email')); 
    $this->load->library('Jquery_pagination');//-->la estrella del equipo 
  }


 //listado de ctas por pagar
  public function listado_ctasxpagar(){

     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 

              $data['almacenes']   = $this->modelo->coger_catalogo_almacenes(2);
              $data['facturas']   	= $this->catalogo->catalogo_tipos_facturas();
              
              $dato['id'] = 7;
              $data['config_factura'] = $this->catalogo->coger_configuracion($dato); 




          switch ($id_perfil) {    
            case 1:          

                        $this->load->view( 'ctasxpagar/ctasxpagar',$data );
              break;
            case 2:
            case 3:
            case 4:
              //|| (in_array(28, $data['coleccion_id_operaciones']))  
                  if ( ( (in_array(29, $data['coleccion_id_operaciones'])) || (in_array(30, $data['coleccion_id_operaciones'])) ) ) {                 
                            $this->load->view( 'ctasxpagar/ctasxpagar',$data );
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

 //1ra regilla (ctas vencidas)
 public function procesando_ctas_vencidas(){
      $data=$_POST;
      $data['tipo']='vencidas';
      if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                       $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                       $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                      
                      $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
      } else {
        $data['fecha_especifica'] = '';
      }

      $data['having'] = '(
                           (( monto_restante >0 ) OR ( monto_restante IS null ) )'.$data['fecha_especifica'].'  
                        )';    
      $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
      $busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
      echo $busqueda;
  } 

 //2da regilla (ctas por pagar)
 public function procesando_ctasxpagar(){

    $data=$_POST;
    $data['tipo']='porpagar';

          if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                          
                                 
                          $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $data['fecha_especifica'] = '';
          }

    
     $data['having'] = '(
                         (( monto_restante >0 ) OR ( monto_restante IS null )) '.$data['fecha_especifica'].'
                      )';  
    $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
               AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
  $busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
    echo $busqueda;
  } 

 //3ra regilla (ctas pagadas)
 public function procesando_ctas_pagadas(){

    $data=$_POST;
    $data['tipo']='pagadas';

    if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                     $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                     $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                    

                      $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

    } else {
     $data['fecha_especifica'] = '';
    }


     $data['having'] = '(
                         (( monto_restante <=0 )  )'.$data['fecha_especifica'].'
                      )';  

    $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) ';   //or ya esta pagado
  $busqueda  = $this->modelo_ctasxpagar->buscador_ctasxpagar($data);
    echo $busqueda;
  }   



 //1ra, 2da y 3ra entrar en el detalle del "monto"
  public function procesar_ctasxpagar($movimiento,$retorno,$id_factura){


     if($this->session->userdata('session') === TRUE ){
          $id_perfil=$this->session->userdata('id_perfil');
          //$data['movimiento'] = base64_decode($movimiento);
          $porciones = explode("-", base64_decode($movimiento));
          $data['tipo_entrada'] = $porciones[0];
          $data['movimiento'] = $porciones[1];
          


          $data['retorno'] = base64_decode($retorno);
          $data['id_factura'] = base64_decode($id_factura);

          $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
          if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
           } 
           
          $dato['id'] = 6;
          $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
          $dato['id'] = 7;
          $data['config_factura'] = $this->catalogo->coger_configuracion($dato); 


          switch ($id_perfil) {    
            case 1:          

                        $this->load->view( 'ctasxpagar/detalle_ctasxpagar',$data );
              break;
            case 2:
            case 3:
            case 4:
              //|| (in_array(28, $data['coleccion_id_operaciones'])) 
                  if ( ( (in_array(29, $data['coleccion_id_operaciones'])) || (in_array(30, $data['coleccion_id_operaciones'])) )  ) {

                            $this->load->view( 'ctasxpagar/detalle_ctasxpagar',$data );
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



 //1ra, 2da y 3ra regilla interior 
 public function procesando_pagos_realizados(){

    $data=$_POST;
    $dato['id'] = 6;
    $data['configuracion'] = $this->catalogo->coger_configuracion($dato);    
    $busqueda  = $this->modelo_ctasxpagar->buscador_pagosrealizados($data);
    echo $busqueda;
  }   

//1ra, 2da y 3ra "nuevo pago"
function nuevo_pago($movimiento,$id_factura){
    if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');
      //$data['movimiento']= base64_decode($movimiento);

      $porciones = explode("-", base64_decode($movimiento));
      $data['tipo_entrada'] = $porciones[0];
      $data['movimiento'] = $porciones[1];

      //print_r($data);die;

      $data['id_factura']= base64_decode($id_factura);
      $data['pago'] =  $this->modelo_ctasxpagar->nuevo_pago_realizado($data);

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }  

       $data['doc_pagos'] =  $this->catalogo->listado_documentos_pagos();
       $data['retorno']='procesar_ctasxpagar/'.base64_encode($data["tipo_entrada"].'-'.$data["movimiento"]).'/'.base64_encode("listado_ctasxpagar").'/'.base64_encode($data['id_factura']); 

      switch ($id_perfil) {    
        case 1:
            $this->load->view( 'ctasxpagar/nuevo_pago',$data);
          break;
        case 2:
        case 3:
        case 4:
             
             if ( ( (in_array(29, $data['coleccion_id_operaciones'])) || (in_array(30, $data['coleccion_id_operaciones'])) ) && (in_array(28, $data['coleccion_id_operaciones']))  ) { 
                $this->load->view( 'ctasxpagar/nuevo_pago',$data);
              }   
          break;


        default:  
          redirect('');
          break;
      }
    }
    else{ 
      redirect('index');
    }
  } 

  //1ra, 2da y 3ra "validando el nuevo pago"

  function validacion_nuevo_ctasxpagar(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      
      $this->form_validation->set_rules( 'instrumento_pago', ' Referencia', 'trim|required|min_length[3]|max_lenght[6]|xss_clean');
    $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|min_length[3]|max_lenght[180]|xss_clean');             
      $this->form_validation->set_rules( 'importe', 'importe', 'required|callback_importe_valido|xss_clean');   
      $this->form_validation->set_rules('fecha_pago', 'fecha', 'callback_valid_date|xss_clean');


      if ($this->form_validation->run() === TRUE){
          $data['id_documento_pago']   = $this->input->post('id_documento_pago');
          $data['instrumento_pago']   = $this->input->post('instrumento_pago');
          $data['importe']   = $this->input->post('importe');
          $data['comentario']   = $this->input->post('comentario');
          $data['fecha_pago']   = date('Y-m-d',strtotime($this->input->post('fecha_pago')));

          $data['movimiento']   = $this->input->post('movimiento');
          $data['tipo_entrada'] = $this->input->post('tipo_entrada');
          $data['total']   = $this->input->post('total');
          $data['id_factura']   = $this->input->post('id_factura');
          $data['id_almacen']   = $this->input->post('id_almacen');
          $data['id_empresa']   = $this->input->post('id_empresa');


          

          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->modelo_ctasxpagar->anadir_pago( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - El nuevo pago no pudo ser agregado</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }



  //ok(ya no se edita pago al parecer)
  function editar_pago_realizado( $id = '',$movimiento = '',$id_factura  ){
    if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }  

      $data['id']  =  base64_decode($id);
      $data['id_factura'] =  base64_decode($id_factura);

      $dato['id'] = 6;
      $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
      $data['doc_pagos'] =  $this->catalogo->listado_documentos_pagos();
      $data['pago'] =  $this->modelo_ctasxpagar->editar_pago_realizado($data);
      $data['retorno']='procesar_ctasxpagar/'.$movimiento.'/'.base64_encode("listado_ctasxpagar").'/'.base64_encode($data['id_factura']); 
      
      switch ($id_perfil) {    
        case 1:
                  $this->load->view( 'ctasxpagar/editar_pago', $data );
          break;
        case 2:
        case 3:
        case 4:
             if ( ( (in_array(29, $data['coleccion_id_operaciones'])) || (in_array(30, $data['coleccion_id_operaciones'])) ) && (in_array(31, $data['coleccion_id_operaciones']))  ) { 
                $this->load->view( 'ctasxpagar/editar_pago', $data ); 
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


  function validacion_edicion_ctasxpagar(){
    if ($this->session->userdata('session') !== TRUE) {
      redirect('');
    } else {
      
      $this->form_validation->set_rules( 'instrumento_pago', ' Referencia', 'trim|required|min_length[3]|max_lenght[6]|xss_clean');
    $this->form_validation->set_rules( 'comentario', 'Comentario', 'trim|min_length[3]|max_lenght[180]|xss_clean');             
      $this->form_validation->set_rules( 'importe', 'importe', 'required|callback_importe_valido|xss_clean');   
      $this->form_validation->set_rules('fecha_pago', 'fecha', 'callback_valid_date|xss_clean');

      

      if ($this->form_validation->run() === TRUE){
          $data['id_documento_pago']   = $this->input->post('id_documento_pago');
          $data['instrumento_pago']   = $this->input->post('instrumento_pago');
          $data['importe']   = $this->input->post('importe');
          $data['comentario']   = $this->input->post('comentario');
          
          $data['fecha_pago']   = date('Y-m-d',strtotime($this->input->post('fecha_pago')));

            //$data['movimiento']   = $this->input->post('movimiento');
          $data['id']   = $this->input->post('id');
          $data['total']   = $this->input->post('total');
          $data['id_factura']   = $this->input->post('id_factura');
          $data['id_almacen']   = $this->input->post('id_almacen');
          $data['id_empresa']   = $this->input->post('id_empresa');


          $data         =   $this->security->xss_clean($data);  
          $guardar            = $this->modelo_ctasxpagar->editar_pago( $data );
          if ( $guardar !== FALSE ){
            echo true;
          } else {
            echo '<span class="error"><b>E01</b> - El nuevo pago no pudo ser agregado</span>';
          }
      } else {      
        echo validation_errors('<span class="error">','</span>');
      }
    }
  }



/////////////////////////////////////////Eliminar/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//ok
 function eliminar_pago($id = '', $instrumento_pago='',$movimiento,$id_factura){
      if($this->session->userdata('session') === TRUE ){
      $id_perfil=$this->session->userdata('id_perfil');

       $data['coleccion_id_operaciones']= json_decode($this->session->userdata('coleccion_id_operaciones')); 
       if ( (count($data['coleccion_id_operaciones'])==0) || (!($data['coleccion_id_operaciones'])) ) {
                $data['coleccion_id_operaciones'] = array();
       }  

            $data['instrumento_pago']   = base64_decode($instrumento_pago);
            $data['id']   = base64_decode($id);
            $data['id_factura']   = base64_decode($id_factura);
            $data['retorno']='procesar_ctasxpagar/'.$movimiento.'/'.base64_encode("listado_ctasxpagar").'/'.base64_encode($data['id_factura']); 

      switch ($id_perfil) {    
        case 1:
            
            $this->load->view( 'ctasxpagar/eliminar_pago', $data );

          break;
        case 2:
        case 3:
        case 4:
            if ( ( (in_array(29, $data['coleccion_id_operaciones'])) || (in_array(30, $data['coleccion_id_operaciones'])) ) && (in_array(32, $data['coleccion_id_operaciones']))  ) { 
                $this->load->view( 'ctasxpagar/eliminar_pago', $data );
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


  function validar_eliminar_pago(){
    /*
    if (!empty($_POST['id'])){ 
      $data['id'] = $_POST['id'];
    }
    */
    $data['id']   = $this->input->post('id');
    $data['id_factura']   = $this->input->post('id_factura');
    $eliminado = $this->modelo_ctasxpagar->eliminar_pago(  $data );
    if ( $eliminado !== FALSE ){
      echo TRUE;
    } else {
      echo '<span class="error">No se ha podido eliminar el pago</span>';
    }
  }   




//1ra, 2da, 3ra "Imprimir interior detalles"

  public function impresion_ctas_detalle() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;

          //  $data['totales'] = $this->modelo_ctasxpagar->totales_impresion_pagosrealizados($data);
        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_pagosrealizados($data);
        $html = $this->load->view('pdfs/ctasxpagar/detalle', $data, true);
        //print_r($data['totales']) ;
        //die;

        /////////////

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$data['movimiento'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }




    //1ra, 2da, 3ra "Imprimir EXTERIOR"
    public function impresion_ctasxpagar() {
        
        $extra_search = ($this->input->post('extra_search'));

        $data=$_POST;
          

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['having'] = '(
                                       (( monto_restante >0 ) OR ( monto_restante IS null )  )'.$data['fecha_especifica'].'
                                    )';    
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;
            case "xpagar":
                  
                  $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }


                  $data['having'] = '(
                                       ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                       (( monto_restante <=0 ) )'.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
         

            default:
        }

        $data['totales'] = json_decode($this->modelo_ctasxpagar->totales_importes_ctas($data));

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctasxpagar($data);
        $html = $this->load->view('pdfs/ctasxpagar/'.$extra_search, $data, true);
             /////////////

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$extra_search.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }



//1ra (antiguedad)
public function impresion_ctas_antiguedad() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
          

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['having'] = '(
                                       (( monto_restante >0 ) OR ( monto_restante IS null )  )'.$data['fecha_especifica'].'
                                    )';    
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;
            case "xpagar":
                  
                  $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }


                  $data['having'] = '(
                                       ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                    )';  

                  $data["condicion"]=' AND 
                                    ( (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar)<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado

                  

                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                       (( monto_restante <=0 ))'.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
         

            default:
        }


        

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_antiguedad($data);
        $html = $this->load->view('pdfs/ctasxpagar/'.$extra_search.'_antiguedad', $data, true);
         //print_r($data['totales']) ;
      //die;

        /////////////

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$extra_search.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }

//2da impresion (Reporte de Pago General)
    public function impresion_ctas_especificas_rapida() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
          

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['having'] = '(
                                       (( monto_restante >0 ) OR ( monto_restante IS null )  )'.$data['fecha_especifica'].'
                                    )';    
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;
            case "xpagar":
                  
                  $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }


                  $data['having'] = '(
                                       ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                       (( monto_restante <=0 ) )'.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
         

            default:
        }


        
        //ok
        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_especificas($data);
        $html = $this->load->view('pdfs/ctasxpagar_rapida/'.$extra_search.'_especifica', $data, true);
        echo $html;
    }


//3ra

public function impresion_ctas_detalladas() {

        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                                    
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                        FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                        INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                        AND pr1.id_factura=m1.id_factura
                                        where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                        GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa
                                        )  ) ) > 0
                        ) OR
                        (
                              (                                
                                              ( m.total+(
                                              (SELECT 
                                              sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                              FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                              INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento AND pr1.id_factura=m1.id_factura
                                              where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura


                                              GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa
                                              )  ) ) IS null
                              ) 
                              )
                        )'.$data['fecha_especifica'];   


                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;

            case "xpagar":
                  
                  $data['tipo']='porpagar';


                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }    


                $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                        FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                        INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                        AND pr1.id_factura=m1.id_factura
                                        where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                        GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa                                        


                                        )  ) ) > 0
                        ) OR
                        (
                              (                                
                                              ( m.total+(
                                              (SELECT 
                                              sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                              
                                                FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                                AND pr1.id_factura=m1.id_factura
                                                where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                                GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa                                        

                                              )  ) ) IS null
                              ) 
                              )
                        )'.$data['fecha_especifica'];                          


                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $data['fecha_inicial3'] = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $data['fecha_final3'] = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  $data['otro'] =' AND 
                                     (
                                       (                                
                                          SELECT  MAX(DATE_FORMAT(pr1.fecha_pago,"%Y-%m-%d")) 
                                                FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento_unico = m1.movimiento_unico
                                                AND pr1.id_factura=m1.id_factura AND pr1.id_operacion=m1.id_operacion
                                                where m1.movimiento_unico = m.movimiento_unico AND m1.id_factura=m.id_factura  AND m1.id_operacion=m.id_operacion
                                                GROUP BY m1.id,m1.movimiento_unico, m1.id_factura, m1.id_empresa    


                                       )  >=  "'.$data['fecha_inicial3'].'" 
                                     ) AND 
                                     (
                                       (                                
                                          SELECT  MAX(DATE_FORMAT(pr1.fecha_pago,"%Y-%m-%d")) 
                                               FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento_unico = m1.movimiento_unico
                                                AND pr1.id_factura=m1.id_factura AND pr1.id_operacion=m1.id_operacion
                                                where m1.movimiento_unico = m.movimiento_unico AND m1.id_factura=m.id_factura AND m1.id_operacion=m.id_operacion
                                                GROUP BY m1.id,m1.movimiento_unico, m1.id_factura, m1.id_empresa    



                                       )  <=  "'.$data['fecha_final3'].'" 
                                     )
                               ';



                  } else {
                   $data['otro'] = '';
                  }            


                $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                         FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento_unico = m1.movimiento_unico
                                                AND pr1.id_factura=m1.id_factura AND pr1.id_operacion=m1.id_operacion
                                                where m1.movimiento_unico = m.movimiento_unico AND m1.id_factura=m.id_factura AND m1.id_operacion=m.id_operacion
                                                GROUP BY m1.id,m1.movimiento_unico, m1.id_factura, m1.id_empresa    
                                        )  ) ) <= 0
                        ) 
                        )'.$data['otro'];                          
                  


                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
            default:
        }


        //$data['totales'] = json_decode($this->modelo_ctasxpagar->totales_importes_ctas($data));

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_detalladas($data);
        $html = $this->load->view('pdfs/ctasxpagar/'.$extra_search.'_detalle', $data, true);
         //print_r($data['totales']) ;
      //die;

        /////////////

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$extra_search.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }


    //exportaciones
    public function exportar_ctasxpagar() {
         $this->load->library('export');

        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
         $nombre_completo=$this->session->userdata('nombre_completo');
        switch($extra_search) {
          case "vencidas":
                 $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }

                $data['having'] = '(
                                    ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                  )';    
                $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                          AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
              break;
            case "xpagar":

                $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }


                $data['having'] = '(
                                    ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                  )'; 
                                  //<=0 ) 
                  $data["condicion"]=' AND ( (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar) <= 0 ) 
                          AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                     
                break;
            case "pagadas":    

                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                         ( ( monto_restante <=0 )  ) '.$data['fecha_especifica'].'
                                      )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 

              break;
         

            default:
        }



        $data['movimientos'] = $this->modelo_ctasxpagar->exportar_ctasxpagar($data);
      //print_r($data['movimientos']) ;
      //die;

        if ($data['movimientos']) {
            $this->export->to_excel($data['movimientos'], 'reporte_ctas_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
        }    


    } 


    ////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////
                //NOSE
    ////////////////////////////////////////////////////////////////////////////////

    public function impresion_ctas_especificas() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
          

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['having'] = '(
                                       (( monto_restante >0 ) OR ( monto_restante IS null )  )'.$data['fecha_especifica'].'
                                    )';    
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;
            case "xpagar":
                  
                  $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }


                  $data['having'] = '(
                                       ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                       (( monto_restante <=0 ) )'.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
         

            default:
        }


        //$data['totales'] = json_decode($this->modelo_ctasxpagar->totales_importes_ctas($data));

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_especificas($data);
        $html = $this->load->view('pdfs/ctasxpagar/'.$extra_search.'_especifica', $data, true);
         //print_r($data['totales']) ;
      //die;

        /////////////

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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".$extra_search.".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }



///////////////////////////////////////////3- imprimir/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function impresion_ctas_detalladas_rapida() {

        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                                    
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                        FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                        INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                        AND pr1.id_factura=m1.id_factura
                                        where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                        GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa
                                        )  ) ) > 0
                        ) OR
                        (
                              (                                
                                              ( m.total+(
                                              (SELECT 
                                              sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                              FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                              INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento AND pr1.id_factura=m1.id_factura
                                              where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura


                                              GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa
                                              )  ) ) IS null
                              ) 
                              )
                        )'.$data['fecha_especifica'];   


                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;

            case "xpagar":
                  
                  $data['tipo']='porpagar';


                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((DATE_ADD(fecha_entrada, INTERVAL p.dias_ctas_pagar DAY)),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }    


                $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                        FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                        INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                        AND pr1.id_factura=m1.id_factura
                                        where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                        GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa                                        


                                        )  ) ) > 0
                        ) OR
                        (
                              (                                
                                              ( m.total+(
                                              (SELECT 
                                              sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                              
                                                FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                                AND pr1.id_factura=m1.id_factura
                                                where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                                GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa                                        

                                              )  ) ) IS null
                              ) 
                              )
                        )'.$data['fecha_especifica'];                          


                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado
                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $data['fecha_inicial3'] = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $data['fecha_final3'] = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  $data['otro'] =' AND 
                                     (
                                       (                                
                                          SELECT  MAX(DATE_FORMAT(pr1.fecha_pago,"%Y-%m-%d")) 
                                                FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                                AND pr1.id_factura=m1.id_factura
                                                where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                                GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa    


                                       )  >=  "'.$data['fecha_inicial3'].'" 
                                     ) AND 
                                     (
                                       (                                
                                          SELECT  MAX(DATE_FORMAT(pr1.fecha_pago,"%Y-%m-%d")) 
                                               FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                                AND pr1.id_factura=m1.id_factura
                                                where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                                GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa    



                                       )  <=  "'.$data['fecha_final3'].'" 
                                     )
                               ';



                  } else {
                   $data['otro'] = '';
                  }            


                $data['filtro']  = ' AND (
                        (                                
                                        ( m.total+(
                                        (SELECT 
                                        sum((pr1.id_documento_pago <> 12)*pr1.importe*-1)+sum((pr1.id_documento_pago = 12)*pr1.importe)                                         
                                         FROM '.$this->db->dbprefix('historico_ctasxpagar').' m1
                                                INNER JOIN '.$this->db->dbprefix('historico_pagos_realizados').' pr1 ON pr1.movimiento = m1.movimiento
                                                AND pr1.id_factura=m1.id_factura
                                                where m1.movimiento = m.movimiento AND m1.id_factura=m.id_factura
                                                GROUP BY m1.movimiento, m1.id_factura, m1.id_empresa    
                                        )  ) ) <= 0
                        ) 
                        )'.$data['otro'];                          
                  

                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
            default:
        }


        //$data['totales'] = json_decode($this->modelo_ctasxpagar->totales_importes_ctas($data));

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_detalladas($data);
        $html = $this->load->view('pdfs/ctasxpagar_rapida/'.$extra_search.'_detalle', $data, true);
        echo $html;
    }





///////////////////////////////////////////4- imprimir/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function impresion_ctas_antiguedad_rapida() {
        
        $extra_search = ($this->input->post('extra_search'));
        $data=$_POST;
          

        switch($extra_search) {
            case "vencidas":
         $data['tipo']='vencidas';
                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                  } else {
                    $data['fecha_especifica'] = '';
                  }            

                  $data['having'] = '(
                                       (( monto_restante >0 ) OR ( monto_restante IS null )  )'.$data['fecha_especifica'].'
                                    )';    
                  $data["condicion"]=' AND (DATEDIFF( NOW( ) ,  m.fecha_entrada )-p.dias_ctas_pagar>0 ) 
                            AND (m.id_tipo_pago<>2 ) ';  // y no se ha pagado
                  break;
            case "xpagar":
                  
                  $data['tipo']='porpagar';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  
                                         
                                  $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_ven,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }


                  $data['having'] = '(
                                       ( ( monto_restante >0 ) OR ( monto_restante IS null ) ) '.$data['fecha_especifica'].'
                                    )';  

                  $data["condicion"]=' AND 
                                    ( (DATEDIFF( NOW( ) ,  fecha_entrada )-p.dias_ctas_pagar)<=0 ) 
                             AND (m.id_tipo_pago<>2 ) '; // y no se ha pagado

                  

                break;
            case "pagadas":    
                  $data['tipo']='pagadas';

                  if  ( ($data['fecha_inicial2'] !="") and  ($data['fecha_final2'] !="")) {
                                   $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial2'] ));
                                   $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final2'] ));
                                  

                                    $data['fecha_especifica'] =  ' AND ( ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT(fecha_pago,"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

                  } else {
                   $data['fecha_especifica'] = '';
                  }            
                 $data['having'] = '(
                                       (( monto_restante <=0 ))'.$data['fecha_especifica'].'
                                    )';  
                  $data["condicion"]=' AND ( (m.id_tipo_pago<>2)) '; 
              break;
         

            default:
        }


        

        $data['movimientos'] = $this->modelo_ctasxpagar->impresion_ctas_antiguedad($data);
        $html = $this->load->view('pdfs/ctasxpagar_rapida/'.$extra_search.'_antiguedad', $data, true);
        echo $html;
    }





///////////////////////////////////////////4- imprimir/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    


  


/////////////////validaciones/////////////////////////////////////////  




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


  



  public function valid_email($str)
  {
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
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



}

/* End of file nucleo.php */
/* Location: ./app/controllers/nucleo.php */