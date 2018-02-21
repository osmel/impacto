<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
	<div>
		<div>
			<table style="width: 100%; border: 2px solid #222222;">
				<tbody>
					<tr>
						<td>
							<p style="font-size: 15px; line-height: 20px; padding: 0px; margin-bottom: 0px;">
								<span><b>Tipo Aparatdo: </b> <?php echo $movimientos[0]->tipo_pedido.'-'.$movimientos[0]->tipo_apartado; ?></span><br>

								<?php if ( $movimientos[0]->tipo_apartado=='Vendedor' ) { ?>

								<span><b>Empresa Relacionada: </b> <?php echo $movimientos[0]->comprador; ?></span><br>
								<span><b>Fecha: </b><?php echo $movimientos[0]->fecha_apartado; ?></span><br>

								<?php } else { ?>

								<span><b>Movimiento: </b> <?php echo $movimientos[0]->id_cliente_apartado; ?></span><br>
								<span><b>Vendedor: </b> <?php echo $movimientos[0]->vendedor; ?></span><br>
								
								<span><b>Fecha: </b> <?php echo $movimientos[0]->fecha_apartado; ?></span><br>

								<?php } ?>
								<span><b>Almacén: </b> <?php echo $movimientos[0]->almacen; ?></span><br>


								<?php
								$consecutivo_actual = (( ($movimientos[0]->id_tipo_pedido == 1)) ? $movimientos[0]->tipo_factura : $movimientos[0]->tipo_pedido );
								?>	
								<span><b>Tipo de Pedido: </b> <?php echo $consecutivo_actual; ?></span><br>

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
						<th width="30%">Código</th>
						<th width="25%">Descripción</th>
						<th width="8%">Color</th>
						<th width="10%">Cantidad</th>
						<th width="10%">Ancho</th>
						<th width="<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 9 : 17; ?>%">Lote</th>
						<?php  if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) {
								?>   
									<th width="8%">Precio</th>
								<?php } ?>	

					</tr>
				</thead>	
				<tbody>	
				<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
					<?php foreach( $movimientos as $movimiento ): ?>
						<tr>

							
							<td width="30%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
							<td width="25%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
							<td width="8%" style="border-top: 1px solid #222222;">
								<div style="background-color:#<?php echo $movimiento->hexadecimal_color; ?>;display:block;width:15px;height:15px;margin:0 auto;"></div>
							</td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um; ?> <?php echo $movimiento->medida; ?></td>
							<td width="10%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho; ?> cm</td>
							
							<td width="<?php echo (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) ? 9 : 17; ?>%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_lote.'-'.$movimiento->consecutivo; ?></td>	

							<?php if (($this->session->userdata('id_perfil')==1) || ( (in_array(80, $coleccion_id_operaciones)) || (in_array(81, $coleccion_id_operaciones))   ) ) {
								?>   
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->precio+($movimiento->precio*$movimiento->iva)/100; ?></td>
								<?php } ?>		



						</tr>
					<?php endforeach; ?>
				<?php else : ?>
						<tr class="noproducto">
							<td colspan="9">No se han agregado producto</td>
						</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>