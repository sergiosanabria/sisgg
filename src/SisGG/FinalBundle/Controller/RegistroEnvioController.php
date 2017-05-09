<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
use SisGG\FinalBundle\Entity\RegistroEnvio;
use SisGG\FinalBundle\Form\RegistroEnvioType;
use SisGG\FinalBundle\Form\RegistroEnvioRendirType;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of Pedido
 *
 * @author martin
 */
class RegistroEnvioController extends Controller
{

    public function registrosEnvioAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), 'pedido_listar')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $estado = $this->getRequest()->get('estado');
        $registrosEnvio = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroEnvio")->findAll();
        $parameters = array('registrosEnvio' => $registrosEnvio, 'form' => null, 'gestor_autenticacion' => $gestor, 'estado' => $estado);
        return $this->render('SisGGFinalBundle:RegistroEnvio:registrosEnvio.html.twig', $parameters);
    }

    public function nuevoRegistroEnvioAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unRegistroEnvio = new RegistroEnvio();
        $form = $this->createForm(new RegistroEnvioType(), $unRegistroEnvio);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                /* @var $pedido Pedido */
                foreach ($unRegistroEnvio->getPedido() as $pedido) {
                    $pedido->setRegistroEnvio($unRegistroEnvio);
                    $pedido->setEstado("Entregado");
                }
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($unRegistroEnvio);
                $em->flush();
                return $this->redirect($this->generateUrl('registros_envios'));
            }
        }
        $dia = new \DateTime();
        $dia->modify('-1 day');
        $pedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findRegistroEnvio($dia);
        $parameters = array('form' => $form->createView(), 'gestor_autenticacion' => $gestor, 'pedidos' => $pedidos);
        return $this->render('SisGGFinalBundle:RegistroEnvio:nuevoRegistroEnvio.html.twig', $parameters);
    }

    public function modificarRegistroEnvioAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unRegistroEnvio = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroEnvio")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new RegistroEnvioType(), $unRegistroEnvio);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $total = 0.00;
                /* @var $pedido Pedido */
                foreach ($unRegistroEnvio->getPedido() as $pedido) {
                    $pedido->setRegistroEnvio($unRegistroEnvio);
                    $pedido->setEstado("Entregado");
                    $total = $total + $pedido->getPrecio();
                }
                $unRegistroEnvio->setTotalPedidos($total);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($unRegistroEnvio);
                $em->flush();
                return $this->redirect($this->generateUrl('registros_envios'));
            }
        }
        $dia = new \DateTime();
        $dia->modify('-1 day');
        $pedidos = $unRegistroEnvio->getPedido();
        $pedidosSinAsignar = array_merge($this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findDelDia($dia));
        $parameters = array('form' => $form->createView(), 'gestor_autenticacion' => $gestor, 'registroEnvio' => $unRegistroEnvio, 'pedidos' => $pedidos, 'pedidosSinAsignar' => $pedidosSinAsignar);
        return $this->render('SisGGFinalBundle:RegistroEnvio:modificarRegistroEnvio.html.twig', $parameters);
    }

    public function rendirRegistroEnvioAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unRegistroEnvio = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroEnvio")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new RegistroEnvioRendirType(), $unRegistroEnvio);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                /* @var $pedido Pedido */
                foreach ($unRegistroEnvio->getPedido() as $pedido) {
                    $pedido->setRegistroEnvio($unRegistroEnvio);
                    $pedido->setEstado("Entregado");
                }
                $unRegistroEnvio->setFechaRendicion(new \DateTime('now'));
                $this->get("gestor_cajas")->registrarRendicionEnvio($this->getUser(), $unRegistroEnvio);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($unRegistroEnvio);
                $em->flush();
                return $this->redirect($this->generateUrl('registros_envios'));
            }
        }
        $dia = new \DateTime();
        $dia->modify('-1 day');
        $pedidos = $unRegistroEnvio->getPedido();
        $parameters = array('form' => $form->createView(), 'gestor_autenticacion' => $gestor, 'registroEnvio' => $unRegistroEnvio, 'pedidos' => $pedidos, 'id' => $this->getRequest()->get('id'));
        return $this->render('SisGGFinalBundle:RegistroEnvio:rendirRegistroEnvio.html.twig', $parameters);
    }

    public function borrarRegistroEnvioAction()
    {
        /*@var $unRegistroEnvio RegistroEnvio*/
        $unRegistroEnvio = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroEnvio")->find($this->getRequest()->get("id"));
        /*@var $pedido Pedido*/
        foreach ($unRegistroEnvio->getPedido() as $pedido) {
            $pedido->setEstado("Listo");
            $pedido->setRegistroEnvio(null);
        }
        $unRegistroEnvio->setEstado("inactivo");
        $this->getDoctrine()->getEntityManager()->flush();
        if ($this->getRequest()->get('estado') != null)
            return $this->redirect($this->generateUrl('registros_envios', array('estado' => $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('registros_envios'));
    }

//    /**
//     * @Pdf()
//     */
//    public function imprimirRegistroEnvioAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $registroEnvio = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroEnvio")->find($this->getRequest()->get("id"));
//        $format = $this->get('request')->get('_format');
//        $markers = "";
//        /*@var $pedido Pedido*/
//        foreach ($registroEnvio->getPedido() as $pedido) {
//            $direccion = $pedido->getDireccion();
//            $markers = $markers . "&markers=color:red%7Clabel:E%7C" . $direccion->getCiudad()->getNombre() . "," . $direccion->getCalle() . "+" . $direccion->getNumero() . "," . $direccion->getCiudad()->getProvincia();
//        }
//        $empresa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Empresa")->find(1);
//        $empresaDireccion = $empresa->getDireccion();
//        $center = "&markers=color:blue%7C" . $empresaDireccion->getCiudad()->getNombre() . " " . $empresaDireccion->getCalle() . "+" . $empresaDireccion->getNumero() . "," . $empresaDireccion->getCiudad()->getProvincia();
//        $center = str_replace(" ", "%20", $center);
//        $markers = "http://maps.googleapis.com/maps/api/staticmap?size=600x600&maptype=roadmap\&markers=size:mid%7C" . $markers . $center . "&sensor=true";
//        $url = str_replace(" ", "%20", $markers);
//        $img = '/tmp/map.png';
//        if ($this->hayConexion()) {
//            file_put_contents($img, file_get_contents($url));
//        } else {
//            $img = '';
//        }
//        $parameters = array("form" => null, 'empresa' => $this->getDoctrine()->getRepository("SisGGFinalBundle:Empresa")->find(1), 'registroEnvio' => $registroEnvio, 'markers' => $markers, 'center' => $center, 'img' => $img);
//        return $this->render('SisGGFinalBundle:RegistroEnvio:imprimirRegistroEnvio.' . $format . '.twig', $parameters);
//    }

    public function hayConexion()
    {
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