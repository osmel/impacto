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
echo form_open('validar_agregar_producto', $attr);

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );

//para determinar el consecutivo

if ($val_proveedor) {
	 $consecutivo_actual = ( ($val_proveedor->id_factura==1) ? $consecutivo->conse_factura : $consecutivo->conse_remision );
} else {
	$consecutivo_actual = $consecutivo->conse_factura;
}	

//print_r('a');die;

?>		
<input type="hidden" id="conse_factura" name="conse_factura" value="<?php echo $consecutivo->conse_factura+1; ?>">
<input type="hidden" id="conse_remision" name="conse_remision" value="<?php echo $consecutivo->conse_remision+1; ?>">

<input type="hidden" id="id_operacion" name="id_operacion" value="<?php echo $id_operacion; ?>">

<div class="container">

	<br>



	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h4>Registro de Transferencia</h4>
			<hr>
		</div>

		<input type="hidden" id="oculto_producto" name="oculto_producto" value="" color="" composicion="" calidad="">


	<div  style="display:none;" class="col-sm-6 col-md-6">
			
 			   
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

				<!--
				<div class="col-xs-6 col-sm-3 col-md-1" >
					    	<div class="form-group">
								<input type="text" title="234" value="<?php echo $conse_general->c234+(!$val_proveedor); ?>" class="form-control ttip" id="c234" name="c234" style="color:red; ">
							</div>	
				</div>	
				-->

 			   <div class="col-xs-6 col-sm-3 col-md-1">
					    	<div class="form-group">
								<input type="text" title="34" value="<?php echo $conse_general->c34+(!$val_proveedor); ?>" class="form-control ttip" id="c34" name="c34">
							</div>	
				</div>	


		 	
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

		<div  style="display:none;" class="col-xs-6 col-sm-3 col-md-2">
			<fieldset disabled>
				<div class="form-group">
					<label for="movimiento" class="col-sm-12 col-md-12 ttip" title="Campo informativo, no editable.">No. Movimiento</label>
					<div class="col-sm-6 col-md-6">
						<input type="text" title="Movimiento" value="<?php echo $consecutivo->consecutivo+1; ?>" class="form-control" id="movimiento" name="movimiento" placeholder="No. Movimiento">
					</div>
					<div class="col-sm-6 col-md-6">
						<input type="text" title="movimiento_unico" value="<?php echo $consecutivo_actual+1; ?>" class="form-control" id="movimiento_unico" name="movimiento_unico" placeholder="No. Movimiento">
					</div>
				</div>
			</fieldset>			
		</div>

		<div style="display:none;" class="Bueno col-xs-6 col-sm-3 col-md-2">
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
				<fieldset class="disabledme">						
					<div class="form-group">
						<label for="id_transferencia" class="col-sm-12 col-md-12">Tienda-Consecutivo</label>
						<div class="col-sm-12 col-md-12">
							<select name="id_transferencia" id="id_transferencia" class="form-control">
									<option value="0" id_tienda_origen="0">Selecciona una opción</option>
										<?php foreach ( $transferencias as $transf ){ ?>
													<option value="<?php echo $transf->mov_salida_unico; ?>" id_tienda_origen="<?php echo $transf->id_tienda_origen; ?>" ><?php echo $transf->nombre.' - '.$transf->movi_salida.' - '.$transf->nombre_usuario; ?></option>
										<?php } ?>
								</select>
						</div>
					</div>
				</fieldset>							
			
		</div>

<!-- si configuracion lo tiene activo y es(administrador o por el contrario tiene "permiso de ver y editar") -->
		<?php if (($configuracion->activo==1)) {  ?> 

				<div class="col-xs-12 col-sm-6 col-md-2">
						<fieldset class="disabledme">						

						<div class="form-group">
							<label for="factura" class="col-sm-12 col-md-12">Factura/Remisión</label>
							<div class="col-sm-12 col-md-12">
											<input type="text" class="form-control" id="factura" name="factura" placeholder="Factura">
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
							  
									 <fieldset class="disabledme" >
							
											<select name="id_almacen" id="id_almacen" tipo="entrada" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $almacenes as $almacen ){ ?>
																<option value="<?php echo $almacen->id_almacen; ?>"  ><?php echo $almacen->almacen; ?></option>
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

									<fieldset class="disabledme">						

											<select name="id_tipo_pago" id="id_tipo_pago" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $pagos as $pago ){ ?>
																<option value="<?php echo $pago->id; ?>" ><?php echo $pago->tipo_pago; ?></option>
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

									<fieldset class="disabledme">						

											<select name="id_factura" id="id_factura" tipo="entrada" class="form-control">
												<!--<option value="0">Selecciona una opción</option>-->
													<?php foreach ( $facturas as $factura ){ ?>
																<option value="<?php echo $factura->id; ?>"  ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
					</div>		



				
				


	</div>


	



	<?php echo form_close(); ?>


			<div class="row">
				<div class="col-md-12">
					
					<h4>Listado de productos seleccionados para Entrada</h4>	
					<br>			
						<div class="notif-bot-vendedor"></div>
						<div class="table-responsive">	
								<section>
									
									<table id="tabla_transferencia_recibida"	class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
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

				<div class="col-sm-4 col-md-3"></div>
				<div class="col-sm-4 col-md-3 marginbuttom">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-warning btn-block">Limpiar Información</a>
				</div>

				<div class="col-sm-4 col-md-3 marginbuttom">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
				</div>

				<div class="col-sm-4 col-md-3">
					<button id="conf_transferencia_recibida"  type="button" class="btn btn-success btn-block">
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