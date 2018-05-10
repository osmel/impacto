<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

  class modelo_reportes extends CI_Model{ 
    
    private $key_hash;
    private $timezone;

    function __construct(){

      parent::__construct();
      $this->load->database("default");
      $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
      $this->timezone    = 'UM1';
      date_default_timezone_set('America/Mexico_City'); 
      
        //usuarios
      $this->usuarios    = $this->db->dbprefix('usuarios');
        //catalogos     
      $this->actividad_comercial     = $this->db->dbprefix('catalogo_actividad_comercial');
      $this->cargadores             = $this->db->dbprefix('catalogo_cargador');
      
      $this->estratificacion_empresa = $this->db->dbprefix('catalogo_estratificacion_empresa');
      
      $this->productos               = $this->db->dbprefix('catalogo_productos');
      $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
      $this->movimientos               = $this->db->dbprefix('movimientos');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros               = $this->db->dbprefix('registros_entradas');
      

      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');

      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      
      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      
      
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad');

      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
      $this->almacenes       = $this->db->dbprefix('catalogo_almacenes');
      $this->catalogo_tipos_pagos  = $this->db->dbprefix('catalogo_tipos_pagos');
      
      $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
      $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
      $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');

      $this->historico_ctasxpagar                           = $this->db->dbprefix('historico_ctasxpagar');
      $this->historico_pagos_realizados                           = $this->db->dbprefix('historico_pagos_realizados');
      
      $this->documentos_pagos                           = $this->db->dbprefix('catalogo_documentos_pagos');

      $this->catalogo_tiendas  = $this->db->dbprefix('catalogo_tiendas');

    }
     

          public function listado_productos_completa($data){

                
                $this->db->select('p.id');
                $this->db->select('p.descripcion nombre');

                $this->db->select('"'.$data['val_prod_id'].'" as activo', false);

                $filtro="";        

                if  ($data['val_color']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_color = ".$data["val_color"].") ";
                }
                if ($data['val_comp'] !=0) {
                  $filtro.= (($filtro!="") ? " and " : "") . "(c.id_composicion = '".$data["val_comp"]."') ";
                } 
                if  ($data['val_calidad']!=0){
                   $filtro.= (($filtro!="") ? " and " : "") . "(c.id_calidad = ".$data["val_calidad"].") ";
                }

                
                

                switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_descripcion =  p.descripcion'); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_salidas.' As c', 'p.referencia = c.referencia  and c.id_descripcion =  p.descripcion'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->historico_registros_entradas.' As c', 'p.referencia = c.referencia  and c.id_descripcion =  p.descripcion'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                            $this->db->select("COUNT(c.referencia) as 'suma'");
                            $this->db->select("p.minimo, p.precio");
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_estatus=12  and c.id_descripcion =  p.descripcion'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("p.referencia, p.minimo,p.precio"); 
                           
                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < p.minimo))');
                                  $where_total = '((suma>0) AND (suma < p.minimo))';
                              }  
                         break;
                         case 'cero':
                            $this->db->select("COUNT(c.referencia) as 'suma'");
                            $this->db->select("p.minimo, p.precio");
                            $this->db->from($this->productos.' as p');
                            $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_estatus=12 and c.id_descripcion =  p.descripcion and p.activo =  0','LEFT');  // 

            
                           $filtro="";                              


                            if  ($data['val_color']!=0){
                               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
                            }
                            if ($data['val_comp'] !=0) {
                              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = '".$data["val_comp"]."') ";
                            } 
                            if  ($data['val_calidad']!=0){
                               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_calidad = ".$data["val_calidad"].") ";
                            }


                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("p.referencia, p.minimo,p.precio"); 

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 0');
                                  $where_total = 'suma <= 0';
                              }   
                             
                         break;

                      default:
                          $this->db->from($this->productos.' as p');
                          $this->db->join($this->registros_entradas.' As c', 'p.referencia = c.referencia and c.id_descripcion =  p.descripcion'); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                

                        break;
                }

                
                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');

                  if ( $result->num_rows() > 0 )
                     return $result->result();
                  else
                     return False;
                  $result->free_result();
        }     





  public function lista_color_completa($data){

            $this->db->distinct();

        
            $this->db->select("c.color nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select("c.hexadecimal_color", FALSE);  
            $this->db->select($data['val_color'].' as activo', false);
            //$this->db->from($this->registros_entradas.' as p');
            //$this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');


            $filtro="";        

            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".addslashes($data["val_prod"])."') ";
            } 
            if ($data['val_comp'] !=0) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = '".$data["val_comp"]."') ";
            } 
            if  ($data['val_calidad']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_calidad = ".$data["val_calidad"].") ";
            }

              switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->historico_registros_salidas.' as p');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->historico_registros_entradas.' as p');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                      
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12');
                            $this->db->join($this->colores.' As c', 'c.id = p.id_color'); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 
 
                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < prod.minimo))');
                                  $where_total = '((suma>0) AND (suma < prod.minimo))';
                              }  
                         break;

                         case 'cero':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12 and p.id_descripcion =  prod.descripcion','LEFT');
                            $this->db->join($this->colores.' As c', 'c.id = prod.id_color'); 
                            

                          $filtro="";        

                          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                            $filtro.= (($filtro!="") ? " and " : "") . "(prod.descripcion = '".addslashes($data["val_prod"])."') ";
                          } 
                          if ($data['val_comp'] !=0) {
                            $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_composicion = '".$data["val_comp"]."') ";
                          } 
                          if  ($data['val_calidad']!=0){
                             $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_calidad = ".$data["val_calidad"].") ";
                          }



                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 1'); //0
                                  $where_total = 'suma <= 0'; //0
                              }   
                             
                         break;

                      default:
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->colores.' As c', 'c.id = p.id_color '); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );             

                        break;
                }

                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');

            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }         


  public function lista_composiciones_completa($data){

            $this->db->distinct();
            $this->db->select("c.composicion nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select($data['val_comp'].' as activo', false);
            //$this->db->from($this->registros_entradas.' as p');
            //$this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id','LEFT');

            $filtro="";        
            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".addslashes($data["val_prod"])."') ";
            } 
            if  ($data['val_color']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
            }
            if  ($data['val_calidad']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_calidad = ".$data["val_calidad"].") ";
            }
  


                switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id'); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->historico_registros_salidas.' as p');
                            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id'); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->historico_registros_entradas.' as p');
                            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id'); 
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12');
                            $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id'); 

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 

                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < prod.minimo))');
                                  $where_total = '((suma>0) AND (suma < prod.minimo))';
                              }  
                         break;
                        case 'cero':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");


                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12 and p.id_descripcion =  prod.descripcion','LEFT');
                            $this->db->join($this->composiciones.' As c', 'c.id = prod.id_composicion'); 

                            $filtro="";        
                            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                              $filtro.= (($filtro!="") ? " and " : "") . "(prod.descripcion = '".addslashes($data["val_prod"])."') ";
                            } 
                            if  ($data['val_color']!=0){
                               $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_color = ".$data["val_color"].") ";
                            }
                            if  ($data['val_calidad']!=0){
                               $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_calidad = ".$data["val_calidad"].") ";
                            }                            


                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 
                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 1');
                                  $where_total = 'suma <= 0';
                              }   
                         break;                         
                      default:
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->composiciones.' As c', 'p.id_composicion = c.id'); 
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );             

                        break;
                }

                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');



            
            //$result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }         


 public function lista_calidad_completa($data){

            $this->db->distinct();
            $this->db->select("c.calidad nombre", FALSE);  
            $this->db->select("c.id", FALSE);  
            $this->db->select($data['val_comp'].' as activo', false);
            //$this->db->from($this->registros_entradas.' as p');
            //$this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');

            $filtro="";        

            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_descripcion = '".addslashes($data["val_prod"])."') ";
            } 
            if  ($data['val_color']!=0){
               $filtro.= (($filtro!="") ? " and " : "") . "(p.id_color = ".$data["val_color"].") ";
            }
            if ($data['val_comp'] !=0) {
              $filtro.= (($filtro!="") ? " and " : "") . "(p.id_composicion = '".$data["val_comp"]."') ";
            } 



                switch ($data['extra_search']) {    
                     case 'existencia':
                      case 'apartado':
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );                
                         break;
                      case 'salida':
                            $this->db->from($this->historico_registros_salidas.' as p');
                            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                          
                            $this->db->group_by( 'nombre' );                
                         break;
                      case 'devolucion':
                      case 'entrada':
                            $this->db->from($this->historico_registros_entradas.' as p');
                            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');
                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }                   
                            $this->db->group_by( 'nombre' );                       
                         break;
                      case 'baja':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12');
                            $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');

                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 


                              if ($data['extra_search']=="baja") {
                                  $this->db->having('((suma>0) AND (suma < prod.minimo))');
                                  $where_total = '((suma>0) AND (suma < prod.minimo))';
                              }  
                         break;
                      case 'cero':
                            $this->db->select("COUNT(prod.referencia) as 'suma'");
                            $this->db->select("prod.minimo");

                            $this->db->from($this->productos.' as prod');
                            $this->db->join($this->registros_entradas.' As p', 'p.referencia = prod.referencia and p.id_estatus=12','LEFT');
                            $this->db->join($this->calidades.' As c', 'c.id = prod.id_calidad');

                          $filtro="";        

                          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                            $filtro.= (($filtro!="") ? " and " : "") . "(prod.descripcion = '".addslashes($data["val_prod"])."') ";
                          } 
                          if  ($data['val_color']!=0){
                             $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_color = ".$data["val_color"].") ";
                          }
                          if ($data['val_comp'] !=0) {
                            $filtro.= (($filtro!="") ? " and " : "") . "(prod.id_composicion = '".$data["val_comp"]."') ";
                          } 


                            if ($filtro !=""){
                              $this->db->where( $filtro );               
                            }
                            $this->db->group_by("prod.referencia"); 

                             if ($data['extra_search']=="cero") {
                                  $this->db->having('suma <= 1');
                                  $where_total = 'suma <= 0';
                              }   
                         break;

                      default:
                          $this->db->from($this->registros_entradas.' as p');
                          $this->db->join($this->calidades.' As c', 'p.id_calidad = c.id','LEFT');
                          if ($filtro !=""){
                            $this->db->where( $filtro );               
                          }                          
                          $this->db->group_by( 'nombre' );             

                        break;
                }

                $this->db->get();
                $consulta = $this->db->last_query();
                $result = $this->db->query('select * from ('.$consulta.') as cons_interna group by nombre order by nombre');
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }     

        
//////////////////////Auxiliar 
        public function check_existente_proveedor_entrada($descripcion){
            $this->db->select("pro.id", FALSE);         
            $this->db->from($this->proveedores.' as pro ');
            $where = '(
                        (
                          ( pro.nombre =  "'.$descripcion.'" ) 
                          
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


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function buscador_entrada_home($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $factura_reporte = addslashes($data['factura_reporte']);
          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];
           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 
          switch ($columa_order) {
                   /*case '0':
                        $columna = 'm.codigo';
                     break;*/
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.movimiento_unico';
                     break;
                   /*case '6':
                          if ($estatus=="apartado") {
                              $columna= 'pr.nombre';
                          }  else {
                              $columna= 'p.nombre';
                          }  
                     break;*/
                   case '6':
                          if ($estatus=="apartado") {
                              $columna= 'm.id_apartado';
                          }  else {
                              $columna= 'm.id_lote, m.consecutivo';  
                          }  
                     break;

                     /*
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;    
                     */                   
                   default:
                       $columna = 'm.id';
                       $order = 'DESC';                       
                     break;
                 }          
          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                          if ($estatus=="apartado") {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_apartado),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  else {
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          }  
          } else {
           $fechas .= ' ';
          }
          $donde = '';
         if ($id_empresa!="") {
              $id_empre =  self::check_existente_proveedor_entrada($id_empresa);
                if (!($id_empre)) {
                  $id_empre =0;
                }
              if ($estatus=="apartado") {
                  $donde .= ' AND ( us.id_cliente  =  '.$id_empre.' ) '; //id_cliente_apartado, id_usuario_apartado
              }    else {
                  $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';
              }
        } else 
        {
           $donde .= ' ';
        }
         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 
          $id_descripcion = addslashes($data['id_descripcion']);
          $id_color = $data['id_color'];
          $id_composicion = $data['id_composicion'];
          $id_calidad = $data['id_calidad'];
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)");  
          $this->db->select('m.id, m.movimiento,m.movimiento_unico, m.factura,m.id_factura,m.id_fac_orig, m.id_descripcion, m.num_partida,m.id_operacion'); 
          $this->db->select('m.codigo, m.ancho, m.devolucion, m.precio,m.c234'); 
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo,   m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color');

          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia'); 
              $this->db->select('m.id_apartado'); 
          }
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           WHEN "8" THEN "14b80f" 
                           ELSE "No Apartado"
                        END AS apartado
            ',False);
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");  
          $this->db->select("prod.codigo_contable, m.nombre_usuario,m.id_compra");  

          $this->db->select("( CASE WHEN m.nombre_usuario <> '' THEN t.nombre ELSE p.nombre END ) AS nombre");

          $this->db->select('tipfac.tipo_factura, m.id_almacen');
          
          
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_empresa','LEFT');

          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado'); 
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id'); 
          }    

          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); //


          if ($estatus=="apartado") {
            $cond= ' (pr.nombre LIKE  "%'.$cadena.'%") OR  ( m.id_apartado LIKE  "%'.$cadena.'%" )';                 
          } else {
            $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';
          }
          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }
          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }

          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      ( ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento_unico LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )
            ) ' ;                     

          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';
          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    
          if ($estatus=="apartado") {
              $where .= ' AND ( m.id_apartado != 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado != 0 ) ' ;   
          }    elseif ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 

         if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     

          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 


          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }


          
          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }                                    

          $where_total.= $donde.$fechas; 
          $this->db->where($where.$donde.$fechas); //
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              $data['where_total'] = $where_total; 
              $data['estatus'] = $estatus;
              if ( $result->num_rows() > 0 ) {
                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);
                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {
                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  


                             $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$row->id_descripcion,
                                          2=> $row->color.
                                            '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                          3=>$row->cantidad_um.' '.$row->medida,
                                          4=>$row->ancho.' cm',
                                          5=>
                                               '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode((($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))).$row->movimiento_unico).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                                   type="button" class="btn btn-success btn-block">'.'['.(($row->id_operacion==72) ? 'B' : (($row->id_operacion==71) ? 'C' : (($row->devolucion<>0) ? 'D' :  (($row->id_operacion==70) ? 'T' : (($row->id_operacion==73) ? 'A' :'E') ) ))).'] '.$row->id_almacen.'-'.$row->tipo_factura.'-'.$row->c234.'</a>', 
                                          6=>$columna7,//id_lote
                                          7=>$row->metros, // 9
                                          8=>$row->kilogramos,  //10
                                          9=>$row->color_devolucion, //11
                                          10=>$row->proceso_traspaso, //15
                                          11=>$row->codigo_contable, //16
                                          12=>$row->id_estatus,
                                          13=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? $row->precio : '-'),
                                        );                    
                          
                          

                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 

                      ));
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::total_campos_entrada_home($data)->pieza ), "metro"=>floatval( self::total_campos_entrada_home($data)->metros ), "kilogramo"=>floatval( self::total_campos_entrada_home($data)->kilogramos )), 
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  


       public function total_campos_entrada_home($data){

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); 
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); 
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->registros.' as m');
              if ($data['estatus']=="apartado") {
                  $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado'); 
                  $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id'); 

              }    
              $this->db->where($data['where_total']);

             $result = $this->db->get();
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              
       }   






