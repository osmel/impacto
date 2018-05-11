jQuery(document).ready(function($) {

/*
jQuery('#id_tipo_pedido[pantalla="pedir"], #id_tipo_factura[pantalla="pedir"]').on('change', function(e) {
	
	consecutivo_actual = (( (jQuery("#id_tipo_pedido").val() == 1) && (jQuery("#id_tipo_factura").val()==1) ) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
	consecutivo_actual = ( (jQuery("#id_tipo_pedido").val()==2) ? jQuery("#conse_surtido").val() : consecutivo_actual);
	consecutivo_actual = ( (jQuery("#id_tipo_pedido").val()==3) ? jQuery("#conse_bodega").val() : consecutivo_actual);

	jQuery("#movimiento").val(consecutivo_actual);
});
*/

jQuery('#id_tipo_pedido[pantalla="pedir"]').on('change', function(e) {
	console.log(jQuery(this).val());
	if  (jQuery(this).val()==3) {
		jQuery('#id_tipo_factura option:eq(0)').prop('selected', 'selected');
		 jQuery('.tipo_factura').css('display','none');			

		 	 
	} else if  (jQuery(this).val()==2)  {
			jQuery('#id_tipo_factura option:eq(0)').prop('selected', 'selected');
		jQuery('.tipo_factura').css('display','none');			
			 
	} else {
		jQuery('.tipo_factura').css('display','block');	
		
	}

	var mi_perfil = jQuery('#mi_perfil').val();

	if ( jQuery("#mi_perfil").val() !='4') {
		var oTable =jQuery('#tabla_apartado').dataTable();
		oTable._fnAjaxUpdate();		
	}
	if ( jQuery("#mi_perfil").val() !='3') {
		var oTable =jQuery('#tabla_pedido').dataTable();
		oTable._fnAjaxUpdate();		
	}	
	var oTable =jQuery('#tabla_pedido_completado').dataTable();
	oTable._fnAjaxUpdate();		

});

jQuery('#id_tipo_factura[pantalla="pedir"]').on('change', function(e) {

	var mi_perfil = jQuery('#mi_perfil').val();

	if ( jQuery("#mi_perfil").val() !='4') {
		var oTable =jQuery('#tabla_apartado').dataTable();
		oTable._fnAjaxUpdate();		
	}
	if ( jQuery("#mi_perfil").val() !='3') {
		var oTable =jQuery('#tabla_pedido').dataTable();
		oTable._fnAjaxUpdate();		
	}	
	var oTable =jQuery('#tabla_pedido_completado').dataTable();
	oTable._fnAjaxUpdate();		

	//jQuery('#pedido_entrada').dataTable().fnDraw();
});


jQuery('.fecha_reporte_pedido').daterangepicker(
  	  { 
	    locale: { cancelLabel: 'Cancelar',
	    		  applyLabel: 'Aceptar',
	    		  fromLabel : 'Desde',
	    		  toLabel: 'Hasta',
	    		  monthNames : "ene._feb._mar_abr._may_jun_jul._ago_sep._oct._nov._dec.".split("_"),
	    		  daysOfWeek: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
	     } , 
	    separator: ' / ',
	    format: 'DD-MM-YYYY',
	    //startDate: fecha_hoy, //'2014/09/01',
	    //endDate: fecha_hoy //'2014/12/31'
	  }
  );


jQuery('.fecha_reporte_pedido').on('apply.daterangepicker', function(ev, picker) {
	var mi_perfil = jQuery('#mi_perfil').val();

	if ( jQuery("#mi_perfil").val() !='4') {
		var oTable =jQuery('#tabla_apartado').dataTable();
		oTable._fnAjaxUpdate();		
	}
	if ( jQuery("#mi_perfil").val() !='3') {
		var oTable =jQuery('#tabla_pedido').dataTable();
		oTable._fnAjaxUpdate();		
	}	
	var oTable =jQuery('#tabla_pedido_completado').dataTable();
	oTable._fnAjaxUpdate();	

});


//////////

jQuery('#id_almacen_pedido').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	
	var mi_perfil = jQuery('#mi_perfil').val();

	if ( jQuery("#mi_perfil").val() !='4') {
		var oTable =jQuery('#tabla_apartado').dataTable();
		oTable._fnAjaxUpdate();		
	}
	if ( jQuery("#mi_perfil").val() !='3') {
		var oTable =jQuery('#tabla_pedido').dataTable();
		oTable._fnAjaxUpdate();		
	}	
	var oTable =jQuery('#tabla_pedido_completado').dataTable();
	oTable._fnAjaxUpdate();		
	
});
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//////////////////////////////////////////////////
//////////////////////////////////////////////////

if (jQuery("#id_perfil").val()==1) {
	jQuery('#rol_perfil').css('display','none');	
} else {
	jQuery('#rol_perfil').css('display','block');	
}



jQuery("#id_perfil").on('change', function(e) {
	if (jQuery(this).val()==1) {
		jQuery('#rol_perfil').css('display','none');	
	} else {
		jQuery('#rol_perfil').css('display','block');	
	}
});

//////////////////PRESENTAR LOS ROLES DE ALMACEN EN EL USUARIO////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

if (jQuery("#id_perfil").val()==2) {
	 jQuery('#rol_almacen').css('display','block');	
} else {
	jQuery('#rol_almacen').css('display','none');	
}



jQuery("#id_perfil").on('change', function(e) {
	if (jQuery(this).val()==2) {
		jQuery('#rol_almacen').css('display','block');			
	} else {
		jQuery('#rol_almacen').css('display','none');	
	}
});


//////////////////////////////////////////////////
///////////DIBUJAR NAV-BAR COLOR HOVER/////////////
//////////////////////////////////////////////////
//jQuery(window).load(function() {
		var hash_url = window.location.pathname;

		

		/*
			La verdad, no siento molestia, siento indignación, porq de los amigos q tengo en el facebook, que son de nacionalidad cubana, 
			ninguno fue perseguido politico, ninguno es hijo de desendencia millonaria $$$, ninguno a logrado rebasar las posibilidades que tenia en cuba,
			ninguno esta en la lista de nuevo millonario $$$, los millonarios de $$$ en cuba se fueron junto con batista. Y aclaro millonario de $$$, pero ademas a ninguno en cuba lo conoci como oposición de fidel dentro de cuba, si lo que quieren es vender otra persona fuera de cuba se auto-engañan y mienten a los nuevos conocidos. que levante la mano, que tire la piedra aquel que no recibio apoyo de fidel o de la revolución cubana
		*/


		jQuery.ajax({
				url : '/establecer_modulo',
		        //url : 'http://104.236.91.215/establecer_modulo',
		        //url : 'http://inventarios.dev.com/establecer_modulo',
		        //url : 'establecer_modulo',
		        data:{
		        	hash_url:hash_url
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(midata) {
		        	console.log(midata);

						jQuery(".navbar-nav > li" ).each(function( index ) {
							  if ('bar_'+midata == jQuery( this ).attr('id')) {
							      jQuery(this).addClass('activo_bar');						  	
							  } else {
							  	  jQuery(this).removeClass('activo_bar');						  	
							  };
						});

						   //pedidos	
						  if ('bar_'+midata == 'bar_pedidos') {
							      jQuery('#bar_pedidos').addClass('activo_bar');						  	
							  } else {
							  	  jQuery('#bar_pedidos').removeClass('activo_bar');						  	
						  };

						  //home
						  if (midata == false) {
							      jQuery('#bar_').addClass('activo_bar');						  	
							  } else {
							  	  jQuery('#bar_').removeClass('activo_bar');						  	
						  };

						  
		        		
		            //return false;
		        },
		        error : function(jqXHR, status, error) {
		        	//
		        },
		        complete : function(jqXHR, status) {
		        	//
		            
		        }
		}); 
//}); //fin de la carga

		//INICIO	
/*
	if  ( (hash_url=="/") )   {   //activo_bar
				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_consulta_totales').dataTable();
				oTable._fnAjaxUpdate();
    	}	

    	$('ol li').each(function(indice, elemento) {
  console.log('El elemento con el índice '+indice+' contiene '+$(elemento).text());
});
*/

/*
0: bar_entradas
sistema.js:24 1: bar_devolucion
sistema.js:24 2: bar_salidas
sistema.js:24 3: bar_generar_pedidos
sistema.js:24 4: bar_editar_inventario
sistema.js:24 5: bar_reportes
sistema.js:24 6: bar_catalogos
sistema.js:24 7: bar_usuarios
sistema.js:24 8: bar_salir

*/



jQuery(".navbar-nav > li" ).each(function( index ) {
  //console.log( index + ": " + jQuery( this ).text() );
//  console.log( index + ": " + jQuery( this ).attr('id') );

//  ident = jQuery( this ).attr('id');

//  console.log(hash_url);

});

//////////////////////////////////////////////////
//////////////////////////////////////////////////

//Agregar las estradas a salidas
jQuery('body').on('click','#exportar_totales', function (e) {

	   	     busqueda      = jQuery('input[type=search]').val();
 		    id_descripcion = jQuery("#producto_totales").val();
		  if (id_descripcion !='') {
	   	    id_descripcion = jQuery('#producto_totales option:selected').text();
		  }
		  id_composicion = jQuery('#composicion_totales').val();  		  
		   	     ancho = jQuery('#ancho_totales').val();  		  
			  id_color = jQuery('#color_totales').val();  		  
		  id_proveedor = jQuery('#proveedor_totales').val();  
		  

		 var fecha = (jQuery('.fecha_totales').val()).split(' / ');
 		 fecha_inicial = fecha[0];
		 fecha_final = fecha[1];

		 id_almacen = jQuery('#id_almacen_totales').val();  

    abrir('POST', 'exportar_totales', {

			busqueda:busqueda,
			id_descripcion:id_descripcion, 
			id_composicion:id_composicion, 
			    ancho:ancho, 
			id_color:id_color, 
			id_proveedor:id_proveedor,
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen : id_almacen,
    		
    }, '_blank' );
		        
	
});



//Agregar las estradas a salidas
jQuery('body').on('click','#impresion_totales', function (e) {
	   	     busqueda      = jQuery('input[type=search]').val();

 		    id_descripcion = jQuery("#producto_totales").val();
		  if (id_descripcion !='') {
	   	    id_descripcion = jQuery('#producto_totales option:selected').text();
		  }

			id_composicion = jQuery('#composicion_totales').val();  		  
			   	     ancho = jQuery('#ancho_totales').val();  		  
				  id_color = jQuery('#color_totales').val();  		  
			  id_proveedor = jQuery('#proveedor_totales').val();  

		var fecha = (jQuery('.fecha_totales').val()).split(' / ');

			fecha_inicial = fecha[0];
			fecha_final = fecha[1];
			id_almacen = jQuery('#id_almacen_totales').val();  


    abrir('POST', 'imprimir_totales', {

   			busqueda:busqueda,
			id_descripcion:id_descripcion, 
			id_composicion:id_composicion, 
			    ancho:ancho, 
			id_color:id_color, 
			id_proveedor:id_proveedor,
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_almacen: id_almacen,
			almacen : jQuery('#id_almacen_totales option:selected').text(),

    }, '_blank' );
    
	
});



////////////////////////////////////

	jQuery("#mifoco").focusout(function (e) {
	 	comienzo=true; 
		var oTable =jQuery('#tabla_consulta_totales').dataTable();
		oTable._fnAjaxUpdate();

	});


    jQuery('.fecha_totales').daterangepicker(
	  	  { 
		    locale: { cancelLabel: 'Cancelar',
		    		  applyLabel: 'Aceptar',
		    		  fromLabel : 'Desde',
		    		  toLabel: 'Hasta',
		    		  monthNames : "ene._feb._mar_abr._may_jun_jul._ago_sep._oct._nov._dec.".split("_"),
		    		  daysOfWeek: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
		     } , 
		    separator: ' / ',
		    format: 'DD-MM-YYYY',
		  }
    );

	jQuery('.fecha_totales').on('apply.daterangepicker', function(ev, picker) {
		comienzo=true; 
		var oTable =jQuery('#tabla_consulta_totales').dataTable();
		oTable._fnAjaxUpdate();

	});


	jQuery("#id_almacen_totales").change(function(e) {
		comienzo=true; 
		var oTable =jQuery('#tabla_consulta_totales').dataTable();
		oTable._fnAjaxUpdate();

	});


	//,  #proveedor_totales
    jQuery("#producto_totales, #composicion_totales, #ancho_totales, #color_totales, #proveedor_totales").on('change', function(e) {
		 var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_totales option:selected').text();  		  //elemento** id
 		 var val_comp = jQuery('#composicion_totales').val();  		  //elemento** id
 		 var val_ancho = jQuery('#ancho_totales').val();  		  //elemento** id
 		 var val_color = jQuery('#color_totales').val();  		  //elemento** id
 		 var val_proveedor = jQuery('#proveedor_totales').val();  		  //elemento** id

         var dependencia = jQuery(this).attr("dependencia"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
    	if (dependencia !="") {	    
	        //limpiar la dependencia
	        jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	        cargarDependencia_totales(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre);
        }


		var hash_url = window.location.pathname;


		if  ( (hash_url=="/consulta_totales") )   {  

				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_consulta_totales').dataTable();
				oTable._fnAjaxUpdate();
    	}	



     });




	function cargarDependencia_totales(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre) {
		
		var url = 'cargar_dependencia_totales';	
		jQuery.ajax({
		        url : 'cargar_dependencia_totales',
		        data:{
		        	campo:campo,
	        	
		        	val_prod:val_prod,
		        	val_comp:val_comp,
		        	val_ancho: val_ancho,
		        	val_color:val_color,		        	
		        	val_proveedor:val_proveedor,

		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
                    
					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	
					
					jQuery("#"+dependencia).trigger('change');

                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}




	jQuery('#tabla_consulta_totales').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_consulta_totales",
	         		"type": "POST",

	         		 "data": function ( d ) {



   					  if (comienzo) {
         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   }


		 			  d.id_descripcion = jQuery("#producto_totales").val();
	 				  if (d.id_descripcion !='') {
   				   	    d.id_descripcion = jQuery('#producto_totales option:selected').text();
	 				  }

 		 				d.id_composicion = jQuery('#composicion_totales').val();  		  
 		 				   	     d.ancho = jQuery('#ancho_totales').val();  		  
 		 					  d.id_color = jQuery('#color_totales').val();  		  
 		 				  d.id_proveedor = jQuery('#proveedor_totales').val();  
 		 				  

						var fecha = (jQuery('.fecha_totales').val()).split(' / ');

							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];

							d.id_almacen = jQuery('#id_almacen_totales').val();  



	    			 }
	         		
	     },   

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [		    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3]
		            },
		            
		        ],




  "fnPreDrawCallback": function (oSettings) {
		if (comienzo) {
			oSettings._iDisplayStart = 0;  //comienza en cero siempre q cambia de botones
			comienzo=false;
		}
  },

	"infoCallback": function( settings, start, end, max, total, pre ) {


	    if (settings.json.subtotales) {
		    jQuery('#subtotal_entrada').html( 'Total de Entradas:'+ settings.json.subtotales.totale);
			jQuery('#subtotal_salida').html( 'Total de Salidas:'+ settings.json.subtotales.totals);
			jQuery('#subtotal_devoluciones').html('Total de Devoluciones:'+ settings.json.subtotales.totald);
		} else {
		    jQuery('#subtotal_entrada').html( 'Total de Entradas: 0');
			jQuery('#subtotal_salida').html( 'Total de Salidas: 0');
			jQuery('#subtotal_devoluciones').html('Total de Devoluciones: 0');

		}	


	    if (settings.json.totales) {
		    jQuery('#total_entrada').html( 'Total de Entradas:'+ settings.json.totales.totale);
			jQuery('#total_salida').html( 'Total de Salidas:'+ settings.json.totales.totals);
			jQuery('#total_devoluciones').html('Total de Devoluciones:'+ settings.json.totales.totald);
		} else {
		    jQuery('#total_entrada').html( 'Total de Entradas: 0');
			jQuery('#total_salida').html( 'Total de Salidas: 0');
			jQuery('#total_devoluciones').html('Total de Devoluciones: 0');

		}	

			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportetotal").attr('disabled', true);					
			} else {
				jQuery("#disa_reportetotal").attr('disabled', false);					
			}



	    return pre;
	  } ,



	});	















////////////////////////////////////////////////////////////////////////////////


	jQuery('body').on('submit','#form_apartado_eliminar_vendedor', function (e) {

			jQuery('#foo').css('display','block');
			var spinner = new Spinner(opts).spin(target);
			jQuery(this).ajaxSubmit({
				success: function(data){
					if(data != true){


						
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					

					}else{

						    $catalogo = e.target.name;
							spinner.stop();
							jQuery('#foo').css('display','none');
							window.location.href = '/'+$catalogo;	
					}
				} 
			});
			return false;
	});	



//¿Cómo bloquear o restringir los caracteres especiales de campos de entrada con jquery?
//http://www.iteramos.com/pregunta/21206/como-bloquear-o-restringir-los-caracteres-especiales-de-campos-de-entrada-con-jquery
//http://www.mkyong.com/jquery/jquery-attribute-selector-examples/




// catalogo de proveedores
jQuery('#codigo[restriccion="numletra"]').bind('keypress paste', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});



// entradas y editar y "minimo"
jQuery('#cantidad_royo[restriccion="entero"]').bind('keypress paste', function (event) {
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});

/*
jQuery('#minimo[restriccion="entero"]').bind('keypress paste', function (event) {
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});
*/

jQuery('body').on('keypress paste','.minimo[restriccion="decimal"], .pedido_compra[restriccion="decimal"], .cant_solicitada[restriccion="decimal"], .cant_aprobada[restriccion="decimal"]', function (e) {	
    var nn = jQuery(this);
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});


/*
jQuery('body').on('keypress paste','.pedido_compra[restriccion="decimal"]', function (e) {	
	
    //var nn = jQuery('.peso_real[restriccion="decimal"]');
    var nn = jQuery(this);
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});
*/

/*
nominuscula
// catalogo de proveedores
jQuery('#codigo[restriccion="numletra"]').bind('keypress paste', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});
*/

var reg1 = /^['A-Z0-9 ]*$/;

jQuery('#descripcion[restriccion="nominuscula"]').bind('keypress paste', function (e) {
    var nn = jQuery('#descripcion[restriccion="nominuscula"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue); //.toUpperCase();
    
    var bool = reg1.test(strValue);
    //console.log(strValue);
    //console.log(bool);

    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});

var reg2 = /^['A-Z0-9]{0,6}$/;
jQuery('#codigo_contable[restriccion="nominuscula"]').bind('keypress paste', function (e) {
    var nn = jQuery('#codigo_contable[restriccion="nominuscula"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue); 
    var bool = reg2.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});



var reg = /^[0-9]{1,10}(\.[0-9]{0,2})?$/;

jQuery('#cantidad_um[restriccion="decimal"]').bind('keypress paste', function (e) {
    var nn = jQuery('#cantidad_um[restriccion="decimal"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});



jQuery('#ancho[restriccion="decimal"]').bind('keypress paste', function (e) {
    var nn = jQuery('#ancho[restriccion="decimal"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});

jQuery('#precio[restriccion="decimal"]').bind('keypress paste', function (e) {
    var nn = jQuery('#precio[restriccion="decimal"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});





jQuery('#importe[restriccion="decimal"]').bind('keypress paste', function (e) {
    var nn = jQuery('#importe[restriccion="decimal"]');
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});




///////////////////////Formatear
          //http://phpjs.org/functions/number_format/
function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}
/////////////////////////buscar proveedores consulta


	var consulta_proveedor_consulta = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_proveedor_consulta').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_proveedor_consulta.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_proveedor_consulta.tt-input').attr("idproveedor"));
	            
	            return  q;
	        }
	    },   

	});

	consulta_proveedor_consulta.initialize();

	jQuery('.buscar_proveedor_consulta').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_proveedor_consulta',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor_consulta.ttAdapter(),
	   templates: {
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor_consulta').on('typeahead:selected', function (e, datum,otro) {
	    comienzo=true;  //para indicar que start comience en 0;
	    key = datum.key;
		var oTable =jQuery('#tabla_consulta_proveedor').dataTable();
		oTable._fnAjaxUpdate();


	});	

	jQuery('.buscar_proveedor_consulta').on('typeahead:closed', function (e) {
		comienzo=true;  //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_consulta_proveedor').dataTable();
		oTable._fnAjaxUpdate();

	});	


////////////////// Fin de consulta////////////////////////////////////////////////////////////
/////////////////////////////////




/////////////////////////buscar proveedores consulta

	var buscar_producto_cons = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_producto_consulta').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_producto_consulta.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_producto_consulta.tt-input').attr("idproveedor"));
	            
	            return  q;
	        }
	    },   

	});


buscar_producto_cons.initialize();

	jQuery('.buscar_producto_consulta').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_producto_consulta',
	  displayKey: 'descripcion', //
	  source: buscar_producto_cons.ttAdapter(),
	   templates: {
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>';

		   }
	    
	  }
	});

	jQuery('.buscar_producto_consulta').on('typeahead:selected', function (e, datum,otro) {
	    comienzo=true;  //para indicar que start comience en 0;
	    key = datum.key;

		jQuery("#color_consulta").html(''); 
		$producto= jQuery('.buscar_producto_consulta').typeahead("val");
		cargarDependencia_producto($producto);



		var oTable =jQuery('#tabla_consulta_producto').dataTable();
		oTable._fnAjaxUpdate();



	});	

	jQuery('.buscar_producto_consulta').on('typeahead:closed', function (e) {
		comienzo=true;  //para indicar que start comience en 0;
		

		
		

		$producto= jQuery('.buscar_producto_consulta').typeahead("val");
		if ($producto=='') {
			jQuery("#color_consulta").html(''); 	
		}
		

		//cargarDependencia_producto($producto);
		



		var oTable =jQuery('#tabla_consulta_producto').dataTable();
		oTable._fnAjaxUpdate();

	});	







	function cargarDependencia_producto(valor) {

		
		var url = 'cargar_dependencia_producto';
		var dependencia = 'color';	

		jQuery.ajax({
		        url : 'cargar_dependencia_producto',
		        data:{
		        	valor:valor,
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
		        	 
	                 jQuery("#color_consulta").append('<option value="0" >Seleccione un color</option>');

                    
					if (data != "[]") {
						//alert(data);
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#color_consulta").append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	
						
					
					//jQuery("#"+dependencia).trigger('change');
	                //
	               // jQuery('#color').change();
                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}	



    jQuery("#color_consulta").on('change', function(e) {
		comienzo=true;  //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_consulta_producto').dataTable();
		oTable._fnAjaxUpdate();


     });




///////////////////////////////



	jQuery('#tabla_consulta_producto').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_consulta_producto",
	         		"type": "POST",

	         		 "data": function ( d ) {
	         		 	   if (comienzo) {
	         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
	         		 	   	 d.draw =0;
	         		 	   }

	         		 	 	d.producto = jQuery("#editar_producto_consulta").val(); 
	         		 	 	d.id_color = jQuery("#color_consulta").val(); 
	    			 }


	         		
	     },   





		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [

			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3]
		            },


		            
		        ],
	});	





	jQuery('#tabla_consulta_proveedor').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_consulta_proveedor",
	         		"type": "POST",

	         		 "data": function ( d ) {
	         		 	   if (comienzo) {
	         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
	         		 	   	 d.draw =0;
	         		 	   }

	         		 	     //alert(jQuery("#editar_proveedor_consulta").val());
						    d.proveedor = jQuery("#editar_proveedor_consulta").val(); 	   
	    			 }


	         		
	     },   





		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [
			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,1,2,3,4]
		            },


		            
		        ],
	});	










//Agregar las estradas a salidas
jQuery('body').on('click','#impresion_reporte', function (e) {
	

	//codigo = jQuery.base64.encode('1420150716lTvr62600130072015_1'); //jQuery("#editar_prod_inven").val(); 
	
	  //$('input[type=search]').on('search', function () {	

	  	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss").val(); 
	   id_almacen = jQuery("#id_almacen_reporte").val(); 

	   id_factura = jQuery("#id_factura_reporte").val(); 


	     				   //datos del producto
	   //id_descripcion = jQuery("#producto").val(); 
	   //id_descripcion = jQuery('#producto option:selected').text();

	   id_descripcion = jQuery("#producto").val(); 
	   if (id_descripcion !='') {
	   	  id_descripcion = jQuery('#producto option:selected').text();
	   }
	   //alert(id_descripcion);

	   id_color = jQuery("#color").val(); 
	   id_composicion = jQuery("#composicion").val(); 
	   id_calidad = jQuery("#calidad").val(); 
		
		factura_reporte = jQuery('#factura_reporte').val();					

		proveedor = jQuery("#editar_proveedor_reporte").val(); 	   

		var fecha = (jQuery('.fecha_reporte').val()).split(' / ');

		fecha_inicial = fecha[0];
		fecha_final = fecha[1];


    abrir('POST', 'imprimir_reportes', {
    			busqueda:busqueda,
    			id_factura:id_factura,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
    }, '_blank' );
		        
	
});







