<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\PedidoDelivery;
use SisGG\FinalBundle\Form\PedidoDeliveryType;
use SisGG\FinalBundle\Entity\ItemPedido;
use SisGG\FinalBundle\Form\ItemPedidoType;
use SisGG\FinalBundle\Entity\ProductoVenta;
use SisGG\FinalBundle\Form\ClienteType;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Entity\Ingrediente;
use SisGG\FinalBundle\Form\IngredienteType;
use SisGG\FinalBundle\Form\ProductoVentaType;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Form\PedidoMostradorType;
use SisGG\FinalBundle\Entity\PedidoMostrador;
use SisGG\FinalBundle\Entity\PedidoMesa;
use SisGG\FinalBundle\Form\PedidoMesaType;
use SisGG\FinalBundle\Model\GestorPedidos;
use SisGG\FinalBundle\Form\PedidoMostradorModificadoType;
use SisGG\FinalBundle\Entity\Mesa;
use SisGG\FinalBundle\Form\PedidoType;
use SisGG\FinalBundle\Entity\Recargo;
//use Ps\PdfBundle\Annotation\Pdf;
use Endroid\QrCode\QrCode;

/**
 * Description of Pedido
 *
 * @author martin
 */
class PedidoController extends Controller {

    public function pedidosAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), 'pedido_listar')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $dia = new \DateTime();
        $dia->modify('-1 day');
        $pedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findDelDia($dia);
        $historialPedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findHistorial();
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Sector')->findAll();
        return $this->render('SisGGFinalBundle:Pedido:pedidos.html.twig', array('dia' => $dia, 'pedidos' => $pedidos, 'historialPedidos' => $historialPedidos, "sectores" => $sectores, 'form' => null, 'gestor_autenticacion' => $gestor, 'hayConexion' => $this->hayConexion()));
    }

    public function pedidosMesaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pedidosMesa = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:PedidoMesa')->findAll();
        $parameters = null;
        if ($this->getRequest()->get("vista") === "mesas") {
            $sectores = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Sector')->findAll();
            $parameters = array("form" => null, "pedidoMesa" => $pedidosMesa, "sectores" => $sectores);
        } else {
            $sectores = null;
            $parameters = array("form" => null, "pedidoMesa" => $pedidosMesa, "sectores" => $sectores, 'gestor_autenticacion' => $gestor);
        }
        return $this->render("SisGGFinalBundle:Pedido:verPedidosMesa.html.twig", $parameters);
    }

    function detallesPedidoMostradorDeliveryAction() {
        $pedido = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get("idPedido"));
        return $this->render("SisGGFinalBundle:Pedido:detallesPedidoMostradorDelivery.html.twig", array("pedido" => $pedido));
    }

    function cambiarPedidoMesaAction() {
        /* @var $mesaCambiada Mesa */
        $mesaCambiada = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get("idMesaCambiada"));
        /* @var $mesa Mesa */
        $mesa = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get("idMesa"));
        $mesaCambiada->getPedidoActivo()->cambiarMesa($mesa);
        $this->getDoctrine()->getEntityManager()->flush();
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Sector")->findAll();
        return $this->render("SisGGFinalBundle:Pedido:cambioMesa.html.twig", array("sectores" => $sectores));
    }

    /**
     * Devuelve los campos correspondientes al tipo seleccionado
     */
    function transformarPedidoAction() {
        /* @var $pedido Pedido */
        $unPedido = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new PedidoType($this->getDoctrine()->getEntityManager()), $unPedido);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
        }
        $provincias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Sector")->findAll();
        return $this->render("SisGGFinalBundle:Pedido:transformarPedido.html.twig", array("form" => $form->createView(), "sectores" => $sectores, 'provincias' => $provincias));
    }

    function cambiarPedidoAction() {
        /* @var $pedido Pedido */
        $unPedido = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new PedidoType($this->getDoctrine()->getEntityManager()), $unPedido);
        $form->handleRequest($this->getRequest());
        if ($form->isValid()) {
            $this->get('gestor_pedidos')->cambiarPedido($unPedido);
        }
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Sector")->findAll();
        return $this->render("SisGGFinalBundle:Pedido:transformarPedido.html.twig", array("form" => $form->createView(), "sectores" => $sectores));
    }

    public function detallesPedidoAction() {
        $pedido = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get("idPedido"));
        return $this->render("SisGGFinalBundle:Pedido:detallesPedido.html.twig", array("pedido" => $pedido));
    }

