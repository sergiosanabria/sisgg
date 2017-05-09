<?php

namespace SisGG\FinalBundle\Autenticacion;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\Rol;
use SisGG\FinalBundle\Entity\Permiso;

/**
 * @author martin
 */
class GestorAutenticacion {
    /* Constantes de Autenticación */

    const ACCESS_GRANTED = true;
    const ACCESS_DENIED = false;
    /* Objetos_Acciones */
    /* Pedido */
    const PEDIDO_LISTAR = 'pedido_listar';
    const PEDIDO_NUEVO = 'pedido_nuevo';
    const PEDIDO_EDITAR = 'pedido_editar';
    const PEDIDO_BORRAR = 'pedido_borrar';
    /* CuentaCorriente */
    const CUENTA_CORRIENTE_LISTAR = 'cuenta_corriente_listar';
    const CUENTA_CORRIENTE_NUEVO = 'cuenta_corriente_nuevo';
    const CUENTA_CORRIENTE_EDITAR = 'cuenta_corriente_editar';
    const CUENTA_CORRIENTE_BORRAR = 'cuenta_corriente_borrar';
    /* Sectores */
    const SECTOR_LISTAR = 'sector_listar';
    const SECTOR_NUEVO = 'sector_nuevo';
    const SECTOR_EDITAR = 'sector_editar';
    const SECTOR_BORRAR = 'sector_borrar';
    /* GrupoCliente */
    const GRUPO_CLIENTE_LISTAR = 'grupo_cliente_listar';
    const GRUPO_CLIENTE_NUEVO = 'grupo_cliente_nuevo';
    const GRUPO_CLIENTE_EDITAR = 'grupo_cliente_editar';
    const GRUPO_CLIENTE_BORRAR = 'grupo_cliente_borrar';
    /* Recargo */
    const RECARGO_LISTAR = 'recargo_listar';
    const RECARGO_NUEVO = 'recargo_nuevo';
    const RECARGO_EDITAR = 'recargo_editar';
    const RECARGO_BORRAR = 'recargo_borrar';   
    /* Descuento */
    const DESCUENTO_LISTAR = 'descuento_listar';
    const DESCUENTO_NUEVO = 'descuento_nuevo';
    const DESCUENTO_EDITAR = 'descuento_editar';
    const DESCUENTO_BORRAR = 'descuento_borrar';  
    /* Envio */
    const ENVIO_LISTAR = 'envio_listar';
    const ENVIO_NUEVO = 'envio_nuevo';
    const ENVIO_EDITAR = 'envio_editar';
    const ENVIO_BORRAR = 'envio_borrar';  
    /* Cocina */
    const COCINA_GESTIONAR = 'cocina_gestionar';
    /* Cliente */
    const CLIENTE_LISTAR = 'cliente_listar';
    const CLIENTE_NUEVO = 'cliente_nuevo';
    const CLIENTE_EDITAR = 'cliente_editar';
    const CLIENTE_BORRAR = 'cliente_borrar';
    /* Ventas */
    const VENTA_LISTAR = 'venta_listar';
    const VENTA_NUEVO = 'venta_nuevo';
    const VENTA_EDITAR = 'venta_editar';
    const VENTA_BORRAR = 'venta_borrar';
    /* Cajas */
    const CAJA_APERTURA_Y_CIERRRE = 'caja_apertura_y_cierre';
    /* Securidad */
    const USUARIO_LISTAR = 'usuario_listar';
    const USUARIO_NUEVO = 'usuario_nuevo';
    const USUARIO_EDITAR = 'usuario_editar';
    const USUARIO_BORRAR = 'usuario_borrar';
    const USUARIO_ACTIVAR = 'usuario_activar';
    const USUARIO_PERFIL = 'usuario_ver_Perfil';
    const USUARIO_IMPRIMIR = 'usuario_imprimir';
    const USUARIO_CAMBIARCONTRASENIA = 'usuario_cambiar_Contrseña';
    const USUARIO_RECUPERARCONTRASENIA = 'usuario_recuperar_Contrseña';
    const ROl_LISTAR = 'rol_listar';
    const ROl_NUEVO = 'rol_nuevo';
    const ROl_EDITAR = 'rol_editar';
    const ROl_BORRAR = 'rol_borrar';
    const ROl_ACTIVAR = 'rol_activar';
    /* Tipo de Cobro */
    const TIPOCOBRO_LISTAR = 'tipoCobro_listar';
    const TIPOCOBRO_NUEVO = 'tipoCobro_nuevo';
    const TIPOCOBRO_EDITAR = 'tipoCobro_editar';
    const TIPOCOBRO_BORRAR = 'tipoCobro_borrar';
    const TIPOCOBRO_ACTIVAR = 'tipoCobro_activar';
    /* Tipo de Documento */
    const TIPODOCUMENTO_LISTAR = 'tipoDocumento_listar';
    const TIPODOCUMENTO_NUEVO = 'tipoDocumento_nuevo';
    const TIPODOCUMENTO_EDITAR = 'tipoDocumento_editar';
    const TIPODOCUMENTO_BORRAR = 'tipoDocumento_borrar';
    /* Configuración */
    // const EMPRESA_VER = 'empresa_ver';
    // const EMPRESA_EDITAR = 'empresa_editar';
    //Auditoria
    const AUDITORIA_LISTAR = 'auditoria_listar';
    const AUDITORIA_DESCARGARARCHIVO = 'auditoria_descargar_archivo';
    const AUDITORIA_ELEGIR_CARPETA = 'auditoria_elegir_carpeta';
    const AUDITORIA_IMPRIMIR = 'auditoria_imprimir';
    /* Agenda */
    const GESTION_AGENDA = 'agenda_gestionar_agenda';
    //Backup
    const BACKUP_LISTAR = 'backup_listar';
    const BACKUP_REGISTRAR = 'backup_registrar';
    const BACKUP_IMPRIMIR = 'backup_imprimir';
    const BACKUP_DESCARGAR = 'backup_descargar';
    const BACKUP_ELEGIR_CARPETA = 'backup_elegir_carpeta';
    //Cargo
    const CARGO_LISTAR = 'cargo_listar';
    const CARGO_NUEVO = 'cargo_nuevo';
    const CARGO_EDITAR = 'cargo_editar';
    const CARGO_ACTIVAR = 'cargo_activar';
    const CARGO_BORRAR = 'cargo_borrar';
    //CategoriaPP
    const CATEGORIAPP_NUEVO = 'categoriaProductoProduccion_nuevo';
    const CATEGORIAPP_EDITAR = 'categoriaProductoProduccion_editar';
    const CATEGORIAPP_BORRAR = 'categoriaProductoProduccion_borrar';
    const CATEGORIAPP_LISTAR = 'categoriaProductoProduccion_listar';
    //CategoriaPV
    const CATEGORIAPV_NUEVO = 'categoriaProductoVenta_nuevo';
    const CATEGORIAPV_EDITAR = 'categoriaProductoVenta_editar';
    const CATEGORIAPV_BORRAR = 'categoriaProductoVenta_borrar';
    const CATEGORIAPV_LISTAR = 'categoriaProductoVenta_listar';
    /* Chat */
    const GESTION_CHAT = 'chat_gestionar_chat';
    //Ciudad
    const CIUDAD_NUEVO = 'ciudad_nuevo';
    const CIUDAD_EDITAR = 'ciudad_editar';
    const CIUDAD_BORRAR = 'ciudad_borrar';
    const CIUDAD_LISTAR = 'ciudad_listar';
    //Compra
    const COMPRA_NUEVO = 'compra_nuevo';
    const COMPRA_PAGO_POR_FACTURA = 'compra_pago_por_factura';
    const COMPRA_PAGO_POR_PROVEEDOR = 'compra_pago_por_proveedor';
    const COMPRA_LISTAR = 'compra_listar';
    const COMPRA_IMPRIMIR = 'compra_imprimir';
     //CONDICIONESIVA
    const CONDICIONESIVA_NUEVO = 'condiciones IVA_nuevo';
    const CONDICIONESIVA_EDITAR = 'condiciones IVA_editar';
    const CONDICIONESIVA_BORRAR = 'condiciones IVA_borrar';
    const CONDICIONESIVA_LISTAR = 'condiciones IVA_listar';
    const CONDICIONESIVA_ACTIVAR = 'condiciones IVA_activar';
    //Empresa
    const EMPRESA_EDITAR = 'empresa_editar';
    //FacturaServicio
    const FACTURASERVICIO_NUEVO = 'facturaServicio_nuevo';
    const FACTURASERVICIO_LISTAR = 'facturaServicio_listar';
    const FACTURASERVICIO_IMPRIMIR = 'facturaServicio_imprimir';
    //IVA
    const IVA_NUEVO = 'IVA_nuevo';
    const IVA_EDITAR = 'IVA_editar';
    const IVA_BORRAR = 'IVA_borrar';
    const IVA_LISTAR = 'IVA_listar';
    const IVA_ACTIVAR = 'IVA_activar';
    //INGREDIENTE
    const INGREDIENTE_NUEVO = 'ingrediente_nuevo';
    const INGREDIENTE_EDITAR = 'ingrediente_editar';
    const INGREDIENTE_BORRAR = 'ingrediente_borrar';
    //LIC
    const LIBROIVACOMPRA_LISTAR = 'LibroIVACompra_listar';
    const LIBROIVACOMPRA_IMPRIMIR = 'LibroIVACompra_imprimir';
    //MANTENIMIENTO
    const MANTENIMIENTO_NUEVO = 'mantenimiento_nuevo';
    const MANTENIMIENTO_EDITAR = 'mantenimiento_editar';
    const MANTENIMIENTO_BORRAR = 'mantenimiento_borrar';
    const MANTENIMIENTO_LISTAR = 'mantenimiento_listar';
    //MATERIAPRIMA
    const MATERIAPRIMA_NUEVO = 'materiaPrima_nuevo';
    const MATERIAPRIMA_EDITAR = 'materiaPrima_editar';
    const MATERIAPRIMA_BORRAR = 'materiaPrima_borrar';
    const MATERIAPRIMA_LISTAR = 'materiaPrima_listar';
    //MERCADERIA
    const MERCADERIA_NUEVO = 'mercaderia_nuevo';
    const MERCADERIA_EDITAR = 'mercaderia_editar';
    const MERCADERIA_BORRAR = 'mercaderia_borrar';
    const MERCADERIA_LISTAR = 'mercaderia_listar';
    //NOTAPEDIDO
    const NOTAPEDIDO_NUEVO = 'notaPedido_nuevo';
    const NOTAPEDIDO_LISTAR = 'notaPedido_listar';
    const NOTAPEDIDO_IMPRIMIR = 'notaPedido_imprimir';
    const NOTAPEDIDO_EDITAR = 'notaPedido_editar';
      //OPERACIONES
    const OPERACIONES_NUEVO = 'operaciones_nuevo';
    const OPERACIONES_EDITAR = 'operaciones_editar';
    const OPERACIONES_BORRAR = 'operaciones_borrar';
    const OPERACIONES_LISTAR = 'operaciones_listar';
    const OPERACIONES_ACTIVAR = 'operaciones_activar';
    //PAGO
    const PAGO_REGISTRAR = 'pago_registrar_salidad_de_caja';
    const PAGO_LISTAR = 'pago_listar';
    const PAGO_IMPRIMIR = 'pago_imprimir';
    //PERSONAE
    const PERSONAE_NUEVO = 'empleado_nuevo';
    const PERSONAE_EDITAR = 'empleado_editar';
    const PERSONAE_BORRAR = 'empleado_borrar';
    const PERSONAE_LISTAR = 'empleado_listar';
    const PERSONAE_ACTIVAR = 'empleado_activar';
    const PERSONAE_DETALLES = 'empleado_detalles-Acciones_de_pago';
    const PERSONAE_GESTION = 'empleado_Gestión_y_acciones_de_pago';
    const PERSONAE_IMP_DETALLES = 'empleado-Imprimir_detalles-Acciones_de_pago';
    const PERSONAE_IMP_COMPROBANTE = 'empleado-Imprimir_comprobante_de_pago';
    const PERSONAE_IMP_LISTA = 'empleado-Imprimir_lista_de_empleados';
    //PLATO
    const PLATO_NUEVO = 'plato_nuevis';
    const PLATO_EDITAR = 'plato_editar';
    const PLATO_BORRAR = 'plato_borrar';
    const PLATO_LISTAR = 'plato_listar';
    //PRE-ELABORADO
    const PREELABORADO_NUEVO = 'pre-Elaborado_nuevo';
    const PREELABORADO_EDITAR = 'pre-Elaborado_editar';
    const PREELABORADO_BORRAR = 'pre-Elaborado_borrar';
    const PREELABORADO_LISTAR = 'pre-Elaborado_listar';
    //Producto
    const PRODUCTO_LISTAR_PRODUCTPVENTA = 'producto_listar_producto_venta';
    const PRODUCTO_LISTAR_PRODUCTOPRODUCCION = 'producto_listar_producto_prodccion';
    const PRODUCTO_ACTIVAR = 'producto_activar';
    const PRODUCTO_GESTIONMENU = 'producto_Gestión de menu';
    const PRODUCTO_IMPRIMIR = 'producto_imprimir';
    //PROVEEDOR
    const PROVEEDOR_NUEVO = 'proveedor_nuevo';
    const PROVEEDOR_EDITAR = 'proveedor_editar';
    const PROVEEDOR_BORRAR = 'proveedor_borrar';
    const PROVEEDOR_LISTAR = 'proveedor_listar';
    const PROVEEDOR_ACTIVAR = 'proveedor_activar';
    const PROVEEDOR_IMPRIMIR = 'proveedor_imprimir';
    //Provincia
    const PROVINCIA_NUEVO = 'provincia_nuevo';
    const PROVINCIA_EDITAR = 'provincia_editar';
    const PROVINCIA_BORRAR = 'provincia_borrar';
    const PROVINCIA_LISTAR = 'provincia_listar';
    //Registros
    const REGISTRO_CANTIDAD = 'registro_registro_producción_por_cantidad';
    const REGISTRO_INGREDIENTE = 'registro_registro_producción_por_ingredientes';
    const REGISTRO_PERDIDA = 'registro_registro_pérdida_de_producción';
    const REGISTRO_LISTAR = 'registro_listar';
    const REGISTRO_IMPRIMIR = 'registro_imprimir';
    //SERVICIO
    const SERVICIO_NUEVO = 'servicio_nuevo';
    const SERVICIO_EDITAR = 'servicio_editar';
    const SERVICIO_BORRAR = 'servicio_borrar';
    const SERVICIO_LISTAR = 'servicio_listar';
    const SERVICIO_ACTIVAR = 'servicio_activar';
    const SERVICIO_IMPRIMIR = 'servicio_imprimir';
    //UM
    const UM_LISTAR = 'unidad de medida y tasas_listar';
    const UM_GESTION = 'unidad de medida y tasas_Gestion';
    //NOTIFICACIONES DE SISTEMA
    const NOTI_MOSTRAR ='notificaciones de sistema_Mostrar';
    
