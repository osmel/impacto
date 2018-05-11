<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<?php 
	  $perfil= $this->session->userdata('id_perfil'); 
	  $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 

	  if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) )  
	  		{
	  			$coleccion_id_operaciones = array();
	  		} 	

?>	


<div class="container margenes">

	
	<input type="hidden" id="id_operacion_pedido" name="id_operacion_pedido" value="<?php echo $id_operacion_pedido; ?>">		
		

	<input type="hidden" id="id_almacen_pedido" name="id_almacen_pedido" value="<?php echo $id_almacen; ?>">		
	<input type="hidden" id="num_pedido" name="num_pedido" value="">		
	<input type="hidden" id="on_off" name="on_off" value="">		
		

		<div class="panel panel-primary">
			<div class="panel-heading">Detalles de pedido &nbsp;&nbsp;&nbsp;<?php echo "<b>ALM:</b> ". $almacen; ?>&nbsp;&nbsp;<span></span>
			</div>
			<div class="panel-body">
				
				

					<div class="row">
						<div class="col-sm-4 col-md-5">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Cliente/Núm. Pedido</label>
									<input type="text" disabled class="form-control" id="etiq_num_mov" name="etiq_num_mov" placeholder="">
							</div>
						</div>		

						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Vendedor</label>
									<input type="text" disabled class="form-control" id="etiq_cliente" name="etiq_cliente" placeholder="34534534554">
							</div>
						</div>							



						<div class="col-sm-4 col-md-3" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Sucursal</label>
									<input type="text" disabled class="form-control" id="etiq_dependencia" name="etiq_dependencia" placeholder="">
							</div>
						</div>
					
						<div class="col-sm-4 col-md-4" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Fecha</label>
									<input type="text" disabled class="form-control" id="etiq_fecha" name="etiq_fecha" placeholder="10/10/15">
							</div>
						</div>
						
						<div class="col-sm-4 col-md-4" >
							<div class="form-group">
								<label for="descripcion" class="col-sm-12 col-md-12">Hora</label>
									<input type="text" disabled class="form-control" id="etiq_hora" name="etiq_hora" placeholder="9:05am">
							</div>
						</div>		

						<?php if ( ( $this->session->userdata('config_salida') == 1 ) ) { ?>
							<div class="col-sm-4 col-md-4" >

									<div class="col-sm-12 col-md-12" style="height: 33px;"> </div>

								
									<div class="col-sm-1 col-md-1" id="etiq_color_apartado"> 
									</div>

									<label for="descripcion" class="col-sm-10 col-md-10">
										<span id="etiq_tipo_apartado" ></span>
									</label>
								
							</div>									
						<?php } else { ?>
							<?php if ( ( $perfil == 2 ) OR ( $perfil == 1 ) 
									OR ( 
										( ($id_operacion_pedido==4) &&  (in_array(2, $coleccion_id_operaciones)))
										|| ( ($id_operacion_pedido==97) &&  (in_array(94, $coleccion_id_operaciones)))
										|| ( ($id_operacion_pedido==98) &&  (in_array(95, $coleccion_id_operaciones)))
									)	
									)
							 
							 { ?>
							<div class="col-xs-12 col-sm-4 col-md-3">
								
									<div class="form-group">
										<label for="descripcion">Cargador</label>
										<div class="input-group col-md-12 col-sm-12 col-xs-12">
											
											<input  type="text" name="editar_cargador" class="buscar_cargador form-control typeahead tt-query ttip" title="Campo predictivo. Comience a escribir el nombre de un cargador y seleccione una opción para poder continuar." autocomplete="off" spellcheck="false" placeholder="Buscar Cargador...">
											
										</div>
									</div>
								
							</div>
							<?php }  ?>	
						<?php }  ?>	


				</div>		

				<div class="col-sm-1 col-md-1"> 
					<div style="margin-right: 15px;float:left;background-color:#f2dede;width:15px;height:15px;"></div>
				</div>Serán traspasados


	<hr/>



	<div class="row">					
		<div class="col-md-12">		
					  
						<input type="hidden" id="num_mov" value="<?php echo $num_mov ; ?>">

						<div class="table-responsive">
							<section>
								<table id="pedido_detalle" class="display table table-striped table-bordered table-responsive " cellspacing="0" width="100%">
								</table>
							</section>
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
								<hr/>

						
							<div class="row">
								<div class="col-sm-3 col-md-3 marginbuttom">
									<label for="descripcion" class="col-sm-12 col-md-12"></label>
									<a href="<?php echo base_url(); ?>generar_pedido_especifico/<?php echo base64_encode($num_mov); ?>/<?php echo base64_encode(1); ?>/<?php echo base64_encode($id_almacen); ?>/<?php echo base64_encode($id_operacion_pedido); ?>" 
										type="button" class="btn btn-success btn-block ttip" target="_blank" title="Generar un PDF del pedido para impresión.">Imprimir
									</a>
								</div>

								<?php if ( ( $this->session->userdata('config_salida') == 1 ) ) { ?>
									<?php if ( ( $perfil != 4 ) ) { ?>
										<div class="col-sm-3 col-md-3 marginbuttom">
											<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para que NO sea visible y no pueda ser procesada su salida." id="excluir_pedido">
												<span>Excluir de la Salida</span>
											</button>
										</div>	
										<div class="col-sm-3 col-md-3 marginbuttom">
											<button type="button"  class="btn btn-success btn-block ttip" title="Cambiar el estatus del pedido para poder ser procesado en la salida." id="incluir_pedido">
												<span>Incluir en la Salida</span>
											</button>
										</div>			
									<?php } ?>		
								<?php } else { ?>			


									<?php if ( ( $perfil == 2 ) OR ( $perfil == 1 ) 
									OR ( 
										( ($id_operacion_pedido==4) &&  (in_array(2, $coleccion_id_operaciones)))
										|| ( ($id_operacion_pedido==97) &&  (in_array(94, $coleccion_id_operaciones)))
										|| ( ($id_operacion_pedido==98) &&  (in_array(95, $coleccion_id_operaciones)))
									)	
									)
							 
							 		{ ?>
										<div class="col-sm-3 col-md-3 marginbuttom">
											<button type="button"  class="btn btn-success btn-block ttip" title="Procesar la salida." id="proc_salida_pedido">
												<span>Procesar Salida</span>
											</button>
										</div>		
									<?php } ?>		

								<?php }  ?>				

								<div class="col-sm-3 col-md-3 marginbuttom">
									<a href="<?php echo base_url(); ?>pedidos" type="button" class="btn btn-danger btn-block">Regresar</a>
								</div>	
	
							</div>	
						

		</div>
	</div>

</div>
</div>
<?php $this->load->view( 'footer' ); ?>


<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	