//    /**
//     * @Pdf()
//     */
//    public function imprimirPedidoAction() {
//        /* @var $pedido Pedido */
//        $pedido = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pedido')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array("form" => null, 'pedido' => $pedido);
//        $url = $this->generateUrl("detalles_pedido_mostrdordelivery");
//        $host = "192.168.0.100";
//        $qrCode = new QrCode();
//        $qrCode->setText("http://".$host.$this->generateUrl("nueva_redireccion",array("url"=>$url.'?idPedido='.$pedido->getId())));
//        $qrCode->setSize(300);
//        $qrCode->setPadding(10);
//        $qrCode->render("/tmp/QrCode.png");
//        return $this->render('SisGGFinalBundle:Pedido:imprimirPedido.' . $format . '.twig', $parameters);
//    }

    public function nuevoPedidoMostradorAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        $unPedidoMostrador = new Pedido();
        $unPedidoMostrador->setTipoPedido($this->getDoctrine()->getRepository("SisGGFinalBundle:TipoPedido")->find(2));
        $subtotal = 0.00;
        /* @var $itemPedido ItemPedido */
        foreach ($unPedidoMostrador->getItemPedido() as $itemPedido) {
            $subtotal = $subtotal + $itemPedido->getPrecio();
        }
        $form = $this->createForm(new PedidoMostradorType(), $unPedidoMostrador);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $faltantes = $this->get('gestor_pedidos')->nuevoPedido($unPedidoMostrador, $this->getRequest()->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('pedidos'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $parameters = array('form' => $form->createView(), 'sectores' => $em->getRepository("SisGGFinalBundle:Sector")->findAll(), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'gestor_autenticacion' => $gestor);
        return $this->render('SisGGFinalBundle:Pedido:nuevoPedidoMostrador.html.twig', $parameters);
    }

    public function editarPedidoMostradorAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $gestorPedidos GestorPedidos */
        $gestorPedidos = $this->get('gestor_pedidos');
        $itemsOriginales = array();
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        /* @var $unPedidoMostrador Pedido */
        $unPedidoMostrador = $gestorPedidos->getPedido($this->getRequest()->get("id"));
        foreach ($unPedidoMostrador->getItemPedido() as $item) {
            $itemsOriginales[] = $item;
        }
        $form = $this->createForm(new PedidoMostradorType(), $unPedidoMostrador, array('attr' => array('only_cliente' => false)));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $faltantes = $gestorPedidos->editarPedido($unPedidoMostrador, $itemsOriginales, $this->getRequest()->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('pedidos'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $parameters = array('form' => $form->createView(), 'sectores' => $em->getRepository("SisGGFinalBundle:Sector")->findAll(), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'id' => $this->getRequest()->get("id"), 'gestor_autenticacion' => $gestor);
        return $this->render('SisGGFinalBundle:Pedido:editarPedidoMostrador.html.twig', $parameters);
    }

    public function editarPedidoDeliveryAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $gestorPedidos GestorPedidos */
        $gestorPedidos = $this->get('gestor_pedidos');
        $itemsOriginales = array();
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        $unPedidoDelivery = $gestorPedidos->getPedido($this->getRequest()->get("id"));
        foreach ($unPedidoDelivery->getItemPedido() as $item) {
            $itemsOriginales[] = $item;
        }
        $form = $this->createForm(new PedidoDeliveryType(), $unPedidoDelivery, array('attr' => array('only_cliente' => false)));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $faltantes = $gestorPedidos->editarPedido($unPedidoDelivery, $itemsOriginales, $this->getRequest()->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('pedidos'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $provincias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        $parameters = array('form' => $form->createView(), 'sectores' => $em->getRepository("SisGGFinalBundle:Sector")->findAll(), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'id' => $this->getRequest()->get("id"), 'gestor_autenticacion' => $gestor, 'provincias' => $provincias);
        return $this->render('SisGGFinalBundle:Pedido:editarPedidoDelivery.html.twig', $parameters);
    }

    public function nuevoPedidoMesaAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        $unPedidoMesa = new Pedido();
        $mesa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get('idMesa'));
        $unPedidoMesa->setMesa($mesa);
        $unPedidoMesa->setTipoPedido($this->getDoctrine()->getRepository("SisGGFinalBundle:TipoPedido")->find(1));
        $subtotal = 0.00;
        /* @var $itemPedido ItemPedido */
        foreach ($unPedidoMesa->getItemPedido() as $itemPedido) {
            $subtotal = $subtotal + $itemPedido->getPrecio();
        }
        $form = $this->createForm(new PedidoMesaType(), $unPedidoMesa, array('attr' => array('only_cliente' => false)));
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $faltantes = $this->get('gestor_pedidos')->nuevoPedido($unPedidoMesa, $request->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('sectores_mesas'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $parameters = array('form' => $form->createView(), 'idMesa' => $request->get('idMesa'), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'gestor_autenticacion' => $gestor);
        return $this->render('SisGGFinalBundle:Pedido:nuevoPedidoMesa.html.twig', $parameters);
    }

    public function nuevoPedidoDeliveryAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        $unPedidoDelivery = new Pedido();
        $unPedidoDelivery->setTipoPedido($this->getDoctrine()->getRepository("SisGGFinalBundle:TipoPedido")->find(3));
        $subtotal = 0.00;
        /* @var $itemPedido ItemPedido */
        foreach ($unPedidoDelivery->getItemPedido() as $itemPedido) {
            $subtotal = $subtotal + $itemPedido->getPrecio();
        }
        $form = $this->createForm(new PedidoDeliveryType(), $unPedidoDelivery, array('attr' => array('only_cliente' => false)));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $faltantes = $this->get('gestor_pedidos')->nuevoPedido($unPedidoDelivery, $this->getRequest()->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('pedidos'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $provincias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        $parameters = array('form' => $form->createView(), 'sectores' => $em->getRepository("SisGGFinalBundle:Sector")->findAll(), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'gestor_autenticacion' => $gestor, 'provincias' => $provincias);
        return $this->render('SisGGFinalBundle:Pedido:nuevoPedidoDelivery.html.twig', $parameters);
    }

    public function editarPedidoMesaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $gestorPedidos GestorPedidos */
        $gestorPedidos = $this->get('gestor_pedidos');
        $itemsOriginales = array();
        $idSolicitud = 0;
        $faltantes = array(); //Inicializacion de Variables
        $unPedidoMesa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get('idMesa'))->getPedidoActivo();
        foreach ($unPedidoMesa->getItemPedido() as $item) {
            $itemsOriginales[] = $item;
        }
        $form = $this->createForm(new PedidoMesaType(), $unPedidoMesa, array('attr' => array('only_cliente' => false)));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                $faltantes = $gestorPedidos->editarPedido($unPedidoMesa, $itemsOriginales, $this->getRequest()->get("idSolicitud"), $this->getUser());
                if (empty($faltantes)) {
                    return $this->redirect($this->generateUrl('sectores_mesas'));
                }
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $parameters = array('form' => $form->createView(), 'idMesa' => $this->getRequest()->get('idMesa'), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres(), "faltantes" => $faltantes, "idSolicitud" => $idSolicitud, 'id' => $this->getRequest()->get("id"), 'gestor_autenticacion' => $gestor);
        return $this->render('SisGGFinalBundle:Pedido:editarPedidoMesa.html.twig', $parameters);
    }

    public function cancelarAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PEDIDO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $gestorPedidos = $this->get('gestor_pedidos');
        $unPedido = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pedido')->find($this->getRequest()->get("id"));
        if ($this->getRequest()->getMethod() === 'POST') {
            $gestorPedidos->cancelarPedido($this->getRequest()->get("id"));
        }
        $parameters = array('form' => null, 'pedido' => $unPedido);
        return $this->render('SisGGFinalBundle:Pedido:cancelarPedido.html.twig', $parameters);
    }

    //Mesas, Categorias y Productos
    public function mesasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $parameters = null;
        if ($this->getRequest()->attributes->has("idMesa")) {
            $parameters = array('form' => null, 'mesas' => $em->getRepository("SisGGFinalBundle:Sector")->find($this->getRequest()->get("id"))->getMesas(), "mesa" => $em->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get("id")));
        } else {
            $parameters = array('form' => null, 'mesas' => $em->getRepository("SisGGFinalBundle:Sector")->find($this->getRequest()->get("id"))->getMesas());
        }
        return $this->render('SisGGFinalBundle:Pedido:mesas.html.twig', $parameters);
    }

    public function productosAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $productos = $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->find($this->getRequest()->get("id"))->getProductoVenta();
        $retorno = array();
        /* @var $producto ProductoVenta */
        foreach ($productos as $producto) {
            $tasas = array();
            foreach ($producto->getTasa()->getUM()->getTasas() as $tasa) {
                $tasas[] = array('id' => $tasa->getId(), 'nombre' => $tasa->__toString());
            }
            $ingredientes = array();
            foreach ($producto->getIngredientes() as $ingrediente) {
                if (!$ingrediente->getObligatorio())
                    $ingredientes[] = array('id' => $ingrediente->getId(), 'nombre' => $ingrediente->getExclusion());
            }
            $imagen = null;
            if ($producto->getFoto() != null) {
                $imagen = $producto->getFoto()->getPath();
            }
            $retorno[] = array('id' => $producto->getId(), 'nombre' => $producto->getNombre(), 'descripcion' => $producto->getDescripcion(), 'precioCosto' => $producto->getPrecioCosto(), 'precioVenta' => $producto->getPrecioVenta(), 'imagen' => $imagen, 'tasa' => array('id' => $producto->getTasa()->getId(), 'nombre' => $producto->getTasa()->__toString()), 'tasas' => $tasas, 'ingredientes' => $ingredientes, 'descuento' => $producto->getPorcentajeDescuento());
        }
        return new Response(json_encode($retorno));
    }

    public function productosByTagAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $productos = $em->getRepository("SisGGFinalBundle:ProductoVenta")->findByNombres($this->getRequest()->get("tag"));
        $retorno = array();
        /* @var $producto ProductoVenta */
        foreach ($productos as $producto) {
            $tasas = array();
            foreach ($producto->getTasa()->getUM()->getTasas() as $tasa) {
                $tasas[] = array('id' => $tasa->getId(), 'nombre' => $tasa->__toString());
            }
            $ingredientes = array();
            foreach ($producto->getIngredientes() as $ingrediente) {
                if (!$ingrediente->getObligatorio())
                    $ingredientes[] = array('id' => $ingrediente->getId(), 'nombre' => $ingrediente->getExclusion());
            }
            $imagen = null;
            if ($producto->getFoto() != null) {
                $imagen = $producto->getFoto()->getPath();
            }
            $retorno[] = array('id' => $producto->getId(), 'nombre' => $producto->getNombre(), 'descripcion' => $producto->getDescripcion(), 'precioCosto' => $producto->getPrecioCosto(), 'precioVenta' => $producto->getPrecioVenta(), 'imagen' => $imagen, 'tasa' => array('id' => $producto->getTasa()->getId(), 'nombre' => $producto->getTasa()->__toString()), 'tasas' => $tasas, 'ingredientes' => $ingredientes, 'descuento' => $producto->getPorcentajeDescuento());
        }
        return new Response(json_encode($retorno));
    }

    public function categoriasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->getRequest()->get('id') === null) {
            $parameters = array('form' => null, 'tipo' => $this->getRequest()->get('tipo'), 'categorias' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findPadres());
            return $this->render('SisGGFinalBundle:Pedido:categoriasPadre.html.twig', $parameters);
        } else {
            $parameters = array('form' => null, 'tipo' => $this->getRequest()->get('tipo'), 'categoria' => $em->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->find($this->getRequest()->get("id")));
            return $this->render('SisGGFinalBundle:Pedido:categorias.html.twig', $parameters);
        }
    }

    public function tasasProductoAction() {
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto');
        $producto = $repositorio->find($this->getRequest()->get("id"));
        return $this->render('SisGGFinalBundle:Pedido:tasasProducto.html.twig', array('producto' => $producto));
    }

    /* Verifica si "virtualmente" es posible elaborar o utilizar el producto */

    public function verificarDisponibilidadAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $unProductoVenta = $em->getRepository("SisGGFinalBundle:ProductoVenta")->find($this->getRequest()->get("idProducto"));
        $tasa = $em->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get("idTasa"));
        $cantidad = $this->getRequest()->get("cantidad");
        $arguments = $unProductoVenta->verificarDisponibilidad($tasa, $cantidad);
        if (!empty($arguments)) {
            $parameters = array('form' => null, 'ingredientes' => $arguments);
            return $this->render('SisGGFinalBundle:Pedido:verificarDisponibilidad.html.twig', $parameters);
        }
    }

    public function deliverysAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), 'pedido_listar')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $dia = new \DateTime();
        $dia->modify('-1 day');
        $pedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findDelivery($dia);
        $historialPedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findHistorial();
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Sector')->findAll();
        return $this->render('SisGGFinalBundle:Pedido:deliverys.html.twig', array('dia' => $dia, 'pedidos' => $pedidos, 'historialPedidos' => $historialPedidos, "sectores" => $sectores, 'form' => null, 'gestor_autenticacion' => $gestor));
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