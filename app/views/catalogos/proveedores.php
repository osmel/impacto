<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php
 	if (!isset($retorno)) {
      	$retorno ="catalogos";
    }


	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
	  
	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  {
	  			$coleccion_id_operaciones = array();
	  } 	

?>	

	<div class="container">

		<div class="row">
			<br>
			<div class="col-xs-12 col-sm-12 col-md-12 marginbuttom">
				<div class="col-xs-12 col-sm-12 col-md-12"><h4>Catálogo de Proveedores / Clientes / Sucursales</h4></div>
			</div>	

			<div class="col-xs-12 col-sm-4 col-md-3 marginbuttom">
				<a href="<?php echo base_url(); ?>nuevo_proveedor" type="button" class="btn btn-success btn-block">Nuevo</a>
			</div>

		</div>
		<br>
		<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Listado</div>
			<div class="panel-body">
				<div class="col-md-12">
					<div class="table-responsive">


						<section>
							<table id="tabla_cat_proveedores" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="text-center cursora" width="15%">Código</th>
										<th class="text-center cursora" width="20%">Nombre </th>
										<th class="text-center cursora" width="15%">Teléfono</th>
										<th class="text-center cursora" width="15%">Actividad comercial </th>
										<th class="text-center " width="5%"><strong>Días de Créditos</strong></th>
										<th class="text-center " width="5%"><strong>Editar</strong></th>
										<th class="text-center " width="5%"><strong>Eliminar</strong></th>

										<!-- Este campo es válido, sólo para proveedor-->
									</tr>
								</thead>
							</table>
						</section>

					</div>

				</div>
			</div>
		</div>
		
		<div class="row">

			<div class="col-sm-8 col-md-9"></div>
			<div class="col-sm-4 col-md-3">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
		<br>
	</div>
	</div>

<?php $this->load->view('footer'); ?>	


<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	

