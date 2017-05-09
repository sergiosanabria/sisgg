<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\PedidoMostrador;
use SisGG\FinalBundle\Form\PedidoMostradorType;
use SisGG\FinalBundle\Entity\PedidoDelivery;
use SisGG\FinalBundle\Form\PedidoDeliveryType;
use SisGG\FinalBundle\Entity\PedidoMesa;
use SisGG\FinalBundle\Form\PedidoMesaType;

/**
 * Description of SolicitudController
 *
 * @author martin
 */
class SolicitudController extends Controller {

    public function enviarSolicitudMostradorAction() {
        $unPedidoMostrador = new PedidoMostrador();
        $form = $this->createForm(new PedidoMostradorType(), $unPedidoMostrador,array('attr'=>array('only_cliente'=>false)));
        $form->bindRequest($this->getRequest());
        if ($unPedidoMostrador->getItemPedido() != null) {
            foreach ($unPedidoMostrador->getItemPedido() as $itemPedido) {
                $itemPedido->setPedido($unPedidoMostrador);
            }
        }
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        return $this->render("SisGGFinalBundle:Solicitud:nuevaSolicitud.html.twig", array("form" => null, "id" => $gestor->enviarSolicitud($unPedidoMostrador)));
    }
    
    public function enviarSolicitudDeliveryAction() {
        $unPedidoDelivery = new PedidoDelivery();
        $form = $this->createForm(new PedidoDeliveryType(), $unPedidoDelivery,array('attr'=>array('only_cliente'=>false)));
        $form->bindRequest($this->getRequest());
        if ($unPedidoDelivery->getItemPedido() != null) {
            foreach ($unPedidoDelivery->getItemPedido() as $itemPedido) {
                $itemPedido->setPedido($unPedidoDelivery);
            }
        }
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        return $this->render("SisGGFinalBundle:Solicitud:nuevaSolicitud.html.twig", array("form" => null, "id" => $gestor->enviarSolicitud($unPedidoDelivery)));
    }
    
    public function enviarSolicitudMesaAction() {
        $unPedidoMesa = new PedidoMesa();
        $form = $this->createForm(new PedidoMesaType(), $unPedidoMesa,array('attr'=>array('only_cliente'=>false)));
        $form->bindRequest($this->getRequest());
        if ($unPedidoMesa->getItemPedido() != null) {
            foreach ($unPedidoMesa->getItemPedido() as $itemPedido) {
                $itemPedido->setPedido($unPedidoMesa);
            }
        }
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        return $this->render("SisGGFinalBundle:Solicitud:nuevaSolicitud.html.twig", array("form" => null, "id" => $gestor->enviarSolicitud($unPedidoMesa)));
    }

    public function verSolicitudesAction() {
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        /* @var $solicitudes \Doctrine\Common\Collections\ArrayCollection */
        $solicitudes = $gestor->verSolicitudes();
        if (!empty($solicitudes)){
            return $this->render("SisGGFinalBundle:Solicitud:solicitudes.html.twig", array("form" => null, "solicitudes" => $solicitudes));
        }        
    }
    
    public function responderSolicitudAction() {
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        $gestor->responderSolicitud($this->getRequest()->get("idSolicitud"), $this->getRequest()->get("respuesta"));
        return $this->render("SisGGFinalBundle:Solicitud:respuesta.html.twig",array("respuesta"=>true));
    }
    
    public function obenterRespuestaAction() {
        /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
        $gestor = $this->get('gestor_solicitudes');
        $respuesta = $gestor->verRespuesta($this->getRequest()->get("idSolicitud"));
        if ($respuesta===true||$respuesta===false){
            return $this->render("SisGGFinalBundle:Solicitud:respuesta.html.twig",array("respuesta"=>$respuesta));
        }
    }

}