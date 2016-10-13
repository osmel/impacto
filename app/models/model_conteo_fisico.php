<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');
  class model_conteo_fisico extends CI_Model {
    
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
      
      $this->registros_entradas               = $this->db->dbprefix('registros_entradas');
      $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
      $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
      $this->registros_cambios               = $this->db->dbprefix('registros_cambios');

      $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
      $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');
      $this->historico_registros_traspasos        = $this->db->dbprefix('historico_registros_traspasos');
      $this->historico_acceso        = $this->db->dbprefix('historico_acceso');

      $this->historico_pagos_realizados        = $this->db->dbprefix('historico_pagos_realizados');
      $this->historico_ctasxpagar        = $this->db->dbprefix('historico_ctasxpagar');

     
      $this->temporal_pedido_compra        = $this->db->dbprefix('temporal_pedido_compra');
      $this->historico_pedido_compra        = $this->db->dbprefix('historico_pedido_compra');
      $this->historico_cancela_pedido_compra      = $this->db->dbprefix('historico_cancela_pedido_compra');
      $this->historico_historial_compra      = $this->db->dbprefix('historico_historial_compra');

      $this->catalogo_operaciones      = $this->db->dbprefix('catalogo_operaciones');
      $this->almacenes      = $this->db->dbprefix('catalogo_almacenes');

      //proceso de conteo
      $this->conteo_almacen      = $this->db->dbprefix('conteo_almacen');
      $this->productos           = $this->db->dbprefix('catalogo_productos');      
      $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');


      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->composiciones     = $this->db->dbprefix('catalogo_composicion');
      $this->calidades                 = $this->db->dbprefix('catalogo_calidad'); 
      $this->colores                 = $this->db->dbprefix('catalogo_colores');
      $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
       $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
    }


     
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////    


 public function procesando_operacion( $data ){
          $consecutivo = self::consecutivo_operacion_entrada(1,$data['id_factura']); //cambio
          //self::reordenar_new_temporal(); //cambio
          //self::actualizando_consecutivo_productos($data['id_operacion']); //cambio

          $id_session = $this->session->userdata('id');
          $fecha_hoy = date('Y-m-d H:i:s');  
             
          //aqui lista todos los datos que fueron entrados por un usuario especifico   
          $this->db->select('id_empresa, factura, id_descripcion, id_color, id_composicion, id_calidad, referencia, num_partida,id_almacen,id_factura,id_fac_orig,iva, id_tipo_pago');
          $this->db->select('id_medida, cantidad_um, peso_real, cantidad_royo, ancho, precio, codigo, comentario, id_estatus, id_lote, consecutivo');
          $this->db->select('id_cargador, id_usuario, fecha_mac, id_operacion');
          $this->db->select('"'.$fecha_hoy.'" AS fecha_entrada',false);

          $this->db->select($consecutivo.' AS movimiento',false); //cambio

          $this->db->from($this->registros_temporales);

          $this->db->where('id_usuario',$id_session);
          $this->db->where('id_operacion',$data['id_operacion']);
          $this->db->where('id_almacen',$data['id_almacen']);

          $result = $this->db->get();

          $objeto = $result->result();
          //copiar a tabla "registros" e "historico_registros_entradas"
          foreach ($objeto as $key => $value) {
            $this->db->insert($this->historico_registros_entradas, $value); 
            $value->peso_real = 0; //para el futuro es necesario hacerlo 0
            $this->db->insert($this->registros, $value);
            $num_movimiento = $value->movimiento;
          }

          //aqui es donde voy a agregar "historico_ctasxpagar"

                  $this->db->select('"'.addslashes($num_movimiento).'" AS movimiento',false); 
                  $this->db->select('m.id_tipo_pago');
                  $this->db->select('m.id_almacen');
                  $this->db->select('m.id_empresa');
                  $this->db->select('m.fecha_entrada');
                  $this->db->select('m.factura');
                  $this->db->select('m.id_factura,m.id_fac_orig');
                  $this->db->select('m.fecha_mac, m.id_operacion,m.id_usuario');
                  $this->db->select('m.comentario');
                  
                  $this->db->select('sum(m.precio) as subtotal');           
                  $this->db->select("sum(m.precio*m.iva)/100 as iva", FALSE);
                  $this->db->select("sum(m.precio)+((sum(m.precio*m.iva))/100) as total", FALSE);
                 

                  $this->db->from($this->registros_temporales.' as m');

          
                  $where = '(
                                 (m.id_usuario = "'.$id_session.'" ) AND (m.id_almacen = '.$data['id_almacen'].' ) AND
                                 ( m.id_operacion = '.$data['id_operacion'].' )    
                           )';
                   

                  $this->db->where($where);          

                  $this->db->group_by('m.movimiento,m.id_almacen,m.id_empresa,m.factura');


                  $result_ctas_pagar = $this->db->get();

                  
                  $objeto_ctas_pagar = $result_ctas_pagar->result();
                  //copiar a tabla de "historico_ctasxpagar"  un resumen
                  foreach ($objeto_ctas_pagar as $key => $value) {
                    $this->db->insert($this->historico_ctasxpagar, $value); 
                    
                  }


          //fin de  agregar "historico_ctasxpagar"

          //actualizar (consecutivo) en tabla "operacion" 
          if ($data['id_factura']==1) {
              $this->db->set( 'conse_factura', 'conse_factura+1', FALSE  );  
          } else {
              $this->db->set( 'conse_remision', 'conse_remision+1', FALSE  );  
          }

          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',1);
          $this->db->update($this->operaciones);

          //eliminar los registros en "temporal_registros" del usuario 
          $this->db->delete($this->registros_temporales, array('id_usuario'=>$id_session,'id_operacion'=>$data['id_operacion'],'id_almacen'=>$data['id_almacen'])); 

          return $num_movimiento;

          $result->free_result();          

        }


      public function consecutivo_productos($referencia){

        $this->db->select('p.id, p.referencia, p.consecutivo');
        $this->db->from($this->productos .' as p');
        
        $this->db->where('referencia',$referencia);  
        

        $result = $this->db->get();

          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();
      } 

