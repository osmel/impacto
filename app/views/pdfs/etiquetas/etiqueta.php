<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">


		<div class="row">

			<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
				<?php foreach( $movimientos as $movimiento ): ?>
	
					<div class="col-sm-6 col-md-6">	
						<table style="width: 378px; font-family: Arial, sans-serif;">
							<tbody>
								<tr>
									<td style="height: 378px; text-align: center; vertical-align: middle;">
										<table style="display: inline-block; text-align: left; width: 358px; border: 1px solid #000000;">
											<tbody>
												<tr>
													<td colspan="2">
														<p style="padding-left: 10px; ">&nbsp;</p>
													</td>
												</tr>
												<tr>
													<td style="font-size: 10px;">
														<p style="margin-top: 10px; line-height: 10px;">
															&nbsp;Impacto Textil S.A. de C.V.<br>
															&nbsp;Calle Venustiano Carranza 140<br>
															&nbsp;Centro Histórico, Centro, C.P. 06060, <br>
															&nbsp;D.F., México<br>
															&nbsp;Tels. (55) 5542 5305 - (55) 5542 5304<br>
														</p>
													</td>
													<td style="text-align: center; vertical-align: middle; line-height: 100%">
														<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="50px"/>'; ?>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="height: 5px;"></td>
												</tr>
												<tr>
													<td colspan="2" style="height: 5px;"><hr style="border-style: dotted; margin-top: 20px;"></td>
												</tr>
												<tr>
													<td colspan="2" style="height: 5px; text-align: center;">
														<?php echo $movimiento->codigo?>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="height: 5px;">&nbsp;</td>
												</tr>

												<tr>
													<td style="text-align: left;">
														<p style="margin-left: 10px; font-size: 20px;"><b><?php echo $movimiento->id_lote?></b>-<b><?php echo $movimiento->consecutivo?></b></p>
													</td>
												</tr>

												<tr>
													<td colspan="2" style="height: 5px;">&nbsp;</td>
												</tr>


												<tr>
													<td style="text-align: left;">
														<p style="margin-left: 10px; font-size: 15px;"><b><?php echo $movimiento->id_descripcion?></b></p>
													</td>
													<td style="font-size: 12px; text-align: center;">
														<?php echo $movimiento->fecha?>
													</td>
												</tr>
												<tr>
													<td style="font-size: 12px;">
														<?php echo $movimiento->composicion?>
													</td>
													<td rowspan="5" style="text-align: center;">
														<?php echo '<img src="'.base_url().'qr_code/'.$movimiento->codigo.'.png" width="100px" height="100px"/>'; ?>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>
												<tr>
													<td style="font-size: 15px; text-align: left;">
														<b><?php echo $movimiento->ancho;?>m <?php echo $movimiento->color?></b>
													</td>
												</tr>
												<tr>
													<td style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>
												<tr>
													<td style="font-size: 20px;"><b><?php echo $movimiento->cantidad_um?> <?php echo $movimiento->medida?></b></td>
												</tr>
												<tr>
													<td style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>
												<tr>
													<td style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>
												<tr>
													<td style="font-size: 12px;">
														<p style="margin: 0px; padding: 0px; line-height: 12px;">
															<?php //echo $movimiento->comentario; ?>
															Tela cortada no se aceptan devoluciones.
															Lavado normal, agua tibia.
															Planchar a baja temperatura.
															NO USAR CLORO.
														</p>
													</td>
													<td style="text-align: center;">
														<?php echo '<img src="'.base_url().'img/hem.png" width="60px" height="50px"/>';?>
													</td>
												</tr>
												<tr>
													<td colspan="2" style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>

					</div>			

				<?php endforeach; ?>
				<?php else : ?>
							<h4>No existen productos</h4>
				<?php endif; ?>		


		
		</div>
	

</div>