////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////filtros de catalogos/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////



	//,  #proveedor_catalogo

    jQuery("#producto_catalogo, #color_catalogo, #composicion_catalogo, #calidad_catalogo").on('change', function(e) {

		var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_catalogo option:selected').text();  		  //elemento** id
 		 var val_color = jQuery('#color_catalogo').val();  		  //elemento** id
 		 var val_comp = jQuery('#composicion_catalogo').val();  		  //elemento** id
 		 var val_calida = jQuery('#calidad_catalogo').val();  		  //elemento** id


         var dependencia = jQuery(this).attr("dependencia"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
    	if (dependencia !="") {	    
	        //limpiar la dependencia
	        jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	        cargarDependencia_catalogo(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
        }


		var hash_url = window.location.pathname;


		if  ( (hash_url=="/productos") )   {  

				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_cat_productos').dataTable();
				oTable._fnAjaxUpdate();
    	}	



     });




	function cargarDependencia_catalogo(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre) {
		
		var url = 'cargar_dependencia_catalogo';	

		jQuery.ajax({
		        url : 'cargar_dependencia_catalogo',
		        data:{
		        	campo:campo,
		        	
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,

		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		


	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
             	     

					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	

					
				
					jQuery("#"+dependencia).trigger('change');

                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}





///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////



var comenzar = false;
//jQuery('input[type="checkbox"][class="check_activo"]').click(function(e) {
	jQuery('body').on('click','input.check_activo[type="checkbox"]', function (e) {   		

	    var $this = $(this);
	    // $this will contain a reference to the checkbox   
	    var identificador = jQuery(this).attr('identificador');


	    var activo = ( ($this.is(':checked')) ? 1 : 0 );

		jQuery.ajax({
				        url : 'marcando_activo',
				        data : { 
				        	identificador: identificador,
				        	activo: activo,
				        },
				        type : 'POST',
				        dataType : 'json',
				        success : function(data) {	
				        	
				        	//comienzo = true;
				        	comenzar = true; //para indicar que start comience en 0;
		        			var oTable =jQuery('#tabla_cat_productos').dataTable();
							oTable._fnAjaxUpdate();



				        }
			});	


	});


	jQuery('#tabla_cat_productos').dataTable( {
	
	  "pagingType": "full_numbers",
		
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_cat_producto",
	         		"type": "POST",
 					"data": function ( d ) {
         		 	   if (comienzo) {
         		 	   	 d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   	
         		 	   }
         		 	   comienzo = false;
         		 	   
         		 	   d.comenzar = comenzar;
         		 	   comenzar = false;
         		 	   

    				   //datos del producto
     				   d.id_descripcion = jQuery("#producto_catalogo").val(); 
     				   if (d.id_descripcion !='') {
     				   	  d.id_descripcion = jQuery('#producto_catalogo option:selected').text();
     				   }

     				   d.id_color = jQuery("#color_catalogo").val(); 
     				   d.id_composicion = jQuery("#composicion_catalogo").val(); 
     				   d.id_calidad = jQuery("#calidad_catalogo").val(); 
    				   
    			 	}	         		
	         		
	     },   

     	  //"order": [[ 0, "asc" ]],

		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [

			    	{  //nombre de tela
		                "render": function ( data, type, row ) {
		                		if (row[12]!='') {
		                			return row[0]+'<br/><b style="color:red;">Cód: </b>'+row[12];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //nombre de tela y codigo
		            },

			    	{
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [1,2]    //referencia, minimo, 
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[13]; //minimo_kg
		                },
		                "targets": [3]  
		            },

			    	{ //imagen 
		                "render": function ( data, type, row ) {
		                		return row[3];
		                },
		                "targets": [4]   //el 3 es la imagen q ya viene formada desde el modelo
		            },		            
					{ //imagen, color 
		                "render": function ( data, type, row ) {
		                		return row[4];
		                },
		                "targets": [5]   //el 3 es la imagen q ya viene formada desde el modelo
		            },		            


		            { //ultimo consecutivo
		                "render": function ( data, type, row ) {

							return row[7];	
		                },
		                "targets": 6
		            },

		            {  //composicion
		                "render": function ( data, type, row ) {

							return row[8];	
		                },
		                "targets": 7
		            },

		            { //calidad
		                "render": function ( data, type, row ) {

							return row[9];	
		                },
		                "targets": 8
		            },

		            {  //precio
		                "render": function ( data, type, row ) {

							return row[10];	
		                },
		                "targets": 9
		            },		
		            {  //desactivar 
		                "render": function ( data, type, row ) {

							var checado = ((row[11] == 1) ? "checked" : ""); 
							texto='<td>';
								texto+='<input type="checkbox" '+checado+' class="check_activo" identificador='+row[5]+' style="margin: 33px 33px 0px;" name="colores" value="1">'; 
							texto+='</td>';							


							return texto;	
		                },
		                "targets": 10
		            },		                        		            

		            {  //editar
		                "render": function ( data, type, row ) {

		               if (row[6]==0) { 	
							texto='<td>';
								texto+='<a href="editar_producto/'+jQuery.base64.encode(row[5])+'" type="button"'; 
								texto+=' class="btn btn-warning btn-sm btn-block" >';
									texto+=' <span class="glyphicon glyphicon-edit"></span>';
								texto+=' </a>';
							texto+='</td>';
						} else {
							texto='<fieldset disabled> <td>';
								texto+='<a href="#" type="button"'; 
								texto+=' class="btn btn-warning btn-sm btn-block" >';
									texto+=' <span class="glyphicon glyphicon-edit"></span>';
								texto+=' </a>';
							texto+='</td> </fieldset>';							
						}

						
			
							return texto;	
		                },
		                "targets": 11
		            },

		            
		            {   //eliminar
		                "render": function ( data, type, row ) {

		                	if (row[6]==0) {
	   							texto='	<td>';								
									texto+=' <a href="eliminar_producto/'+(row[5])+'/'+jQuery.base64.encode(row[0])+ '"'; 
									texto+=' class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td>';	
		                	} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	


		                		
		                	}
									



							return texto;	
		                },
		                "targets": 12
		            },


		            {  //cambio precio/codigo
		                "render": function ( data, type, row ) {
						texto='	<td>';
							texto+=' <a href="cambiar_producto/'+jQuery.base64.encode(row[5])+'" type="button" ';
							texto+='	class="btn btn-warning btn-sm btn-block" >';
							texto+='	<span class="glyphicon glyphicon-edit"></span>';
							texto+='</a>';
						texto+='</td>';	 
							return texto;	
		                },
		                "targets": 13
		            },


		            {  //detalle
		                "render": function ( data, type, row ) {
						texto='	<td>';
							texto+=' <a href="detalle_producto/'+jQuery.base64.encode(row[5])+'" type="button" ';
							texto+='	class="btn btn-warning btn-sm btn-block" >';
							texto+='	<span class="glyphicon glyphicon-eye-open"></span>';
							texto+='</a>';
						texto+='</td>';	 
							return texto;	
		                },
		                "targets": 14
		            },



		            /*
		            { 
		                 "visible": false,
		                "targets": [5]
		            }*/	

		            
		        ],

	 			"rowCallback": function( row, data ) {
					    if  (data[11] == 1) 					    {
					      jQuery('td', row).addClass( "danger" );
					    }
					  },	
	});	






/////////////////////////////////////new////////////////////////////
    
    jQuery("#id_almacen").on('change', function(e) {
		var hash_url = window.location.pathname;
		if  ( (hash_url=="/salidas") )   {  
				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_entrada').dataTable();
				oTable._fnAjaxUpdate();
    	}	
     });

//productos filtrados en salidas
    jQuery("#producto_filtro, #color_filtro").on('change', function(e) {
		var campo = jQuery(this).attr("name");   
		
		var oTable =jQuery('#tabla_entrada').dataTable();
		oTable._fnAjaxUpdate();


     });


    jQuery("#ancho_filtro, #factura_filtro").on('keyup', function(e) {
		//var campo = jQuery(this).attr("name");   
		var oTable =jQuery('#tabla_entrada').dataTable();
		oTable._fnAjaxUpdate();
     });



 //desabilitando el boton de productos para el caso de la devolucion y editar inventarios
jQuery('#editar_prod_devolucion, #editar_prod_inven').on('keyup keypress', function(e) {
  var code = e.keyCode || e.which;
  if (code == 13) { 
    e.preventDefault();
    return false;
  }
});


//actualizar el consecutivo de la entrada. Cuando cambie la id_factura


jQuery('body').on('change','#id_factura[tipo="entrada"], #id_almacen[tipo="entrada"]', function (e) {	
  consecutivo_actual = ( (jQuery(this).val()==1) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
  jQuery("#movimiento").val(consecutivo_actual);

		jQuery.ajax({
		        url : 'consecutivo_entrada',
		        data : { 
		        	id_operacion: jQuery("#id_operacion").val(), 
		        	  id_almacen: jQuery("#id_almacen").val(),
		        	  id_factura: jQuery("#id_factura").val(),
		        	   id_pedido: 0, // no pedido
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						console.log(data);
						jQuery("#c1").val( parseInt(data.c1)+1);
						jQuery("#c2").val( parseInt(data.c2)+1);
						jQuery("#c1234").val( parseInt(data.c1234)+1);
						jQuery("#c234").val( parseInt(data.c234)+1);
						jQuery("#c34").val( parseInt(data.c34)+1);
						//{c1: "0", c2: "0", c1234: "0", c234: "0", c34: "0"}
				}
		});	


});





jQuery('body').on('click','#conf_entrada_compra', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

		jQuery.ajax({
		        url : 'validar_proceso_compra',
		        data : { 
		        		  dato: "valor",
		        	id_factura: jQuery("#id_factura").val(),
		        	id_estatus: jQuery("#id_estatus").val(),
		        	id_almacen: jQuery("#id_almacen").val(),
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
					if(data.exito != true){
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data.error);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					}else{
						spinner.stop();
						//borrar el mensaje q quedo	
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'conf_entrada');
						
												$catalogo = e.target.name;
												window.location.href = 'procesar_entrar_compra/'+jQuery.base64.encode("C-"+data.num_mov)+'/'+jQuery.base64.encode(jQuery("#id_factura").val())+'/'+jQuery.base64.encode(jQuery("#id_estatus").val());
									        	
									        }
								});			

						
					}		        			        	  
				}
		});	

});




jQuery('body').on('click','#conf_entrada', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

		jQuery.ajax({
		        url : 'validar_proceso',
		        data : { 
		        		  dato: "valor",
		        	id_factura: jQuery("#id_factura").val(),
		        	id_estatus: jQuery("#id_estatus").val(),
		        	id_almacen: jQuery("#id_almacen").val(),
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
					if(data.exito != true){
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data.error);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					}else{
						spinner.stop();
						//borrar el mensaje q quedo	
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'conf_entrada');
						
												$catalogo = e.target.name;
												window.location.href = 'procesar_entrar/'+jQuery.base64.encode("E-"+data.num_mov)+'/'+jQuery.base64.encode(jQuery("#id_factura").val())+'/'+jQuery.base64.encode(jQuery("#id_estatus").val());
									        	
									        }
								});			

						
					}		        			        	  
				}
		});	

});



jQuery('body').on('click','#conf_devolucion', function (e) {

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

		jQuery.ajax({
		        url : 'validar_conf_devolucion',
		        data : { 
		        	dato: "valor"
		        },
		        type : 'POST',
		        //dataType : 'json',
		        success : function(data) {	
					if(data != true){
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					}else{
						spinner.stop();
						//borrar el mensaje q quedo	
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'conf_devolucion');
						
												$catalogo = e.target.name;
												window.location.href = 'procesar_devoluciones/'+jQuery.base64.encode("-1");
									        	
									        }
								});			


						
					}		        			        	  
				}
		});	

});

////////////////////////////Para los botones de agregar catalogo Modales///////////////////////////////


  jQuery("#id_lote").on('change', function(e) {
		
 		 //var val_prod = jQuery('#producto').val();
 		 var val_prod = jQuery('#producto option:selected').text();  		  //elemento** id

 		 var val_color = jQuery('#color').val();  
 		 var val_comp = jQuery('#composicion').val();  		  
 		 var val_calida = jQuery('#calidad').val();  		  


    		if  ((val_calida != "0") && (val_calida != "") && (val_calida != null)) 
    		{
    		
				//entradas

				var id_cliente = jQuery('.buscar_proveedor').typeahead('val');
				var url = 'id_proveedor';	
				jQuery.ajax({  //para tomar la referencia del producto
				        url : '/refe_producto',
					    data:{
					        	id_cliente : id_cliente,
					        	val_prod:val_prod,
					        	val_color:val_color,
					        	val_comp:val_comp,
					        	val_calida:val_calida,
					        },
					        type : 'POST',
					        dataType : 'json',
				        success : function(dato) {
				        	
							codigo_proveedor =dato.cliente_id;
						        		lote =jQuery('#id_lote option:selected').text();
						        
						          referencia =dato.ref_prod.referencia; 
						          comentario =dato.ref_prod.comentario; 
						              precio =dato.ref_prod.precio; 
						              ancho =dato.ref_prod.ancho; 


							referencia2 = (referencia.substring(8, referencia.length));						              
				        	codigo=codigo_proveedor+referencia2+lote+fecha_formateada;
				        	//alert(referencia);

				        		//codigo
				        	jQuery('#codigo').val(codigo);
								
								//referencia
							jQuery('#referencia').attr('value',referencia);
								//ancho
							jQuery('#ancho').attr('value',ancho);
								//precio
							jQuery('#precio').attr('value',precio);
								//comentario
							//jQuery('#comentario').text(comentario); //attr('text',comentario);


						},
				        error : function(jqXHR, status, error) {
				        },
				        complete : function(jqXHR, status) {
				            
				        }											        	
				});					

			


			}	


 

     });


var comienzo =false;
jQuery.fn.dataTable.Api.register( 'column().data().sum()', function () {
	return this.reduce( function (a, b) {
		var x = parseFloat( a ) || 0;
		var y = parseFloat( b ) || 0;
		return x + y;
	} );
} );




if ( jQuery('#config_entrada_activo').val() == 1 ) { //si tiene factura
//home		
		var existencia = ['Código', 'Producto', 'Color',  'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso','Factura', 'No. de Partida','Almacén'];
		var devolucion = ['Código', 'Producto', 'Color', 'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso','Factura', 'No. de Partida','Almacén'];
		var apartado = ['Código', 'Producto', 'Color', 'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento', 'Dependencia', 'Tipo Apartado', 'Fecha','Factura', 'No. de Partida','Almacén'];
    	var cero = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio','Factura'];
    	var baja = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio','Factura'];
		
//informe

		var entrada = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso','Factura', 'No. de Partida','Almacén'];
		var salida = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Cliente', 'Lote', 'Egreso','Factura', 'No. de Partida','Almacén'];
		var existencia_informe = ['Código', 'Producto', 'Color',   'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso','Factura', 'No. de Partida','Almacén'];
		var devolucion_informe = ['Código', 'Producto', 'Color',  'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso','Factura', 'No. de Partida','Almacén'];
		var apartado_informe = ['Código', 'Producto', 'Color',  'Cantidad',  'Ancho', 'No. Movimiento', 'Dependencia', 'Tipo Apartado', 'Fecha','Factura', 'No. de Partida','Almacén'];
    	var cero_informe = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio','Factura'];
    	var baja_informe = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio','Factura'];
    	var top = ['Referencia', 'Producto', 'Metros Vendidos', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio','Factura'];

} else { //sino tiene factura

	//home	

			var existencia = ['Código', 'Producto', 'Color',  'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso', 'No. de Partida','Almacén'];
			var devolucion = ['Código', 'Producto', 'Color', 'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso', 'No. de Partida','Almacén'];
			var apartado = ['Código', 'Producto', 'Color', 'Imagen', 'Cantidad',  'Ancho', 'No. Movimiento', 'Dependencia', 'Tipo Apartado', 'Fecha', 'No. de Partida','Almacén'];
	    	var cero = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio'];
			var baja = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio'];	    	
			
	//informe

			var entrada = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso', 'No. de Partida','Almacén'];
			var salida = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Cliente', 'Lote', 'Egreso', 'No. de Partida','Almacén'];
			var existencia_informe = ['Código', 'Producto', 'Color',   'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso', 'No. de Partida','Almacén'];
			var devolucion_informe = ['Código', 'Producto', 'Color',  'Cantidad',  'Ancho', 'No. Movimiento','Proveedor', 'Lote', 'Ingreso', 'No. de Partida','Almacén'];
			var apartado_informe = ['Código', 'Producto', 'Color',  'Cantidad',  'Ancho', 'No. Movimiento', 'Dependencia', 'Tipo Apartado', 'Fecha', 'No. de Partida','Almacén'];

	    	var cero_informe = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio'];
	    	var baja_informe = ['Referencia', 'Producto', 'Existencias', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio'];
	    	var top = ['Referencia', 'Producto', 'Metros Vendidos', 'Imagen', 'Color', 'Especificaciones', 'Composición', 'Calidad', 'Precio'];

}


if ( jQuery('#config_salida_activo').val() == 1 ) { //si tiene factura salida

		var salida = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Cliente', 'Lote', 'Egreso','Factura', 'No. de Partida','Almacén'];

} else {
			var salida = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Cliente', 'Lote', 'Egreso', 'No. de Partida','Almacén'];

}	



//var arreglo =existencia
		
    	
    	//hasta aqui reporte

    	if ( jQuery('#config_salida').val() == 1 ) { //si tiene factura

	    	var arr_apartado_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Precio', 'IVA','Lote','No. de Partida','Almacén','Tipo factura','Precio'];
	    	var arr_pedido_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Precio', 'IVA','Lote','No. de Partida','Almacén','Tipo factura','Precio'];
	    	var arr_completo_detalle = ['Código', 'Producto', 'Color', 'Cantidad', 'Ancho', 'Subtotal', 'IVA', 'Lote','No. de Partida','Almacén','Tipo factura'];
			
			var apartado_pendiente = ['Vendedor', 'Núm. Pedido','Cliente',  'Fecha','Tipo Apartado','Vencimiento','Tipo pedido','Tipo factura','Detalles','Cancelar','Almacén','Importe' ];  //'Prorrogar',
			var pedido_pendiente = ['Vendedor', 'Núm. Pedido','Cliente',  'Fecha','Tipo Apartado','Vencimiento','Tipo pedido','Tipo factura','Detalles','Cancelar','Almacén','Importe' ];  //'Prorrogar',
			var pedido_completo = ['Pedido realizado por:', 'Núm. Pedido','Cliente', 'Fecha','Tipo Apartado','Núm. Salida','Tipo pedido','Tipo factura','Detalles','Almacén','Precio'];

			var productos_temporales = ['Código', 'Descripción','Color', 'Medida','Ancho','Peso Real','Proveedor','Lote - No. consecutivo', 'No. de Partida','Precio','Subtotal','IVA','Total', 'Quitar']; 

	        var producto_color	= ['Código', 'Lote','Cantidad', 'Ancho','Entrada','Apartar','Almacén'];
	        var producto_color1	= ['Código', 'Lote','Cantidad', 'Ancho','Entrada','Almacén'];
	    } else {
	    	
	    	
	    	if ( ( jQuery('#el_perfil').val() == 3 ) || ( jQuery('#el_perfil').val() == 4 ) ) {
	    		//var arr_pedido_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Lote','No. de Partida','Almacén','Precio'];
	    		var arr_pedido_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Lote','No. de Partida','Almacén','Peso','Precio'];
	    		var arr_apartado_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Lote','No. de Partida','Almacén','Precio'];
	    	} else {
	    		var arr_pedido_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Lote','No. de Partida','Almacén','Peso','Precio'];
	    		var arr_apartado_detalle = ['Código', 'Producto', 'Color', 'Cantidad',   'No. Movimiento','Ancho', 'Lote','No. de Partida','Almacén','Peso','Precio'];
	    	}
	    		
	    	
	    	var arr_completo_detalle = ['Código', 'Producto', 'Color', 'Cantidad', 'Ancho', 'SubTotal', 'IVA', 'Lote','No. de Partida','Almacén','Tipo factura'];

			
			var apartado_pendiente = ['Vendedor', 'Núm. Pedido','Cliente',  'Fecha','Tipo Apartado','Tipo factura','Detalles','Cancelar','Almacén','Importe' ];  //'Prorrogar',
			var pedido_pendiente = ['Vendedor', 'Núm. Pedido','Cliente',  'Fecha','Tipo pedido','Tipo factura','Detalles','Cancelar','Almacén','Importe' ];  //'Prorrogar',
			var pedido_completo = ['Pedido realizado por:', 'Núm. Pedido','Cliente', 'Fecha','Tipo Apartado','Núm. Salida','Tipo pedido','Tipo factura','Detalles','Almacén','Precio'];

			var productos_temporales = ['Código', 'Descripción','Color', 'Medida','Ancho','Peso Real','Proveedor','Lote - No. consecutivo', 'No. de Partida','Precio','Subtotal','IVA','Total', 'Quitar']; 

	        var producto_color	= ['Código', 'Lote','Cantidad', 'Ancho','Entrada','Apartar','Almacén'];
	        var producto_color1	= ['Código', 'Lote','Cantidad', 'Ancho','Entrada','Almacén'];
	   

	    }    
    	

///////////////////////////////////////////////////////////////////////////////
///////////////////////////DEVOLUCION////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////




	/////////////////////////buscar producto_devolucion (buscar_prod_inven)


	var consulta_prod_devolucion = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_prod_devolucion').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_prod_devolucion.tt-input').attr("name"));
				    q += '&idprodinven='+encodeURIComponent(jQuery('.buscar_prod_devolucion.tt-input').attr("idprodinven"));
					q += '&id_almacen='+encodeURIComponent(jQuery('#id_almacen option:selected').val());
	            return  q;
	        }
	    },   

	});


    //consulta_prod_devolucion.clear();
	consulta_prod_devolucion.initialize();

	jQuery('.buscar_prod_devolucion').typeahead(
		{
			   hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_prod_devolucion',
	  displayKey: 'descripcion', //
	  source: consulta_prod_devolucion.ttAdapter(),
	   templates: {
				
			    suggestion: function (data) {  
			    	//alert('una');   			
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_prod_devolucion').on('typeahead:selected', function (e, datum,otro) {

	    jQuery('#producto').val(datum.id_descripcion);
	    jQuery('#codigo_original').val(datum.key)

	    

	    jQuery('#oculto_producto').attr('color',datum.id_color );
	    jQuery('#oculto_producto').attr('composicion',datum.id_composicion );
	    jQuery('#oculto_producto').attr('calidad',datum.id_calidad );

	    //provocar el evento
	    jQuery('#oculto_producto').val('si');
	    jQuery('#producto').change();

	    jQuery("#codigo_contable").text(datum.codigo_contable);

	   	jQuery('#movimiento').val(datum.id_movimiento);
	   	jQuery('#movimiento_unico').val(datum.movimiento_unico);
	   	jQuery('#proveedor').val(datum.proveedor);
	   	jQuery('#fecha').val(datum.fecha_entrada);
	   	jQuery('#factura').val(datum.factura);

	   	jQuery('#peso_real').val(datum.peso_real_devolucion); //aqui es el peso_real_devolucion temporal
	   	
	   	jQuery('#cantidad_um').val(datum.cantidad_um);
	   	jQuery('#id_medida').val(datum.id_medida);
	   	jQuery('#ancho').val(datum.ancho);
	   	jQuery('#precio').val(datum.precio);
	   	jQuery('#num_partida').val(datum.num_partida);

	   	jQuery('#id_estatus').val(datum.id_estatus);
	   	jQuery('#id_lote').val(datum.id_lote);

	   	//jQuery('#tabla_cambio').dataTable().fnDraw();
	    

	});	

	jQuery('.buscar_prod_devolucion').on('typeahead:closed', function (e) {
		//jQuery('#tabla_entrada').dataTable().fnDraw();
	});	

///tabla_devoluciones

jQuery('#tabla_devolucion').dataTable( {
	
		"pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_servidor_devolucion",
	         		"type": "POST",
	         		 "data": function ( d ) {
	     				  // d.codigo = jQuery("#codigo_original").val(); 
	    			 }
	     },   
 
		"infoCallback": function( settings, start, end, max, total, pre ) {

			if (settings.json.data) {
				jQuery("#cod_devolucion").val(settings.json.data[0][8]);

				jQuery("fieldset.disableddev").attr('disabled', true);					

					jQuery("select#id_almacen > option[value="+settings.json.data[0][14]+"]").prop("selected",true);
					jQuery("fieldset.disabled_almacen").attr('disabled', true);					


			} else {

				

				jQuery("#cod_devolucion").val('');
				jQuery("fieldset.disableddev").attr('disabled', false);					

				if ( jQuery("#mi_perfil").val() !='2') {
					jQuery("fieldset.disabled_almacen").attr('disabled', false);					
				}
				
					

			}
			
		    if (settings.json.totales) {
			    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			    jQuery('#total_peso').html( 'Total de peso real:'+number_format(settings.json.totales.peso, 2, '.', ','));
				jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
				jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

			} else {
			    jQuery('#total_pieza').html( 'Total de piezas: 0');
			    jQuery('#total_peso').html( 'Total de peso real: 0.00');
				jQuery('#total_kg').html( 'Total de kgs: 0.00');
				jQuery('#total_metro').html('Total de mts: 0.00');

			}	



		    return pre;
	  	} ,   	     



		"footerCallback": function( tfoot, data, start, end, display ) {
		   var api = this.api(), data;
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};

			if  (data.length>0) {   
					
					total_metro = api
						.column( 10 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );
					total_kilogramo = api
						.column( 11)
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );
					
					total_peso_real = api
					.column( 13)  //se suma el peso_real_devolucion,
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

					total_pieza = (end-start);	

				        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
				        jQuery('#peso').html( 'Total de peso real:'+number_format(total_peso_real, 2, '.', ','));
				        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
				        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

			} else 	{
				        jQuery('#pieza').html('Total de piezas: 0');
				        jQuery('#peso').html('Total de peso real: 0.00');
				        jQuery('#metro').html('Total de mts: 0.00');
						jQuery('#kg').html('Total de kgs: 0.00');	

			}	
	    },	



		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {
		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:green;">Cód: </b>'+row[15];	 //aqui es verde porq siempre el id_estatus es devolucion
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        }, 
			    	{ 
		                "render": function ( data, type, row ) {
		                		return 'A'+data;
		                },
		                "targets": [0]
		            },
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [2,3,4,5] //6,7
		            },

	    			{ 
		                "render": function ( data, type, row ) {
							return row[13];	
		                },
		                "targets": [6]
		            },	
	    			{ 
		                "render": function ( data, type, row ) {
							return row[6];	
		                },
		                "targets": [7]
		            },

			    	{ 
		                "render": function ( data, type, row ) {
		                		return row[7];
		                },
		                "targets": [8]
		            },

		            {
		                "render": function ( data, type, row ) {
    					 texto='<td><a href="quitar_devolucion/'+jQuery.base64.encode(row[0])+'/'+jQuery.base64.encode(row[1])+ '"';  //+jQuery.base64.encode(row[6])+'" '; 
						 	texto+=' class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalMessage">';
						 	texto+=' Quitar';
						 texto+='</a></td>';
							return texto;	
		                },
		                "targets": 9
		            },
		            { 
		                 "visible": false,
		                "targets": [10,11,12,13,14,15,16]
		            }
		        ],
		});	



	//Agregar producto_devolucion  a la regilla
	jQuery("#form_editar_devolucion").submit(function(e){

		jQuery(this).attr('disabled', true);				        
		


		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				}else{
					spinner.stop();
					//borrar el mensaje q quedo	
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','none');


					//desabilito proveedor y factura
					//jQuery("fieldset.disabledme").attr('disabled', true);
					//vuelve a los valores por defecto, producto, color, composicion y calidad

					//
					jQuery("#fecha").val('');
					jQuery("#movimiento").val('');
					jQuery("#movimiento_unico").val('');
					

					jQuery("#proveedor").val('');
					jQuery("#factura").val('');
					jQuery("#codigo_contable").text('');
					//jQuery("#cod_devolucion").val('');

					jQuery("fieldset.disableddev").attr('disabled', true);					

					jQuery("#editar_prod_devolucion").val('');
					jQuery("#codigo").val('');
					
					jQuery("#peso_real").val('');  //Limpiar el precio_real

					jQuery("#cantidad_um").val('');
					jQuery("#cantidad_royo").val('');
					jQuery("#ancho").val('');
					jQuery("#precio").val('');

					jQuery("#num_partida").val('');

					jQuery("#comentario").val('');


					jQuery('#calidad option:eq(0)').prop('selected', 'selected');
					jQuery('#composicion option:eq(0)').prop('selected', 'selected');
					jQuery('#color option:eq(0)').prop('selected', 'selected');
					jQuery('#producto option:eq(0)').prop('selected', 'selected');

					jQuery('#producto').trigger( "change" );
					

					//um y estatus sus valores por defectos

					jQuery('#id_medida option:eq(0)').prop('selected', 'selected');
					jQuery('#id_estatus option:eq(0)').prop('selected', 'selected');
					jQuery('#id_lote option:eq(0)').prop('selected', 'selected');
					
					jQuery('#tabla_devolucion').dataTable().fnDraw();
					//tuve q usar este porque no se puede reinicializar el selector
					$catalogo = e.target.name;
					window.location.href = '/'+$catalogo;	
			

				}
			} 
		});
		
		jQuery(this).attr('disabled', false);
		return false;
	});	


	//Quitar el producto devolucion de la regilla
    jQuery('body').on('submit','#form_devolucion', function (e) {
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					    $catalogo = e.target.name;
						spinner.stop();
						jQuery('#foo').css('display','none');


						
						jQuery('#tabla_devolucion').dataTable().fnDraw();
						window.location.href = '/'+$catalogo;	

						//return false;						

						
				}
			} 
		});
		return false;
	});	


///////////////////////////////////////////////////////////////////////////////
///////////////////////////FIN DE DEVOLUCION////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////








//fecha
			  

 
/////////////////////////////////////////////////


	//Agregar entrada temporal 
	jQuery("#form_editar_inventario").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				}else{
					spinner.stop();

					//borrar el mensaje q quedo	
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','none');

					 //limpiar campo comentario
					jQuery("#comentario").val('');

					jQuery('#tabla_cambio').dataTable().fnDraw();

				}
			} 
		});
		return false;
	});	