public function anadir_producto_temporal( $data ){
              $id_session = $this->session->userdata('id');
              $fecha_hoy2 = date('Y-m-d H:i:s');  
              
              $fecha_hoy= date ( 'Y-m-d H:i:s' , strtotime ( '+1 g' , strtotime ($fecha_hoy2) ) );
              $cant=0;

              $hoy = getdate();
              $fecha_formateada = ($hoy["seconds"]+$hoy["minutes"]+$hoy["hours"]+$hoy["mday"]+$hoy["mon"]+$hoy["year"]);
              $id_cliente_asociado = $this->session->userdata('id_cliente_asociado');


              $this->db->select('CONCAT('.$id_cliente_asociado.',SUBSTRING(calm.referencia, 9 ),"001",'.$fecha_formateada.') as codigo', false);


              $this->db->select('"'.$id_session.'" as id_usuario', false);
              $this->db->select('"'.$fecha_hoy.'" as fecha_entrada', false);
              $this->db->select('"F-ajuste" as factura', false);
              $this->db->select('"P-ajuste" as num_partida', false);
              $this->db->select('"Ajuste por sobrante" as comentario', false);
              
              $this->db->select('"0" as iva', false);
              
              $this->db->select('"0" as peso_real', false);
              $this->db->select('"0" as cantidad_um', false);
              $this->db->select('"0" as ancho', false);
              $this->db->select('"0" as precio', false);
              
              $this->db->select('"1" as id_operacion', false); //operacion 1 es entrada
              $this->db->select('"1" as id_medida', false); //mts
              $this->db->select('"12" as id_estatus', false); //normal
              $this->db->select('"001" as id_lote', false); //lote 001

              $this->db->select('"'.$data['id_empresa'].'" as id_empresa', false);
              $this->db->select('"'.$data['movimiento'].'" as movimiento', false);
              $this->db->select('"'.$data['id_almacen'].'" as id_almacen', false);
              $this->db->select('"'.$data['id_factura'].'" as id_factura', false);
              $this->db->select('"'.$data['id_factura'].'" as id_fac_orig', false);
              $this->db->select('"'.$data['id_tipo_pago'].'" as id_tipo_pago', false);

              $this->db->select('calm.descripcion as id_descripcion', false);
              $this->db->select('calm.id_color as id_color', false);
              $this->db->select('calm.id_composicion as id_composicion', false);
              $this->db->select('calm.id_calidad as id_calidad', false);

              $this->db->select('calm.referencia as referencia', false);
              $this->db->select('calm.conteo3-calm.cantidad_royo as cantidad_royo', false);

              $this->db->select('calm.consecutivo as consecutivo', false);

              
              //$this->db->set( 'codigo', $data['codigo'].'_'.$i   );
              //$this->db->set( 'consecutivo', $i);           //data['consecutivo']



              $this->db->from($this->conteo_almacen.' As calm');


              $where = '(
                          (calm.sobrante=0) and (calm.num_conteo>=3) AND (calm.cantidad_royo<calm.conteo3) AND (calm.id_almacen =  '.$data["id_almacen"].' )
                        )';


              $this->db->where($where);
              $this->db->order_by('calm.referencia', 'desc');                         

              $result = $this->db->get();

              //return $result->result();

            $objeto = $result->result();
            
            //$data['productos'] = $this->model_conteo_fisico->anadir_producto_temporal($data);
             
            
               
            foreach ($objeto as $key => $value) {
                   $cant = self::consecutivo_productos($value->referencia)->consecutivo;

                    //actualizar el consecutivo de cada referencia
                    $this->db->set( 'consecutivo',($value->cantidad_royo+$cant), FALSE  );
                    $this->db->set( 'id_usuario', $id_session );
                    $this->db->where('referencia',$value->referencia);
                    $this->db->update($this->productos);


                  for ($i=(1+$cant); $i <= ($value->cantidad_royo+$cant) ; $i++) {         
                      $cod_temp=$value->codigo;
                      $value->codigo=$value->codigo.'_'.$i;
                      $value->consecutivo=$i;
                      $this->db->insert($this->registros_temporales, $value); 
                      $value->codigo=$cod_temp;
                  }
          
                  
              }


                //indicar en la tabla que ya se hizo el sobrante
                $this->db->set( 'sobrante', 1  );  
                $this->db->where('id_almacen',$data['id_almacen']);                
                $this->db->update($this->conteo_almacen);

          if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();

}

 

         public function actualizar_peso_real( $data ){
            foreach ($data['pesos'] as $key => $value) {

                if(!is_numeric($value['peso_real'])) {  //caso cuando el peso viene vacio
                  $value['peso_real'] = 0;
                  
                } 
                
                $this->db->set( 'cantidad_um', $data['cantidad_um'][$key]['cantidad_um'], FALSE  );
                $this->db->set( 'ancho', $data['ancho'][$key]['ancho'], FALSE  );
                $this->db->set( 'precio', $data['precio'][$key]['precio'], FALSE  );
                $this->db->set( 'peso_real', $value['peso_real'], FALSE  );

                
                $this->db->where('id',$value['id']);                
                $this->db->update($this->registros_temporales);
              }
            return TRUE;       
         }
  
