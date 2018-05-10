<meta charset="UTF-8">
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>
<div class="container" style="background-color:transparent !important">
<?php 

	
   $coleccion_id_operaciones= json_decode($this->session->userdata('coleccion_id_operaciones')); 
   if ( (count($coleccion_id_operaciones)==0) || (!($coleccion_id_operaciones)) ) {
        $coleccion_id_operaciones = array();
   }   

 	if (!isset($retorno)) {
      	$retorno ="productos";
    }
	 $attr = array('class' => 'form-horizontal', 'id'=>'form_prod','name'=>$retorno,'method'=>'POST','autocomplete'=>'off','role'=>'form');
?>	
<div class="container row" style="background-color:transparent !important">
<?php
	 echo form_open('validar_nuevo_producto', $attr);
?>		
	
	
		<div class="panel panel-primary">
			<div class="panel-heading">Datos del producto</div>
			<div class="panel-body">
				<div class="col-sm-12 col-md-6">

					<div class="form-group">

						<label for="descripcion" class="col-sm-12 col-md-12">Nombre</label>
						<div class="col-sm-12 col-md-12">
							<input restriccion="nominuscula" title="Letras mayusculas, números y espacios." type="text" class="form-control ttip" id="descripcion" name="descripcion" placeholder="Nombre">

						</div>
					</div>

					<div class="form-group">
						<label for="ancho" class="col-sm-12 col-md-12">Ancho (cm)</label>
						<div class="col-sm-12 col-md-12">
							<input restriccion="decimal" type="text" class="form-control ttip" title="Números y puntos decimales." id="ancho" name="ancho" placeholder="ancho">
						</div>
					</div>					

					
					<div class="form-group">
						<label for="precio" class="col-sm-12 col-md-12">Precio por rollo</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control ttip" title="Números y puntos decimales."  restriccion="decimal" id="precio" name="precio" placeholder="precio">
						</div>
					</div>					
					


 <!-- colores -->  
 
        <span class="row mini-bloque multiples">
            <!-- alfabeto -->  
            
	            <span class="col-sm-1 col-md-1" id="coloresSeccion">
	                <label id="alfabetoColores" ></label>
	            </span>
	        
            <!-- bloque izquierdo colores -->  
            <span class="col-sm-5 col-md-5">
                <label for="color_producto">Colores </label>
                <select name="colores[]" id="lista_colores" multiple class="select"></select>
            </span>

            <!-- bOTONES AGREGAR Y QUITAR -->  
            <span class="col-sm-1 col-md-1" id="addremove">
                <input style="height: 60px; border-radius:0px;" type="button" name="agregar_color" value=">>" id="agregar_color">
                <input style="height: 60px; border-radius:0px;" type="button" name="quitar_color" value="<<" id="quitar_color">
            </span>

            <!-- bloque derecho "colores SELECCIONADOS" -->  
            <span class="col-sm-5 col-md-5">
                <label for="color_producto">Colores seleccionados </label>
                <select name="colores_seleccionados[]" id="colores_seleccionados" multiple class="select"></select>
            </span>
            <!-- <em>Los colores están filtrados por letra del alfabeto.</em> -->
            
         <div class="col-sm-12 col-md-12"> <em>Los colores están filtrados por letra del alfabeto.</em></div>    
        </span>


   <!-- fin de colores -->


				<!--composicion -->	
				  <div class="form-group">
						<label for="descripcion" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Composición</label>

			            <?php if ( ($this->session->userdata('id_perfil') ==1) or (in_array(8, $coleccion_id_operaciones)) ) { ?>
			              <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
			            <?php } else {?>      
			              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			            <?php } ?>                

						<select name="id_composicion" id="id_composicion" class="form-control">
							<?php foreach ( $composiciones as $composicion ){ ?>
								<option value="<?php echo $composicion->id; ?>"><?php echo $composicion->composicion; ?></option>
							<?php } ?>
						</select>
					  </div>

					  <?php if ( ($this->session->userdata('id_perfil') ==1) or (in_array(8, $coleccion_id_operaciones)) ) { ?>
						  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							  <a href="<?php echo base_url(); ?>catalogo_modal/<?php echo base64_encode("composicion"); ?>/<?php echo base64_encode($this->uri->uri_string()); ?>"  
								class="btn btn-default" data-toggle="modal" data-target="#modalMessage">
								<span class="glyphicon glyphicon-plus"></span>
							  </a>
						  </div>
					  <?php } ?>      
				  </div>        

				<!--calidades -->
				  <div class="form-group">
						<label for="calidad" class="col-xs-10 col-sm-10 col-md-10 col-lg-10">Calidad</label>

			            <?php if ( ($this->session->userdata('id_perfil') ==1) or (in_array(8, $coleccion_id_operaciones)) ) { ?>
			              <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
			            <?php } else {?>      
			              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			            <?php } ?>                

						<select name="id_calidad" id="id_calidad" class="form-control">
							<?php foreach ( $calidades as $calidad ){ ?>
								<option value="<?php echo $calidad->id; ?>"><?php echo $calidad->calidad; ?></option>
							<?php } ?>
						</select>
					  </div>
					  <?php if ( ($this->session->userdata('id_perfil') ==1) or (in_array(8, $coleccion_id_operaciones)) ) { ?>
						  <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							  <a href="<?php echo base_url(); ?>catalogo_modal/<?php echo base64_encode("calidad"); ?>/<?php echo base64_encode($this->uri->uri_string()); ?>"  
								class="btn btn-default" data-toggle="modal" data-target="#modalMessage">
								<span class="glyphicon glyphicon-plus"></span>
							  </a>
						  </div>
					  <?php } ?>      	  
				  </div>
				  
					</div>
				<div class="col-sm-12 col-md-6">				
					<div class="form-group">
						<label for="minimo" class="col-sm-12 col-md-12">Cantidad mínima de mts</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control ttip minimo" title="Números y puntos decimales." restriccion="decimal" id="minimo" name="minimo" placeholder="minimo mts">
						</div>
					</div>		
					
					<div class="form-group">
						<label for="minimo_kg" class="col-sm-12 col-md-12">Cantidad mínima de kgs</label>
						<div class="col-sm-12 col-md-12">
							<input type="text" class="form-control ttip minimo" title="Números y puntos decimales." restriccion="decimal" id="minimo_kg" name="minimo_kg" placeholder="minimo kg">
						</div>
					</div>		

					<!-- Imagen-->	
					<div class="form-group">
						<div class="col-sm-12 col-md-12">
							<div class="panel-heading">
								<h4 class="azul bloque-informacion-azul">Imagen</h4>
							</div>
							<div class="panel-body">
								<input type="file" class="ttip" title="Archivo .jpg, .png, .gif." name="archivo_imagen" id="archivo_imagen" size="20">
							</div>
						</div>
					</div>					

					<!-- comentarios-->	
					<div class="form-group">
						<label for="comentario" class="col-sm-12 col-md-12">Especificaciones</label>
						<div class="col-sm-12 col-md-12">
							<textarea class="form-control" name="comentario" id="comentario" rows="6" placeholder="Comentarios"></textarea>
						</div>
					</div>						
	
					<!-- -->





				</div>

			</div>

			<div class="container row">
				<div class="col-sm-4 col-md-4"></div>
				<div class="col-sm-4 col-md-4 marginbuttom">
					<a href="<?php echo base_url(); ?><?php echo $retorno; ?>" type="button" class="btn btn-danger btn-block">Cancelar</a>
				</div>
				<div class="col-sm-4 col-md-4">
					<input type="submit" class="btn btn-success btn-block" value="Guardar"/>
				</div>
			</div>
			<br>

		</div>

	</div>

