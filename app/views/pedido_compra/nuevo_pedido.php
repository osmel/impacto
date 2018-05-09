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

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );


?>	

<input type="hidden" id="retorno" name="retorno" value="<?php echo $retorno; ?>">

<div class="container margenes">
<div class="panel panel-primary">
<div class="panel-heading">Detalles de la Orden de Compra</div>
<div class="panel-body">		
	
	<div class="row">		
		<!-- derecha comentarios-->	
	   <div class="col-sm-12 col-md-8">
			
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<fieldset disabled>
					<div class="form-group">
						<label for="fecha" class="ttip" title="Campo informativo, no editable.">Fecha</label>
						<div>
							<input value="<?php echo $fecha_hoy; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
						</div>
					</div>
					</fieldset>	
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<fieldset disabled>
						<div class="form-group">
							<label for="movimiento" class="ttip" title="Campo informativo, no editable.">No. Movimiento</label>
							<div>
								<input type="text" value="<?php echo $consecutivo->consecutivo+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
							</div>
						</div>
					</fieldset>			
				</div>
				
				
					<div class="col-xs-12 col-sm-4 col-md-4" <?php echo 'style="display:'.( (($configuracion->activo==0) ) ? 'none':'block').'"'; ?>>
						<?php if ($val_compra) { ?>
						<fieldset class="disabledme" disabled>							
						<?php } else { ?>
						<fieldset class="disabledme">						
						<?php } ?>
							<div class="form-group">
							<label for="factura">Nro. Control</label>
								<div>
									<?php if ($val_compra) { ?>
									<input value="<?php echo htmlspecialchars($val_compra->factura); ?>" type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Factura">							
									<?php } else { ?>
									<input type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Nro. Control">
									<?php } ?>				
								</div>
							</div>
						</fieldset>	
					</div>
				

					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-4" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
					    
							<label for="id_almacen_compra" class="control-label">Almacén</label>
							<div>
							    <!--Los administradores o con permisos de traspaso 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(39, $coleccion_id_operaciones)) ) && (!$val_compra) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	
											<select name="id_almacen_compra" id="id_almacen_compra" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $almacenes as $almacen ){ ?>
															<?php 
															   if  (($almacen->id_almacen==$id_almacen) && (!$val_compra))
																 {$seleccionado='selected';} else {$seleccionado='';}
																
																if ($val_compra) { //comprobar una vez que ya esten inhabilitados factura
																	 if ($almacen->id_almacen==$val_compra->id_almacen) {
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


					<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:0px;">
					    
							<label for="id_proveedor_compra" class="control-label">Proveedor</label>
							<div>
							    <?php if (!$val_compra) { ?>
											 <fieldset class="disabledme">				
										<?php } else { ?>	
											 <fieldset class="disabledme" disabled>
										<?php } ?>	
													<select name="id_proveedor_compra" id="id_proveedor_compra" class="form-control">
															<?php foreach ( $proveedores as $proveedor ){ ?>
																	<?php 
																	   
																	   $seleccionado='';	
																		if ($val_compra) { //comprobar una vez que ya esten inhabilitados factura
																			 if ($proveedor->id==$val_compra->id_proveedor) {
																					$seleccionado='selected';
																				} else {
																					$seleccionado='';
																				}
																		}
																	?>
																		<option value="<?php echo $proveedor->id; ?>" <?php echo $seleccionado; ?> ><?php echo $proveedor->nombre; ?></option>
															<?php } ?>
													</select>
										    </fieldset>

							</div>
					</div>	

					<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:0px;">
					    
							<label for="id_medida_compra" class="control-label">Mts/kgs</label>
							<div>
							    
											 <fieldset >				
										
													<select name="id_medida_compra" id="id_medida_compra" class="form-control">
															<?php foreach ( $medidas as $medida ){ ?>
																	<?php 
																	   
																	   $seleccionado='';	
																		if ($val_compra) { //comprobar una vez que ya esten inhabilitados factura
																			 if ($medida->id==$val_compra->id_medida) {
																					$seleccionado='selected';
																				} else {
																					$seleccionado='';
																				}
																		}
																	?>
																		<option value="<?php echo $medida->id; ?>" <?php echo $seleccionado; ?> ><?php echo $medida->medida; ?></option>
															<?php } ?>
													</select>
										    </fieldset>

							</div>
					</div>					


			</div>  	<!--*****fin del div vacio -->
			

		</div>		 <!-- fin del lado izquierdo -->




	     <!-- derecho comentarios-->	
		<div class="col-sm-12 col-md-4">
				<div class="form-group">
					<!--<label for="comentario" class="col-sm-4 col-md-4">Especificaciones</label>-->
					<label for="factura">Comentarios</label>
					<div class="col-sm-4 col-md-12">

						<?php if ($val_compra) { ?>
							<fieldset class="disabledme" disabled>							
						<?php } else { ?>
							<fieldset class="disabledme">						
						<?php } ?>					
									<?php 

										$nomb_nom='';
											if ($val_compra) { //comprobar una vez que ya esten inhabilitados factura
												if (isset($val_compra->comentario)) 
												 {	$nomb_nom = $val_compra->comentario;}
											}

										
									?>	

									<textarea  class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"><?php echo  set_value('comentario',$nomb_nom); ?></textarea>
							  </fieldset>		
					</div>
				</div>						
		</div>	



   </div>		<!-- Fin del row-->		



    	<br/>

		<!-- primera tabla-->				
		<div class="col-md-12">	


				<div id="example22" class="row">
				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
										<label for="descripcion">Producto</label>
				                        <select class="form-control" name="producto_catalogo_compra" id="producto_catalogo_compra" dependencia="color_catalogo_compra" nombre="un color">
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
				                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un PRODUCTO." name="color_catalogo_compra" id="color_catalogo_compra"  dependencia="composicion_catalogo_compra" nombre="una composición">
				                            <option value="0">Seleccione un color</option>
				                          </select>
				                 </div>
				              </div>
				              
				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
									<label for="descripcion">Composición</label>
				                          <select class="form-control ttip" title="Campo dependiente. Primero seleccione un COLOR." name="composicion_catalogo_compra" id="composicion_catalogo_compra" dependencia="calidad_catalogo_compra" nombre="una calidad">
				                            <option value="0">Seleccione una composición</option>
				                          </select>
				                 </div>
				              </div>



				              <div class="col-sm-6 col-md-3">
				                 <div class="form-group">
									  <label for="descripcion">Calidad</label>
				                      <select class="form-control ttip" title="Campo dependiente. Primero seleccione una COMPOSICIÓN." name="calidad_catalogo_compra" id="calidad_catalogo_compra" dependencia="" nombre="">
				                        <option value="0">Seleccione una calidad</option>
				                      </select>
				                 </div>
				              </div>

				</div>     


				<div class="table-responsive" > <!--style="overflow-x:initial !important;" -->
						<section>
							<table id="tabla_entrada_pedido_compra" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
								<th width="25%">Nombre de Tela</th>
								<th  width="5%">Imagen</th>
								<th  width="10%">Color</th>
								<th  width="10%">Ancho</th>
								<th  width="10%">Composición</th>
								<th  width="10%">Calidad</th>
								<th  width="10%">Precio</th>
								<th width="10%">Cant. Disponible</th>
								<th width="10%">Agregar</th>





								</tr>
							</thead>
							</table>
						</section>
				</div>			
		</div>




	<br/>
		
				<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">												
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes por Página</b>
					</div>	
			
					<div class="col-sm-3 col-md-2">	
						<span id="total"></span>			
					</div>	
				</div>			

				<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">								
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes Totales</b>			
					</div>									

					
					<div class="col-sm-3 col-md-2">	
						<span id="total_total"></span>			
					</div>	

				</div>		


		<!-- Segunda tabla-->
		<div class="col-md-12">		
			
			<h4>Orden de Compra</h4>	
			<br>	
			<div class="table-responsive">
				<section>
					<table id="tabla_salida_pedido_compra" class="display table table-striped table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>

								<th width="25%">Nombre de Tela</th>
								<th  width="5%">Imagen</th>
								<th  width="10%">Color</th>
								<th  width="10%">Ancho</th>
								<th  width="10%">Composición</th>
								<th  width="8%">Calidad</th>
								<th  width="8%">Precio</th>
								<th width="8%">Cant. Disponible</th>
								<th width="8%">Cant. Pedido</th>
								<th width="8%">Quitar</th>
								<th width="10%">U.M.</th>
							</tr>
						</thead>
					</table>
				</section>
			</div>
		</div>



	<br/>
		
				<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">												
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes por Página</b>
					</div>	
			
					<div class="col-sm-3 col-md-2">	
						<span id="total2"></span>			
					</div>	
				</div>			

				<div class="row bloque_totales" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">									
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Importes Totales</b>			
					</div>									

					
					<div class="col-sm-3 col-md-2">	
						<span id="total_total2"></span>			
					</div>	

				</div>		
		

 <br>

	<div class="row">
		<div class="col-sm-4 col-md-4">	</div>
		<div class="col-sm-4 col-md-4 marginbuttom">
			<a href="<?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
		</div>
			<div class="col-sm-4 col-md-4">
				<button id="proc_pedido_compra" type="button"  class="btn btn-success btn-block">
					<span class="">Procesar Orden de Compra</span>
				</button>
			</div>
	</div>

</div>
</div>
</div>


<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	




<!-- Modal -->
<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 	<div class="modal-dialog">
        <div class="modal-content">
    
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">Modal header</h3>
			  </div>
			  <div class="modal-body">
			    <p>One fine body…</p>
			  </div>
			  <div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			    <button class="btn btn-primary">Save changes</button>
			  </div>

		</div>  
	</div>	  
</div>


<div id="miModal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn">Close</a>
    <a href="#" class="btn btn-primary">Save changes</a>
  </div>
</div>

<?php $this->load->view( 'footer' ); ?>