/*


    public function actualizar_cantidad_aprobado( $data ){
            $id_session = ($this->session->userdata('id'));

            foreach ($data['cant_aprobada'] as $key => $value) {
                if(!is_numeric($value['cantidad'])) {  //caso cuando el peso viene vacio
                  $value['cantidad'] = 0;                  
                } 
                $this->db->set( 'comentario', '"'.addslashes($data['comentario']).'"', FALSE  );

                $this->db->set( 'cantidad_aprobada', $value['cantidad'], FALSE  );
                $this->db->set( 'cantidad_pedida', $data['cant_solicitada'][$key]['cantidad'], FALSE  );
                $this->db->where('id_producto',$value['id']);                
                $this->db->where('movimiento',$data['movimiento']);                
                $this->db->update($this->historico_pedido_compra);
              }

          $this->db->select("sum(cantidad_aprobada<>cantidad_pedida) as desigual", FALSE);          
          $this->db->select("sum(cantidad_aprobada) as suma", FALSE);
          $this->db->from($this->historico_pedido_compra.' as p');
          $this->db->where('movimiento',$data['movimiento']);                

          $result = $this->db->get();
      
          if ( $result->num_rows() > 0 )
             return (($result->row()->suma>0) && ($result->row()->desigual==0));
          else
             return False;
          $result->free_result();              

            return TRUE;       
      }*/


 public function buscador_productos_temporales($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
           $largo = $data['length'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

      
          switch ($columa_order) {
                   case '1':
                        $columna = 'm.codigo';
                     break;
                   case '2':
                        $columna = 'm.id_descripcion';
                     break;
                   case '3':
                        $columna = 'c.hexadecimal_color';
                     break;
                   case '4':
                        $columna = 'm.cantidad_um, u.medida';
                     break;
                   case '5':
                        $columna = 'm.ancho';
                     break;
                   case '6':
                        $columna = 'm.peso_real';
                     break;

                   case '7':
                          $columna = 'p.nombre';
                     break;
                   case '8':
                        $columna = 'm.id_lote, m.consecutivo';
                     break;                     

                   case '9':
                        $columna = 'm.num_partida';
                     break;                     


                   case '10':
                        $columna = 'm.precio';
                     break;      


                   case '11':
                   case '12':
                        $columna = 'm.precio, m.iva';
                     break;      

                   default:
                         $columna = 'm.id_lote, m.id_descripcion';
                     break;
                 }                 


                      
          
          $fecha_hoy =  date("Y-m-d h:ia"); 
          $hoy = new DateTime($fecha_hoy);

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

                    
          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');
          $this->db->select('c.hexadecimal_color, u.medida,p.nombre');
          $this->db->select('m.peso_real');
          $this->db->select('m.precio, m.iva');

           $this->db->select("((m.precio*m.iva))/100 as sum_iva", FALSE);
           $this->db->select("(m.precio)+((m.precio*m.iva))/100 as sum_total", FALSE);



          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          $this->db->select("prod.codigo_contable");  


          $this->db->from($this->registros_temporales.' as m');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
        
        
          //filtro de busqueda
          //( m.id_usuario = '.$id_session.' ) or ( m.id_operacion = 1 ) 
          $where = '(
                      (
                        ( m.id_usuario = '.$id_session.' )
                      ) 
                      AND
                      (    
                          (m.codigo LIKE  "%'.$cadena.'%") OR ( m.id_descripcion LIKE  "%'.$cadena.'%" ) OR                    
                          (CONCAT(m.id_lote," - ",m.consecutivo) LIKE  "%'.$cadena.'%" ) OR 
                          (m.ancho LIKE  "%'.$cadena.'%") 
                       )   

            )';   

        // OR ( p.nombre  "%'.$cadena.'%" ) 
 


          $where_total = '( m.id_usuario = '.$id_session.' )  '; //or ( m.id_operacion = 1 ) 

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


                  foreach ($result->result() as $row) {
                            $dato[]= array(
                                      0=>$row->id,
                                      1=>$row->codigo,
                                      2=>$row->id_descripcion,
                                      3=>'<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->cantidad_um, //.' '.$row->medida,
                                      5=>$row->ancho, //.' cm', 
                                      6=>$row->nombre,
                                      7=>$row->id_lote.' - '.$row->consecutivo, 
                                      8=>$row->id_lote.' - '.$row->consecutivo, 
                                      9=>$row->num_partida,
                                      10=>$row->metros,
                                      11=>$row->kilogramos,  
                                      12=>$row->peso_real,  
                                      13=>$row->precio, 
                                      14=>$row->iva, 
                                      15=>$row->sum_iva, 
                                      16=>$row->sum_total, 
                                      17=>$row->codigo_contable, 

                                                                          
                                    );
                   }

                      

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    =>intval( self::total_productos_temporales($where_total) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato,

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0, //intval( self::total_productos_temporales($where_total) ),  
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                  );
                  $array[]="";
                  return json_encode($output);
                  

              }

              $result->free_result();           
      }  


   public function total_productos_temporales($where){

              $this->db->from($this->registros_temporales.' as m');
              $this->db->where($where);

              $result = $this->db->get();
              $cant = $result->num_rows();
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         

       }    


        public function existencia_temporales_peso_real($data){

              $id_session = $this->session->userdata('id');
              $cant=0;

               /* 
              $this->db->where('id_almacen',$data['id_almacen']);
              $this->db->where('id_usuario',$id_session);
              $this->db->where('id_operacion',1);
              $this->db->where('peso_real',0);  //no tiene peso real
              */

              $where = '(
                         ( (id_almacen='.$data['id_almacen'].') and (id_usuario="'.$id_session.'") AND (id_operacion=1) ) AND
                         ( (cantidad_um=0) OR (ancho=0) OR (precio=0) OR (peso_real=0)   )
                      )';

              $this->db->where($where); 

              $this->db->from($this->registros_temporales);

              $cant = $this->db->count_all_results();          

              if ( $cant > 0 )
                 return false;
              else
                 return true;              

        }     


       public function consecutivo_operacion_entrada( $id,$id_factura ){
              $this->db->select("o.consecutivo,o.conse_factura,o.conse_remision,o.conse_surtido");         
              $this->db->from($this->operaciones.' As o');
              $this->db->where('o.id',$id);
              $result = $this->db->get( );
                  if ($result->num_rows() > 0) {
                        $consecutivo_actual = ( ($id_factura==1) ? $result->row()->conse_factura : $result->row()->conse_remision );
                        return $consecutivo_actual+1;
                  }                    
                  else 
                      return FALSE;
                  $result->free_result();
       }  


             



      public function buscador_entrada($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];

           $id_tipo_factura = $data['id_tipo_factura'];
           $id_tipo_pedido = $data['id_tipo_pedido'];

                $producto_filtro = addslashes($data['producto_filtro']); 
                $color_filtro = $data['color_filtro']; 
                //$ancho_filtro = number_format((float)$data['ancho_filtro'],2,'.','');  
                //$ancho_filtro = (float)$data['ancho_filtro'];  
                $ancho_filtro   = $data['ancho_filtro'];  
                $factura_filtro = addslashes($data['factura_filtro']);           
                $proveedor_filtro = addslashes($data['proveedor_filtro']);    


          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];

          switch ($columa_order) {
                   case '0':
                        $columna = 'm.codigo';
                     break;
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
                        $columna = 'm.movimiento';
                     break;
                   case '6':
                              $columna= 'p.nombre';
                     break;
                   case '7':
                              $columna= 'm.id_lote, m.consecutivo';  
                     break;
                   
                   default:
                       $columna = 'm.codigo';
                     break;
                 }                 
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id, m.movimiento,m.id_empresa, m.factura, m.id_factura,m.id_fac_orig, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario, m.fecha_mac fecha');

          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre, m.id_apartado');
         
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          

          $this->db->from($this->registros_entradas.' as m');
          $this->db->join($this->conteo_almacen.' As calm' , 'calm.referencia = m.referencia');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
         


          $id_almacenid = ' AND (m.id_almacen =  '.$data["id_almacen"].' )' ;  
          
          

          if  ( ($data["modulo"]==5) )  {
              $filtro = ' AND (
                                
                                 (calm.num_conteo>=3) AND (calm.cantidad_royo>calm.conteo3)
                        )';
          } else  if  ( ($data["modulo"]==6) )  {
              $filtro = ' AND (
                                
                                 (calm.num_conteo>=3) AND (calm.cantidad_royo<calm.conteo3)
                        )';
          } else {
            $filtro ='';
          } 



        $donde1 = '';
        $donde = '';
        if ($producto_filtro!="") {
            $donde .= ' AND ( m.id_descripcion  =  "'.$producto_filtro.'" ) ';
        } 

        if ($color_filtro!="") {
            $donde .= ' AND ( m.id_color  =  '.$color_filtro.' ) ';
        } 
                

        if ($ancho_filtro!=0) {
            //$donde .= ' AND ( CAST(m.ancho AS DECIMAL)   =  CAST('.$ancho_filtro.' AS DECIMAL) ) ';
            $donde .= ' AND ( ROUND(m.ancho, 3)   =  ROUND('.$ancho_filtro.' ,3) ) ';
        } 
                

        if ($factura_filtro!="") {
            $donde .= ' AND ( m.factura  =  "'.$factura_filtro.'" ) ';
        } 
                
        if ($proveedor_filtro!="") {
            $donde .= ' AND ( p.nombre  =  "'.$proveedor_filtro.'" ) ';
        } 
                           

         //este no hace falta en pedido porq no se filtra
          if ($id_tipo_factura!=0) {
              $id_tipo_facturaid = ' AND ( m.id_factura =  '.$id_tipo_factura.' )  AND ( m.id_tipo_pedido  <>2  ) ';  
              //$id_tipo_facturaid = '';
          } else {
              //$id_tipo_facturaid = '';
              $id_tipo_facturaid = ' AND (( m.id_tipo_pedido  =0  ) OR ( m.id_tipo_pedido  =2  ) )';  
          } 

        
          $where = '(
                      (
                        (
                          ( ( us.id_cliente = '.$data['id_cliente'].' )  AND  ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.proceso_traspaso = 0 ) AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )  '.$donde.'

                      )'.$id_tipo_facturaid.$id_almacenid.$filtro.'
                       AND

                      (
                        ( m.codigo LIKE  "%'.$cadena.'%" ) OR (m.id_descripcion LIKE  "%'.$cadena.'%") OR (c.color LIKE  "%'.$cadena.'%")  OR
                        ( CONCAT(m.cantidad_um," ",u.medida) LIKE  "%'.$cadena.'%" ) OR (CONCAT(m.ancho," cm") LIKE  "%'.$cadena.'%")  OR
                        ( m.movimiento LIKE  "%'.$cadena.'%" ) OR  
                        (p.nombre LIKE  "%'.$cadena.'%") OR  (CONCAT(m.id_lote,"-",m.consecutivo) LIKE  "%'.$cadena.'%") '.
                        $donde1
                       .')


            )';   

          $where_total = '(
                        (
                          ( ( us.id_cliente = '.$data['id_cliente'].' )  AND  ( (m.id_apartado = 3)  or ( m.id_apartado = 6 ) ) ) OR
                          (( m.id_apartado = 0 ) AND ( m.id_operacion = "1" ) )
                        )  AND ( m.estatus_salida = "0" ) AND (m.id_almacen = '.$data['id_almacen'].' )
                        '.$id_tipo_facturaid.$id_almacenid.$filtro.'
                       )';
          $this->db->where($where);

          //ordenacion
          $this->db->order_by('m.id_apartado', 'desc'); 
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
                                      0=>$row->codigo.' - '.$row->codigo1,
                                      1=>$row->id_descripcion,
                                      2=>$row->color.
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      3=>$row->cantidad_um.' '.$row->medida,
                                      4=>$row->ancho.' cm',
                                      5=>
                                           '<a style="  padding: 1px 0px 1px 0px;" href="'.base_url().'procesar_entradas/'.base64_encode($row->movimiento).'/'.base64_encode($row->devolucion).'/'.base64_encode($retorno).'/'.base64_encode($row->id_fac_orig).'"
                                               type="button" class="btn btn-success btn-block">'.$row->movimiento.'</a>', 
                                      6=>$row->nombre,
                                      7=>$row->id_lote.'-'.$row->consecutivo,
                                      8=>$row->id,
                                      9=>$row->id_apartado,
                                      10=>$row->num_partida,
                                      11=>$row->metros,
                                      12=>$row->kilogramos,
                                    );
                      }



                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_entrada_home($where_total) ), 
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

  public function total_entrada_home($where){
              $id_session = $this->session->userdata('id');
          
              $this->db->from($this->registros_entradas.' as m');
              $this->db->join($this->conteo_almacen.' As calm' , 'calm.referencia = m.referencia');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');

              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;         
       }     



  public function total_campos_salida_home($where) {

              $this->db->select("SUM((id_medida =1) * cantidad_um) as metros", FALSE);
              $this->db->select("SUM((id_medida =2) * cantidad_um) as kilogramos", FALSE);
              $this->db->select("COUNT(m.id_medida) as 'pieza'");
              
             
              $this->db->from($this->registros_entradas.' as m');
              $this->db->join($this->conteo_almacen.' As calm' , 'calm.referencia = m.referencia');
              $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
              $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
              $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
              $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');


              $this->db->where($where);

             $result = $this->db->get();
          
              if ( $result->num_rows() > 0 )
                 return $result->row();
              else
                 return False;
              $result->free_result();              

       }  


