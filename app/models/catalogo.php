<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class catalogo extends CI_Model {
		
		private $key_hash;
		private $timezone;

		function __construct(){

			parent::__construct();
			$this->load->database("default");
			$this->key_hash    = $_SERVER['HASH_ENCRYPT'];
			$this->timezone    = 'UM1';

      
				//usuarios
			$this->usuarios                       = $this->db->dbprefix('usuarios');
				//catalogos			
			$this->composiciones                  = $this->db->dbprefix('catalogo_composicion');
      $this->colores                        = $this->db->dbprefix('catalogo_colores');
      $this->anchos                         = $this->db->dbprefix('catalogo_ancho');
      $this->cargadores                     = $this->db->dbprefix('catalogo_cargador');
      $this->calidades                      = $this->db->dbprefix('catalogo_calidad');

      $this->proveedores                    = $this->db->dbprefix('catalogo_empresas');
      $this->actividad_comercial            = $this->db->dbprefix('catalogo_actividad_comercial');
      $this->operaciones                    = $this->db->dbprefix('catalogo_operaciones');
      $this->estatuss                       = $this->db->dbprefix('catalogo_estatus');
      $this->lotes                          = $this->db->dbprefix('catalogo_lotes');

      
      $this->unidades_medidas               = $this->db->dbprefix('catalogo_unidades_medidas');

      
      $this->productos                      = $this->db->dbprefix('catalogo_productos');
      $this->catalogo_destinos                      = $this->db->dbprefix('catalogo_destinos');
      
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
      $this->registros_entradas             = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas    = $this->db->dbprefix('historico_registros_salidas');

      $this->almacenes                         = $this->db->dbprefix('catalogo_almacenes');
      $this->configuraciones                         = $this->db->dbprefix('catalogo_configuraciones');

      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
      $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
      $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');
		  $this->catalogo_tipos_pagos  = $this->db->dbprefix('catalogo_tipos_pagos');

      $this->catalogo_documentos_pagos  = $this->db->dbprefix('catalogo_documentos_pagos');

      $this->catalogo_tiendas  = $this->db->dbprefix('catalogo_tiendas');


      
      $this->consecutivo_general  = $this->db->dbprefix('consecutivos');

   
    }


 



        public function check_codigo_contable($data){
                $this->db->select("id", FALSE);         
                $this->db->from($this->productos);

                $where = '(
                              ( id <> '.$data['id'].' ) AND
                              ( codigo_contable =  "'.$data['codigo_contable'].'" )
                  )';   

                $this->db->where($where);  
                
                $login = $this->db->get();
                if ($login->num_rows() > 0)
                    return true;
                else
                    return false;
                $login->free_result();
        } 

        public function listado_documentos_pagos(){
          
          $this->db->select('c.id, c.documento_pago');
          $this->db->from($this->catalogo_documentos_pagos.' as c');
         
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        


        public function listado_tipos_pagos(){
          
          $this->db->select('c.id, c.tipo_pago');
          $this->db->from($this->catalogo_tipos_pagos.' as c');
         
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        


////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

    public function lista_destino(){
            //distinct
            $this->db->distinct();
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.nombre", FALSE);  
            $this->db->from($this->catalogo_destinos.' as c');
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
    }    


    public function marcando_activo( $data ){
              $id_session = $this->session->userdata('id');
              $this->db->set( 'activo', '(1 XOR activo)', FALSE );
              $this->db->where('id', $data['id'] );
              $this->db->update($this->productos );
              return true;
        }  

 

        public function total_cat_proveedores($where_total){
            


              $id_session = $this->session->userdata('id');

              $this->db->from($this->proveedores.' as p');

              if ($where_total!='') {
                $this->db->where($where_total);
              }

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_cat_proveedores($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'p.codigo';
                     break;
                   case '1':
                        $columna = 'p.nombre';
                     break;
                   case '2':
                        $columna = 'p.telefono';
                     break;
                   
                   case '3':
                        $columna = 'p.coleccion_id_actividad';
                     break;
                   case '4':
                        $columna = 'p.dias_ctas_pagar';
                     break;

                   default:
                        $columna = 'p.id';
                     break;
                 }                 




                ///////////**********Aqui comienza la restriccion*********///////////////////// 

         $id_perfil=$this->session->userdata('id_perfil');        

        //devuelve un arreglo de todas las operaciones asociadas al usuario activo           
        $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

        //me aseguro que el arreglo se haga vacio sino tiene operaciones asociadas    
         if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
              $coleccion_id_operaciones = array();
         }   



        //La tabla "inven_catalogo_actividad_comercial". tiene asociado como id
        // 1- proveedores, 2- cliente, 3- comprador
        //Des esta misma manera se guarda en la tabla Empresa

        $identificador ='';
        if   (in_array(14, $coleccion_id_operaciones))    {  //proveedor = 14 ->1
              $identificador .='(LOCATE("1", p.coleccion_id_actividad) >0)';
        }  

        if   (in_array(15, $coleccion_id_operaciones))    { //cliente = 15  -> 2
             if ($identificador!='') {$identificador.=' OR '; }
             $identificador .='(LOCATE("2", p.coleccion_id_actividad) >0)';
          }  
         
        if   (in_array(16, $coleccion_id_operaciones))    { //comprador = 16 ->1
             if ($identificador!='') {$identificador.=' OR '; }
             $identificador .='(LOCATE("3", p.coleccion_id_actividad) >0)';
         }   

         $where_total = $identificador;

          ///////////**********Aqui termina las restriccion*********///////////////////// 
                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
         
          $this->db->select('p.id, p.uid, p.codigo, p.nombre,  p.direccion, p.telefono,  p.coleccion_id_actividad, p.id_usuario, p.fecha_mac'); 

          $this->db->select("( CASE WHEN ( LOCATE('1', p.coleccion_id_actividad) >0)  THEN 'Proveedor' else '' END ) AS p1",FALSE);
          $this->db->select("( CASE WHEN ( LOCATE('2', p.coleccion_id_actividad) >0)  THEN 'Sucursal' else '' END ) AS p2",FALSE);
          $this->db->select("( CASE WHEN ( LOCATE('3', p.coleccion_id_actividad) >0)  THEN 'Cliente' else '' END ) AS p3",FALSE);

          $this->db->select("p.dias_ctas_pagar");

          

          $this->db->from($this->proveedores.' as p');
         // $this->db->join($this->actividad_comercial.' As a', 'a.id =  mid(p.coleccion_id_actividad,LOCATE(a.id, p.coleccion_id_actividad),1)');
       
          $where = '(

                      (
                        ( p.id LIKE  "%'.$cadena.'%" ) OR (p.codigo LIKE  "%'.$cadena.'%") OR
                        ( p.nombre LIKE  "%'.$cadena.'%" ) OR (p.direccion LIKE  "%'.$cadena.'%") OR 
                        (p.telefono LIKE  "%'.$cadena.'%")
                        
                       )
            )';   



  
          //
          
          if ($identificador!=''){
             $this->db->where($where.' AND ('.$identificador.')');  
          } else {
            $this->db->where($where);
          }
          
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  



                  foreach ($result->result() as $row) {


                            $activ_id_array =(json_decode($row->coleccion_id_actividad) );  

                              if (count($activ_id_array)==0) {  //si el valor esta vacio
                                  $activ_id_array = array();
                                }
                              if (!($activ_id_array)) {
                                $activ_id_array = array();  
                              }


                                $desabilitar= self::proveedores_en_uso($row->id);


                              //si el 8 no pertenece a la colleccion que tengo
                              // o perfil no es 1
                              //y actividad no esta en la colleccion que tengo pues entonces q desabilite ese      

                            foreach ( $activ_id_array as $identifica ){ 
                                if  ( (!( ( $id_perfil == 1 ) || (in_array(8, $coleccion_id_operaciones)) ) )
                                      and (!(in_array($identifica+13, $coleccion_id_operaciones))) ) { 
                                  $desabilitar = 1;
                                }
                            }   




                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->codigo,
                                      2=>$row->nombre,
                                      3=>$row->telefono,
                                      4=>$row->p1.'<br/>'.$row->p2.'<br/>'.$row->p3,      
                                      5=>$desabilitar,
                                      6=>$row->dias_ctas_pagar,
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_proveedores($where_total) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      } 

///////////////////////







        public function total_cat_calidades(){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->calidades.' as c');


              //$this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_cat_calidades($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.calidad';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.calidad');

          $this->db->from($this->calidades.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.calidad LIKE  "%'.$cadena.'%")
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->calidad,
                                      2=>self::calidades_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_calidades() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      } 



        public function total_cat_composiciones(){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->composiciones.' as c');


              //$this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_cat_composiciones($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.composicion';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.composicion');

          $this->db->from($this->composiciones.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.composicion LIKE  "%'.$cadena.'%")
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->composicion,
                                      2=>self::composiciones_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_composiciones() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      } 

       

////////////////////
        public function total_cat_cargadores(){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->cargadores.' as c');


              //$this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_cat_cargadores($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.nombre';
                     break;
                   case '2':
                        //$columna = 'c.color, hexadecimal_color';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.nombre, c.estatus');

          $this->db->from($this->cargadores.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.nombre LIKE  "%'.$cadena.'%") OR (c.estatus LIKE  "%'.$cadena.'%") 
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->nombre,
                                      2=>$row->estatus,
                                      3=>self::cargadores_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_cargadores() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      } 


