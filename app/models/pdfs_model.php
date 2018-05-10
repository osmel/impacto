<?php 
class Pdfs_model extends CI_Model
{
    function __construct()
    {
        
        parent::__construct();
            $this->load->database("default");
            $this->key_hash    = $_SERVER['HASH_ENCRYPT'];
            $this->timezone    = 'UM1';

            $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
                $this->productos               = $this->db->dbprefix('catalogo_productos');
                  $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
                  $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');

                  $this->operaciones             = $this->db->dbprefix('catalogo_operaciones');
                  $this->movimientos               = $this->db->dbprefix('movimientos');
                  $this->colores                 = $this->db->dbprefix('catalogo_colores');
                  $this->composiciones                 = $this->db->dbprefix('catalogo_composicion');
                  $this->unidades_medidas        = $this->db->dbprefix('catalogo_unidades_medidas');
                  $this->registros               = $this->db->dbprefix('registros_entradas');
                  $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
                  $this->cargadores             = $this->db->dbprefix('catalogo_cargador');

                  

                  $this->almacenes       = $this->db->dbprefix('catalogo_almacenes');

                  $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
                  $this->historico_registros_salidas = $this->db->dbprefix('historico_registros_salidas');

                  $this->tipos_facturas                         = $this->db->dbprefix('catalogo_tipos_facturas');
                  $this->tipos_pedidos                         = $this->db->dbprefix('catalogo_tipos_pedidos');
                  $this->tipos_ventas                         = $this->db->dbprefix('catalogo_tipos_ventas');


                    //usuarios
                  $this->usuarios    = $this->db->dbprefix('usuarios');
                  
                  $this->catalogo_tiendas  = $this->db->dbprefix('catalogo_tiendas');

    }