////cdn.datatables.net/tabletools/2.2.4/js/dataTables.tableTools.min.js
//https://editor.datatables.net/examples/advanced/exportButtons.html




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
/////////////////////////////////////////////////////reportes/////////////////////////////////////////////////////////



jQuery('#existencia_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');		
	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','block');
	jQuery('.bloque_totales').css('display','block');
	jQuery('#label_reporte').text("Reportes de Existencias");
	jQuery('#estatus_id').css('display','block');
	jQuery('#proveedor_id').css('display','block');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','none');

	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/

	jQuery('#label_proveedor').text("Proveedor");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyen_home').css('display','block');
	jQuery('.leyenda_devolucion').css('display','none');



	comienzo=true;  //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('existencia');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

   
    jQuery("#producto_rep").trigger('change');




	oTable._fnAjaxUpdate();
});

jQuery('#salida_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	

	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');
	
	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}
	
	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#id_factura_reporte').append('<option value="3">S</option>');
    jQuery('#factura_reporte').val('');
    if (jQuery('#config_salida_activo').val()==1 ) {
		jQuery('#bloque_factura').css('display','block');
	} else {
		jQuery('#bloque_factura').css('display','none');
	}

   	jQuery('.bloque_totales').css('display','block');
	jQuery('#label_reporte').text("Reportes de Salidas");
	jQuery('#estatus_id').css('display','block');
	jQuery('#proveedor_id').css('display','block');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','none');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/

	jQuery('#label_proveedor').text("Cliente");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','3');
	jQuery('.leyen_home').css('display','none');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyenda_devolucion').css('display','block');


	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('salida');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');

	oTable._fnAjaxUpdate();
});


jQuery('#apartado_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	

	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','none');
	jQuery('.bloque_totales').css('display','block');
	jQuery('#label_reporte').text("Reportes de Apartados");
	jQuery('#estatus_id').css('display','block');
	jQuery('#proveedor_id').css('display','block');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','none');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/
	jQuery('.leyen_home').css('display','none');
	jQuery('.leyenda').css('display','block');
	jQuery('.leyenda_devolucion').css('display','none');
	jQuery('#label_proveedor').text("Cliente");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');

	comienzo=true;  //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('apartado');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');


	oTable._fnAjaxUpdate();
});

jQuery('#cero_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	

	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','none');
	jQuery('.bloque_totales').css('display','none');
	jQuery('#label_reporte').text("Reportes de Existencias Cero");
	jQuery('#estatus_id').css('display','none');
	jQuery('#proveedor_id').css('display','none');
	jQuery('#fecha_id').css('display','none');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','block');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/
	jQuery('.leyen_home').css('display','none');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyenda_devolucion').css('display','none');
	jQuery('#label_proveedor').text("Proveedor");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');

	comienzo=true;  //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('cero');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');


	oTable._fnAjaxUpdate();
});

jQuery('#baja_reporte').click(function (e) {
	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','none');
	jQuery('.bloque_totales').css('display','none');
	jQuery('#label_reporte').text("Reportes de Existencias Bajas");
	jQuery('#estatus_id').css('display','none');
	jQuery('#proveedor_id').css('display','block'); //**
	jQuery('#fecha_id').css('display','none');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','block');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/
	jQuery('.leyen_home').css('display','none');
	jQuery('.leyenda').css('display','none');	
	jQuery('.leyenda_devolucion').css('display','none');
	jQuery('#proveedor_id').parent().css('display','block'); //***
	jQuery('#proveedor_id').siblings().css('display','none'); //***
	jQuery('#label_proveedor').text("Proveedor");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');

	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('baja');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');

	oTable._fnAjaxUpdate();
});


jQuery('#top_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	

	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');
	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','none');
	jQuery('.bloque_totales').css('display','none');
	jQuery('#label_reporte').text("Reportes de Top 10");
	jQuery('#estatus_id').css('display','none');
	jQuery('#proveedor_id').css('display','none');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','none');
	jQuery('.leyen_home').css('display','none');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyenda_devolucion').css('display','none');
	 //
    jQuery('.bloq_id_medida').css('display','block');
    

	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('top');
	oTable._fnAjaxUpdate();
});




//nuevo reportes

jQuery('#entrada_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	
	

	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','block');
	jQuery('.bloque_totales').css('display','block');
	jQuery('#label_reporte').text("Reportes de Entradas");
	jQuery('#estatus_id').css('display','block');
	jQuery('#proveedor_id').css('display','block');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','none');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/
	jQuery('#label_proveedor').text("Proveedor");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyen_home').css('display','block');
	jQuery('.leyenda_devolucion').css('display','none');

	comienzo=true;  //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('entrada');
	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');


	oTable._fnAjaxUpdate();
});




jQuery('#devolucion_reporte').click(function (e) {
	jQuery('#proveedor_id').siblings().css('display','block');	
	jQuery('.fecha_reporte').val('');
	jQuery('#id_estatuss option:eq(0)').prop('selected', 'selected');

	if ( jQuery("#mi_perfil").val() !='2') {
		jQuery('#id_almacen_reporte option:eq(0)').prop('selected', 'selected');
	}

	jQuery('#id_factura_reporte').html(''); 
	jQuery('#id_factura_reporte').append('<option value="0">Todos</option>');
	jQuery('#id_factura_reporte').append('<option value="1">A</option>');
	jQuery('#id_factura_reporte').append('<option value="2">B</option>');
	jQuery('#factura_reporte').val('');
	jQuery('#bloque_factura').css('display','block');
	jQuery('.bloque_totales').css('display','block');
	jQuery('#label_reporte').text("Reportes de Devoluciones");
	jQuery('#estatus_id').css('display','none');
	jQuery('#proveedor_id').css('display','block');
	jQuery('#fecha_id').css('display','block');
	jQuery('#example2').css('display','block');
	 //
    jQuery('.bloq_id_medida').css('display','none');
    
	/*
	jQuery('#calidad option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion option:eq(0)').prop('selected', 'selected');
	jQuery('#color option:eq(0)').prop('selected', 'selected');
	jQuery('#producto option:eq(0)').prop('selected', 'selected');
	*/
	jQuery('#label_proveedor').text("Proveedor");
	jQuery('#editar_proveedor_reporte').typeahead("val",'');
	jQuery('#editar_proveedor_reporte').attr('idproveedor','1');
	jQuery('.leyenda').css('display','none');
	jQuery('.leyen_home').css('display','block');
	jQuery('.leyenda_devolucion').css('display','none');

	comienzo=true;  //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	jQuery('#botones').val('devolucion');

	jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');
	
	oTable._fnAjaxUpdate();
});





var rep_existencia = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Lote', 'Precio'];
  var rep_apartado = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Tipo Apartado', 'Precio'];
    var rep_salida = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Lote', 'Precio'];
   var rep_entrada = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Lote', 'Precio'];
var rep_devolucion = ['Código', 'Producto', 'Color', 'Cantidad',  'Ancho', 'No. Movimiento','Lote', 'Precio'];

      var rep_cero = ['Referencia', 'Producto', 'Existencias', 'Color', 'Composición', 'Calidad', 'Precio'];
	  var rep_baja = ['Referencia', 'Producto', 'Existencias', 'Color', 'Composición', 'Calidad', 'Precio'];
	   var rep_top = ['Referencia', 'Producto', 'Metros Vendidos', 'Color', 'Composición', 'Calidad', 'Precio'];

jQuery('#tabla_reporte').dataTable( {
		
	  "pagingType": "full_numbers",
 	  "order": [[ 6, "asc" ]],

      "fnPreDrawCallback": function (oSettings) {
		if (comienzo) {
			oSettings._iDisplayStart = 0;  //comienza en cero siempre q cambia de botones
			comienzo=false;
		}
      },
      /*
      "oLanguage": {  //spin de la tabla
				"sProcessing": "Un momento por favor...."
	   },*/

	
	 "processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_reporte",
         		"type": "POST",
         		 "data": function ( d ) {
						if (comienzo) {
							 d.start=0;	 //comienza en cero siempre q cambia de botones
							 d.draw =0;
						}

						d.extra_search = jQuery("#botones").val(); 
						d.id_estatus = jQuery("#id_estatuss").val(); 
						d.id_almacen = jQuery("#id_almacen_reporte").val(); 
						d.id_factura = jQuery("#id_factura_reporte").val(); 


						//datos del producto
						d.id_descripcion = jQuery("#producto_rep").val(); 
						if (d.id_descripcion !='') {
							  d.id_descripcion = jQuery('#producto_rep option:selected').text();
						}
						d.val_prod_id = jQuery('#producto_rep option:selected').val();

						d.id_medida = jQuery("#id_medida_reporte option:selected").val();

						d.id_color = jQuery("#color_rep").val(); 
						d.id_composicion = jQuery("#composicion_rep").val(); 
						d.id_calidad = jQuery("#calidad_rep").val(); 
						d.factura_reporte = jQuery('#factura_reporte').val();					
						d.proveedor = jQuery("#editar_proveedor_reporte").val(); 	   
						var fecha = (jQuery('.fecha_reporte').val()).split(' / ');
						d.fecha_inicial = fecha[0];
						d.fecha_final = fecha[1];
    			 }
     },   
	"initComplete": function(settings, json) {
		/////////
	},
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));
		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');
		}	
		if (settings.json.recordsTotal==0) {
			jQuery("#disa_reportes").attr('disabled', true);					
		} else {
			jQuery("#disa_reportes").attr('disabled', false);					
		}
	    return pre
  	} ,    

	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				total_metro = api
					.column( 7 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 8 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	
			switch(jQuery("#botones").val()) {
			    case "salida":
			    case "existencia":
			    case "apartado":
				case "devolucion":
				case "entrada":
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));
			        break;
			    default:
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');			        
	              break;
			}
		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');			        
		}
    },
   "columnDefs": [

				  { 
                "className":'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '',
                "targets": [0]  //0,
              },
				{ 
		                "render": function ( data, type, row ) {  

		                		var color;
		                		switch (row[12]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 


		                		if (row[11]!='') { //row[11] codigo contable
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[11];	
		                		} else {
		                			return row[1];
		                		}
		                },
		                "targets": [1]   //descripcion+codigo contable
		        },   	
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [2,3,4,5,6] //,7,8,12,13,14
	            },
    			{ 

	                "render": function ( data, type, row ) {
						return row[13];
	                },

	                "targets": [7] 
	            },

    			{ 
	                 "visible": false,
	                "targets": [8,9,10,11,12] //12= id_estatus,
	            }
	],
 "rowCallback": function( row, data ) {
	    if ( data[9] == "red" ) {
	      jQuery('td', row).addClass( "danger" );
	    }
	    if ( data[9] == "morado" ) {
	      jQuery('td', row).addClass( "success" );
	    }
	    if ( data[10] == 1 ) {
	      jQuery('td', row).addClass( "warning" );
	    }
	  },		

    "fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		switch(jQuery("#botones").val()) {
		    
		   case "existencia":
		        var arreglo =rep_existencia;
		        break;
		    case "apartado":
		        var arreglo =rep_apartado;
		        break;
		    case "salida":
		        var arreglo =rep_salida;
		        break;
		    case "entrada":
		          var arreglo =rep_entrada;
		        break;
		    case "devolucion":
		          var arreglo =rep_devolucion;
		        break;
		    case "cero":
		        var arreglo =rep_cero;
		        break;
		    case "baja":
		        var arreglo =rep_baja;
		        break;
		    case "top":
		        var arreglo =rep_top;
		        break;
		    default:
		}

	    var api = this.api();
	    //var api = this.api(), data;


	      
	      
    	 

	      	
	     api.column(7).visible(true);	
	     api.column(6).visible(true);	
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
    	}
    	

    	 if ((jQuery("#botones").val()=="cero") ||  (jQuery("#botones").val()=="baja") ||  (jQuery("#botones").val()=="top"))  {
	                	//api.column(7).visible(false);	
	            		if (aData.length >0){
		                	if (aData[0][6]=="-") {
		                		api.column(6).visible(false);
		                		api.column(7).visible(false);		
		                	} else {
		                		api.column(6).visible(true);	
		                		api.column(7).visible(false);	
		                	}
		                } else {
		                	api.column(6).visible(false);	
		                	api.column(7).visible(false);	
		                }

	      } else {
  				if (aData.length >0){
      				if (aData[0][13]=="-") {
                		api.column(7).visible(false);	
                	} else {
                	api.column(7).visible(true);	
                	}
                } else {
                	api.column(7).visible(false);	
                }
		                
	      } 

	                
	},
	"language": {  
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	






    jQuery('#tabla_reporte tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var td = $(this).closest('tr > td');
        var row = jQuery('#tabla_reporte').DataTable().row( tr );
        //console.log(row);
  
          //console.log($('#tabla_rep_general').DataTable().row( tr ).data());
          //console.log(tabla.row());

        
        if ( row.child.isShown() ) { //si la fila esta "abierta" entonces "cerrarla"
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
           			//si la fila esta "cerrada" entonces "abrirla"

						extra_search = jQuery("#botones").val(); 
						id_estatus = jQuery("#id_estatuss").val(); 
						id_almacen = jQuery("#id_almacen_reporte").val(); 
						id_factura = jQuery("#id_factura_reporte").val(); 

						//datos del producto
						id_descripcion = jQuery("#producto_rep").val(); 
						if (id_descripcion !='') {
							  id_descripcion = jQuery('#producto_rep option:selected').text();
						}
						id_medida = jQuery("#id_medida_reporte option:selected").val();
						id_color = jQuery("#color_rep").val(); 
						id_composicion = jQuery("#composicion_rep").val(); 
						id_calidad = jQuery("#calidad_rep").val(); 
						factura_reporte = jQuery('#factura_reporte').val();					
						proveedor = jQuery("#editar_proveedor_reporte").val(); 	   
						var fecha = (jQuery('.fecha_reporte').val()).split(' / ');
						fecha_inicial = fecha[0];
						fecha_final = fecha[1];
						var d= row.data();
						//console.log(d[0]);

                 $.ajax({
                        url: "/procesando_detalle_reporte",
                        type: 'POST',
                        dataType: "json",
                        data: {
                        	   identificador : d[0],
                        		   busqueda  : jQuery('input[type=search]').val(),
								extra_search : extra_search,
								  id_estatus : id_estatus,
								  id_almacen : id_almacen,
								  id_factura : id_factura,
								//datos del producto
							  id_descripcion : id_descripcion,
							  	   id_medida : id_medida,
								    id_color : id_color,
							   id_composicion:id_composicion,
								   id_calidad:id_calidad,
							  factura_reporte:factura_reporte,
							  	    proveedor:proveedor,
								        fecha:fecha,
								fecha_inicial:fecha_inicial,
								  fecha_final:fecha_final
                         },
                        success: function(datos){

                        		if (datos.data) {

                        			
                        				var etq_existencia = ['Código', 'Proveedor',   'Ingreso', 'No. de Partida','Almacén','Imagen'];
										  var etq_apartado = ['Código', 'Dependencia', 'Fecha',   'No. de Partida','Almacén', 'Imagen']; //'Tipo Apartado',
										    var etq_salida = ['Código', 'Cliente',     'Egreso',  'No. de Partida','Almacén', 'Imagen'];

										   var etq_entrada = ['Código', 'Proveedor',   'Ingreso', 'No. de Partida','Almacén', 'Imagen'];
										var etq_devolucion = ['Código', 'Proveedor',   'Ingreso', 'No. de Partida','Almacén'];
									
										      var etq_cero = ['Referencia', 'Almacén',  'Imagen',  'Especificaciones'];
										      var etq_baja = ['Referencia', 'Almacén',  'Imagen',  'Especificaciones'];
										       var etq_top = ['Referencia', 'Almacén',  'Imagen',  'Especificaciones'];
							

											


									switch(jQuery("#botones").val()) {
										   case "existencia":
										        var arreglo =etq_existencia;
										        break;
										    case "apartado":
										        var arreglo =etq_apartado;
										        break;
										    case "salida":
										        var arreglo =etq_salida;
										        break;
										    case "entrada":
										          var arreglo =etq_entrada;
										        break;
										    case "devolucion":
										          var arreglo =etq_devolucion;
										        break;
										    case "cero":
										        var arreglo =etq_cero;
										        break;
										    case "baja":
										        var arreglo =etq_baja;
										        break;
										    case "top":
										        var arreglo =etq_top;
										        break;
										    default:
										}


										var html	= '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
									    
											for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
									    		
												     html+= '<tr>';
												     html+=       '<td>'+arreglo[i]+'</td>';
												     html+=       '<td>'+datos.data[0][i]+'</td>';
												     html+=   '</tr>';

									    	}
									    	html	+= '</table>';

		                        		 $cad ='<div>'+html+'</div>';
										 row.child( $cad ).show();
		                                  tr.addClass('shown');
                                }  


                        }
                });        						



        } //fin else
    });



  jQuery("#producto_rep, #color_rep, #composicion_rep, #calidad_rep").on('change', function(e) {
         var campo = jQuery(this).attr("name");   
         var val_prod = jQuery('#producto_rep option:selected').text(); 
         var val_color = jQuery('#color_rep').val();           
         var val_comp = jQuery('#composicion_rep').val();          
         var val_calidad = jQuery('#calidad_rep').val(); 

         var dependencia = jQuery(this).attr("name"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
         cargarDependencia_reportes(campo,val_prod,val_color, val_comp, val_calidad,dependencia,nombre);
         var hash_url = window.location.pathname;


        if  ( (hash_url=="/") || (hash_url=="/reportes") )   {  
                comienzo=true; //para indicar que start comience en 0;
                var oTable =jQuery('#tabla_reporte').dataTable();
                oTable._fnAjaxUpdate();
        }   
     });

  function cargarDependencia_reportes(campo,val_prod,val_color, val_comp, val_calidad,dependencia,nombre) {
        jQuery.ajax({
                url : '/cargar_dependencia_reporte',
                data:{
                    campo:campo,
                    val_prod_id : jQuery('#producto_rep option:selected').val(),    //elemento** id
                    val_prod:val_prod,
                    val_color: val_color,
                    val_comp:val_comp,
                    val_calidad:val_calidad,
                    dependencia:dependencia,
                    extra_search: jQuery("#botones").val(),
                },
                type : 'POST',
                dataType : 'json',
                success : function(data) {

						    jQuery('#producto_rep').css('pointer-events', 'none');
						    jQuery('#color_rep').css('pointer-events', 'none');
						    jQuery('#composicion_rep').css('pointer-events', 'none');
						    jQuery('#calidad_rep').css('pointer-events', 'none');


                            var $elArray = new Array();
                            
                            $elArray['producto_rep']  = 'un producto';
                            $elArray['color_rep']  = 'un color';
                            $elArray['composicion_rep']  = 'una composición';
                            $elArray['calidad_rep']  = 'una calidad';
                            

                        jQuery.each(data, function (dep, valor) {
                            
                            jQuery("#"+dep).html(''); 

                            jQuery("#"+dep).append('<option value="0" >Seleccione '+$elArray[dep]+' </option>'); //+valor.nombre+

                                jQuery.each(valor, function (i, value) {
                                    
                                    hexadecimal_color = (value.hexadecimal_color)  ? (value.hexadecimal_color) : 'ffff';

                                    if (dep=="producto_rep") { //caso de producto
                                     jQuery("#"+dep).append('<option '+ ( (value.nombre==value.activo) ? 'selected' : '') +' value="' + value.nombre + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');         
                                    } else {
                                        jQuery("#"+dep).append('<option '+ ( (parseInt(value.id)==parseInt(value.activo)) ? 'selected' : '') +' value="' + value.id + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');      
                                    }
                                    

                                    
                                });
                        });




						    jQuery('#producto_rep').css('pointer-events', 'initial');
						    jQuery('#color_rep').css('pointer-events', 'initial');
						    jQuery('#composicion_rep').css('pointer-events', 'initial');
						    jQuery('#calidad_rep').css('pointer-events', 'initial');
                            

							

                    return false;
                    
                },
                error : function(jqXHR, status, error) {
                },
                complete : function(jqXHR, status) {
                    
                }
            });   	

  }	


var hash_url = window.location.pathname;
    if  ( (hash_url=="/") || (hash_url=="/reportes"))   {  
        //jQuery("#producto_pedido, #composicion_pedido, #ancho_pedido, #color_pedido, #proveedor_pedido").trigger('change');
        jQuery("#producto_rep").trigger('change');
    }  


jQuery('body').on('click','#limpiar_filtro_rep', function (e) {

    jQuery('#producto_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#color_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#composicion_rep option:eq(0)').prop('selected', 'selected');
    jQuery('#calidad_rep option:eq(0)').prop('selected', 'selected');

    jQuery("#producto_rep").trigger('change');

});     

/////////////////////////////////////////////////FILTROS/////////////////////////////////////////////////////////////

jQuery('.fecha_reporte').daterangepicker(
  	  { 
	    locale: { cancelLabel: 'Cancelar',
	    		  applyLabel: 'Aceptar',
	    		  fromLabel : 'Desde',
	    		  toLabel: 'Hasta',
	    		  monthNames : "ene._feb._mar_abr._may_jun_jul._ago_sep._oct._nov._dec.".split("_"),
	    		  daysOfWeek: "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
	     } , 
	    separator: ' / ',
	    format: 'DD-MM-YYYY',
	    //startDate: fecha_hoy, //'2014/09/01',
	    //endDate: fecha_hoy //'2014/12/31'
	  }
  );

jQuery('.fecha_reporte').on('apply.daterangepicker', function(ev, picker) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	oTable._fnAjaxUpdate();

});

jQuery('#id_estatuss').change(function(e) {
		comienzo=true; //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();
});

jQuery('#id_almacen_reporte').change(function(e) {
		comienzo=true; //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();
});

jQuery('#id_factura_reporte').change(function(e) {
		comienzo=true; //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();
});

jQuery('#id_medida_reporte').change(function(e) {
		comienzo=true; //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();
});



jQuery("#factura_reporte").on('keyup', function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	oTable._fnAjaxUpdate();
 });



jQuery("#foco").focusout(function (e) {
 //alert('sadasd');

 	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_reporte').dataTable();
	oTable._fnAjaxUpdate();

});








/////////////////////////buscar proveedores reportes

	// busqueda de proveedors reportes
	var consulta_proveedor_reporte = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   //remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_proveedor_reporte').attr("name")+'&idproveedor='+jQuery('.buscar_proveedor_reporte').attr("idproveedor"),

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_proveedor_reporte').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_proveedor_reporte.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_proveedor_reporte.tt-input').attr("idproveedor"));
	            
	            return  q;
	        }
	    },   

	});

	consulta_proveedor_reporte.initialize();
	jQuery('.buscar_proveedor_reporte').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  name: 'buscar_proveedor_reporte',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor_reporte.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_proveedor_reporte').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor_reporte').on('typeahead:selected', function (e, datum,otro) {
	    comienzo=true;  //para indicar que start comience en 0;
	    key = datum.key;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();


	});	

	jQuery('.buscar_proveedor_reporte').on('typeahead:closed', function (e) {
		comienzo=true;  //para indicar que start comience en 0;
		var oTable =jQuery('#tabla_reporte').dataTable();
		oTable._fnAjaxUpdate();

	});	








   //////////////////////////////////DEPENDENCIA//////////////////////////