public function buscador_ajustes($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $fecha_hoy = date('Y-m-d H:i:s');  
          $id_almacen= $data['id_almacen'];
          $id_session = $this->session->userdata('id');


          $columa_order = $data['order'][0]['column'];

          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

      
          switch ($columa_order) {
                   case '0':
                        $columna = 'p.referencia';
                     break;
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'p.imagen'; 
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
                   default:
                       $columna = 'p.referencia';
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          
          $this->db->select("p.id,p.consecutivo, p.codigo_contable,p.grupo,p.referencia");    
          $this->db->select('p.imagen');
          $this->db->select('p.descripcion');
          $this->db->select('p.id_composicion,p.id_color,p.id_calidad, p.cantidad_royo');
          $this->db->select("p.id_almacen, p.fecha_creacion, p.id_usuario");
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("p.conteo1,p.conteo2,p.conteo3,p.num_conteo");  
         
          
          $id_almacenid = ' AND (p.id_almacen =  '.$id_almacen.' )' ;  
          
          $this->db->from($this->conteo_almacen.' as p');
          $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

          if  ( ($data["modulo"]==5) )  {
              $filtro = ' AND (
                                
                                 (p.num_conteo>=3) AND (p.cantidad_royo>p.conteo3)
                        )';
          } else  if  ( ($data["modulo"]==6) )  {
              $filtro = ' AND (
                                
                                 (p.num_conteo>=3) AND (p.cantidad_royo<p.conteo3)
                        )';
          } else {
            $filtro ='';
          }          

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR 
                        (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" ) 
                       )'.$id_almacenid.$filtro.'

            ) ' ; 



          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion,p.id_composicion,p.id_color,p.id_calidad");

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>$imagen.$row->conteo3,
                                      3=>$row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,
                                      5=>$row->calidad,
                                      6=>$row->cantidad_royo,
                                      7=>$row->conteo1,
                                      8=>$row->conteo2,
                                      9=>$row->conteo3,
                                      10=>$row->id,
                                      11=>$row->num_conteo,
                                      12=>abs($row->cantidad_royo-$row->conteo3),
                                    );                    

                            $num_conteo = $row->num_conteo;
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_ajustes($where) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "generales"            =>  array(
                                                      "modulo_activo"=>intval($num_conteo)+2
                                                    ),  

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                   "generales"            =>  array(
                                                      "modulo_activo"=>0
                                                    ),  
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
              

      }  


