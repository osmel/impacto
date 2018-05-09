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


$attr = array('class' => 'form-horizontal', 'id'=>'form_entradas','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
echo form_open('validar_agregar_producto_compra', $attr);

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

//para determinar el consecutivo

if ($val_proveedor) {
	 $consecutivo_actual = ( ($val_proveedor->id_factura==1) ? $consecutivo->conse_factura : $consecutivo->conse_remision );
} else {
	$consecutivo_actual = $consecutivo->conse_factura;
}	


?>		
<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_remision+1; ?>">

<input type="hidden" id="id_operacion" name="id_operacion" value="<?php echo $id_operacion; ?>">

<div class="container">

	<br>



	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h4>Registro de Entradas </h4>
			<hr>
		</div>

		<input type="hidden" id="oculto_producto" name="oculto_producto" value="" color="" composicion="" calidad="">

		<div style="display:none;" class="col-sm-6 col-md-6">
			
 			   
 			   <div class="col-xs-6 col-sm-3 col-md-1">
					    	<div class="form-group">
								<input type="text" title="1" value="<?php echo $conse_general->c1+(!$val_proveedor); ?>" class="form-control ttip" id="c1" name="c1">
							</div>	
				</div>	

				<div class="col-xs-6 col-sm-3 col-md-1">
					    	<div class="form-group">
								<input type="text" title="2" value="<?php echo $conse_general->c2+(!$val_proveedor); ?>" class="form-control ttip" id="c2" name="c2">
							</div>	
				</div>	

 			   <div class="col-xs-6 col-sm-3 col-md-1">
					    	<div class="form-group">
								<input type="text" title="1234" value="<?php echo $conse_general->c1234+(!$val_proveedor); ?>" class="form-control ttip" id="c1234" name="c1234">
							</div>	
				</div>	

				<!--<div class="col-xs-6 col-sm-3 col-md-1" >
					    	<div class="form-group">
								<input type="text" title="234" value="<?php echo $conse_general->c234+(!$val_proveedor); ?>" class="form-control ttip" id="c234" name="c234" style="color:red; ">
							</div>	
				</div>	-->

 			   <div class="col-xs-6 col-sm-3 col-md-1">
					    	<div class="form-group">
								<input type="text" title="34" value="<?php echo $conse_general->c34+(!$val_proveedor); ?>" class="form-control ttip" id="c34" name="c34">
							</div>	
				</div>	


		 	
		</div>


		<div class="col-xs-12 col-sm-2 col-md-2">
				<fieldset class="disabledme">						
					<div class="form-group">
						<label for="id_compra" class="col-sm-12 col-md-12">Compras-Consecutivo</label>
						<div class="col-sm-12 col-md-12">

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>
									<select name="id_compra" id="id_compra" tipo="entrada"  class="form-control">
											<option value="0" prov_nomb="">Selecciona una opción</option> 
												<?php foreach ( $compras as $compra ){ ?>
															<?php 

															$seleccionado='';
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($compra['movimiento']==$val_proveedor->id_compra) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
															<option value="<?php echo $compra['movimiento']; ?>" prov_nomb="<?php echo $compra['nombre']; ?>" almacen="<?php echo $compra['id_almacen']; ?>" <?php echo $seleccionado; ?>> <?php echo $compra['movimiento'].' - '.$compra['almacen']; ?></option>
												<?php } ?>
									</select>
								</fieldset>
						</div>
					</div>
				</fieldset>							
			
		</div>		





		<div class="col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="fecha" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">Fecha</label>
					<div class="col-sm-12 col-md-12">
						<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">

					</div>
				</div>
			</fieldset>	
		</div>

		<div style="display:none;" class="col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="movimiento" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<div class="col-sm-6 col-md-6">
						<input type="text" title="Movimiento" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
					</div>
					<div class="col-sm-6 col-md-6">
						<input type="text" title="Movimiento" value="<?php echo $consecutivo->consecutivo+1; ?>" class="form-control" id="movimiento_unico" name="movimiento_unico" placeholder="No. Movimiento">
					</div>


				</div>
			</fieldset>			
		</div>


		<div <div style="display:none;" class="Bueno col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="movimiento" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<div class="col-sm-6 col-md-12">

								<input type="text" title="234" value="<?php echo $conse_general->c234+(!$val_proveedor); ?>" class="form-control ttip" id="c234" name="c234" style="color:red; ">
					</div>								

				</div>
			</fieldset>			
		</div>

	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<?php if ($val_proveedor) { ?>
				<fieldset class="disabledme" disabled>							
			<?php } else { ?>
				<fieldset class="disabledme" disabled>						
			<?php } ?>

					<div class="form-group">
						<label for="descripcion" class="col-sm-12 col-md-12">Proveedor</label>
						


						<div class="col-sm-12 col-md-12">
								<?php if ($val_proveedor) { ?>
									
									<input value="<?php echo htmlspecialchars($val_proveedor->nombre); ?>" type="text" name="editar_proveedor" idproveedor="1" class="form-control buscar_proveedor ttip" title="Campo predictivo. Escriba y seleccione el nombre de un Proveedor." autocomplete="off" spellcheck="false" placeholder="Buscar Proveedor...">
								<?php } else { ?>
									<input  type="text" name="editar_proveedor" idproveedor="1" class="form-control buscar_proveedor ttip" title="Campo predictivo. Escriba y seleccione el nombre de un Proveedor." autocomplete="off" spellcheck="false" placeholder="Buscar Proveedor...">
								<?php } ?>
						</div>
					</div>
			
				</fieldset>							
			
		</div>

<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->
		<?php if (($configuracion->activo==1)) {  ?> 

				<div class="col-xs-12 col-sm-6 col-md-2">
					<?php if ($val_proveedor) { ?>
						<fieldset class="disabledme" disabled>							
					<?php } else { ?>
						<fieldset class="disabledme" >						
					<?php } ?>

						<div class="form-group">
							<label for="factura" class="col-sm-12 col-md-12">Factura/Remisión</label>
							<div class="col-sm-12 col-md-12">
										<?php if ($val_proveedor) { ?>
											<input value="<?php echo htmlspecialchars($val_proveedor->factura); ?>" type="text" class="form-control" id="factura" name="factura" placeholder="Factura">							
										<?php } else { ?>
											<input type="text" class="form-control" id="factura" name="factura" placeholder="Factura">
										<?php } ?>				
							</div>
						</div>
					
						</fieldset>							
				</div>
		<?php }  ?> 		




					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?> >
					    
							<label for="id_almacen" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-10">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(1, $coleccion_id_operaciones)) ) && (!$val_proveedor) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabledme"  disabled>				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	
											<select name="id_almacen" id="id_almacen" tipo="entrada"  class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															   if  (($almacen->id_almacen==$id_almacen) && (!$val_proveedor))
																 {$seleccionado='selected';} else {$seleccionado='';}
																
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($almacen->id_almacen==$val_proveedor->id_almacen) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $almacen->id_almacen; ?>" <?php echo $seleccionado; ?> ><?php echo $almacen->almacen; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		


					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_tipo_pago" class="col-sm-3 col-md-12">Tipo de Pago</label>
							<div class="col-sm-9 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean facturaista 
							    	ENTONCES lista editable -->

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>

											<select name="id_tipo_pago" id="id_tipo_pago" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $pagos as $pago ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($pago->id==$val_proveedor->id_tipo_pago) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $pago->id; ?>" <?php echo $seleccionado; ?> ><?php echo $pago->tipo_pago; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



					<!--Tipos de factura -->
					<div class="col-xs-12 col-sm-6 col-md-2">
					    
							<label for="id_factura" class="col-sm-3 col-md-12">Tipo</label>
							<div class="col-sm-9 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean facturaista 
							    	ENTONCES lista editable -->

								<?php if ($val_proveedor) { ?>
									<fieldset class="disabledme" disabled>							
								<?php } else { ?>
									<fieldset class="disabledme">						
								<?php } ?>

											<select name="id_factura" id="id_factura" tipo="entrada"  class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $facturas as $factura ){ ?>
															<?php 
																if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($factura->id==$val_proveedor->id_factura) {
																			$seleccionado='selected';
																		} else {
																			$seleccionado='';
																		}
																}
															?>
																<option value="<?php echo $factura->id; ?>" <?php echo $seleccionado; ?> ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



				
				


	</div>


	<div class="row">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">Nuevo Producto</div>
				<div class="panel-body">


					<div class="row">

		                  <div class="col-xs-12 col-sm-6 col-md-4">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Producto</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control" name="producto" id="producto" dependencia="color" nombre="un color">
			                            <option value="">Seleccione un producto</option>
			                            <?php if($productos){ ?>
			                              <?php foreach($productos as $producto){ ?>
			                                <option value="<?php echo htmlspecialchars($producto->descripcion); ?>" ><?php echo htmlspecialchars($producto->descripcion); ?></option>
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

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color" id="color"  dependencia="composicion" nombre="una composición" style="padding-right:0px">
			                            <option value="0">Seleccione un color</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		                  
		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Composición</label>
								<div class="col-sm-12 col-md-12">

			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion" id="composicion" dependencia="calidad" nombre="una calidad" style="padding-right:0px">
			                            <option value="0">Seleccione una composición</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>



		                  <div class="col-xs-12 col-sm-6 col-md-3">
		                     <div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Calidad</label>
								<div class="col-sm-12 col-md-12">
			                          <select class="col-sm-12 col-md-12 form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad" id="calidad" dependencia="" nombre="" style="padding-right:0px">
			                            <option value="0">Seleccione una calidad</option>
			                          </select>
		                        </div>  
		                          
		                     </div>
		                  </div>
		            </div>      





							<input type="hidden" id="referencia" name="referencia" value="">

					


						<!--2da linea -->
					<div class="row">

						<div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group">
								<label for="peso_real" class="col-sm-12 col-md-12">Peso Real</label>
								<div class="col-sm-12 col-md-12">
									<input  type="text" class="form-control ttip peso_real" title="Números y puntos decimales." restriccion="decimal" id="peso_real" name="peso_real" placeholder="No. de kgs">
								</div>
							</div>
						</div>	

						<div class="col-xs-12 col-sm-4 col-md-2">
							<div class="form-group">
								<label for="cantidad_um" class="col-sm-12 col-md-12">Cantidad (en m o kg)</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control" restriccion="decimal" id="cantidad_um" name="cantidad_um" placeholder="No. de mts o kgs">
								</div>
							</div>
						</div>				

						<div class="col-xs-12 col-sm-4 col-md-2">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Mts/kgs</label>
								<div class="col-sm-12 col-md-12">
									<select name="id_medida" id="id_medida" tipo="entrada_compra" class="form-control">
											<?php foreach ( $medidas as $medida ){ ?>

													<option <?php  //echo (($medida->id==$compras[0]->id_medida) ? 'selected' : ''); ?> value="<?php echo $medida->id; ?>"><?php echo $medida->medida; ?></option>
											<?php } ?>
									</select>
									<span id="diferencia" style="color:#a55a5a;"></span>
								</div>

							</div>

						</div>		

						<div class="col-xs-12 col-sm-4 col-md-2">
							<div class="form-group">
								<label for="cantidad_royo" class="col-sm-12 col-md-12">Cantidad de Rollos</label>
								<div class="col-sm-12 col-md-12">
									<input type="text" class="form-control ttip" title="Este campo no admite decimales."  restriccion="entero" id="cantidad_royo" name="cantidad_royo" placeholder="Cant. de rollos">
								</div>
							</div>
						</div>				




						<div class="col-xs-12 col-sm-6 col-md-2">
							<fieldset>
								<div class="form-group">
									<label for="ancho" class="col-sm-12 col-md-12">Ancho (cm)</label>
									<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control" id="ancho" restriccion="decimal" name="ancho" placeholder="Ancho">
									</div>
								</div>
							</fieldset>	
						</div>	

						<div class="col-xs-12 col-sm-6 col-md-2">
							<fieldset > <!--disabled-->
								<div class="form-group">
									<label for="precio" class="col-sm-12 col-md-12">Precio</label>
									<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control" id="precio" name="precio" placeholder="Precio">

									</div>
								</div>
							</fieldset>		
						</div>	
					</div>
						<!--3ta linea -->

					<div class="row">	
							<div class="col-sm-3 col-md-3">
								<fieldset disabled>
									<div class="form-group">
										<label for="codigo" class="col-sm-12 col-md-12"><b style="color:red;">Cód: </b>
												<span id="codigo_contable"></span>
										</label>

										
											<div class="col-sm-12 col-md-12">
												<input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código">

											</div>


									</div>
								</fieldset>
						
								<div class="form-group">
									<label for="num_partida" class="col-sm-12 col-md-12">No. de Partida</label>
									<div class="col-sm-12 col-md-12">
										<input type="text" class="form-control" id="num_partida" name="num_partida" placeholder="No. de Partida">
									</div>
								</div>
							
							</div>	



							

						<div class="col-sm-5 col-md-5">
							<div class="form-group">
								<label for="comentario" class="col-sm-12 col-md-12">Comentarios</label>
								<div class="col-sm-12 col-md-12">
									<textarea class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"></textarea>
								</div>
							</div>
						</div>					

							

							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Lote</label>
									<div class="col-sm-12 col-md-12">
										<select name="id_lote" id="id_lote" class="form-control">
												<?php foreach ( $lotes as $lote ){ ?>
														<option value="<?php echo $lote->id; ?>"><?php echo $lote->lote; ?></option>
												<?php } ?>
										</select>
									</div>
								</div>
							</div>	

							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label for="descripcion" class="col-sm-12 col-md-12">Estatus</label>
									<div class="col-sm-12 col-md-12">
										<select name="id_estatus" id="id_estatus" class="form-control">
												<?php foreach ( $estatuss as $estatus ){ ?>
														<option value="<?php echo $estatus->id; ?>"><?php echo $estatus->estatus; ?></option>
												<?php } ?>
										</select>
									</div>
								</div>
							</div>							


	<br>
							<div class="col-sm-4 col-md-4"></div>
										<div class="col-sm-4 col-md-4">
											<input  type="submit" class="btn btn-success btn-block" value="Agregar"/>
										</div>
							</div>
	
	  					</div>

	  				</div>

	  						
			</div>


			<!-- Regilla-->

		</div>




	<?php echo form_close(); ?>


			<div class="row">
				<div class="col-md-12">
					
					<h4>Listado de productos seleccionados para Entrada</h4>	
					<br>			
						<div class="notif-bot-vendedor"></div>
						<div class="table-responsive">	
								<section>
									
									<table id="tabla_productos_compra"	class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
									</table>

								</section>
						</div>
				</div>
			</div>


				<br/>
		
				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-2">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias por Página</b>
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="peso"></span>			
					</div>				
					<div class="col-sm-3 col-md-2">	
						<span id="metro"></span>			
					</div>	
	
					<div class="col-sm-3 col-md-2">	
						<span id="kg" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-2">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									

					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_peso"></span>			
					</div>					

					<div class="col-sm-3 col-md-2">	
						<span id="total_metro"></span>			
					</div>	

					<div class="col-sm-3 col-md-2">	
						<span id="total_kg" ></span>				
					</div>	
				</div>


			<br/>
		
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


			<br>
			<div class="row">

				<div class="col-sm-4 col-md-4"></div>
				<div class="col-sm-4 col-md-4 marginbuttom">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
				</div>

				<div class="col-sm-4 col-md-4">
					<button id="conf_entrada_compra"  type="button" class="btn btn-success btn-block">
						Procesar Entrada
					</button>
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