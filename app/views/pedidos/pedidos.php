<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>
<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	



   $id_almacen=$this->session->userdata('id_almacen');

	$config_almacen = $this->session->userdata( 'config_almacen' );
	$el_perfil = $this->session->userdata( 'id_perfil' );
?>	

<div class="container margenes">
		<div class="panel panel-primary">
			<div class="panel-heading">Gestión de pedidos</div>
			<div class="panel-body">




		<div class="notif-bot-tienda"></div>
		<div class="notif-bot-vendedor"> </div>


				
		<div class="container row">



		   <div class="row" >

				<input type="hidden" id="mi_perfil" name="mi_perfil" value="<?php echo $this->session->userdata( 'id_perfil' ); ?>">

					
					    
							<div class="col-sm-2 col-md-2" <?php echo 'style="display:'.( (($config_almacen->activo==0) && ($el_perfil==2) ) ? 'none':'block').'"'; ?>>

							<label for="id_almacen" class="col-sm-12 col-md-12">Almacén</label>
							<div class="col-sm-12 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    							****2121 sistema.js por ajax deshabilita sino hay en la regilla 
							    	que no sean almacenista 
							    	ENTONCES lista editable -->
							    <?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(10, $coleccion_id_operaciones)) )  && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen">				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" disabled>
								<?php } ?>	
											<select name="id_almacen_pedido" id="id_almacen_pedido" class="form-control">
												
												<!--	<option value="0">Todos</option> -->
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
					



		   	<!--Tipos de pedidos -->
								    

							<div class="col-sm-2 col-md-2">
							<label for="id_tipo_pedido" class="col-sm-12 col-md-12">Tipo de Pedido</label>
							<div class="col-sm-12 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean pedidoista 
							    	ENTONCES lista editable -->
    							<?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(10, $coleccion_id_operaciones)) )  && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen">				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" > <!--disabled-->
								<?php } ?>	

											<select name="id_tipo_pedido" id="id_tipo_pedido"  pantalla="pedir"class="form-control id_tipo_pedido">
												<option value="0">Todos</option>
													<?php foreach ( $pedidos as $pedido ){ ?>
															
																<option value="<?php echo $pedido->id; ?>" ><?php echo $pedido->tipo_pedido; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
							</div>
			


							<div class="col-sm-2 col-md-2 tipo_factura" >

							<label for="id_tipo_factura" class="col-sm-12 col-md-12 ">Tipo</label>
							<div class="col-sm-12 col-md-12">
							    <!--Los administradores o con permisos de entrada 
							    	Y que no este inhabilitado y 
							    	que no sean facturaista 
							    	ENTONCES lista editable -->

								<?php if (( ( $this->session->userdata( 'id_perfil' ) == 1  ) || (in_array(10, $coleccion_id_operaciones)) )  && (( $this->session->userdata( 'id_perfil' ) != 2 ) ) ){ ?>
									 <fieldset class="disabled_almacen">				
								<?php } else { ?>	
									 <fieldset class="disabled_almacen" > <!--disabled-->
								<?php } ?>	
											<select name="id_tipo_factura" id="id_tipo_factura" pantalla="pedir" class="form-control id_tipo_factura">
												<option value="0">Todos</option>
													<?php foreach ( $facturas as $factura ){ ?>
															
																<option value="<?php echo $factura->id; ?>" ><?php echo $factura->tipo_factura; ?></option>
													<?php } ?>
												<!--rol de usuario -->
											</select>
								    </fieldset>

							</div>
							</div>

							<div id="fecha_id" class="col-xs-2 col-sm-2 col-md-2">
									<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Rango de fecha</label>
									<div class="input-prepend input-group  form-group" style="padding-left:15px !important;padding-right:15px !important;">
			                       		<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input id="foco" type="text" name="permisos"  class="form-control col-sm-12 col-md-12 fecha_reporte_pedido ttip" title="Seleccione un rango de fechas para filtrar los resultados." value="" format = "DD-MM-YYYY"/> 
									</div>	
			                     </div>


			                     <!--
								<div id="proveedor_id" class="col-xs-2 col-sm-6 col-md-2">

											<div class="form-group">
												<label id="label_proveedor" for="descripcion" class="col-sm-12 col-md-12">Vendedor</label>
												<div class="col-sm-12 col-md-12">
													 <input  type="text" name="editar_proveedor_reporte" id="editar_proveedor_reporte" idproveedor="1" class="form-control buscar_proveedor_reporte ttip" title="Campo predictivo. Comience a escribir y seleccione una opción para agregar un filtro de selección." autocomplete="off" spellcheck="false" placeholder="Buscar...">
												</div>
											</div>
									
								</div>	
								-->	





		   </div>





			<?php if  ($config_almacen->activo==1) { ?>
			    <div class="col-md-7">

					<div class="col-md-4 ttip" title="Producto en espera de confirmación total del apartado."><span> Apartado Individual</span><div style="margin-right: 15px;float:left;background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
					<div class="col-md-4 ttip" title="El apartado ha sido generado."><span> Apartado Confirmado</span><div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div></div>
					<div class="col-md-4 ttip" title="Indica que se puede procesar la salida del apartado."><span> Disponibilidad Salida</span><div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div></div>
				</div>			
				<hr/>
			<?php } else { ?>
				<div class="col-md-7"></div>
				<hr/>

			<?php } ?>	
				
					    
						
					

			

		<?php if ( ( $perfil != 4 ) ) { ?>		 
			<div class="table-responsive">
				
				<?php if ( ( $perfil == 3 ) ) { ?>		 
					<h4>Mis Pedidos</h4>	
				<?php } else { ?>		
					<h4>Pedidos de vendedores</h4>	
				<?php } ?>		
				<br>	
				<section>
					<table id="tabla_apartado" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">

					</table>
				</section>
			</div>
		<?php } ?>		
			
		<?php if ( ( $perfil != 3 ) ) { 
			//if ( ( $perfil == 1 ) || (in_array(98, $coleccion_id_operaciones)) ) { 
			?>		 
			<div class="table-responsive">
				

				<?php echo ( ( $perfil == 1 ) || (in_array(4, $coleccion_id_operaciones)) || (in_array(97, $coleccion_id_operaciones)) || (in_array(98, $coleccion_id_operaciones)) ) ? '<h4>Pedidos de tiendas</h4>': '<h4>Mis Pedidos</h4>';?>


				<br>			
				   
				<section>
					<table id="tabla_pedido" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">

					</table>
				</section>
			</div>		
		<?php } ?>		

			<div class="table-responsive">
				
				<h4>Histórico de Pedidos</h4>	
				<br>			

				<section>
					<table id="tabla_pedido_completado" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">

					</table>
				</section>
			</div>		
			

	
</div>
<hr>

<div class="row">
	<div class="col-sm-8 col-md-8"></div>

	<div class="col-sm-4 col-md-4">
		<a href="<?php echo base_url(); ?>" type="button" class="btn btn-danger btn-block">Regresar</a>
	</div>	
	
</div>

</div>
</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>	



<?php $this->load->view( 'footer' ); ?>