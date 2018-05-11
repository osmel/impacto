<?php if(! defined('BASEPATH')) exit('No tienes permiso para acceder a este archivo');

	class modelo extends CI_Model{
		
		private $key_hash;
		private $timezone;

		function __construct(){
			parent::__construct();
			$this->load->database("default");
			$this->key_hash    = $_SERVER['HASH_ENCRYPT'];
			$this->timezone    = 'UM1';

				//usuarios
			$this->usuarios    = $this->db->dbprefix('usuarios');
            $this->perfiles    = $this->db->dbprefix('perfiles');
            $this->catalogo_operaciones    = $this->db->dbprefix('catalogo_operaciones');
            $this->proveedores             = $this->db->dbprefix('catalogo_empresas');
            
            $this->historico_acceso = $this->db->dbprefix('historico_acceso');

      
              $this->registros_temporales               = $this->db->dbprefix('temporal_registros');
              $this->registros_cambios               = $this->db->dbprefix('registros_cambios');
              $this->registros_entradas             = $this->db->dbprefix('registros_entradas');
              $this->registros_salidas       = $this->db->dbprefix('registros_salidas');
              $this->historico_registros_entradas = $this->db->dbprefix('historico_registros_entradas');
              $this->historico_registros_salidas    = $this->db->dbprefix('historico_registros_salidas');
            
               $this->almacenes                         = $this->db->dbprefix('catalogo_almacenes');

		}



 public function usuarios_en_uso($id_usuario) {

          $result = $this->db->query("
            select distinct r.id_usuario from (

            (select distinct id_usuario from ".$this->registros_temporales.")
              union   
            (select distinct id_usuario from ".$this->registros_cambios.")
              union   

            (select distinct id_usuario from ".$this->registros_entradas.")
              union   

            (select distinct id_usuario from ".$this->registros_salidas.")
              union   

            (select distinct id_usuario from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_usuario from ".$this->historico_registros_salidas.")
              union

            (select distinct id_usuario_salida id_usuario from ".$this->registros_cambios.")
              union   

            (select distinct id_usuario_salida id_usuario from ".$this->registros_entradas.")
              union   

            (select distinct id_usuario_salida id_usuario from ".$this->registros_salidas.")
              union   

            (select distinct id_usuario_salida id_usuario from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_usuario_salida id_usuario from ".$this->historico_registros_salidas.")

              union

            (select distinct id_usuario_apartado id_usuario from ".$this->registros_cambios.")
              union   

            (select distinct id_usuario_apartado id_usuario from ".$this->registros_entradas.")
              union   

            (select distinct id_usuario_apartado id_usuario from ".$this->registros_salidas.")
              union   

            (select distinct id_usuario_apartado id_usuario from ".$this->historico_registros_entradas.")
              union   

            (select distinct id_usuario_apartado id_usuario from ".$this->historico_registros_salidas.")
              


              ) r 
           where r.id_usuario='".$id_usuario."'                                

          "
          );  

           if ( $result->num_rows() > 0 ) {
                  return 1;
              } else 
                  return 0;
            $result->free_result();                 

      }    



		//login
		public function check_login($data){
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->select("AES_DECRYPT(contrasena,'{$this->key_hash}') AS contrasena", FALSE);			
			$this->db->select($this->usuarios.'.nombre,'.$this->usuarios.'.apellidos');			
			$this->db->select($this->usuarios.'.id,'.$this->perfiles.'.id_perfil,'.$this->perfiles.'.perfil,'.$this->perfiles.'.operacion');
            $this->db->select($this->usuarios.'.coleccion_id_operaciones');         
            $this->db->select($this->usuarios.'.id_cliente');         
            $this->db->select($this->usuarios.'.sala');         
            $this->db->select($this->usuarios.'.especial');      
            $this->db->select($this->usuarios.'.id_almacen');         
            $this->db->select($this->usuarios.'.id_tienda'); 

                
			$this->db->from($this->usuarios);
			$this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');
            


			$this->db->where('contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);


			$login = $this->db->get();

			if ($login->num_rows() > 0)
				return $login->result();
			else 
				return FALSE;
			$login->free_result();
		}

        //anadir al historico de acceso
        public function anadir_historico_acceso($data){

            $timestamp = time();
            $ip_address = $this->input->ip_address();
            $user_agent= $this->input->user_agent();

            $this->db->set( 'email', "AES_ENCRYPT('{$data->email}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data->id_perfil);
            //$this->db->set( 'id_cliente', $data->id_cliente);

            $this->db->set( 'id_usuario', $data->id);
            $this->db->set( 'fecha',  gmt_to_local( $timestamp, 'UM1', TRUE) );
            $this->db->set( 'ip_address',  $ip_address, TRUE );
            $this->db->set( 'user_agent',  $user_agent, TRUE );
            

            $this->db->insert($this->historico_acceso );

            if ($this->db->affected_rows() > 0){
                    return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();

        }

       public function total_acceso($limit=-1, $offset=-1){

            $fecha = date_create(date('Y-m-j'));
            date_add($fecha, date_interval_create_from_date_string('-1 month'));
            $data['fecha_inicial'] = date_format($fecha, 'm');
            $data['fecha_final'] = $data['fecha_final'] = (date('m'));


            $this->db->from($this->historico_acceso.' As h');
            $this->db->join($this->usuarios.' As u' , 'u.id = h.id_usuario','LEFT');
            $this->db->join($this->perfiles.' As p', 'u.id_perfil = p.id_perfil','LEFT');

            if  (($data['fecha_inicial']) and ($data['fecha_final'])) {
                $this->db->where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_inicial'] );
                $this->db->or_where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_final'] );
            } 

              

           $unidades = $this->db->get();            
           return $unidades->num_rows();
        }

        //anadir al historico de acceso
        public function historico_acceso($data,$limit=-1, $offset=-1){

            $fecha = date_create(date('Y-m-j'));
            date_add($fecha, date_interval_create_from_date_string('-1 month'));
            $data['fecha_inicial'] = date_format($fecha, 'm');
            $data['fecha_final'] = $data['fecha_final'] = (date('m'));

            $this->db->select("AES_DECRYPT(h.email,'{$this->key_hash}') AS email", FALSE);            
            $this->db->select('p.id_perfil, p.perfil, p.operacion');
            $this->db->select('u.nombre,u.apellidos');         
            $this->db->select('h.ip_address, h.user_agent, h.id_usuario');
            $this->db->select("( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%d-%m-%Y %H:%i:%s') END ) AS fecha", FALSE);   

            $this->db->from($this->historico_acceso.' As h');
            $this->db->join($this->usuarios.' As u' , 'u.id = h.id_usuario','LEFT');
            $this->db->join($this->perfiles.' As p', 'u.id_perfil = p.id_perfil','LEFT');
            

            //gmt_to_local( $timestamp, 'UM1', TRUE) )
            
            if  (($data['fecha_inicial']) and ($data['fecha_final'])) {
                $this->db->where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_inicial'] );
                $this->db->or_where( "( CASE WHEN h.fecha = 0 THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(h.fecha),'%m') END ) = ", $data['fecha_final'] );
                
            }     
            

            if ($limit!=-1) {
                $this->db->limit($limit, $offset); 
            } 
              

            $this->db->order_by('h.fecha', 'desc'); 




            $login = $this->db->get();
            if ($login->num_rows() > 0)
                return $login->result();
            else 
                return FALSE;
            $login->free_result();
        }


		
		//Recuperar contraseña		
	    public function recuperar_contrasena($data){
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);						
			$this->db->select('nombre,apellidos');
			$this->db->select("AES_DECRYPT(telefono,'{$this->key_hash}') AS telefono", FALSE);			
			$this->db->select("AES_DECRYPT(contrasena,'{$this->key_hash}') AS contrasena", FALSE);
			$this->db->from($this->usuarios);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return $login->result();
			else 
				return FALSE;
			$login->free_result();		
	    }	

	
		//Lista de todos los usuarios 

        public function listado_usuarios(  ){

            $this->db->select($this->usuarios.'.id, nombre,  apellidos');

            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->select( "AES_DECRYPT( telefono,'{$this->key_hash}') AS telefono", FALSE );
            $this->db->select($this->perfiles.'.id_perfil,'.$this->perfiles.'.perfil,'.$this->perfiles.'.operacion');
            $this->db->select($this->usuarios.'.id_cliente');  
            $this->db->from($this->usuarios);
            $this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }     


        //Lista de todas las operaciones

        public function listado_operaciones(  ){

            $this->db->select('id, operacion, tooltip, grupo');
            $this->db->from($this->catalogo_operaciones);
            $this->db->where( 'activo', 1 );

            $this->db->order_by('orden', 'asc'); 

            $result = $this->db->get();
            
            if ( $result->num_rows() > 0 )
               return $result->result();
            else
               return False;
            $result->free_result();
        }    


 

 


        public function total_usuarios($uid){

            $this->db->from($this->usuarios);
            $this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');
            $this->db->where( $this->usuarios.'.id !=', $uid );
            
           $unidades = $this->db->get();            
           return $unidades->num_rows();
            
        }
   
        public function coger_usuarios($limit=-1, $offset=-1, $uid ){

		    $this->db->select($this->usuarios.'.id, nombre,  apellidos');
            

            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->select( "AES_DECRYPT( telefono,'{$this->key_hash}') AS telefono", FALSE );
			$this->db->select($this->perfiles.'.id_perfil,'.$this->perfiles.'.perfil,'.$this->perfiles.'.operacion');
            $this->db->select($this->usuarios.'.id_cliente');    
            $this->db->select($this->almacenes.'.almacen');         
                




			$this->db->from($this->usuarios);
			$this->db->join($this->perfiles, $this->usuarios.'.id_perfil = '.$this->perfiles.'.id_perfil');
            $this->db->join($this->almacenes, $this->usuarios.'.id_almacen = '.$this->almacenes.'.id','LEFT');
			$this->db->where( $this->usuarios.'.id !=', $uid );
            if ($limit!=-1) {
                $this->db->limit($limit, $offset); 
            } 
             

			$result = $this->db->get();
			
            if ( $result->num_rows() > 0 ) {
                foreach ($result->result() as $row)  {
                         $row->uso = self::usuarios_en_uso($row->id);
                 }                 
               return $result->result();
            }     
            else
               return False;
            $result->free_result();
        }        

        //eliminar usuarios
        public function borrar_usuario( $uid ){
            $this->db->delete( $this->usuarios, array( 'id' => $uid ) );
            if ( $this->db->affected_rows() > 0 ) return TRUE;
            else return FALSE;
        }



        //editar	
        public function coger_catalogo_usuario( $uid ){
            $this->db->select('id, nombre, apellidos, id_perfil,  coleccion_id_operaciones, id_cliente,id_almacen');
            $this->db->select( "AES_DECRYPT( email,'{$this->key_hash}') AS email", FALSE );
            $this->db->select( "AES_DECRYPT( telefono,'{$this->key_hash}') AS telefono", FALSE );
            $this->db->select( "AES_DECRYPT( contrasena,'{$this->key_hash}') AS contrasena", FALSE );
            $this->db->where('id', $uid);
            $result = $this->db->get($this->usuarios );
            if ($result->num_rows() > 0)
            	return $result->row();
            else 
            	return FALSE;
            $result->free_result();
        }  


		public function check_correo_existente($data){
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->from($this->usuarios);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return FALSE;
			else
				return TRUE;
			$login->free_result();
		}

		public function anadir_usuario( $data ){
            $timestamp = time();

            $id_session = $this->session->userdata('id');
            $this->db->set( 'fecha_pc',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->set( 'id_usuario',  $id_session );

            $this->db->set( 'id', "UUID()", FALSE);
			$this->db->set( 'nombre', $data['nombre'] );
            $this->db->set( 'apellidos', $data['apellidos'] );
            $this->db->set( 'email', "AES_ENCRYPT('{$data['email']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'telefono', "AES_ENCRYPT('{$data['telefono']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data['id_perfil']);
            $this->db->set( 'id_cliente', $data['id_cliente']);
            $this->db->set( 'id_tienda', $data['id_cliente']);
            
            $this->db->set( 'coleccion_id_operaciones', $data['coleccion_id_operaciones']);

            if  ($data['id_perfil']==2) {
                $this->db->set( 'id_almacen', $data['id_almacen']);    
            } else {
                $this->db->set( 'id_almacen', 0);    
            }
            


            $this->db->set( 'contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'creacion',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->insert($this->usuarios );

            if ($this->db->affected_rows() > 0){
            		return TRUE;
                } else {
                    return FALSE;
                }
                $result->free_result();
            
        }

		public function check_usuario_existente($data){
			
			$this->db->select("AES_DECRYPT(email,'{$this->key_hash}') AS email", FALSE);			
			$this->db->from($this->usuarios);
			$this->db->where('email',"AES_ENCRYPT('{$data['email']}','{$this->key_hash}')",FALSE);
			$this->db->where('id !=',$data['id']);
			$login = $this->db->get();
			if ($login->num_rows() > 0)
				return FALSE;
			else
				return TRUE;
			$login->free_result();
		}        


        public function edicion_usuario( $data ){

            $timestamp = time();

            $id_session = $this->session->userdata('id');
            $this->db->set( 'fecha_pc',  gmt_to_local( $timestamp, $this->timezone, TRUE) );
            $this->db->set( 'id_usuario',  $id_session );

			      $this->db->set( 'nombre', $data['nombre'] );
            $this->db->set( 'apellidos', $data['apellidos'] );
            $this->db->set( 'email', "AES_ENCRYPT('{$data['email']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'telefono', "AES_ENCRYPT('{$data['telefono']}','{$this->key_hash}')", FALSE );
            $this->db->set( 'id_perfil', $data['id_perfil']);
            $this->db->set( 'id_cliente', $data['id_cliente']);
            $this->db->set( 'id_tienda', $data['id_cliente']);
            
            $this->db->set( 'coleccion_id_operaciones', $data['coleccion_id_operaciones']);

            if  ($data['id_perfil']==2) {
                $this->db->set( 'id_almacen', $data['id_almacen']);    
            } else {
                $this->db->set( 'id_almacen', 0);    
            }

            
            $this->db->set( 'contrasena', "AES_ENCRYPT('{$data['contrasena']}','{$this->key_hash}')", FALSE );
            $this->db->where('id', $data['id'] );
            $this->db->update($this->usuarios );
            if ($this->db->affected_rows() > 0) {
				return TRUE;
			}  else
				 return FALSE;
        }		

//----------------**************catalogos-------------------************------------------
        public function coger_catalogo_perfiles(){
            $this->db->select( 'id_perfil, perfil, operacion' );
            $perfiles = $this->db->get($this->perfiles );
            if ($perfiles->num_rows() > 0 )
            	 return $perfiles->result();
            else
            	 return FALSE;
            $perfiles->free_result();
        }	    	

   			    
//----------------**************clientes_asociados-------------------************------------------
        public function coger_catalogo_clientes( $id_actividad){

              $this->db->select('p.id id_cliente, p.nombre cliente'); 
              $this->db->from($this->proveedores.' as p');

             // $where = '( (LOCATE("'.$id_actividad.'", p.coleccion_id_actividad) >0)   )';          $this->db->where($where);              

              $this->db->where('(LOCATE("'.$id_actividad.'", p.coleccion_id_actividad) >0)' );

              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
        }  


        public function coger_catalogo_proveedores( $id_actividad){

              $this->db->select('p.id, p.nombre'); 
              $this->db->from($this->proveedores.' as p');

              $id_actividad=1;
              $this->db->where('(LOCATE("'.$id_actividad.'", p.coleccion_id_actividad) >0)' );

              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
        }           
			    
//----------------**************almacenes_asociados-------------------************------------------
        public function coger_catalogo_almacenes( $id_actividad){

              $this->db->select('a.id id_almacen, a.almacen almacen'); 
              $this->db->from($this->almacenes.' as a');

              $this->db->where('a.activo', 1);

              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
        }   
          
        public function listado_almacenes(){

              $this->db->select('a.id id_almacen, a.almacen almacen'); 
              $this->db->from($this->almacenes.' as a');

              //$this->db->where('a.activo', 1);

              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->result();
                else
                   return False;
                $result->free_result();
        }               

    public function status_almacen( $data){

              $this->db->select('activo'); 
              $this->db->from($this->almacenes);
              $this->db->where('id', $data["id_almacen"]);

              $result = $this->db->get();

                if ( $result->num_rows() > 0 )
                   return $result->row();
                else
                   return False;
                $result->free_result();
        }   


	} 
?>