    public function totales_entradas($data){


          $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
          $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
          $this->db->select("COUNT(m.id_medida) as 'pieza'");
          $this->db->select("SUM(m.peso_real) as 'peso_real'");

          $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
          $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
          $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);

          
          //$this->db->from($this->historico_registros_entradas.' as m');

          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); //AND a.activo=1
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');          
          $this->db->join($this->productos.' as prod', 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 



         $where='';
          
          
          

          $where.= (($where!='') ? ' and ' : '') . '  m.movimiento_unico = '.$data["id_movimiento"];
          
           $where.= (($where!='') ? ' and ' : '') .' m.devolucion = '.$data["dev"];

          if ($data['id_estatus']!=0) {
            // $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
            //$this->db->where('m.id_estatus',$data['id_estatus']);
             $where.= (($where!="") ? " and " : "") . ' m.id_estatus = '.$data["id_estatus"];
          } else {
             //$id_estatusid = '';
          }            

          if  ($data['dev'] == 0) { //si es una entrada porq la devolucion puede tener multiples
            //$this->db->where('m.id_factura',$data['id_factura']);
            $where.= (($where!="") ? " and " : "") . ' m.id_factura = '.$data["id_factura"];
          }  


          if ($data['tipo_entrada']=='E') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  1 ) and ( m.devolucion =  0) and ( m.id_compra =  0)  '; 
          } 

          if ($data['tipo_entrada']=='B') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  72 ) ';  //compra
          } 

          if ($data['tipo_entrada']=='T') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  70 ) ';  //compra
          } 


          if ($data['tipo_entrada']=='D') {
              $where.= (($where!="") ? " and " : "") . ' ( m.devolucion <>  0) ';  //devoluciones
          } 

          if ($data['tipo_entrada']=='C') {
              //$nomb_usuario = ' and ( m.id_compra <>  0) ';  //compra
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  71 ) ';  //compra
          } 

          if ($data['tipo_entrada']=='A') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  73 ) ';  
          } 
           
          

          $this->db->where('('.$where.')');          


         

          $result = $this->db->get();

          if ( $result->num_rows() > 0 )
             return $result->row();
          else
             return False;
          $result->free_result();              


        }     

      public function totales_salidas($data){
          
            $this->db->select("SUM((m.id_medida =1) * m.cantidad_um) as metros", FALSE);
            $this->db->select("SUM((m.id_medida =2) * m.cantidad_um) as kilogramos", FALSE);
            $this->db->select("COUNT(m.id_medida) as 'pieza'");
            $this->db->select("sum(m.peso_real) as 'peso_real'");
            $this->db->select('sum(m.precio*m.cantidad_um) as sum_precio');           
            $this->db->select("sum(m.precio*m.cantidad_um*m.iva)/100 as sum_iva", FALSE);
            $this->db->select("sum(m.precio*m.cantidad_um)+((sum(m.precio*m.cantidad_um*m.iva))/100) as sum_total", FALSE);

            $this->db->from($this->historico_registros_salidas.' as m');


            $this->db->where('m.mov_salida_unico',$data['num_mov']);
            $this->db->where('m.id_operacion_salida',$data['id_operacion_salida']);

            if (!(isset($data['id_estatus']))) {
               $this->db->where('m.id_estatus !=',15);
            } else if ($data['id_estatus']==15) {
               $this->db->where('m.id_estatus',$data['id_estatus']);
            } else {
              $this->db->where('m.id_estatus !=',15);
            }    



  
            $result = $this->db->get();

            if ( $result->num_rows() > 0 )
               return $result->row();
            else
               return False;
            $result->free_result();              


    }                
  
    public function listado_registros($data){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id, m.movimiento,m.movimiento_unico,m.id_empresa, m.factura, m.id_descripcion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um,  m.cantidad_royo, m.ancho, m.precio,m.iva, m.codigo, m.comentario,prod.codigo_contable');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario'); //, m.fecha_mac fecha
          $this->db->select("(DATE_FORMAT(m.fecha_entrada,'%d-%m-%Y %H:%i')) as fecha",false);

          

          $this->db->select('
                        CASE m.id_estatus
                          WHEN 14 THEN "-D"
                           ELSE ""
                        END AS estatusd
         ',False);          


          $this->db->select('c.hexadecimal_color, c.color, u.medida,p.nombre');
          $this->db->select('co.composicion');
          $this->db->select('m.peso_real');
          $this->db->select('a.almacen');

          $this->db->select('m.id_factura, m.c234, m.id_operacion, m.devolucion');
          $this->db->select('tipfac.tipo_factura, m.id_almacen');

          $this->db->select('CONCAT(us.nombre," ",us.apellidos) as nombre_completo', false);
          
          $this->db->from($this->historico_registros_entradas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); //AND a.activo=1
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_empresa','LEFT');
          $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');          
          $this->db->join($this->productos.' as prod', 'prod.referencia = m.referencia','LEFT');

          
          $this->db->join($this->tipos_facturas.' As tipfac' , 'tipfac.id = m.id_factura'); 
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario','LEFT');          


          

          $where='';
          
          
          

          $where.= (($where!='') ? ' and ' : '') . '  m.movimiento_unico = '.$data["id_movimiento"];
          
           $where.= (($where!='') ? ' and ' : '') .' m.devolucion = '.$data["dev"];

          if ($data['id_estatus']!=0) {
            // $id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
            //$this->db->where('m.id_estatus',$data['id_estatus']);
             $where.= (($where!="") ? " and " : "") . ' m.id_estatus = '.$data["id_estatus"];
          } else {
             //$id_estatusid = '';
          }            

          if  ($data['dev'] == 0) { //si es una entrada porq la devolucion puede tener multiples
            //$this->db->where('m.id_factura',$data['id_factura']);
            $where.= (($where!="") ? " and " : "") . ' m.id_factura = '.$data["id_factura"];
          }  

          /*
          if ($data['tipo_entrada']=='D') {
              $where.= (($where!="") ? " and " : "") . '  ( m.devolucion <>  0) ';  //devoluciones
          } else if ($data['tipo_entrada']=='C') {
               $where.= (($where!="") ? " and " : "") . ' ( m.id_compra <>  0) ';  //compra
          } else if ($data['tipo_entrada']!='T') {
             $where.= (($where!="") ? " and " : "") . '  ( m.nombre_usuario =  "" )  and ( m.devolucion =  0) and ( m.id_compra =  0)  ';   //Normal
          } else {
             $where.= (($where!="") ? " and " : "") . '  ( m.nombre_usuario <>  "" ) ';   //Transferencia
          }    

          */

          if ($data['tipo_entrada']=='E') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  1 ) and ( m.devolucion =  0) and ( m.id_compra =  0)  '; 
          } 

          if ($data['tipo_entrada']=='B') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  72 ) ';  //compra
          } 

          if ($data['tipo_entrada']=='T') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  70 ) ';  //compra
          } 


          if ($data['tipo_entrada']=='D') {
              $where.= (($where!="") ? " and " : "") . ' ( m.devolucion <>  0) ';  //devoluciones
          } 

          if ($data['tipo_entrada']=='C') {
              //$nomb_usuario = ' and ( m.id_compra <>  0) ';  //compra
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  71 ) ';  //compra
          } 

          if ($data['tipo_entrada']=='A') {
              $where.= (($where!="") ? " and " : "") . '  ( m.id_operacion =  73 ) ';  
          } 
           
          

          $this->db->where('('.$where.')');

          $this->db->order_by('m.id_lote', 'asc'); 
          $this->db->order_by('m.codigo', 'asc'); 
          $this->db->order_by('m.consecutivo', 'asc'); 

           $result = $this->db->get();
        


            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }        



    public function listado_salida($data){

          $id_session = $this->session->userdata('id');
          $nombre_completo = $this->session->userdata('nombre_completo');

                    
          $this->db->select('m.id, m.id_apartado, m.mov_salida,m.mov_salida_unico, m.movimiento,m.movimiento_unico,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario'); //, m.fecha_mac fecha

          //$this->db->select('DATE_FORMAT((m.fecha_mac),"%d-%m-%Y  %H:%I:%S")  fecha', false);
          $this->db->select("(DATE_FORMAT(m.fecha_salida,'%d-%m-%Y %H:%i')) as fecha",false);

          $this->db->select('c.hexadecimal_color,c.color, u.medida,p.nombre cliente, ca.nombre cargador');

          $this->db->select("( CASE WHEN m.id_usuario_apartado <> '' THEN CONCAT(us.nombre,' ',us.apellidos) ELSE '".$nombre_completo."' END ) AS nom_vendedor", FALSE);
          

          
          $this->db->select("tp.tipo_pedido,m.id_tipo_pedido");          
          $this->db->select("tf.tipo_factura,m.id_tipo_factura");          

          $this->db->select("( CASE WHEN m.id_apartado = 3 THEN m.consecutivo_venta ELSE m.id_cliente_apartado END ) AS mov_pedido", FALSE);

          $this->db->select('a.almacen');
          $this->db->select("prod.codigo_contable");  
          $this->db->select("m.peso_real, m1.peso_real peso_entrada");          

          //$this->db->select("prov_pedido.nombre cliente_pedido");
          $this->db->select("( CASE WHEN m.on_off = 1 THEN t.nombre ELSE prov_pedido.nombre END ) AS cliente_pedido");
          $this->db->select("prov_apartado.nombre cliente_apartado");


          $this->db->select("m.id_operacion_salida, m.id_operacion_pedido");
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
              AS mov",FALSE);

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
          AS mov_pedido",FALSE);


          $this->db->from($this->historico_registros_salidas.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen'); //AND a.activo=1
          $this->db->join($this->historico_registros_entradas.' as m1' , 'm1.codigo = m.codigo','LEFT');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->usuarios.' As us' , 'us.id = m.id_usuario_apartado','LEFT');          
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');          
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->proveedores.' As prov_pedido' , 'prov_pedido.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->catalogo_tiendas.' As t' , 't.id = m.consecutivo_venta','LEFT');
          $this->db->join($this->proveedores.' As prov_apartado' , 'prov_apartado.id = m.id_cliente_apartado','LEFT');
          


          //$this->db->where('m.id_usuario',$id_session);
          
          $this->db->where('m.mov_salida_unico',$data['num_mov']);
          $this->db->where('m.id_operacion_salida',$data['id_operacion_salida']);
          

          $this->db->group_by('m.id');

          if (!(isset($data['id_estatus']))) {
             $this->db->where('m.id_estatus !=',15);
          } else if ($data['id_estatus']==15) {
             //$id_estatusid = ' and ( m.id_estatus =  '.$data['id_estatus'].' ) ';  
             $this->db->where('m.id_estatus',$data['id_estatus']);
          } else {
             //$id_estatusid = '';
            $this->db->where('m.id_estatus !=',15);
          }                

          $this->db->order_by('m.id_lote', 'asc'); 
          $this->db->order_by('m.codigo', 'asc'); 
          $this->db->order_by('m.consecutivo', 'asc'); 

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

  }        


 public function etiqueta_codigo($data){

          $id_session = $this->session->userdata('id');
                    
          $this->db->select('m.id,  m.movimiento,m.id_empresa, m.factura, m.id_descripcion, m.id_operacion,m.devolucion, m.num_partida');
          $this->db->select('m.id_color, m.id_composicion, m.id_calidad, m.referencia');
          $this->db->select('m.id_medida, m.cantidad_um, m.cantidad_royo, m.ancho, m.precio, m.codigo, m.comentario');
          $this->db->select('m.id_estatus, m.id_lote, m.consecutivo, m.id_cargador, m.id_usuario,   co.composicion'); //m.fecha_mac fecha,

          $this->db->select('DATE_FORMAT((m.fecha_mac),"%d-%m-%Y  %H:%I:%S")  fecha', false);

          $this->db->select('c.hexadecimal_color,c.color, u.medida, ca.nombre cargador,prod.codigo_contable');

          $this->db->select('
                        CASE m.id_estatus
                          WHEN 14 THEN "-D"
                           ELSE ""
                        END AS estatusd
         ',False);          

          
          

          $this->db->from($this->registros.' as m');
          $this->db->join($this->colores.' As c' , 'c.id = m.id_color','LEFT');
          $this->db->join($this->unidades_medidas.' As u' , 'u.id = m.id_medida','LEFT');
          $this->db->join($this->cargadores.' As ca' , 'ca.id = m.id_cargador','LEFT');
          $this->db->join($this->composiciones.' As co' , 'co.id = m.id_composicion','LEFT');
          $this->db->join($this->productos.' as prod', 'prod.referencia = m.referencia','LEFT');


          $this->db->where('m.codigo',$data['codigo']);

           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

  }  






    public function pedido_especifico_vendedor($data){

          
          
          $id_session = $this->db->escape($this->session->userdata('id'));

          

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado');  //fecha falta
          $this->db->select('pr.nombre dependencia,m.id_tipo_pedido,m.id_tipo_factura');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva, m.fecha_apartado,m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida, m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('
                        CASE m.id_apartado
                          WHEN "4" THEN "Pedido Individual"
                           WHEN "5" THEN "Pedido Confirmado"
                           WHEN "6" THEN "Disponibilidad Salida"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          
          $this->db->select('
                        CASE m.id_apartado
                           WHEN "4" THEN "ab1d1d"
                           WHEN "5" THEN "f1a914"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  
          $this->db->select("a.almacen");

          
          $this->db->select("prod.codigo_contable");  

          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");    

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
                AS mov",FALSE);      


          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');
         
          
          

          //filtro de busqueda
          if ($data['id_almacen']!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$data['id_almacen'].' ) ';  
          } else {
              $id_almacenid = '';
          } 

          $where = '(
                     ( m.id_operacion_pedido =  '.$data["id_operacion_pedido"].' )  AND 
                      (
                        (( m.id_apartado = 5 ) or ( m.id_apartado = 6 ) ) AND ( m.movimiento_unico_apartado = "'.$data['num_mov'].'" )
                      ) '.$id_almacenid.'
            )';   

          $this->db->where($where);

  
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
         
      }    






    public function pedido_especifico_tienda($data){
          /*
          $id_usuario = $data['num_mov'];
          $id_cliente = $data['id_cliente'];
          $id_almacen = $data['id_almacen'];
          $consecutivo_venta = $data['consecutivo_venta'];
          */

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado');  //fecha falta
          $this->db->select('p.nombre comprador,m.id_tipo_pedido,m.id_tipo_factura');  
          $this->db->select('pr.nombre cliente ');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);
          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva, m.fecha_apartado,m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida, m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "Apartado Individual"
                           WHEN "2" THEN "Apartado Confirmado"
                           WHEN "3" THEN "Disponibilidad Salida"
                           ELSE "No Apartado"
                        END AS tipo_apartado
         ',False);          
          $this->db->select('
                        CASE m.id_apartado
                          WHEN "1" THEN "ab1d1d"
                           WHEN "2" THEN "f1a914"
                           WHEN "3" THEN "14b80f"
                           ELSE "No Apartado"
                        END AS color_apartado
         ',False);          

          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable");  

          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura");  

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
              AS mov",FALSE);          

          $this->db->from($this->registros.' as m');
          $this->db->join($this->almacenes.' As a' , 'a.id = m.id_almacen AND a.activo=1');
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');


          if ($data['id_almacen']!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$data['id_almacen'].' ) ';  
          } else {
              $id_almacenid = '';
          } 
 
          /*
          $where = '(
                      ( m.id_operacion_pedido =  '.$data["id_operacion_pedido"].' )  AND 
                      ( 
                        ( (m.id_apartado = 2) OR (m.id_apartado = 3) ) AND ( m.id_usuario_apartado = "'.$id_usuario.'" ) AND ( m.id_cliente_apartado = "'.$id_cliente.'" ) AND ( m.consecutivo_venta = '.$data['consecutivo_venta'].' ) '.$id_almacenid.'
                      ) 
            )';   */


            $where = '(
                     ( m.id_operacion_pedido =  '.$data["id_operacion_pedido"].' )  AND 
                      (
                        (( m.id_apartado = 2 ) or ( m.id_apartado = 3 ) ) AND ( m.movimiento_unico_apartado = "'.$data['num_mov'].'" )
                      ) '.$id_almacenid.'
            )';  


          $this->db->where($where);

  
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

         
      }  








    public function pedido_especifico_completo($data){

          
          

          $id_session = $this->db->escape($this->session->userdata('id'));

          $this->db->select("SQL_CALC_FOUND_ROWS *", FALSE); //

          $this->db->select('m.id_usuario_apartado, m.id_cliente_apartado');  //fecha falta
          $this->db->select('pr.nombre dependencia,m.id_tipo_pedido,m.id_tipo_factura');  
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as cliente', FALSE);
          $this->db->select('CONCAT(u.nombre,"  ",u.apellidos) as vendedor', FALSE);

          $this->db->select('m.codigo,m.id_descripcion, m.id_lote,m.precio,m.iva, m.fecha_apartado, m.consecutivo');  
          $this->db->select('c.hexadecimal_color,c.color nombre_color, m.ancho, um.medida, m.cantidad_um');
          
          $this->db->select("( CASE WHEN m.id_medida = 1 THEN m.cantidad_um ELSE 0 END ) AS metros", FALSE);
          $this->db->select("( CASE WHEN m.id_medida = 2 THEN m.cantidad_um ELSE 0 END ) AS kilogramos", FALSE);

          $this->db->select('p.nombre comprador , m.id_apartado');  

          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "Vendedor"
                           WHEN "6" THEN "Tienda"
                           ELSE "No Pedido"
                        END AS tipo_apartado
         ',False);          


          $this->db->select('
                        CASE m.tipo_salida
                           WHEN 1 THEN "(Salida Parcial)"
                           WHEN 2 THEN "(Salida Total)"
                           ELSE "(Salida Total)"
                        END AS tipo_pedido
         ',False);  



          $this->db->select('
                        CASE m.id_apartado
                           WHEN "3" THEN "ab1d1d"
                           WHEN "6" THEN "14b80f"
                           ELSE "No Pedido"
                        END AS color_apartado
         ',False);  

          $this->db->select("a.almacen");
          $this->db->select("prod.codigo_contable"); 

          $this->db->select("tp.tipo_pedido");          
          $this->db->select("tf.tipo_factura"); 


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
          $this->db->join($this->productos.' As prod' , 'prod.referencia = m.referencia','LEFT');
          $this->db->join($this->usuarios.' As u' , 'u.id = m.id_usuario_apartado','LEFT');
          $this->db->join($this->proveedores.' As pr', 'u.id_cliente = pr.id','LEFT');
          $this->db->join($this->proveedores.' As p' , 'p.id = m.id_cliente_apartado','LEFT');
          $this->db->join($this->unidades_medidas.' As um' , 'um.id = m.id_medida','LEFT');
          $this->db->join($this->colores.' As c', 'm.id_color = c.id','LEFT');
          $this->db->join($this->tipos_pedidos.' As tp' , 'tp.id = m.id_tipo_pedido','LEFT');
          $this->db->join($this->tipos_facturas.' As tf' , 'tf.id = m.id_tipo_factura','LEFT');

          
          

          //filtro de busqueda
          if ($data['id_almacen']!=0) {
              $id_almacenid = ' AND ( m.id_almacen =  '.$data['id_almacen'].' ) ';  
          } else {
              $id_almacenid = '';
          } 

          $where = '(
                    ( m.id_operacion_salida =  '.$data["id_operacion_pedido"].' )  AND 
                      (
                         ( m.mov_salida_unico = '.$data['num_mov'].' )
                      )'.$id_almacenid.'
            )';   


       $this->db->where($where);

  
           $result = $this->db->get();
        
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();

         
      }  










}
