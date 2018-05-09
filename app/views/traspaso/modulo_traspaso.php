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
?>	

<?php if (isset($val_proveedor->nombre)) { 
	 $mi_proveedor = htmlspecialchars($val_proveedor->nombre);
  } else {
  	 $mi_proveedor = '';
  }

   $id_almacen=$this->session->userdata('id_almacen');
   
	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

	if ($val_proveedor) {
		 $consecutivo_actual = ( ($val_proveedor->id_factura==1) ? $consecutivo->conse_factura : $consecutivo->conse_remision );
	} else {
		$consecutivo_actual = $consecutivo->conse_factura;
	}	

  ?>

<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_remision+1; ?>">

<input type="hidden" id="id_proveedor" value="<?php echo $mi_proveedor; ?>">

<div class="container margenes">
<div class="panel panel-primary">
<div class="panel-heading">Registro de Traspaso</div>
<div class="panel-body">		
	
	<div class="row">		
		<!-- derecha comentarios-->	
	   <div class="col-sm-12 col-md-8">
			<div >
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
				<label for="movimiento" class="ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<fieldset disabled>
						<div class="form-group row">
							
							<div style="margin-top: 0px;" class="col-xs-12 col-sm-4 col-md-6">
								<input type="text" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
							</div>
							<div style="margin-top: 0px;" class="col-xs-12 col-sm-4 col-md-6">
								<input type="text" value="<?php echo $consecutivo->consecutivo+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
							</div>

						</div>
					</fieldset>			
				</div>
				
				<?php if (($configuracion->activo==1)) {  ?> 
					<div class="col-xs-12 col-sm-4 col-md-4">
						<?php if ($val_proveedor) { ?>
						<fieldset class="disabledme" disabled>							
						<?php } else { ?>
						<fieldset class="disabledme">						
						<?php } ?>
							<div class="form-group">
							<label for="factura">Nro. Control</label>
								<div>
									<?php if ($val_proveedor) { ?>
									<input value="<?php echo htmlspecialchars($val_proveedor->factura); ?>" type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Factura">							
									<?php } else { ?>
									<input type="text" class="form-control ttip" title="Introduzca un número de factura para continuar." id="factura" name="factura" placeholder="Nro. Control">
									<?php } ?>				
								</div>
							</div>
						</fieldset>	
					</div>
				<?php }  ?> 	
			</div>

			<div class="row">


					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-4" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
					    
							<label for="id_almacen_modulo" class="control-label">Almacén</label>
							<div>
							    <!--Los administradores o con permisos de traspaso 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(26, $coleccion_id_operaciones)) ) && (!$val_proveedor) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabledme">				
								<?php } else { ?>	
									 <fieldset class="disabledme" disabled>
								<?php } ?>	
											<select name="id_almacen_modulo" id="id_almacen_modulo" class="form-control">
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
						<div class="col-xs-12 col-sm-6 col-md-4 tipo_factura1">

								<label for="id_tipo_factura_traspaso" id="label_tipo_factura_traspaso" style="margin-top: 10px;"></label>
								<div>
								    <!--Los administradores o con permisos de entrada 
								    	Y que no este inhabilitado y 
								    	que no sean facturaista 
								    	ENTONCES lista editable -->

									<?php if ($val_proveedor) { ?>
										<fieldset class="disabledme" disabled>							
									<?php } else { ?>
										<fieldset class="disabledme">						
									<?php } ?>

												<select name="id_tipo_factura_traspaso" id="id_tipo_factura_traspaso" class="form-control">
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

		</div>		
	     <!-- Izquierda comentarios-->	
		<div class="col-sm-12 col-md-4">
				<div class="form-group">
					<!--<label for="comentario" class="col-sm-4 col-md-4">Especificaciones</label>-->
					<label for="factura">Comentarios</label>
					<div class="col-sm-4 col-md-12">

						<?php if ($val_proveedor) { ?>
							<fieldset class="disabledme" disabled>							
						<?php } else { ?>
							<fieldset class="disabledme">						
						<?php } ?>					
									<?php 

										$nomb_nom='';
											if ($val_proveedor) { //comprobar una vez que ya esten inhabilitados factura
												if (isset($val_proveedor->comentario)) 
												 {	$nomb_nom = $val_proveedor->comentario;}
											}

										
									?>	

									<textarea  class="form-control" name="comentario" id="comentario" rows="5" placeholder="Comentarios"><?php echo  set_value('comentario',$nomb_nom); ?></textarea>
							  </fieldset>		
					</div>
				</div>						
		</div>	
			<!-- -->		


    </div>



    	<br/>

		<!-- primera tabla-->				
		<div class="col-md-12">	

				<div class="table-responsive" > <!--style="overflow-x:initial !important;" -->
						<section>


							<table id="tabla_entrada_traspaso" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="width:20%;">Código</th>
									<th style="width:10%;">Prod.</th>
									<th style="width:5%;">Color</th>
									<th style="width:5%;">Cant.</th>
									<th style="width:5%;">Ancho</th>
									<th style="width:5%;">Precio</th>
									<th style="width:5%;">SubTotal</th>
									<th style="width:5%;">Iva</th>
									<th style="width:5%;">No. Mov.</th>			
									<th style="width:10%;">Proveedor</th>
									<th style="width:5%;">Lote</th>
									<th style="width:5%;">Partida</th>
									<th style="width:10%;">Agregar</th>
								</tr>
							</thead>
							</table>
						</section>
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


	<br/>
		
				<div class="row bloque_totales">						
					<div class="col-sm-0 col-md-4">	
					  
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
					<div class="col-sm-0 col-md-4">	
					  
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


		<!-- Segunda tabla-->
		<div class="col-md-12">		
			
			<h4>Orden de Traspaso</h4>	
			<br>	
			<div class="table-responsive">
				<section>
					<table id="tabla_salida_traspaso" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
						<thead>
							<tr>
									<th style="width:20%;">Código</th>
									<th style="width:10%;">Prod.</th>
									<th style="width:5%;">Color</th>
									<th style="width:5%;">Cant.</th>
									<th style="width:5%;">Ancho</th>
									<th style="width:5%;">Precio</th>
									<th style="width:5%;">Subtotal</th>
									<th style="width:5%;">Iva</th>
									<th style="width:5%;">No. Mov.</th>			
									<th style="width:10%;">Proveedor</th>
									<th style="width:5%;">Lote</th>
									<th style="width:5%;">Partida</th>
									<th style="width:10%;">Quitar</th>
							</tr>
						</thead>
					</table>
				</section>
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
						<span id="pieza2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="metro2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="kg2" ></span>				
					</div>	
				</div>			

				<div class="row bloque_totales">		
					<div class="col-sm-0 col-md-4">	
					  
					</div>	
					<div class="col-sm-3 col-md-2">	
					  <b>Existencias Totales</b>			
					</div>									
					<div class="col-sm-3 col-md-2">	
						<span id="total_pieza2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_metro2"></span>			
					</div>	
					<div class="col-sm-3 col-md-2">	
						<span id="total_kg2" ></span>				
					</div>	
				</div>

	<br/>
		
				<div class="row bloque_totales2" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">		
					<div class="col-sm-0 col-md-4">	
					  
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

				<div class="row bloque_totales2" style="display:<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 'block' : 'none'; ?>">			
					<div class="col-sm-0 col-md-4">	
					  
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

<br>

	<div class="row">
		<div class="col-sm-4 col-md-4">	</div>

		<div class="col-sm-4 col-md-4 marginbuttom">
			<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
		</div>

			<div class="col-sm-4 col-md-4">
				<button id="proc_traspaso" type="button"  class="btn btn-success btn-block">
					<span class="">Procesar Traspaso</span>
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