public function detalle_entrada_home($data){

          $estatus= $data['extra_search'];
          
          $this->db->select('m.id, m.movimiento,m.movimiento_unico, m.factura,m.id_factura,m.id_fac_orig, m.id_descripcion, m.num_partida'); 
          $this->db->select('m.codigo, m.ancho, m.devolucion'); 
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo,   m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color');
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia'); 
              $this->db->select('m.id_apartado'); 
          }
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Apartado"
                        END AS apartado
            ',False);
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");  
          $this->db->select("prod.codigo_contable");  

          $this->db->select("a.almacen");  
          $this->db->select("prod.imagen");  

          $this->db->select("( CASE WHEN m.nombre_usuario <> '' THEN t.nombre ELSE p.nombre END ) AS nombre");
          
          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 

           $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_empresa','LEFT');

          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado'); 
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id'); 
          }    


          $where = '( m.codigo =  "'.$data["identificador"].'" ) ' ;                              


          
          $this->db->where($where); //
            

          $result = $this->db->get();

              
              $data['estatus'] = $estatus;
              if ( $result->num_rows() > 0 ) {
                    
                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {

                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }



                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  


                             $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$columna6,//proveedor o dependencia
                                          2=> date( 'd-m-Y', strtotime($fecha)),
                                          3=>$row->num_partida,
                                          4=>$row->almacen,
                                          5=>$imagen,

                                        );                    
                          
                          

                      }

                      return json_encode ( array(
                        "data"            =>  $dato, 
                      ));
              }   
              else {
                  $output = array(
                    "aaData" => array(),
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   
   public function buscador_salida_home($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $id_empresa= addslashes($data['proveedor']);
          $factura_reporte = $data['factura_reporte'];
        $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];
           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 
          switch ($columa_order) {
                   /*case '0':
                        $columna = 'm.codigo';
                     break;*/
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.mov_salida_unico';
                     break;
                   /*case '6':
                              $columna= 'us.nombre';
                     break;*/
                   case '6':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                    /*  
                   case '8':
                        $columna = 'm.fecha_salida';
                     break;
                   case '12': 
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;

                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       
                     */
                   default:
                       $columna = 'm.id';
                       $order = 'DESC';                       
                     break;
                 }       

          $donde = '';
         if ($id_empresa!="") {
            $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                if (!($id_empre)) {
                  $id_empre =0;
                }
            $donde .= ' AND ( m.consecutivo_venta  =  '.$id_empre.' ) ';
        } else 
        {
           $donde .= ' ';
        }
        if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
        } 
          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                          
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                           $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 
                          
          } else {
           $fechas .= ' ';
          }

          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)");  
          $this->db->select('m.id, m.mov_salida,m.mov_salida_unico, m.factura, m.id_descripcion,  m.num_partida'); //m.id_empresa, m.id_operacion,
          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura, m.fecha_salida');
          $this->db->select('m.ancho,  m.codigo');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.proceso_traspaso, m.precio');
          $this->db->select('c.hexadecimal_color, c.color , p.nombre');

          $this->db->select('p.nombre cliente,ca.nombre cargador');  
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia'); 
          }
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion"); 
          $this->db->select("prod.codigo_contable");  
          
          //$this->db->select('us.nombre as nom_cliente');   
          $this->db->select("( CASE WHEN m.on_off = 1 THEN t.nombre ELSE us.nombre END ) AS nom_cliente");

            $this->db->select("m.id_operacion_salida");

             $this->db->select("
              CONCAT('[',
              ( CASE 
                WHEN (m.id_operacion_salida=2)  THEN 'S' 
                 WHEN (m.id_operacion_salida=95)  THEN 'B'  
                 WHEN (m.id_operacion_salida=93)  THEN 'A' 
                 WHEN (m.id_operacion_salida=99)  THEN 'J' 
                else 'T' 
              end),
              ']',m.id_almacen,'-',  
                (CASE 
                 WHEN (m.id_tipo_pedido=3)  THEN 'G' 
                 WHEN (m.id_tipo_pedido=2)  THEN 'S'  
                else tf.tipo_factura
              end)

              ,'-',m.cs234   
             )
              AS movi_salida",FALSE);



            $this->db->select("m.id_operacion_pedido");
            $this->db->select("
                CONCAT('[',
                ( CASE 
                  WHEN (m.id_operacion_pedido=4)  THEN 'S' 
                   WHEN (m.id_operacion_pedido=98)  THEN 'B'  
                   WHEN (m.id_operacion_pedido=96)  THEN 'A' 
                   WHEN (m.id_operacion_pedido=99)  THEN 'J' 
                  else 'T' 
                end),
                ']',m.id_almacen,'-',  
                  (CASE 
                   WHEN (m.id_tipo_pedido=3)  THEN 'G' 
                   WHEN (m.id_tipo_pedido=2)  THEN 'S'  
                  else tf.tipo_factura
                end)

                ,'-',m.cp234   
               )
                AS movi_pedido",FALSE);          

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          $this->db->join($this->proveedores.' As us' , 'us.id = m.consecutivo_venta','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');  //
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador');                
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');  


          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado'); 
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id'); 
          }    
          if ($id_estatus!=0) {
            $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
          } else {
            $estatus_idid = '';
          }          
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }

          if ($id_factura!=0) {
             $id_factura = (($id_factura==3) ? 0 : $id_factura);
             $id_facturaid = ' and ( m.id_tipo_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }    


          $where = '(
                      (
                         ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (  ( m.num_partida LIKE  "%'.$cadena.'%" ) OR   
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.mov_salida_unico LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%")  OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") 
                        OR (us.nombre LIKE  "%'.$cadena.'%")
                       )
            ) ' ;   

              $where_total = '( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid;

               if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
                   $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                   $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
                }     
                if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
                } 
                if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
                   $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                   $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
                }

                //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
                if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                    $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                    $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
                }                                    

          $where_total .= $donde.$fechas;
          $this->db->where($where.$donde.$fechas); 
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                          if ($estatus=="apartado") {

                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  


                         
                             $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$row->id_descripcion,
                                          2=>$row->color.
                                            '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                          3=>$row->cantidad_um.' '.$row->medida,
                                          4=>$row->ancho.' cm',
                                          5=> //
                                          '<a style="padding: 1px 0px 1px 0px;" href="'.base_url().'detalle_salidas/'.base64_encode($row->mov_salida_unico).'/'.base64_encode( ($row->cliente=='') ? '0': $row->cliente   ).'/'.base64_encode($row->cargador."r*").'/'.base64_encode($row->id_operacion_salida).'/'.base64_encode("reportes").'/'.base64_encode($row->id_estatus).'" 
                                          type="button" class="btn btn-success btn-block">'.$row->movi_salida.'</a>',
                                          6=>$columna7,//id_lote
                                          7=>$row->metros, // 9
                                          8=>$row->kilogramos,  //10
                                          9=>$row->color_devolucion, //11
                                          10=>$row->proceso_traspaso, //15
                                          11=>$row->codigo_contable, //16
                                          12=>$row->id_estatus,
                                          13=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  )? $row->precio : '-'),
                                        );                    
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )), 
                      ));
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::total_campos_salida_home($where_total)->pieza ), "metro"=>floatval( self::total_campos_salida_home($where_total)->metros ), "kilogramo"=>floatval( self::total_campos_salida_home($where_total)->kilogramos )), 
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  

      public function total_campos_salida_home($where) {

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); 
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); 
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
             
              $this->db->from($this->historico_registros_salidas.' as m');
              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  