/*
UPDATE  `inven_conteo_almacen` SET  `num_conteo` =0,
`conteo1` =0,
`conteo2` =0,
`conteo3` =0

*/

        public function total_ajustes($where){
              $this->db->from($this->conteo_almacen.' as p');
              $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;     
       }

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////proceso de conteo//////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////    


                /*if ($data['modulo'] == 2) {
                  $this->db->set( 'conteo1', $value['cantidad'], FALSE  );  
                } else if ($data['modulo'] == 3) {
                   $this->db->set( 'conteo2', $value['cantidad'], FALSE  );  
                } else {
                   $this->db->set( 'conteo3', $value['cantidad'], FALSE  );  
                }  */


    public function actualizar_cantidad( $data ){
            $id_session = ($this->session->userdata('id'));

            foreach ($data['cantidad'] as $key => $value) {
                if(!is_numeric($value['cantidad'])) {  //caso cuando el peso viene vacio
                  $value['cantidad'] = 0;                  
                } 

                $this->db->set( 'conteo'.($data["modulo"]-1), $value["cantidad"], FALSE  );  
                $this->db->where('id',$value['id']);                
                $this->db->where('id_almacen',$data['id_almacen']);                
                $this->db->update($this->conteo_almacen);
              }

              //cantidad_royo
          $this->db->select("sum(cantidad_royo<>conteo".($data['modulo']-1).") as desigual", FALSE);          
          $this->db->select("sum(conteo".($data['modulo']-1).") as suma", FALSE);
          $this->db->from($this->conteo_almacen.' as p');
          $this->db->where('id_almacen',$data['id_almacen']);                

          $result = $this->db->get();
          /*
          if ( $result->num_rows() > 0 )
             return (($result->row()->suma>0) && ($result->row()->desigual==0));
          else
             return False;
          $result->free_result();              
          */

            return TRUE;       
    }
    public function actualizar_conteos( $data ){

          //UPDATE `inven_conteo_almacen` SET `num_conteo`=0
          //$this->db->set( 'num_conteo', ($data["modulo"]-1), FALSE  );  

          if  ($data['modulo']!=4) {
          $this->db->set( "conteo".($data['modulo']), "(cantidad_royo=conteo".($data['modulo']-1).")*conteo".($data['modulo']-1), FALSE  );
          }

          if  ($data['modulo']==2) {
              $this->db->set( "conteo".($data['modulo']+1), "(cantidad_royo=conteo".($data['modulo']-1).")*conteo".($data['modulo']-1), FALSE  );
          }

          $this->db->set( 'num_conteo', 'num_conteo+1', FALSE  );
          $this->db->where('id_almacen',$data['id_almacen']);                
          $this->db->update($this->conteo_almacen);

    }  

   public function consecutivo_operacion( $id ){
              
            $this->db->select("o.consecutivo");         
            $this->db->from($this->operaciones.' As o');
            $this->db->where('o.id',$id);
            $result = $this->db->get( );
                if ($result->num_rows() > 0)
                    return $result->row()->consecutivo+1;
                else 
                    return FALSE;
                $result->free_result();
     }  



    public function creando_conteo($data){
         $fecha_hoy = date('Y-m-d H:i:s');  

          $id_almacen= $data['id_almacen'];
          $id_descripcion= addslashes($data['id_descripcion']);
          $id_color= $data['id_color'];
          $id_composicion= $data['id_composicion'];
          $id_calidad= $data['id_calidad'];
          $id_session = $this->session->userdata('id');

          $consecutivo = self::consecutivo_operacion(50); //cambio

          /*
              codigo_contable, grupo, referencia, imagen, descripcion, id_composicion, id_color, id_calidad, id_usuario, fecha_mac, comentario, cantidad_royo, conteo1, conteo2, conteo3, estatus_conteo
          */
          
          $this->db->select('"'.$consecutivo.'" AS consecutivo',false);
          $this->db->select("p.codigo_contable,p.grupo,p.referencia");    
          $this->db->select('p.imagen');
          $this->db->select('p.descripcion');
          $this->db->select('p.id_composicion,p.id_color,p.id_calidad');
          
          //id_usuario, cantidad_royo,id_almacen
          $this->db->select("COUNT(m.referencia) as 'cantidad_royo'"); //cantidad_royo
          $this->db->select("m.id_almacen");
         
          $this->db->select('"'.$id_session.'" as id_usuario', false);
          $this->db->select('"'.$fecha_hoy.'" AS fecha_creacion',false);
          

          
          $id_almacenid = ' AND (m.id_almacen =  '.$id_almacen.' )' ;  
          
          $this->db->from($this->productos.' as p');
          $this->db->join($this->registros_entradas.' As m', 'm.referencia= p.referencia'.$id_almacenid,'LEFT');
        
          $activo  = ' and ( p.activo =  0 ) '; 
          $where = '( 
                        (m.id_almacen =  '.$id_almacen.' )'.$activo.'
                     ) ' ; 


         $where_cond ='';

         if ( (($id_calidad!="0") AND ($id_calidad!="") AND ($id_calidad!= null))
            and (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {

              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) AND  ( p.id_calidad  =  '.$id_calidad.' )';
          }    
          elseif
           ( 
               (($id_composicion!="0") AND ($id_composicion!="") AND ($id_composicion!= null))
            and (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond .= ' AND ( p.id_composicion  =  '.$id_composicion.' ) ';
          }  

          elseif 
           ( (($id_color!="0") AND ($id_color!="") AND ($id_color!= null))
            and (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) 
            ) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
              $where_cond = '( p.descripcion  =  "'.$id_descripcion.'" ) AND  ( p.id_color  =  '.$id_color.' )';
          }  

          elseif  (($id_descripcion!="0") AND ($id_descripcion!="") AND ($id_descripcion!= null)) {
              $where .= ' AND ( p.descripcion  =  "'.$id_descripcion.'" )';
              $where_cond  = '( p.descripcion  =  "'.$id_descripcion.'" )';
          }            
        
    
          

          $this->db->where($where);

          //$this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion,p.id_composicion,p.id_color,p.id_calidad");
          
          $this->db->having('(cantidad_royo>0)');
          $where_total = '(cantidad_royo>0)';          
 
         



/*         $registros = $this->db->get();  


          if ($registros->num_rows() > 0) {
              return $registros->result(); 
          }    
          else
              return false;
          $registros->free_result();

          
*/

          $result = $this->db->get();


          $objeto = $result->result();

          //copiar a tabla "registros"
          foreach ($objeto as $key => $value) {
              $this->db->insert($this->conteo_almacen, $value); 
          }



          //actualizar (consecutivo) en tabla "operacion" 
          
          $this->db->set( 'consecutivo', 'consecutivo+1', FALSE  );
          $this->db->set( 'id_usuario', $id_session );
          $this->db->where('id',50);
          $this->db->update($this->operaciones);


          return true;

      }  



      


