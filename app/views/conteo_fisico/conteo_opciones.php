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
			<div class="panel-heading">Conteos de Inventarios</div>
			<div class="panel-body">	

				<?php if ( ( $perfil == 1 ) || (in_array(50, $coleccion_id_operaciones)) ) { ?>		 
					<div class="row">
						
							<div class="col-md-3"></div>
								<div class="col-md-6">
									<a href="<?php echo base_url(); ?>informe_pendiente" type="button" class="btn btn-success btn-lg btn-block">Generar conteos</a>
								</div>
							<div class="col-md-3"></div>
							
					</div>	
				

				
					<div class="row">
						<div class="col-md-3"></div>
							<div class="col-md-6">
								<a href="<?php echo base_url(); ?>historico_conteo" type="button" class="btn btn-success btn-lg btn-block" >Historicos de conteos</a>
							</div>
						<div class="col-md-3"></div>
					</div>
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