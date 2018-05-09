<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php
  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
      	$otro_retorno='listado_notas';
    }
    $id_almacen=$this->session->userdata('id_almacen');

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );
	$config_impresion= $this->session->userdata('config_impresion');


?>   
					<!--<div class="col-md-2" > </div>
	                <div class="col-md-2" >
	                		<span> Productos Devueltos</span>
	                			<div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> 
                	</div>-->
<div class="container">
	<div class="row">
	    <div class="col-md-6" >
	    		<span> Productos Devueltos</span>
	    			<div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> 
		</div>
	</div>
</div>

<div class="container margenes">

		<div class="container margenes">
			<div class="panel panel-primary">
			<div class="panel-heading">Gestión de Cuentas por pagar</div>
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
							
							
							<!--Tipos de factura -->
							<div class="col-xs-12 col-sm-6 col-md-2">
							    
								<label for="id_factura_historicos" class="col-sm-3 col-md-12">Tipo</label>
								<div class="col-sm-9 col-md-12">
								    			
									<select name="id_factura_historicos" vista="cuentas" id="id_factura_historicos" class="form-control">

										<?php if ( ( $el_perfil == 1 ) || ((in_array(29, $coleccion_id_operaciones)) 
										 		&& (in_array(30, $coleccion_id_operaciones)) )														 ) { ?>
											<option value="0">Todos</option>
										<?php } ?>	

										<?php foreach ( $facturas as $factura ){ ?>
										<?php if ( ( $el_perfil == 1 ) || ( (in_array(29, $coleccion_id_operaciones) && ($factura->id==1) ) 	|| (in_array(30, $coleccion_id_operaciones) && ($factura->id==2) ) )) { ?>
										   <option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
											<?php } ?>
											   
										<?php } ?>
										<!--rol de usuario -->
									</select>
								</div>
							</div>	


							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
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



												<select name="id_almacen_historicos" vista="cuentas" id="id_almacen_historicos" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
												
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
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha de creación</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco_historicos" vista="cuentas" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
			                </div>

							<div id="proveedor_id" class="col-xs-12 col-sm-6 col-md-3">

											<div class="form-group">
												<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
												<div class="col-sm-12 col-md-12">
													 <input  type="text" name="editar_proveedor_historico" id="editar_proveedor_historico" vista="cuentas" idproveedor="1" class="form-control buscar_proveedor_historico ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
												</div>
											</div>
									
								</div>		


		            </div>     

		            <hr style="padding: 0px; margin: 15px;"/>					
				</div>

