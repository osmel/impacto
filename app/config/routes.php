<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']	 		= 'Main';
$route['404_override'] 					= '';


$route['login']							= 'main/login';
$route['forgot']						= 'main/forgot';
$route['session']						= 'main/session';

$route['establecer_modulo']				= 'main/establecer_modulo';



///////////////////////////////catalogos///////////////////////////////////////
$route['respaldar']					= 'respaldo/respaldar';
//catalogos modales
$route['catalogo_modal/(:any)/(:any)']					    = 'catalogos/catalogo_modal/$1/$2';
$route['validar_catalogo_modal']    						= 'catalogos/validar_catalogo_modal';
//listado de reportes
$route['reportes']						= 'nucleo/listado_reportes';

//Listado de todos los catalogos
$route['catalogos']						= 'catalogos/listado_catalogos';



/*

composicion

unidades_medidas
colores

operaciones

proveedores
productos

*/

//proveedores
$route['proveedores']					     = 'catalogos/listado_proveedores';

$route['nuevo_proveedor']                  = 'catalogos/nuevo_proveedor';
$route['validar_nuevo_proveedor']          = 'catalogos/validar_nuevo_proveedor';

$route['editar_proveedor/(:any)']      = 'catalogos/editar_proveedor/$1';
$route['validacion_edicion_proveedor']     = 'catalogos/validacion_edicion_proveedor';

$route['eliminar_proveedor/(:any)/(:any)'] = 'catalogos/eliminar_proveedor/$1/$2';
$route['validar_eliminar_proveedor']       = 'catalogos/validar_eliminar_proveedor';




//actividad_comercial
$route['actividades_comerciales']					     = 'catalogos/listado_actividad_comercial';

$route['nuevo_actividad_comercial']                  = 'catalogos/nuevo_actividad_comercial';
$route['validar_nuevo_actividad_comercial']          = 'catalogos/validar_nuevo_actividad_comercial';

$route['editar_actividad_comercial/(:any)']			 = 'catalogos/editar_actividad_comercial/$1';
$route['validacion_edicion_actividad_comercial']     = 'catalogos/validacion_edicion_actividad_comercial';

$route['eliminar_actividad_comercial/(:any)/(:any)'] = 'catalogos/eliminar_actividad_comercial/$1/$2';
$route['validar_eliminar_actividad_comercial']    	 = 'catalogos/validar_eliminar_actividad_comercial';



//unidades_medidas Ok
$route['unidades_medidas']					     = 'catalogos/listado_unidades_medidas';

$route['nuevo_unidad_medida']                  = 'catalogos/nuevo_unidad_medida';
$route['validar_nuevo_unidad_medida']          = 'catalogos/validar_nuevo_unidad_medida';

$route['editar_unidad_medida/(:any)']			 = 'catalogos/editar_unidad_medida/$1';
$route['validacion_edicion_unidad_medida']     = 'catalogos/validacion_edicion_unidad_medida';

$route['eliminar_unidad_medida/(:any)/(:any)'] = 'catalogos/eliminar_unidad_medida/$1/$2';
$route['validar_eliminar_unidad_medida']    	 = 'catalogos/validar_eliminar_unidad_medida';

//color
$route['colores']					     = 'catalogos/listado_colores';

$route['nuevo_color']                  = 'catalogos/nuevo_color';
$route['validar_nuevo_color']          = 'catalogos/validar_nuevo_color';

$route['editar_color/(:any)']			 = 'catalogos/editar_color/$1';
$route['validacion_edicion_color']     = 'catalogos/validacion_edicion_color';

$route['eliminar_color/(:any)/(:any)/(:any)'] = 'catalogos/eliminar_color/$1/$2/$3';
$route['validar_eliminar_color']    	 = 'catalogos/validar_eliminar_color';

//calidad
$route['calidades']              = 'catalogos/listado_calidades';

$route['nuevo_calidad']                  = 'catalogos/nuevo_calidad';
$route['validar_nuevo_calidad']          = 'catalogos/validar_nuevo_calidad';

$route['editar_calidad/(:any)']      = 'catalogos/editar_calidad/$1';
$route['validacion_edicion_calidad']     = 'catalogos/validacion_edicion_calidad';

$route['eliminar_calidad/(:any)/(:any)'] = 'catalogos/eliminar_calidad/$1/$2';
$route['validar_eliminar_calidad']       = 'catalogos/validar_eliminar_calidad';

//composicion
$route['composiciones']					     = 'catalogos/listado_composiciones';

$route['nuevo_composicion']                  = 'catalogos/nuevo_composicion';
$route['validar_nuevo_composicion']          = 'catalogos/validar_nuevo_composicion';

$route['editar_composicion/(:any)']			 = 'catalogos/editar_composicion/$1';
$route['validacion_edicion_composicion']     = 'catalogos/validacion_edicion_composicion';

$route['eliminar_composicion/(:any)/(:any)'] = 'catalogos/eliminar_composicion/$1/$2';
$route['validar_eliminar_composicion']    	 = 'catalogos/validar_eliminar_composicion';

//ancho de tela
$route['anchos']					     = 'catalogos/listado_anchos';

$route['nuevo_ancho']                  = 'catalogos/nuevo_ancho';
$route['validar_nuevo_ancho']          = 'catalogos/validar_nuevo_ancho';

$route['editar_ancho/(:any)']			 = 'catalogos/editar_ancho/$1';
$route['validacion_edicion_ancho']     = 'catalogos/validacion_edicion_ancho';

$route['eliminar_ancho/(:any)/(:any)'] = 'catalogos/eliminar_ancho/$1/$2';
$route['validar_eliminar_ancho']    	 = 'catalogos/validar_eliminar_ancho';

//almacen
$route['almacenes']					     = 'catalogos/listado_almacenes';

$route['nuevo_almacen']                  = 'catalogos/nuevo_almacen';
$route['validar_nuevo_almacen']          = 'catalogos/validar_nuevo_almacen';

