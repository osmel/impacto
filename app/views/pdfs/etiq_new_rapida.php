<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<html lang="es_MX">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap-3.3.1/dist/css/bootstrap.min.css">
		
</head>
<body>


			<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
				<?php foreach( $movimientos as $movimiento ): ?>
	
					<div>	
						<table style="width: 378px; font-family: Arial, sans-serif;">
							<tbody>
								<tr>
								  <td style="height: 348px; text-align: center; vertical-align: middle;">
										<table style="display: inline-block; text-align: left;  " >
											<tbody >


												<tr >
													<td style="font-size: 12px; width:200px;height:100px;">
														<p style="margin-top: 0px; line-height: 15px;">
															Impacto Textil S.A. de C.V.<br>
															Calle Venustiano Carranza 140<br>
															Centro Histórico, Centro, C.P. 06060, <br>
															D.F., México<br>
															Tels. (55) 5542 5305 - (55) 5542 5304<br>
														</p>
													</td>
													<td style="text-align: center; vertical-align: middle; line-height: 100%;"><br><br>
														<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="50px"/>'; ?>
													</td>
												</tr>
												

												
												<tr>
													<td colspan="1" style="height: 15px; text-align: left;">
														<p style="margin-left: 10px; font-size: 10px; line-height:30px;"><b><?php echo $movimiento->codigo;?></b></p>														
													</td>
													<td colspan="1" style="height: 5px; text-align: center; ">
														<p style="font-size: 10px; line-height:30px;"><?php echo $movimiento->fecha?></p>														
													</td>

												</tr>


												<tr>
													<td colspan="2" style="height: 40px; text-align: center;">
														<p style="margin-left: 10px; font-size: 15px; line-height:30px;"><b><?php echo $movimiento->id_descripcion; ?></b></p>														
													</td>
												
												</tr>





												<tr >

													<td rowspan="1" style="font-size: 15px; text-align: left;width:230px;">
														<b><?php echo $movimiento->cantidad_um;?>  <?php echo $movimiento->medida?> &nbsp;  &nbsp; <?php echo $movimiento->color?></b>
													</td>
													

													<!-- -->
													<td rowspan="6" style="text-align:right;  width:100px; margin:0px; padding:0px;">
														<?php echo '<img src="'.base_url().'qr_code/'.$movimiento->codigo.'.png" width="110px" height="110px"/>'; ?>
													</td>
												</tr>


												<tr >


													<td  style="text-align: left; margin-left: 10px; font-size: 15px; font-weight:bold; ">
															<?php echo $movimiento->num_partida?>
													</td>

												</tr>
												<tr>
													<td style="font-size: 10px; text-align: left; line-height:20px;">
													<?php echo $movimiento->ancho?>m
														    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  <?php echo $movimiento->id_lote; ?>-<?php echo $movimiento->consecutivo; ?><?php echo $movimiento->estatusd; ?>

													</td>
												</tr>

												<tr>
													<!--<td style="font-size: 10px;">

													</td>-->
													<td colspan="1" style="height: 5px; text-align: left;">
													
														<p style="margin-right: 10px; font-size: 10px; line-height:20px;"><?php echo $movimiento->composicion?></p>														
													</td>

												</tr>



												
												<tr>
													

													<?php if ($movimiento->codigo_contable!='') { ?>
														<td style="font-size: 10px; text-align: left;">
															<b style="color:red;">Cód: </b>	
															<?php echo $movimiento->codigo_contable; ?>&nbsp;
														</td> 
													<?php } else { ?>
														<td style="font-size: 5px; text-align: center;">&nbsp;</td>	
													<?php }  ?>



												</tr>												
												<tr>
													<td style="font-size: 5px; text-align: center;">&nbsp;</td>
												</tr>

											
											
												<tr>
													<td style="font-size: 10px;">
														<p style="margin: 0px; padding: 0px; line-height: 12px;">
															<?php //echo $movimiento->comentario; ?>
															Tela cortada no se aceptan devoluciones.
															Lavado normal, agua tibia.
															Planchar a baja temperatura.
															NO USAR CLORO.
														</p>
													</td>
													<td style="text-align: center; vertical-align: middle; line-height: 100%">
														<?php echo '<img src="'.base_url().'img/hem.png"  style="margin-top:15px;" width="60px" height="50px"/>'; ?>
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

</body>
</html>	