    /* Variables del Servicio */

    var $em = null;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function isGranted(Usuario $user, $objeto) {
        foreach ($user->getRole()->getPermisos() as $permiso) {
            /* @var $permiso Permiso */
            if ($permiso->getObjeto() == $objeto) {
                if ($permiso->getOtorgado()) {
                    return GestorAutenticacion::ACCESS_GRANTED;
                } else {
                    return GestorAutenticacion::ACCESS_DENIED;
                }
            }
        }
    }

    public function nuevoRol() {
        $retorno = new Rol();
        $this->addObjeto($retorno, GestorAutenticacion::AUDITORIA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::AUDITORIA_ELEGIR_CARPETA);
        $this->addObjeto($retorno, GestorAutenticacion::AUDITORIA_DESCARGARARCHIVO);
        $this->addObjeto($retorno, GestorAutenticacion::AUDITORIA_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::GESTION_AGENDA);
        $this->addObjeto($retorno, GestorAutenticacion::BACKUP_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::BACKUP_REGISTRAR);
        $this->addObjeto($retorno, GestorAutenticacion::BACKUP_DESCARGAR);
        $this->addObjeto($retorno, GestorAutenticacion::BACKUP_ELEGIR_CARPETA);
        $this->addObjeto($retorno, GestorAutenticacion::BACKUP_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::CARGO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CARGO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CARGO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CARGO_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::CARGO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPP_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPP_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPP_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPP_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPV_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPV_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPV_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CATEGORIAPV_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::GESTION_CHAT);
        $this->addObjeto($retorno, GestorAutenticacion::CIUDAD_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CIUDAD_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CIUDAD_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CIUDAD_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::COMPRA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::COMPRA_PAGO_POR_PROVEEDOR);
        $this->addObjeto($retorno, GestorAutenticacion::COMPRA_PAGO_POR_FACTURA);
        $this->addObjeto($retorno, GestorAutenticacion::COMPRA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::COMPRA_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::CONDICIONESIVA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CONDICIONESIVA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CONDICIONESIVA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CONDICIONESIVA_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::CONDICIONESIVA_BORRAR);
        
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_DETALLES);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_GESTION);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_IMP_LISTA);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_IMP_COMPROBANTE);
        $this->addObjeto($retorno, GestorAutenticacion::PERSONAE_IMP_DETALLES);

        $this->addObjeto($retorno, GestorAutenticacion::EMPRESA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::FACTURASERVICIO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::FACTURASERVICIO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::FACTURASERVICIO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::IVA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::IVA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::IVA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::IVA_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::IVA_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::INGREDIENTE_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::INGREDIENTE_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::INGREDIENTE_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::LIBROIVACOMPRA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::LIBROIVACOMPRA_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::MANTENIMIENTO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::MANTENIMIENTO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::MANTENIMIENTO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::MANTENIMIENTO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::MATERIAPRIMA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::MATERIAPRIMA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::MATERIAPRIMA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::MATERIAPRIMA_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::MERCADERIA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::MERCADERIA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::MERCADERIA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::MERCADERIA_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::NOTAPEDIDO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::NOTAPEDIDO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::NOTAPEDIDO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::NOTAPEDIDO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::OPERACIONES_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::OPERACIONES_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::OPERACIONES_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::OPERACIONES_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::OPERACIONES_BORRAR);
        
        $this->addObjeto($retorno, GestorAutenticacion::PAGO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PAGO_REGISTRAR);
        $this->addObjeto($retorno, GestorAutenticacion::PAGO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::PLATO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PLATO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PLATO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PLATO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::PREELABORADO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PREELABORADO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PREELABORADO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PREELABORADO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::PRODUCTO_LISTAR_PRODUCTPVENTA);
        $this->addObjeto($retorno, GestorAutenticacion::PRODUCTO_LISTAR_PRODUCTOPRODUCCION);
        $this->addObjeto($retorno, GestorAutenticacion::PRODUCTO_GESTIONMENU);
        $this->addObjeto($retorno, GestorAutenticacion::PRODUCTO_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::PRODUCTO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVEEDOR_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVINCIA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVINCIA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PROVINCIA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PROVINCIA_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::REGISTRO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::REGISTRO_CANTIDAD);
        $this->addObjeto($retorno, GestorAutenticacion::REGISTRO_INGREDIENTE);
        $this->addObjeto($retorno, GestorAutenticacion::REGISTRO_PERDIDA);
        $this->addObjeto($retorno, GestorAutenticacion::REGISTRO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::SERVICIO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::UM_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::UM_GESTION);
        $this->addObjeto($retorno, GestorAutenticacion::NOTI_MOSTRAR);
        $this->addObjeto($retorno, GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE);
        $this->addObjeto($retorno, GestorAutenticacion::CLIENTE_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CLIENTE_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CLIENTE_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CLIENTE_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::COCINA_GESTIONAR);
        $this->addObjeto($retorno, GestorAutenticacion::PEDIDO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::PEDIDO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::PEDIDO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::PEDIDO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::ROl_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::ROl_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::ROl_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::ROl_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::ROl_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPOCOBRO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPOCOBRO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::TIPOCOBRO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPOCOBRO_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPOCOBRO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPODOCUMENTO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPODOCUMENTO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::TIPODOCUMENTO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::TIPODOCUMENTO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_ACTIVAR);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_PERFIL);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_CAMBIARCONTRASENIA);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_RECUPERARCONTRASENIA);
        $this->addObjeto($retorno, GestorAutenticacion::USUARIO_IMPRIMIR);
        $this->addObjeto($retorno, GestorAutenticacion::VENTA_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::VENTA_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::VENTA_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::VENTA_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::DESCUENTO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::DESCUENTO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::DESCUENTO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::DESCUENTO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::RECARGO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::RECARGO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::RECARGO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::RECARGO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::GRUPO_CLIENTE_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::GRUPO_CLIENTE_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::GRUPO_CLIENTE_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::GRUPO_CLIENTE_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::ENVIO_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::ENVIO_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::ENVIO_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::ENVIO_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::CUENTA_CORRIENTE_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::CUENTA_CORRIENTE_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::CUENTA_CORRIENTE_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::CUENTA_CORRIENTE_BORRAR);
        $this->addObjeto($retorno, GestorAutenticacion::SECTOR_LISTAR);
        $this->addObjeto($retorno, GestorAutenticacion::SECTOR_NUEVO);
        $this->addObjeto($retorno, GestorAutenticacion::SECTOR_EDITAR);
        $this->addObjeto($retorno, GestorAutenticacion::SECTOR_BORRAR);
        return $retorno;
    }

    private function addObjeto(Rol &$rol, $objeto) {
        $permiso = new Permiso();
        $permiso->setObjeto($objeto);
        $permiso->setOtorgado(true);
        $rol->addPermiso($permiso);
    }

    public function hayConexion() {
        $ip = gethostbyname('www.google.com');
        $primero = explode('.', $ip);
        $var = 0;
        $var = $primero[0];
        if (is_numeric($var))
            return true;
        else
            return false;
    }

}

?>