$route['editar_almacen/(:any)']			 = 'catalogos/editar_almacen/$1';
$route['validacion_edicion_almacen']     = 'catalogos/validacion_edicion_almacen';

$route['eliminar_almacen/(:any)/(:any)'] = 'catalogos/eliminar_almacen/$1/$2';
$route['validar_eliminar_almacen']    	 = 'catalogos/validar_eliminar_almacen';

//configuracion
$route['configuraciones']					     = 'catalogos/listado_configuraciones';

$route['nuevo_configuracion']                  = 'catalogos/nuevo_configuracion';
$route['validar_nuevo_configuracion']          = 'catalogos/validar_nuevo_configuracion';

$route['editar_configuracion/(:any)']			 = 'catalogos/editar_configuracion/$1';
$route['validacion_edicion_configuracion']     = 'catalogos/validacion_edicion_configuracion';

$route['eliminar_configuracion/(:any)/(:any)'] = 'catalogos/eliminar_configuracion/$1/$2';
$route['validar_eliminar_configuracion']    	 = 'catalogos/validar_eliminar_configuracion';
$route['procesando_cat_configuraciones']    = 'catalogos/procesando_cat_configuraciones';

//tipo_factura
$route['tipos_facturas']					     = 'catalogos/listado_tipos_facturas';

$route['nuevo_tipo_factura']                  = 'catalogos/nuevo_tipo_factura';
$route['validar_nuevo_tipo_factura']          = 'catalogos/validar_nuevo_tipo_factura';

$route['editar_tipo_factura/(:any)']			 = 'catalogos/editar_tipo_factura/$1';
$route['validacion_edicion_tipo_factura']     = 'catalogos/validacion_edicion_tipo_factura';

$route['eliminar_tipo_factura/(:any)/(:any)'] = 'catalogos/eliminar_tipo_factura/$1/$2';
$route['validar_eliminar_tipo_factura']    	 = 'catalogos/validar_eliminar_tipo_factura';


//tipo_pedido 
$route['tipos_pedidos']					     = 'catalogos/listado_tipos_pedidos';

$route['nuevo_tipo_pedido']                  = 'catalogos/nuevo_tipo_pedido';
$route['validar_nuevo_tipo_pedido']          = 'catalogos/validar_nuevo_tipo_pedido';

$route['editar_tipo_pedido/(:any)']			 = 'catalogos/editar_tipo_pedido/$1';
$route['validacion_edicion_tipo_pedido']     = 'catalogos/validacion_edicion_tipo_pedido';

$route['eliminar_tipo_pedido/(:any)/(:any)'] = 'catalogos/eliminar_tipo_pedido/$1/$2';
$route['validar_eliminar_tipo_pedido']    	 = 'catalogos/validar_eliminar_tipo_pedido';


//tipo_venta de tela
$route['tipos_ventas']					     = 'catalogos/listado_tipos_ventas';

$route['nuevo_tipo_venta']                  = 'catalogos/nuevo_tipo_venta';
$route['validar_nuevo_tipo_venta']          = 'catalogos/validar_nuevo_tipo_venta';

$route['editar_tipo_venta/(:any)']			 = 'catalogos/editar_tipo_venta/$1';
$route['validacion_edicion_tipo_venta']     = 'catalogos/validacion_edicion_tipo_venta';

$route['eliminar_tipo_venta/(:any)/(:any)'] = 'catalogos/eliminar_tipo_venta/$1/$2';
$route['validar_eliminar_tipo_venta']    	 = 'catalogos/validar_eliminar_tipo_venta';

//cargador
$route['cargadores']					     = 'catalogos/listado_cargadores';

$route['nuevo_cargador']                  = 'catalogos/nuevo_cargador';
$route['validar_nuevo_cargador']          = 'catalogos/validar_nuevo_cargador';

$route['editar_cargador/(:any)']			 = 'catalogos/editar_cargador/$1';
$route['validacion_edicion_cargador']     = 'catalogos/validacion_edicion_cargador';

$route['eliminar_cargador/(:any)/(:any)'] = 'catalogos/eliminar_cargador/$1/$2';
$route['validar_eliminar_cargador']    	 = 'catalogos/validar_eliminar_cargador';

//operaciones
$route['operaciones']					     = 'catalogos/listado_operaciones';

$route['nuevo_operacion']                  = 'catalogos/nuevo_operacion';
$route['validar_nuevo_operacion']          = 'catalogos/validar_nuevo_operacion';

$route['editar_operacion/(:any)']			 = 'catalogos/editar_operacion/$1';
$route['validacion_edicion_operacion']     = 'catalogos/validacion_edicion_operacion';

$route['eliminar_operacion/(:any)/(:any)'] = 'catalogos/eliminar_operacion/$1/$2';
$route['validar_eliminar_operacion']    	 = 'catalogos/validar_eliminar_operacion';



//productos
$route['productos']					       = 'catalogos/listado_productos';

$route['nuevo_producto']                  = 'catalogos/nuevo_producto';
$route['validar_nuevo_producto']          = 'catalogos/validar_nuevo_producto';

$route['editar_producto/(:any)']          = 'catalogos/editar_producto/$1';
$route['cambiar_producto/(:any)']          = 'catalogos/cambiar_producto/$1';
$route['detalle_producto/(:any)']          = 'catalogos/detalle_producto/$1';

$route['validacion_edicion_producto']     = 'catalogos/validacion_edicion_producto';
$route['validacion_cambio_producto']     = 'catalogos/validacion_cambio_producto';

$route['eliminar_producto/(:any)/(:any)'] = 'catalogos/eliminar_producto/$1/$2';
$route['validar_eliminar_producto']       = 'catalogos/validar_eliminar_producto';


//catalogos modales
$route['catalogo_modal/(:any)/(:any)']					    = 'catalogos/catalogo_modal/$1/$2';
$route['validar_catalogo_modal']    						= 'catalogos/validar_catalogo_modal';


