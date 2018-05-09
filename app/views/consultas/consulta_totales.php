<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>


<?php
 	if (!isset($retorno)) {
      	$retorno ="";
    }
?>    

<div class="container blanco">



<br/>
	<h4>Reportes de Totales</h4>	
	<hr style="padding: 0px; margin: 6px;"/>		
	<div class="row">					
		<div class="col-md-12">		

		

		<div class="row">
			<div class="row">

				<fieldset style="display:none;">
					
					<div class="col-sm-4 col-md-4">
						<label id="label_productos" for="descripcion" class="col-sm-12 col-md-12">Productos</label>
						 <input  type="text" name="editar_producto_consulta" id="editar_producto_consulta" idproveedor="1" class="form-control buscar_producto_consulta ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
					</div>


					
					<div class="col-sm-4 col-md-4">
						  <label for="descripcion" class="col-sm-4 col-md-4">Color</label>
		                  <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un producto." name="color_consulta" id="color_consulta"  dependencia="composicion" nombre="una composición" style="padding-right:0px">
		                	    <option value="0">Seleccione un color</option>
		                  </select>
		            </div>  
				</fieldset>





							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<div id="almacen_id1" class="col-xs-12 col-sm-6 col-md-3">
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
							    <?php if  ( $this->session->userdata( 'id_perfil' ) == 1  ) { ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	

										<select name="id_almacen_totales" id="id_almacen_totales" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
										
											<option value="0">Todos</option>

												<?php foreach ( $almacenes as $almacen ){ ?>
													<?php 
													if  (($almacen->id_almacen==$id_almacen) ) 
														{$seleccionado='selected';} else {$seleccionado='';}
													?>
													
														<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?>><?php echo $almacen->almacen; ?></option>
												<?php } ?>
										</select>
									</fieldset>	

									</div>
								</div>
							</div>	



				<div id="mifecha" class="col-xs-12 col-sm-12 col-md-3">
					<label id="label_mifecha" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
					<div class="input-prepend input-group  form-group col-sm-12 col-md-12" style="padding-left:15px !important; padding-right:15px !important;">
                   		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
						<input id="mifoco" type="text" name="permisos1"  class="form-control col-sm-12 col-md-12 fecha_totales ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
					</div>	
                 </div>






			</div>
              

		</div>	



<!--  -->

				<div class="row" >
		                  <div class="col-sm-3 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div >

			                          <select class="form-control" name="producto_totales" id="producto_totales" dependencia="composicion_totales" nombre="una composición"  style="padding-right:0px;font-size:12px;">

			                            <option value="">Seleccione un producto</option>
			                            <?php if($productos){ ?>
			                              <?php foreach($productos as $producto){ ?>
			                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>"><?php echo htmlspecialchars($producto->descripcion); ?></option>
			                              <?php } ?>
			                            <?php } ?>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>


		                  <div class="col-sm-3 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div >

			                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="composicion_totales" id="composicion_totales" dependencia="ancho_totales" nombre="un ancho" style="padding-right:0px;font-size:12px;">
			                            <option value="0">Seleccione una composición</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>		                  


		                  <div class="col-sm-2 col-md-2">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Ancho</label>
								<div>

			                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="ancho_totales" id="ancho_totales"  dependencia="color_totales" nombre="un color" style="padding-right:0px; font-size:12px;">
			                            <option value="0">Seleccione un ancho</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>


		                  
		                  <div class="col-sm-2 col-md-2">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Color</label>
								<div>

			                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un ANCHO." name="color_totales" id="color_totales"  dependencia="proveedor_totales" nombre="un proveedor" style="padding-right:0px; font-size:12px;">
			                            <option value="0">Seleccione un color</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  




		                  <div class="col-sm-2 col-md-2">
		                     <div class="form-group">
								<label for="descripcioon" class="col-sm-12 col-md-12">Proveedor</label>
								<div>
			                          <select style="font-size:10px;" class="form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="proveedor_totales" id="proveedor_totales" dependencia="" nombre="" >
			                            <option value="0">Seleccione un proveedor</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>

		        </div>     


<!--  -->			

      
      


			<hr style="padding: 0px; margin: 6px;"/>		
			<h4>Listado de Proveedores</h4>	
			


			<div class="table-responsive">

				<fieldset class="row" id="disa_reportetotal" disabled>
					<div class="col-sm-4 col-md-4 marginbuttom">
						<a  id="impresion_totales" type="button" class="btn btn-success btn-block">Imprimir</a>
					</div>

					<div class="col-sm-4 col-md-4">
						<a id="exportar_totales" type="button" class="btn btn-success btn-block">Exportar</a>
					</div>
				</fieldset>	

				<br/>

				<section>
					<table id="tabla_consulta_totales" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center cursora" width="40%">Fecha</th>
								<th class="text-center cursora" width="20%">Entrada</th>
								<th class="text-center cursora" width="20%">Salida</th>
								<th class="text-center cursora" width="20%">Devolución</th>
								
							</tr>
						</thead>
					</table>
				</section>
			</div>
		</div>
	</div>

				<br/>		

				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="subtotal_entrada"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="subtotal_salida"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="subtotal_devoluciones" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									
					<div class="col-sm-3 col-md-2">	
						<span id="total_entrada"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_salida"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_devoluciones" ></span>				
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
