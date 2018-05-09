<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>


<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
?>    

<div class="container blanco">
			<br>
			<h4>Consulta por Producto</h4>	
				<hr style="padding: 0px; margin: 6px;"/>					

	<div class="row">					
		<div class="col-md-12">		

		

		<div class="row">
			<div class="form-group">

				
				<div class="col-sm-4 col-md-4">
					<label id="label_productos" for="descripcion" class="col-sm-12 col-md-12">Productos</label>
					 <input  type="text" name="editar_producto_consulta" id="editar_producto_consulta" idproveedor="1" class="form-control buscar_producto_consulta ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
				</div>


				
				<div class="col-sm-4 col-md-4">
					  <label for="descripcion" class="col-sm-4 col-md-4">Color</label>
	                  <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un producto." name="color_consulta" id="color_consulta"  dependencia="composicion" nombre="composición" style="padding-right:0px">
	                	    <option value="0">Seleccione un color</option>
	                  </select>
	            </div>  


			</div>
              

		</div>	

      
      


			<hr style="padding: 0px; margin: 6px;"/>					
			<h4>Listado de Proveedores</h4>	
			


			<div class="table-responsive">
				<section>
					<table id="tabla_consulta_producto" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center cursora" width="10%">Código</th>
								<th class="text-center cursora" width="40%">Proveedor</th>
								<th class="text-center cursora" width="15%">Teléfono</th>
								<th class="text-center cursora" width="35%">Actividad Comercial</th>
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
