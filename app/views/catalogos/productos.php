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
				<div class="col-xs-12 col-sm-12 col-md-12"><h4>Catálogo de Telas</h4></div>
			</div>	
		
			<div class="col-xs-12 col-sm-4 col-md-3 marginbuttom">
				<a href="<?php echo base_url(); ?>nuevo_producto" type="button" class="btn btn-success btn-block">Nuevo Producto</a>
			</div>
		</div>


	<div class="row">					
		
	<div class="col-md-12">		

		<br/>

		<div id="example22" class="row">
              <div class="col-sm-6 col-md-3">
                 <div class="form-group">
						<label for="descripcion">Producto</label>
                        <select class="form-control" name="producto_catalogo" id="producto_catalogo" dependencia="color_catalogo" nombre="un color">
                            <option value="">Seleccione un producto</option>
                            <?php if($productos){ ?>
                              <?php foreach($productos as $producto){ ?>
                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>"><?php echo htmlspecialchars($producto->descripcion); ?></option>
                              <?php } ?>
                            <?php } ?>
                        </select>
                 </div>
              </div>

              <div class="col-sm-6 col-md-3">
                 <div class="form-group">
					<label for="descripcion">Color</label>
                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color_catalogo" id="color_catalogo"  dependencia="composicion_catalogo" nombre="una composición">
                            <option value="0">Seleccione un color</option>
                          </select>
                 </div>
              </div>
              
              <div class="col-sm-6 col-md-3">
                 <div class="form-group">
					<label for="descripcion">Composición</label>
                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion_catalogo" id="composicion_catalogo" dependencia="calidad_catalogo" nombre="una calidad">
                            <option value="0">Seleccione una composición</option>
                          </select>
                 </div>
              </div>



              <div class="col-sm-6 col-md-3">
                 <div class="form-group">
					  <label for="descripcion">Calidad</label>
                      <select class="form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad_catalogo" id="calidad_catalogo" dependencia="" nombre="">
                        <option value="0">Seleccione una calidad</option>
                      </select>
                 </div>
              </div>

        </div>     








		<div class="panel panel-primary">
			<div class="panel-heading">Listado de productos</div>
			<div class="panel-body">

			<div class="table-responsive">
				
				<section>
					
					<table style="font-size: 12px;" id="tabla_cat_productos" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center" width="12%">Nombre de Tela</th>
								<th class="text-center" width="12%">Referencia</th>
								<th class="text-center" width="6%">Mínimo_mts</th>
								<th class="text-center" width="6%">Mínimo_kg</th>
								
								<th class="text-center" width="15%">Imagen</th>
								<th class="text-center" width="10%">Color</th>
								<th class="text-center" width="4%">Últ. Cons.</th>

								<th class="text-center" width="4%">Composición</th>
								<th class="text-center" width="4%">Calidad</th>
								<th class="text-center" width="4%">Precio</th>
								<th class="text-center" width="4%">Desactivar</th>
								
								<th class="text-center" width="4%">Editar</th>
								
								<th class="text-center" width="5%">Eliminar</th>
								
								<th class="text-center" width="5%">Cambio Precio/Código</th>
								<th class="text-center" width="5%">Detalle</th>


							</tr>
						</thead>
					</table>

				</section>

			</div> <!--Table responsive-->

			</div>
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
