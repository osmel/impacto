<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php 
//print_r($total);
//die;

?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<td>
							<p style="font-size: 15px; line-height: 20px; padding: 0px; margin-bottom: 0px;">

									<span><b>Traspaso: </b> <?php echo $movimientos[0]->tipo_factura_manual; ?></span><br>
									<span><b>Responsable: </b> <?php echo $movimientos[0]->vendedor_manual; ?></span><br>
									<span><b>Dependencia: </b> <?php echo $movimientos[0]->dependencia_manual; ?></span><br>
									<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>
									<span><b>Fecha: </b> <?php echo date('Y-m-d'); ?></span><br>
		                            <span><b>Motivos: </b> <?php echo $movimientos[0]->comentario_traspaso; ?></span><br>
							</p>
						</td>
						<td style="text-align: right;">
							<p>&nbsp;</p>
							<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="50px"/>'; ?>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="width: 100%; border: 2px solid #222222; font-size: 12px;">
				<thead>
					<tr>
						<th colspan="9">
							<p><b>Productos</b></p>
						</th>
					</tr>
					<tr>
						<th width="25%">Código</th>
						<th width="20%">Descripción</th>
						<th width="8%">Color</th>
						<th width="10%">Cantidad</th>
						<th width="10%">Precio</th>
						<th width="10%">Ancho</th>
						<?php if ($configuracion->activo==1) { ?> 
							<th width="9%">Lote</th>
							<th width="8%">Subtotal</th>
						<?php } else { ?> 	
							<th width="17%">Lote</th>
						<?php }  ?> 


					</tr>
				</thead>	
				<tbody>	
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>

							
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="20%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;">
								<div style="background-color:#<?php echo $movimiento->hexadecimal_color; ?>;display:block;width:15px;height:15px;margin:0 auto;"></div>
							</td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?> <?php echo $movimiento->medida; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->precio; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?> cm</td>
							
							<?php if ($configuracion->activo==1) { ?> 
								<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo; ?></td>							
								<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->subtotal; ?></td>
							<?php } else {  ?> 	
								<td width="17%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo; ?></td>							
							<?php }  ?> 	


						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>
				</tbody>

				<tfooter>	
						<tr>
							<td width="100%" style="border-top: 1px solid #222222; font-size: 10px; line-height: 15px; padding: 0px; margin-bottom: 0px;">
								<span><b>Subtotal: </b><?php echo number_format($totales->subtotal, 2, '.', ','); ?></span> 

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Iva: </b><?php echo number_format($totales->iva, 2, '.', ','); ?></span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total: </b><?php echo number_format($totales->total, 2, '.', ','); ?></span><br>
									
									<?php  if ($totales->metros>0) { ?>	
										<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span>
									<?php } ?>		
									<?php  if ($totales->kilogramos>0) { ?>	
										<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span>
									<?php } ?>	

									<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Piezas: </b><?php echo $totales->pieza; ?></span>
								
							</td>
						</tr>
				</tfooter>	


			</table>
		</div>
	</div>
</div>