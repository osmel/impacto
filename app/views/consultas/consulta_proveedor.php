<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>


<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
?>    

<div class="container blanco">

<br>

	
	<h4>Consulta por Proveedor</h4>	
	<hr style="padding: 0px; margin: 6px;"/>		
	<div class="row">					
		<div class="col-md-12">		

		

		<div class="row">
			<div class="form-group">
				<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
				<div class="col-sm-4 col-md-4">
					 <input  type="text" name="editar_proveedor_consulta" id="editar_proveedor_consulta" idproveedor="1" class="form-control buscar_proveedor_consulta ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
				</div>
			</div>
		</div>	

			
			<h4>Listado de productos</h4>	
			<hr style="padding: 0px; margin: 6px;"/>		


			<div class="table-responsive">
				<section>
					<table id="tabla_consulta_proveedor" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center cursora" width="40%">Nombre de Tela <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" width="30%">Referencia <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" width="5%">Mínimo <i class="glyphicon glyphicon-sort"></i></th>
								
								<th class="text-center cursora" width="8%">Imagen <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" width="17%">color <i class="glyphicon glyphicon-sort"></i></th>

								

							</tr>
						</thead>
					</table>
				</section>
			</div>
		</div>
	</div>

			<!-- Fin de la Regilla-->


		<br>
		<div class="row">
			<div class="col-sm-8 col-md-8"></div>
			<div class="col-sm-4 col-md-4">
				<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
			</div>
		</div>
<br>
	
</div>



<?php $this->load->view('footer'); ?>


<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>	
