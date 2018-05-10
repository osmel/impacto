<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
		</div>

	</div>
		<footer class="container-fluid navbar-fixed-bottom footer">
			<div class="container">
					
					<div style="margin-top:10px">
                        <span style="color:#ffffff;">Sitio desarrollado por: 
                        	<a href="http://estrategasdigitales.com/" target="_blank">Estrategas Digitales. 2018.</a></span>
                    	</span>
                    </div>


			</div>
		</footer>

	<!-- SCRIPTS -->
	<?php  echo link_tag('css/fontello.css');  ?>

	<!-- ****quitar -->
	<!--
	   <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
	-->   

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	 

	<!-- componente fecha simple -->
	<?php echo link_tag('css/bootstrap-datepicker.css'); ?>
	
	<!-- componente rango fecha -->
	<?php echo link_tag('css/daterangepicker-bs3.css'); ?>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/spin.min.js"></script>

	<!-- componente fecha simple -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>

	<!-- componente rango fecha -->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/moment.js"></script>		
	<script type="text/javascript" src="<?php echo base_url(); ?>js/daterangepicker.js"></script>		
	
	<!-- nuestro js principal -->

	<script type="text/javascript" src="<?php echo base_url(); ?>js/sistema.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script> 	

	 <link href="//raw.github.com/jharding/typeahead.js-bootstrap.css/master/typeahead.js-bootstrap.css" rel="stylesheet" media="screen">
	
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/typeahead/dist/typeahead.jquery.min.js"></script>	
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/typeahead/dist/typeahead.bundle.js"></script>	
	

	<!-- Catalogo de colores -->	
	<?php echo link_tag('css/colorpicker.css'); ?>
	<?php echo link_tag('css/manager.css'); ?>
	<script src="<?php echo base_url(); ?>js/colorpicker.js" type="text/javascript"></script>
	


	<!--cascada -->
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/knockout/2.3.0/knockout-min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/cascada/jquery.mockjax.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/cascada/ajax-mocks.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/cascada/jquery.cascadingdropdown.js"></script>
																			

	<!--para conversion a base64.encode y base64.decode -->
	<script src="<?php echo base_url(); ?>js/base64/jquery.base64.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>js/base64/jquery.base64.min.js" type="text/javascript"></script>
	

	<!--datatables para el caso de salidas -->
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/media/css/jquery.dataTables.css">
	
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/media/js/jquery.dataTables.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/extensions/TableTools/css/dataTables.tableTools.css">
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/extensions/TableTools/js/dataTables.tableTools.js"></script>
	
	<?php  if ($this->session->userdata('session')) {  ?>
		<script src="<?php echo base_url();?>nodejs/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
		<script src="<?php echo base_url();?>js/socket.js"></script>
	<?php } ?>			



</body>
</html>