<!-- Hasta aqui el filtro	-->	                     



				<div class="col-md-12">
					
					<div class="table-responsive">

						<h4>Vencidas</h4>	
						<hr>	

							<fieldset id="disa_vencidas" disabled class="row">
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="impresion_vencidas" type="button" class="btn btn-success btn-block impresion_ctas" tipo="vencidas">Imprimir</a>
								</div>

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="exportar_vencidas" type="button" class="btn btn-success btn-block exportar_ctas" tipo="vencidas" >Exportar</a>
								</div>
								<!--
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_especificas" tipo="vencidas">Reporte de Pago General</a>
								</div>								

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_detalladas" tipo="vencidas">Historico de Pagos por proveedor</a>
								</div>								
								-->


								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_antiguedad_rapida" tipo="vencidas">Antiguedad</a>
								</div>								

								<div class="col-sm-3 col-md-3 marginbuttom"  <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
									<a type="button" class="btn btn-success btn-block impresion_ctas_antiguedad" tipo="vencidas">PDF Antiguedad</a>
								</div>							


			                </fieldset>	
							
							
								<!--Rango de fecha -->
								<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-3">
										<label id="label_proveedor" for="descripcion">Rango de fecha vencida</label>
										<div class="input-prepend input-group  form-group">
				                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
											<input id="foco_historicos" tipo="vencidas" vista="ctas_vencida" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
										</div>	
				                </div>
				                </div>


						<section>
							<table id="tabla_ctas_vencidas" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha Creación </th>
											<th class="text-center cursora" width="10%">Fecha Vencida</th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<th class="text-center " width="20%"><strong>Días Vencidos</strong></th>
											<th class="text-center " width="20%"><strong>Monto a Pagar</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>
											
													<div class="row bloque_totales">						
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

													<div class="row bloque_totales">		
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
					
					<hr style="padding: 0px; margin: 15px;"/>					
					<div class="table-responsive">
						<h4>Por Pagar</h4>	
						<hr>	

							<fieldset id="disa_xpagar" disabled class="row">
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="impresion_ctasxpagar" type="button" class="btn btn-success btn-block impresion_ctas" tipo="xpagar">Imprimir</a>
								</div>

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="exportar_ctasxpagar" type="button" class="btn btn-success btn-block exportar_ctas" tipo="xpagar" >Exportar</a>
								</div>

								<div class="col-sm-3 col-md-3 marginbuttom" >
									<a type="button" class="btn btn-success btn-block impresion_ctas_especificas_rapida" tipo="xpagar">Reporte de Pago General</a>
								</div>								

								<div class="col-sm-3 col-md-3 marginbuttom"  <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
									<a type="button" class="btn btn-success btn-block impresion_ctas_especificas" tipo="xpagar">PDF Reporte de Pago General</a>
								</div>								

								<!--
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_detalladas" tipo="xpagar">Historico de Pagos por proveedor</a>
								</div>						

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_antiguedad" tipo="xpagar">Antiguedad</a>
								</div>								
								-->
		

							</fieldset>			

								<!--Rango de fecha -->
								<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-3">
										<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha de vencimiento</label>
										<div class="input-prepend input-group  form-group">
				                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
											<input id="foco_historicos" tipo="xpagar" vista="ctas_ctasxpagar" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
										</div>	
				                </div>	
				                </div>							

							

						<section>
							<table id="tabla_ctasxpagar" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha Creación </th>
											<th class="text-center cursora" width="10%">Fecha Vencimiento</th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<th class="text-center " width="20%"><strong>Días por Vencer</strong></th>
											<th class="text-center " width="20%"><strong>Monto a Pagar</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

					</div>

													<div class="row bloque_totales">						
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes por Página</b>
														</div>	

														<div class="col-sm-3 col-md-2">	
															<span id="subtotal2"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="iva2"></span>			
														</div>				
														<div class="col-sm-3 col-md-2">	
															<span id="total2"></span>			
														</div>	
													</div>			

													<div class="row bloque_totales">		
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes Totales</b>			
														</div>									

														<div class="col-sm-3 col-md-2">	
															<span id="total_subtotal2"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="total_iva2"></span>			
														</div>					

														<div class="col-sm-3 col-md-2">	
															<span id="total_total2"></span>			
														</div>	

													</div>							

				<hr style="padding: 0px; margin: 15px;"/>					
					<div class="table-responsive">
						<h4>Pagadas</h4>	
						<hr>	


							<fieldset id="disa_pagadas" disabled class="row">
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="impresion_pagadas" type="button" class="btn btn-success btn-block impresion_ctas" tipo="pagadas">Imprimir</a>
								</div>

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="exportar_pagadas" type="button" class="btn btn-success btn-block exportar_ctas" tipo="pagadas">Exportar</a>
								</div>
								<!--
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_especificas" tipo="pagadas">Reporte de Pago General</a>
								</div>	
								-->							

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a type="button" class="btn btn-success btn-block impresion_ctas_detalladas_rapida" tipo="pagadas">Historico de Pagos por proveedor</a>
								</div>									
								<div class="col-sm-3 col-md-3 marginbuttom"  <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
									<a type="button" class="btn btn-success btn-block impresion_ctas_detalladas" tipo="pagadas">PDF Historico de Pagos por proveedor</a>
								</div>									

							</fieldset>			
								<!--Rango de fecha -->
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-3">
										<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha pagadas</label>
										<div class="input-prepend input-group  form-group">
					                       	<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
												<input id="foco_historicos" tipo="pagadas" vista="ctas_pagadas" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_historicos ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
										</div>	
					                </div>	
					            </div>	

							<!--<div class="col-md-7">		
							   
							   <div class="col-md-5 leyen_home"  style="display: block;"><span> Recargos o Descuentos</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#fcf8e3;width:15px;height:15px;"></div> </div>


							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Exceso de pago</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div> </div>
						   </div>-->
						   		 <div class="row">
																			   
									   <div class="col-md-3 leyen_home"  style="display: block;"><span> Recargos o Descuentos</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#fcf8e3;width:15px;height:15px;"></div> </div>
									   <div class="col-md-3 leyen_home"  style="display: block;"><span> Exceso de pago</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div> </div>
								   
						   		</div>

							<br>						


						<section>
							<table id="tabla_ctas_pagadas" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
								<thead>
									<tr>
											<th class="text-center cursora" width="10%">Movimiento  </th>
											<th class="text-center cursora" width="10%">Tipo Pago  </th>
											<th class="text-center cursora" width="5%">Almacén  </th>
											<th class="text-center cursora" width="35%">Proveedor  </th>
											
											<th class="text-center cursora" width="10%">Fecha Creación </th>
											<th class="text-center cursora" width="10%">Fecha Pagada</th>
											<th class="text-center cursora" width="10%">Factura  </th>
											
											<th class="text-center cursora" width="10%">Subtotal  </th>
											<th class="text-center cursora" width="10%">IVA  </th>
											<th class="text-center cursora" width="10%">Total  </th>
											<!-- <th class="text-center " width="20%"><strong>Días</strong></th> -->
											<th class="text-center " width="20%"><strong>Monto Pagado</strong></th>
											<th class="text-center " width="20%"><strong>Detalles</strong></th>
									</tr>
								</thead>
							</table>
						</section>

												</div>



													<div class="row bloque_totales">						
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes por Página</b>
														</div>	

														<div class="col-sm-3 col-md-2">	
															<span id="subtotal3"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="iva3"></span>			
														</div>				
														<div class="col-sm-3 col-md-2">	
															<span id="total3"></span>			
														</div>	
													</div>			

													<div class="row bloque_totales">		
														<div class="col-sm-0 col-md-2">	
														  
														</div>	
														<div class="col-sm-3 col-md-2">	
														  <b>Importes Totales</b>			
														</div>									

														<div class="col-sm-3 col-md-2">	
															<span id="total_subtotal3"></span>			
														</div>	
														<div class="col-sm-3 col-md-2">	
															<span id="total_iva3"></span>			
														</div>					

														<div class="col-sm-3 col-md-2">	
															<span id="total_total3"></span>			
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