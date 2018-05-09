<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view( 'header' ); ?>
	<!-- Aseguradoras-->
<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	  
	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  {
	  			$coleccion_id_operaciones = array();
	  } 	

?>	

<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Catálogos</div>
			<div class="panel-body">	

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones)) || (in_array(11, $coleccion_id_operaciones)) ) { ?>
					<div class="row">
						
							<div class="col-md-3"></div>
								<div class="col-md-6">
									<a href="<?php echo base_url(); ?>productos" type="button" class="btn btn-success btn-lg btn-block">Tipos de Telas (Productos)</a>
								</div>
							<div class="col-md-3"></div>
							
					</div>	
				<?php } ?>	

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(12, $coleccion_id_operaciones)) ) { ?>
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>colores" type="button" class="btn btn-success btn-lg btn-block" >Colores</a>
							</div>
						<div class="col-md-3"></div>
					</div>
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(13, $coleccion_id_operaciones)) ) { ?>
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>composiciones" type="button" class="btn btn-success btn-lg btn-block" >Composiciones</a>
							</div>
						<div class="col-md-3"></div>
					</div>
				<?php } ?>		

				<?php 
			         if   (( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(14, $coleccion_id_operaciones))  
            	          || (in_array(15, $coleccion_id_operaciones)) || (in_array(16, $coleccion_id_operaciones)) )   { 

				?>
					<div class="row">
						<div class="col-md-3"></div>

							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>proveedores" type="button" class="btn btn-success btn-lg btn-block">Proveedores / Clientes / Sucursales</a>
							</div>

						<div class="col-md-3"></div>
					</div>
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(18, $coleccion_id_operaciones)) ) { ?>
					<!--
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>anchos" type="button" class="btn btn-succes btn-lg btn-block" >Anchos de Telas</a>
							</div>
						<div class="col-md-3"></div>
					</div>-->
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(17, $coleccion_id_operaciones)) ) { ?>
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>cargadores" type="button" class="btn btn-success btn-lg btn-block" >Cargadores</a>
							</div>
						<div class="col-md-3"></div>
					</div>					
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(19, $coleccion_id_operaciones)) ) { ?>
				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>calidades" type="button" class="btn btn-success btn-lg btn-block">Calidades</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
				<?php } ?>		

				



				<?php if ( ( $perfil == 1 ) && ($this->session->userdata('especial') ==1 ) ) { ?>

				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>almacenes" type="button" class="btn btn-success btn-lg btn-block">Almacenes</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
				
				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>tipos_facturas" type="button" class="btn btn-success btn-lg btn-block">Tipos de Facturas</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
				

				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>tipos_pedidos" type="button" class="btn btn-success btn-lg btn-block">Tipos de pedidos</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
				

				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>tipos_ventas" type="button" class="btn btn-success btn-lg btn-block">Tipos de ventas</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
					
				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>configuraciones" type="button" class="btn btn-success btn-lg btn-block">Configuraciones</a>
							</div>
						<div class="col-md-3"></div>
					</div>				
				
				<?php } ?>		









				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(20, $coleccion_id_operaciones)) ) { ?>
					<!-- 
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>unidades_medidas" type="button" class="btn btn-success btn-lg btn-block">**Unidades de medidas</a>
							</div>
						<div class="col-md-3"></div>
					</div>
					-->			
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(21, $coleccion_id_operaciones)) ) { ?>
					<!-- 
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<a href="<?php echo base_url(); ?>actividades_comerciales" type="button" class="btn btn-success btn-lg btn-block">****Actividades comerciales</a>
						</div>
						<div class="col-md-3"></div>
					</div>						
					-->
				<?php } ?>		

				<?php if ( ( $perfil == 1 ) || (in_array(8, $coleccion_id_operaciones))  || (in_array(22, $coleccion_id_operaciones)) ) { ?>
					<!-- 
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<a href="<?php echo base_url(); ?>operaciones" type="button" class="btn btn-success btn-lg btn-block">**Operaciones</a>
						</div>
						<div class="col-md-3"></div>
					</div>
					-->
				<?php } ?>		
				
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-lg btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar 

						</a>
					</div>
					<div class="col-md-3"></div>
				</div>	
			</div>
		</div>
	</div>

<?php $this->load->view( 'footer' ); ?>