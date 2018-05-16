<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   


 $id_almacen=$this->session->userdata('id_almacen');
	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

	$config_impresion= $this->session->userdata('config_impresion');

?>


<div class="container margenes">
	<div class="panel panel-primary">
		<div id="label_reporte" class="panel-heading">Reportes de Existencias</div>
			<div class="container">	
				<br>

				<!-- <div class="col-sm-12 col-md-12" style="margin-bottom:10px"> -->
				<div class="row">
					<?php if ( ( $el_perfil == 1 ) || (in_array(9, $coleccion_id_operaciones)) ) { ?>					
						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button id="existencia_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos con existencia en almacén.">Existencias</button>							
						</div>						
						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button id="salida_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar las salidas de productos.">Salidas</button>							
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="apartado_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar sólo productos con apartado individual y/o confirmado.">Apartados</button>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="cero_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos agotados.">Existencias Cero</button>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="baja_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar productos con existencia por debajo del mínimo.">Existencias Bajas</button>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="top_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar los productos con mayor.">TOP 10</button>
						</div>

						<!--  		-->

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="entrada_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar histórico de entradas.">Entrada</button>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<button  id="devolucion_reporte" type="button" class="btn btn-danger btn-block ttip" title="Mostrar histórico de productos devueltos.">Devolución</button>
						</div>


						<!--  		-->
						
						<div class="col-xs-12 col-sm-3 col-md-2">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>listado_notas"  
								type="button" class="btn btn-info btn-block ttip" title="Ver listado de entradas.">Hist. Entradas
							</a>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>listado_salidas" type="button" class="btn btn-info btn-block ttip" title="Ver listado de salidas.">
								Hist. Salidas
							</a>
						</div>

						<div class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>listado_devolucion"  
								type="button" class="btn btn-info btn-block ttip" title="Ver listado de salidas devueltas.">Hist. Devolución
							</a>
						</div>

						<div id="disponibilidad"  class="col-xs-12 col-sm-3 col-md-2 marginbuttom">
									<button  id="ver_filtro" type="button" class="btn btn-success btn-block ttip" title="Mostrar u ocultar filtros.">Filtros</button>
						</div>
					<?php } ?>		

					<?php if ( ( $el_perfil == 1 ) || (in_array(61, $coleccion_id_operaciones)) ) { ?>						

						<div class="col-xs-12 col-sm-3 col-md-2">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>listado_traspaso" type="button" class="btn btn-info btn-block ttip" title="Ver listado de traspasos.">
								Hist. Traspasos
							</a>
						</div>

					    <div class="col-xs-12 col-sm-3 col-md-2">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>costo_inventario" type="button" class="btn btn-info btn-block ttip" title="Ver Costo de Inventario Real.">
								Costo de Inventario
							</a>
						</div>
					    <div class="col-xs-12 col-sm-3 col-md-2">
							<label for="descripcion" class="col-sm-12 col-md-12"></label>
							<a href="<?php echo base_url(); ?>costo_rollo" type="button" class="btn btn-info btn-block ttip" title="Ver Costo en función del histórico.">
								Costo Histórico
							</a>
						</div>
					<?php } ?>



					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_proveedor"  
							type="button" class="btn btn-warning btn-block ttip" title="Listado de proveedores." style="font-size:13px;">Consultas por Proveedor
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_producto" type="button" class="btn btn-warning btn-block ttip" title="Listado de productos." style="font-size:13px;">
							Consultas por Productos
						</a>
					</div>

					<div class="col-xs-12 col-sm-3 col-md-2">
						<label for="descripcion" class="col-sm-12 col-md-12"></label>
						<a href="<?php echo base_url(); ?>consulta_totales" type="button" class="btn btn-warning btn-block ttip" title="Totales de productos." style="font-size:13px;">
							Reportes Totales
						</a>
					</div>



				</div>


<!-- Aqui comienza filtro	-->

				<div class="col-md-12 form-horizontal" style="display:none;" id="tab_filtro">      
						
						<h4>Filtros</h4>	
						<hr style="padding: 0px; margin: 15px;"/>					

					

					<div  class="row">



<!--Tipos de medida -->
					<div class="col-xs-12 col-sm-6 col-md-2 bloq_id_medida" style="display:none;">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Mts/kgs</label>
								<div class="col-sm-12 col-md-12">
									<select name="id_medida_reporte" id="id_medida_reporte" class="form-control">
											<?php foreach ( $medidas as $medida ){ ?>
													<option value="<?php echo $medida->id; ?>"><?php echo $medida->medida; ?></option>
											<?php } ?>
									</select>
								</div>
							</div>
					</div>				

<!--Tipos de factura -->
				 <div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( ($el_perfil==4 ) ? 'none':'block').'"'; ?>>
					    
							<label for="id_factura_reporte" class="col-sm-3 col-md-12">Tipo</label>
							<div class="col-sm-9 col-md-12">
							    			
											<select name="id_factura_reporte" id="id_factura_reporte" class="form-control">
													<option value="0">Todos</option>	
													<?php foreach ( $facturas as $factura ){ ?>
																<option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>

												<!--	<option value="3">Surtidos</option>	
												rol de usuario -->
											</select>
								    

							</div>
					</div>	

							<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">
							
							<div id="almacen_id" class="col-xs-12 col-sm-6 col-md-2"  <?php echo 'style="display:'.( ( ($config_almacen->activo==0) && ($el_perfil==2)   && (!(in_array(103, $coleccion_id_operaciones)))   ) ? 'none':'block').'"'; ?>>
								<div class="form-group">
									<label for="almacen" class="col-sm-12 col-md-12">Almacén</label>
									<div class="col-sm-12 col-md-12">
				
							    <?php if  (! ( ( $el_perfil == 2 ) &&  (!(in_array(103, $coleccion_id_operaciones))) )   )

							     { ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	

										<select name="id_almacen_reporte" id="id_almacen_reporte" class="form-control ttip" title="Seleccione el almacén del producto a consultar.">
										
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

							<div id="fecha_id">

								<div id="estatus_id" class="col-xs-12 col-sm-6 col-md-2">
									<div class="form-group">
										<label for="estatus" class="col-sm-12 col-md-12">Estatus</label>
										<div class="col-sm-12 col-md-12">
											<select name="id_estatuss" id="id_estatuss" class="form-control ttip" title="Seleccione el estatus del producto a consultar.">
													<?php foreach ( $estatuss as $estatus ){ ?>
															<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
													<?php } ?>
											</select>
										</div>
									</div>
								</div>							

														
								<div id="proveedor_id" class="col-xs-12 col-sm-6 col-md-3">

											<div class="form-group">
												<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
												<div class="col-sm-12 col-md-12">
													 <input  type="text" name="editar_proveedor_reporte" id="editar_proveedor_reporte" idproveedor="1" class="form-control buscar_proveedor_reporte ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
												</div>
											</div>
									
								</div>		

								<div id="fecha_id" class="col-xs-12 col-sm-6 col-md-2">
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_reporte ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
			                     </div>


			                     
								
									<div class="col-xs-12 col-sm-6 col-md-1" id="bloque_factura" <?php echo 'style="display:'.(($configuracion->activo==0) ? 'none':'block' ).'"'; ?> >
									
											<div class="form-group">
											<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Factura</label>
												<div class="col-sm-12 col-md-12">
													<input type="text" class="form-control" id="factura_reporte" name="factura_reporte" placeholder="Fact.">
												</div>
											</div>
									</div>	
								
							</div>	


		            </div>     
							

		          

					<div id="example2" class="row">
		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto_rep" id="producto_rep" dependencia="color_rep" nombre="un color">
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

		                  <div class="col-xs-12 col-sm-6 col-md-2">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Color</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color_rep" id="color_rep"  dependencia="composicion_rep" nombre="una composición" style="padding-right:0px">
			                            <option value="0">Seleccione un color</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion_rep" id="composicion_rep" dependencia="calidad_rep" nombre="una calidad" style="padding-right:0px">
			                            <option value="0">Seleccione una composición</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>



		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Calidad</label>
								<div class="col-sm-12 col-md-12">
			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad_rep" id="calidad_rep" dependencia="" nombre="" style="padding-right:0px">
			                            <option value="0">Seleccione una calidad</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>

		            </div>     


		            <hr style="padding: 0px; margin: 15px;"/>					
				</div>

<!-- Hasta aqui el filtro	-->

				<hr style="padding: 0px; margin: 8px;"/>					

				<div class="row">	
					<div class="col-md-12">	

							<fieldset id="disa_reportes" disabled>
		  						<div class="col-sm-3 col-md-3">
									<button id="limpiar_filtro_rep" type="button" class="btn btn-success">
										Limpiar filtros
									</button>
								</div>							
								<div class="col-sm-3 col-md-3"></div>
								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="impresion_rapida" type="button" class="btn btn-success btn-block">Imprimir</a>
								</div>


								<div class="col-sm-3 col-md-3 marginbuttom">
									<a id="exportar_reportes" type="button" class="btn btn-success btn-block">Exportar</a>
								</div>

								<div class="col-sm-3 col-md-3 marginbuttom" 
									<?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>
								>
									<a id="impresion_reporte" type="button" class="btn btn-success btn-block">PDF</a>
								</div>


							</fieldset>						
					
						<div class="table-responsive">

                           <div class="col-md-4 leyenda_devolucion"  style="display: none;"><span> Productos Devueltos</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>

                           <div class="col-md-7">		
	                           <div class="col-md-4 leyen_home" style="display: block;" ><span> Apartados</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Devoluciones</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
							   <div class="col-md-4 leyen_home"  style="display: block;"><span> Traspasos en proceso</span><div style="border: 1px solid black; margin-right: 15px;float:left;background-color:#fcf8e3;width:15px;height:15px;"></div> </div>
						   </div>

							<div class="col-md-8">		
		                           <div class="col-md-4 leyenda"  style="display: none;"><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
		                           <div class="col-md-4 leyenda" style="display: none;" ><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
								   <div class="col-md-4 leyenda" style="display: none;" ><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
							</div>
							<br/>	
						   <hr style="padding: 0px; margin: 8px;"/>					
						   	<div class="notif-bot-pedidos"></div>
							

							<br>
							<section>
								<table id="tabla_reporte" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
									<!--
	
									-->
							

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
						<span id="pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="metro"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="kg" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									
					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_metro"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_kg" ></span>				
					</div>	
				</div>






				
				



					<input type="hidden" id="referencia" name="referencia" value="">
					<input type="hidden" id="codigo_original" name="codigo_original" value="">

						

						<div class="row">

								<input type="hidden" id="botones" name="botones" value="existencia">
						</div>
						<br><br>

						
						<div class="row">
							<div class="col-sm-8 col-md-8"></div>
							<div class="col-sm-4 col-md-4">
								<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
							</div>
						</div>
						<br/>
				


			</div>

	</div>

</div>


<?php $this->load->view( 'footer' ); ?>