public function buscador_costos($data){

          $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          

          $fecha_hoy = date('Y-m-d H:i:s');  
          $id_almacen= $data['id_almacen'];
          $id_session = $this->session->userdata('id');


          $columa_order = $data['order'][0]['column'];

          $order = $data['order'][0]['dir'];

           if ($data['draw'] ==0) {  //que se ordene por el ultimo
                 $columa_order ='-1';
                 $order = 'DESC';
           } 

      
          switch ($columa_order) {
                   case '0':
                        $columna = 'p.referencia';
                     break;
                   case '1':
                        $columna = 'p.descripcion';
                     break;
                   case '2':
                        $columna = 'p.imagen'; 
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
                   default:
                       $columna = 'p.referencia';
                       $order = 'DESC';                       
                     break;
                 }           


          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); 

          
          $this->db->select("p.id,p.consecutivo, p.codigo_contable,p.grupo,p.referencia");    
          $this->db->select('p.imagen');
          $this->db->select('p.descripcion');
          $this->db->select('p.id_composicion,p.id_color,p.id_calidad, p.cantidad_royo');
          $this->db->select("p.id_almacen, p.fecha_creacion, p.id_usuario");
          $this->db->select('c.hexadecimal_color,c.color nombre_color');
          $this->db->select("co.composicion", FALSE);  
          $this->db->select("ca.calidad", FALSE);  
          $this->db->select("p.conteo1,p.conteo2,p.conteo3,p.num_conteo");  
         
          
          $id_almacenid = ' AND (p.id_almacen =  '.$id_almacen.' )' ;  
          
          $this->db->from($this->conteo_almacen.' as p');
          $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
          $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
          $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
          $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

          if  ( ($data["modulo"]==3) || ($data["modulo"]==4) )  {
              $filtro = ' AND (
                        (
                        (
                        ( (conteo'.(intval($data['modulo'])-1).'<> p.cantidad_royo)  OR (conteo'.(intval($data['modulo'])-1).'<> conteo'.(intval($data['modulo'])-2).')  )
                        ) AND (num_conteo<>0)
                        )

                         OR 
                        (num_conteo=0)
                        )';
          } else {
            $filtro ='';
          }

          $where = '(
                      
                      (
                        ( p.referencia LIKE  "%'.$cadena.'%" ) OR 
                        (p.descripcion LIKE  "%'.$cadena.'%") OR 
                        (c.color LIKE  "%'.$cadena.'%") OR
                        (co.composicion LIKE  "%'.$cadena.'%")  OR
                        ( ca.calidad LIKE  "%'.$cadena.'%" ) 
                       )'.$id_almacenid.$filtro.'

            ) ' ; 



          $this->db->where($where);

          $this->db->order_by($columna, $order); 

          $this->db->group_by("p.referencia,p.descripcion,p.id_composicion,p.id_color,p.id_calidad");

          $this->db->limit($largo,$inicio); 


          $result = $this->db->get();

              if ( $result->num_rows() > 0 ) {

                    $cantidad_consulta = $this->db->query("SELECT FOUND_ROWS() as cantidad");
                    $found_rows = $cantidad_consulta->row(); 
                    $registros_filtrados =  ( (int) $found_rows->cantidad);


                  foreach ($result->result() as $row) {
                        $nombre_fichero ='uploads/productos/thumbnail/300X300/'.substr($row->imagen,0,strrpos($row->imagen,".")).'_thumb'.substr($row->imagen,strrpos($row->imagen,"."));
                        if (file_exists($nombre_fichero)) {
                            $imagen ='<img src="'.base_url().$nombre_fichero.'" border="0" width="75" height="75">';

                        } else {
                            $imagen ='<img src="img/sinimagen.png" border="0" width="75" height="75">';
                        }

                           $dato[]= array(
                                      0=>$row->referencia, 
                                      1=>$row->descripcion,
                                      2=>$imagen.$row->cantidad_royo,
                                      3=>$row->nombre_color.                                      
                                        '<div style="background-color:#'.$row->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>',
                                      4=>$row->composicion,
                                      5=>$row->calidad,
                                      6=>$row->cantidad_royo,
                                      7=>$row->conteo1,
                                      8=>$row->conteo2,
                                      9=>$row->conteo3,
                                      10=>$row->id,
                                      11=>$row->num_conteo,
                                      
                                      


                                    );                    

                            $num_conteo = $row->num_conteo;
                      }

                      return json_encode ( array(
                        "draw"            => intval( $data['draw'] ),
                        "recordsTotal"    => intval( self::total_conteo($where) ),  
                        "recordsFiltered" => $registros_filtrados, 
                        "data"            =>  $dato, 
                        "generales"            =>  array(
                                                      "modulo_activo"=>intval($num_conteo)+2
                                                    ),  

                      ));
                    
              }   
              else {
                  $output = array(
                  "draw" =>  intval( $data['draw'] ),
                  "recordsTotal" => 0,
                  "recordsFiltered" =>0,
                  "aaData" => array(),
                   "generales"            =>  array(
                                                      "modulo_activo"=>0
                                                    ),  
                  );
                  $array[]="";
                  return json_encode($output);
              }

              $result->free_result();   
              

      }  