public function detalle_salida_home($data){

          $estatus= $data['extra_search'];
         
          $id_session = $this->db->escape($this->session->userdata('id'));

          
          $this->db->select('m.id, m.mov_salida, m.mov_salida_unico, m.factura, m.id_descripcion,  m.num_partida'); //m.id_empresa, m.id_operacion,
          $this->db->select('m.id_tipo_pedido,m.id_tipo_factura, m.fecha_salida');
          $this->db->select('m.ancho,  m.codigo');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color , p.nombre');
            
          $this->db->select('p.nombre cliente,ca.nombre cargador');  
          if ($estatus=="apartado") {
              $this->db->select('pr.nombre as dependencia'); 
          }
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion"); 
          $this->db->select("prod.codigo_contable");  

          $this->db->select('a.almacen, prod.imagen, m.fecha_salida');

          //$this->db->select('us.nombre as nom_cliente');  
          $this->db->select("( CASE WHEN m.on_off = 1 THEN t.nombre ELSE us.nombre END ) AS nom_cliente");          
         
          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia'); 
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          $this->db->join($this->proveedores.' As us' , 'us.id = m.consecutivo_venta','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente'); 
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador');                  


          if ($estatus=="apartado") {
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado'); 
              $this->db->join($this->proveedores.' As pr', 'us.id_cliente = pr.id'); 
          }    
             

          $where = '( m.codigo =  "'.$data["identificador"].'" ) ' ;                              

          
          
          $this->db->where($where); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                  foreach ($result->result() as $row) {
                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }

                            if ($tip_apart)



                          if ($estatus=="apartado") {

                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nom_cliente; //nombre;
                          }  


                             $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$columna6,//proveedor o dependencia
                                          2=>date( 'd-m-Y', strtotime($row->fecha_salida)),
                                          3=>$row->num_partida,
                                          4=>$row->almacen,
                                          5=>$imagen,
                                        );      

                
                      }

                      return json_encode ( array(
                        
                        
                        "data"            =>  $dato, 
                        
                      ));
              }   
              else {
                  $output = array(
                        "aaData" => array(),
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }         


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function buscador_entrada_devolucion($data){
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $factura_reporte = $data['factura_reporte'];
          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 

          switch ($columa_order) {
                   /*case '0':
                        $columna = 'm.codigo';
                     break;*/
                   case '1':
                        $columna = 'm.id_descripcion';
                     break;
                   case '2':
                        $columna = 'c.color';
                     break;
                   case '3':
                        $columna = 'm.cantidad_um';
                     break;
                   case '4':
                        $columna = 'm.ancho';
                     break;
                   case '5':
                        $columna = 'm.movimiento_unico';
                     break;
                   /*case '6':
                              $columna= 'p.nombre';
                     break;*/
                   case '6':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                     /*
                   case '8':
                        $columna = 'm.fecha_entrada';
                     break;

                   case '12': //'9':
                        $columna = 'm.factura';
                     break;

                   case '13':
                        $columna = 'm.num_partida';
                     break;
                   case '14':
                        $columna = 'm.id_almacen';
                     break;                       

                   */
                   default:
                       $columna = 'm.id';
                       $order = 'DESC';                       
                     break;
                 }                 
           

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }
          $donde = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  
                      $donde .= ' AND ( m.id_empresa  =  '.$id_empre.' ) ';
            } else 
            {
               $donde .= ' ';
            }
         if ($factura_reporte!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_reporte.'" ) ';
         } 
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_session = $this->db->escape($this->session->userdata('id'));


          $this->db->select("SQL_CALC_FOUND_ROWS(m.id)");  
          $this->db->select('m.id, m.movimiento,m.movimiento_unico,m.factura, m.id_fac_orig, m.id_descripcion,  m.num_partida,m.id_estatus,m.id_operacion');
          $this->db->select('m.ancho, m.codigo'); 
          $this->db->select('m.id_lote, m.consecutivo,  m.fecha_mac fecha, m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color,  m.devolucion');
          $this->db->select('m.cantidad_um, u.medida, m.precio,m.c234');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");    
          $this->db->select("prod.codigo_contable,m.nombre_usuario, m.id_compra");  

          $this->db->select("( CASE WHEN m.nombre_usuario <> '' THEN t.nombre ELSE p.nombre END ) AS nombre");
          $this->db->select('tipfac.tipo_factura, m.id_almacen');

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia');           
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 

           $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_empresa','LEFT');
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 


          $cond= ' (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") ';//' OR (m.consecutivo LIKE  "%'.$cadena.'%") ';

          if ($estatus=="devolucion") {
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
               $estatus_idid = ' AND ( m.id_estatus = "13" ) ' ;   
          }  else {

              if ($id_estatus!=0) {
                $estatus_idid = ' and ( m.id_estatus =  '.$id_estatus.' ) ';  
              } else {
                $estatus_idid = '';
              }

          }  
          if ($id_almacen!=0) {
          $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
          $id_almacenid = '';
          }
          if ($id_factura!=0) {
          $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
          $id_facturaid = '';
          }
          $where = '(
                      (
                         ( m.estatus_salida = "0" ) '.$estatus_idid.$id_almacenid.$id_facturaid.' 
                      ) 
                       AND
                      (  ( m.num_partida LIKE  "%'.$cadena.'%" ) OR 
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        (m.factura LIKE  "%'.$cadena.'%") OR ( m.movimiento_unico LIKE  "%'.$cadena.'%" ) OR ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y") ) LIKE  "%'.$cadena.'%") OR '.$cond.' 
                       )

            ) ' ;                     
          $where_total = '( ( m.estatus_salida = "0" )  '.$estatus_idid.$id_almacenid.$id_facturaid.'  )';
          if ($estatus=="devolucion") {
              $where .= ' AND ( m.id_estatus = "13" ) ' ;   
              $where_total .= ' AND ( m.id_estatus = "13" ) ' ;   
          }    
          if ($estatus!="existencia") {
              $where .= ' AND ( m.id_apartado = 0 ) ' ;   
              $where_total .= ' AND ( m.id_apartado = 0 ) ' ;   
          } 


          if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
             $where.= (($where!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
             $where_total.= (($where_total!="") ? " and " : "") . "( m.id_calidad  =  ".$id_calidad." )";
          }     

          if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
              $where.= (($where!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_composicion  =  ".$id_composicion." ) ";
          } 


          if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
             $where.= (($where!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
             $where_total.= (($where_total!="") ?  " and " : "") . "( m.id_color  =  ".$id_color." )";
          }

          //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
          if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
              $where.= (($where!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
              $where_total.= (($where_total!="") ? " and " : "") . "( m.id_descripcion  =  '".$id_descripcion."' )";
          }                                    

          $where_total.= $donde.$fechas; 
          $this->db->where($where.$donde.$fechas); //
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);
                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {

                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  

                            
                             $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$row->id_descripcion,
                                          2=> $row->color.
                                            '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                          3=>$row->cantidad_um.' '.$row->medida,
                                          4=>$row->ancho.' cm',
                                          5=>
                                               '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode((($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))).$row->movimiento_unico).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'/'.base64_encode($row->id_estatus).'"
                                                   type="button" class="btn btn-success btn-block">'.'['.(($row->id_operacion==72) ? 'B' : (($row->id_operacion==71) ? 'C' : (($row->devolucion<>0) ? 'D' :  (($row->id_operacion==70) ? 'T' : (($row->id_operacion==73) ? 'A' :'E') ) ))).'] '.$row->id_almacen.'-'.$row->tipo_factura.'-'.$row->c234.'</a>', 
                                          6=>$columna7,//id_lote
                                          7=>$row->metros, // 9
                                          8=>$row->kilogramos,  //10
                                          9=>$row->color_devolucion, //11
                                          10=>$row->proceso_traspaso, //15
                                          11=>$row->codigo_contable, //16
                                          12=>$row->id_estatus,
                                          13=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  )? $row->precio : '-'),
                                        );             

                              


                                            
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "totales"            =>  array("pieza"=>intval( self::total_registros_entrada_devolucion($where_total)->pieza ), "metro"=>floatval( self::total_registros_entrada_devolucion($where_total)->metros ), "kilogramo"=>floatval( self::total_registros_entrada_devolucion($where_total)->kilogramos )), 

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  "totales"            =>  array("pieza"=>intval( self::total_registros_entrada_devolucion($where_total)->pieza ), "metro"=>floatval( self::total_registros_entrada_devolucion($where_total)->metros ), "kilogramo"=>floatval( self::total_registros_entrada_devolucion($where_total)->kilogramos )), 
                  );
                  $array[]="";
                  return json_encode($output);
              }
              $result->free_result();           
      }  

      public function total_registros_entrada_devolucion($where){
              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros"); 
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos"); 
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
            

              $this->db->from($this->historico_registros_entradas.' as m');
              $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
              $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia');           
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 

              $this->db->where($where);
             $result = $this->db->get();
              if ( $result->num_rows() > 0 ) {
                 return $result->row();    
              }
            
              else
                 return False;
              $result->free_result();              
       }       

  
 public function detalle_entrada_devolucion($data){

          $estatus= $data['extra_search'];

          $this->db->select('m.id, m.movimiento,m.movimiento_unico,m.factura, m.id_fac_orig, m.id_descripcion,  m.num_partida,m.id_estatus');
          $this->db->select('m.ancho, m.codigo'); 
          $this->db->select('m.id_lote, m.consecutivo,  m.fecha_mac fecha, m.fecha_entrada,fecha_apartado,m.proceso_traspaso');
          $this->db->select('c.hexadecimal_color, c.color, m.devolucion');
          $this->db->select('m.cantidad_um, u.medida');
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"

                           ELSE "No Apartado"
                        END AS apartado
            ',False);
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          $this->db->select("( CASE WHEN  (m.devolucion <> 0)  THEN 'red'  
                                    WHEN  (m.id_apartado <> 0)  THEN 'morado' 
                                    ELSE 'black' END )
                             AS color_devolucion");    
          $this->db->select("prod.codigo_contable");  

          $this->db->select("prod.imagen, a.almacen");  

           $this->db->select("( CASE WHEN m.nombre_usuario <> '' THEN t.nombre ELSE p.nombre END ) AS nombre");

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia');           
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color'); 
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida'); 
          //$this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 

          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_empresa','LEFT');

          $where = '( m.codigo =  "'.$data["identificador"].'" ) ' ;

          $this->db->where($where); //


            $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                   $retorno= "reportes";   
                  foreach ($result->result() as $row) {

                     if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }

                          if ($estatus=="apartado") {
                              $fecha= $row->fecha_apartado;
                              if (($row->id_apartado) >=4) {
                                $tip_apart= " (Tienda)";
                              } else {
                                $tip_apart= " (Vendedor)";
                              }  
                              $columna6= $row->dependencia.$tip_apart;
                              $columna7= 
                              '<div style="background-color:#'.$row->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';
                          }  else {
                              $fecha= $row->fecha_entrada;
                              $columna7=$row->id_lote.'-'.$row->consecutivo; 
                              $columna6= $row->nombre;
                          }  

                                        $dato[]= array(
                                          0=>$row->codigo,
                                          1=>$columna6,//proveedor o dependencia
                                          2=>date( 'd-m-Y', strtotime($fecha)),
                                          3=>$row->num_partida,
                                          4=>$row->almacen,
                                          5=>$imagen,
                                        );      
                
                      }

                      return json_encode ( array(
                        
                        "data"            =>  $dato, 
                        

                      ));
                    
              }  else {
                  $output = array(
                  
                      "aaData" => array(),
                  );
                  
                  return json_encode($output);
              }
              $result->free_result();           


 }  
    


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function buscador_cero_baja($data){
        $id_empresa= addslashes($data['proveedor']);
           $id_empresaid = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $id_empresaid .= ' and ( m.id_empresa  =  '.$id_empre.' )  ';

            } else 
            {
               $id_empresaid .= ' ';
            }          
          $id_empresaid .= '';
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_medida = $data['id_medida'];
          
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $columa_order = $data['order'][0]['column'];
          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

          switch ($columa_order) {
                   /*case '0':
                        $columna = 'p.referencia';
                     break;
                     */
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; 
                     break;
                   
                   case '3':
                        $columna = 'c.color';
                     break;
                   
                   case '4':
                              $columna= 'co.composicion';
                     break;
                   case '5':
                              $columna= 'ca.calidad';
                     break;
                   case '6':
                        $columna = 'p.precio';
                     break;
                   
                   /*case '14':
                        $columna = 'm.id_almacen';
                     break;                       
                     */
                   default:
                       $columna = 'suma'; 
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.referencia)"); 

          //$this->db->select("0 as suma",FALSE);
          $this->db->select("sum(m.cantidad_um*(m.id_medida=".$id_medida.") ) as suma",FALSE);
          //$this->db->select("COUNT(m.referencia) as 'suma'");
          

          $this->db->select('p.referencia, m.proceso_traspaso, p.descripcion, p.minimo,p.minimo_kg,   p.precio,c.hexadecimal_color,c.color nombre_color , co.composicion, ca.calidad, m.id_estatus'); 
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
         
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   

          
          $this->db->select("p.codigo_contable");  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); 
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); 
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); 
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid,'LEFT'); 

          if ($estatus=="cero") {
            $activo  = ' and ( p.activo =  0 ) ';  
          } else {
            $activo ='';
          }

          

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR

                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'

            ) ' ; 
            //CONCAT("Optimo:",p.minimo_kg) LIKE  "%'.$cadena.'%")  OR
             $where_cond="";        

             //ojo AQUI EL CERO
            if  (($id_medida!="0") AND ($id_medida!="") AND ($id_medida!= null)) {
               $where.= (($where!="") ? " and " : "") . "( m.id_medida  =  ".$id_medida." )";
               $where_cond.= (($where_cond!="") ? " and " : "") . "( m.id_medida  =  ".$id_medida." )";
            }     


            if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
               $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
               $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
            }     
            if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
            } 
            if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
               $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
               $where_cond.= (($where_cond!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
            }
            //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
            }

          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          if ($id_medida==1) { //metro
            $this->db->group_by("p.referencia, p.minimo,  p.precio");   
          } else { //kilogramos
            $this->db->group_by("p.referencia, p.minimo_kg,  p.precio"); 
          }
          
          

         if ($estatus=="cero") {
               $this->db->having('suma  IS  NULL');
               $where_total = 'suma  IS  NULL';
          
          }   

          if ($estatus=="baja") {
                  if ($id_medida==1) { //metro
                        $this->db->having('((suma>0) AND (suma < p.minimo))');
                        $where_total = '((suma>0) AND (suma < p.minimo))';
                  } else { //kilogramos
                        $this->db->having('((suma>0) AND (suma < p.minimo_kg))');
                        $where_total = '((suma>0) AND (suma < p.minimo_kg))';
                  }
  



          }             
          $this->db->limit($largo,$inicio); 
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }

                              $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>'Optimo:'. ($id_medida==1 ? $row->minimo : $row->minimo_kg).'<br/>  Reales:'. $row->suma, //
                                      3=> $row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,  //6
                                      5=>$row->calidad,   //7
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? $row->precio : '-'),

                                      7=>$row->metros,   //9
                                      8=>$row->kilogramos, //10
                                      9=>"black",  //11
                                      10=>$row->proceso_traspaso, //15
                                      11=>$row->codigo_contable,  //16
                                      12=>$row->id_estatus,
                                      13=>"precio",
                                    );                    
                            

                          
                      }
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
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


  public function buscador_cero($data){
        $id_empresa= addslashes($data['proveedor']);
           $id_empresaid = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $id_empresaid .= ' and ( m.id_empresa  =  '.$id_empre.' )  ';

            } else 
            {
               $id_empresaid .= ' ';
            }          
          $id_empresaid .= '';
          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_medida = $data['id_medida'];
          
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $columa_order = $data['order'][0]['column'];
          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

          switch ($columa_order) {
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; 
                     break;
                   
                   case '3':
                        $columna = 'c.color';
                     break;
                   
                   case '4':
                              $columna= 'co.composicion';
                     break;
                   case '5':
                              $columna= 'ca.calidad';
                     break;
                   case '6':
                        $columna = 'p.precio';
                     break;
                   
                   default:
                       $columna = 'suma'; 
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.referencia)"); 
          $this->db->select("sum(m.cantidad_um*(m.id_medida=".$id_medida.") ) as suma",FALSE);
          $this->db->select('p.referencia, m.proceso_traspaso, p.descripcion, p.minimo,p.minimo_kg,   p.precio,c.hexadecimal_color,c.color nombre_color , co.composicion, ca.calidad, m.id_estatus'); 
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
         
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = ' ';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   

          
          $this->db->select("p.codigo_contable");  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); 
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); 
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); 
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12  and m.id_medida='.$id_medida.$id_almacenid.$id_facturaid.$id_empresaid,'LEFT'); 

          
          $activo  = ' and ( p.activo =  0 ) ';  

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR (CONCAT("Optimo:",p.minimo) LIKE  "%'.$cadena.'%")  OR

                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'

            ) ' ; 
            
             $where_cond="";        

             //ojo AQUI EL CERO
            
            /*    
            if  (($id_medida!="0") AND ($id_medida!="") AND ($id_medida!= null)) {
               $where.= (($where!="") ? " and " : "") . "( m.id_medida  =  ".$id_medida." )";
               $where_cond.= (($where_cond!="") ? " and " : "") . "( m.id_medida  =  ".$id_medida." )";
            }     
            */

            if  (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null)) {
               $where.= (($where!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
               $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_calidad  =  ".$id_calidad." )";
            }     
            if (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null)) {
                $where.= (($where!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
                $where_cond.= (($where_cond!="") ? " and " : "") . "( p.id_composicion  =  ".$id_composicion." ) ";
            } 
            if  (($id_color!="0") AND ($id_color!="") AND ($id_color!= null)) {
               $where.= (($where!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
               $where_cond.= (($where_cond!="") ?  " and " : "") . "( p.id_color  =  ".$id_color." )";
            }
            //if (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null))  {                
            if ( ($data['val_prod_id'] !="")  && ($data['val_prod_id'] !="0") ) {
                $where.= (($where!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
                $where_cond.= (($where_cond!="") ? " and " : "") . "( p.descripcion  =  '".$id_descripcion."' )";
            }

          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          
          if ($id_medida==1) { //metro
            $this->db->group_by("p.referencia, p.minimo,  p.precio");   
          } else { //kilogramos
            $this->db->group_by("p.referencia, p.minimo_kg,  p.precio"); 
          }
          
          $this->db->having('suma  IS  NULL');
    

          
          $this->db->limit($largo,$inicio); 
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }

                              $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>'Optimo:'. ($id_medida==1 ? $row->minimo : $row->minimo_kg).'<br/>  Reales:'. $row->suma, //
                                      3=> $row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,  //6
                                      5=>$row->calidad,   //7
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? $row->precio : '-'),
                                      7=>$row->metros,   //9
                                      8=>$row->kilogramos, //10
                                      9=>"black",  //11
                                      10=>$row->proceso_traspaso, //15
                                      11=>$row->codigo_contable,  //16
                                      12=>$row->id_estatus,
                                      13=>"precio",
                                    );                    
                            

                          
                      }
                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados, 
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
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




 public function detalle_cero_baja($data){
        $id_empresa= addslashes($data['proveedor']);
           $id_empresaid = '';
             if ($id_empresa!="") {
                  $id_empre =  self::check_existente_proveedor_entrada($id_empresa);

                    if (!($id_empre)) {
                      $id_empre =0;
                    }                  

                      $id_empresaid .= ' and ( m.id_empresa  =  '.$id_empre.' )  ';

            } else 
            {
               $id_empresaid .= ' ';
            }          
          $id_empresaid .= '';
          
          $estatus= $data['extra_search'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          //$estatus= $data['extra_search'];
          
          
          $id_session = $this->db->escape($this->session->userdata('id'));

          
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select('p.referencia, m.proceso_traspaso, p.descripcion, p.minimo,   p.precio,c.hexadecimal_color,c.color nombre_color , co.composicion, ca.calidad'); 
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 

          
         
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   
          $this->db->select("p.codigo_contable");  
          $this->db->select("p.imagen,a.almacen,p.comentario"); 

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); 
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); 
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); 
          $this->db->join($this->registros.' As m', 'm.referencia= p.referencia and m.id_estatus=12 '.$id_almacenid.$id_facturaid.$id_empresaid); 
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');

        

          $where = '( m.referencia =  "'.$data["identificador"].'" ) ' ;

          $this->db->where($where);
          
          $this->db->group_by("p.referencia, p.minimo,  p.precio"); 

         if ($estatus=="cero") {
              $this->db->having('suma <= 0');
              $where_total = 'suma <= 0';
          }   
          if ($estatus=="baja") {
              $this->db->having('((suma>0) AND (suma < p.minimo))');
              $where_total = '((suma>0) AND (suma < p.minimo))';
          }             
          
          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

          
                  foreach ($result->result() as $row) {
                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }



                             $dato[]= array(
                                          0=>$row->referencia,
                                          1=>$row->almacen,  
                                          2=>$imagen,
                                          3=>$row->comentario,
                                          
                                        );   


                             
                       
                      }
                      return json_encode ( array(
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
                  $output = array(
                  "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
      }  



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function buscador_top($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $columa_order = $data['order'][0]['column'];
          $order = $data['order'][0]['dir'];
          $id_medida = $data['id_medida'];
           
           if ($data['draw'] ==0) {  
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

          switch ($columa_order) {
                   /*
                   case '0':
                        $columna = 'p.referencia';
                     break;
                     */
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'suma'; 
                     break;
                   
                   case '3':
                        $columna = 'c.color';
                     break;
                   
                   case '4':
                              $columna= 'co.composicion';
                     break;
                   case '5':
                              $columna= 'ca.calidad';
                     break;
                   case '6':
                        $columna = 'p.precio';
                     break;
                   /*
                    case '14':
                        $columna = 'm.id_almacen';
                     break;                       
                     */

                   default:
                       $columna = 'suma'; 
                       $order = 'DESC';

                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.referencia)");  
          $this->db->select('p.referencia, m.proceso_traspaso,m.id_estatus'); 
          $this->db->select('p.descripcion, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion");   
          $this->db->select("ca.calidad");   
          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                          
          } else {
           $fechas .= ' ';
          }
          
          //$this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("sum(m.cantidad_um*(m.id_medida=".$id_medida.") ) as suma",FALSE);

          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   
          $this->db->select("p.codigo_contable");  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); 
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); 
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); 
          $this->db->join($this->historico_registros_salidas.' As m', 'p.referencia = m.referencia'.$fechas.''.$id_almacenid.$id_facturaid,'LEFT'); 
          //$this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen','LEFT'); 


          if ($estatus=="cero") {
            $activo  = ' and ( p.activo =  0 ) ';  
          } else {
            $activo ='';
          }
          
          $where = '(
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR (p.comentario LIKE  "%'.$cadena.'%")  OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" )  OR 
                        ( p.precio LIKE  "%'.$cadena.'%" ) 
                       )'.$activo.'
            ) ' ; 


           //ojo AQUI EL CERO
            if  (($id_medida!="0") AND ($id_medida!="") AND ($id_medida!= null)) {
               $where.= (($where!="") ? " and " : "") . "( m.id_medida  =  ".$id_medida." )";
               
            }     

          $this->db->where($where);
          $this->db->order_by($columna, $order); 
          
          
          if ($id_medida==1) { //metro
            $this->db->group_by("p.referencia, p.minimo,  p.precio");   
          } else { //kilogramos
            $this->db->group_by("p.referencia, p.minimo_kg,  p.precio"); 
          }


          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  foreach ($result->result() as $row) {
                          $nombre_fichero ='uploads/productos/thumbnail/516X516/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));

                          if (file_exists($nombre_fichero)) {
                              $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';
                              $imagen='<a type="button" href="'.base_url().$nombre_fichero.'" border="0" width="75" height="75" target="_blank" class="button">Ver Imagen</a>';
                          } else {
                              $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              $imagen='<a type="button" href="img/sinimagen.png" border="0" width="75" height="75" target="_blank" class="button">Ver Imagen</a>';
                          }

                                $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>$row->suma, //
                                      3=> $row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,  //6
                                      5=>$row->calidad,   //7
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  )? $row->precio : '-'),
                                      7=>$row->metros,   //9
                                      8=>$row->kilogramos, //10
                                      9=>"black",  //11
                                      10=>$row->proceso_traspaso, //15
                                      11=>$row->codigo_contable,  //16
                                      12=>$row->id_estatus,
                                      13=>"precio",
                                    );                    


                        
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
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



  public function detalle_top($data){

          
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(p.referencia)");  
          $this->db->select('p.referencia, m.proceso_traspaso'); 
          $this->db->select('p.descripcion, p.imagen, p.precio');
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion");   
          $this->db->select("ca.calidad");   
          $this->db->select("a.almacen, p.comentario, p.imagen");   
          //$this->db->select("p.imagen,a.almacen,p.comentario"); 
          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 


                          
          } else {
           $fechas .= ' ';
          }
          $this->db->select("COUNT(m.referencia) as 'suma'");
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros"); 
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos"); 
          if ($id_almacen!=0) {
            $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
            $id_almacenid = '';
          }   

          if ($id_factura!=0) {
            $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
            $id_facturaid = '';
          }   
          $this->db->select("p.codigo_contable");  

          $this->db->from($this->productos.' as p');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id'); 
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id'); 
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id'); 
          $this->db->join($this->historico_registros_salidas.' As m', 'p.referencia = m.referencia'.$fechas.''.$id_almacenid.$id_facturaid); 
          $this->db->join($this->almacenes.' As a', 'a.id = m.id_almacen'); 

          
          $where = '( m.referencia =  "'.$data["identificador"].'" ) ' ;
          $this->db->where($where);
          $this->db->group_by("p.referencia, p.minimo,  p.precio"); //p.imagen,
          

          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                  foreach ($result->result() as $row) {
                         
                            if  (isset($row->imagen)) {
                              $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                              if (file_exists($nombre_fichero)) {
                                  $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                              } else {
                                  $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                              }
                            } else {
                                $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                            }



                             $dato[]= array(
                                          0=>$row->referencia,
                                          1=>$row->almacen,  
                                          2=>$imagen,
                                          3=>$row->comentario,
                                          
                                        );   
                  

                      }

                      return json_encode ( array(
                        "data"            =>  $dato, 
                      ));
                    
              }   
              else {
                  $output = array(
                    "aaData" => array()
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
              

      }      


////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

 public function buscador_historico_entradas($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];
          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 
          switch ($columa_order) {
                   case '0':
                        $columna = 'm.movimiento_unico';
                     break;
                   case '1':
                        $columna = 'tp.tipo_pago';
                     break;
                   case '2':
                        $columna = 'a.almacen';
                     break;
                   case '3':
                        $columna = 'p.nombre';
                     break;
                   case '4':
                        $columna = 'm.fecha_entrada';
                     break;
                   case '5':
                        $columna = 'm.factura';
                     break;
                   case '6':
                        $columna = 'sum_precio';
                     break;
                   case '7':
                        $columna = 'sum_iva';
                     break;
                   case '8':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.movimiento_unico';
                     break;
                 }                 


   

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.movimiento_unico)"); 
          $this->db->select('m.movimiento, m.movimiento_unico, m.id_operacion'); 
          $this->db->select('a.almacen,m.id_factura, m.id_estatus');
          $this->db->select('m.factura,tp.tipo_pago');
          $this->db->select("MAX(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false); 
          $this->db->select("MAX(m.devolucion) devolucion"); 
          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva"); 
          $this->db->select("sum(m.precio*m.cantidad_um)+(sum(m.precio*m.cantidad_um*m.iva)/100) as sum_total"); 
          $this->db->select("m.nombre_usuario,m.id_compra, m.c234"); 

          $this->db->select("( CASE WHEN m.nombre_usuario <> '' THEN t.nombre ELSE p.nombre END ) AS nombre");
          $this->db->select('tipfac.tipo_factura, m.id_almacen');

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT'); 
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.id_empresa','LEFT');
  
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 
          

          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');

          if ($id_almacen!=0) {
             $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }
         

          if ($id_factura!=0) {
             $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }        

          if ($data['id_estatus']!=0) {
             
             $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
          } else {
             $id_estatusid = '';
          }               

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }           

           $id_operacion = ' (( m.id_operacion =  1 ) OR ( m.id_operacion =  70 )  OR ( m.id_operacion =  71 ) OR ( m.id_operacion =  72 ) OR ( m.id_operacion =  73 ) ) ';

          $where = '(
                      (
                          '.$id_operacion.'   AND ( m.devolucion = 0 )'.$fechas.$id_almacenid.$id_facturaid.$id_estatusid.' 
                      ) 

                       AND
                      (  ( m.movimiento_unico LIKE  "%'.$cadena.'%" )OR 
                        ( tp.tipo_pago LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   

          $where_total= $where;
          $this->db->where($where);          

          $this->db->group_by('m.movimiento_unico,m.id_factura,m.id_almacen,m.id_empresa,m.factura'); //,m.id_estatus
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
                                      0=>$row->movimiento_unico, //movimiento,
                                      1=>$row->tipo_pago,
                                      2=>$row->almacen,
                                      3=>$row->nombre,
                                      4=>$row->fecha,
                                      5=>$row->factura,
                                      6=>
                                      ( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_precio, 2, '.', ',') : '-'),

                                      7=>
                                      ( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_iva, 2, '.', ',') : '-'),
                                      
                                      8=>
                                      ( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format(($row->sum_precio+$row->sum_iva), 2, '.', ',') : '-'),
                                      

                                      9=>$row->devolucion,
                                      10=>$row->id_factura,
                                      11=>$row->id_estatus,
                                      //12=>($row->id_factura==3) ? 'B-' : (($row->id_compra!=0) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->nombre_usuario!='') ? 'T-' :'E-') )),
                                      12=>(($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))),
                                      13=>$row->c234,
                                      14=>$row->id_almacen,
                                      15=>$row->tipo_factura
                                    );
                      }


                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>$registros_filtrados, 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes($where_total)->total ),
                            ),                           
                      ));
                    
              }   
              else {
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


    
public function totales_importes($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal", FALSE);
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva", FALSE);
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total", FALSE);
   
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');          
          $this->db->join($this->catalogo_tipos_pagos.' As tp' , 'tp.id = m.id_tipo_pago','LEFT');

          $this->db->where($where);


          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }        
       
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function buscador_historico_devolucion($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.movimiento_unico'; //movimiento
                     break;
                   case '1':
                        $columna = 'a.almacen';
                     break;
                   case '2':
                        $columna = 'p.nombre';
                     break;
                   case '3':
                        $columna = 'm.fecha_entrada';
                     break;
                   case '4':
                        $columna = 'm.factura';
                     break;
                   case '5':
                        $columna = 'sum_precio';
                     break;
                   case '6':
                        $columna = 'sum_iva';
                     break;
                   case '7':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.movimiento_unico';
                     break;
                 }                 


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.movimiento_unico)"); 
          $this->db->select('m.movimiento,m.movimiento_unico,p.nombre, m.factura, m.devolucion,m.id_estatus,m.id_operacion, m.c234');
          $this->db->select("(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);
          $this->db->select("( CASE WHEN m.devolucion <> 0 THEN 'red' ELSE 'black' END ) AS color_devolucion"); 
          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva"); 
          $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total"); 
          $this->db->select('a.almacen,m.id_factura,m.nombre_usuario,m.id_compra');
          $this->db->select('tipfac.tipo_factura, m.id_almacen');

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa'); 
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 
          

          if ($id_almacen!=0) {
             $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }
         

          if ($id_factura!=0) {
             $id_facturaid = ' and ( m.id_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }         

          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_entrada),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }     



          $where = '(
                      (
                         ( m.id_operacion = '.$data["id_operacion"].' )    AND ( m.devolucion <> 0 )'.$fechas.$id_almacenid.$id_facturaid.' 
                      ) 

                       AND
                      (  ( m.movimiento_unico LIKE  "%'.$cadena.'%" )OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_entrada),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (m.factura LIKE  "%'.$cadena.'%")                         
                       )



            )';   



          $where_total= $where;            

          $this->db->where($where);          
          $this->db->group_by('m.movimiento_unico,m.id_almacen,m.factura'); //m.id_empresa,(de lo contrario saldran 2 movimientos)
          $this->db->order_by($columna, $order); 
          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);

                  $retorno= " ";  
                  foreach ($result->result() as $row) {
                               $dato[]= array(
                                      0=>$row->movimiento_unico, //movimiento,
                                      1=>$row->almacen,
                                      2=>$row->nombre,
                                      3=>$row->fecha,
                                      4=>$row->factura,
                                      5=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_precio, 2, '.', ',') : '-'),
                                      6=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_iva, 2, '.', ',') : '-'),
                                      7=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_total, 2, '.', ',') : '-'),
                                      8=>$row->devolucion,
                                      9=>$row->id_factura,
                                      10=>$row->id_estatus,
                                      11=>(($row->id_operacion==72) ? 'B-' : (($row->id_operacion==71) ? 'C-' : (($row->devolucion<>0) ? 'D-' :  (($row->id_operacion==70) ? 'T-' : (($row->id_operacion==73) ? 'A-' :'E-') ) ))),

                                      12=>$row->id_almacen,
                                      13=>$row->tipo_factura                                      

                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => $registros_filtrados,  
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes_devolucion($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes_devolucion($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes_devolucion($where_total)->total ),
                        ),                              

                      ));
                    
              }   
              else {
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
       

   
public function totales_importes_devolucion($where){

           $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal"); 
           $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva"); 
           $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total"); 
   
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');

          $this->db->where($where);

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              

    }              


      




public function buscador_historico_salida($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];          

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

           if ($data['draw'] ==1) { //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'desc';
           } 



          switch ($columa_order) {
                   case '0':
                        $columna = 'm.mov_salida';
                     break;
                   case '1':
                        $columna = 'a.almacen';
                     break;
                   case '2':
                        $columna = 'p.nombre'; 
                     break;
                   case '3':
                        $columna = 'ca.nombre'; 
                     break;                     
                     
                   case '4':
                        $columna = 'm.fecha_salida';
                     break;
                   case '5':
                        $columna = 'tf.tipo_factura,tp.tipo_pedido';
                     break;

                   case '6':
                        $columna = 'm.factura';
                     break;
                   case '7':
                        $columna = 'sum_precio';
                     break;
                   case '8':
                        $columna = 'sum_iva';
                     break;
                   case '9':
                        $columna = 'sum_total';
                     break;


                   default:
                        $columna = 'm.mov_salida_unico';
                        
                     break;
                 }                 

                                      

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS(m.mov_salida_unico)"); 

          $this->db->select('m.mov_salida_unico, ca.nombre cargador, m.factura, m.movimiento_unico_apartado');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);    
          $this->db->select("(DATE_FORMAT(m.fecha_salida,'%d-%m-%Y %H:%i')) as fecha",false);
          
          $this->db->select('a.almacen');
           $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura, m.id_estatus");  

          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva"); 
          $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total"); 

          $this->db->select('m.id_apartado apartado, m.consecutivo_venta,m.id_cliente_apartado');  

          //$this->db->select("prov_pedido.nombre cliente_pedido");
          //$this->db->select("( CASE WHEN m.on_off = 1 THEN t.nombre ELSE prov_pedido.nombre END ) AS cliente_pedido");


            $this->db->select('
                          CASE m.on_off
                            WHEN 0 THEN  prov_pedido.nombre
                            WHEN 1 THEN  t.nombre
                            WHEN 2 THEN  al.almacen 
                             ELSE  prov_pedido.nombre
                          END AS cliente_pedido
           ',False);

          $this->db->select("prov_apartado.nombre cliente_apartado");

          $this->db->select("m.on_off");

          $this->db->select("m.id_operacion_salida");

             $this->db->select("
              CONCAT('[',
              ( CASE 
                WHEN (m.id_operacion_salida=2)  THEN 'S' 
                 WHEN (m.id_operacion_salida=95)  THEN 'B'  
                 WHEN (m.id_operacion_salida=93)  THEN 'A' 
                 WHEN (m.id_operacion_salida=99)  THEN 'J' 
                else 'T' 
              end),
              ']',m.id_almacen,'-',  
                (CASE 
                 WHEN (m.id_tipo_pedido=3)  THEN 'G' 
                 WHEN (m.id_tipo_pedido=2)  THEN 'S'  
                else tf.tipo_factura
              end)

              ,'-',m.cs234   
             )
              AS movi_salida",FALSE);



            $this->db->select("m.id_operacion_pedido");
            $this->db->select("
                CONCAT('[',
                ( CASE 
                  WHEN (m.id_operacion_pedido=4)  THEN 'S' 
                   WHEN (m.id_operacion_pedido=98)  THEN 'B'  
                   WHEN (m.id_operacion_pedido=96)  THEN 'A' 
                  else 'T' 
                end),
                ']',m.id_almacen,'-',  
                  (CASE 
                   WHEN (m.id_tipo_pedido=3)  THEN 'G' 
                   WHEN (m.id_tipo_pedido=2)  THEN 'S'  
                  else tf.tipo_factura
                end)

                ,'-',m.cp234   
               )
                AS movi_pedido",FALSE);

          
          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT'); 
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT'); 
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador');  
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT'); //
          $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta','LEFT'); //
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->almacenes.' As al' , 'al.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT'); //
   


          if ($data['identificador_vendedor']!='0') {
             $id_usuarioid = ' and ( u.id =  "'.$data['identificador_vendedor'].'" ) ';  
          } else {
              $id_usuarioid = '';
          }
                
 

          if ($id_almacen!=0) {
             $id_almacenid = ' and ( m.id_almacen =  '.$id_almacen.' ) ';  
          } else {
              $id_almacenid = '';
          }
         

          if ($id_factura!=0) {
             $id_factura = (($id_factura==3) ? 0 : $id_factura);
             $id_facturaid = ' and ( m.id_tipo_factura =  '.$id_factura.' ) ';  
          } else {
             $id_facturaid = '';
          }         



          if ($data['id_estatus']!=0) {
             $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
          } else {
             $id_estatusid = '';
          }         


          $fechas = ' ';
          if  ( ($data['fecha_inicial'] !="") and  ($data['fecha_final'] !="")) {
                           $fecha_inicial = date( 'Y-m-d', strtotime( $data['fecha_inicial'] ));
                           $fecha_final = date( 'Y-m-d', strtotime( $data['fecha_final'] ));
                          
                            $fechas .= ' AND ( ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  >=  "'.$fecha_inicial.'" )  AND  ( DATE_FORMAT((m.fecha_salida),"%Y-%m-%d")  <=  "'.$fecha_final.'" ) )'; 

          } else {
           $fechas .= ' ';
          }     



          
          if ($data['identificador']!=0) {
             
             $tienda_cliente = ' and ( m.consecutivo_venta =  '.$data['identificador'].' ) ';  
          } else {
             $tienda_cliente = '';
          }      

            //( m.on_off = '.$data["on_off"].' )

          $id_operacion = ' (( m.id_operacion_salida =  2 ) OR ( m.id_operacion_salida =  93 )  OR ( m.id_operacion_salida =  94 ) OR ( m.id_operacion_salida =  95 ) OR ( m.id_operacion_salida =  99 ) ) ';

          $where = '(
                     (
                        '.$id_operacion.'
                         '.$tienda_cliente.' 
                      

                          '.$fechas.$id_usuarioid.$id_almacenid.$id_facturaid.$id_estatusid.'  
                      ) 

                       AND
                      ( 
                        ( m.mov_salida_unico LIKE  "%'.$cadena.'%" ) OR 
                        ( a.almacen LIKE  "%'.$cadena.'%" ) OR (p.nombre LIKE  "%'.$cadena.'%") OR 
                        (ca.nombre LIKE  "%'.$cadena.'%") OR 
                        ((DATE_FORMAT((m.fecha_salida),"%d-%m-%Y %H:%i") ) LIKE  "%'.$cadena.'%") OR
                        (tf.tipo_factura LIKE  "%'.$cadena.'%") OR (tp.tipo_pedido LIKE  "%'.$cadena.'%") OR 
                        (m.factura LIKE  "%'.$cadena.'%") 
                       )
            )';   



          $where_total= $where;

          $this->db->where($where);          

          $this->db->group_by('m.mov_salida_unico,m.id_operacion_salida,m.id_almacen'); //,m.id_estatus ,m.id_cliente

          
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



                              if ($row->id_tipo_factura!=0) {
                                $tipo_salida=$row->tipo_factura;
                              } else {
                                $tipo_salida=$row->tipo_pedido;
                              }


                              if ($row->apartado==3) {
                                 $num=$row->consecutivo_venta;
                                 $client=$row->cliente_apartado;

                              } else  {
                                 $num= $row->id_cliente_apartado;
                                 $client=$row->cliente_pedido;
                              }   

                               $dato[]= array(
                                      0=>$row->mov_salida_unico,
                                      1=>$num,
                                      2=>$row->almacen,
                                      3=>$row->cliente,
                                      4=>$row->cargador,
                                      5=>$row->fecha,
                                      6=>$tipo_salida, 
                                      7=>$row->factura,
                                      8=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_precio, 2, '.', ',') : '-'),

                                      9=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_iva, 2, '.', ',') : '-'),
                                      10=>( ( ($this->session->userdata('id_perfil')==1) || ( (in_array(80, $data['coleccion_id_operaciones'])) || (in_array(81, $data['coleccion_id_operaciones'])) )  ) ? number_format($row->sum_total, 2, '.', ',') : '-'),
                                      11=>$row->id_tipo_pedido,
                                      12=>$row->id_tipo_factura,
                                      13=>$client,
                                      14=>$row->id_estatus,
                                      15=>$row->on_off,
                                      16=>$row->movimiento_unico_apartado,
                                      17=>$row->movi_salida,
                                      18=>$row->movi_pedido,
                                      19=>(($row->id_operacion_salida==2) ? 'S-' : (($row->id_operacion_salida==93) ? 'A-' :  ( ($row->id_operacion_salida == 94) ? 'T-' :  ( ($row->id_operacion_salida == 99) ? 'J-' :  'B-' ) ) )),
                                      20=>$row->id_operacion_salida,
                                      21=>$row->id_operacion_pedido,

                                    );
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>$registros_filtrados, 
                        "recordsFiltered" =>   $registros_filtrados, 
                        "data"            =>  $dato,
                        "totales_importe"            =>  array(
                              "subtotal"=>floatval( self::totales_importes_salida($where_total)->subtotal ), 
                              "iva"=>floatval( self::totales_importes_salida($where_total)->iva ), 
                              "total"=>floatval( self::totales_importes_salida($where_total)->total ),
                        ),                           
                      ));
                    
              }   
              else {
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
       


   
public function totales_importes_salida($where){
          $this->db->select("SUM(m.precio*m.cantidad_um) as subtotal"); 
          $this->db->select("(SUM(m.precio*m.cantidad_um*m.iva))/100 as iva"); 
          $this->db->select("SUM(m.precio*m.cantidad_um)+(SUM(m.precio*m.cantidad_um*m.iva))/100 as total"); 

          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado'); 
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente'); 
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador');  
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT'); //
          $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta');
          $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT'); //


          $this->db->where($where);
          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              
    }       


    
 







  } 
?>