/////////////////////////        





        public function total_cat_colores(){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->colores.' as c');


              //$this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     


      public function buscador_cat_colores($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.color';
                     break;
                   case '2':
                        $columna = 'c.color, hexadecimal_color';
                     break;
                   
                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.color, c.hexadecimal_color');

          $this->db->from($this->colores.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.color LIKE  "%'.$cadena.'%") OR (c.hexadecimal_color LIKE  "%'.$cadena.'%") 
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->color,
                                      2=>
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->hexadecimal_color,

                                      4=>self::colores_en_uso($row->id),
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_colores() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      















  public function buscador_cat_configuraciones($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'c.configuracion';
                     break;
                   case '1':
                        $columna = 'c.valor';
                     break;

                   case '2':
                        $columna = 'c.activo';
                     break;

                   default:
                        $columna = 'c.id';
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //
          
          $this->db->select('c.id, c.configuracion,c.valor,c.activo');

          $this->db->from($this->configuraciones.' as c');
          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( c.id LIKE  "%'.$cadena.'%" ) OR (c.configuracion LIKE  "%'.$cadena.'%")
                        OR (c.activo LIKE  "%'.$cadena.'%") OR (c.valor LIKE  "%'.$cadena.'%")
                        
                       )
            )';   



  
          $this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->configuracion,
                                      2=>$row->activo,
                                      3=>$row->valor,
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_cat_configuraciones() ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      } 


  public function total_cat_configuraciones(){
              $id_session = $this->session->userdata('id');

              $this->db->from($this->configuraciones.' as c');

              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     






///////////



      public function buscador_cat_producto($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 
           /*
           if ($data['comenzar'] == true) {
                 $columa_order ='-1';
                 $order = 'desc';
            
           }*/


          switch ($columa_order) {
                   case '0':
                        $columna = 'p.descripcion';
                     break;
                   case '1':
                        $columna = 'p.referencia';
                     break;
                   case '2':
                        $columna = 'p.minimo';
                     break;
                   case '3':
                        $columna = 'p.minimo_kg';
                     break;                     
                   case '4':
                        $columna = 'p.imagen';
                     break;
                   case '5':
                        $columna = 'c.color';
                     break;

                   case '6':
                        $columna = 'p.consecutivo';
                     break;

                   case '7':
                        $columna = 'co.composicion';
                     break;

                   case '8':
                        $columna = 'ca.calidad';
                     break;

                   case '9':
                        $columna = 'p.precio';
                     break;
                   
                   default:
                        $columna = 'p.fecha_mac';
                     break;
                 }                 

          $descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.id)"); //
          
          $this->db->select('p.id, p.uid, p.referencia,  p.comentario,p.consecutivo');
          $this->db->select('p.descripcion, p.minimo,p.minimo_kg, p.imagen, p.id_composicion, p.id_color,p.id_calidad,p.precio,p.ancho,p.codigo_contable');
          $this->db->select('p.id_usuario, p.fecha_mac, c.hexadecimal_color,c.color nombre_color');

          $this->db->select('co.composicion, ca.calidad, p.activo');

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); //,'LEFT'
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); //,'LEFT'
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); //,'LEFT'

          
          //filtro de busqueda
       
          $where = '(

                      (
                        ( p.descripcion LIKE  "%'.$cadena.'%" ) OR (p.referencia LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        (p.codigo_contable LIKE  "%'.$cadena.'%")  OR
                        ( p.minimo LIKE  "%'.$cadena.'%" ) OR
                        ( p.minimo_kg LIKE  "%'.$cadena.'%" ) 
                        
                       )

            )';   


          
        /*
          if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
              $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
          }    

          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
              $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_total .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_total .= '( p.descripcion  =  "'.$descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
          }  

          elseif  (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null)) {
              $where .= ' AND ( p.descripcion  =  "'.$descripcion.'" )';
              $where_total  .= '( p.descripcion  =  "'.$descripcion.'" )';
          } */


          $where_total ='';
          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
               $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
               $where_total.= (($where_total!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
            }     
            if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                $where_total.= (($where_total!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
            } 
            if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
               $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
               $where_total.= (($where_total!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
            }
            
            //if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
            if (($descripcion!="0") AND ($descripcion!="") AND ($descripcion!= null))  {                
                $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$descripcion."' )";
                $where_total.= (($where_total!="") ? " and " : "") . "( p.descripcion  =  '".$descripcion."' )";
            }


          $data['where_total']=$where_total;

  
          //$this->db->where($where);
    
          //ordenacion
          $this->db->order_by($columna, $order); 

          //paginacion
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {

                          // $data['referencia'] =$row->referencia;
                          // $data['ref'] = self::referencias_en_uso($data['referencia']);


                          //variables para cachear las imagenes                                                  
                          $fechaSegundos = time(); 
                          $strNoCache = "?nocache=$fechaSegundos"; 

                        $nombre_fichero ='';
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            
                            $imagen ='<img src="'.base_url().$nombre_fichero.$strNoCache.'" border="0" width="100%" height="auto">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }



                            $dato[]= array(
                                      
                                      0=>$row->descripcion,
                                      1=>$row->referencia,
                                      2=>$row->minimo,
                                      3=>$imagen,  
                                      4=>$row->nombre_color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                       5=>$row->id, 
                                       6=>self::referencias_en_uso($row->referencia),
                                       7=>$row->consecutivo, 
                                       8=>$row->composicion, 
                                       9=>$row->calidad, 
                                       10=>$row->precio, 
                                       11=>$row->activo, 
                                       12=>$row->codigo_contable,
                                       13=>$row->minimo_kg
                                    );
                      }




                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  //intval( self::total_cat_productos($data) ), 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato 
                      ));
                    
              }   
              else {
                  //cuando este vacio la tabla que envie este
                //http://www.datatables.net/forums/discussion/21311/empty-ajax-response-wont-render-in-datatables-1-10
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           

      }  
      

