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


if (ltrim($retorno)=="") {
	$regreso = " Ir a Home";
} elseif ($retorno=="reportes") {
	$regreso = " Ir a Reportes";
} else {
		$regreso = " Regresar";
}

	$config_impresion= $this->session->userdata('config_impresion');
 

  
$hidden = array('id_movimiento'=>$num_mov,'id_factura'=>$id_factura); 
$attr = array('class' => 'form-horizontal', 'id'=>'form_entradas1','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');

?>
<div class="container ">
<?php
echo form_open('pdfs/generar', $attr,$hidden );
?>		

			<div class="panel panel-primary">

			<div class="panel-heading">Número de Movimiento <?php echo $etiq_mov; ?>: <?php  echo '['.(($movimientos[0]->id_operacion==72) ? 'B' : (($movimientos[0]->id_operacion==71) ? 'C' : (($movimientos[0]->devolucion<>0) ? 'D' :  (($movimientos[0]->id_operacion==70) ? 'T' : (($movimientos[0]->id_operacion==73) ? 'A' :'E') ) )) ).']  '.$movimientos[0]->id_almacen.'-'.$movimientos[0]->tipo_factura.'-'.(($movimientos[0]->devolucion<>0) ? $movimientos[0]->movimiento_unico : $movimientos[0]->c234); ?>
				     &nbsp;  &nbsp;  &nbsp; <b>Almacén</b>: <?php echo $movimientos[0]->almacen; ?>
			</div>

			<div class="panel-body">		
					
					
				<div class="col-sm-<?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 6:0).'"'; ?> col-md-<?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 6:0).'"'; ?>" >
				</div>	

				<div class="col-sm-3 col-md-3">
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					<?php $tipo= (($movimientos[0]->id_operacion==72) ? 'B-' : (($movimientos[0]->id_operacion==71) ? 'C-' : (($movimientos[0]->devolucion<>0) ? 'D-' :  (($movimientos[0]->id_operacion==70) ? 'T-' : (($movimientos[0]->id_operacion==73) ? 'A-' :'E-') ) )) ); ?>
					<a href="<?php echo base_url(); ?>generar_etiquetas_rapida/<?php echo base64_encode($tipo.$num_mov); ?>/<?php echo base64_encode($movimientos[0]->devolucion); ?>/<?php echo base64_encode($id_factura); ?>/<?php echo base64_encode($id_estatus); ?>" 
					

						type="button" class="btn btn-success btn-block" target="_blank">Imprimir etiquetas
						
					</a>

				
				</div>

				
				<div class="col-sm-3 col-md-3">
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					<?php $tipo= (($movimientos[0]->id_operacion==72) ? 'B-' : (($movimientos[0]->id_operacion==71) ? 'C-' : (($movimientos[0]->devolucion<>0) ? 'D-' :  (($movimientos[0]->id_operacion==70) ? 'T-' : (($movimientos[0]->id_operacion==73) ? 'A-' :'E-') ) )) ); ?>
					<a href="<?php echo base_url(); ?>generar_notas_rapida/<?php echo base64_encode($tipo.$num_mov); ?>/<?php echo base64_encode($movimientos[0]->devolucion); ?>/<?php echo base64_encode($id_factura); ?>/<?php echo base64_encode($id_estatus); ?>"  
						type="button" class="btn btn-success btn-block" target="_blank">Imprimir nota
					</a>
				</div>


				<div class="col-sm-3 col-md-3" <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					
				

					<a href="<?php echo base_url(); ?>generar_etiquetas/<?php echo base64_encode($num_mov); ?>/<?php echo base64_encode($movimientos[0]->devolucion); ?>/<?php echo base64_encode($id_factura); ?>/<?php echo base64_encode($id_estatus); ?>" 
					

						type="button" class="btn btn-success btn-block" target="_blank">PDF etiquetas
						
					</a>
				</div>				


				<div class="col-sm-3 col-md-3" <?php echo 'style="display:'.( (($config_impresion->activo==0) ) ? 'none':'block').'"'; ?>>
					<label for="descripcion" class="col-sm-12 col-md-12"></label>
					<a href="<?php echo base_url(); ?>generar_notas/<?php echo base64_encode($num_mov); ?>/<?php echo base64_encode($movimientos[0]->devolucion); ?>/<?php echo base64_encode($id_factura); ?>/<?php echo base64_encode($id_estatus); ?>"  
						type="button" class="btn btn-success btn-block" target="_blank">PDF nota
					</a>
				</div>


			<div class="col-sm-12 col-md-12" style="margin-top:20px;">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-responsive tabla_ordenadas" >
						<thead>	
							<tr>
								<th class="text-center cursora" style="width:14%">Código<i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:14%">Descripción <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:8%">Color<i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:7%">Medida<i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:7%">Ancho <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:7%">Peso Real <i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:14%">Proveedor<i class="glyphicon glyphicon-sort"></i></th>

								<?php if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) {
								?>    

									<th class="text-center cursora" width="10%">Precio  <i class="glyphicon glyphicon-sort"></i></th>
									<th class="text-center cursora" width="10%">Subtotal  <i class="glyphicon glyphicon-sort"></i></th>
									<th class="text-center cursora" width="10%">IVA  <i class="glyphicon glyphicon-sort"></i></th>
									<th class="text-center cursora" width="10%">Total  <i class="glyphicon glyphicon-sort"></i></th>				

								<?php } ?>		
								<th class="text-center cursora" style="width:7%">Lote<i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:8%">No. de Partida<i class="glyphicon glyphicon-sort"></i></th>
								<th class="text-center cursora" style="width:14%">Comentario<i class="glyphicon glyphicon-sort"></i></th>
								
							</tr>
						</thead>		

						<tbody>	
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
							<?php foreach( $movimientos as $movimiento ): ?>
								<tr style="color:<?php echo $movimiento->color_devolucion?>">
									<td class="text-center"><?php echo $movimiento->codigo; ?></td>								
									
									<td class="text-center"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?> </td>

									<td class="text-center">
										<div style="background-color:#<?php echo $movimiento->hexadecimal_color; ?>;display:block;width:15px;height:15px;margin:0 auto;"></div>
									</td>	
									<td class="text-center"> <?php echo $movimiento->cantidad_um; ?> <?php echo $movimiento->medida; ?></td>
									<td class="text-center"><?php echo $movimiento->ancho; ?> cm</td>
									<td class="text-center"><?php echo $movimiento->peso_real; ?> kgs</td>
									<td class="text-center"><?php echo $movimiento->nombre; ?></td>
									
									<?php if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) {
								?>   
									<td class="text-center"><?php echo number_format($movimiento->precio, 2, '.', ','); ?></td>
									<td class="text-center"><?php echo number_format($movimiento->sum_precio, 2, '.', ','); ?></td>
									<td class="text-center"><?php echo number_format($movimiento->sum_iva, 2, '.', ','); ?></td>
									<td class="text-center"><?php echo number_format($movimiento->sum_total, 2, '.', ','); ?></td>
									
									<?php } ?>	

									<td class="text-center"><?php echo $movimiento->id_lote; ?> - <?php echo $movimiento->consecutivo; ?></td>
									<td class="text-center"><?php echo $movimiento->num_partida; ?></td>
									<td class="text-center"><?php echo $movimiento->comentario; ?></td>

						

								</tr>
									
							<?php endforeach; ?>
						

						<?php else : ?>
								<tr class="noproducto">
									<td colspan="10">No se han agregado producto</td>
								</tr>

						<?php endif; ?>		

						</tbody>		
					</table>
				</div>
				
				<br>
				<div class="row">

					<div class="col-sm-8 col-md-9"></div>
					<div class="col-sm-4 col-md-3" style="margin-bottom:25px;">
						<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" class="btn btn-danger btn-block"><i class="glyphicon glyphicon-backward"></i><?php echo $regreso; ?></a>
					</div>
				</div>

			</div>

			</div> <!--panel body-->

		</div>
</div>



<?php echo form_close(); ?>

</div>

<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>	



<?php $this->load->view('footer'); ?>


