<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<?php
 	if (!isset($retorno)) {
      	$retorno ="catalogos";
    }
?>    

	<div class="container">
		
		
		<div class="row">
			<br>
			<div class="col-xs-12 col-sm-12 col-md-12 marginbuttom">
				<div class="col-xs-12 col-sm-12 col-md-12"><h4>Catálogo de Composiciones</h4></div>
			</div>		

			<div class="col-xs-12 col-sm-4 col-md-3 marginbuttom">
				<a href="<?php echo base_url(); ?>nuevo_composicion" type="button" class="btn btn-success btn-block">Nueva composición</a>
			</div>
		</div>

		<br>
		<div class="container row">
		<div class="panel panel-primary">
			<div class="panel-heading">Listado de composiciones</div>
			<div class="panel-body">
			<div class="col-md-12">				
				<div class="table-responsive">

					<section>
						<table id="tabla_cat_composiciones" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th class="text-center cursora" style="width:90%">Composición</th>
									<th class="text-center" style="width:5%"><strong>Editar</strong></th>
									<th class="text-center" style="width:5%"><strong>Eliminar</strong></th>
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
				<a href="<?php echo base_url(); ?>catalogos" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
			</div>
		</div>
		<br/>
	</div>
	</div>

<?php $this->load->view('footer'); ?>	

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	