$route['editar_minimo/(:any)']          = 'catalogos/editar_minimo/$1';
$route['validacion_edicion_minimo']     = 'catalogos/validacion_edicion_minimo';

///////////////////////////////usuarios///////////////////////////////////////


$route['detalle_historico/(:any)/(:any)/(:any)/(:any)']    = 'unidades/detalle_historico/$1/$2/$3/$4';

$route['historicoaccesos']                 = 'main/historicoaccesos';

$route['usuarios']						= 'main/listado_usuarios';

$route['nuevo_usuario']                 = 'main/nuevo_usuario';
$route['validar_nuevo_usuario']         = 'main/validar_nuevo_usuario';

$route['eliminar_usuario/(:any)/(:any)']		= 'main/eliminar_usuario/$1/$2';
$route['validando_eliminar_usuario']    = 'main/validar_eliminar_usuario';

$route['actualizar_perfil']		         = 'main/actualizar_perfil';
$route['editar_usuario/(:any)']			= 'main/actualizar_perfil/$1';
$route['validacion_edicion_usuario']    = 'main/validacion_edicion_usuario';

$route['salir']							= 'main/logout';
$route['validar_login']					= 'main/validar_login';
$route['validar_recuperar_password']	= 'main/validar_recuperar_password';


/////////////////////////////////////////////Listado de todas las salidas
$route['salidas']						= 'salidas/listado_salidas';
//ventas, ofertas, transferencia_enviada, devolucion_compra, ajuste_negativo

/////////////////////////////////////////////Listado de todas las entradas
$route['entradas']						= 'entradas/listado_entradas';
//recepcion, devolucion_venta, transferencia_salida, ajuste_positivo

//operaciones de entradas

$route['validar_agregar_producto']          = 'entradas/validar_agregar_producto';
$route['listado_temporal']          = 'entradas/listado_temporal';


$route['recepciones']					     = 'entradas/listado_recepciones';
$route['nuevo_recepcion']                  = 'entradas/nuevo_recepcion';
$route['editar_recepcion/(:any)']      = 'entradas/editar_recepcion/$1';
$route['validacion_edicion_recepcion']     = 'entradas/validacion_edicion_recepcion';


$route['procesando_productos_temporales']    		= 'entradas/procesando_productos_temporales';




$route['eliminar_prod_temporal/(:any)/(:any)']       = 'entradas/eliminar_prod_temporal/$1/$2';
$route['validar_eliminar_prod_temporal']    = 'entradas/validar_eliminar_prod_temporal';


//procesamiento de entrada
$route['procesar_entradas/(:any)/(:any)/(:any)/(:any)/(:any)']    = 'entradas/procesar_entradas/$1/$2/$3/$4/$5';

$route['procesar_entrar/(:any)/(:any)/(:any)']   				 = 'entradas/procesar_entrar/$1/$2/$3';




$route['validar_proceso']    = 'entradas/validar_proceso';

$route['consecutivo_entrada']    = 'entradas/consecutivo_entrada';