//////////////////////////////////////////////////////////////////////////////////////
//////////////////Comienzo de tratamiento de dependencia///////////////////////////



	//jQuery("#producto, #color, #composicion, #calidad").on('change', function(e) {
	jQuery('body').on('change','#producto, #color, #composicion, #calidad', function (e) {
		var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto option:selected').text();  		  //elemento** id
 		 var val_color = jQuery('#color').val();  		  //elemento** id
 		 var val_comp = jQuery('#composicion').val();  		  //elemento** id
 		 var val_calida = jQuery('#calidad').val();  		  //elemento** id

 		 var hash_url = window.location.pathname;
         var dependencia = jQuery(this).attr("dependencia"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        //alert(valor);
    	if ((dependencia !="") && (hash_url!="/costo_inventario") && (hash_url!="/entrada_compra") ) {	    
	        //limpiar la dependencia
	        jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	        cargarDependencia(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre);
        }

        if  (hash_url=="/entrada_compra") {	    
        	jQuery("#"+dependencia).html(''); 
        	var id_compra = jQuery("#id_compra").val(); 
        	cargarDependencia_entrada_compra(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre,id_compra);
        }	


        //reportes
		
		if  ( (hash_url=="/costo_rollo") )   {  

				//comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_costo_rollo').dataTable();
				oTable._fnAjaxUpdate();
    	}	

		if  ( (hash_url=="/reportes") )   {  

				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_reporte').dataTable();
				oTable._fnAjaxUpdate();
    	}	


		if  ( (hash_url=="/") )   {  
				comienzo=true;  //para indicar que start comience en 0;
				//var oTable =jQuery('#tabla_home').dataTable();
				var oTable =jQuery('#tabla_reporte').dataTable();
				oTable._fnAjaxUpdate();
    	}	


		if  ( (hash_url=="/devolucion") )   {  //actualizar la regilla de abajo
				var oTable =jQuery('#tabla_devolucion').dataTable();
				oTable._fnAjaxUpdate();
    	}





    	//entradas

		if ((campo == 'calidad') && ( (hash_url=="/entradas") || (hash_url=="/editar_inventario") || (hash_url=="/devolucion") ) ) { //si calidad cambio de valor //|| (hash_url=="/entrada_compra") 
    		if  ((val_calida != "0") && (val_calida != "") && (val_calida != null)) 
    		{
    			
    		
				var id_cliente = jQuery('.buscar_proveedor').typeahead('val');
				var url = 'id_proveedor';	
				jQuery.ajax({  //para tomar la referencia del producto
				        url : '/refe_producto',
					    data:{
					        	id_cliente : id_cliente,
					        	val_prod:val_prod,
					        	val_color:val_color,
					        	val_comp:val_comp,
					        	val_calida:val_calida,
					        },
					        type : 'POST',
					        dataType : 'json',
				        success : function(dato) {

				        	codigo_proveedor =dato.cliente_id;
						        		lote =jQuery('#id_lote option:selected').text();
						        
						          referencia =dato.ref_prod.referencia; 
						          referencia2 = (referencia.substring(8, referencia.length));						              
						          comentario =dato.ref_prod.comentario; 
						          	  precio =dato.ref_prod.precio; 
						          	  ancho =dato.ref_prod.ancho; 


				        	codigo=codigo_proveedor+referencia2+lote+fecha_formateada;
				        				        	
				        	jQuery('#codigo').val(codigo);	

							jQuery('#referencia').val(referencia);

				        		
						
						if  ( (hash_url!="/editar_inventario") && (hash_url!="/devolucion") )   {  	
							jQuery('#ancho').val(ancho);
							jQuery('#precio').val(precio);
							jQuery('#codigo_contable').text(dato.ref_prod.codigo_contable);
						}	
							

						},
				        error : function(jqXHR, status, error) {
				        },
				        complete : function(jqXHR, status) {
				            
				        }											        	
				});					

			


			}	


		}	



			//entrada_compra
	if (hash_url=="/entrada_compra")  { //si calidad cambio de valor
    		if  ((val_calida != "0") && (val_calida != "") && (val_calida != null)) 	{
    			
    			var id_compra= jQuery('#id_compra option:selected').val();
				var id_cliente = jQuery('.buscar_proveedor').typeahead('val');
				var url = 'id_proveedor';	
				jQuery.ajax({  
				        url : '/refencia_producto_compra',
					    data:{
					        	id_cliente : id_cliente,
					        	val_prod:val_prod,
					        	val_color:val_color,
					        	val_comp:val_comp,
					        	val_calida:val_calida,
					        	id_compra:id_compra
					        },
					        type : 'POST',
					        dataType : 'json',
				        success : function(dato) {

				        	codigo_proveedor =dato.cliente_id;
						        		lote =jQuery('#id_lote option:selected').text();
						        
						          referencia =dato.ref_prod.referencia; 
						          referencia2 = (referencia.substring(8, referencia.length));						              
						          comentario =dato.ref_prod.comentario; 
						          	  precio =dato.ref_prod.precio; 
						          	  ancho =dato.ref_prod.ancho; 
						        	codigo=codigo_proveedor+referencia2+lote+fecha_formateada;
						        				        	
						        	jQuery('#codigo').val(codigo);	
									jQuery('#referencia').val(referencia);
									jQuery('#ancho').val(ancho);
									jQuery('#precio').val(precio);
									jQuery('#codigo_contable').text(dato.ref_prod.codigo_contable);

									//"total_entrada_mts":"0.00","total_entrada_kg":"0.00","total_compra_mts":"2.00","total_compra_kg":"2.00"

									//number_format(parseFloat(row[18]), 2, '.', ',');	
									
									


									 jQuery("#id_medida").html(''); 		
									 if ( ( parseFloat(dato.ref_prod.total_compra_mts)- (parseFloat(dato.ref_prod.total_entrada_mts) +parseFloat(dato.ref_prod.total_entrada_temp_mts))   ) > 0  ) {
									 	diferencia_mt= number_format(parseFloat(dato.ref_prod.total_compra_mts)-(parseFloat(dato.ref_prod.total_entrada_mts) +parseFloat(dato.ref_prod.total_entrada_temp_mts)), 2, '.', ',');
									 	jQuery("#id_medida").append('<option value="1" dif="'+diferencia_mt+'" >Mts</option>');	
									 	//jQuery("#diferencia").text(diferencia_mt);	
									 }
									
									 if ( ( parseFloat(dato.ref_prod.total_compra_kg)-(parseFloat(dato.ref_prod.total_entrada_kg) +parseFloat(dato.ref_prod.total_entrada_temp_kg)) ) > 0 )	{
									 	diferencia_kg = number_format(parseFloat(dato.ref_prod.total_compra_kg)-(parseFloat(dato.ref_prod.total_entrada_kg) +parseFloat(dato.ref_prod.total_entrada_temp_kg)), 2, '.', ',');

									 	jQuery("#id_medida").append('<option value="2" dif="'+diferencia_kg+'" >Kgs</option>');	
									 	//jQuery("#diferencia").text(diferencia_kg);	
									 } 
									 

									 jQuery('#id_medida[tipo="entrada_compra"]').trigger('change');

								
							

						},
				        error : function(jqXHR, status, error) {
				        },
				        complete : function(jqXHR, status) {
				            
				        }											        	
				});					

			


			}	









		}


     });

//cuando cambia las medidas en las entrada de compra



jQuery('body').on('change','#id_medida[tipo="entrada_compra"]', function (e) {
	var este=jQuery(this);
  jQuery('#cantidad_um').val( este.find('option:selected').attr('dif')   ); //attr('dif')
  jQuery('#cantidad_royo').val( 1  ); //attr('dif')
  /*
  $(this).find('option:selected').text();
  $('option:selected',this).attr('value');
  $('#select-id').children('option:selected').text();
  */
	
});

//obligarlo a disparar


jQuery('body').on('change','#id_compra', function (e) {
		
	  jQuery('input[name=editar_proveedor]').typeahead("val", jQuery('#id_compra option:selected').attr('prov_nomb') );
	  jQuery('#id_almacen option[value="'+jQuery('#id_compra option:selected').attr('almacen')+'"]').prop('selected', true);
	  //provocar movimiento en almacen para q actualice los consecutivos
	  jQuery('#id_factura[tipo="entrada"]').trigger('change');

	  jQuery("#producto").html(''); 
	  var id_compra= jQuery('#id_compra option:selected').val();
	  cargarDependencia_entrada_compra("producto",0,0,0,0,"producto","producto",id_compra);

});    

	function cargarDependencia_entrada_compra(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre,id_compra) {


		jQuery.ajax({
		        url : 'cargarDependencia_entrada_compra',
		        data:{
		        	campo:campo,
		        	
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,
		        	dependencia:dependencia,
		        	id_compra: id_compra
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
		        	 //jQuery("#"+dependencia).trigger('change');
	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
                    
					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	
						
					if (jQuery('#oculto_producto').val() == 'si') {
						if (dependencia=='color') {
							jQuery('#color').val(jQuery('#oculto_producto').attr('color'));	
						}

						if (dependencia=='composicion') {
							//jQuery('#composicion').val("2");	
							jQuery('#composicion').val(jQuery('#oculto_producto').attr('composicion'));	
						}

						if (dependencia=='calidad') {
							jQuery('#calidad').val(jQuery('#oculto_producto').attr('calidad'));	
							jQuery('#oculto_producto').val('no');
						}
					}	
					

					jQuery("#"+dependencia).trigger('change');
	                
                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}


	function cargarDependencia(campo,val_prod,val_color,val_comp,val_calida,dependencia,nombre) {
		
		var url = 'cargar_dependencia';	

		jQuery.ajax({
		        url : 'cargar_dependencia',
		        data:{
		        	campo:campo,
		        	
		        	val_prod:val_prod,
		        	val_color:val_color,
		        	val_comp:val_comp,
		        	val_calida:val_calida,
		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
		        	 //jQuery("#"+dependencia).trigger('change');
	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
                    
					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	
						
					if (jQuery('#oculto_producto').val() == 'si') {
						if (dependencia=='color') {
							jQuery('#color').val(jQuery('#oculto_producto').attr('color'));	
						}

						if (dependencia=='composicion') {
							//jQuery('#composicion').val("2");	
							jQuery('#composicion').val(jQuery('#oculto_producto').attr('composicion'));	
						}

						if (dependencia=='calidad') {
							jQuery('#calidad').val(jQuery('#oculto_producto').attr('calidad'));	
							jQuery('#oculto_producto').val('no');
						}
					}	
					

					jQuery("#"+dependencia).trigger('change');
	                //
	               // jQuery('#color').change();
                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}

//////////////////fin de tratamiento de dependencia///////////////////////////


abrir = function(verb, url, data, target) {
  var form = document.createElement("form");
  form.action = url;
  form.method = verb;
  form.target = target || "_self";
  if (data) {
    for (var key in data) {
      var input = document.createElement("textarea");
      input.name = key;
      input.value = typeof data[key] === "object" ? JSON.stringify(data[key]) : data[key];
      form.appendChild(input);
    }
  }
  form.style.display = 'none';
  document.body.appendChild(form);
  form.submit();
};


//Agregar las estradas a salidas
jQuery('body').on('click','#exportar_reportes', function (e) {

	  	  busqueda      = jQuery('input[type=search]').val();

	   extra_search = jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss").val(); 
	   id_almacen = jQuery("#id_almacen_reporte").val(); 
	   id_factura = jQuery("#id_factura_reporte").val(); 

	     				   //datos del producto
	   //id_descripcion = jQuery("#producto").val(); 
	   //id_descripcion = jQuery('#producto option:selected').text();

		   id_descripcion = jQuery("#producto_rep").val(); 
		   if (id_descripcion !='') {
		   	  id_descripcion = jQuery('#producto_rep option:selected').text();
		   }
		   val_prod_id = jQuery('#producto_rep option:selected').val();


	   id_color = jQuery("#color_rep").val(); 
	   id_composicion = jQuery("#composicion_rep").val(); 
	   id_calidad = jQuery("#calidad_rep").val(); 
		
		factura_reporte = jQuery('#factura_reporte').val();					

		proveedor = jQuery("#editar_proveedor_reporte").val(); 	   

		var fecha = (jQuery('.fecha_reporte').val()).split(' / ');

		fecha_inicial = fecha[0];
		fecha_final = fecha[1];

		id_medida = jQuery("#id_medida_reporte option:selected").val();


    abrir('POST', 'exportar_reportes', {
    			busqueda:busqueda,
			extra_search:extra_search,
			id_factura:id_factura,
			id_estatus:id_estatus,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			val_prod_id: val_prod_id,
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			id_medida:id_medida
    }, '_blank' );
		        
	
});


	function addslashes(string) {
	    return string.replace(/\\/g, '\\\\').
	        replace(/\u0008/g, '\\b').
	        replace(/\t/g, '\\t').
	        replace(/\n/g, '\\n').
	        replace(/\f/g, '\\f').
	        replace(/\r/g, '\\r').
	        replace(/'/g, '\\\'').
	        replace(/"/g, '\\"');
	}

		/*
         $cadena = addslashes($data['search']['value']);
          $inicio = $data['start'];
          $largo = $data['length'];
          $estatus= $data['extra_search'];
          $id_estatus= $data['id_estatus'];
          $id_empresa= addslashes($data['proveedor']);
          $id_almacen= $data['id_almacen'];
          $id_factura= $data['id_factura'];

          $factura_reporte = $data['factura_reporte'];

          $columa_order = $data['order'][0]['column'];
                 $order = $data['order'][0]['dir'];
                 */


jQuery('body').on('click','#impresion_rapida', function (e) {
	

	//codigo = jQuery.base64.encode('1420150716lTvr62600130072015_1'); //jQuery("#editar_prod_inven").val(); 
	
	  //$('input[type=search]').on('search', function () {	

	  	  busqueda      = jQuery('input[type=search]').val();
	   extra_search = jQuery("#botones").val(); 
	   id_estatus = jQuery("#id_estatuss").val(); 
	   id_almacen = jQuery("#id_almacen_reporte").val(); 

	   id_factura = jQuery("#id_factura_reporte").val(); 


	     				   //datos del producto
	   //id_descripcion = jQuery("#producto").val(); 
	   //id_descripcion = jQuery('#producto option:selected').text();

	   id_descripcion = jQuery("#producto_rep").val(); 
	   if (id_descripcion !='') {
	   	  id_descripcion = jQuery('#producto_rep option:selected').text();
	   }

	   val_prod_id = jQuery('#producto_rep option:selected').val();

	   id_color = jQuery("#color_rep").val(); 
	   id_composicion = jQuery("#composicion_rep").val(); 
	   id_calidad = jQuery("#calidad_rep").val(); 
		
		factura_reporte = jQuery('#factura_reporte').val();					

		proveedor = jQuery("#editar_proveedor_reporte").val(); 	   

		var fecha = (jQuery('.fecha_reporte').val()).split(' / ');

		fecha_inicial = fecha[0];
		fecha_final = fecha[1];

		id_medida = jQuery("#id_medida_reporte option:selected").val();

		var oTable =jQuery('#tabla_reporte').DataTable();
		order = oTable.order();

    abrir('POST', 'imprimir_rapida', {
    			busqueda:busqueda,
    			id_factura:id_factura,
			extra_search:extra_search,
			id_estatus:id_estatus,
			id_almacen: id_almacen,

			id_descripcion:id_descripcion, 
			val_prod_id:val_prod_id,
			id_color:id_color, 
			id_composicion:id_composicion, 
			id_calidad:id_calidad,

			factura_reporte: factura_reporte,

			proveedor:proveedor, 
			fecha_inicial:fecha_inicial, 
			fecha_final: fecha_final,
			columna : order[0][0],
			orden : order[0][1],
			id_medida:id_medida
    }, '_blank' );
	
});



 

jQuery('#exportar_reporte').click(function (e) {

	var fecha = (jQuery('.fecha_reporte').val()).split(' / ');

	jQuery.ajax({
		        url : 'exportar_reporte',
		        data : { 
					extra_search 	: jQuery("#botones").val(), 
					id_estatus 	 	: jQuery("#id_estatuss").val(), 
					//id_almacen 	 	: jQuery("#id_almacen_reporte").val(),
					id_factura      : jQuery("#id_factura_reporte").val(),
					id_descripcion 	: jQuery("#producto").val(),
					id_color 		: jQuery("#color").val(), 
					id_composicion 	: jQuery("#composicion").val(),
					id_calidad 		: jQuery("#calidad").val(),
					proveedor 		: jQuery("#editar_proveedor_reporte").val(),
					fecha_inicial 	: fecha[0],
					fecha_final 	: fecha[1]
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
		        	//
		        }
	});						        

     				   

});
////////////////// Fin de reportes////////////////////////////////////////////////////////////


////////////////// Aqui comienza DASHBOARD////////////////////////////////////////////////////////////
	jQuery('#myModaldashboard').on('hide.bs.modal', function(e) {
	    jQuery(this).removeData('bs.modal');
	}); 
////////////////// hasta aqui la de DashBoard////////////////////////////////////////////////////////////


////////////////// Comienza Inicio////////////////////////////////////////////////////////////
//http://stackoverflow.com/questions/21934121/jquery-datatable-overflows-in-bootstrap-modal
///http://www.bootply.com/88364
//var mitable = jQuery('#tabla_producto_color').DataTable();
//mitable.on( 'draw', function () {
//jQuery('body').live('dataTable','#tabla_producto_color', function (e) {


jQuery('body').on('click','.apartar', function (e) {


	identificador = (jQuery(this).attr('identificador'));

	jQuery.ajax({
		        url : 'marcando_apartado',
		        data : { 
		        	identificador: identificador,

		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	

					   var oTable =jQuery('#tabla_producto_color').dataTable();
					   oTable._fnAjaxUpdate();      


						if(data != true){
								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'noapartar');
												return false;	
									        }
								});	

							 
						}else{
								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'apartar');
												return false;	
									        }
								});	
						}


		        }
	});						        
});


			jQuery('#myModalInicio').on('hide.bs.modal', function(e) {
			    jQuery(this).removeData('bs.modal');
			});	



		jQuery('#myModalInicio').on('shown.bs.modal', function() {

				 jQuery('body').on('change','#id_color_grupo', function (e) {

				           var hexacolor = jQuery("#id_color_grupo option:selected").attr('hexacolor');
				           var precio = jQuery("#id_color_grupo option:selected").attr('precio');
				           var imagen = jQuery("#id_color_grupo option:selected").attr('imagen');

				             
				            var url= 'uploads/productos/thumbnail/300X300/'+imagen.substr(0, (imagen.lastIndexOf(".")))+'_thumb'+imagen.substr(imagen.lastIndexOf(".")); 
							

								if (url) { 
								        var req = new XMLHttpRequest();
								        req.open('GET', url, false);
								        req.send();
								        $estatus= req.status==200;
								    } else {
								        $estatus= false;
								 }


									if ($estatus==true) { 

										var d = new Date();
										var fechaSegundos = d.getTime();
										//console.log(fechaSegundos);
																	
							          	//var fechaSegundos = time(); 
							         	 var strNoCache = '?nocache='+fechaSegundos.toString(); 
										//alert(imagen);
		     				 	   		var imagen='uploads/productos/thumbnail/300X300/'+imagen.substr(0, (imagen.lastIndexOf(".")))+'_thumb'+imagen.substr(imagen.lastIndexOf(".")); 

		     				 	   		jQuery('.img_peque').attr( 'src',imagen+strNoCache);			
		     				 	   	} else {
		     				 	   		jQuery('.img_peque').attr( 'src','img/sinimagen.png');		
		     				 	   	}





     				 	  
				 	       jQuery('#color_box').css( "background-color", "#"+hexacolor );//.css({ "background-color": "#edf3e"} ); //'"'+hexacolor+'"' 

				 	       if (precio!=undefined){
				 	       	 jQuery('.preciocatalogo').text('Precio: $'+precio );	
				 	       } else {
				 	       	jQuery('.preciocatalogo').text('             ');
				 	       }
				 	       
				 	       
						  var oTable =jQuery('#tabla_producto_color').dataTable();
						  oTable._fnAjaxUpdate();

						  var oTable =jQuery('#tabla_producto_color2').dataTable();
						  oTable._fnAjaxUpdate();     
				  }); 


				jQuery('#tabla_producto_color').dataTable( {
					"pagingType": "full_numbers",
					"processing": true,
					"serverSide": true,
					"ajax": {
				            	"url" : "procesando_producto_color",
				         		"type": "POST",
								"data": function ( d ) {
								       d.grupo = jQuery("#grupo_oculto").val();  
								       d.id_color = jQuery("#id_color_grupo").val();  
								       d.id_almacen = jQuery("#id_almacen").val();  
								       
								 }
				     },   

					

					"infoCallback": function( settings, start, end, max, total, pre ) {
					    if (settings.json.totales) {
						    //jQuery('#metro_disp').html( 'Total de piezas:'+ settings.json.totales.pieza);
						  
							jQuery('#metro_disp').html( number_format(settings.json.totales.metro_disp, 2, '.', ',')+' mts');
							jQuery('#metro_nodisp').html( number_format(settings.json.totales.metro_nodisp, 2, '.', ',')+' mts');
							jQuery('#kilogramo_disp').html( number_format(settings.json.totales.kilogramo_disp, 2, '.', ',')+' kgs');
							jQuery('#kilogramo_nodisp').html(number_format(settings.json.totales.kilogramo_nodisp, 2, '.', ',')+' kgs');

						} else {
						 
							jQuery('#metro_disp').html('0.00 mts');
							jQuery('#metro_nodisp').html( '0.00 mts');
							jQuery('#kilogramo_disp').html('0.00 kgs');
							jQuery('#kilogramo_nodisp').html('0.00 kgs');


						}	



							if (settings.json.recordsTotal==0) {
								jQuery("#disa_reportes").attr('disabled', true);					
							} else {
								jQuery("#disa_reportes").attr('disabled', false);					
							}

					    return pre
				  	} ,    


					 
					 
					 "rowCallback": function( row, data ) {
					    
					    if ( data[6] == 1 ) {
					      jQuery('td', row).addClass( "danger" );
					    }

					    if ( data[6] == 0 ) {
					      jQuery('td', row).removeClass( "danger" );
					    }
					  },	




				    
				   "columnDefs": [
				    			{ 
					                "render": function ( data, type, row ) {
										return data;	
					                },
					                "targets": [0,1,2,3,4]
					            },
				    			{ 
					                "render": function ( data, type, row ) {

				                          texto= '<td> <button type="button"  identificador="'+row[5]+'" class="btn btn-success btn-block apartar '+row[5]+'">';
				                             texto+=' <span class="letra_apartar">Apartar</span>';
				                          texto+=' </button> <td>';

										return texto;	
					                },
					                "targets": [5]
					            },
				    			{ 
					                "render": function ( data, type, row ) {

										return row[7];	
					                },
					                "targets": [6]
					            },				    				                      

					],	

					"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
						var arreglo =producto_color;
						for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
					    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
					    	}
					},	

					"language": {  //tratamiento de lenguaje
						"lengthMenu": "Mostrar _MENU_ registros por página",
						"zeroRecords": "No hay registros",
						"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"infoEmpty": "No hay registros disponibles",
						"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
						"emptyTable":     "No hay registros",
						"infoPostFix":    "",
						"thousands":      ",",
						"loadingRecords": "Leyendo...",
						"processing":     "Procesando...",
						"search":         "Buscar:",
						"paginate": {
							"first":      "Primero",
							"last":       "Último",
							"next":       "Siguiente",
							"previous":   "Anterior"
						},
						"aria": {
							"sortAscending":  ": Activando para ordenar columnas ascendentes",
							"sortDescending": ": Activando para ordenar columnas descendentes"
						},
					},
				});	

jQuery('#tabla_producto_color2').dataTable( {
					"pagingType": "full_numbers",
					"processing": true,
					"serverSide": true,
					"ajax": {
				            	"url" : "procesando_producto_color2",
				         		"type": "POST",
								"data": function ( d ) {
								       d.grupo = jQuery("#grupo_oculto").val();  
								       d.id_color = jQuery("#id_color_grupo").val();  
								       d.id_almacen = jQuery("#id_almacen").val();  
							       
								 }

				     },   




					"infoCallback": function( settings, start, end, max, total, pre ) {
					    return pre
					},    
					 
					 
					 "rowCallback": function( row, data ) {
					    
					    if ( data[6] == 1 ) {
					      jQuery('td', row).addClass( "danger" );
					    }

					    if ( data[6] == 0 ) {
					      jQuery('td', row).removeClass( "danger" );
					    }
					  },	
				    
				   "columnDefs": [
				    			{ 
					                "render": function ( data, type, row ) {
										return data;	
					                },
					                "targets": [0,1,2,3,4]
					            },

				    			{ 
					                 "visible": false,
					                "targets": [5]
					            },

				    			{ 
					                "render": function ( data, type, row ) {

										return row[7];	
					                },
					                "targets": [6]
					            },					            

					            
				    				                      

					],	

					"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
						var arreglo =producto_color1;
						for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
					    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
					    	}
					},	

					"language": {  //tratamiento de lenguaje
						"lengthMenu": "Mostrar _MENU_ registros por página",
						"zeroRecords": "No hay registros",
						"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
						"infoEmpty": "No hay registros disponibles",
						"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
						"emptyTable":     "No hay registros",
						"infoPostFix":    "",
						"thousands":      ",",
						"loadingRecords": "Leyendo...",
						"processing":     "Procesando...",
						"search":         "Buscar:",
						"paginate": {
							"first":      "Primero",
							"last":       "Último",
							"next":       "Siguiente",
							"previous":   "Anterior"
						},
						"aria": {
							"sortAscending":  ": Activando para ordenar columnas ascendentes",
							"sortDescending": ": Activando para ordenar columnas descendentes"
						},
					},
				});


});

jQuery('#id_almacen_inicio').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_inicio').dataTable();
	oTable._fnAjaxUpdate();		
});


jQuery('#id_factura_inicio').change(function(e) {
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_inicio').dataTable();
	oTable._fnAjaxUpdate();		
});


jQuery('#tabla_inicio').dataTable( {
	

	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_inicio",
         		"type": "POST",
				"data": function ( d ) {

				     d.id_estatus = jQuery("#id_estatus").val();  
				       d.id_color = jQuery("#id_color").val();  
				       d.id_almacen = jQuery('#id_almacen_inicio').val();  
				       d.id_factura_inicio = jQuery('#id_factura_inicio').val();  
				       
				 }

     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	}, 
   "columnDefs": [
			    { //+'"
	                "render": function ( data, type, row ) {
	                	if (data) {


                                        url='uploads/productos/thumbnail/300X300/'+data[0].substr(0, (data[0].lastIndexOf(".")))+'_thumb'+data[0].substr(data[0].lastIndexOf(".")); 
										//checar si la imagen existe. de lo contrario no imprimir nada										
										if(url){ 
										        var req = new XMLHttpRequest();
										        req.open('GET', url, false);
										        req.send();
										        $estatus= req.status==200;
										    } else {
										        $estatus= false;
										 }


										prod='<div class="col-lg-11 col-md-11 col-xs-11 thumb">';
                                        prod+= '<a href="detalles_grupo/'+jQuery.base64.encode(data[2])+'/'+jQuery.base64.encode(jQuery('#id_almacen_inicio').val())+'" class="thumbnail col-md-12 col-lg-12 col-xs-12" data-toggle="modal" data-target="#myModalInicio">';



										if ($estatus==true) {
		                                        prod+=        '<img class="img-responsive" src="uploads/productos/thumbnail/300X300/'+
													data[0].substr(0, (data[0].lastIndexOf(".")))+'_thumb'+data[0].substr(data[0].lastIndexOf(".")) 
		                                        +'" alt="" border="0" width="260" height="195">';
										} else {  //caso q imagen no existe, porque fue borrada
	                                        prod+= '<img class="img-responsive" src="img/sinimagen.png" alt="" border="0" width="260" height="195">';

										};    

										if (data[4]!='') {
				                			prod+=       '<span class="col-xs-12 col-md-12 col-lg-12 nombre">'+data[1]+'</span>'
				                			+'<br/><b style="color:red;">Cód: </b>'+data[4];
				                		} else {
				                			prod+=       '<span class="col-xs-12 col-md-12 col-lg-12 nombre">'+data[1]+'</span>';
				                		}
                                        

                                        prod+=       '<span class="col-xs-12 col-md-12 col-lg-12 text-right cantidadtotal">'+data[3]+'</span>';
                                        prod+='</a>';
                                        prod+= '</div>';

 
                                     return prod;  
						   }
						else //no tiene asociada imagen
							return "";
	                },
	                "targets": [ 0,1,2,3 ],
	            },

		        { 
		                 "visible": false,
		                "targets": [4]
		            }
	],

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar por nombre o color:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	



////////////////////////Pedidos de Apartados /////////////////////////////////////
jQuery('body').on('click','.prorrogar_venta', function (e) {
	
	id_usuario_apartado = (jQuery(this).attr('id_usuario_apartado'));
	id_cliente_apartado = (jQuery(this).attr('id_cliente_apartado'));
	consecutivo_venta = (jQuery(this).attr('consecutivo_venta'));


	jQuery.ajax({
		        url : 'marcando_prorroga_venta',
		        data : { 
		        	id_usuario_apartado: id_usuario_apartado,
		        	id_cliente_apartado: id_cliente_apartado,
		        	id_almacen:jQuery('#id_almacen_pedido').val(),
		        	consecutivo_venta:consecutivo_venta,

		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
		        	   jQuery('#tabla_apartado').dataTable().fnDraw();
		        }
	});						        
});


jQuery('#myModal').on('hide.bs.modal', function(e) {
    jQuery(this).removeData('bs.modal');
});	

//hola mi niño, acabo de ver tu sms, cuidate mucho, que te recuperes pronto, fin d semana te llamo, no estoy en el DF ahora, te ama tu papi


jQuery("#id_tipo_pedido_inicio").on('change', function(e) {
	if (jQuery(this).val()==2) {
		jQuery('.tipo_factura').css('display','none');			
	} else {
		jQuery('.tipo_factura').css('display','block');	
	}
});


jQuery('#id_tipo_pedido_inicio, #id_tipo_factura_inicio').on('change', function(e) {
	consecutivo_actual = (( (jQuery("#id_tipo_pedido_inicio").val() == 1) && (jQuery("#id_tipo_factura_inicio").val()==1) ) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
	consecutivo_actual = ( (jQuery("#id_tipo_pedido_inicio").val()==2) ? jQuery("#conse_surtido").val() : consecutivo_actual);

	jQuery("#movimiento").val(consecutivo_actual);
});


jQuery('body').on('click','#conf_apartado', function (e) {

	id_tipo_pedido = jQuery("#id_tipo_pedido_inicio").val();
	id_tipo_factura = ( (id_tipo_pedido==2) || (id_tipo_pedido==3) ) ? 0:jQuery("#id_tipo_factura_inicio").val();
	factor_salida =  (id_tipo_pedido==2)  ? "S" : ((id_tipo_pedido==3) ? "G" : jQuery("#id_tipo_factura_inicio option:selected").text() ) ;	

	proveedor=jQuery('.buscar_proveedor').typeahead("val");

	//tipo_pedido = jQuery("#id_tipo_pedido_inicio option:selected").text();
	//tipo_factura = ( (id_tipo_pedido==2) || (id_tipo_pedido==3) )  ? "no":jQuery("#id_tipo_factura_inicio option:selected").text();
	//num_mov=jQuery('#movimiento').val();
	



		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

	jQuery.ajax({
		        url : 'apartado_definitivo',
		        data : { 
					//num_mov: num_mov,
		        	id_tipo_pedido:id_tipo_pedido,
		        	id_tipo_factura:id_tipo_factura,
		        	factor_salida: factor_salida,
		        	cliente: proveedor, 
		        	id_operacion_pedido  : 96,

		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(datos) {	
		        
						if(datos.exito != true){
								
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(datos.mensaje);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);

						}else{

						
						    abrir('POST', 'imprimir_reportes_apartado', {
						    			datos: JSON.stringify(datos),
						    }, '_blank' );							
						    
							spinner.stop();
							jQuery('#foo').css('display','none');


								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'conf_apartado');
									        	window.location.href = '/';
									        }
								});										
					
							 return false;
							
						}
		        }
	});		

			        
});

jQuery('body').on('click','#ver_dis', function (e) {
  jQuery( "#cont_tab" ).animate({height: 'toggle'});
});


jQuery('body').on('click','#ver_filtro', function (e) {
  jQuery( "#tab_filtro" ).animate({height: 'toggle'});
});

jQuery('#id_estatus, #id_color').change(function(e) {
	var hash_url = window.location.pathname;
	if  ( (hash_url!="/entradas") )  {  //sino es entrada
		var oTable =jQuery('#tabla_inicio').dataTable();
		oTable._fnAjaxUpdate();
	}	
});




jQuery('#tabla_apartado').dataTable({ 
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_apartado_pendiente",
         		"type": "POST",
				"data": function ( d ) {
						    d.id_almacen = jQuery('#id_almacen_pedido').val();	
	         			   d.id_operacion_pedido = jQuery('#id_operacion_pedido').val();	
		   				   d.num_mov = jQuery('#num_mov').val();
		   				   var fecha = (jQuery('.fecha_reporte_pedido').val()).split(' / ');

							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];


    			 }          		
     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	},    

	 "rowCallback": function( row, data ) {
	    if ( data[8] == 1 ) {
	      jQuery('td', row).addClass( "danger" );
	    }

	    if ( data[8] == 0 ) {
	      jQuery('td', row).removeClass( "danger" );
	    }

	  },	

   "columnDefs": [
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,3,4,5] //0=vendedor, 3=fecha_apartado
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[19]; // num mov
	                },
	                "targets": [1]
	            },		            
    			{ 
	                "render": function ( data, type, row ) {
						return row[2]; //cliente o comprador
	                },
	                "targets": [2]
	            },	   

				{ 
	                "render": function ( data, type, row ) {
						return row[12];	 //tipo_pedido
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[13];	 //tipo_factura
	                },
	                "targets": [7]
	            }, 	

    			{ 
	                "render": function ( data, type, row ) {
    					 //texto='<td><a href="apartado_detalle/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(row[7])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[11])+'/'+jQuery.base64.encode(row[20])+'" '; 
						
    					 texto='<td><a href="apartado_detalle/'+jQuery.base64.encode(row[11])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[20])+'" '; 
    					 	texto+=' class="btn btn-success  btn-block">';
						 	texto+=row[17]+' Detalles';
						 texto+='</a></td>';


						return texto;	
	                },
	                "targets": [8]
	            },


    			
    			{ 
	                "render": function ( data, type, row ) {
	                	if (row[14]!=3) {
	                		//jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(row[7])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[11])+'/'+jQuery.base64.encode(row[15])+'/'+jQuery.base64.encode(row[16])+'" '; 
							texto='<td><a href="eliminar_apartado_detalle/'+jQuery.base64.encode(row[11])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[20])+'" '; 
								texto+='class="btn btn-danger  btn-block" data-toggle="modal" data-target="#modalMessage">';
								texto+='<span class="glyphicon glyphicon-remove"></span>';
							texto+='</a></td>';
						} else {


								texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';									

						}
							
						return texto;	

	                },
	                "targets": [9]
	            },


    			{ 
	                "render": function ( data, type, row ) {
						return row[10];	 //almacen
	                },
	                "targets": [10]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[18];	//importe
	                },
	                "targets": [11]
	            },


  				{ 
		                 "visible": false,
		                "targets": [12,13,14,15,16,17,18,19,20]
		        }


	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var api = this.api();

		if ( jQuery('#config_salida').val() == 0 ) {
			api.column(4).visible(false);	
			api.column(5).visible(false);	
		} else {
			api.column(4).visible(true);	
			api.column(5).visible(true);	
		}



		var arreglo =apartado_pendiente;
		api.column(11).visible(true);	
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}

	    	
				if (aData.length >0){
      				if (aData[0][18]=="-") {
                		api.column(11).visible(false);	
                	} else {
                		api.column(11).visible(true);	
		     			
                	}
                } else {
	            		api.column(11).visible(false);	
                }	

	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


	/*
	jQuery('#modalMessage').on('hide.bs.modal', function(e) {
	    jQuery(this).removeData('bs.modal');
	});	
	*/

	//gestion de usuarios (crear, editar y eliminar )
	//jQuery("#form_apartado").submit(function(e){

    jQuery('body').on('submit','#form_apartado', function (e) {


		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					    $catalogo = e.target.name;
					    
						spinner.stop();
						jQuery('#foo').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'form_apartado');
									        	//alert('este');
									        	window.location.href = '/'+$catalogo;	
									        }
								});		



						//window.location.href = '/'+$catalogo;	
				}
			} 
		});
		return false;
	});	



jQuery('body').on('submit','#form_pedido', function (e) {


		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					    $catalogo = e.target.name;
					    
						spinner.stop();
						jQuery('#foo').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'form_pedido');
									        	//alert('este');
									        	window.location.href = '/'+$catalogo;	
									        }
								});		



						//window.location.href = '/'+$catalogo;	
				}
			} 
		});
		return false;
	});	


////////////////////////Pedidos de Apartados /////////////////////////////////////

jQuery('#tabla_detalle').dataTable( {
	"pagingType": "full_numbers",
	"pageLength": 500,  //que soporte 500 registro
	"lengthChange": false,  //y que deshabilite el cambio de cantidad de registro
	scroller:       true,
    scrollY:        250,
    scrollCollapse: false,

	
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_detalle",
         		"type": "POST",
         		 "data": function ( d ) {
     				   d.num_mov = jQuery('#num_mov').val();	
         			   d.id_almacen = jQuery('#id_almacen_pedido').val();	
         			   d.id_operacion_pedido = jQuery('#id_operacion_pedido').val();	
	   				   

    			 } 

     },   

	"footerCallback": function( tfoot, data, start, end, display ) {
		

	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 17)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				total_pieza = (end-start);	
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));
		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	
		}	
    },	

	"infoCallback": function( settings, start, end, max, total, pre ) {


		if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}
	    if (settings.json.datos) {
	    	jQuery('#etiq_num_mov').val(  jQuery('#consecutivo_venta').val());
		    jQuery('#etiq_usuario').val(  settings.json.datos.usuario);
		    jQuery('#etiq_cliente').val(  settings.json.datos.cliente);
		    jQuery('#etiq_dependencia').val(  settings.json.datos.cliente);
			jQuery('#etiq_comprador').val(  settings.json.datos.comprador+'  Nro.'+settings.json.datos.mov);   //jQuery('#consecutivo_venta').val());

		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    jQuery('#etiq_hora').val(  settings.json.datos.mi_hora);
		    jQuery('#etiq_tipo_apartado').html(  settings.json.datos.tipo_apartado);
		    jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#'+settings.json.datos.color_apartado+';width:15px;height:15px;"></div>');

		    /*
		    jQuery('#id_tipo_pedido').val(settings.json.datos.id_tipo_pedido);
		    jQuery('#id_tipo_factura').val(settings.json.datos.id_tipo_factura);
		    */

 			if (settings.json.datos.tipo_factura!=null) {
		    	jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido+' - '+settings.json.datos.tipo_factura );		
		    }else {
		    	jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido);	
		    }

		}	else {
			window.location.href = '/pedidos';	
		}
	    return pre
	},    

  				"columnDefs": [
  				{ 
		                "render": function ( data, type, row ) {
								var color;
		                		switch (row[20]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 


		                		if (row[18]!='') {
		                			return row[0]+'<br/><b style="color:'+color+';">Cód: </b>'+row[18];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [1,2,3,4,5,6,7,8,9,10],
	            },

	            {
	                "render": function ( data, type, row ) {
						if ( jQuery('#config_salida').val() == 0 ) {
							texto='<td>'; 
								texto+='<input restriccion="decimal" value="'+row[15]+'" codigo="'+row[0]+'" type="text" class="form-control ttip peso_real" title="Números y puntos decimales."  placeholder="0.00">';							
							texto+='</td>';
						} else {
							texto=row[14];
						}	
						return texto;	

	                },
	                "targets": 11
	            },
  				{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [12],
	            },	            

    			{ 
	                 "visible": false,
	                "targets": [13,14,15,16,17,18,19,20], 
	            }	            

	],	

	"rowCallback": function( row, data ) {
					    
		    if (( data[11] != data[12]) && ( data[12] != 0)  ) {
		      jQuery('td', row).addClass( "danger" );
		    }

		    if ( data[6] == 0 ) {   
		      //jQuery('td', row).removeClass( "danger" );
		    }

	 },	
 
	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		
		var api = this.api();

		if ( jQuery('#config_salida').val() == 0 ) {

			api.column(6).visible(false);	
			api.column(7).visible(false);	
			if ( ( jQuery('#el_perfil').val() == 3 ) || ( jQuery('#el_perfil').val() == 4 ) ) {
				api.column(11).visible(false);	
			}

			//api.column(12).visible(true);	
		} else {
			
			api.column(6).visible(true);	
			api.column(7).visible(true);	
			if ( ( jQuery('#el_perfil').val() == 3 ) || ( jQuery('#el_perfil').val() == 4 ) ) {
				api.column(11).visible(true);	
			}

			//api.column(11).visible(false);	
		}

		var arreglo =arr_apartado_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}

				api.column(12).visible(true);	
				if (aData.length >0){
      				if (aData[0][6]=="-") {
                		api.column(12).visible(false);	
                	} else {
                		api.column(12).visible(true);	
		     			
                	}
                } else {
	            		api.column(12).visible(false);	
                }


	},

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	



jQuery('body').on('click','#incluir_salida', function (e) {

		
	 id_usuario = jQuery("#id_usuario_apartado").val();  //"0cc5510f-c452-11e4-8ada-7071bce181c3"; //
     id_cliente = jQuery("#id_cliente_apartado").val();  //3; //

	jQuery.ajax({
		        url : '/incluir_apartado',
		        data : { 
		        	id_usuario: id_usuario,
		        	id_cliente: id_cliente,
		        	id_almacen:jQuery('#id_almacen_pedido').val(),
		     consecutivo_venta:jQuery('#consecutivo_venta').val(),
		     id_tipo_factura:jQuery('#id_tipo_factura').val(),
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data != true){
						     jQuery('#etiq_tipo_apartado').html('Disponibilidad Salida');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div>');

							 return false;	
						}else{
						     jQuery('#etiq_tipo_apartado').html('Disponibilidad Salida');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div>');


								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	jQuery('#tabla_detalle').dataTable().fnDraw();
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'incluir_salida');
												return false;
									        }
								});	
							 //return false;
						}
		        }
	});						        
});


jQuery('body').on('click','#excluir_salida', function (e) {

		
	 id_usuario = jQuery("#id_usuario_apartado").val();  //"0cc5510f-c452-11e4-8ada-7071bce181c3"; //
     id_cliente = jQuery("#id_cliente_apartado").val();  //3; //

	jQuery.ajax({
		        url : '/excluir_apartado',
		        data : { 
		        	id_usuario: id_usuario,
		        	id_cliente: id_cliente,
		        	id_almacen:jQuery('#id_almacen_pedido').val(),
		     consecutivo_venta:jQuery('#consecutivo_venta').val(),		        	
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data != true){
							 jQuery('#etiq_tipo_apartado').html('Apartado Confirmado');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div>');

							 return false;	
						}else{
							 jQuery('#etiq_tipo_apartado').html('Apartado Confirmado');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div>');
								
								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'excluir_salida');
												return false;
									        }
								});	
						}
		        }
	});						        
});





/////////////////////////////Modulo de salida////////////////////////////////////////////////////


jQuery('body').on('click','#proc_salida_apartado', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	 id_cargador = jQuery('.buscar_cargador').typeahead("val");
	 num_mov = jQuery('#num_mov').val(); 
	 
	 dependencia= jQuery('#etiq_dependencia').val( ); // etiq_cliente
	 id_almacen = jQuery('#id_almacen_pedido').val( );
	 id_operacion_pedido = jQuery('#id_operacion_pedido').val( );

	 var url = '/confirmar_proc_apartado_sino';

	    var arreglo_peso = [];
	    var arreglo = {};

	   jQuery("#tabla_detalle tbody tr td input.peso_real").each(function(e) {
	   		arreglo = {};
	   		arreglo["codigo"] = jQuery(this).attr('codigo') ;  
	   		arreglo['peso_real'] = jQuery(this).val();
	   		arreglo_peso.push( arreglo);
	   });

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	id_cargador: id_cargador,
		        	arreglo_peso:arreglo_peso,
		        	num_mov:num_mov,
		        	dependencia: dependencia,
		        	id_almacen:id_almacen,
		        	 id_operacion_pedido:id_operacion_pedido,
		        	

		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('#messages').append(data.errores);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{

							spinner.stop();
							jQuery('#foo').css('display','none');
							//alert('ok');

								

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_salida');

												valor= jQuery.base64.encode(data.valor);
												var url = "/proc_apartado_pedido_definitivo/"+jQuery.base64.encode(num_mov)+'/'+jQuery.base64.encode(data.id_cargador)+'/'+jQuery.base64.encode(id_operacion_pedido)+'/'+jQuery.base64.encode(id_almacen);
												jQuery('#modalMessage').modal({
													  show:'true',
													remote:url,
												}); 									        	
												
									        }
								});	


						}
		        }

		        
	});						        
});


jQuery('body').on('click','#proc_salida_pedido', function (e) {

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);


	 id_cargador = jQuery('.buscar_cargador').typeahead("val");
	 num_mov = jQuery('#num_mov').val(); 
	 dependencia= jQuery('#etiq_dependencia').val( ); // etiq_cliente
	 id_almacen = jQuery('#id_almacen_pedido').val( );
	 id_operacion_pedido = jQuery('#id_operacion_pedido').val( );
	 
	 on_off = jQuery('#on_off').val( );

	 var url = '/confirmar_proc_pedido_sino';

	    var arreglo_peso = [];
	    var arreglo = {};

	   jQuery("#pedido_detalle tbody tr td input.peso_real").each(function(e) {
	   		arreglo = {};
	   		arreglo["codigo"] = jQuery(this).attr('codigo') ;  
	   		arreglo['peso_real'] = jQuery(this).val();
	   		arreglo_peso.push( arreglo);
	   });

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	id_cargador: id_cargador,
		        	arreglo_peso:arreglo_peso,
		        	num_mov:num_mov,
		        	dependencia: dependencia,
		        	id_almacen:id_almacen,
		        	 id_operacion_pedido:id_operacion_pedido,
		        	
		        	on_off:on_off,


		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('#messages').append(data.errores);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{

							spinner.stop();
							jQuery('#foo').css('display','none');
							//alert('ok');

								

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_salida');

												valor= jQuery.base64.encode(data.valor);
												var url = "/proc_salida_pedido_definitivo/"+jQuery.base64.encode(num_mov)+'/'+jQuery.base64.encode(data.id_cargador)+'/'+jQuery.base64.encode(id_operacion_pedido)+'/'+jQuery.base64.encode(id_almacen);
												jQuery('#modalMessage').modal({
													  show:'true',
													remote:url,
												}); 									        	
												
									        }
								});	


						}
		        }

		        
	});						        
});


jQuery('body').on('click','#proc_salida', function (e) {


	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	id_cliente = jQuery('.buscar_proveedor').typeahead("val");
	id_cargador = jQuery('.buscar_cargador').typeahead("val");
	factura = jQuery("#factura").val();
	//id_destino = jQuery("#id_destino").val(); //??
	id_almacen = jQuery('#id_almacen_salida').val();

	id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
	id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();


	 var url = 'confirmar_salida_sino';

	    var arreglo_peso = [];
	    var arreglo = {};

	   jQuery("#tabla_salida tbody tr td input.peso_real").each(function(e) {  //??
	   		arreglo = {};
	   		arreglo["id"] = jQuery(this).attr('identificador') ;  
	   		arreglo['peso_real'] = jQuery(this).val();
	   		arreglo_peso.push( arreglo);
	   });

	jQuery.ajax({
		        url : url,
		        type : 'POST',
		       	data : { 
		        	id_cliente: id_cliente,
		        	id_cargador: id_cargador,
		        	factura: factura,
		        	arreglo_peso:arreglo_peso,
		        	//id_destino:id_destino,
		        	id_almacen:id_almacen,
		        	id_tipo_pedido:id_tipo_pedido,
		        	id_tipo_factura:id_tipo_factura
		        },
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.error);
								jQuery('#messages').append(data.errores);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);
						}else{
							
							spinner.stop();
							jQuery('#foo').css('display','none');



								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(dato) {	
									        	MY_Socket.sendNewPost(dato.vendedor+' - '+dato.tienda+' - '+dato.compra,'proc_salida');

												valor= jQuery.base64.encode(data.valor);

												var url = "pro_salida/"+valor+'/'+data.id_cliente+'/'+jQuery.base64.encode(id_almacen)+'/'+jQuery.base64.encode(id_tipo_pedido)+'/'+jQuery.base64.encode(id_tipo_factura);
											
												jQuery('#modalMessage').modal({
													  show:'true',
													remote:url,
												}); 									        	
									        }
								});	
							

						}
		        }

		        
	});						        
});


jQuery('#id_tipo_pedido_salida, #id_tipo_factura_salida').on('change', function(e) {
	consecutivo_actual = (( (jQuery("#id_tipo_pedido_salida").val() == 1) && (jQuery("#id_tipo_factura_salida").val()==1) ) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
	consecutivo_actual = ( (jQuery("#id_tipo_pedido_salida").val()==2) ? jQuery("#conse_surtido").val() : consecutivo_actual);

	jQuery("#movimiento").val(consecutivo_actual);
});


//Agregar las estradas a salidas en el modulo de salida "agregar la regilla de arriba a la regilla inferior"
jQuery('table').on('click','.agregar', function (e) {
	jQuery(this).attr('disabled', true);		
	identificador = (jQuery(this).attr('identificador'));
	proveedor = jQuery('.buscar_proveedor').typeahead("val");
	cargador = jQuery('.buscar_cargador').typeahead("val");
	factura = jQuery("#factura").val();
	movimiento = jQuery("#movimiento").val();
	id_destino = jQuery("#id_destino").val();
	id_almacen = jQuery("#id_almacen").val();
	id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
	id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();


	//editar_proveedor
	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

 
	jQuery.ajax({
		        url : 'agregar_prod_salida',
		        data : { 
		        	identificador: identificador,
		        	id_cliente:proveedor,
		        	id_cargador:cargador,
		        	factura: factura,
		        	movimiento: movimiento,
		        	id_destino: id_destino,
		        	id_almacen: id_almacen,
		        	id_tipo_pedido: id_tipo_pedido,
		        	id_tipo_factura: id_tipo_factura,
		        },
		        type : 'POST',
		       // dataType : 'json',
		        success : function(data) {	
						if(data != true){
							//alert('sad');
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);


							//aqui es donde va el mensaje q no se ha copiado
						}else{
							
							spinner.stop();
							jQuery('#foo').css('display','none');
						    jQuery('#messages').css('display','none');

							jQuery("fieldset.disabledme").attr('disabled', true);

							jQuery('#tabla_salida').dataTable().fnDraw();
							jQuery('#tabla_entrada').dataTable().fnDraw();


								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar');
									        	return false;	
									        }
								});	
								//alert('2');	
							//return false;
						}
		        }
	});		
	jQuery(this).attr('disabled', false);				        

});


jQuery("#id_tipo_pedido_salida").on('change', function(e) {
	if (jQuery(this).val()==2) {
		jQuery('.tipo_factura').css('display','none');			
	} else {
		jQuery('.tipo_factura').css('display','block');	
	}
	jQuery('#tabla_entrada').dataTable().fnDraw();
});

jQuery("#id_tipo_factura_salida").on('change', function(e) {

	jQuery('#tabla_entrada').dataTable().fnDraw();
});


/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////SALIDAS////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

 //cuando entre por vez primera, q actualice todas las dependencia
	var hash_url = window.location.pathname;
	if  ( (hash_url=="/salidas") )   {  
		jQuery("#producto_salida").trigger('change');
	}	



//,  #proveedor_salida
    jQuery("#producto_salida, #composicion_salida, #ancho_salida, #color_salida, #proveedor_salida").on('change', function(e) {
		 var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_salida option:selected').text();  		  //elemento** id
 		 var val_comp = jQuery('#composicion_salida').val();  		  //elemento** id
 		 var val_ancho = jQuery('#ancho_salida').val();  		  //elemento** id
 		 var val_color = jQuery('#color_salida').val();  		  //elemento** id
 		 var val_proveedor = jQuery('#proveedor_salida').val();  		  //elemento** id

         var dependencia = jQuery(this).attr("name"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
        cargarDependencia_salida(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre);
		var hash_url = window.location.pathname;

		if  ( (hash_url=="/salidas") )   {  
				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#tabla_entrada').dataTable();
				oTable._fnAjaxUpdate();
    	}	

     });




	function cargarDependencia_salida(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre) {
		
		jQuery.ajax({
		        url : 'cargar_dependencia_salida',
		        data:{
		        	campo:campo,
		        	val_prod_id : jQuery('#producto_salida option:selected').val(),  		  //elemento** id
		        	val_prod:val_prod,
		        	val_comp:val_comp,
		        	val_ancho: val_ancho,
		        	val_color:val_color,		        	
		        	val_proveedor:val_proveedor,
		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {

		        			var $elArray = new Array();
							$elArray['producto_salida']  = 'un producto';
				        	$elArray['composicion_salida']  = 'una composición';
				            $elArray['ancho_salida']  = 'un ancho';
				            $elArray['color_salida']  = 'un color';
				            $elArray['proveedor_salida']  = 'un proveedor';

		        		jQuery.each(data, function (dep, valor) {
		        			
		        			jQuery("#"+dep).html(''); 

		        			jQuery("#"+dep).append('<option value="0" >Seleccione '+$elArray[dep]+' </option>'); //+valor.nombre+

		        				jQuery.each(valor, function (i, value) {
		        					
		        					hexadecimal_color = (value.hexadecimal_color)  ? (value.hexadecimal_color) : 'ffff';

		        					if (dep=="producto_salida") { //caso de producto
		        					 jQuery("#"+dep).append('<option '+ ( (value.nombre==value.activo) ? 'selected' : '') +' value="' + value.nombre + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');     	
		        					} else {
		        						jQuery("#"+dep).append('<option '+ ( (parseInt(value.id)==parseInt(value.activo)) ? 'selected' : '') +' value="' + value.id + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');     	
		        					}
		        					

		        					
		        				});
		        		});


                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}


jQuery('body').on('click','#limpiar_filtro_salida', function (e) {
	jQuery('#producto_salida option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion_salida option:eq(0)').prop('selected', 'selected');
	jQuery('#ancho_salida option:eq(0)').prop('selected', 'selected');
	jQuery('#color_salida option:eq(0)').prop('selected', 'selected');
	jQuery('#proveedor_salida option:eq(0)').prop('selected', 'selected');
	jQuery("#producto_salida").trigger('change');
});	



jQuery('#id_almacen_salida').change(function(e) {
	jQuery('.buscar_proveedor').typeahead('val','');
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#tabla_entrada').dataTable();
	oTable._fnAjaxUpdate();		
});


jQuery('#tabla_entrada').dataTable( {
 	"processing": true, //	//tratamiento con base de datos
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_servidor",
         		"type": "POST",
 				"data": function ( d ) {
         		 	   if (comienzo) {
         		 	   	 //d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   }


		 			    d.id_descripcion = jQuery("#producto_salida").val();
	 				  if (d.id_descripcion !='') {
   				   	    d.id_descripcion = jQuery('#producto_salida option:selected').text();
	 				  }

	 				  d. val_prod_id = jQuery('#producto_salida option:selected').val();


 		 				d.id_composicion = jQuery('#composicion_salida').val();  		  
 		 				   	     d.ancho = jQuery('#ancho_salida').val();  		  
 		 					  d.id_color = jQuery('#color_salida').val();  		  
 		 				  d.id_proveedor = jQuery('#proveedor_salida').val();  
 		 				    d.id_almacen = jQuery('#id_almacen_salida').val();

							d.id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
							d.id_tipo_factura = (d.id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura_salida").val();

							d.id_cliente = jQuery('.buscar_proveedor').typeahead('val');

    			 }

         		

     }, 
	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
	
	
"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	



			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 12 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

		}
	
    },	  


"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {

  								var color;
		                		switch (row[17]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 



		                		if (row[16]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[16];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,3,4,5,6,7,8]
	            },



				{ 
	                "render": function ( data, type, row ) {
                            //prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[14])+'" data-toggle="modal" data-target="#myModaldashboard">';
                            prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[14])+'/'+jQuery.base64.encode(row[1])+'" data-toggle="modal" data-target="#myModaldashboard">';
                               prod+= row[2];
                            prod+='</a>';
                            
                            return prod; 
	                },
	                "targets": [2] //,11
	            },	 


	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[11];
	                },
	                "targets": [9]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-success btn-block agregar_salida '+row[9]+'">';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';

						return texto;	
	                },
	                "targets": 10
	            },
	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[15];
	                },
	                "targets": [11]
	            },	

	            { 
	             "render": function ( data, type, row ) {
	                		return row[18];
	                },
	                "targets": [12]
	            },	


    			{ 
	                 "visible": false,
	                "targets": [13,14,15,16,17,18,19]
	            }	            
	        ],

	         "fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
	    			var api = this.api();
	    			api.column(12).visible(true);	
	     			
		
				if (aData.length >0){
      				if (aData[0][18]=="-") {
                		api.column(12).visible(false);	
                		
                	} else {
                		api.column(12).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(12).visible(false);	
                }
		         
                //mostrar codigo en función del rol
                api.column(0).visible(true);	
				if (aData.length >0){
      				if (aData[0][19]==2) {
                		api.column(0).visible(false);	
                		
                	} else {
                		api.column(0).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(0).visible(false);	
                }		          

	                
			},

});	
 

//Agregar las estradas a salidas
jQuery('table').on('click','.agregar_salida', function (e) {

	//console.log(jQuery('button.activo').attr('data'));
	jQuery(this).attr('disabled', true);

	identificador = (jQuery(this).attr('identificador'));
	movimiento = jQuery("#movimiento").val();
	id_tipo_pedido = jQuery("#id_tipo_pedido_salida").val();
	id_tipo_factura = ((id_tipo_pedido==2) || (id_tipo_pedido==3)) ? 0 : jQuery("#id_tipo_pedido_salida").val();
	id_cliente = jQuery('.buscar_proveedor').typeahead('val');
	id_cargador = jQuery('.buscar_cargador').typeahead("val");
     on_off  = 0; //jQuery('button.activo').attr('data'); //jQuery('.on-off[class!="btn-outline"]').attr('data');    //jQuery('#on-off').prop('checked');

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);


	jQuery.ajax({
		        url : '/agregar_prod_salida',
		        data : { 
		        	identificador: identificador,
		        	movimiento: movimiento,
		        	movimiento_unico : jQuery("#movimiento_unico").val(),
		        	id_tipo_pedido: id_tipo_pedido,
		        	id_tipo_factura: id_tipo_factura,
		        	id_cliente: id_cliente,
		        	id_cargador: id_cargador,
		        	on_off : 0, //((on_off=='editar_tienda') ? 1 : ((on_off=='editar_bodega') ? 2 : 0) ),


		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.mensaje);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);

						}else{

							jQuery('#tabla_salida').dataTable().fnDraw();
							jQuery('#tabla_entrada').dataTable().fnDraw();

								spinner.stop();
								jQuery('#foo').css('display','none');


								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar_salida');
												return false;
									        }
								});								
							 
						}
		        }
	});			

   jQuery(this).attr('disabled', false);				        
});

 
//Quitar las salidas y retornarlas a estradas 
jQuery('table').on('click','.quitar_salida', function (e) {
	jQuery(this).attr('disabled', true);				        

	identificador = (jQuery(this).attr('identificador'));
	jQuery.ajax({
		        url : 'quitar_prod_salida', //
		        data : { 
		        	identificador: identificador
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
							//aqui es donde va el mensaje q no se ha copiado
						}else{
								jQuery('#tabla_salida').dataTable().fnDraw();
								jQuery('#tabla_entrada').dataTable().fnDraw();

								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_salida');
									        	return false;
	
									        }
								});								
							 
						}


		        }
	});			
	jQuery(this).attr('disabled', false);				        			        

});