<?php echo form_close(); ?>
</div>
<div class="modal fade bs-example-modal-lg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin-top:150px !important">
        <div class="modal-content"></div>
    </div>
</div>	
<?php $this->load->view('footer'); ?>






<script type="text/javascript">
 
    $(document).ready(function() {
        mostrarColores(null, "#");
        var alfabeto = "#ABCDEFGHIJKLMNÑOPQRSTUVWXYZ*";
        for (var i = 0; i < alfabeto.length; i++) {
            if (i === 0) {	//color:#007698;
                var enlace = "<a onclick='mostrarColores(this,0);' style='text-decoration:underline;color:#000000;margin-top:5px;cursor:pointer;display:inline-block;'>" + alfabeto.charAt(i) + "</a><br />";
            } else {
                var enlace = "<a onclick='mostrarColores(this,0);' style='color:#007698;margin-top:5px;cursor:pointer;display:inline-block;'>" + alfabeto.charAt(i) + "</a><br />";
            }
            $('#coloresSeccion #alfabetoColores').append(enlace);
        }
        ;
    });

    function mostrarColores(ele, indice) {
        if (indice === 0) {
            letra = $(ele).html();
        } else {
            letra = indice;
        }
        $('#coloresSeccion #alfabetoColores a').css('text-decoration', '');
        $('#coloresSeccion #alfabetoColores a').css('color', '#007698');
        $(ele).css('text-decoration', 'underline');
        $(ele).css('color', '#000000');

        var arreglo_colores=[]; 
		jQuery( "#colores_seleccionados > option" ).each(function() {
	      arreglo_colores.push(this.value); 
	    });


        $('#lista_colores').html('');
        $.ajax({
            dataType: "json",
            type: 'POST',
            url: '<?php echo base_url(); ?>catalogos/coloresAjax', //Realizaremos la petición al metodo list_dropdown del controlador match
            data: {indiceColor: letra,
            	arreglo_colores: arreglo_colores,
            }, //Pasaremos por parámetro POST el id del torneo
            success: function(resp) {
                for (i = 0; i < resp.length; i++) {
                    var opcion = '<option style = "background-color:#' + resp[i].hexadecimal_color + '" value = "' + resp[i].color_uid + '" >' + resp[i].nombre_color + '</option>';
                    $('#lista_colores').append(opcion);
                }
            }
        });
    }
</script>