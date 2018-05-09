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
  	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );
	$id_almacen=$this->session->userdata('id_almacen');

?>	

<input type="hidden" id="retorno" name="retorno" value="<?php echo $retorno; ?>">
<input type="hidden" id="modulo" name="modulo" value="<?php echo $modulo; ?>">

<div class="container margenes">
<div class="panel panel-primary">
<div class="panel-heading">Detalles de la Orden de Compra</div>
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
							<input value="<?php echo $val_compra->fecha_entrada; ?>"  type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha">
						</div>
					</div>
					</fieldset>	
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<fieldset disabled>
						<div class="form-group">
							<label for="movimiento" class="ttip" title="Campo informativo, no editable.">No. Movimiento</label>
							<div>
								<input type="text" value="<?php echo $val_compra->movimiento; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
							</div>
						</div>
					</fieldset>			
				</div>
				
				<?php if (($configuracion->activo==1)) {  ?> 
					<div class="col-xs-12 col-sm-4 col-md-4">
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
				<?php }  ?> 	
			</div>

			<div class="row">


					<!--almacen Asociado -->
					<div class="col-xs-12 col-sm-6 col-md-4" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>
					    
							<label for="id_almacen_compra" class="col-sm-3 col-md-3 control-label">Almacén</label>
							<div class="col-sm-9 col-md-10">
							    <!--Los administradores o con permisos de traspaso 
							    	Y que no este inhabilitado y 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(26, $coleccion_id_operaciones)) ) && (!$val_compra) && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
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




					<!--Proveedor -->
					<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:0px;">
					    
							<label for="id_proveedor_compra" class="col-sm-3 col-md-3 control-label">Proveedor</label>
							<div class="col-sm-9 col-md-10">
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

					<!--Medida 
					<div class="col-xs-12 col-sm-6 col-md-6" style="margin-top:0px;">
					    
							<label for="id_medida_compra" class="col-sm-3 col-md-3 control-label">Mts/kgs</label>
							<div class="col-sm-9 col-md-12">
							    <?php if (!$val_compra) { ?>
											 <fieldset class="disabledme">				
										<?php } else { ?>	
											 <fieldset class="disabledme" disabled>
										<?php } ?>	
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
	-->




					<div class="col-xs-12 col-sm-6 col-md-8">
						<fieldset id="disa_reportes" disabled>
										<div class="col-sm-6 col-md-6">
											<a id="impresion_reporte_compra" type="button" class="btn btn-success btn-block">Imprimir</a>
										</div>

										<div class="col-sm-6 col-md-6">
											<a id="exportar_reportes_compra" type="button" class="btn btn-success btn-block">Exportar</a>
										</div>
						</fieldset>	
					</div>
					
			</div>

		</div>		
	     <!-- Izquierda comentarios-->	
		<div class="col-sm-12 col-md-4">
				<div class="form-group">
					<!--<label for="comentario" class="col-sm-4 col-md-4">Especificaciones</label>-->
					<label for="factura">Comentarios</label>
					<div class="col-sm-4 col-md-12">

						<?php if ($val_compra) { ?>
							<fieldset class="disabledme">							
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
			<!-- -->		


    </div>



    	<br/>

		<!-- primera tabla-->				
		<div class="col-md-12">	
 					<div class="col-md-12" style="display: block;" ><span> Productos cancelados en el pedido</span>
                     <div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;">
                     </div>
	                </div>
					<br/>	
				    <hr style="padding: 0px; margin: 8px;"/>	


					<!-- Segunda tabla-->
					<div class="col-md-12">		
						
						<h4>Orden de Compra</h4>	
						<br>	
						<div class="table-responsive">
							<section>
								<table id="tabla_revisa_pedido_compra" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
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
											<th width="8%">Cant. Solicitada</th>
											<th width="8%">Cant. Aprobada</th>
											<!--<th style="display:none;">Cant. Aprobada</th>
											<th style="display:none;">Cant. Aprobada</th> -->
											<th>UM</th>
										</tr>
									</thead>
								</table>
							</section>
						</div>
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
				<button id="proc_pedido_cambio" type="button"  class="btn btn-success btn-block">
					<span class="">Confirmar cambios</span>
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