//"modulo salida"
jQuery('#tabla_salida').dataTable( {
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_servidor_salida",
	         		"type": "POST",
	      }, 

	"language": {  
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    
		    if (settings.json.totales.pieza==0) {
		    	jQuery(".disabledme").prop('disabled', false);	
		    } else {
		    	jQuery(".disabledme").prop('disabled', true);	
		    }		    

		    jQuery('#total_pieza2').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg2').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro2').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
			jQuery(".disabledme").prop('disabled', false);	
		    jQuery('#total_pieza2').html( 'Total de piezas: 0');
			jQuery('#total_kg2').html( 'Total de kgs: 0.00');
			jQuery('#total_metro2').html('Total de mts: 0.00');


		}	

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 11 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza2').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg2').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro2').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza2').html('Total de piezas: 0');
			        jQuery('#metro2').html('Total de mts: 0.00');
					jQuery('#kg2').html('Total de kgs: 0.00');	

		}	
	
    },
	"columnDefs": [
				{ 
		                "render": function ( data, type, row ) {

								var color;
		                		switch (row[16]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 


		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[15];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,3,4,5,6,7,8]
	            },	

				{ 
	                "render": function ( data, type, row ) {
                            prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[13])+'/'+jQuery.base64.encode(row[1])+'" data-toggle="modal" data-target="#myModaldashboard">';
                               prod+= row[2];
                            prod+='</a>';
                            
                            return prod; 
	                },
	                "targets": [2] //,11
	            },	 

	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[10];
	                },
	                "targets": [9]
	            },	            
	            {
	                "render": function ( data, type, row ) {
						
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-danger btn-block quitar_salida">';
							texto+='<span class="letra_quitar">Quitar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 10
	            },

				{
	                "render": function ( data, type, row ) {
						
						//modulo= jQuery("#modulo").val(); 

						texto='<td>'; 
							texto+='<input restriccion="decimal" value="'+row[19]+'" identificador="'+row[9]+'" type="text" class="form-control ttip peso_real" title="Números y puntos decimales."  placeholder="0.00">';							
						texto+='</td>';
						return texto;	

	                },
	                "targets": 11
	            },	            

	            {
	                "render": function ( data, type, row ) {
						
						return row[14];	
	                },
	                "targets": 12
	            },
	            {
	                "render": function ( data, type, row ) {
						
						return row[17];	
	                },
	                "targets": 13
	            },	            	            
				{ 
	                 "visible": false,
	                "targets": [14,15,16,17,18,19]
	            }		            
	        ],
 		"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
	    			var api = this.api();
	    			api.column(13).visible(true);	
	     			
		
				if (aData.length >0){
      				if (aData[0][17]=="-") {
                		api.column(13).visible(false);	
                		
                	} else {
                		api.column(13).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(13).visible(false);	
                }

		  //mostrar codigo en función del rol
               api.column(0).visible(true);  
	        	if (aData.length >0){
	              if (aData[0][18]==2) {
	                    api.column(0).visible(false); 
	                    
	                  } else {
	                    api.column(0).visible(true);  
	              
	              
	                  }
	                } else {
	                    api.column(0).visible(false); 
	                } 
			          

		                
				},
});	


//jQuery('#peso_real[restriccion="decimal"]').bind('keypress paste', function (e) {
//id="peso_real" name="peso_real"
jQuery('body').on('keypress paste','.peso_real[restriccion="decimal"]', function (e) {	
	//console.log('aaa');
    //var nn = jQuery('.peso_real[restriccion="decimal"]');
    var nn = jQuery(this);
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});

jQuery('body').on('keypress paste','.precio[restriccion="decimal"], .cantidad_um[restriccion="decimal"], .ancho[restriccion="decimal"]', function (e) {	
    var nn = jQuery(this);
    var strValue = nn[0].value.toString() + String.fromCharCode(e.which);
    strValue = jQuery.trim(strValue);
    var bool = reg.test(strValue);
    if (bool) {
        return true;
    }
    else { 
        e.preventDefault();
    }
});

/////////////////////////////////////////////////PEDIDO Completado///////////////////////////////////////////////////////////

jQuery('#pedido_completo_detalle').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_completo_detalle",
         		"type": "POST",
         		 "data": function ( d ) {
     				   d.mov_salida_unico = jQuery("#mov_salida_unico").val();  //numero_mov del pedido
     				   d.id_almacen = jQuery('#id_almacen_pedido').val();	
     				   d.id_operacion_salida = jQuery('#id_operacion_salida').val();	

    			 } 

     },   
	"footerCallback": function( tfoot, data, start, end, display ) {
		

	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 15 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 16)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				total_pieza = (end-start);	
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));
		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	
		}	
    },	
	"infoCallback": function( settings, start, end, max, total, pre ) {
		if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	    
	    if (settings.json.datos) {
			
			if (settings.json.datos.tipo_apartado=="Vendedor") {
				jQuery('#label_cliente').text("Vendedor");
				jQuery('#label_vendedor').text("Cliente/Núm. Pedido");

				
				jQuery('#etiq_num_mov').val(  settings.json.datos.cliente+'  Nro.'+settings.json.datos.movi_pedido); //+jQuery('#consecutivo_venta').val());
				jQuery('#etiq_cliente').val(  settings.json.datos.num_mov);	
			} else {//pedidos internos
				jQuery('#label_cliente').text("Vendedor");
				jQuery('#label_vendedor').text("Cliente/Núm. Pedido");

				jQuery('#etiq_num_mov').val(  ((settings.json.datos.cliente_pedido!=null)?settings.json.datos.cliente_pedido:'')+' Nro.'+settings.json.datos.movi_pedido);
				jQuery('#etiq_cliente').val(  settings.json.datos.cliente);
				
			}

			jQuery('#etiq_dependencia').val(  settings.json.datos.dependencia);	
				

		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    jQuery('#etiq_hora').val(  settings.json.datos.mi_hora);

		    jQuery('#etiq_tipo_apartado').html(  settings.json.datos.tipo_apartado+' '+settings.json.datos.tipo_pedido  );
		    jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#'+settings.json.datos.color_apartado+';width:15px;height:15px;"></div>');
			

		    //jQuery('#id_tipo_factura').val(settings.json.datos.id_tipo_factura);

		    if (settings.json.datos.tipo_factura!=null) {
		    	//jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido+' - '+settings.json.datos.tipo_factura+'  ('+settings.json.datos.concepto+')' );		
		    	jQuery('.panel-heading > span').text( '(  '+settings.json.datos.concepto+'  )' );		
		    }else {
		    	jQuery('.panel-heading > span').text( '(  '+settings.json.datos.concepto+'  )' );		
		    	//jQuery('.panel-heading > span').text(settings.json.datos.tipo_pedido+'  ('+settings.json.datos.concepto+')');	
		    }


		}	
		
	    return pre
	}, 

	"rowCallback": function( row, data ) {
		    //aqui lo esta comparando con id_factura_original=13
		    //( data[10] != 0) -->para caso tipo_pedido=surtido
		    if (( data[14] != data[11]) && ( data[14] != 0)  ) {
		      jQuery('td', row).addClass( "danger" );
		    }
	 },		   
   "columnDefs": [
   				{ 
		                "render": function ( data, type, row ) {
 					  var color;
                        switch (row[18]){
                          case "12": //normal rojo
                            color = 'red';
                             break; 
                          case "13": //devolucion verde
                            color = 'green';
                             break;
                          case "14": //defecto azul
                            color = 'blue';
                             break;
                          case "15": //ajuste naranja
                            color = 'orange';
                             break;
                          default: 
                            color = 'red';            
                        } 		                	
		                		if (row[17]!='') {
		                			return row[0]+'<br/><b style="color:'+color+';">Cód: </b>'+row[17];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                //"targets": [0,1,2,3,4,5,6,7,8,12],
	                "targets": [1,2,3,4,5,6,7,8,9,13],
	            },


    			{ 
	                 "visible": false,
	                "targets": [10,11,12,14,15,16,17,18], 
	            }		            

	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		
		var arreglo =arr_completo_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}

	    var api = this.api();	
		api.column(5).visible(true);
		api.column(6).visible(true);
			
				if (aData.length >0){
      				if (aData[0][5]=="-") {
                		api.column(5).visible(false);	
                		api.column(6).visible(false);	

                	} else {
                		api.column(5).visible(true);	
                		api.column(6).visible(true);	
		     			
                	}
                } else {
	            		api.column(5).visible(false);	
	            		api.column(6).visible(false);	
                }

	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


jQuery('#tabla_pedido_completado').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_pedido_completo",
         		"type": "POST",
    			"data": function ( d ) {
						    d.id_almacen = jQuery('#id_almacen_pedido').val();	
	         			    d.id_tipo_pedido = jQuery('#id_tipo_pedido').val();	
		   				    d.id_tipo_factura = jQuery('#id_tipo_factura').val();

		   				   var fecha = (jQuery('.fecha_reporte_pedido').val()).split(' / ');

							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];  
    			 }          		

     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	},    

   "columnDefs": [
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,3,4]  //0=vendedor,  3= fecha_apartado, 4= $row->tipo_apartado.' '.$row->tipo_pedido
	            },
				{ 
	                "render": function ( data, type, row ) {
							return row[8];	//movi_pedido	
	                },
	                "targets": [1]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[2];		//cliente_pedido
	                },
	                "targets": [2]
	            },

				{ 
	                "render": function ( data, type, row ) {
						return row[15];	 //movi_salida
	                },
	                "targets": [5]
	            },


				{ 
	                "render": function ( data, type, row ) {
						return row[9];	//tip_pedido
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[10];	 //tipo_factura
	                },
	                "targets": [7]
	            }, 		            

    			{ 
	                "render": function ( data, type, row ) {
    					 texto='<td><a href="pedido_completado_detalle/'+jQuery.base64.encode(row[5])+'/'+jQuery.base64.encode(row[16])+'/'+jQuery.base64.encode(row[17])+'" '; 
						 	texto+=' class="btn btn-success btn-block">';
						 	texto+=row[13]+' Detalles';
						 texto+='</a></td>';


						return texto;	
	                },
	                "targets": [8]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[7];	//almacen
	                },
	                "targets": [9]
	            },	

				{ 
	                "render": function ( data, type, row ) {
						return row[14];	
	                },
	                "targets": [10]
	            }, 


				{ 
		                 "visible": false,
		                "targets": [11,12,13,14,15,16,17]
		            }
		            

	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var api = this.api();
		api.column(10).visible(true);	

		var arreglo =pedido_completo;

		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}

		
				if (aData.length >0){
      				if (aData[0][14]=="-") {
                		api.column(10).visible(false);	
                	} else {
                		api.column(10).visible(true);	
		     			
                	}
                } else {
	            		api.column(10).visible(false);	
                }

	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	




/////////////////////////////////////////////////PEDIDO///////////////////////////////////////////////////////////

jQuery('body').on('click','#incluir_pedido', function (e) {
	 
	 
     num_mov = jQuery("#num_mov").val();  //3; //
 	 jQuery.ajax({
		        url : '/incluir_pedido',
		        data : { 
		        	num_mov: num_mov,
		        id_almacen:jQuery('#id_almacen_pedido').val(),	
		        id_tipo_pedido:jQuery('#id_tipo_pedido').val(),
		        id_tipo_factura:jQuery('#id_tipo_factura').val()
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data != true){
						     jQuery('#etiq_tipo_apartado').html('Disponibilidad Salida');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div>');
							 
						}else{
						     jQuery('#etiq_tipo_apartado').html('Disponibilidad Salida');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#14b80f;width:15px;height:15px;"></div>');

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	jQuery('#pedido_detalle').dataTable().fnDraw();
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'incluir_pedido');
												return false;		
									        }
								});	
						}
		        }
	});						        
});


jQuery('body').on('click','#excluir_pedido', function (e) {

num_mov = jQuery("#num_mov").val();  //3; //

	jQuery.ajax({
		        url : '/excluir_pedido',
		        data : { 
		        	num_mov: num_mov,
		        	id_almacen:jQuery('#id_almacen_pedido').val(),
		        	
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data != true){
							 jQuery('#etiq_tipo_apartado').html('Apartado Confirmado');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div>');

							 return false;	
						}else{
							 jQuery('#etiq_tipo_apartado').html('Apartado Confirmado');
						     jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#f1a914;width:15px;height:15px;"></div>');

								jQuery.ajax({
									        url : '/conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'excluir_pedido');
												return false;		
									        }
								});	
						}
		        }
	});						        
});





jQuery('#pedido_detalle').dataTable( {
	"pageLength": 500,  //que soporte 500 registro
	"lengthChange": false,  //y que deshabilite el cambio de cantidad de registro
	scroller:       true,
    scrollY:        250,
    scrollCollapse: false,

	"pagingType": "full_numbers",

	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "/procesando_pedido_detalle",
         		"type": "POST",
         		 "data": function ( d ) {
     				   d.num_mov = jQuery("#num_mov").val();  //numero_mov del pedido
     				   d.id_almacen = jQuery('#id_almacen_pedido').val();	
     				   d.id_operacion_pedido = jQuery('#id_operacion_pedido').val();	
     				   
    			 } 

     },   

	"footerCallback": function( tfoot, data, start, end, display ) {
		

	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 17)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				total_pieza = (end-start);	
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));
		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	
		}	
    },	
	"infoCallback": function( settings, start, end, max, total, pre ) {
		
	    
 		if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	

	    if (settings.json.datos) {
	    	
	    	jQuery('#num_pedido').val(  settings.json.datos.num_mov);
			jQuery('#etiq_num_mov').val(  ((settings.json.datos.cliente_pedido!=null)?settings.json.datos.cliente_pedido:'')+' Nro.'+settings.json.datos.mov); //num_mov
		    jQuery('#etiq_cliente').val(  settings.json.datos.cliente);
			jQuery('#etiq_dependencia').val(  settings.json.datos.dependencia);
		    jQuery('#etiq_fecha').val(  settings.json.datos.mi_fecha);
		    jQuery('#etiq_hora').val(  settings.json.datos.mi_hora);

		    jQuery('#etiq_tipo_apartado').html(  settings.json.datos.tipo_apartado);
		    jQuery('#etiq_color_apartado').html('<div style="margin-right: 15px;float:left;background-color:#'+settings.json.datos.color_apartado+';width:15px;height:15px;"></div>');
			
		    jQuery('#on_off').val(settings.json.datos.on_off);


		    if (settings.json.datos.tipo_factura!=null) {
		    	jQuery('.panel-heading > span').text(settings.json.datos.tipo_pedido+' - '+settings.json.datos.tipo_factura+'  ('+settings.json.datos.concepto+')' );		
		    }else {
		    	jQuery('.panel-heading > span').text( settings.json.datos.tipo_pedido+'  ('+settings.json.datos.concepto+')');	
		    }
			
		}	else {
			window.location.href = '/pedidos';	
		}

		
		

		
	    return pre
	},    

   "columnDefs": [
				{ 
		                "render": function ( data, type, row ) {
	  						var color;
	                        switch (row[19]){
	                          case "12": //normal rojo
	                            color = 'red';
	                             break; 
	                          case "13": //devolucion verde
	                            color = 'green';
	                             break;
	                          case "14": //defecto azul
	                            color = 'blue';
	                             break;
	                          case "15": //ajuste naranja
	                            color = 'orange';
	                             break;
	                          default: 
	                            color = 'red';            
	                        } 


		                		if (row[18]!='') {
		                			return row[0]+'<br/><b style="color:'+color+';">Cód: </b>'+row[18];	
		                		} else {
		                			return row[0];
		                		}
		                		
		                },
		                "targets": [0]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [1,2,3,4,5,6,7,8,9,10],
	            },

	            {
	                "render": function ( data, type, row ) {
						if ( jQuery('#config_salida').val() == 0 ) {
							texto='<td>'; 
								texto+='<input restriccion="decimal" value="'+row[15]+'" codigo="'+row[0]+'" type="text" class="form-control ttip peso_real" title="Números y puntos decimales."  placeholder="0.00">';							
							texto+='</td>';
						} else {
							texto=row[14];
						}	
						return texto;	

	                },
	                "targets": 11
	            },
	            { 
	                "render": function ( data, type, row ) {
						return row[20];	
	                },
	                "targets": [12],
	            },
    			{ 
	                 "visible": false,
	                "targets": [13,14,15,16,17,18,19,20,21,22], 
	            }	            

	],	

	"rowCallback": function( row, data ) {
					    
		     if (( data[11] != data[12]) && ( data[12] != 0)  ) {
		      jQuery('td', row).addClass( "danger" );
		    }

		    if ( data[6] == 0 ) {   
		      //jQuery('td', row).removeClass( "danger" );
		    }

	 },	
 
	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {

		var api = this.api();

		
		if ( jQuery('#config_salida').val() == 0 ) {
			api.column(6).visible(false);	
			api.column(7).visible(false);	
		} else {
			api.column(6).visible(true);	
			api.column(7).visible(true);	
		}

		var arreglo =arr_pedido_detalle;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum //
					nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 	
	    	}
	    		
				api.column(12).visible(true);	
				if (aData.length >0){
      				if (aData[0][20]=="-") {
                		api.column(12).visible(false);	
                	} else {
                		api.column(12).visible(true);	
		     			
                	}
                } else {
	            		api.column(12).visible(false);	
                }

             api.column(11).visible(true);			
			if (!(aData[0][22])) {
				api.column(11).visible(false);	
				//console.log( (aData[0][22])  );
			} else {
				api.column(11).visible(true);		
			}



	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


jQuery('body').on('click','.prorrogar_tienda', function (e) {
	
	id_cliente_apartado = (jQuery(this).attr('id_cliente_apartado'));

	jQuery.ajax({
		        url : 'marcando_prorroga_tienda',
		        data : { 
		        	id_cliente_apartado: id_cliente_apartado,
		        	id_almacen:jQuery('#id_almacen_pedido').val(),
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
		        	   jQuery('#tabla_pedido').dataTable().fnDraw();
		        }
	});						        
});


jQuery('#tabla_pedido').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_pedido_pendiente",
         		"type": "POST",
				"data": function ( d ) {
						    d.id_almacen = jQuery('#id_almacen_pedido').val();	

	         			   d.id_tipo_pedido = jQuery('#id_tipo_pedido').val();	
		   				   d.id_tipo_factura = jQuery('#id_tipo_factura').val();
		   				   var fecha = (jQuery('.fecha_reporte_pedido').val()).split(' / ');

							d.fecha_inicial = fecha[0];
							d.fecha_final = fecha[1];
    			 }          		

     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    return pre
	},    
	 
	 "rowCallback": function( row, data ) {
	    // Bold the grade for all 'A' grade browsers
	    if ( data[7] == 1 ) {
	      jQuery('td', row).addClass( "danger" );
	    }

	    if ( data[7] == 0 ) {
	      jQuery('td', row).removeClass( "danger" );
	    }
	  },	

   "columnDefs": [
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [0,2,3,4,5]  //0=vendedor, 2= cliente, 3=fecha, 	4=, 5=
	            },


    			{ 
	                "render": function ( data, type, row ) {
						return row[17];	
	                },
	                "targets": [1] //num pedido
	            },	            

    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	 //tipo_pedido
	                },
	                "targets": [6]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return row[10];	 //tipo_factura
	                },
	                "targets": [7]
	            }, 	            
    			{ 
	                "render": function ( data, type, row ) {
    					 texto='<td><a href="pedido_detalle/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[16])+'" '; 
						 	texto+=' class="btn btn-success btn-block">';
						 	texto+=row[14]+' Detalles';
						 texto+='</a></td>';


						return texto;	
	                },
	                "targets": [8]
	            },
    			{ 
	                "render": function ( data, type, row ) {
	                	if ( (row[11]!=6) ) { //|| ( (row[11]==6) && (jQuery("#mi_perfil").val() !='4')  )  
							texto='<td><a href="eliminar_pedido_detalle/'+jQuery.base64.encode(row[6])+'/'+jQuery.base64.encode(jQuery('#id_almacen_pedido option:selected').val())+'/'+jQuery.base64.encode(row[16])+'" '; 
								texto+='class="btn btn-danger  btn-block" data-toggle="modal" data-target="#modalMessage">';
								texto+='<span class="glyphicon glyphicon-remove"></span>';
							texto+='</a></td>';
						} else {
	   							texto='	<fieldset disabled> <td>';								
									texto+=' <a href="#"'; 
									texto+=' class="btn btn-danger btn-sm btn-block">';
									texto+=' <span class="glyphicon glyphicon-remove"></span>';
									texto+=' </a>';
								texto+=' </td></fieldset>';	

						}	
							
						return texto;	

	                },
	                "targets": [9]
	            },	
    			
    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	 //almacen
	                },
	                "targets": [10]
	            },
	            { 
			        "render": function ( data, type, row ) {
						return row[15];	 //importe
	                },
	                "targets": [11]
	            },


	            { 
		                 "visible": false,
		                "targets": [12,13,14,15,16,17]
		         }



	],	

	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var api = this.api();

		if ( jQuery('#config_salida').val() == 0 ) {
			api.column(4).visible(false);	
			api.column(5).visible(false);	
		} else {
			api.column(4).visible(true);	
			api.column(5).visible(true);	
		}

			api.column(11).visible(true);	

		var arreglo =pedido_pendiente;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}


				
	    		
		
				if (aData.length >0){
      				if (aData[0][15]=="-") {
                		api.column(11).visible(false);	
                	} else {
                		api.column(11).visible(true);	
		     			
                	}
                } else {
	            		api.column(11).visible(false);	
                }



	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