$route['generar_etiquetas/(:any)/(:any)/(:any)/(:any)']   		  = 'pdfs/generar_etiquetas/$1/$2/$3/$4';
$route['generar_etiquetas_rapida/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_etiquetas_rapida/$1/$2/$3/$4';
    $route['generar_notas/(:any)/(:any)/(:any)/(:any)']   		  = 'pdfs/generar_notas/$1/$2/$3/$4';
    $route['generar_notas_rapida/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_notas_rapida/$1/$2/$3/$4';

$route['pdfs']    = 'pdfs/index';



/////////////////////////////////////////////Listado de todas las entradas de compra
$route['entrada_compra']						 		= 'entrada_compra/listado_entradas';
$route['validar_agregar_producto_compra']       		= 'entrada_compra/validar_agregar_producto_compra';
$route['listado_temporal']          	 		= 'entrada_compra/listado_temporal';


$route['recepciones']					 		= 'entrada_compra/listado_recepciones';
$route['nuevo_recepcion']                		= 'entrada_compra/nuevo_recepcion';
$route['editar_recepcion/(:any)']      	 		= 'entrada_compra/editar_recepcion/$1';
$route['validacion_edicion_recepcion']   		= 'entrada_compra/validacion_edicion_recepcion';

$route['procesando_productos_temporales_compra']    	= 'entrada_compra/procesando_productos_temporales_compra';

$route['eliminar_prod_temporal_compra/(:any)/(:any)']  = 'entrada_compra/eliminar_prod_temporal_compra/$1/$2';
$route['validar_eliminar_prod_temporal_compra']    	= 'entrada_compra/validar_eliminar_prod_temporal_compra';

$route['procesar_entrada_compra/(:any)/(:any)/(:any)/(:any)/(:any)']    = 'entrada_compra/procesar_entrada_compra/$1/$2/$3/$4/$5';

$route['procesar_entrar_compra/(:any)/(:any)/(:any)']   				 = 'entrada_compra/procesar_entrar_compra/$1/$2/$3';

$route['validar_proceso_compra']    					= 'entrada_compra/validar_proceso_compra';

$route['cargarDependencia_entrada_compra']   = 'entrada_compra/cargar_dependencia';
$route['refencia_producto_compra']   		 = 'entrada_compra/refencia_producto_compra';


///////////////////salidas///////////////////////


$route['cargar_dependencia_salida']    		= 'salidas/cargar_dependencia_salida';
$route['procesando_servidor']    		= 'salidas/procesando_servidor';
$route['agregar_prod_salida']    		= 'salidas/agregar_prod_salida';

$route['procesando_servidor_salida']    = 'salidas/procesando_servidor_salida';
$route['quitar_prod_salida']		    = 'salidas/quitar_prod_salida';

$route['generar_salida/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_salida/$1/$2/$3/$4';
$route['generar_salida_rapida/(:any)/(:any)/(:any)']    = 'pdfs/generar_salida_rapida/$1/$2/$3';




$route['detalle_salidas/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)']    = 'salidas/detalle_salidas/$1/$2/$3/$4/$5/$6';

$route['procesar_salidas']    = 'salidas/procesar_salidas';

$route['confirmar_salida_sino']    = 'salidas/confirmar_salida_sino';
$route['validar_confirmar_salida_sino']    = 'salidas/validar_confirmar_salida_sino';


///para el caso de salida por pedidos

$route['confirmar_proc_pedido_sino']    = 'salidas/confirmar_proc_pedido_sino';
$route['proc_salida_pedido_definitivo/(:any)/(:any)/(:any)/(:any)']      = 'salidas/proc_salida_pedido_definitivo/$1/$2/$3/$4';
$route['validar_salida_pedido']    = 'salidas/validar_salida_pedido';
$route['detalles_salidas/(:any)/(:any)/(:any)/(:any)']    = 'salidas/detalles_salidas/$1/$2/$3/$4';

$route['detalles_salidas_bodegas/(:any)']    = 'salidas/detalles_salidas_bodegas/$1';




///para el caso de salida por pedidos "apartados" VENDEDOR
$route['confirmar_proc_apartado_sino']    = 'salidas/confirmar_proc_apartado_sino';
$route['proc_apartado_pedido_definitivo/(:any)/(:any)/(:any)/(:any)']      = 'salidas/proc_apartado_pedido_definitivo/$1/$2/$3/$4';
$route['validar_apartado_pedido']    = 'salidas/validar_apartado_pedido';

/////////////////////////////////////////Salida_directa///////////////////


$route['salida_directa']									= 'salida_directa/listado_salida_directa';

$route['procesando_entrada_directa']    					= 'salida_directa/procesando_entrada_directa';
$route['procesando_salida_directa']    						= 'salida_directa/procesando_salida_directa';
$route['agregar_salida_directa']    							= 'salida_directa/agregar_salida_directa';
$route['quitar_salida_directa']		    					= 'salida_directa/quitar_salida_directa';
$route['salida_directa_definitiva']							= 'salida_directa/salida_directa_definitiva';


$route['confirmar_salida_directa']    						= 'salida_directa/confirmar_salida_directa';

/*


$route['conteo_tienda']   									= 'salida_directa/conteo_tienda';
$route['pedidos']											= 'salida_directa/listado_apartados';
$route['apartado_detalle/(:any)/(:any)/(:any)'] 			= 'salida_directa/apartado_detalle/$1/$2/$3';
$route['pedido_detalle/(:any)/(:any)/(:any)']    			= 'salida_directa/pedido_detalle/$1/$2/$3';
$route['pedido_completado_detalle/(:any)/(:any)/(:any)']    = 'salida_directa/pedido_completado_detalle/$1/$2/$3';
$route['eliminar_apartado_detalle/(:any)/(:any)/(:any)']    = 'salida_directa/eliminar_apartado_detalle/$1/$2/$3';
$route['validar_eliminar_apartado_detalle']    				= 'salida_directa/validar_eliminar_apartado_detalle';
$route['eliminar_pedido_detalle/(:any)/(:any)/(:any)']    	= 'salida_directa/eliminar_pedido_detalle/$1/$2/$3';
$route['validar_eliminar_pedido_detalle']    				= 'salida_directa/validar_eliminar_pedido_detalle';
$route['imprimir_reportes_pedido']    						= 'salida_directa/imprimir_reportes_pedido';
$route['procesando_pedido_pendiente']       				= 'salida_directa/procesando_pedido_pendiente';


$route['procesando_pedido_detalle']    						= 'salida_directa/procesando_pedido_detalle';
$route['incluir_pedido']    								= 'salida_directa/incluir_pedido';
$route['excluir_pedido']    								= 'salida_directa/excluir_pedido';
$route['incluir_apartado']    								= 'salida_directa/incluir_apartado';
$route['excluir_apartado']    								= 'salida_directa/excluir_apartado';
$route['procesando_apartado_pendiente'] 					= 'salida_directa/procesando_apartado_pendiente';
$route['procesando_detalle'] 								= 'salida_directa/procesando_detalle';
$route['cargar_dependencia_pedido']   						= 'salida_directa/cargar_dependencia_pedido';
$route['procesando_pedido_completo']       					= 'salida_directa/procesando_pedido_completo';
$route['procesando_completo_detalle']    					= 'salida_directa/procesando_completo_detalle';
$route['marcando_prorroga_venta']    						= 'salida_directa/marcando_prorroga_venta';
$route['marcando_prorroga_tienda']    						= 'salida_directa/marcando_prorroga_tienda';
*/

/////////////////////////////////////////HOME///////////////////
$route['procesando_home']    		= 'main/procesando_home';
$route['procesando_inicio']    		= 'main/procesando_inicio';



$route['detalles_grupo/(:any)/(:any)']   = 'main/detalles_grupo/$1/$2';







$route['marcando_apartado']    		= 'main/marcando_apartado';

$route['procesar_apartados']    		    = 'main/procesar_apartados';
$route['tabla_apartado_vendedores']    		= 'main/tabla_apartado_vendedores';

$route['eliminar_apartado_vendedores/(:any)/(:any)'] = 'main/eliminar_apartado_vendedores/$1/$2';
$route['validar_eliminar_apartado_vendedores']    			= 'main/validar_eliminar_apartado_vendedores';



$route['apartado_definitivo']    		= 'main/apartado_definitivo';


$route['procesando_producto_color']    		= 'main/procesando_producto_color';
$route['procesando_producto_color2']    		= 'main/procesando_producto_color2';

$route['imprimir_reportes_apartado']    		= 'main/imprimir_reportes_apartado';

$route['detalles_imagen/(:any)/(:any)']    		= 'main/detalles_imagen/$1/$2';




/////////////////////////////////////////////Listado de todos los pedidos
// conteo con notificacion push
$route['conteo_tienda']   			= 'pedidos/conteo_tienda';
////

$route['pedidos']						= 'pedidos/listado_apartados';

$route['apartado_detalle/(:any)/(:any)/(:any)'] = 'pedidos/apartado_detalle/$1/$2/$3';
//$route['apartado_detalle/(:any)/(:any)/(:any)/(:any)'] = 'pedidos/apartado_detalle/$1/$2/$3/$4';

//$route['pedido_detalle/(:any)/(:any)/(:any)/(:any)']    			= 'pedidos/pedido_detalle/$1/$2/$3/$4';

$route['pedido_detalle/(:any)/(:any)/(:any)']    			= 'pedidos/pedido_detalle/$1/$2/$3';

$route['pedido_completado_detalle/(:any)/(:any)/(:any)']    			= 'pedidos/pedido_completado_detalle/$1/$2/$3';


$route['eliminar_apartado_detalle/(:any)/(:any)/(:any)']    	= 'pedidos/eliminar_apartado_detalle/$1/$2/$3';
$route['validar_eliminar_apartado_detalle']    			= 'pedidos/validar_eliminar_apartado_detalle';


$route['eliminar_pedido_detalle/(:any)/(:any)/(:any)']    			= 'pedidos/eliminar_pedido_detalle/$1/$2/$3';
$route['validar_eliminar_pedido_detalle']    			= 'pedidos/validar_eliminar_pedido_detalle';


$route['imprimir_reportes_pedido']    		= 'pedidos/imprimir_reportes_pedido';

////////

$route['procesando_pedido_pendiente']       = 'pedidos/procesando_pedido_pendiente';
$route['generar_pedidos']						= 'pedidos/listado_pedidos';
$route['pedido_definitivo']						= 'pedidos/pedido_definitivo';
$route['procesando_pedido_detalle']    			= 'pedidos/procesando_pedido_detalle';

$route['incluir_pedido']    			= 'pedidos/incluir_pedido';
$route['excluir_pedido']    			= 'pedidos/excluir_pedido';
$route['incluir_apartado']    			= 'pedidos/incluir_apartado';
$route['excluir_apartado']    			= 'pedidos/excluir_apartado';

$route['procesando_apartado_pendiente'] = 'pedidos/procesando_apartado_pendiente';
$route['procesando_detalle'] = 'pedidos/procesando_detalle';



$route['cargar_dependencia_pedido']   = 'pedidos/cargar_dependencia_pedido';












/////////////////////////////////////////
$route['id_proveedor']    			= 'salidas/id_proveedor';
$route['refe_producto']    			= 'salidas/refe_producto';



/////////////////////////////////////////////Listado de todos los pedidos completados
$route['procesando_pedido_completo']       = 'pedidos/procesando_pedido_completo';


$route['procesando_completo_detalle']    			= 'pedidos/procesando_completo_detalle';



///////////////////Pedidos salidas///////////////////////
///nose

//$route['pedidodetalle']					= 'pedidos/detalle_pedidos';
//$route['pedidocompleto']				= 'pedidos/detalle_completo';


$route['procesando_pedido_entrada']    		= 'pedidos/procesando_pedido_entrada';

$route['procesando_pedido_salida']    		= 'pedidos/procesando_pedido_salida';

$route['agregar_prod_pedido']    				= 'pedidos/agregar_prod_pedido';
$route['quitar_prod_pedido']		    		= 'pedidos/quitar_prod_pedido';

$route['marcando_prorroga_venta']    			= 'pedidos/marcando_prorroga_venta';
$route['marcando_prorroga_tienda']    			= 'pedidos/marcando_prorroga_tienda';


$route['generar_pedido_especifico/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_pedido_especifico/$1/$2/$3/$4';


//////////****************Aqui para las impresiones**************////////////////////

$route['pdf_pedido/(:any)']    		= 'pdfs/pdf_pedido/$1';
$route['pdf_apartado/(:any)']   	= 'pdfs/pdf_apartado/$1';
$route['pdf_historico/(:any)']    	= 'pdfs/pdf_historico/$1';


$route['pro_salida/(:any)/(:any)/(:any)/(:any)/(:any)']    			= 'salidas/pro_salida/$1/$2/$3/$4/$5';

///////////////////////////////////DEVOLUCIONES//////////////////////////////////////////////

$route['devolucion']										= 'devoluciones/devolucion';
$route['validar_devolucion_producto']						= 'devoluciones/validar_devolucion_producto';

$route['quitar_devolucion/(:any)/(:any)']					= 'devoluciones/quitar_devolucion/$1/$2';

$route['validar_quitar_devolucion']						    = 'devoluciones/validar_quitar_devolucion';


$route['procesar_devoluciones/(:any)']    = 'devoluciones/procesar_devoluciones/$1';
//$route['generar_etiquetas/(:any)']    = 'pdfs/generar_etiquetas/$1';
//$route['generar_notas/(:any)']    = 'pdfs/generar_notas/$1';


//$route['eliminar_prod_temporal/(:any)/(:any)']       = 'entradas/eliminar_prod_temporal/$1/$2';
//$route['validar_eliminar_prod_temporal']    = 'entradas/validar_eliminar_prod_temporal';




$route['procesando_servidor_devolucion']			= 'devoluciones/procesando_servidor_devolucion';  

$route['validar_conf_devolucion']					= 'devoluciones/validar_conf_devolucion';  


///////////////////////////////////EDITAR INVENTARIO///////////////////////////////////////////////////

$route['editar_inventario']						= 'inventario/editar_inventario';
$route['validar_edicion_producto']				= 'inventario/validar_edicion_producto';
$route['procesando_servidor_cambio']			= 'inventario/procesando_servidor_cambio';

$route['validar_impresion']						= 'inventario/validar_impresion';

$route['cargar_dependencia_estatus']    = 'inventario/cargar_dependencia_estatus';


$route['impresion_etiquetas/(:any)']    = 'pdfs/impresion_etiquetas/$1';





/////////////////////////////////////////////Listado de todas las reportes
$route['reportes']						= 'reportes/listado_reportes';
$route['procesando_reporte']    		= 'reportes/procesando_reporte';
$route['procesando_detalle_reporte']    		= 'reportes/procesando_detalle_reporte'; //nuevo

$route['listado_notas']    = 'reportes/listado_notas';
$route['listado_salidas']    = 'reportes/listado_salidas';

$route['listado_devolucion']    = 'reportes/listado_devolucion';

$route['exportar_reporte']    = 'reportes/exportar_reporte';

$route['procesando_historico_entrada']    = 'reportes/procesando_historico_entrada';
$route['procesando_historico_devolucion']    = 'reportes/procesando_historico_devolucion';
$route['procesando_historico_salida']    = 'reportes/procesando_historico_salida';


$route['existencias_baja']						= 'reportes/existencias_baja';
$route['procesando_existencias_baja']			= 'reportes/procesando_existencias_baja';

$route['cargar_dependencia_reporte']   = 'reportes/cargar_dependencia_reporte';


/////////////////////////////////////////////Listado de todas las reportes
//$route['pedido_compra']								= 'pedido_compra/modulo_pedido_compra';
$route['nuevo_pedido_compra/(:any)']						= 'pedido_compra/nuevo_pedido_compra/$1';
$route['procesando_entrada_pedido_compra']			= 'pedido_compra/procesando_entrada_pedido_compra';
$route['procesando_salida_pedido_compra']			= 'pedido_compra/procesando_salida_pedido_compra';
$route['agregar_salida_compra']						= 'pedido_compra/agregar_salida_compra';
$route['quitar_salida_compra']						= 'pedido_compra/quitar_salida_compra';
$route['cargar_dependencia_compra']					= 'pedido_compra/cargar_dependencia_compra';
$route['proc_pedido_compra']						= 'pedido_compra/proc_pedido_compra';

$route['pendiente_revision']							= 'pedido_compra/pendiente_revision';
$route['solicitar_modificacion']						= 'pedido_compra/solicitar_modificacion';
$route['aprobado']										= 'pedido_compra/aprobado';
$route['cancelado']										= 'pedido_compra/cancelado';
$route['gestionar_pedido_compra']						= 'pedido_compra/gestionar_pedido_compra';

$route['procesando_pedido_compra']						= 'pedido_compra/procesando_pedido_compra';
$route['detalle_revision/(:any)/(:any)']				= 'pedido_compra/detalle_revision/$1/$2';

$route['procesando_revisar_pedido_compra']				= 'pedido_compra/procesando_revisar_pedido_compra';

$route['cancelar_pedido_compra/(:any)/(:any)']					= 'pedido_compra/cancelar_pedido_compra/$1/$2';

$route['validar_cancelar_pedido_compra']				= 'pedido_compra/validar_cancelar_pedido_compra';
$route['proc_pedido_cambio']						= 'pedido_compra/proc_pedido_cambio';								

$route['proc_pedido_aprobado']						= 'pedido_compra/proc_pedido_aprobado';								


$route['pedido_compra_modal/(:any)/(:any)/(:any)/(:any)']					= 'pedido_compra/pedido_compra_modal/$1/$2/$3/$4';

$route['confirmar_pedido_compra']					= 'pedido_compra/confirmar_pedido_compra';

$route['impresion_reporte_compra']					= 'pedido_compra/impresion_reporte_compra';
$route['exportar_reportes_compra']					= 'pedido_compra/exportar_reportes_compra';

$route['notificacion_compra']					= 'pedido_compra/notificacion_compra';



/////////////////////////////////////////////Listado de todas las reportes
$route['traspasos']								= 'traspaso/modulo_traspaso';
$route['borrardatos']								= 'traspaso/borrardatos';


$route['procesando_entrada_traspaso']			= 'traspaso/procesando_entrada_traspaso';
$route['procesando_salida_traspaso']			= 'traspaso/procesando_salida_traspaso';



$route['listado_traspaso']						= 'traspaso/listado_traspaso';
$route['procesando_general_traspaso']			= 'traspaso/procesando_general_traspaso';
$route['procesando_traspaso_historico']			= 'traspaso/procesando_traspaso_historico';
$route['traspaso_detalle/(:any)/(:any)']				= 'traspaso/traspaso_detalle/$1/$2';
$route['traspaso_historico_detalle']			= 'traspaso/traspaso_historico_detalle';
$route['procesando_traspaso_general_detalle']					= 'traspaso/procesando_traspaso_general_detalle';
$route['procesando_traspaso_general_detalle_manual']					= 'traspaso/procesando_traspaso_general_detalle_manual';

$route['traspaso_general_detalle/(:any)/(:any)/(:any)']			= 'traspaso/traspaso_general_detalle/$1/$2/$3';

$route['traspaso_general_detalle_manual/(:any)/(:any)']	= 'traspaso/traspaso_general_detalle_manual/$1/$2';

$route['imprimir_detalle_general_traspaso/(:any)/(:any)/(:any)/(:any)']	= 'traspaso/imprimir_detalle_general_traspaso/$1/$2/$3/$4';
$route['imprimir_detalle_general_traspaso_manual/(:any)/(:any)/(:any)']	= 'traspaso/imprimir_detalle_general_traspaso_manual/$1/$2/$3';



$route['imprimir_detalle_historico_traspaso/(:any)/(:any)']	= 'traspaso/imprimir_detalle_historico_traspaso/$1/$2';

$route['agregar_prod_salida_traspaso']			= 'traspaso/agregar_prod_salida_traspaso';
$route['quitar_prod_salida_traspaso']			= 'traspaso/quitar_prod_salida_traspaso';

$route['imprimir_detalle_traspaso_post']			= 'traspaso/imprimir_detalle_traspaso_post';
$route['procesando_traspaso_definitivo']			= 'traspaso/procesando_traspaso_definitivo';

$route['impresion_traspaso_historico']			= 'traspaso/impresion_traspaso_historico';


/////////////////////////////Costo de Inventarios////////////////////////

$route['costo_inventario']						= 'reportes/costo_inventario';
$route['procesando_costo_inventario']			= 'reportes/procesando_costo_inventario';

//costo por rollo
$route['costo_rollo']						= 'reportes/costo_rollo';
$route['procesando_costo_rollo']			= 'reportes/procesando_costo_rollo';


///ctas por pagar



/////////////////////////////CTAS POR PAGAR////////////////////////

$route['listado_ctasxpagar']						= 'ctasxpagar/listado_ctasxpagar';
$route['procesando_ctas_vencidas']			= 'ctasxpagar/procesando_ctas_vencidas';
$route['procesando_ctasxpagar']			= 'ctasxpagar/procesando_ctasxpagar';
$route['procesando_ctas_pagadas']			= 'ctasxpagar/procesando_ctas_pagadas';
$route['procesar_ctasxpagar/(:any)/(:any)/(:any)']    = 'ctasxpagar/procesar_ctasxpagar/$1/$2/$3';
$route['procesando_pagos_realizados']			= 'ctasxpagar/procesando_pagos_realizados';

$route['editar_pago_realizado/(:any)/(:any)/(:any)']			= 'ctasxpagar/editar_pago_realizado/$1/$2/$3';
$route['nuevo_pago/(:any)/(:any)']			= 'ctasxpagar/nuevo_pago/$1/$2';

$route['validacion_nuevo_ctasxpagar']			= 'ctasxpagar/validacion_nuevo_ctasxpagar';
$route['validacion_edicion_ctasxpagar']			= 'ctasxpagar/validacion_edicion_ctasxpagar';

$route['eliminar_pago/(:any)/(:any)/(:any)/(:any)']			= 'ctasxpagar/eliminar_pago/$1/$2/$3/$4';
$route['validar_eliminar_pago']			= 'ctasxpagar/validar_eliminar_pago';

$route['impresion_ctasxpagar']			= 'ctasxpagar/impresion_ctasxpagar';
$route['exportar_ctasxpagar']			= 'ctasxpagar/exportar_ctasxpagar';

$route['impresion_ctas_especificas']			= 'ctasxpagar/impresion_ctas_especificas';
$route['impresion_ctas_detalladas']			= 'ctasxpagar/impresion_ctas_detalladas';
$route['impresion_ctas_antiguedad']			= 'ctasxpagar/impresion_ctas_antiguedad';


$route['impresion_ctas_especificas_rapida']			= 'ctasxpagar/impresion_ctas_especificas_rapida';
$route['impresion_ctas_detalladas_rapida']			= 'ctasxpagar/impresion_ctas_detalladas_rapida';
$route['impresion_ctas_antiguedad_rapida']			= 'ctasxpagar/impresion_ctas_antiguedad_rapida';

$route['impresion_ctas_detalle']			= 'ctasxpagar/impresion_ctas_detalle';






/////////////////////////////dependencias////////////////////////

$route['cargar_dependencia']   = 'inventario/cargar_dependencia';

/////////////////////////////Conteo de inventarios////////////////////////

$route['informe_pendiente']   			= 'conteo_fisico/informe_pendiente';
$route['procesando_informe_pendiente']  = 'conteo_fisico/procesando_informe_pendiente';
$route['procesar_conteo/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)']	 			= 'conteo_fisico/procesar_conteo/$1/$2/$3/$4/$5/$6/$7/$8';

		
$route['confirmar_proceso_conteo']  = 'conteo_fisico/confirmar_proceso_conteo';
$route['procesando_conteos']  = 'conteo_fisico/procesando_conteos';
$route['conteo1']   			= 'conteo_fisico/conteo1';
$route['conteo2']   			= 'conteo_fisico/conteo2';
$route['conteo3']   			= 'conteo_fisico/conteo3';

$route['conteos_opciones']   			= 'conteo_fisico/conteos_opciones';




$route['procesar_contando/(:any)/(:any)']	 			= 'conteo_fisico/procesar_contando/$1/$2';
$route['confirmar_procesar_contando']  = 'conteo_fisico/confirmar_procesar_contando';

$route['procesar_por_conteo']	 			= 'conteo_fisico/procesar_por_conteo';


$route['faltante']   			= 'conteo_fisico/faltante';
$route['sobrante']   			= 'conteo_fisico/sobrante';
$route['procesando_ajustes']  = 'conteo_fisico/procesando_ajustes';

$route['salida_faltante/(:any)/(:any)']  = 'conteo_fisico/salida_faltante/$1/$2';
$route['entrada_sobrante/(:any)/(:any)'] = 'conteo_fisico/entrada_sobrante/$1/$2';

$route['procesando_servidor_ajustes']  = 'conteo_fisico/procesando_servidor_ajustes';

$route['validar_proceso_sobrante']  = 'conteo_fisico/validar_proceso_sobrante';

$route['procesando_temporales_sobrante']  = 'conteo_fisico/procesando_temporales_sobrante';

$route['agregar_salida_faltante'] 	 = 'conteo_fisico/agregar_salida_faltante';

$route['procesando_salida_ajuste'] 	 = 'conteo_fisico/procesando_salida_ajuste';

$route['quitar_salida_ajuste'] 	 = 'conteo_fisico/quitar_salida_ajuste';

$route['procesando_salida_ajuste_definitivo'] 	 = 'conteo_fisico/procesando_salida_ajuste_definitivo';
$route['generar_conteos/(:any)/(:any)/(:any)'] 	 = 'conteo_fisico/generar_conteos/$1/$2/$3';



$route['resumen_conteo'] 	 			= 'conteo_fisico/resumen_conteo';
$route['procesando_resumen_conteo'] 	= 'conteo_fisico/procesando_resumen_conteo';
$route['resumiendo_conteo'] 	 		= 'conteo_fisico/resumiendo_conteo';

$route['cargar_dependencia_existente'] 	 		= 'conteo_fisico/cargar_dependencia_existente';



			//historico conteo

$route['historico_conteo']   				= 'conteo_fisico/historico_conteo';
$route['procesando_historico_conteo']   	= 'conteo_fisico/procesando_historico_conteo';


$route['historico_conteo1/(:any)/(:any)'] 	 = 'conteo_fisico/historico_conteo1/$1/$2';
$route['historico_conteo2/(:any)/(:any)'] 	 = 'conteo_fisico/historico_conteo2/$1/$2';
$route['historico_conteo3/(:any)/(:any)'] 	 = 'conteo_fisico/historico_conteo3/$1/$2';

$route['procesando_conteo_historico']   	= 'conteo_fisico/procesando_conteo_historico';

$route['generar_conteos_historico/(:any)/(:any)/(:any)'] 	 = 'conteo_fisico/generar_conteos_historico/$1/$2/$3';


//$route['generar_historico_inventarios/(:any)'] 	 = 'conteo_fisico/generar_historico_inventarios/$1';

$route['generar_historico_inventarios'] 	 = 'conteo_fisico/generar_historico_inventarios';


$route['almacen_ajuste_conteo']   	= 'conteo_fisico/almacen_ajuste_conteo';

/////////////////////////////new_implementacion////////////////////////

$route['imprimir_reportes']    = 'pdf_reportes/imprimir_reportes';
$route['imprimir_rapida']    = 'pdf_reportes/imprimir_rapida';

$route['procesando_cat_producto']    = 'catalogos/procesando_cat_producto';

$route['procesando_cat_colores']    = 'catalogos/procesando_cat_colores';

$route['procesando_cat_cargadores']    = 'catalogos/procesando_cat_cargadores';

$route['procesando_cat_composiciones']    = 'catalogos/procesando_cat_composiciones';
$route['procesando_cat_calidades']    = 'catalogos/procesando_cat_calidades';

$route['procesando_cat_proveedores']    = 'catalogos/procesando_cat_proveedores';

$route['cargar_dependencia_catalogo']   = 'catalogos/cargar_dependencia_catalogo';
$route['marcando_activo']    			= 'catalogos/marcando_activo';


/////////////////////////////Exportar////////////////////////

$route['exportar_reportes']    = 'exportar_reportes/exportar';

/////////////////////////////Consultas////////////////////////

$route['consulta_proveedor']    = 'consultas/consulta_proveedor';
$route['procesando_consulta_proveedor']    = 'consultas/procesando_consulta_proveedor';


$route['consulta_producto']    = 'consultas/consulta_producto';
$route['procesando_consulta_producto']    = 'consultas/procesando_consulta_producto';
$route['cargar_dependencia_producto']    = 'consultas/cargar_dependencia_producto';


//totales
$route['consulta_totales']    = 'consultas/consulta_totales';
$route['procesando_consulta_totales']    = 'consultas/procesando_consulta_totales';

$route['cargar_dependencia_totales']   = 'consultas/cargar_dependencia_totales';

$route['imprimir_totales']   = 'pdf_reportes/imprimir_totales';

$route['exportar_totales']    = 'exportar_reportes/exportar_totales';





/////////////////////////////////////////////Listado de todas las entradas

$route['transferencia_recibida']						= 'transferencias/transferencia_recibida';


$route['procesando_transferencia_recibida']						= 'transferencias/procesando_transferencia_recibida';

$route['validar_proceso_transferencia']						= 'transferencias/validar_proceso_transferencia';

$route['procesar_entrar_transferencia/(:any)/(:any)/(:any)']   				 = 'transferencias/procesar_entrar_transferencia/$1/$2/$3';


/////////////////////////////////////////////Entrada Transferencia recibida de bodega

$route['transferencia_bodega']						= 'entrada_bodega/transferencia_bodega';
$route['procesando_transferencia_bodega']						= 'entrada_bodega/procesando_transferencia_bodega';
$route['validar_proceso_bodega']						= 'entrada_bodega/validar_proceso_bodega';
$route['procesar_entrar_bodega/(:any)/(:any)']   				 = 'entrada_bodega/procesar_entrar_bodega/$1/$2';



//recepcion, devolucion_venta, transferencia_salida, ajuste_positivo

//operaciones de entradas
/*
$route['validar_agregar_producto']          = 'entradas/validar_agregar_producto';
$route['listado_temporal']          = 'entradas/listado_temporal';


$route['recepciones']					     = 'entradas/listado_recepciones';
$route['nuevo_recepcion']                  = 'entradas/nuevo_recepcion';
$route['editar_recepcion/(:any)']      = 'entradas/editar_recepcion/$1';
$route['validacion_edicion_recepcion']     = 'entradas/validacion_edicion_recepcion';


$route['procesando_productos_temporales']    		= 'entradas/procesando_productos_temporales';




$route['eliminar_prod_temporal/(:any)/(:any)']       = 'entradas/eliminar_prod_temporal/$1/$2';
$route['validar_eliminar_prod_temporal']    = 'entradas/validar_eliminar_prod_temporal';


//procesamiento de entrada
$route['procesar_entradas/(:any)/(:any)/(:any)/(:any)/(:any)']    = 'entradas/procesar_entradas/$1/$2/$3/$4/$5';

$route['procesar_entrar/(:any)/(:any)/(:any)']    = 'entradas/procesar_entrar/$1/$2/$3';

$route['validar_proceso']    = 'entradas/validar_proceso';


$route['generar_etiquetas/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_etiquetas/$1/$2/$3/$4';
$route['generar_etiquetas_rapida/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_etiquetas_rapida/$1/$2/$3/$4';
    $route['generar_notas/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_notas/$1/$2/$3/$4';
    $route['generar_notas_rapida/(:any)/(:any)/(:any)/(:any)']    = 'pdfs/generar_notas_rapida/$1/$2/$3/$4';

$route['pdfs']    = 'pdfs/index';
*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */