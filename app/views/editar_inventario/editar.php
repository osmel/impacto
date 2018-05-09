<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="";
    }

  $fecha_hoy = date('j-m-Y');

   $id_almacen=$this->session->userdata('id_almacen');

$attr = array('class' => 'form-horizontal', 'id'=>'form_editar_inventario','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
echo form_open('validar_edicion_producto', $attr);
?>		
<div class="container">

	<br>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h4>Editar Producto Inventario</h4>
			<hr>
		</div>
		

	<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">

					<div class="col-xs-12 col-sm-12 col-md-3">
					    
							<label for="id_almacen" class="control-label">Almacén</label>
							<div>
							    <!--Los administradores o con permisos de entrada 
							    							****2121 sistema.js por ajax deshabilita sino hay en la regilla 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(3, $coleccion_id_operaciones)) )  && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen" disabled>				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" disabled>
								<?php } ?>	
											<select name="id_almacen" id="id_almacen" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															   
																
																if  (($almacen->id_almacen==$id_almacen) )
																 {$seleccionado='selected';} else {$seleccionado='';}
			
															?>
																<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?> ><?php echo $almacen->almacen; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>				

		<div class="col-xs-12 col-sm-6 col-md-3">
			<fieldset disabled>
				<div class="form-group">
					<label for="fecha" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">Fecha Entrada</label>
					<div class="col-sm-12 col-md-12">
						<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">

					</div>
				</div>
			</fieldset>	
		</div>

		<div style="display:none;" class="Bueno col-xs-12 col-sm-6 col-md-3">
			<fieldset disabled>
				<div class="form-group">
					<label for="movimiento" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<div class="col-sm-12 col-md-6">
						<input type="text" value="" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
					</div>

					<div class="col-sm-12 col-md-6">
						<input type="text" value="" class="form-control" id="movimiento_unico" name="movimiento_unico" placeholder="No. Movimiento">
					</div>

				</div>
			</fieldset>			
		</div>
	</div>

	<!--producto proveedor y factura -->
	<div class="row">
		<input type="hidden" id="oculto_producto" name="oculto_producto" value="no" color="" composicion="" calidad="">
		<div class="col-xs-12 col-sm-12 col-md-3">
		
			<div class="form-group">
				<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
				<div class="col-sm-12 col-md-12">
							<input  type="text" name="editar_prod_inven" id="editar_prod_inven" idprodinven="1" class="form-control buscar_prod_inven ttip" title="Campo predictivo. Escanee o escriba y seleccione el número de producto para devolución." autocomplete="off" spellcheck="false" placeholder="Buscar Código de Producto...">
				</div>
			</div>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-3">
				<fieldset disabled>
						<div class="form-group">
							<label for="proveedor" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">Proveedor</label>
							<div class="col-sm-12 col-md-12">
								<input type="text" value="" class="form-control" id="proveedor" name="proveedor" placeholder="Proveedor">
							</div>
						</div>
				</fieldset>			
		</div>

		
		
		<?php if (($configuracion->activo==1)) {  ?> 
			<div class="col-xs-12 col-sm-12 col-md-2">
					<fieldset class="disabledme" >		 <!-- disabled-->					

					<div class="form-group">
						<label for="factura" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">Factura/Remisión</label>
						<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control" id="factura" name="factura" placeholder="Factura">
						</div>
					</div>
				
					</fieldset>							
				
			
			</div>
		<?php }  ?> 	


		<div class="col-xs-12 col-sm-6 col-md-2">
			<div class="form-group">
				<label for="descripcion" class="col-sm-12 col-md-12">Tipo de Pago</label>
				<div class="col-sm-12 col-md-12">
					<fieldset disabled>	
						<select name="id_tipo_pago" id="id_tipo_pago" class="form-control">
								<?php foreach ( $pagos as $pago ){ ?>
										<option value="<?php echo $pago->id; ?>"><?php echo $pago->tipo_pago; ?></option>
								<?php } ?>
						</select>
					</fieldset>			
				</div>
			</div>
		</div>	


		<div class="col-xs-12 col-sm-6 col-md-2">
			<div class="form-group">
				<label for="descripcion" class="col-sm-12 col-md-12">Tipo</label>
				<div class="col-sm-12 col-md-12">
					<fieldset disabled>	
						<select name="id_factura" id="id_factura" class="form-control">
								<?php foreach ( $facturas as $factura ){ ?>
										<option value="<?php echo $factura->id; ?>"><?php echo $factura->tipo_factura; ?></option>
								<?php } ?>
						</select>
					</fieldset>			
				</div>
			</div>
		</div>	

	</div>

	<!-- todos los componentes del nuevo producto -->
	<div class="row">
		<div class="container ">
			<div class="panel panel-primary">
				<div class="panel-heading">Nuevo Producto</div>
				<div class="panel-body">


					<div class="row">
						
			                  <div class="col-xs-12 col-sm-6 col-md-4">
			                     <div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
									<div class="col-sm-12 col-md-12">
										<fieldset disabled>	
				                          <select class="col-sm-12 col-md-12 form-control" name="producto" id="producto" dependencia="color" nombre="color" style="font-size:12px;">
				                            <option value="">Selecciona un producto</option>
				                            <?php if($productos){ ?>
				                              <?php foreach($productos as $producto){ ?>
				                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>"><?php echo htmlspecialchars($producto->descripcion); ?></option>
				                              <?php } ?>
				                            <?php } ?>
				                          </select>
				                        </fieldset>  
			                        </div>  
			                          
			                     </div>
			                  </div>	
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label id="label_color" class="col-sm-12 col-md-12">Color</label>
								<div class="col-sm-12 col-md-12 ttip" title="Campo dependiente. Seleccione primero un PRODUCTO.">
									<fieldset disabled>	
				                          <select class="col-sm-12 col-md-12 form-control" name="color" id="color"  dependencia="composicion" nombre="composición" style="padding-right:0px;font-size:12px;">
				                            <option value="0">Selecciona un color</option>
				                            <option value="1">Selecciona un color</option>
				                            <option value="2">Selecciona un color</option>
				                          </select>
				                    </fieldset>	      
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div class="col-sm-12 col-md-12">
									<fieldset disabled>	
				                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Seleccione primero un COLOR." name="composicion" id="composicion" dependencia="calidad" nombre="calidad" style="padding-right:0px;font-size:12px;">
				                            <option value="0">Selecciona una composición</option>
				                          </select>
			                         </fieldset>	 
		                        </div>  
		                          
		                     </div>
		                  </div>



		                  <div class="col-xs-12 col-sm-6 col-md-2">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Calidad</label>
								<div class="col-sm-12 col-md-12 ttip" title="Campo dependiente. Seleccione primero una COMPOSICIÓN.">
									<fieldset disabled>	
				                          <select class="col-sm-12 col-md-12 form-control" name="calidad" id="calidad" dependencia="" nombre="" style="padding-right:0px;font-size:12px;">
				                            <option value="0">Seleccione una calidad</option>
				                          </select>
				                    </fieldset>	       
		                        </div>  
		                          
		                     </div>
		                  </div>
		            </div>      


							<input type="hidden" id="referencia" name="referencia" value="">
							<input type="hidden" id="codigo_original" name="codigo_original" value="">



						<!--2da linea -->
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="peso_real" class="col-sm-12 col-md-12">Peso Real</label>
								<div class="col-sm-12 col-md-12">
									<input disabled type="text" class="form-control ttip peso_real" title="Números y puntos decimales." restriccion="decimal" id="peso_real" name="peso_real" placeholder="No. de kgs">
								</div>
							</div>
						</div>				

						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="cantidad_um" class="col-sm-12 col-md-12">Cantidad</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control ttip" title="Números y puntos decimales." restriccion="decimal" id="cantidad_um" name="cantidad_um" placeholder="No. de mts o kgs">
								</div>
							</div>
						</div>				

						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Mts/kgs</label>
								<div class="col-sm-12 col-md-12">
									<select name="id_medida" id="id_medida" class="form-control">
											<?php foreach ( $medidas as $medida ){ ?>
													<option value="<?php echo $medida->id; ?>"><?php echo $medida->medida; ?></option>
											<?php } ?>
									</select>
								</div>
							</div>
						</div>		



						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="ancho" class="col-sm-12 col-md-12">Ancho</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control ttip" title="Números y puntos decimales." restriccion="decimal" id="ancho" name="ancho" placeholder="Ancho">

								</div>
							</div>
						</div>	

						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="precio" class="col-sm-12 col-md-12">Precio</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control ttip" title="Números y puntos decimales." restriccion="decimal" id="precio"  name="precio" placeholder="Precio">

								</div>
							</div>
						</div>	

						<div class="col-xs-12 col-sm-6 col-md-2">
							<fieldset disabled>
								<div class="form-group">
									<label for="iva" class="col-sm-12 col-md-12">IVA</label>
									<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control ttip" title="Números y puntos decimales." restriccion="decimal" id="iva"  name="iva" placeholder="IVA">

									</div>
								</div>
							</fieldset>
						</div>	


					</div>
						<!--3ta linea -->

					<div class="row">	
							<div class="col-xs-12 col-sm-6 col-md-3">
								<div class="col-xs-12 col-sm-12 col-md-12">				
									<fieldset disabled>
										<div class="form-group">
												<label for="codigo" class="col-sm-12 col-md-12"><b style="color:red;">Cód: </b>
												<span id="codigo_contable"></span>
												</label>
														<input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código">
										</div>
									</fieldset>		
								</div>	
								
								<div class="col-xs-12 col-sm-12 col-md-12">				
									<fieldset disabled>	
										<div class="form-group">
											<label for="num_partida" class="col-sm-12 col-md-12">No. de Partida</label>
											
												<input type="text" class="form-control" id="num_partida" name="num_partida" placeholder="No. de Partida">
										</div>
									</fieldset>		
								</div>	

							</div>	
							

						<div class="col-xs-12 col-sm-6 col-md-5">
							<div class="form-group">
								<label for="comentario" class="col-sm-12 col-md-12">Reportes de Cambios</label>
								<div class="col-sm-12 col-md-12">
									<textarea class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"></textarea>
								</div>
							</div>
						</div>					

							

							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Lote</label>
									<div class="col-sm-12 col-md-12">
										<fieldset disabled>	
											<select name="id_lote" id="id_lote" class="form-control">
													<?php foreach ( $lotes as $lote ){ ?>
															<option value="<?php echo $lote->id; ?>"><?php echo $lote->lote; ?></option>
													<?php } ?>
											</select>
										</fieldset>			
									</div>
								</div>
							</div>	

							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Estatus</label>
									<div class="col-sm-12 col-md-12">
										<fieldset disabled>	
											<select name="id_estatus" id="id_estatus" class="form-control">
													<?php foreach ( $estatuss as $estatus ){ ?>
															<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
													<?php } ?>
											</select>
										</fieldset>		
									</div>
								</div>
							</div>							



							
									
					</div>

					<br>				

					<div class="row">
					
							<div class="col-sm-4 col-md-<?php echo ( ( $this->session->userdata( 'id_perfil' ) != 2  ) ? 4:8 ); ?>"></div>	
							
							<div class="col-sm-4 col-md-4 marginbuttom">
								<a id="impresion" type="button" class="btn btn-success btn-block">Imprimir Etiqueta</a>
							</div>
						    
								<?php if  ( $this->session->userdata( 'id_perfil' ) != 2  ) { ?>
									 	<div class="col-sm-4 col-md-4">
											<input type="submit" class="btn btn-success btn-block" value="Cambiar caracteristicas"/>
										</div>

								<?php }  ?>	
								


					</div>

					
	  					</div>

	  				</div>

	  						
			</div> <!-- fin del container-->
		</div> <!-- fin del row-->
	
    <!--fin de todos los componentes del nuevo producto -->



				<?php echo form_close(); ?>

				<div class="row">					
					<div class="col-md-12">		
						
						<h4>Histórico de Ediciones</h4>	
						<hr style="padding: 0px; margin: 15px;"/>					
						<div class="table-responsive">
							<section>
								<table id="tabla_cambio" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>Código</th>
											<th>Descripción</th>
											<th>Color</th>
											<th>Composición</th>
											<th>Calidad</th>
											<th>Pieza</th>
											<th>Control de Edición</th>
											<th>Comentario</th>
											<th>No. de Partida</th>

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
</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	
<?php $this->load->view('footer'); ?>