jQuery('body').on('click','#conf_pedido_PRUEBAbORRAR', function (e) {
	
	num_mov=jQuery('#movimiento').val();
	id_tipo_pedido = jQuery("#id_tipo_pedido").val();
	id_tipo_factura = (id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura").val();

	on_off  = jQuery('button.activo').attr('data'); 
	id_operacion_pedido  = ((on_off=='editar_tienda') ? 97 : ((on_off=='editar_bodega') ? 98 : 4) );
		        	 
	alert(id_operacion_pedido);

});

jQuery('body').on('click','#conf_pedido', function (e) {

	num_mov=jQuery('#movimiento').val();
	id_tipo_pedido = jQuery("#id_tipo_pedido").val();
	id_tipo_factura = ( (id_tipo_pedido==2) || (id_tipo_pedido==3) ) ? 0:jQuery("#id_tipo_factura").val();
	
	  factor_salida =  (id_tipo_pedido==2)  ? "S" : ((id_tipo_pedido==3) ? "G" : jQuery("#id_tipo_factura option:selected").text() ) ;	

	on_off  = jQuery('button.activo').attr('data'); 
	proveedor=jQuery('.buscar_proveedor').typeahead("val");

	jQuery('#foo').css('display','block');
	var spinner = new Spinner(opts).spin(target);

	jQuery.ajax({
		        url : 'pedido_definitivo',
		        data : { 
		        	num_mov: num_mov,
		        	id_tipo_pedido:id_tipo_pedido,
		        	id_tipo_factura:id_tipo_factura,
		        	factor_salida: factor_salida,
		        	cliente: proveedor, 

		      		id_operacion_pedido  : ((on_off=='editar_tienda') ? 97 : ((on_off=='editar_bodega') ? 98 : 4) ),
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(datos) {	
		        
						if(datos.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(datos.mensaje);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);

						}else{

							abrir('POST', 'imprimir_reportes_pedido', {
						    			datos: JSON.stringify(datos),
						    }, '_blank' );							
						    
							spinner.stop();
							jQuery('#foo').css('display','none');

								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'conf_pedido');
									        	window.location.href = '/';
									        }
								});		


						}
		        }
	});						        
});






	//,  #proveedor_pedido
    jQuery("#producto_pedido, #composicion_pedido, #ancho_pedido, #color_pedido, #proveedor_pedido").on('change', function(e) {
    	
    	
		 var campo = jQuery(this).attr("name");   
 		 var val_prod = jQuery('#producto_pedido option:selected').text();  		  //elemento** id
 		 var val_comp = jQuery('#composicion_pedido').val();  		  //elemento** id
 		 var val_ancho = jQuery('#ancho_pedido').val();  		  //elemento** id
 		 var val_color = jQuery('#color_pedido').val();  		  //elemento** id
 		 var val_proveedor = jQuery('#proveedor_pedido').val();  		  //elemento** id

         var dependencia = jQuery(this).attr("name"); //color composicion
         var nombre = jQuery(this).attr("nombre");           //color composicion
        
        //alert(dependencia);
    	//if (dependencia !="") {	    
	        //limpiar la dependencia
	        //jQuery("#"+dependencia).html(''); 
	        //cargar la dependencia
	        cargarDependencia_pedido(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre);
        //}


		var hash_url = window.location.pathname;


		if  ( (hash_url=="/generar_pedidos") )   {  

				comienzo=true; //para indicar que start comience en 0;
				var oTable =jQuery('#pedido_entrada').dataTable();
				oTable._fnAjaxUpdate();
    	}	



     });



	

	function cargarDependencia_pedido(campo,val_prod,val_comp, val_ancho, val_color,val_proveedor,dependencia,nombre) {
		
		var url = 'cargar_dependencia_pedido';	
		jQuery.ajax({
		        url : 'cargar_dependencia_pedido',
		        data:{
		        	campo:campo,
		        	val_prod_id : jQuery('#producto_pedido option:selected').val(),  		  //elemento** id
		        	val_prod:val_prod,
		        	val_comp:val_comp,
		        	val_ancho: val_ancho,
		        	val_color:val_color,		        	
		        	val_proveedor:val_proveedor,

		        	dependencia:dependencia
		        },


		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
		        		
		        		

		        			var $elArray = new Array();
							
							$elArray['producto_pedido']  = 'un producto';
				        	$elArray['composicion_pedido']  = 'una composición';
				            $elArray['ancho_pedido']  = 'un ancho';
				            $elArray['color_pedido']  = 'un color';
				            $elArray['proveedor_pedido']  = 'un proveedor';

		        		jQuery.each(data, function (dep, valor) {
		        			
		        			jQuery("#"+dep).html(''); 

		        			jQuery("#"+dep).append('<option value="0" >Seleccione '+$elArray[dep]+' </option>'); //+valor.nombre+

		        				jQuery.each(valor, function (i, value) {
		        					
		        					hexadecimal_color = (value.hexadecimal_color)  ? (value.hexadecimal_color) : 'ffff';

		        					if (dep=="producto_pedido") { //caso de producto
		        					 jQuery("#"+dep).append('<option '+ ( (value.nombre==value.activo) ? 'selected' : '') +' value="' + value.nombre + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');     	
		        					} else {
		        						jQuery("#"+dep).append('<option '+ ( (parseInt(value.id)==parseInt(value.activo)) ? 'selected' : '') +' value="' + value.id + '" style="background-color:#'+hexadecimal_color+' !important;" >' + value.nombre + '</option>');     	
		        					}
		        					

		        					
		        				});
		        		});

	                 /*
	                 jQuery("#"+dependencia).append('<option value="0" >Seleccione '+nombre+'</option>');
                    
					if (data != "[]") {
						
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery("#"+dependencia).append('<option value="' + valor.identificador + '" style="background-color:#'+valor.hexadecimal_color+' !important;" >' + valor.nombre + '</option>');     
                            }
                        });

	                } 	*/
					
				
					//jQuery("#"+dependencia).trigger('change');


					/*
					if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}*/


                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}


jQuery('body').on('click','#limpiar_filtro', function (e) {
	// alert('aa');

	//jQuery('#producto_pedido option:selected').att('selectedIndex', 0); 
	jQuery('#producto_pedido option:eq(0)').prop('selected', 'selected');
	jQuery('#composicion_pedido option:eq(0)').prop('selected', 'selected');
	jQuery('#ancho_pedido option:eq(0)').prop('selected', 'selected');
	jQuery('#color_pedido option:eq(0)').prop('selected', 'selected');
	jQuery('#proveedor_pedido option:eq(0)').prop('selected', 'selected');


	jQuery("#producto_pedido").trigger('change');

});	


jQuery('#id_almacen_generar_pedido').change(function(e) {
	//alert('aa');
	jQuery('.buscar_proveedor').typeahead('val','');
	comienzo=true; //para indicar que start comience en 0;
	var oTable =jQuery('#pedido_entrada').dataTable();
	oTable._fnAjaxUpdate();		
		
	
});

jQuery("#id_factura_filtro").on('change', function(e) {
	jQuery('#pedido_entrada').dataTable().fnDraw();
});

jQuery('#pedido_entrada').dataTable( {
	
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_pedido_entrada",
         		"type": "POST",
         		 "data": function ( d ) {
         		 	   if (comienzo) {
         		 	   	 //d.start=0;	 //comienza en cero siempre q cambia de botones
         		 	   	 d.draw =0;
         		 	   }


		 			    d.id_descripcion = jQuery("#producto_pedido").val();
	 				  if (d.id_descripcion !='') {
   				   	    d.id_descripcion = jQuery('#producto_pedido option:selected').text();
	 				  }

	 				  d. val_prod_id = jQuery('#producto_pedido option:selected').val();


 		 				d.id_composicion = jQuery('#composicion_pedido').val();  		  
 		 				   	     d.ancho = jQuery('#ancho_pedido').val();  		  
 		 					  d.id_color = jQuery('#color_pedido').val();  		  
 		 				  d.id_proveedor = jQuery('#proveedor_pedido').val();  
 		 				    d.id_almacen = (jQuery("#id_tipo_pedido").val()==3) ?  jQuery('#id_almacen_generar_pedido').val() : 0;  

							d.id_tipo_pedido = jQuery("#id_tipo_pedido").val();
							d.id_tipo_factura = (d.id_tipo_pedido==2) ? 0:jQuery("#id_tipo_factura").val();

							d.id_factura_filtro = jQuery("#id_factura_filtro").val();
							d.id_cliente = jQuery('.buscar_proveedor').typeahead('val');

    			 }
     }, 
	"language": {  
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},

"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	



			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 12 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

		}
		/*
		if (( jQuery('#config_almacen').val() == 0 ) ) {
			api.column(11).visible(false);		
		}	else {
			api.column(11).visible(true);		
		}*/	
    },
	"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {

  								var color;
		                		switch (row[17]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 



		                		if (row[16]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[16];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	
	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,3,4,5,6,7,8]
	            },



				{ 
	                "render": function ( data, type, row ) {
                            //prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[14])+'" data-toggle="modal" data-target="#myModaldashboard">';
                            prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[14])+'/'+jQuery.base64.encode(row[1])+'" data-toggle="modal" data-target="#myModaldashboard">';
                               prod+= row[2];
                            prod+='</a>';
                            
                            return prod; 
	                },
	                "targets": [2] //,11
	            },	 


	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[11];
	                },
	                "targets": [9]
	            },

	            {
	                "render": function ( data, type, row ) {
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-success btn-block agregar_pedido '+row[9]+'">';
							texto+='<span  class="">Agregar</span>';
						texto+='</button></td>';

						return texto;	
	                },
	                "targets": 10
	            },
	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[15];
	                },
	                "targets": [11]
	            },	

	            { 
	             "render": function ( data, type, row ) {
	                		return row[18];
	                },
	                "targets": [12]
	            },	


    			{ 
	                 "visible": false,
	                "targets": [13,14,15,16,17,18,19]
	            }	            
	        ],

	         "fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
	    			var api = this.api();
	    			api.column(12).visible(true);	
	     			
		
				if (aData.length >0){
      				if (aData[0][18]=="-") {
                		api.column(12).visible(false);	
                		
                	} else {
                		api.column(12).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(12).visible(false);	
                }
		         
                //mostrar codigo en función del rol
                api.column(0).visible(true);	
				if (aData.length >0){
      				if (aData[0][19]==2) {
                		api.column(0).visible(false);	
                		
                	} else {
                		api.column(0).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(0).visible(false);	
                }		          

	                
			},
});	



  //cuando entre por vez primera, q actualice todas las dependencia
	var hash_url = window.location.pathname;
	if  ( (hash_url=="/generar_pedidos") )   {  
		//jQuery("#producto_pedido, #composicion_pedido, #ancho_pedido, #color_pedido, #proveedor_pedido").trigger('change');
		jQuery("#producto_pedido").trigger('change');
	}	



jQuery('#pedido_salida').dataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_pedido_salida",
         		"type": "POST",
     }, 

	"language": {  
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    
		    if (settings.json.totales.pieza==0) {
		    	jQuery(".disabledme").prop('disabled', false);	
		    } else {
		    	jQuery(".disabledme").prop('disabled', true);	
		    }		    

		    jQuery('#total_pieza2').html( 'Total de piezas:'+ settings.json.totales.pieza);
			jQuery('#total_kg2').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro2').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
			jQuery(".disabledme").prop('disabled', false);	
		    jQuery('#total_pieza2').html( 'Total de piezas: 0');
			jQuery('#total_kg2').html( 'Total de kgs: 0.00');
			jQuery('#total_metro2').html('Total de mts: 0.00');


		}	

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 11 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_pieza = (end-start);	

			        jQuery('#pieza2').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#kg2').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro2').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

		} else 	{
			        jQuery('#pieza2').html('Total de piezas: 0');
			        jQuery('#metro2').html('Total de mts: 0.00');
					jQuery('#kg2').html('Total de kgs: 0.00');	

		}	
	/*	if (( jQuery('#config_almacen').val() == 0 )  ) {
			api.column(11).visible(false);		
		}	else {
			api.column(11).visible(true);		
		}*/
    },
	"columnDefs": [
				{ 
		                "render": function ( data, type, row ) {

								var color;
		                		switch (row[16]){
		                			case "12": //normal rojo
		                				color = 'red';
		                			   break;	
		                			case "13": //devolucion verde
		                				color = 'green';
		                			   break;
		                			case "14": //defecto azul
		                				color = 'blue';
		                			   break;
		                			case "15": //ajuste naranja
		                				color = 'orange';
		                			   break;
		                			default: 
		                				color = 'red';            
		                		} 


		                		if (row[15]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[15];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	

	    		{ 
	                "render": function ( data, type, row ) {
	                		return data;
	                },
	                "targets": [0,3,4,5,6,7,8]
	            },	

				{ 
	                "render": function ( data, type, row ) {
                            prod= '<a href="detalles_imagen/'+jQuery.base64.encode(row[13])+'/'+jQuery.base64.encode(row[1])+'" data-toggle="modal" data-target="#myModaldashboard">';
                               prod+= row[2];
                            prod+='</a>';
                            
                            return prod; 
	                },
	                "targets": [2] //,11
	            },	 

	    		{ 
	                "render": function ( data, type, row ) {
	                		return row[10];
	                },
	                "targets": [9]
	            },	            
	            {
	                "render": function ( data, type, row ) {
						
						texto='<td><button'; 
							texto+='type="button" identificador="'+row[9]+'" class="btn btn-danger btn-block quitar_pedido">';
							texto+='<span class="letra_quitar">Quitar</span>';
						texto+='</button></td>';
						return texto;	
	                },
	                "targets": 10
	            },
	            {
	                "render": function ( data, type, row ) {
						
						return row[14];	
	                },
	                "targets": 11
	            },
	            {
	                "render": function ( data, type, row ) {
						
						return row[17];	
	                },
	                "targets": 12
	            },	            	            
				{ 
	                 "visible": false,
	                "targets": [13,14,15,16,17,18]
	            }		            
	        ],
 		"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
	    			var api = this.api();
	    			api.column(12).visible(true);	
	     			
		
				if (aData.length >0){
      				if (aData[0][17]=="-") {
                		api.column(12).visible(false);	
                		
                	} else {
                		api.column(12).visible(true);	
		    			
		     			
                	}
                } else {
	                	api.column(12).visible(false);	
                }

		  //mostrar codigo en función del rol
               api.column(0).visible(true);  
	        	if (aData.length >0){
	              if (aData[0][18]==2) {
	                    api.column(0).visible(false); 
	                    
	                  } else {
	                    api.column(0).visible(true);  
	              
	              
	                  }
	                } else {
	                    api.column(0).visible(false); 
	                } 
			          

		                
				},
});	


jQuery('body').on('click','.on-off', function (e) {

	 jQuery(this).siblings().removeClass('activo');
	 jQuery(this).siblings().addClass('btn-outline');
	 jQuery(this).removeClass('btn-outline');
	 jQuery(this).addClass('activo');
	 //alert('aa');


	if (jQuery(this).attr('data') == "editar_bodega") {  //Bodega
		 	jQuery('#id_tipo_pedido option[value="3"]').prop('selected', true);
		 	jQuery("#id_tipo_pedido").trigger('change');

			 	
	} else {
		if ( jQuery('#id_tipo_pedido option:selected').val() ==3 ) {  //tienda
			jQuery('#id_tipo_pedido option[value="1"]').prop('selected', true);
		 	jQuery("#id_tipo_pedido").trigger('change');

		}
	}

	//cambiar el tipo de proveedor, bodega o tienda
	jQuery('.buscar_proveedor').prop('name', jQuery(this).attr('data') );
	jQuery('.buscar_proveedor').typeahead("val",'');  //borrar la casilla

	
});	



jQuery('#id_tipo_pedido[pantalla="generar_pedidos"]').on('change', function(e) {


	if  (jQuery(this).val()==3) { //bodega
		 //aqui
		 jQuery('.tipo_factura').css('display','none');			

		 if (!(jQuery('button[data="editar_bodega"]').hasClass('activo') )) {
			 jQuery('button[data="editar_bodega"]').siblings().removeClass('activo');
		 	 jQuery('button[data="editar_bodega"]').siblings().addClass('btn-outline');
			 jQuery('button[data="editar_bodega"]').removeClass('btn-outline');
			 jQuery('button[data="editar_bodega"]').addClass('activo');
		}	 
	} else if  (jQuery(this).val()==2)  { //surtido
		
		jQuery('.tipo_factura').css('display','none');			
		if (jQuery('button[data="editar_bodega"]').hasClass('activo') ) {
			 jQuery('button[data="editar_proveedor"]').siblings().removeClass('activo');
		 	 jQuery('button[data="editar_proveedor"]').siblings().addClass('btn-outline');
			 jQuery('button[data="editar_proveedor"]').removeClass('btn-outline');
			 jQuery('button[data="editar_proveedor"]').addClass('activo');
		}	 
	} else {  //venta
		
		jQuery('.tipo_factura').css('display','block');	
		if (jQuery('button[data="editar_bodega"]').hasClass('activo') ) {
			 jQuery('button[data="editar_proveedor"]').siblings().removeClass('activo');
		 	 jQuery('button[data="editar_proveedor"]').siblings().addClass('btn-outline');
			 jQuery('button[data="editar_proveedor"]').removeClass('btn-outline');
			 jQuery('button[data="editar_proveedor"]').addClass('activo');
		}
	}


	//alert(jQuery('button.on-off.activo').attr('data'));
	jQuery('.buscar_proveedor').prop('name', jQuery('button.on-off.activo').attr('data') );
	jQuery('.buscar_proveedor').typeahead("val",'');  //borrar la casilla

	jQuery('#pedido_entrada').dataTable().fnDraw();
});

jQuery("#id_tipo_factura").on('change', function(e) {
	jQuery('#pedido_entrada').dataTable().fnDraw();
});



jQuery('#id_tipo_pedido[pantalla="generar_pedidos"], #id_tipo_factura[pantalla="generar_pedidos"]').on('change', function(e) {
	
	consecutivo_actual = (( (jQuery("#id_tipo_pedido").val() == 1) && (jQuery("#id_tipo_factura").val()==1) ) ? jQuery("#conse_factura").val() : jQuery("#conse_remision").val() );
	consecutivo_actual = ( (jQuery("#id_tipo_pedido").val()==2) ? jQuery("#conse_surtido").val() : consecutivo_actual);
	consecutivo_actual = ( (jQuery("#id_tipo_pedido").val()==3) ? jQuery("#conse_bodega").val() : consecutivo_actual);
	//activar almacen 
	if (jQuery("#id_tipo_pedido").val()==3) {
		jQuery('.id_almacen_generar_pedido').css('display','block');

	} else {
		jQuery('.id_almacen_generar_pedido').css('display','none');
	}
	jQuery("#movimiento").val(consecutivo_actual);




/*
editar_proveedor
editar_tienda


jQuery('.buscar_proveedor').prop('name', 'editar_bodega' );
jQuery('.buscar_proveedor').typeahead("val",'');  //borrar la casilla

*/



});


//Agregar las estradas a salidas
jQuery('table').on('click','.agregar_pedido', function (e) {
	//console.log(jQuery('button.activo').attr('data'));
	jQuery(this).attr('disabled', true);
	identificador = (jQuery(this).attr('identificador'));
	movimiento = jQuery("#movimiento").val();
	id_tipo_pedido = jQuery("#id_tipo_pedido").val();
	id_tipo_factura = ((id_tipo_pedido==2) || (id_tipo_pedido==3)) ? 0 : jQuery("#id_tipo_factura").val();
	var id_cliente = jQuery('.buscar_proveedor').typeahead('val');
	       on_off  = jQuery('button.activo').attr('data'); //jQuery('.on-off[class!="btn-outline"]').attr('data');    //jQuery('#on-off').prop('checked');
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);

	jQuery.ajax({
		        url : 'agregar_prod_pedido',
		        data : { 
		        	identificador: identificador,
		        	movimiento: movimiento,
		        	movimiento_unico : jQuery("#movimiento_unico").val(),
		        	id_tipo_pedido: id_tipo_pedido,
		        	id_tipo_factura: id_tipo_factura,
		        	id_cliente: id_cliente,
		        	on_off : ((on_off=='editar_tienda') ? 1 : ((on_off=='editar_bodega') ? 2 : 0) ),


		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
								spinner.stop();
								jQuery('#foo').css('display','none');
								jQuery('#messages').css('display','block');
								jQuery('#messages').addClass('alert-danger');
								jQuery('#messages').html(data.mensaje);
								jQuery('html,body').animate({
									'scrollTop': jQuery('#messages').offset().top
								}, 1000);

						}else{

							jQuery('#pedido_salida').dataTable().fnDraw();
							jQuery('#pedido_entrada').dataTable().fnDraw();

								spinner.stop();
								jQuery('#foo').css('display','none');


								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'agregar_pedido');
												return false;
									        }
								});								
							 
						}
		        }
	});			

   jQuery(this).attr('disabled', false);				        
});

 
//Quitar las salidas y retornarlas a estradas 
jQuery('table').on('click','.quitar_pedido', function (e) {
	jQuery(this).attr('disabled', true);				        

	identificador = (jQuery(this).attr('identificador'));
	jQuery.ajax({
		        url : 'quitar_prod_pedido', //
		        data : { 
		        	identificador: identificador
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {	
						if(data.exito != true){
							//aqui es donde va el mensaje q no se ha copiado
						}else{
							jQuery('#pedido_salida').dataTable().fnDraw();
							jQuery('#pedido_entrada').dataTable().fnDraw();

								jQuery.ajax({
									        url : 'conteo_tienda',
									        data : { 
									        	tipo: 'tienda',
									        },
									        type : 'POST',
									        dataType : 'json',
									        success : function(data) {	
									        	MY_Socket.sendNewPost(data.vendedor+' - '+data.tienda,'quitar_pedido');
									        	return false;
	
									        }
								});								
							 
						}


		        }
	});			
	jQuery(this).attr('disabled', false);				        			        

});


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function centerModal() {
    jQuery(this).css('display', 'block');
    var $dialog = jQuery(this).find(".modal-dialog");
    var offset = (jQuery(window).height() - $dialog.offsetHeight) / 2; //height()
    // Center modal vertically in window
    $dialog.css("margin-top", offset);
}

jQuery('.modal').on('show.bs.modal', centerModal);
jQuery(window).on("resize", function () {
    jQuery('.modal:visible').each(centerModal);
});

	//logueo y recuperar contraseña
	jQuery("#form_login").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				}else{
						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '';						
				}
			} 
		});
		return false;
	});

jQuery('.datepicker').datepicker({
    format: 'mm/dd/yyyy',
    startDate: '-3d'
});

  ////////////////////////////catalogos////////////////////////////////////////////////

	jQuery('#modalMessage').on('hide.bs.modal', function(e) {
	    jQuery(this).removeData('bs.modal');
	});	

	//gestion de usuarios (crear, editar y eliminar)

    	//gestion de usuarios (crear, editar y eliminar )
	jQuery("#form_sino").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if (data != true) {
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				} else {
					    $catalogo = e.target.name;
						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '/'+$catalogo;	
				}
			} 
		});
		return false;
	});	

	

    jQuery('body').on('submit','#form_prod', function (e) {
            
            e.stopImmediatePropagation();
    		
			    //poner la seleccion cuando se va a enviar	
			jQuery( "#colores_seleccionados > option" ).each(function() {
		      
		      jQuery( "#colores_seleccionados > option" ).prop('selected','selected');
		    });

			//alert('asd');
			jQuery('#foo').css('display','block');
			var spinner = new Spinner(opts).spin(target);
			
			jQuery(this).ajaxSubmit({
				success: function(data){
					if(data != true){
						
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					

					}else{
						    
						    $catalogo = e.target.name;
							spinner.stop();
							jQuery('#foo').css('display','none');
							window.location.href = '/'+$catalogo;	
							return false;
					}
				} 
			});
			return false;
	});	

	//http://jepser.com/idea/2-errores-comunes-programando-en-javascript/
    jQuery('body').on('submit','#form_catalogos', function (e) {
    		
			    //poner la seleccion cuando se va a enviar	
			jQuery( "#colores_seleccionados > option" ).each(function() {
		      //console.log(this.text + ' ' + this.value); //#colores_seleccionados
		      //jQuery( "#colores_seleccionados > option" ).attr('selected','selected');
		      jQuery(this).prop('selected','selected');
		      //jQuery(this).attr('selected','selected');1
		    });

			jQuery('#foo').css('display','block');
			var spinner = new Spinner(opts).spin(target);
			jQuery(this).ajaxSubmit({
				success: function(data){
					if(data != true){
						
						spinner.stop();
						jQuery('#foo').css('display','none');
						jQuery('#messages').css('display','block');
						jQuery('#messages').addClass('alert-danger');
						jQuery('#messages').html(data);
						jQuery('html,body').animate({
							'scrollTop': jQuery('#messages').offset().top
						}, 1000);
					

					}else{
						    $catalogo = e.target.name;
							spinner.stop();
							jQuery('#foo').css('display','none');
							//e.preventDefault();
							window.location.href = '/'+$catalogo;	
							return false;
					}
				} 
			});
			return false;
	});	


jQuery('body').on('submit','#form_eliminar', function (e) {
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					    $catalogo = e.target.name;
						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '/'+$catalogo;	
				}
			} 
		});
		return false;
});	





jQuery('#tabla_productos_compra').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_productos_temporales_compra",
         		"type": "POST"
     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  	jQuery('#total_peso').html( 'Total de kgs:'+number_format(settings.json.totales.peso, 2, '.', ','));
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
		    jQuery('#total_peso').html( 'Total de kgs: 0.00');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	


	    if (settings.json.totales_importe) {
		  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
			jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {
		    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
			jQuery('#total_iva').html( 'IVA: 0.00');
			jQuery('#total_total').html('Total de mts: 0.00');

		}	





			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
		

	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 10 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 11)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				total_peso_real = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				//importe
				
				total_subtotal = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 15)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
				
				total_total = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

					


				total_pieza = (end-start);	

			        
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#peso').html( 'Total de peso real:'+number_format(total_peso_real, 2, '.', ','));
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format(total_total, 2, '.', ','));


		} else 	{

			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#peso').html('Total de peso real: 0.00');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');	

		}	
    },	

   "columnDefs": [
   				
   				{ 
		                "render": function ( data, type, row ) {
								 var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 


		                		if (row[17]!='') {
		                			return row[2]+'<br/><b style="color:'+color+';">Cód: </b>'+row[17];	
		                		} else {
		                			return row[2];
		                		}
		                		
		                },
		                "targets": [2]   //el 3 es la imagen q ya viene formada desde el modelo
		        },  
    			
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [1,3,4,5]
	            },
    			
    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [6]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [9]
	            },

    			{  //precio
	                "render": function ( data, type, row ) {
						
						return number_format(parseFloat(row[18]), 2, '.', ',');	
	                },
	                "targets": [10]
	            },

    			{ //subtotal
	                "render": function ( data, type, row ) {
						
						return number_format(parseFloat(row[13]), 2, '.', ',');	
	                },
	                "targets": [11]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return number_format(parseFloat((row[13]*row[14])/100), 2, '.', ',');	
	                },
	                "targets": [12]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						


						return number_format((parseFloat(row[13])+parseFloat((row[13]*row[14])/100)), 2, '.', ',');	
	                },
	                "targets": [13]
	            },	            	

    			{ 
	                "render": function ( data, type, row ) {
						texto='<td>';
						    texto+='<a href="eliminar_prod_temporal_compra/'+(row[0])+'/'+jQuery.base64.encode(row[1])+'" '; 
									texto+='class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage"> ';
									texto+='<span class="glyphicon glyphicon-remove"></span> ';
							texto+='</a>';
						texto+='</td>';
						return texto;	

	                },
	                "targets": [14]
	            },
    			{ 
	                 "visible": false,
	                "targets": [0,15,16,17,18,19] //11,12
	            }

	],	


	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =productos_temporales;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	



jQuery('#tabla_productos').dataTable( {
	"pagingType": "full_numbers",
	"processing": true,
	"serverSide": true,
	"ajax": {
            	"url" : "procesando_productos_temporales",
         		"type": "POST"
     },   
	"infoCallback": function( settings, start, end, max, total, pre ) {
	    if (settings.json.totales) {
		    jQuery('#total_pieza').html( 'Total de piezas:'+ settings.json.totales.pieza);
		  	jQuery('#total_peso').html( 'Total de kgs:'+number_format(settings.json.totales.peso, 2, '.', ','));
			jQuery('#total_kg').html( 'Total de kgs:'+number_format(settings.json.totales.kilogramo, 2, '.', ','));
			jQuery('#total_metro').html('Total de mts:'+ number_format(settings.json.totales.metro, 2, '.', ','));

		} else {
		    jQuery('#total_pieza').html( 'Total de piezas: 0');
		    jQuery('#total_peso').html( 'Total de kgs: 0.00');
			jQuery('#total_kg').html( 'Total de kgs: 0.00');
			jQuery('#total_metro').html('Total de mts: 0.00');

		}	


	    if (settings.json.totales_importe) {
		  	jQuery('#total_subtotal').html( 'SubTotal:'+number_format(settings.json.totales_importe.subtotal, 2, '.', ','));
			jQuery('#total_iva').html( 'IVA:'+number_format(settings.json.totales_importe.iva, 2, '.', ','));
			jQuery('#total_total').html('Total:'+ number_format(settings.json.totales_importe.total, 2, '.', ','));

		} else {
		    jQuery('#total_subtotal').html( 'Subtotal: 0.00');
			jQuery('#total_iva').html( 'IVA: 0.00');
			jQuery('#total_total').html('Total de mts: 0.00');

		}	





			if (settings.json.recordsTotal==0) {
				jQuery("#disa_reportes").attr('disabled', true);					
			} else {
				jQuery("#disa_reportes").attr('disabled', false);					
			}

	    return pre
  	} ,    


	"footerCallback": function( tfoot, data, start, end, display ) {
		

	   var api = this.api(), data;
			var intVal = function ( i ) {
				return typeof i === 'string' ?
					i.replace(/[\$,]/g, '')*1 :
					typeof i === 'number' ?
						i : 0;
			};

		if  (data.length>0) {   
				
				total_metro = api
					.column( 10 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				total_kilogramo = api
					.column( 11)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				total_peso_real = api
					.column( 12)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );

				//importe
				
				total_subtotal = api
					.column( 13)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

				
				total_iva = api
					.column( 15)
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );	

				//importe
				
				total_total = api
					.column( 16 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );					

					


				total_pieza = (end-start);	

			        
			        jQuery('#pieza').html( 'Total de piezas:'+ total_pieza);
			        jQuery('#peso').html( 'Total de peso real:'+number_format(total_peso_real, 2, '.', ','));
			        jQuery('#kg').html( 'Total de kgs:'+number_format(total_kilogramo, 2, '.', ','));
			        jQuery('#metro').html('Total de mts:'+ number_format(total_metro, 2, '.', ','));

					//importes
					jQuery('#subtotal').html('SubTotal:'+ number_format(total_subtotal, 2, '.', ','));
					jQuery('#iva').html('IVA:' + number_format( total_iva, 2, '.', ','));
					jQuery('#total').html('Total:'+ number_format(total_total, 2, '.', ','));


		} else 	{

			        jQuery('#pieza').html('Total de piezas: 0');
			        jQuery('#peso').html('Total de peso real: 0.00');
			        jQuery('#metro').html('Total de mts: 0.00');
					jQuery('#kg').html('Total de kgs: 0.00');	

					//importes
					jQuery('#subtotal').html('SubTotal: 0.00');	
					jQuery('#iva').html('IVA: 0.00');	
					jQuery('#total').html('Total: 0.00');	

		}	
    },	

   "columnDefs": [
   				
   				{ 
		                "render": function ( data, type, row ) {
								 var color;
		                        switch (row[19]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 


		                		if (row[17]!='') {
		                			return row[2]+'<br/><b style="color:'+color+';">Cód: </b>'+row[17];	
		                		} else {
		                			return row[2];
		                		}
		                		
		                },
		                "targets": [2]   //el 3 es la imagen q ya viene formada desde el modelo
		        },  
    			
    			{ 
	                "render": function ( data, type, row ) {
						return data;	
	                },
	                "targets": [1,3,4,5]
	            },
    			
    			{ 
	                "render": function ( data, type, row ) {
						return row[12];	
	                },
	                "targets": [6]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[6];	
	                },
	                "targets": [7]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[8];	
	                },
	                "targets": [8]
	            },

    			{ 
	                "render": function ( data, type, row ) {
						return row[9];	
	                },
	                "targets": [9]
	            },

    			{  //precio
	                "render": function ( data, type, row ) {
						
						return number_format(parseFloat(row[18]), 2, '.', ',');	
	                },
	                "targets": [10]
	            },

    			{ //subtotal
	                "render": function ( data, type, row ) {
						
						return number_format(parseFloat(row[13]), 2, '.', ',');	
	                },
	                "targets": [11]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						return number_format(parseFloat((row[13]*row[14])/100), 2, '.', ',');	
	                },
	                "targets": [12]
	            },
    			{ 
	                "render": function ( data, type, row ) {
						


						return number_format((parseFloat(row[13])+parseFloat((row[13]*row[14])/100)), 2, '.', ',');	
	                },
	                "targets": [13]
	            },	            	

    			{ 
	                "render": function ( data, type, row ) {
						texto='<td>';
						    texto+='<a href="eliminar_prod_temporal/'+(row[0])+'/'+jQuery.base64.encode(row[1])+'" '; 
									texto+='class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#modalMessage"> ';
									texto+='<span class="glyphicon glyphicon-remove"></span> ';
							texto+='</a>';
						texto+='</td>';
						return texto;	

	                },
	                "targets": [14]
	            },
    			{ 
	                 "visible": false,
	                "targets": [0,15,16,17,18,19] //11,12
	            }

	],	


	"fnHeaderCallback": function( nHead, aData, iStart, iEnd, aiDisplay ) {
		var arreglo =productos_temporales;
		for (var i=0; i<=arreglo.length-1; i++) { //cant_colum
	    		nHead.getElementsByTagName('th')[i].innerHTML = arreglo[i]; 
	    	}
	},	

	"language": {  //tratamiento de lenguaje
		"lengthMenu": "Mostrar _MENU_ registros por página",
		"zeroRecords": "No hay registros",
		"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
		"infoEmpty": "No hay registros disponibles",
		"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
		"emptyTable":     "No hay registros",
		"infoPostFix":    "",
		"thousands":      ",",
		"loadingRecords": "Leyendo...",
		"processing":     "Procesando...",
		"search":         "Buscar:",
		"paginate": {
			"first":      "Primero",
			"last":       "Último",
			"next":       "Siguiente",
			"previous":   "Anterior"
		},
		"aria": {
			"sortAscending":  ": Activando para ordenar columnas ascendentes",
			"sortDescending": ": Activando para ordenar columnas descendentes"
		},
	},
});	


	//Agregar entrada temporal 
	jQuery("#form_entradas").submit(function(e){


		if (number_format(parseFloat(jQuery('#cantidad_um').val()) * parseFloat(jQuery('#cantidad_royo').val()) , 2, '.', ',') > parseFloat(jQuery('#id_medida[tipo="entrada_compra"] option:selected').attr('dif') ) ) {
			//alert('no es el valor' + number_format(parseFloat(jQuery('#cantidad_um').val()) * parseFloat(jQuery('#cantidad_royo').val()) , 2, '.', ',') );

			alert(  'Su cantidad no se corresponde con la aprobada'   );
			return false;
		}

		jQuery(this).attr('disabled', true);				        
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({

   				data : { 
		        	prod_entrada: jQuery('#producto option:selected').text(),

		        },			
			success: function(data){
				if(data != true){
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				}else{
					spinner.stop();
					//borrar el mensaje q quedo	
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','none');
					
					jQuery('#tabla_productos').dataTable().fnDraw();
					jQuery('#tabla_productos_compra').dataTable().fnDraw();

					//desabilito proveedor, factura y tipo_factura
					jQuery("fieldset.disabledme").attr('disabled', true);

					//vuelve a los valores por defecto, producto, color, composicion y calidad

					//
					jQuery("#codigo").val('');
					jQuery("#codigo_contable").text('');
					jQuery("#cantidad_um").val('');
					jQuery("#cantidad_royo").val('');
					jQuery("#ancho").val('');
					jQuery("#precio").val('');
					jQuery("#num_partida").val('');
					jQuery("#comentario").val('');

					jQuery('#calidad option:eq(0)').prop('selected', 'selected');
					jQuery('#composicion option:eq(0)').prop('selected', 'selected');
					jQuery('#color option:eq(0)').prop('selected', 'selected');
					jQuery('#producto option:eq(0)').prop('selected', 'selected');

					jQuery('#producto').trigger( "change" );
					
					//um y estatus sus valores por defectos

					jQuery('#id_medida option:eq(0)').prop('selected', 'selected');
					jQuery('#id_estatus option:eq(0)').prop('selected', 'selected');
					jQuery('#id_lote option:eq(0)').prop('selected', 'selected');

					//actualizar nuevamente los productos, porq dependen de los q ya se usaron
					var hash_url = window.location.pathname;
					if (hash_url=="/entrada_compra")  {
							//actualizar nuevamente el producto	 
							 jQuery("#producto").html(''); 
							  var id_compra= jQuery('#id_compra option:selected').val();
							  cargarDependencia_entrada_compra("producto",0,0,0,0,"producto","producto",id_compra);
					}

			
				}
			} 
		});
		jQuery(this).attr('disabled', false);				        

		return false;


	});	

		//actualizar nuevamente los productos, porq dependen de los q ya se usaron
					var hash_url = window.location.pathname;
					if (hash_url=="/entrada_compra")  {
							//actualizar nuevamente el producto	 
							 jQuery("#producto").html(''); 
							  var id_compra= jQuery('#id_compra option:selected').val();
							  cargarDependencia_entrada_compra("producto",0,0,0,0,"producto","producto",id_compra);
					}



//Agregar las estradas a salidas
jQuery('body').on('click','#impresion', function (e) {
	

	codigo = jQuery("#editar_prod_inven").val(); 
	


		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery.ajax({
		        url : 'validar_impresion',
		        data : { 
		        	codigo: codigo,
		        },
		        type : 'POST',
		       // dataType : 'json',
		        success : function(data) {	
				if(data != true){
							spinner.stop();
							jQuery('#foo').css('display','none');
							jQuery('#messages').css('display','block');
							jQuery('#messages').addClass('alert-danger');
							jQuery('#messages').html(data);
							jQuery('html,body').animate({
								'scrollTop': jQuery('#messages').offset().top
							}, 1000);
						}else{
							spinner.stop();
							//borrar el mensaje q quedo	
							jQuery('#foo').css('display','none');
							jQuery('#messages').css('display','none');

							window.open('impresion_etiquetas/'+jQuery.base64.encode(codigo), '_blank');
							 return false;
						}
		        }
	});						        
	
});




	jQuery('#tabla_cambio').dataTable( {

		/*
		dom: 'T<"clear">lfrtip',

		tableTools: {
			sRowSelect: "os",
			sSwfPath: "../../../js/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
		
		 	"aButtons":    [ 
                    //"xls", 
                   // "csv",
                    {
                        "sExtends": "pdf",
                        "sPdfOrientation": "landscape",
                       // "sPdfMessage": "ok este es la bla, bla, bla."
                    }
                ]

		},*/


	  "pagingType": "full_numbers",
 	  
		//"pagingType": "full_numbers",
		"processing": true,
		"serverSide": true,
		"ajax": {
	            	"url" : "procesando_servidor_cambio",
	         		"type": "POST",
	         		 "data": function ( d ) {
	     				   d.codigo = jQuery("#codigo_original").val(); 
	    			 }
	     },   


		"language": {  //tratamiento de lenguaje
			"lengthMenu": "Mostrar _MENU_ registros por página",
			"zeroRecords": "No hay registros",
			"info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"infoEmpty": "No hay registros disponibles",
			"infoFiltered": "(Mostrando _TOTAL_ de _MAX_ registros totales)",  
			"emptyTable":     "No hay registros",
			"infoPostFix":    "",
			"thousands":      ",",
			"loadingRecords": "Leyendo...",
			"processing":     "Procesando...",
			"search":         "Buscar:",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activando para ordenar columnas ascendentes",
				"sortDescending": ": Activando para ordenar columnas descendentes"
			},
		},


		"columnDefs": [

				{ 
		                "render": function ( data, type, row ) {

							    var color;
		                        switch (row[10]){
		                          case "12": //normal rojo
		                            color = 'red';
		                             break; 
		                          case "13": //devolucion verde
		                            color = 'green';
		                             break;
		                          case "14": //defecto azul
		                            color = 'blue';
		                             break;
		                          case "15": //ajuste naranja
		                            color = 'orange';
		                             break;
		                          default: 
		                            color = 'red';            
		                        } 

		                		if (row[9]!='') {
		                			return row[1]+'<br/><b style="color:'+color+';">Cód: </b>'+row[9];	
		                		} else {
		                			return row[1];
		                		}
		                		
		                },
		                "targets": [1]   //el 3 es la imagen q ya viene formada desde el modelo
		        },   	

			    	
			    	{ 
		                "render": function ( data, type, row ) {
		                		return data;
		                },
		                "targets": [0,2,3,4,5,6,7,8]
		            },
		            { 
		                 "visible": false,
		                "targets": [9,10]
		            }
		            
		        ],

	});	



////////////////////////////ordenar////////////////////////////////////////////////

/*
	jQuery(".tabla_ordenadas").tablesorter(); 
	jQuery("#tablahome1").tablesorter(); 
	jQuery("#tablahome2").tablesorter(); 
	jQuery("#tablahome3").tablesorter(); 
*/







	/////////////////////////buscar producto_inventario (buscar_prod_inven)


	// busqueda de prod_inven
/*
	var consulta_prod_inven = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_prod_inven').attr("name")+'&idprodinven='+jQuery('.buscar_prod_inven').attr("idprodinven"),
	});
*/

	var consulta_prod_inven = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_prod_inven').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_prod_inven.tt-input').attr("name"));
				    q += '&idprodinven='+encodeURIComponent(jQuery('.buscar_prod_inven.tt-input').attr("idprodinven"));
					q += '&id_almacen='+encodeURIComponent(jQuery('#id_almacen option:selected').val());
	            return  q;
	        }
	    },   

	});


	consulta_prod_inven.initialize();

	jQuery('.buscar_prod_inven').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_prod_inven',
	  displayKey: 'descripcion', //
	  source: consulta_prod_inven.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_prod_inven').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});


	jQuery('.buscar_prod_inven').on('typeahead:selected', function (e, datum,otro) {
		//console.log(datum);

	    jQuery('#producto').val(datum.id_descripcion);
	    jQuery('#codigo_original').val(datum.key);

	    //jQuery('#id_almacen').attr('id_almacen',datum.id_almacen);
	    jQuery('#id_almacen').val(datum.id_almacen);



	    jQuery('#oculto_producto').attr('color',datum.id_color );
	    jQuery('#oculto_producto').attr('composicion',datum.id_composicion );
	    jQuery('#oculto_producto').attr('calidad',datum.id_calidad );

	    //provocar el evento
	    jQuery('#oculto_producto').val('si');
	    jQuery('#producto').change();

	    jQuery("#codigo_contable").text(datum.codigo_contable);

	   	jQuery('#movimiento').val(datum.id_movimiento);
	   	jQuery('#movimiento_unico').val(datum.movimiento_unico);
	   	jQuery('#proveedor').val(datum.proveedor);
	   	jQuery('#fecha').val(datum.fecha_entrada);
	   	jQuery('#factura').val(datum.factura);
	   	jQuery('#cantidad_um').val(datum.cantidad_um);
	   	jQuery('#id_medida').val(datum.id_medida);
	   	jQuery('#ancho').val(datum.ancho);
	   	jQuery('#precio').val(datum.precio);
	   	jQuery('#iva').val(datum.iva);

	   	jQuery('#peso_real').val(datum.peso_real);
		
		jQuery('#num_partida').val(datum.num_partida);

		   	//jQuery('#id_estatus').val(datum.id_estatus);

			var val_prod = datum.key.substring(0,1);
    	    jQuery("#id_estatus").html(''); 
	        //cargar la dependencia
	        var id_estatus =datum.id_estatus;
	        cargarDependencia_estatus(val_prod, id_estatus);


	   	jQuery('#id_lote').val(datum.id_lote);

	   	jQuery('#id_tipo_pago').val(datum.id_tipo_pago);
	   	jQuery('#id_factura').val(datum.id_factura);

	   	

	   	jQuery('#tabla_cambio').dataTable().fnDraw();
	    

	});	

	jQuery('.buscar_prod_inven').on('typeahead:closed', function (e) {
		//jQuery('#tabla_entrada').dataTable().fnDraw();
	});	




	function cargarDependencia_estatus(val_prod,id_estatus) {
		
		var url = 'cargar_dependencia_estatus';	
		jQuery.ajax({
		        url : url,
		        data:{
		        	val_prod:val_prod,
		        },
		        type : 'POST',
		        dataType : 'json',
		        success : function(data) {
                    
					if (data != "[]") {
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {

                            	 if (id_estatus == valor.identificador) {
                            	 	jQuery("#id_estatus").append('<option value="' + valor.identificador + '" selected >' + valor.nombre + '</option>');     
                            	 } else {
                                 	jQuery("#id_estatus").append('<option value="' + valor.identificador + '" >' + valor.nombre + '</option>');     
                                 }



                            }
                        });

	                } 	
					

                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}


	
	
	/////////////////////////buscar proveedores

	// busqueda de proveedors
	

	var consulta_proveedor = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,

	  remote: {
	        url: 'catalogos/buscador?key=%QUERY',
	        replace: function () {
	            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_proveedor').typeahead("val"));
					q += '&nombre='+encodeURIComponent(jQuery('.buscar_proveedor.tt-input').attr("name"));
				    q += '&idproveedor='+encodeURIComponent(jQuery('.buscar_proveedor.tt-input').attr("idproveedor"));
				    q += '&id_almacen='+ (jQuery('#id_almacen_generar_pedido').val() ? jQuery('#id_almacen_generar_pedido').val() : '0');
	            
	            return  q;
	        }
	    },   

	});

	


	consulta_proveedor.initialize();

	jQuery('.buscar_proveedor').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_proveedor',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_proveedor').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor').on('typeahead:selected', function (e, datum,otro) {
	    key = datum.key;

	    jQuery('#tabla_entrada').dataTable().fnDraw();
	});	

	jQuery('.buscar_proveedor').on('typeahead:closed', function (e) {
		jQuery('#tabla_entrada').dataTable().fnDraw();
		
	});	


// busqueda de proveedors
	var consulta_proveedor = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_proveedor_filtra').attr("name")+'&idproveedor='+jQuery('.buscar_proveedor_filtra').attr("idproveedor"),
	});

	consulta_proveedor.initialize();

	jQuery('.buscar_proveedor_filtra').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_proveedor_filtra',
	  displayKey: 'descripcion', //
	  source: consulta_proveedor.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_proveedor_filtra').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	});

	jQuery('.buscar_proveedor_filtra').on('typeahead:selected', function (e, datum,otro) {
	    key = datum.key;
	    jQuery('#tabla_entrada').dataTable().fnDraw();
	});	

	jQuery('.buscar_proveedor_filtra').on('typeahead:closed', function (e) {
		jQuery('#tabla_entrada').dataTable().fnDraw();
	});	


	/////////////////////////buscar Cargadores/////////////////

	// busqueda de proveedors
	var consulta_cargador = new Bloodhound({
	   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
	   queryTokenizer: Bloodhound.tokenizers.whitespace,
	   remote:'/catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_cargador').attr("name"),
	});

	consulta_cargador.initialize();
	jQuery('.buscar_cargador').typeahead(
		{
			  hint: true,
		  highlight: true,
		  minLength: 1
		},

		 {
	  
	  name: 'buscar_cargador',
	  displayKey: 'descripcion', //
	  source: consulta_cargador.ttAdapter(),
	   templates: {
	   			//header: '<h4>'+jQuery('.buscar_cargador').attr("name")+'</h4>',
			    suggestion: function (data) {  
					return '<p><strong>' + data.descripcion + '</strong></p>'+
					 '<div style="background-color:'+ '#'+data.hexadecimal_color + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

		   }
	    
	  }
	  
	});



	jQuery('.buscar_proveedor').on('typeahead:selected', function (e, datum,otro) {
	    key = datum.key;
	});	


	jQuery('.buscar_proveedor').on('typeahead:closed', function (e) {

	});		
/////////////////////////////////////////////////////////////////////////////////////////////////////

// busqueda de colores  http://jsfiddle.net/Fresh/kLLCy/
//var p = jQuery('.buscar_proveedor').typeahead('val');
//var p = jQuery('.buscar_proveedor option:selected').val();
var consulta_producto = new Bloodhound({
   datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
   queryTokenizer: Bloodhound.tokenizers.whitespace,
   //remote:'catalogos/buscador?key=%QUERY&nombre='+jQuery('.buscar_producto').attr("name")+'&dependiente=', //typeahead('val')
   
   remote: {
        url: 'catalogos/buscador?key=%QUERY',
        replace: function () {
            var q = 'catalogos/buscador?key='+encodeURIComponent(jQuery('.buscar_producto').typeahead("val"));
				q += '&nombre='+encodeURIComponent(jQuery('.buscar_producto.tt-input').attr("name"));
			    q += '&dependiente='+encodeURIComponent(jQuery('.buscar_proveedor').typeahead("val"));
            
            return  q;
        }
    },   
   
});

//finalmente el celular, esta bloqueado, al parecer cuando se debio poner la contraseña, el intento fue fallido varias veces, y ahora pide el PIN, le puse el pin y el PUK, pero no pude desbloquearlo

consulta_producto.initialize();
jQuery('.buscar_producto').typeahead(
	{
	  hint: true,
	  highlight: true,
	  minLength: 1
	},

	 {
  
  name: 'buscar_producto', //nombre del conjunto de datos. Esto se añadirá a tt-dataset- 
						//para formar el nombre de la clase del elemento DOM que contiene
  displayKey: 'descripcion', //// if not set, will default to 'value',
  
  
  source: consulta_producto.ttAdapter(),
   templates: {
   			//header: '<h4>'+jQuery('.buscar_producto').attr("name")+'</h4>',
	   suggestion: function (data) {  
			return '<p><strong>' + data.descripcion + '</strong></p>'+
			 '<div style="background-producto:'+ '#'+data.hexadecimal_producto + ';display:block;width:15px;height:15px;margin:0 auto;"></div>';

	   }
    
  }
  
  
});
////////////////////************



	var opts = {
		lines: 13, 
		length: 20, 
		width: 10, 
		radius: 30, 
		corners: 1, 
		rotate: 0, 
		direction: 1, 
		color: '#E8192C',
		speed: 1, 
		trail: 60,
		shadow: false,
		hwaccel: false,
		className: 'spinner',
		zIndex: 2e9, 
		top: '50%', // Top position relative to parent
		left: '50%' // Left position relative to parent		
	};

	jQuery(".navigacion").change(function()
	{
	    document.location.href = jQuery(this).val();
	});




   

	var target = document.getElementById('foo');

	//tratamiento de fechas
	var fecha_actual = new Date();
	
	var fecha_anterior = new Date( fecha_actual.getTime() - (30 * 24 * 3600 * 1000));

	var dd = fecha_actual.getDate();
	var dd_anterior = fecha_anterior.getDate();

	var mm = fecha_actual.getMonth()+1;
	var mm_anterior = fecha_anterior.getMonth()+1;
	if(dd<10) {
    	dd='0'+dd;
	} 
	if(dd_anterior<10) {
    	dd_anterior='0'+dd_anterior;
	} 

	if(mm<10) {
	    mm='0'+mm;
	} 

	if(mm_anterior<10) {
	    mm_anterior='0'+mm_anterior;
	} 


	//var fecha_actual = new Date('December 25, 2005 23:15:00');
	var yyyy = fecha_actual.getFullYear();
	var yyyy_anterior = fecha_anterior.getFullYear();
	
	var horas= fecha_actual.getHours()
	var minutos = fecha_actual.getMinutes()
	var segundos = fecha_actual.getSeconds()

	var fecha_formateada = dd+mm+yyyy+horas+minutos+segundos;		

	var fecha_ayer = yyyy_anterior+'/'+mm_anterior+'/'+dd_anterior;
	var fecha_hoy = dd+'/'+mm+'/'+yyyy;	

	var fecha_hoy_uno = dd+'/'+mm+'/'+yyyy;	


 	jQuery('.fecha').datepicker({ format: 'dd-mm-yyyy'});
 	jQuery('.fecha_pago').datepicker({ format: 'dd-mm-yyyy'});

								



////////////////////////////Para los botones de agregar catalogo Modales///////////////////////////////


//gestion de usuarios (crear, editar y eliminar )
	
	jQuery("#form_modales").submit(function(e){
	//jQuery('body .container .btn-default').on('submit','#form_modales', function (e) {	

		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			dataType : 'json',
			success: function(data){
				
				if(data.estado.exito != true){
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data.fallo.mensaje);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
					e.preventDefault();

					if (data.catalogo != "[]") {
						jQuery("#"+data.dato.identificador).html('');
                        jQuery.each(data.catalogo, function (i, valor) {
                            if (valor.nombre !== null) {
                            	 if (valor.nombre == data.dato.valor) {
                            	 	jQuery("#"+data.dato.identificador).append('<option value="' + valor.identificador + '" selected >' + valor.nombre + '</option>');     
                            	 } else {
                                 	jQuery("#"+data.dato.identificador).append('<option value="' + valor.identificador + '">' + valor.nombre + '</option>');     
                                 }
                            }
                        });
	                }					 
					 spinner.stop();
					 jQuery('#foo').css('display','none');

					jQuery("#modalMessage").modal('hide');

					 e.preventDefault();

					 return false;




					/*
									   			"estado" => array('exito' => true),
											    "dato"  => array('valor'=> $data[$catal], 'catalogo' => $catal, 'identificador' =>  $identificador),
											    "catalogo"   => $variables

					*/

					 
				}
			} 
		});
		return false;
	});		

////////////////////////////fin de boton modal para catalogos////////////////////////////////////////////////


//////////////////////tratamiento catalogo colores////////////////////////////////////
/*
 jQuery('#addcolor_form').submit(function(){
    jQuery('#foo').css('display','block');
    var spinner = new Spinner(opts).spin(target);
    jQuery(this).ajaxSubmit({
      success: function(data){
        if(data != true){
          jQuery('#messages').html(data);
          jQuery('#messages').hide().slideDown("slow");
          jQuery("#messages").delay(2500).slideUp(800, function(){
            spinner.stop();
            jQuery('#foo').css('display','none');
            jQuery("#messages").html("");
          });
        }else{
          data = "<span class='success'>El color se ha editado satisfactoriamente.</span>";
          jQuery('#messages').css({'background-color':'#83bc37'});
          jQuery('#messages').html(data);
          jQuery('#messages').hide().slideDown("slow");
          jQuery("#messages").delay(2500).slideUp(800, function(){
            spinner.stop();
            jQuery('#foo').css('display','none');
            jQuery("#messages").html("");
            window.location.reload();
          });
        }
      }
    });
    return false;
  });
*/

/*
  jQuery('#agregar_color').click(function(){
    jQuery('#lista_colores option:selected').appendTo(jQuery('#colores_seleccionados'));
    return false;
  });

  jQuery('#quitar_color').click(function(){
    jQuery('#colores_seleccionados option:selected').appendTo(jQuery('#lista_colores'));
    return false;
  });
  */


  jQuery('#agregar_color').click(function(){
    jQuery('#lista_colores option:selected').appendTo(jQuery('#colores_seleccionados'));

    //quitar la seleccion. Cuando pasen los colores a la derecha
	jQuery( "#colores_seleccionados option:selected" ).each(function() {
      jQuery( "#colores_seleccionados option:selected" ).removeAttr('selected');
    });


    return false;
  });

  jQuery('#quitar_color').click(function(){

    jQuery('#colores_seleccionados option:selected').appendTo(jQuery('#lista_colores'));
    return false;
  });

  jQuery('#hex_color').ColorPicker({
    color: '#ffffff',
    onShow: function(colpkr){
      jQuery(colpkr).fadeIn(500);
      return false;
    },
    onHide: function(colpkr){
      jQuery(colpkr).fadeOut(500);
      return false;
    },
    onChange: function(hsb, hex, rgb){

      jQuery('#hex_color').val(hex);
      jQuery('#hex_color').css('backgroundColor', '#' + hex);
    }
  });


  jQuery('#hexadecimal_color').ColorPicker({
    color: '#ffffff',
    onShow: function(colpkr){
    	
      jQuery(colpkr).fadeIn(500);
      return false;
    },
    onHide: function(colpkr){
      jQuery(colpkr).fadeOut(500);
      return false;
    },
    onChange: function(hsb, hex, rgb){

      jQuery('#hexadecimal_color').val(hex);
      jQuery('#hexadecimal_color').css('backgroundColor', '#' + hex);
    }
  });
  

//////////////////////fin tratamiento catalogo colores////////////////////////////////////


	//gestion de usuarios (crear, editar y eliminar )
	jQuery("#form_usuarios").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{
						/*
						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '/usuarios';						
						*/

  						$catalogo = e.target.name;
						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '/'+$catalogo;	


				}
			} 
		});
		return false;
	});	


	//gestion de usuarios (crear, editar y eliminar )
	jQuery("#form_respaldo").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			success: function(data){
				if(data != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				

				}else{

						spinner.stop();
						jQuery('#foo').css('display','none');
						window.location.href = '/usuarios';						
				}
			} 
		});
		return false;
	});




//gestion de usuarios (crear, editar y eliminar )
	jQuery("#form_mantenimiento").submit(function(e){
		jQuery('#foo').css('display','block');
		var spinner = new Spinner(opts).spin(target);
		jQuery(this).ajaxSubmit({
			dataType : 'json',
			success: function(data){
				
				if(data.estado.exito != true){
					
					spinner.stop();
					jQuery('#foo').css('display','none');
					jQuery('#messages').css('display','block');
					jQuery('#messages').addClass('alert-danger');
					jQuery('#messages').html(data.fallo.mensaje);
					jQuery('html,body').animate({
						'scrollTop': jQuery('#messages').offset().top
					}, 1000);
				}else{
					e.preventDefault();
					 
					 spinner.stop();
					 jQuery('#foo').css('display','none');
					 $("#modalMessage35").modal('hide');

					 window.location.href = '/'+data.dato.valor+'#mante';	
					 window.location.reload();
					 return false;
					 
				}
			} 
		});
		return false;
	});		





    //Filtro La lista de colaboradores que es dependiente de categorias 
    jQuery( ".dependiente" ).change(function(e) {
        var valor_padre = jQuery(this).val();  
        var depende = e.target.name;
         switch(depende) {
		    case "id_marca":
		         var  elem_hijo=  "#id_linea";
		        break;
		    /*case "id_linea":
		        var  elem_hijo=  "#id_sublinea";
		        break;*/
		    default:
		        
		}
        jQuery(elem_hijo).html('');
        cargardependientes(valor_padre,elem_hijo);
     });



	function cargardependientes(valor_padre,elem_hijo) {

		var url = '/unidades/cargar_elemhijo';	
		//alert(elem_hijo);

		jQuery.ajax({
		        url : url+'/'+valor_padre+'/'+(elem_hijo.substring(1)),
		        type : 'GET',
		        dataType : 'json',
		        success : function(data) {
					if (data != "[]") {
                        jQuery(elem_hijo).append('<option value="-1" selected >Seleccione un elemento</option>');
                        jQuery.each(data, function (i, valor) {
                            if (valor.nombre !== null) {
                                 jQuery(elem_hijo).append('<option value="' + valor.identificador + '">' + valor.nombre + '</option>');     
                            }
                        });
	                }
                    return false;
		        },
		        error : function(jqXHR, status, error) {
		        },
		        complete : function(jqXHR, status) {
		            
		        }
		    }); 
	}

    if($(".ttip").length > 0){
        $(".ttip").tooltip();
    }


    if($("#movimiento").length > 0){
        $("#movimiento").tooltip();
    }
});