///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

 public function referencias_en_uso($referencia) {

          $result = $this->db->query("
            select distinct r.referencia from (

            (select distinct referencia from ".$this->registros_temporales.")
              union   
            (select distinct referencia from ".$this->registros_cambios.")
              union   

            (select distinct referencia from ".$this->registros_entradas.")
              union   

            (select distinct referencia from ".$this->registros_salidas.")
              union   

            (select distinct referencia from ".$this->historico_registros_entradas.")
              union   

            (select distinct referencia from ".$this->historico_registros_salidas.")
              ) r 
           where r.referencia='".$referencia."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    


 public function proveedores_en_uso($id_empresa) {

          $result = $this->db->query("
            select distinct r.id_empresa from (

            (select distinct id_empresa from ".$this->historico_registros_entradas.")
              union   
            (select distinct id_empresa from ".$this->registros_cambios.")
              union   
            (select distinct id_empresa from ".$this->registros_temporales.")
              union   
            (select distinct id_empresa from ".$this->registros_entradas.")
              union   
            (select distinct id_cliente_apartado id_empresa from ".$this->registros_entradas." where id_apartado=2)
              union   


            (select distinct id_empresa from ".$this->registros_salidas.")
              union   
            (select distinct id_cliente id_empresa from ".$this->registros_salidas.")
              union   
  
            (select distinct id_empresa from ".$this->historico_registros_salidas.")
              union
            (select distinct id_cliente id_empresa from ".$this->historico_registros_salidas.")

              ) r 
           where r.id_empresa='".$id_empresa."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    



 public function proveedores_en_uso1() {

  $result = $this->db->query("
            select distinct r.referencia from (

            (select distinct referencia from ".$this->registros_temporales.")
              union   
            (select distinct referencia from ".$this->registros_cambios.")
              union   

            (select distinct referencia from ".$this->registros_entradas.")
              union   

            (select distinct referencia from ".$this->registros_salidas.")
              union   

            (select distinct referencia from ".$this->historico_registros_entradas.")
              union   

            (select distinct referencia from ".$this->historico_registros_salidas.")
              ) r 

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return $result->result();
              } else 
                  return 0;
            $result->free_result();                 

      }    



 public function cargadores_en_uso($id_cargador) {

          $result = $this->db->query("
            select distinct r.id_cargador from (

            (select distinct id_cargador from ".$this->registros_temporales.")
              union   
            (select distinct id_cargador from ".$this->registros_cambios.")
              union   

            (select distinct id_cargador from ".$this->registros_entradas.")
              union   

            (select distinct id_cargador from ".$this->registros_salidas.")
              union   

            (select distinct id_cargador from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_cargador from ".$this->historico_registros_salidas.")
              ) r 
           where r.id_cargador='".$id_cargador."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    






 public function calidades_en_uso($id_calidad) {

          $result = $this->db->query("
            select distinct r.id_calidad from (

            (select distinct id_calidad from ".$this->productos.")
              union   

            (select distinct id_calidad from ".$this->registros_temporales.")
              union   
            (select distinct id_calidad from ".$this->registros_cambios.")
              union   

            (select distinct id_calidad from ".$this->registros_entradas.")
              union   

            (select distinct id_calidad from ".$this->registros_salidas.")
              union   

            (select distinct id_calidad from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_calidad from ".$this->historico_registros_salidas.")
              ) r 
           where r.id_calidad='".$id_calidad."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    




  public function colores_en_uso($id_color) {

          $result = $this->db->query("
            select distinct r.id_color from (

            (select distinct id_color from ".$this->productos.")
              union   

            (select distinct id_color from ".$this->registros_temporales.")
              union   
            (select distinct id_color from ".$this->registros_cambios.")
              union   

            (select distinct id_color from ".$this->registros_entradas.")
              union   

            (select distinct id_color from ".$this->registros_salidas.")
              union   

            (select distinct id_color from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_color from ".$this->historico_registros_salidas.")
              ) r 
           where r.id_color='".$id_color."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    

  public function composiciones_en_uso($id_composicion) {

          $result = $this->db->query("
            select distinct r.id_composicion from (

            (select distinct id_composicion from ".$this->productos.")
              union   

            (select distinct id_composicion from ".$this->registros_temporales.")
              union   
            (select distinct id_composicion from ".$this->registros_cambios.")
              union   

            (select distinct id_composicion from ".$this->registros_entradas.")
              union   

            (select distinct id_composicion from ".$this->registros_salidas.")
              union   

            (select distinct id_composicion from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_composicion from ".$this->historico_registros_salidas.")
              ) r 
           where r.id_composicion='".$id_composicion."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    



//SELECT * FROM `inven_catalogo_colores` WHERE 
//(LOCATE((left (color,1)), "ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz0123456789") =0)

    // para el cuadro de colores
     public function lista_colores_ajax($data) {
            $this->db->cache_on();
            $this->db->select('id color_uid, color nombre_color, hexadecimal_color');
            if($data['indice'] == "#"){
                $where = '(
                            
                            (
                              ( color LIKE  "0%" ) OR ( color LIKE  "1%" ) OR ( color LIKE  "2%" ) OR 
                              ( color LIKE  "3%" ) OR ( color LIKE  "4%" ) OR ( color LIKE  "5%" ) OR 
                              ( color LIKE  "6%" ) OR ( color LIKE  "7%" ) OR ( color LIKE  "8%" ) OR 
                              ( color LIKE  "9%" ) 
                             )
                  ) ' ; 

            } elseif ($data['indice'] == "*"){

                $where = '(   
                              (LOCATE((left (color,1)), "ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz0123456789") =0)

                  ) ' ; 

                 

            } else {
                  $where = '(
                            
                            (
                               ( color LIKE  "'.$data['indice'].'%" )
                             )
                  ) ' ; 

            }



                if ($data['arreglo_colores']) {  
                  $where.=' AND (NOT( id IN  '.$data["arreglo_colores"].' ))'; 
                   
                } 




          $this->db->where($where);

            $lista = $this->db->get($this->colores);
            return $lista->result();
            $lista->free_result();
        }



     public function nosirve_borrar($data) {
            $this->db->cache_on();
            $this->db->select('id color_uid, color nombre_color, hexadecimal_color');
            if($data['indice'] == "#"){
                $this->db->like('color', '0', "after");
                $this->db->or_like('color', '1', "after");
                $this->db->or_like('color', '2', "after");
                $this->db->or_like('color', '3', "after");
                $this->db->or_like('color', '4', "after");
                $this->db->or_like('color', '5', "after");
                $this->db->or_like('color', '6', "after");
                $this->db->or_like('color', '7', "after");
                $this->db->or_like('color', '8', "after");
                $this->db->or_like('color', '9', "after");
                if ($data['arreglo_colores']) {  
                   $this->db->where_not_in('id', $data['arreglo_colores']);
                } 

            }else{
                $this->db->like('color', $data['indice'], "after");

                if ($data['arreglo_colores']) {  
                   $this->db->where_not_in('id', $data['arreglo_colores']);
                } 

                


            }
            $lista = $this->db->get($this->colores);
            return $lista->result();
            $lista->free_result();
        }





//////////////////////////dependencia//////////////////////////////


        /*
    $data['val_prod']
    $data['val_color']
    $data['val_comp'] 
    $data['val_calida']  
    */

        public function lista_colores($data){

            $this->db->distinct();
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            $this->db->where('p.activo',0);
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.descripcion', ($data['val_prod']) );


            $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    


        public function lista_colores_existente($data){

            $this->db->distinct();
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id');
            $this->db->where('p.activo',0);
            $this->db->where('m.id_almacen',$data['id_almacen']);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('m.id_factura',$data['id_factura']);


            $this->db->order_by('c.color', 'asc'); 
            


            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    


        public function lista_composiciones($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id','LEFT');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );

            $this->db->where('p.id_color', $data['val_color']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   

        public function lista_composiciones_existente($data){
            //distinct

            $this->db->distinct();
            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('m.id_almacen',$data['id_almacen']);
            $this->db->where('m.id_factura',$data['id_factura']);

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }   

        public function lista_calidad($data){
            //distinct
            $this->db->distinct();
            $this->db->select("c.calidad nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('p.id_composicion', $data['val_comp']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    

        public function lista_calidad_existente($data){
            //distinct
            $this->db->distinct();
            $this->db->select("c.calidad nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id');
            //$this->db->where('p.descripcion', $data['val_prod']);
            $this->db->where('p.activo',0);
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('m.id_almacen',$data['id_almacen']);
            $this->db->where('m.id_factura',$data['id_factura']);

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }  

        public function refe_producto($data){
            //distinct

            $this->db->select("p.referencia,p.comentario,p.imagen,p.precio,p.ancho,p.codigo_contable", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->where('p.descripcion', ($data['val_prod']) );
            $this->db->where('p.id_color', $data['val_color']);
            $this->db->where('p.id_composicion', $data['val_comp']);
            $this->db->where('p.id_calidad', $data['val_calida']);
            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        }            

//////////////////////fin de dependencia//////////////////////////////////////
//////////////////////fin de dependencia//////////////////////////////////////
    

    //checar si el codigo de producto existe para entrada


    public function check_existente_codigo($descripcion){
            $this->db->select("codigo", FALSE);         
            $this->db->from($this->registros_entradas);

            $where = '(
                        (
                          ( codigo =  "'.addslashes($descripcion).'" ) 
                          
                         )

              )';   
  
            $this->db->where($where);



            
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->codigo;
            }    
            else
                return false;
            $login->free_result();
    } 


    //checar si el proveedor existe para entrada




    public function checar_existente_proveedor($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->proveedores);
            $where = '(
                        (
                          ( nombre =  "'.addslashes($data['descripcion']).'" ) 
                          
                         )
              )';   
            $this->db->where($where);
            $this->db->where('(LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)' );
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    } 



    public function check_existente_proveedor_entrada($descripcion){
            $this->db->select("id", FALSE);         
            $this->db->from($this->proveedores);



            $where = '(
                        (
                          ( nombre =  "'.addslashes($descripcion).'" ) 
                          
                         )

              )';   
  
            $this->db->where($where);


          
            
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    } 

    
    //checar si el cargador existe para entrada
    public function check_existente_cargador_entrada($descripcion){
            $this->db->select("id", FALSE);         
            $this->db->from($this->cargadores);
            $where = '(
                        (
                          ( nombre =  "'.addslashes($descripcion).'" ) 
                         )
              )';   
  
            $this->db->where($where);
            
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    }     

  //-----------consecutivo------------------
        public function listado_consecutivo($id=-1){

          $this->db->select('o.id, o.operacion, o.consecutivo, o.conse_factura,o.conse_remision,o.conse_surtido,o.conse_ajuste_factura,o.conse_ajuste_remision, o.conse_bodega'); 
          $this->db->from($this->operaciones .' as o');

          if ($id!=-1) {
              $this->db->where('id',$id);  
          } 
          

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();
        } 


        /*

          id_operacion: 
                inven_catalogo_operaciones
                  1 - Entrada
                  71 - Entrada compra
                  70 - Transferencia Recibida
                  72 - Bodega
                  
                  2 - Salida

          id_almacen:
               inven_catalogo_almacenes                  
                1- Bod.1
                2- Bod.2
                3- Bod.3



          id_factura:   
               inven_catalogo_tipos_facturas
                1- factura
                2- Remision
                3- Bodega
          id_pedido:   
                inven_catalogo_tipos_pedidos
                1- Venta
                2- Surtido
                3- Bodega


        */

        public function consecutivo_general($data){
          $id_session = $this->session->userdata('id');

          $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
          $this->db->from($this->consecutivo_general.' as g');

            $where = '(
                        (
                          ( g.id_operacion =  '.$data["id_operacion"].' ) AND 
                          ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                          ( g.id_factura =  '.$data["id_factura"].' ) AND 
                          ( g.id_pedido =  '.$data["id_pedido"].' ) 
                          
                         )

              )';   
            $this->db->where($where);
            $result = $this->db->get();

            if ( $result->num_rows() > 0 ) {
                return $result->row();
            } else {


                  //obtener c1, c2, c234, c34


                     ///////////
                     if ( ($data["id_operacion"] ==1 ) || ($data["id_operacion"] ==70 )  || ($data["id_operacion"] ==71 ) || ($data["id_operacion"] ==72 ) || ($data["id_operacion"] ==73 ) ) {
                          $id_operacion = '(( g.id_operacion =  1 ) OR ( g.id_operacion =  70 )  OR ( g.id_operacion =  71 ) OR ( g.id_operacion =  72 ) OR ( g.id_operacion =  73 ) )';
                     }

                  $this->db->select('max(g.c1* ( ('.$id_operacion.')  )  ) as c1',false);
                  $this->db->select('max(g.c2* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' ) )  ) as c2',false);
                  $this->db->select('max(g.c234* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' )  AND ( g.id_factura =  '.$data["id_factura"].' ) AND ( g.id_pedido =  '.$data["id_pedido"].' ) )  ) as c234',false);
                  $this->db->select('max(g.c34* ( ('.$id_operacion.')  AND ( g.id_factura =  '.$data["id_factura"].' ) AND ( g.id_pedido =  '.$data["id_pedido"].' ) )  ) as c34',false);
                   $this->db->from($this->consecutivo_general.' as g');
                    $where = '(
                                (
                                  '.$id_operacion.'
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();

                    if ( $result->num_rows() > 0 ) {
                          $c1 = ($result->row()->c1 == NULL) ? 0 : $result->row()->c1;
                          $c2 = ($result->row()->c2 == NULL) ? 0 : $result->row()->c2;
                        $c234 = ($result->row()->c234 == NULL) ? 0 : $result->row()->c234;
                         $c34 = ($result->row()->c34 == NULL) ? 0 : $result->row()->c34;
                    } else {

                        $c1 = 0;
                        $c2 = 0;
                        $c234 = 0;
                        $c34 = 0;
                    }    



                  //en caso de no existir que cree una nueva fila
                   $this->db->set( 'c1', $c1);  
                   $this->db->set( 'c2', $c2);  
                   //$this->db->set( 'c1234', $c1234);  
                   $this->db->set( 'c234', $c234);  
                   $this->db->set( 'c34', $c34);  



                  $this->db->set( 'id_usuario',  $id_session );
                  $this->db->set( 'id_operacion', $data['id_operacion'] );  
                  $this->db->set( 'id_almacen', $data['id_almacen'] );  
                  $this->db->set( 'id_factura', $data['id_factura'] );  
                  $this->db->set( 'id_pedido', $data['id_pedido'] );  
                  $this->db->insert($this->consecutivo_general );




                  $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
                  $this->db->from($this->consecutivo_general.' as g');

                    $where = '(
                                (
                                  ( g.id_operacion =  '.$data["id_operacion"].' ) AND 
                                  ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                                  ( g.id_factura =  '.$data["id_factura"].' ) AND 
                                  ( g.id_pedido =  '.$data["id_pedido"].' ) 
                                  
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();
                    return $result->row();
              
            }
               
            $result->free_result();
        }        


        public function actualizando_nuevos_consecutivos( $data ){
            
                ///////////
                $this->db->set( 'c1234', 'c1234+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion"].' ) AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_factura =  '.$data["id_factura"].' ) AND 
                              ( g.id_pedido =  '.$data["id_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               if ( ($data["id_operacion"] ==1 ) || ($data["id_operacion"] ==70 )  || ($data["id_operacion"] ==71 ) || ($data["id_operacion"] ==72 ) || ($data["id_operacion"] ==73 ) ) {
                    $id_operacion = '(( g.id_operacion =  1 ) OR ( g.id_operacion =  70 )  OR ( g.id_operacion =  71 ) OR ( g.id_operacion =  72 ) OR ( g.id_operacion =  73 ) )';
               }

                ///////////
               $this->db->set( 'c234', 'c234+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_factura =  '.$data["id_factura"].' ) AND 
                              ( g.id_pedido =  '.$data["id_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c34', 'c34+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_factura =  '.$data["id_factura"].' ) AND 
                              ( g.id_pedido =  '.$data["id_pedido"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c2', 'c2+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               $this->db->set( 'c1', 'c1+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


        }   



 //pedido 
public function consecutivo_general_pedido($data){
          $id_session = $this->session->userdata('id');

          $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
          $this->db->from($this->consecutivo_general.' as g');
            $where = '(
                        (
                          ( g.id_operacion =  '.$data["id_operacion_pedido"].' ) AND 
                          ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                          ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                          ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) 
                          
                         )

              )';   
            $this->db->where($where);
            $result = $this->db->get();

            if ( $result->num_rows() > 0 ) { //si existe ya el registro porq se uso antes, pues q envie los consecutivos
                return $result->row();  
            } else {  //en caso de no existir el registro, pues entonces, construira uno apartir de los que ya existen
                  //obtener c1, c2, c234, c34
                     ///////////
                     if ( ($data["id_operacion_pedido"] ==4 ) || ($data["id_operacion_pedido"] ==96 )  || ($data["id_operacion_pedido"] ==97 ) || ($data["id_operacion_pedido"] ==98 )) {
                          $id_operacion = '(( g.id_operacion =  4 ) OR ( g.id_operacion =  96 )  OR ( g.id_operacion =  97 ) OR ( g.id_operacion =  98 ) )';
                     }

                  $this->db->select('max(g.c1* ( ('.$id_operacion.')  )  ) as c1',false);
                  $this->db->select('max(g.c2* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' ) )  ) as c2',false);
                  $this->db->select('max(g.c234* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' )  AND ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) )  ) as c234',false);
                  $this->db->select('max(g.c34* ( ('.$id_operacion.')  AND ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) )  ) as c34',false);
                   $this->db->from($this->consecutivo_general.' as g');
                    $where = '(
                                (
                                  '.$id_operacion.'
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();

                    if ( $result->num_rows() > 0 ) {
                          $c1 = ($result->row()->c1 == NULL) ? 0 : $result->row()->c1;
                          $c2 = ($result->row()->c2 == NULL) ? 0 : $result->row()->c2;
                        $c234 = ($result->row()->c234 == NULL) ? 0 : $result->row()->c234;
                         $c34 = ($result->row()->c34 == NULL) ? 0 : $result->row()->c34;
                    } else {

                        $c1 = 0;
                        $c2 = 0;
                        $c234 = 0;
                        $c34 = 0;
                    }    



                  //en caso de no existir que cree una nueva fila
                   $this->db->set( 'c1', $c1);  
                   $this->db->set( 'c2', $c2);  
                   //$this->db->set( 'c1234', $c1234);  
                   $this->db->set( 'c234', $c234);  
                   $this->db->set( 'c34', $c34);  



                  $this->db->set( 'id_usuario',  $id_session );
                  $this->db->set( 'id_operacion', $data['id_operacion_pedido'] );  
                  $this->db->set( 'id_almacen', $data['id_almacen'] );  
                  $this->db->set( 'id_tipo_factura', $data['id_tipo_factura'] );  
                  $this->db->set( 'id_tipo_pedido', $data['id_tipo_pedido'] );  
                  $this->db->insert($this->consecutivo_general );




                  $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
                  $this->db->from($this->consecutivo_general.' as g');

                    $where = '(
                                (
                                  ( g.id_operacion =  '.$data["id_operacion_pedido"].' ) AND 
                                  ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                                  ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                                  ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) 
                                  
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();
                    return $result->row();
              
            }
               
            $result->free_result();
        }        


 

  public function actualizando_nuevos_consecutivos_pedido( $data ){
            
                ///////////
                $this->db->set( 'c1234', 'c1234+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion_pedido"].' ) AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               if ( ($data["id_operacion_pedido"] ==4 ) || ($data["id_operacion_pedido"] ==96 )  || ($data["id_operacion_pedido"] ==97 ) || ($data["id_operacion_pedido"] ==98 )) {
                    $id_operacion = '(( g.id_operacion =  4 ) OR ( g.id_operacion =  96 )  OR ( g.id_operacion =  97 ) OR ( g.id_operacion =  98) )';
               }

                ///////////
               $this->db->set( 'c234', 'c234+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c34', 'c34+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido =  '.$data["id_tipo_pedido"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c2', 'c2+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               $this->db->set( 'c1', 'c1+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion_pedido"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


        }           



  //-----------estatus------------------

        public function listado_estatus($limit=-1, $offset=-1,$tipo=-1){

          $this->db->select('a.id, a.estatus');
          $this->db->from($this->estatuss.' as a');

          if ($tipo!=-1) {
              $this->db->where('tipo',$tipo);  
          } 
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        } 


///////////salida
public function consecutivo_general_salida($data){
          $id_session = $this->session->userdata('id');

          $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
          $this->db->from($this->consecutivo_general.' as g');
            $where = '(
                        (
                          ( g.id_operacion =  '.$data["id_operacion_salida"].' ) AND 
                          ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                          ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                          ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) 
                          
                         )

              )';   
            $this->db->where($where);
            $result = $this->db->get();

            if ( $result->num_rows() > 0 ) { //si existe ya el registro porq se uso antes, pues q envie los consecutivos
                return $result->row();  
            } else {  //en caso de no existir el registro, pues entonces, construira uno apartir de los que ya existen
                  //obtener c1, c2, c234, c34
                     ///////////
                     if ( ($data["id_operacion_salida"] ==2 ) || ($data["id_operacion_salida"] ==93 )  || ($data["id_operacion_salida"] ==94 ) || ($data["id_operacion_salida"] ==95 ) || ($data["id_operacion_salida"] ==99 ) ) {
                          $id_operacion = '(( g.id_operacion =  2 ) OR ( g.id_operacion =  93 )  OR ( g.id_operacion =  94 ) OR ( g.id_operacion =  95 ) OR ( g.id_operacion =  99) )';
                     }



                  $this->db->select('max(g.c1* ( ('.$id_operacion.')  )  ) as c1',false);
                  $this->db->select('max(g.c2* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' ) )  ) as c2',false);
                  $this->db->select('max(g.c234* ( ('.$id_operacion.') AND ( g.id_almacen =  '.$data["id_almacen"].' )  AND ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) )  ) as c234',false);
                  $this->db->select('max(g.c34* ( ('.$id_operacion.')  AND ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) )  ) as c34',false);
                   $this->db->from($this->consecutivo_general.' as g');
                    $where = '(
                                (
                                  '.$id_operacion.'
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();

                    if ( $result->num_rows() > 0 ) {
                          $c1 = ($result->row()->c1 == NULL) ? 0 : $result->row()->c1;
                          $c2 = ($result->row()->c2 == NULL) ? 0 : $result->row()->c2;
                        $c234 = ($result->row()->c234 == NULL) ? 0 : $result->row()->c234;
                         $c34 = ($result->row()->c34 == NULL) ? 0 : $result->row()->c34;
                    } else {

                        $c1 = 0;
                        $c2 = 0;
                        $c234 = 0;
                        $c34 = 0;
                    }    



                  //en caso de no existir que cree una nueva fila
                   $this->db->set( 'c1', $c1);  
                   $this->db->set( 'c2', $c2);  
                   //$this->db->set( 'c1234', $c1234);  
                   $this->db->set( 'c234', $c234);  
                   $this->db->set( 'c34', $c34);  



                  $this->db->set( 'id_usuario',  $id_session );
                  $this->db->set( 'id_operacion', $data['id_operacion_salida'] );  
                  $this->db->set( 'id_almacen', $data['id_almacen'] );  
                  $this->db->set( 'id_tipo_factura', $data['id_tipo_factura'] );  
                  $this->db->set( 'id_tipo_pedido', $data['id_tipo_pedido'] );  
                  $this->db->insert($this->consecutivo_general );




                  $this->db->select('g.c1, g.c2, g.c1234,  g.c234,  g.c34'); 
                  $this->db->from($this->consecutivo_general.' as g');

                    $where = '(
                                (
                                  ( g.id_operacion =  '.$data["id_operacion_salida"].' ) AND 
                                  ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                                  ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                                  ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) 
                                  
                                 )

                      )';   
                    $this->db->where($where);
                    $result = $this->db->get();
                    return $result->row();
              
            }
               
            $result->free_result();
        }        




  public function actualizando_nuevos_consecutivos_salida( $data ){
            
                ///////////
                $this->db->set( 'c1234', 'c1234+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion_salida"].' ) AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               if ( ($data["id_operacion_salida"] ==2 ) || ($data["id_operacion_salida"] ==93 )  || ($data["id_operacion_salida"] ==94 ) || ($data["id_operacion_salida"] ==95 ) || ($data["id_operacion_salida"] ==99 )) {
                    $id_operacion = '(( g.id_operacion =  2 ) OR ( g.id_operacion =  93 )  OR ( g.id_operacion =  94 ) OR ( g.id_operacion =  95) OR ( g.id_operacion =  99) )';
               }

                ///////////
               $this->db->set( 'c234', 'c234+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) 
                              
                             )

                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c34', 'c34+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_tipo_factura =  '.$data["id_tipo_factura"].' ) AND 
                              ( g.id_tipo_pedido=  '.$data["id_tipo_pedido"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');

                ///////////
               $this->db->set( 'c2', 'c2+1', FALSE  );  
                $where = '(
                            (
                              '.$id_operacion.' AND 
                              ( g.id_almacen =  '.$data["id_almacen"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


                ///////////
               $this->db->set( 'c1', 'c1+1', FALSE  );  
                $where = '(
                            (
                              ( g.id_operacion =  '.$data["id_operacion_salida"].' ) 
                             )
                  )';   
                $this->db->where($where);
                $this->db->update($this->consecutivo_general.' as g');


        }           
        

//-----------excluir "todos" en estatus------------------
        public function listado_estatus_excluir($limit=-1, $offset=-1,$tipo=-1){

          $this->db->select('a.id, a.estatus');
          $this->db->from($this->estatuss.' as a');

          if ($tipo!=-1) {
              $this->db->where('tipo !=',$tipo);  
          } 
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }         
			
   //-----------lotes------------------

        public function listado_lotes($limit=-1, $offset=-1,$tipo=-1){

          $this->db->select('l.id, l.lote');
          $this->db->from($this->lotes.' as l');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        } 

      //-----------medidas------------------

        public function total_medidas(){
           $this->db->from($this->unidades_medidas);
           $medidas = $this->db->get();            
           return $medidas->num_rows();
        }

        public function listado_medidas($limit=-1, $offset=-1){

          $this->db->select('a.id, a.medida');
          $this->db->from($this->unidades_medidas.' as a');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_medidas($data){
            $this->db->select( 'id' );
            $this->db->select("medida", FALSE);  
            $this->db->from($this->unidades_medidas);
            $this->db->like("medida" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->medida,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el medida ya existe
    public function check_existente_medida($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->unidades_medidas);
            $this->db->where('medida',$data['medida']);  
            $this->db->where('estatus',"0");
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_medida( $data ){
              
            $this->db->select("a.id, a.medida");         
            $this->db->from($this->unidades_medidas.' As a');
            $this->db->where('a.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_medida( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'medida', $data['medida'] );  

            $this->db->insert($this->unidades_medidas );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_medida( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'medida', $data['medida'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->unidades_medidas );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar medida
        public function eliminar_medida( $data ){
            $this->db->delete( $this->unidades_medidas, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }


      //-----------composiciones------------------

        public function total_composiciones(){
           $this->db->from($this->composiciones);
           $composiciones = $this->db->get();            
           return $composiciones->num_rows();
        }

        public function listado_composiciones($limit=-1, $offset=-1){

          $this->db->select('c.id, c.composicion');
          $this->db->from($this->composiciones.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();


            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::composiciones_en_uso($row->id);
                 }                 
               return $result->result();
            }             
            else
               return False;
            $result->free_result();
        }        



      public function buscador_composiciones($data){
            $this->db->select( 'id' );
            $this->db->select("composicion", FALSE);  
            $this->db->from($this->composiciones);
            $this->db->like("composicion" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->composicion,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el composicion ya existe
    public function check_existente_composicion($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->composiciones);
            $this->db->where('composicion',$data['composicion']);  
            
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_composicion( $data ){
              
            $this->db->select("c.id, c.composicion");         
            $this->db->from($this->composiciones.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_composicion( $data ){
          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'composicion', $data['composicion'] );  

            $this->db->insert($this->composiciones );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_composicion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'composicion', $data['composicion'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->composiciones );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar composicion
        public function eliminar_composicion( $data ){
            $this->db->delete( $this->composiciones, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     



      //-----------actividades------------------

        public function total_actividades(){
           $this->db->from($this->actividad_comercial);
           $actividades = $this->db->get();            
           return $actividades->num_rows();
        }

        public function listado_actividades($limit=-1, $offset=-1,$id=""){

          $this->db->select('a.id, a.actividad,tooltip');
          $this->db->from($this->actividad_comercial.' as a');

          if ($id!="") {
              $this->db->where($id); 
          } 
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_actividades($data){
            $this->db->select( 'id' );
            $this->db->select("actividad", FALSE);  
            $this->db->from($this->actividad_comercial);
            $this->db->like("actividad" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->actividad,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el actividad ya existe
    public function check_existente_actividad($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->actividad_comercial);
            $this->db->where('actividad',$data['actividad']);  
            $this->db->where('estatus',"0");
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_actividad( $data ){
              
            $this->db->select("a.id, a.actividad");         
            $this->db->from($this->actividad_comercial.' As a');
            $this->db->where('a.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_actividad( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'actividad', $data['actividad'] );  

            $this->db->insert($this->actividad_comercial );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_actividad( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'actividad', $data['actividad'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->actividad_comercial );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar actividad
        public function eliminar_actividad( $data ){
            $this->db->delete( $this->actividad_comercial, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     





   //-----------colores------------------

        public function total_colores(){
           $this->db->from($this->colores);
           $colores = $this->db->get();            
           return $colores->num_rows();
        }


        public function listado_colores($limit=-1, $offset=-1){

          $this->db->select('c.id, c.color, c.hexadecimal_color');
          $this->db->from($this->colores.' as c');
          $this->db->order_by('c.color', 'asc'); 
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();


            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::colores_en_uso($row->id);
                 }                 
               return $result->result();
            }    else
               return False;
            $result->free_result();
        }        



      public function buscador_colores($data){
            $this->db->distinct();
            $this->db->select("c.hexadecimal_color");
            $this->db->select("c.color", FALSE);  
            $this->db->select("p.descripcion", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            $this->db->like("p.descripcion" ,$data['dependiente']);
            $this->db->like("c.color" ,$data['key']);

            $this->db->order_by('c.color', 'asc'); 
            //$this->db->or_like("c.color" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "descripcion"=>$row->descripcion,
                                      "color"=>$row->color,
                                      "hexadecimal_color"=>$row->hexadecimal_color
                                    );
                      }
                      return json_encode($dato);
                      //return '[ {"nombre":"Jhon", "apellido":"calderón"}, {"nombre":"jean", "apellido":"calderón"}]';
              }   
              else 
                 return False;
              $result->free_result();
      }   

 
    //checar si el color ya existe
    public function check_existente_color($data){

            if (!isset($data['id'])) {
              $data['id']=-1;
            }
      
            $this->db->select("id", FALSE);         
            $this->db->from($this->colores);
            $this->db->where('color',$data['color']);  
            $this->db->where('id !=',$data['id']);
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_color( $data ){
              
            $this->db->select("c.id, c.color, c.hexadecimal_color");         
            $this->db->from($this->colores.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_color( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'color', $data['color'] );  
          $this->db->set( 'hexadecimal_color', $data['hexadecimal_color'] );  


            $this->db->insert($this->colores );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_color( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'color', $data['color'] );  
          $this->db->set( 'hexadecimal_color', $data['hexadecimal_color'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->colores );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar color
        public function eliminar_color( $data ){
            $this->db->delete( $this->colores, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     

//-----------calidades------------------

        public function total_calidades(){
           $this->db->from($this->calidades);
           $calidades = $this->db->get();            
           return $calidades->num_rows();
        }

        public function listado_calidades($limit=-1, $offset=-1){

          $this->db->select('c.id, c.calidad');
          $this->db->from($this->calidades.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();


            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::calidades_en_uso($row->id);
                 }                 
               return $result->result();
            }  else
               return False;
            $result->free_result();
        }        



      public function buscador_calidades($data){
            $this->db->select( 'id' );
            $this->db->select("calidad", FALSE);  
            $this->db->from($this->calidades);
            $this->db->like("calidad" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->calidad,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el calidad ya existe
    public function check_existente_calidad($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->calidades);
            $this->db->where('calidad',$data['calidad']);  
            
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_calidad( $data ){
              
            $this->db->select("c.id, c.calidad");         
            $this->db->from($this->calidades.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_calidad( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'calidad', $data['calidad'] );  

            $this->db->insert($this->calidades );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_calidad( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'calidad', $data['calidad'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->calidades );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar calidad
        public function eliminar_calidad( $data ){
            $this->db->delete( $this->calidades, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     

        //-----------cargadores------------------

        public function total_cargadores(){
           $this->db->from($this->cargadores);
           $cargadores = $this->db->get();            
           return $cargadores->num_rows();
        }

        public function listado_cargadores($limit=-1, $offset=-1){
          
          $this->db->select('c.id, c.nombre, c.estatus');
          $this->db->from($this->cargadores.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::cargadores_en_uso($row->id);
                 }                 
               return $result->result();
            }

            else
               return False;
            $result->free_result();
        }        



      public function buscador_cargadores($data){
            $this->db->select( 'id' );
            $this->db->select("nombre", FALSE);  
            $this->db->from($this->cargadores);
            
            $this->db->like("id" ,$data['key'],FALSE);
            $this->db->or_like("nombre" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->id." | ".$row->nombre,
                                       "key"=>$row->id,
                                       "descripcion"=>$row->nombre
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    



      public function buscador_vendedor($data){
            $this->db->select( 'id' );
            $this->db->select("nombre, apellidos");  
            $this->db->from($this->usuarios);
            
            $this->db->like("id" ,$data['key'],FALSE);
            $this->db->or_like("nombre" ,$data['key'],FALSE);
            $this->db->or_like("apellidos" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->id." | ".$row->nombre.' '.$row->apellidos,
                                       "key"=>$row->id,
                                       "descripcion"=>$row->nombre.' '.$row->apellidos
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    




    
    //checar si el cargador ya existe
    public function check_existente_cargador($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->cargadores);
            $where = '(
                        (
                          ( nombre =  "'.addslashes($data['nombre']).'" ) 
                          
                         )

              )';   
  
            $this->db->where($where);
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_cargador( $data ){
              
            $this->db->select("c.id, c.nombre,c.estatus");         
            $this->db->from($this->cargadores.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_cargador( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'nombre', $data['nombre'] );  
          //$this->db->set( 'estatus', $data['estatus'] );  

            $this->db->insert($this->cargadores );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_cargador( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'nombre', $data['nombre'] );  
          //$this->db->set( 'estatus', $data['estatus'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->cargadores );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar cargador
        public function eliminar_cargador( $data ){
            $this->db->delete( $this->cargadores, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   


 //-----------almacenes------------------

        public function total_almacenes(){
           $this->db->from($this->almacenes);
           $almacenes = $this->db->get();            
           return $almacenes->num_rows();
        }

        public function listado_almacenes($limit=-1, $offset=-1){

          $this->db->select('a.id, a.almacen');
          $this->db->from($this->almacenes.' as a');
          $this->db->where('a.activo', 1);
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_almacenes($data){
            $this->db->select( 'id' );
            $this->db->select("almacen", FALSE);  
            $this->db->from($this->almacenes);
            $this->db->like("almacen" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->almacen,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el almacen ya existe
    public function check_existente_almacen($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->almacenes);
            $this->db->where('almacen',$data['almacen']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_almacen( $data ){
              
            $this->db->select("c.id, c.almacen");         
            $this->db->from($this->almacenes.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_almacen( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'almacen', $data['almacen'] );  

            $this->db->insert($this->almacenes );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_almacen( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'almacen', $data['almacen'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->almacenes );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar almacen
        public function eliminar_almacen( $data ){
            $this->db->delete( $this->almacenes, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   


 //-----------configuraciones------------------

        public function total_configuraciones(){
           $this->db->from($this->configuraciones);
           $configuraciones = $this->db->get();            
           return $configuraciones->num_rows();
        }

        public function listado_configuraciones($limit=-1, $offset=-1){

          $this->db->select('c.id, c.configuracion,c.activo,c.valor');
          $this->db->from($this->configuraciones.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_configuraciones($data){
            $this->db->select( 'id' );
            $this->db->select("configuracion", FALSE);  
            $this->db->from($this->configuraciones);
            $this->db->like("configuracion" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->configuracion,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el configuracion ya existe
    public function check_existente_configuracion($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->configuraciones);
            $this->db->where('configuracion',$data['configuracion']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_configuracion( $data ){
              
            $this->db->select("c.id, c.configuracion,c.activo,c.valor");         
            $this->db->from($this->configuraciones.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

     public function get_tienda_activo(){
              
            $this->db->select("c.id, c.nombre");         
            $this->db->from($this->catalogo_tiendas.' As c');
            $this->db->where('c.activo',1);
            $result = $this->db->get( );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  


      //crear
        public function anadir_configuracion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'configuracion', $data['configuracion'] );  
          $this->db->set( 'activo', $data['activo'] );  
          $this->db->set( 'valor', $data['valor'] );  


            $this->db->insert($this->configuraciones );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_configuracion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'configuracion', $data['configuracion'] );  
          $this->db->set( 'activo', $data['activo'] );  
          $this->db->set( 'valor', $data['valor'] );  


          $this->db->where('id', $data['id'] );
          $this->db->update($this->configuraciones );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar configuracion
        public function eliminar_configuracion( $data ){
            $this->db->delete( $this->configuraciones, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   


 //-----------anchos------------------

        public function total_anchos(){
           $this->db->from($this->anchos);
           $anchos = $this->db->get();            
           return $anchos->num_rows();
        }

        public function listado_anchos($limit=-1, $offset=-1){

          $this->db->select('c.id, c.ancho');
          $this->db->from($this->anchos.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_anchos($data){
            $this->db->select( 'id' );
            $this->db->select("ancho", FALSE);  
            $this->db->from($this->anchos);
            $this->db->like("ancho" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->ancho,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el ancho ya existe
    public function check_existente_ancho($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->anchos);
            $this->db->where('ancho',$data['ancho']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_ancho( $data ){
              
            $this->db->select("c.id, c.ancho");         
            $this->db->from($this->anchos.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_ancho( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'ancho', $data['ancho'] );  

            $this->db->insert($this->anchos );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_ancho( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'ancho', $data['ancho'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->anchos );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar ancho
        public function eliminar_ancho( $data ){
            $this->db->delete( $this->anchos, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   





 //-----------tipos_facturas------------------

        public function total_tipos_facturas(){
           $this->db->from($this->tipos_facturas);
           $tipos_facturas = $this->db->get();            
           return $tipos_facturas->num_rows();
        }


        //para saber si esta activa o no la remision y valor de IVA
        public function remision_iva($id){
         
              $this->db->select('a.activo,a.valor');
              $this->db->from($this->configuraciones .' as a');
              $this->db->where('a.id',$id);  
              
              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->row();
                else
                   return False;
                $result->free_result();
            

        } 



      public function catalogo_tipos_facturas(){


          $this->db->select('c.id, c.tipo_factura');
          $this->db->from($this->tipos_facturas.' as c');
          $where = '( c.estatus <> 1 ) ';
          $this->db->where($where);

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }  

     



        public function listado_tipos_facturas($limit=-1, $offset=-1){
          

          $desabilitar= self::remision_iva(1); //remision

          if ($desabilitar->activo==0) {
            $where = '( c.si_remision <> 1 ) ';
            $this->db->where($where);
          }


          $this->db->select('c.id, c.tipo_factura');
          $this->db->from($this->tipos_facturas.' as c');
         
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        


        public function listado_tipos_facturas_especifico($id){
          
          $this->db->select('c.id, c.tipo_factura');
          $this->db->from($this->tipos_facturas.' as c');
          $where = '( c.estatus <> '.$id.' ) ';
          $this->db->where($where);
         
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        

      


      public function buscador_tipos_facturas($data){
            $this->db->select( 'id' );
            $this->db->select("tipo_factura", FALSE);  
            $this->db->from($this->tipos_facturas);
            $this->db->like("tipo_factura" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->tipo_factura,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el tipo_factura ya existe
    public function check_existente_tipo_factura($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->tipos_facturas);
            $this->db->where('tipo_factura',$data['tipo_factura']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_tipo_factura( $data ){
              
            $this->db->select("c.id, c.tipo_factura");         
            $this->db->from($this->tipos_facturas.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_tipo_factura( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'tipo_factura', $data['tipo_factura'] );  

            $this->db->insert($this->tipos_facturas );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_tipo_factura( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'tipo_factura', $data['tipo_factura'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->tipos_facturas );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar tipo_factura
        public function eliminar_tipo_factura( $data ){
            $this->db->delete( $this->tipos_facturas, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   


 //-----------tipos_pedidos------------------

        public function total_tipos_pedidos(){
           $this->db->from($this->tipos_pedidos);
           $tipos_pedidos = $this->db->get();            
           return $tipos_pedidos->num_rows();
        }

    


      public function listado_tip_pedidos($data){
          
          

          if  ( (($this->session->userdata('id_perfil')!=1) &&  ($this->session->userdata('id_perfil')!=2))  and (!(in_array(98, $data['coleccion_id_operaciones']))) ) {
            $where = '( c.id <> 3 ) ';
          //  $this->db->where($where);
          }


          $this->db->select('c.id, c.tipo_pedido');
          $this->db->from($this->tipos_pedidos.' as c');
         
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      



      public function listado_tipos_pedidos($limit=-1, $offset=-1){
          

          $desabilitar= self::remision_iva(3); //surtido

          if ($desabilitar->activo==0) {
            $where = '( c.si_remision <> 1 ) ';
            $this->db->where($where);
          }


          $this->db->select('c.id, c.tipo_pedido');
          $this->db->from($this->tipos_pedidos.' as c');
         
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      


      public function listado_tipos_pedidos_especifico($id){
          
          $this->db->select('c.id, c.tipo_pedido');
          $this->db->from($this->tipos_pedidos.' as c');
          $where = '( c.si_remision <> '.$id.' ) ';
          $this->db->where($where);
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }      




      public function buscador_tipos_pedidos($data){
            $this->db->select( 'id' );
            $this->db->select("tipo_pedido", FALSE);  
            $this->db->from($this->tipos_pedidos);
            $this->db->like("tipo_pedido" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->tipo_pedido,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el tipo_pedido ya existe
    public function check_existente_tipo_pedido($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->tipos_pedidos);
            $this->db->where('tipo_pedido',$data['tipo_pedido']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_tipo_pedido( $data ){
              
            $this->db->select("c.id, c.tipo_pedido");         
            $this->db->from($this->tipos_pedidos.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_tipo_pedido( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'tipo_pedido', $data['tipo_pedido'] );  

            $this->db->insert($this->tipos_pedidos );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_tipo_pedido( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'tipo_pedido', $data['tipo_pedido'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->tipos_pedidos );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar tipo_pedido
        public function eliminar_tipo_pedido( $data ){
            $this->db->delete( $this->tipos_pedidos, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }   



 //-----------tipos_ventas------------------

        public function total_tipos_ventas(){
           $this->db->from($this->tipos_ventas);
           $tipos_ventas = $this->db->get();            
           return $tipos_ventas->num_rows();
        }

        public function listado_tipos_ventas($limit=-1, $offset=-1){

          $this->db->select('c.id, c.tipo_venta');
          $this->db->from($this->tipos_ventas.' as c');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_tipos_ventas($data){
            $this->db->select( 'id' );
            $this->db->select("tipo_venta", FALSE);  
            $this->db->from($this->tipos_ventas);
            $this->db->like("tipo_venta" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->tipo_venta,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el tipo_venta ya existe
    public function check_existente_tipo_venta($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->tipos_ventas);
            $this->db->where('tipo_venta',$data['tipo_venta']);  
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_tipo_venta( $data ){
              
            $this->db->select("c.id, c.tipo_venta");         
            $this->db->from($this->tipos_ventas.' As c');
            $this->db->where('c.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_tipo_venta( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'tipo_venta', $data['tipo_venta'] );  

            $this->db->insert($this->tipos_ventas );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_tipo_venta( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'tipo_venta', $data['tipo_venta'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->tipos_ventas );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar tipo_venta
        public function eliminar_tipo_venta( $data ){
            $this->db->delete( $this->tipos_ventas, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }                           

      //-----------operaciones------------------

        public function total_operaciones(){
           $this->db->from($this->operaciones);
           $operaciones = $this->db->get();            
           return $operaciones->num_rows();
        }

        public function listado_operaciones($limit=-1, $offset=-1){

          $this->db->select('a.id, a.operacion');
          $this->db->from($this->operaciones.' as a');
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



      public function buscador_operaciones($data){
            $this->db->select( 'id' );
            $this->db->select("operacion", FALSE);  
            $this->db->from($this->operaciones);
            $this->db->like("operacion" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array("value"=>$row->operacion,
                                       "key"=>$row->id
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

    
    //checar si el operacion ya existe
    public function check_existente_operacion($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->operaciones);
            $this->db->where('operacion',$data['operacion']);  
            $this->db->where('estatus',"0");
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 



     public function coger_operacion( $data ){
              
            $this->db->select("a.id, a.operacion");         
            $this->db->from($this->operaciones.' As a');
            $this->db->where('a.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_operacion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'operacion', $data['operacion'] );  

            $this->db->insert($this->operaciones );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_operacion( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          $this->db->set( 'operacion', $data['operacion'] );  
          $this->db->where('id', $data['id'] );
          $this->db->update($this->operaciones );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else
                 return FALSE;
                $result->free_result();
        }   


        //eliminar operacion
        public function eliminar_operacion( $data ){
            $this->db->delete( $this->operaciones, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     





////////////////////Productos devolucion

    


     public function buscador_prod_devolucion($data){
            $this->db->select('m.codigo');
            $this->db->select('m.num_partida');
            $this->db->select('m.referencia');
            $this->db->select('m.id_descripcion,c.color,m.id_color, co.composicion, ca.calidad');
            $this->db->select('m.id_composicion,m.id_calidad'); //m.id_color,
            
            $this->db->select('m.movimiento,m.movimiento_unico, m.fecha_entrada, p.nombre proveedor, m.factura, m.cantidad_um');
            $this->db->select('m.id_medida, m.ancho,m.precio, m.id_estatus, m.id_lote, m.id ');
            $this->db->select('m.peso_real, m.peso_real_devolucion');
            $this->db->select("prod.codigo_contable");  

            $this->db->from($this->historico_registros_salidas.' as m');
            $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
            $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');
            $this->db->join($this->calidades.' As ca' , 'ca.id = m.id_calidad','LEFT');
            $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
            
            $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');

            //$this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT'); //um.medida

            //OR (m.referencia LIKE  "%'.$data['key'].'%") 
            $this->db->order_by('c.color', 'asc'); 

            $where = '(
                        (
                          

                          (( m.id_apartado = 0 ) OR ( m.id_apartado = 3 ) OR ( m.id_apartado = 6 ) ) AND  ( m.estatus_salida = "0" ) AND  (( m.devolucion != 2 ) AND  ( m.id_user_devolucion = "" )) 
                          AND  ( m.cod_devolucion = "" ) 
                          AND  ( m.on_off = 0 ) 
                          AND  ( m.id_almacen <> 0 ) 
                        ) AND (m.id_almacen = '.$data['id_almacen'].' )  
                         AND
                        (
                          ( m.codigo LIKE  "%'.$data['key'].'%" ) 
                         )

              )';   

  
            $this->db->where($where);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->codigo." | ".$row->referencia,
                                       "key"=>$row->codigo,
                                       "descripcion"=>$row->codigo,
                                       "id_descripcion"=>$row->id_descripcion,
                                       "id_color"=>$row->id_color,
                                       "id_composicion"=>$row->id_composicion,
                                       "id_calidad"=>$row->id_calidad,
                                       "id_movimiento"=>$row->movimiento,
                                       "movimiento_unico"=>$row->movimiento_unico,
                                       "fecha_entrada"=>$row->fecha_entrada,
                                       "proveedor"=>$row->proveedor,
                                       "factura"=>$row->factura,
                                       "cantidad_um"=>$row->cantidad_um,
                                       "id_medida"=>$row->id_medida,
                                       "ancho"=>$row->ancho,
                                       "precio"=>$row->precio,
                                       "id_estatus"=>13, //$row->id_estatus,
                                       "id_lote"=>$row->id_lote,
                                       "id"=>$row->id,
                                       "num_partida"=>$row->num_partida,
                                       "peso_real"=>$row->peso_real,
                                       "peso_real_devolucion"=>$row->peso_real_devolucion,
                                       "codigo_contable"=>$row->codigo_contable,
                                       
                                       
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    


   ////////////////////Productos del inventario


     public function buscador_prod_inven($data){
            $this->db->select('m.codigo');
            $this->db->select('m.referencia');
            $this->db->select('m.num_partida,m.id_factura,m.id_fac_orig, m.id_tipo_pago');
            $this->db->select('m.id_descripcion,c.color,m.id_color, co.composicion, ca.calidad');
            $this->db->select('m.id_composicion,m.id_calidad'); //m.id_color,
            
            $this->db->select('m.movimiento,m.movimiento_unico, m.fecha_entrada, p.nombre proveedor, m.factura, m.cantidad_um');
            $this->db->select('m.id_medida, m.ancho,m.precio,m.iva, m.id_estatus, m.id_lote, m.id ');
            $this->db->select('m.peso_real');
            $this->db->select("prod.codigo_contable, m.id_almacen");  
            $this->db->from($this->registros_entradas.' as m');
            $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
            $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');
            $this->db->join($this->calidades.' As ca' , 'ca.id = m.id_calidad','LEFT');
            $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
            
            $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
            //$this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT'); //um.medida

            
            $where = '(
                        (
                          ( m.id_apartado = 0 ) AND  ( m.estatus_salida = "0" ) AND ( m.proceso_traspaso = 0 )
                        ) 
                         AND
                        (
                          ( m.codigo LIKE  "%'.$data['key'].'%" ) OR (m.referencia LIKE  "%'.$data['key'].'%") 
                         )

              )';   

              //AND (m.id_almacen = '.$data['id_almacen'].' )  
  
            $this->db->where($where);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->codigo." | ".$row->referencia,
                                       "key"=>$row->codigo,
                                       "descripcion"=>$row->codigo,
                                       "id_descripcion"=>$row->id_descripcion,
                                       "id_color"=>$row->id_color,
                                       "id_composicion"=>$row->id_composicion,
                                       "id_calidad"=>$row->id_calidad,
                                       "id_movimiento"=>$row->movimiento,
                                       "movimiento_unico"=>$row->movimiento_unico,
                                       
                                       "fecha_entrada"=>$row->fecha_entrada,
                                       "proveedor"=>$row->proveedor,
                                       "factura"=>$row->factura,
                                       "cantidad_um"=>$row->cantidad_um,
                                       "id_medida"=>$row->id_medida,
                                       "ancho"=>$row->ancho,
                                       "precio"=>$row->precio,
                                       "id_estatus"=>$row->id_estatus,
                                       "id_lote"=>$row->id_lote,
                                       "id"=>$row->id,
                                       "num_partida"=>$row->num_partida,
                                       "peso_real"=>$row->peso_real,
                                       "iva"=>$row->iva,
                                       "id_factura"=>$row->id_factura,
                                       "id_fac_orig"=>$row->id_fac_orig,
                                       
                                       "id_tipo_pago"=>$row->id_tipo_pago,
                                       "codigo_contable"=>$row->codigo_contable,
                                       "id_almacen"=>$row->id_almacen,

                                       
                                       

                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    


      //-----------proveedores------------------

        public function total_proveedores(){
           $this->db->from($this->proveedores);
           $proveedores = $this->db->get();            
           return $proveedores->num_rows();
        }

        public function listado_proveedores($limit=-1, $offset=-1,$id=""){

          /*
          Datos de la empresa

          id, uid, codigo, nombre,  direccion, telefono, cliente, proveedor,  id_usuario, fecha_mac
          */          


          $this->db->select('p.id, p.uid, p.codigo, p.nombre,  p.direccion, p.telefono,  p.coleccion_id_actividad, p.id_usuario, p.fecha_mac'); 
         // $this->db->select("self::proveedores_en_uso('p.id') uso", FALSE);       



          $this->db->from($this->proveedores.' as p');

          //$this->db->select("( CASE WHEN ( LOCATE('3', p.coleccion_id_actividad) >0) THEN 'Si' ELSE '' END ) AS guiada", FALSE);       

          //$this->db->where('LOCATE("3", p.coleccion_id_actividad) >0');

          if ($id!="") {
              $this->db->where($id); 
          } 
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::proveedores_en_uso($row->id);
                 }                 
               return $result->result();
            }

            else
               return False;
            $result->free_result();
        }        




      public function buscador_proveedores($data){
            $this->db->select( 'id codigo' );
            $this->db->select("nombre", FALSE);  
            $this->db->from($this->proveedores);
            
          $where = '(
                      (
                        (LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)
                      ) 
                       AND
                      (
                        ( codigo LIKE  "%'.$data['key'].'%" ) OR (nombre LIKE  "%'.$data['key'].'%") 
                       )

            )';   
  
          $this->db->where($where);

          /*
            $this->db->where('(LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)' );
          */
              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->codigo." | ".$row->nombre,
                                       "key"=>$row->codigo,
                                       "descripcion"=>$row->nombre
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    



      public function buscador_tiendas($data){
            $this->db->select( 'id' );
            $this->db->select("nombre", FALSE);  
            $this->db->from($this->catalogo_tiendas);

          $where = '(  (activo<>1) AND (id='.$this->session->userdata("id_tienda_cliente").') AND 
                        (( id LIKE  "%'.$data['key'].'%" ) OR (nombre LIKE  "%'.$data['key'].'%")) 

            )';   
  
          $this->db->where($where);

          /*
            $this->db->where('(LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)' );
          */
              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->id." | ".$row->nombre,
                                       "key"=>$row->id,
                                       "descripcion"=>$row->nombre
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    


      public function buscador_bodegas($data){
            $this->db->select( 'id' );
            $this->db->select("almacen", FALSE);  
            $this->db->from($this->almacenes);

            //AND (id='.$this->session->userdata("id_tienda_cliente").')
          $where = '(  (id <>  '.$data['id_almacen'].') AND  (activo=1)  AND 
                        (( id LIKE  "%'.$data['key'].'%" ) OR (almacen LIKE  "%'.$data['key'].'%")) 

            )';   
  
          $this->db->where($where);

          /*
            $this->db->where('(LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)' );
          */
              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->id." | ".$row->almacen,
                                       "key"=>$row->id,
                                       "descripcion"=>$row->almacen
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    

public function checar_existente_tienda($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->catalogo_tiendas);
            $where = '(
                          ( nombre =  "'.addslashes($data['descripcion']).'" ) 
              )';   
            $this->db->where($where);
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    } 


public function checar_existente_bodega($data){
            $this->db->select("id", FALSE);         
            $this->db->from($this->almacenes);
            $where = '(
                          ( almacen =  "'.addslashes($data['descripcion']).'" ) 
              )';   
            $this->db->where($where);
            $login = $this->db->get();
            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                return $fila->id;
            }    
            else
                return false;
            $login->free_result();
    } 
    
    //checar si el proveedor ya existe
    public function check_existente_proveedor($data){
            $this->db->select("codigo");         
            $this->db->from($this->proveedores);
                $where = '( codigo =  "'.addslashes($data['codigo']).'" )';   

            $this->db->where($where);
            
            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return true;
            else
                return false;
            $login->free_result();
    } 

     public function tomar_proveedor( $data ){
          $this->db->select('p.id, p.uid, p.codigo, p.nombre,p.dias_ctas_pagar,  p.direccion, p.telefono,  p.coleccion_id_actividad, p.id_usuario, p.fecha_mac'); 
          $this->db->from($this->proveedores.' as p');

            $this->db->where('p.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

     public function coger_proveedor( $data ){
          $this->db->select('p.id, p.uid, p.codigo, p.nombre,p.dias_ctas_pagar,  p.direccion, p.telefono,  p.coleccion_id_actividad, p.id_usuario, p.fecha_mac'); 
          $this->db->from($this->proveedores.' as p');

            $this->db->where('p.codigo',$data['codigo']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear
        public function anadir_proveedor( $data ){

          //id, uid, codigo, nombre,  direccion, telefono,  coleccion_id_actividad, id_usuario, fecha_mac'); 


          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          //$this->db->set( 'id', $data['id'] ); // autoincrementable
          //$this->db->set( 'uid', $data['uid'] );  
          $this->db->set( 'codigo', $data['codigo'] );  
          $this->db->set( 'nombre', $data['nombre'] ); 
          $this->db->set( 'dias_ctas_pagar', $data['dias_ctas_pagar'] ); 
          $this->db->set( 'direccion', $data['direccion'] );  
          $this->db->set( 'telefono', $data['telefono'] );  
          $this->db->set( 'coleccion_id_actividad', $data['coleccion_id_actividad'] );  


            $this->db->insert($this->proveedores );
            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_proveedor( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          //$this->db->set( 'uid', $data['uid'] );  
          $this->db->set( 'codigo', $data['codigo'] );  
          $this->db->set( 'nombre', $data['nombre'] );            
          $this->db->set( 'dias_ctas_pagar', $data['dias_ctas_pagar'] ); 
          $this->db->set( 'direccion', $data['direccion'] );  
          $this->db->set( 'telefono', $data['telefono'] );  
          $this->db->set( 'coleccion_id_actividad', $data['coleccion_id_actividad'] );  

          $this->db->where('codigo', $data['codigo_ant'] );
          $this->db->update($this->proveedores );
          return TRUE;
            
                $result->free_result();
        }   


        //eliminar proveedor
        public function eliminar_proveedor( $data ){
            $this->db->delete( $this->proveedores, array( 'codigo' => $data['codigo'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     




      //-----------productos------------------

        public function total_productos(){
           $this->db->from($this->productos);
           $productos = $this->db->get();            
           return $productos->num_rows();
        }

       

        public function listado_colores_unico(){

          $this->db->distinct();
          $this->db->select('c.id, c.color, c.hexadecimal_color');
          $this->db->from($this->colores.' as c');
          $this->db->order_by('c.color', 'asc'); 
          
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        




       public function listado_productos_unico_activo(){

          $this->db->distinct();
          $this->db->select('p.descripcion');
          $this->db->from($this->productos.' as p');
          $this->db->where('p.activo',0);
          $this->db->order_by('p.descripcion', 'asc'); 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        


       public function listado_productos_unico(){

          $this->db->distinct();
          $this->db->select('p.descripcion');
          $this->db->from($this->productos.' as p');
          $this->db->where('p.activo',0);
          $this->db->order_by('p.descripcion', 'asc'); 
          $result = $this->db->get();
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        

       public function listado_productos_existente($data){

          $this->db->distinct();
          $this->db->select('p.descripcion');
          $this->db->select("p.descripcion nombre", FALSE);  
          $this->db->select("p.descripcion id", FALSE);  
          $this->db->from($this->productos.' as p');
          $this->db->join($this->registros_entradas.' As m', 'm.referencia = p.referencia');
          $this->db->join($this->proveedores.' As prov' , 'prov.id = m.id_empresa','LEFT');
         /*
          $this->db->where('p.activo',0);
          $this->db->where('m.id_almacen',$data['id_almacen']);
          $this->db->where('m.id_factura',$data['id_factura']);
          */
         
         if  ($data['proveedor']!=''){
            $provee= 'AND ( prov.nombre LIKE  "%'.$data['proveedor'].'%" )'   ; 
         } else {
            $provee= '';
         }
         

          $where = '(
                      (
                         ( p.activo = 0 ) AND ( m.id_almacen = '.$data['id_almacen'].' ) AND ( m.id_factura = '.$data['id_factura'].' ) 
                      )'.$provee.'
            ) ' ; 

           $this->db->where($where); 


          $this->db->order_by('p.descripcion', 'asc'); 

          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }       


        public function listado_productos($limit=-1, $offset=-1){
          $this->db->select('p.id, p.uid, p.referencia,  p.comentario');
          $this->db->select('p.descripcion, p.minimo,p.minimo_kg, p.imagen, p.id_composicion, p.id_color,p.id_calidad,p.precio,p.ancho');
          $this->db->select('p.id_usuario, p.fecha_mac, c.hexadecimal_color,c.color nombre_color');

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          
          
          if ($limit!=-1) {
              $this->db->limit($limit, $offset); 
          } 
          $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        





/////////////////////////////////////////////
      public function buscar_productos(){
            $this->db->distinct();
            $this->db->select("p.descripcion", FALSE);  
            $this->db->from($this->productos.' as p');

            $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "descripcion"=>$row->descripcion,
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }   


      public function buscar_colores($data){
            $this->db->distinct();
            
            $this->db->select("p.id_color", FALSE);  
            $this->db->select("c.color", FALSE);  

            $this->db->from($this->productos.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            $this->db->like("p.descripcion" ,$data['producto'],FALSE);

            $this->db->order_by('c.color', 'asc'); 

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "color"=>$row->color,
                                      "id_color"=>$row->id_color
                                    );
                      }
                      return json_encode($dato);
                      
              }   
              else 
                 return False;
              $result->free_result();
      }   


      public function buscar_composicion($data){
            $this->db->distinct();
            $this->db->select("p.id_composicion", FALSE);  
            $this->db->select("co.composicion", FALSE);  
            
            $this->db->from($this->productos.' as p');
            $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
            $this->db->like("p.descripcion" ,$data['producto'],FALSE);
            $this->db->like("p.id_color" ,$data['color'],FALSE);
            $this->db->order_by('co.composicion', 'asc'); 

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "id_composicion"=>$row->id_composicion,
                                      "composicion"=>$row->composicion
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }       



     public function buscar_calidad($data){
            $this->db->distinct();
            $this->db->select("p.id_calidad", FALSE);  
            $this->db->select("ca.calidad", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

            $this->db->like("p.descripcion" ,$data['producto'],FALSE);
            $this->db->like("p.id_color" ,$data['color'],FALSE);
            $this->db->like("p.id_composicion" ,$data['composicion'],FALSE);

            $this->db->order_by('ca.calidad', 'asc'); 


              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "id_calidad"=>$row->id_calidad,
                                      "calidad"=>$row->calidad,
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    



      public function buscar_completo($data){
            
           // $this->db->distinct();
            $this->db->select("p.id", FALSE);  
            $this->db->select("p.referencia", FALSE);  
            $this->db->select("p.precio,p.comentario,p.ancho");  
            
            $this->db->from($this->productos.' as p');
            

            $this->db->like("p.descripcion" ,$data['producto'],FALSE);
            $this->db->like("p.id_color" ,$data['color'],FALSE);
            $this->db->like("p.id_composicion" ,$data['composicion'],FALSE);
            $this->db->like("p.id_calidad" ,$data['calidad'],FALSE);


              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "id"=>$row->id,
                                      "referencia"=>$row->referencia,
                                      "comentario"=>$row->comentario,
                                      "precio"=>$row->precio,
                                      "ancho"=>$row->ancho,
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }                



/////////////////////////////////////////////


      public function buscador_productos($data){
            $this->db->distinct();
            $this->db->select("c.hexadecimal_color");
            $this->db->select("c.color", FALSE);  
            $this->db->select("p.descripcion", FALSE);  
            $this->db->from($this->productos.' as p');
            $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
            $this->db->like("p.descripcion" ,$data['key'],FALSE);
            //$this->db->or_like("c.color" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "descripcion"=>$row->descripcion,
                                      "color"=>$row->color,
                                      "hexadecimal_color"=>$row->hexadecimal_color
                                    );
                      }
                      return json_encode($dato);
                      //return '[ {"nombre":"Jhon", "apellido":"calderón"}, {"nombre":"jean", "apellido":"calderón"}]';
              }   
              else 
                 return False;
              $result->free_result();
      }   



      public function buscador_provee_consulta($data){
            $this->db->select( 'p.codigo' );
            $this->db->select("p.nombre", FALSE);  
            

            $this->db->from($this->proveedores.' as p');
            $this->db->join($this->historico_registros_entradas.' As he', 'he.id_empresa = p.id');          


            
            
          $where = '(
                      (
                        (LOCATE("'.$data['idproveedor'].'", p.coleccion_id_actividad) >0)
                      ) 
                       AND
                      (
                        ( p.codigo LIKE  "%'.$data['key'].'%" ) OR (p.nombre LIKE  "%'.$data['key'].'%") 
                       )

            )';   


  
          $this->db->where($where);

          $this->db->group_by("p.nombre");

          /*
            $this->db->where('(LOCATE("'.$data['idproveedor'].'", coleccion_id_actividad) >0)' );
          */
              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) 
                      {
                            $dato[]= array(
                                       "value"=>$row->codigo." | ".$row->nombre,
                                       "key"=>$row->codigo,
                                       "descripcion"=>$row->nombre
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    


      public function buscador_prod_consulta($data){
            
            $this->db->distinct();
            $this->db->select("descripcion", FALSE);  
            $this->db->from($this->productos.' As p');
            $this->db->join($this->historico_registros_entradas.' As he', 'he.referencia = p.referencia','RIGHT');


            $where = '(
                        (
                          (descripcion LIKE  "%'.$data['key'].'%") 
                         )
            )';


  
          $this->db->where($where);

          $this->db->group_by("p.descripcion");
          

          $result = $this->db->get();
          if ( $result->num_rows() > 0 ) {
              foreach ($result->result() as $row) 
                  {
                        $dato[]= array(
                                   "value"=>$row->descripcion,
                                   "key"=>$row->descripcion,
                                   "descripcion"=>$row->descripcion
                                );
                  }
                  return json_encode($dato);
          }   
          else 
             return False;
          $result->free_result();
      }    





/*       
      public function buscador_productos($data){
            $this->db->select("id");
            $this->db->select("referencia");  
            $this->db->select("descripcion", FALSE);  
            $this->db->from($this->productos);
            $this->db->like("referencia" ,$data['key'],FALSE);
            $this->db->or_like("descripcion" ,$data['key'],FALSE);

              $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      "value"=>$row->referencia." | ".$row->descripcion,
                                       "key"=>base64_encode($row->id) //
                                    );
                      }
                      return json_encode($dato);
              }   
              else 
                 return False;
              $result->free_result();
      }    
      */
    
    



    //checar si el producto ya existe
    public function grupo_producto($data){
    
            $this->db->select("grupo", FALSE);         
            $this->db->from($this->productos);
            $this->db->where('descripcion',$data['descripcion']);  
            $this->db->where('id_composicion',$data['id_composicion']);  
            $this->db->where('id_calidad',$data['id_calidad']);  
            

            $registros = $this->db->get();
            if ($registros->num_rows() > 0) {
                $fila = $registros->row(); 
                return $fila->grupo;
            }    
            else
                return false;
            $registros->free_result();


          
    } 
    




    //checar si el producto ya existe
    public function check_existente_producto($data){
    
        $cant=0; 

        for ($i=0; $i <= count($data['colores'])-1 ; $i++) { 
            $this->db->select("grupo", FALSE);         
            $this->db->from($this->productos);

            $where = '(
                        (
                          ( descripcion      =  "'.addslashes($data['descripcion']).'" ) AND
                          ( id_color         =   '.$data['colores'][$i].' ) AND
                          ( id_composicion   =   '.$data['id_composicion'].' ) AND
                          ( id_calidad       =   '.$data['id_calidad'].' ) 

                          
                         )

              )';   
  
            $this->db->where($where);

            /*

            $this->db->where('id_color',$data['colores'][$i]);  
            $this->db->where('id_composicion',$data['id_composicion']);  
            $this->db->where('id_calidad',$data['id_calidad']);  
            */

            $registros = $this->db->get();
           
            if ($registros->num_rows() > 0){
               $cant++;
            }

        }
            
            if ($cant > 0) {
                return TRUE;
            }  else {

                return false;
                $login->free_result();
            }
    } 
    


    public function checkar_existente_producto_editado($data){
    
            $this->db->select("grupo", FALSE);         
            $this->db->from($this->productos);

            if (!isset($data['id'])) {
              $data['id']=-1;
            }
            

            $where = '(
                        (
                          ( descripcion =  "'.addslashes($data['descripcion']).'" ) AND
                          ( id_color =  "'.$data['id_color'].'" ) AND
                          ( id_composicion =  "'.$data['id_composicion'].'" ) AND
                          ( id_calidad =  "'.$data['id_calidad'].'" ) AND
                          (id <>  '.$data['id'].') 
                         )

              )';   
  
            $this->db->where($where);

            $registros = $this->db->get();
           
            
            if ($registros->num_rows() > 0){
                return true;
            }  else {

                return false;
                $login->free_result();
            }
    } 


     public function coger_producto( $data ){

          $this->db->select('p.id, p.uid, p.referencia,p.comentario, p.consecutivo, id_imagen_check');
          $this->db->select('p.descripcion, p.minimo,p.minimo_kg, p.imagen, p.id_composicion, p.codigo_contable, p.id_color,p.id_calidad,p.precio,p.ancho');
          $this->db->select('p.id_usuario, p.fecha_mac');

          $this->db->from($this->productos.' as p');
          
            $this->db->where('p.id',$data['id']);
            $result = $this->db->get(  );
                if ($result->num_rows() > 0)
                    return $result->row();
                else 
                    return FALSE;
                $result->free_result();
     }  

      //crear

        public function anadir_producto( $data ){

          $id_session = $this->session->userdata('id');


          
         for ($i=0; $i <= count($data['colores'])-1 ; $i++) { 

               if (($i==0) and ($data['nuevo'] =='si') ) {
                  $this->db->set( 'id_imagen_check',  '1' );                
               }



               $this->db->set( 'id_usuario',  $id_session );
               $this->db->set( 'grupo',  $data['grupo'] );


               $this->db->set( 'referencia', $data['referencia'.$i] );   
               $this->db->set( 'descripcion', $data['descripcion'] );  
               $this->db->set( 'minimo', $data['minimo'] );  
               $this->db->set( 'minimo_kg', $data['minimo_kg'] );  

               $this->db->set( 'precio', $data['precio'] );  
               $this->db->set( 'ancho', $data['ancho'] );  

               //$this->db->set( 'precio_anterior', $data['precio'] );  

               $this->db->set( 'id_composicion', $data['id_composicion'] );  
               $this->db->set( 'id_color', $data['colores'][$i] );  

               $this->db->set( 'id_calidad', $data['id_calidad'] );  
               $this->db->set( 'comentario', $data['comentario'] );  

              if  (isset($data['archivo_imagen'])) {
                $this->db->set( 'imagen', $data['archivo_imagen']['file_name']);          
              }  

              $this->db->insert($this->productos );

         } //fin del for






            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }          


        //editar
        public function editar_producto( $data ){



          if ($data['id_imagen_check'] ) {

            //obtener grupo del id pasado
            $this->db->select("grupo", FALSE);         
            $this->db->from($this->productos);
            $this->db->where('id', $data['id'] );
            $login = $this->db->get();

            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                $grupo = $fila->grupo;
            }    


            ///////////////grupo

            //pone a    id_imagen_check=''  que pertenecen al grupo
            $this->db->update($this->productos, array('id_imagen_check' => ''), array('grupo' => $grupo));  
          } 
          

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          if ($data['id_imagen_check'] ) {
            $this->db->set( 'id_imagen_check', $data['id_imagen_check'] );   
          }  
          $this->db->set( 'descripcion', $data['descripcion'] );  
          $this->db->set( 'minimo', $data['minimo'] );  
          $this->db->set( 'minimo_kg', $data['minimo_kg'] );  
          $this->db->set( 'precio', $data['precio'] );  
          $this->db->set( 'ancho', $data['ancho'] );  
          
          $this->db->set( 'id_composicion', $data['id_composicion'] );  
          $this->db->set( 'id_color', $data['id_color'] );  
          $this->db->set( 'id_calidad', $data['id_calidad'] );  
          $this->db->set( 'comentario', $data['comentario'] );  
          $this->db->set( 'codigo_contable', $data['codigo_contable'] );  

          if  (isset($data['archivo_imagen'])) {
            $this->db->set( 'imagen', $data['archivo_imagen']['file_name']);          
              $imagen = 'si';
          }  else {
              $imagen = 'no';           
          }



          $this->db->where('id', $data['id'] );
          $this->db->update($this->productos );
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            }  else {
                if ($imagen=='si') {
                  return TRUE;
                } else {
                   return FALSE; 
                   $result->free_result();
                }
            }    
                 
                
        }   



        //editar
        public function editar_minimo( $data ){

          $id_session = $this->session->userdata('id');

          $this->db->set( 'id_usuario',  $id_session );
          $this->db->set( 'minimo', $data['minimo'] );  
          $this->db->set( 'minimo_kg', $data['minimo_kg'] );  

          $this->db->where('id', $data['id'] );
          $this->db->update($this->productos );

          if ($this->db->affected_rows() > 0) {
              return TRUE;
          }  else {
                 return FALSE; 
                 $result->free_result();
          }
              
        }   


      //editar
        public function cambiar_precio_producto( $data ){

          $id_session = $this->session->userdata('id');
          $this->db->set( 'id_usuario',  $id_session );

          //$this->db->set( 'precio_anterior', 'precio', FALSE  );
          $this->db->set( 'minimo', $data['minimo'] );  
          $this->db->set( 'minimo_kg', $data['minimo_kg'] );  
          $this->db->set( 'codigo_contable', $data['codigo_contable'] );  
          $this->db->set( 'precio', $data['precio'] );  
          $this->db->set( 'comentario', $data['comentario'] );  
          

          if  (isset($data['archivo_imagen'])) {
            $this->db->set( 'imagen', $data['archivo_imagen']['file_name']);          
          }


          $this->db->where('id', $data['id'] );
          $this->db->update($this->productos );
       
          
          return true;
          $result->free_result();

        }   


          /*
            caso de apartados 
              Pedidos de vendedores: 2 y 3
                  $where_total = '( m.id_apartado = 2 ) or ( m.id_apartado = 3 ) ';
                    **eliminar_apartado_detalle (precios actuales)

              Pedidos de tiendas: 5 y 6
                  $where_total = '( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) ';
                  **eliminar_pedido_detalle (precios actuales)

                 Nota: va a los precios actuales cuando se quiten
                 
          



           Generar pedido: 4
                  $where_total = '(( m.id_apartado = 4 ) )';
                  **quitar_pedido (precios actuales)

            
            .apartar(este es el caso de cuando apartan los vendedores)
                $where_total = '(( m.id_apartado = 1 ) )';
                ** .quitar



          salida
              'estatus_salida', '1'

             **.quitar

          */



        //eliminar producto
        public function eliminar_producto( $data ){
        

            $this->db->select("grupo", FALSE);         
            $this->db->from($this->productos);
            $this->db->where('id', $data['id'] );
            $login = $this->db->get();

            if ($login->num_rows() > 0) {
                $fila = $login->row(); 
                $grupo = $fila->grupo;
            }    


            ///////////////actualizar el primero 

          $this->db->set( 'id_imagen_check', '1' );  

          $where = '(
                      (
                        ( grupo =  "'.$grupo.'" ) and (id <> '.$data["id"].')                        
                       )
            )';   

 
            $this->db->where($where);
            $this->db->order_by('fecha_mac','DESC');
            
            $this->db->limit(1,0);
            
            $this->db->update($this->productos );

 
          



            $this->db->delete( $this->productos, array( 'id' => $data['id'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }     


        public function total_archivos($data){

            $this->db->select("( CASE WHEN imagen = '' THEN 0 ELSE 1 END ) AS cantidad", FALSE);
            $this->db->select('imagen archivo');
            
            $this->db->where('id', $data['id'] );

            $result = $this->db->get( $this->db->dbprefix($data['tabla']) );
            return $result->row();
            $result->free_result();
        }



	} 


?>
