<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
 	if (!isset($retorno)) {
      	$retorno ="reportes";
      	$otro_retorno='listado_notas';
      	$id_almacen=$this->session->userdata('id_almacen');
    }
	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );    
?>   
					
<div class="container">
	<div class="row">
		<div class="col-md-2" >
			<div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> 
			<span> Productos Devueltos</span>
	    </div>
	</div>
</div>

<div class="container margenes">

		<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Histórico de Entradas</div>
			<div class="panel-body">		








<!-- Aqui comienza filtro	-->
				<div class="row">
					<div id="disponibilidad"  class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
								<button  id="ver_filtro" type="button" class="btn btn-success btn-block ttip" title="Mostrar u ocultar filtros.">Filtros</button>
					</div>
				</div>

				<div class="col-md-12 form-horizontal" style="display:none;" id="tab_filtro">      
						
					<h4>Filtros</h4>	
					<hr style="padding: 0px; margin: 15px;"/>					

					<div  class="row">
				
							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<!--Tipos de factura -->
							<div class="col-xs-12 col-sm-6 col-md-2">
							    
									<label for="id_factura_historicos" class="col-sm-3 col-md-12">Tipo</label>
									<div class="col-sm-9 col-md-12">
									    			
													<select name="id_factura_historicos" vista="entrada" id="id_factura_historicos" class="form-control">
															<option value="0">Todos</option>	
															<?php foreach ( $facturas as $factura ){ ?>
																		<option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
															<?php } ?>
														<!--rol de usuario -->
													</select>
										    

									</div>
							</div>	



							<div id="estatus_id" class="col-xs-12 col-sm-6 col-md-2">
								<div class="form-group">
									<label for="estatus" class="col-sm-12 col-md-12">Estatus</label>
									<div class="col-sm-12 col-md-12">
										<select name="id_estatuss_historicos" vista="historico_entrada" id="id_estatuss_historicos" class="form-control ttip" title="Seleccione el estatus del producto a consultar.">
												<?php foreach ( $estatuss as $estatus ){ ?>
														<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
												<?php } ?>
										</select>
									</div>
								</div>
							</div>							




							<!--Tipos de almacen -->
							<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
									    <?php if  ( $this->session->userdata( 'id_perfil' ) != 2  ) { ?>
											 <fieldset class="disabledme">				
										<?php } else { ?>	
											 <fieldset class="disabledme" disabled>
										<?php } ?>	

												<select name="id_almacen_historicos" vista="entrada" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
												
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

							<!--Rango de fecha -->
							<div class="col-xs-12 col-sm-6 col-md-3">
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco_historicos" vista="entrada" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
			                </div>

		            </div>     

		            <hr style="padding: 0px; margin: 15px;"/>					
				</div>

<!-- Hasta aqui el filtro	-->







			<!--tabla-->	

				<div class="col-md-12">
					
					<div class="table-responsive">

						<section>
							<table id="tabla_historico_entrada" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha  </th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>

											<th class="text-center " width="20%"><strong>Detalles</strong></th>

											

									</tr>
								</thead>
							</table>
						</section>
					</div>


													<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">			
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes por Página</b>
														</div>	

														<div class="col-sm-3 col-md-2">	
															<span id="subtotal"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="iva"></span>			
														</div>				
														<div class="col-sm-3 col-md-2">	
															<span id="total"></span>			
														</div>	
													</div>			

													<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">	
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes Totales</b>			
														</div>									

														<div class="col-sm-3 col-md-2">	
															<span id="total_subtotal"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="total_iva"></span>			
														</div>					

														<div class="col-sm-3 col-md-2">	
															<span id="total_total"></span>			
														</div>	

													</div>				
					

					
				</div>

			<!--fin tabla-->					


					
			<div class="row">

				
				<div class="col-sm-8 col-md-9"></div>
				<div class="col-sm-4 col-md-3">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i> Regresar</a>
				</div>
			</div>
					

			</div>
		</div>




		

	

</div>
</div>












<?php $this->load->view( 'footer' ); ?>