/*
UPDATE  `inven_conteo_almacen` SET  `num_conteo` =0,
`conteo1` =0,
`conteo2` =0,
`conteo3` =0

*/

        public function total_conteo($where){
              $this->db->from($this->conteo_almacen.' as p');
              $this->db->join($this->almacenes.' As a', 'a.id = p.id_almacen','LEFT');
              $this->db->join($this->colores.' As c', 'p.id_color = c.id','LEFT');
              $this->db->join($this->composiciones.' As co', 'p.id_composicion = co.id','LEFT');
              $this->db->join($this->calidades.' As ca', 'p.id_calidad = ca.id','LEFT');

              $this->db->where($where);
              $cant = $this->db->count_all_results();          
     
              if ( $cant > 0 )
                 return $cant;
              else
                 return 0;     
       }
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////    


    //cuando se elimina un diseño en particular
    public function entradas($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_temporales.' As m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario','LEFT');
       $this->db->where('m.id_almacen',$id_almacen);
       $this->db->group_by("m.id_usuario");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }

    public function eliminar_prod_temporal( $data ){
            $this->db->delete( $this->registros_temporales, array( 'id_almacen' => $data['id_almacen'] ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
   }


 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

    public function pedidos($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario_apartado, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_entradas.' As m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( m.id_apartado <> 0 ) AND ( m.id_almacen ='.$id_almacen.')
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_usuario_apartado");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }



        
        //cancelar todos los pedidos y apartados de un almacen especifico
        public function cancelar_pedido_detalle( $data ){
                $id_almacen = $data['id_almacen'];

                $this->db->set( 'fecha_vencimiento', '' ); 
                $this->db->set( 'id_prorroga', 0);
                $this->db->set( 'fecha_apartado', '' );  
                $this->db->set( 'id_cliente_apartado', 0 );
                $this->db->set( 'id_apartado', 0);
                $this->db->set( 'id_usuario_apartado', '');
                $this->db->set( 'id_tipo_pedido', 0, false);
                $this->db->set( 'id_tipo_factura', 0, false);
                $this->db->set( 'consecutivo_venta', 0);

                $where = '(
                                      ( m.id_apartado <> 0 ) AND ( m.id_almacen ='.$id_almacen.')
                          )';


                $this->db->where($where);                

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;
       
        }   




 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////DEVOLUCIONES////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



   public function devoluciones($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_user_devolucion, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->historico_registros_salidas.' as m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_user_devolucion','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( m.devolucion = 1  )  AND ( m.id_almacen ='.$id_almacen.')
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_user_devolucion");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }


    //cancelar todas las devoluciones de un almacen especifico
  public function quitar_producto_devolucion( $data ){
              $id_almacen = $data['id_almacen'];

              $this->db->set( 'id_user_devolucion', '');
              $this->db->set( 'devolucion', 0);
              $this->db->set( 'cod_devolucion', '');
              $this->db->set( 'conse_devolucion', '');
              $this->db->set( 'peso_real_devolucion', 0);  //poner a cero el  peso_real_devolucion
              $this->db->set( 'consecutivo_cambio', '0',false);
              $this->db->set( 'comentario', '');
              
              $where = '(
                        ( m.devolucion = 1  )  AND ( m.id_almacen ='.$id_almacen.')
              )';

              $this->db->where($where);
              $this->db->update($this->historico_registros_salidas);


            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
        }  




 /////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////traspasos////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



   public function traspasos($data){

       $id_almacen = $data['id_almacen'];
       
       //$this->db->select('m.id_usuario_traspaso, m.id_almacen, a.almacen, us.nombre'); 
       $this->db->select('us.nombre'); 
       $this->db->from($this->registros_entradas.' as m');
       $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
       $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_traspaso','LEFT');
       
       //vendedores 1,2,3
       //pedidos internos 4,5,6
      $where = '(
                        ( ( incluir =  1 ) AND (proceso_traspaso = 1))  AND ( m.id_almacen ='.$id_almacen.') AND ( m.estatus_salida = "0" )
            )';

      $this->db->where($where);      

      $this->db->group_by("m.id_usuario_traspaso");

      $registros = $this->db->get();  
      if ($registros->num_rows() > 0) {
          return $registros->result(); 
      }    
      else
          return false;
      $registros->free_result();

    }


      public function quitar_productos_traspasado( $data ){
                $id_almacen = $data['id_almacen'];

                $porciento_aplicar = 16;                 
                $this->db->set( 'num_control', '');
                $this->db->set( 'comentario_traspaso', '');
                $this->db->set( 'id_usuario_traspaso', '');


                $this->db->set( 'iva', '((id_factura_original = 1)*'.$porciento_aplicar.')', false);
                $this->db->set( 'incluir', 0);
                $this->db->set( 'proceso_traspaso', 0);

                $this->db->set( 'id_factura', 'id_factura_original', false);
                $this->db->set( 'id_factura_original', 0, false);

                $where = '(
                                  ( ( incluir =  1 ) AND (proceso_traspaso = 1))  AND ( m.id_almacen ='.$id_almacen.') AND ( m.estatus_salida = "0" )
                )';

                $this->db->where($where);               

                $this->db->update($this->registros );

                if ($this->db->affected_rows() > 0) {
                  return TRUE;
                }  else
                   return FALSE;                

        }      






    
  } 


?>