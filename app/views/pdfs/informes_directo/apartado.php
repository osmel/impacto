<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<html lang="es_MX">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>js/bootstrap-3.3.1/dist/css/bootstrap.min.css">
		
</head>
<body>
<?php date_default_timezone_set('America/Mexico_City');  ?>

		<div class="container">
			<div>
				<div>
					





					<table style="width: 100%; border: 2px solid #222222;">
						<thead>



							


							<tr>
								<td colspan="8" style="border-top: 1px solid #222222; ">
									
									<span><b>Fecha y hora: </b> <?php echo date( 'd-m-Y h:i:s A',  strtotime ( gmt_to_local( 'UM1', time(), TRUE)  ) );  ?></span>
									<p ><b >  Apartados</b></p>
									<span><b>Tipo de Salida: </b> <?php echo $factura;  ?></span>


									<table style="width: 80%; font-size:10px;">
										<tr>
											<td width="33%">
												<div class="col-md-1" ><span> Apartado Individual</span><div style="background-color:#ab1d1d;width:15px;height:15px;"></div> </div>
											</td>	
											<td width="33%">
													<div class="col-md-1" ><span> Apartado Confirmado</span><div style="background-color:#f1a914;width:15px;height:15px;"></div></div>
											</td>	

											<td width="33%">
													<div class="col-md-1" ><span> Disponibilidad Salida</span><div style="background-color:#14b80f;width:15px;height:15px;"></div></div>
											</td>	
										</tr>
									</table>



								</td>
								<td colspan="3" style="border-top: 1px solid #222222; ">
								  	
									<?php echo '<img src="'.base_url().'img/unnamed.png" width="93px" height="48px"/>'; ?>
								</td>

							</tr>	





							
							<tr style="font-weight:bold;">
								<th width="<?php echo (($configuracion->activo==0)?18:11);  ?>%">Código</th>
								<th width="18%">Descripción</th>
								
								<th width="8%">Color</th>
								<th width="9%">Cant.</th>
								
								<th width="9%">Ancho</th>
								<th width="5%">Mov.</th>

								<th width="11%">Dependencia</th>
								<th width="8%">Apartado</th>


								<th width="8%">Ingreso</th>
								<?php if (($configuracion->activo==1)) {  ?>
									<th width="7%">Factura</th>
								<?php } ?>	
								<th width="6%">Almacén</th>		
								
							</tr>
						</thead>
						<tbody  style="font-size: 12px;">
						<?php if ( isset($movimientos) && !empty($movimientos) ): ?>
							<?php foreach( $movimientos as $movimiento ): ?>
								<tr style="font-weight:normal;" >
									<td width="<?php echo (($configuracion->activo==0)?18:11);  ?>%" style="border-top: 1px solid #222222;"><?php echo $movimiento->codigo; ?></td>								
									<td width="18%" style="border-top: 1px solid #222222;"><?php echo $movimiento->id_descripcion.'<br/><b style="color:red;">Cód: </b>'.$movimiento->codigo_contable; ?></td>
									
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo $movimiento->color.'<div style="background-color:#'.$movimiento->hexadecimal_color.';display:block;width:15px;height:15px;margin:0 auto;"></div>'; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->cantidad_um.' '.$movimiento->medida; ?></td>
									<td width="9%" style="border-top: 1px solid #222222;"><?php echo $movimiento->ancho.' cm'; ?></td>
									<td width="5%" style="border-top: 1px solid #222222;"><?php echo $movimiento->mov; ?></td>
									
									<?php
		                              if (($movimiento->id_apartado) >=4) {
		                                $tip_apart= " (Tienda)";
		                              } else {
		                                $tip_apart= " (Vendedor)";
		                              }  
		                              $columna6= $movimiento->dependencia.$tip_apart;
		                              ?>							

									<td width="11%" style="border-top: 1px solid #222222;"><?php echo $columna6; ?></td>


									<td width="8%" style="border-top: 1px solid #222222;"><?php echo '<div style="background-color:#'.$movimiento->apartado.';display:block;width:15px;height:15px;margin:0 auto;"></div>';  ?></td>
									<td width="8%" style="border-top: 1px solid #222222;"><?php echo date( 'd-m-Y', strtotime($movimiento->fecha_apartado)); ?></td>
									<?php if (($configuracion->activo==1)) {  ?>
										<td width="7%" style="border-top: 1px solid #222222;"><?php echo $movimiento->factura; ?></td>
									<?php } ?>									

									<td width="6%" style="border-top: 1px solid #222222;"><?php echo $movimiento->almacen; ?></td>
									



								</tr>
							<?php endforeach; ?>
						<?php else : ?>
								<tr class="noproducto">
									<td colspan="9">No se han agregado producto</td>
								</tr>
						<?php endif; ?>	


						
								<tr>
								
									<td colspan="11" style="border-top: 1px solid #222222; ">
											
											<?php  if ($totales->metros>0) { ?>	
												<span><b>Total Metros: </b> <?php echo $totales->metros; ?></span>
											<?php } ?>		
											<?php  if ($totales->kilogramos>0) { ?>	
												<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Kilogramos: </b> <?php echo $totales->kilogramos; ?></span>
											<?php } ?>	

											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Total Piezas: </b><?php echo $totales->pieza; ?></span>

									</td>




								</tr>



						</tbody>	
					
							
					</table>
				</div>
			</div>
		</div>


</body>
</html>				