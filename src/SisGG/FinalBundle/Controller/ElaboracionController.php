<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\LineaTandaType;
use SisGG\FinalBundle\Model\GestorCocinas;
use SisGG\FinalBundle\Form\CocinaType;
use SisGG\FinalBundle\Entity\MarcaTemporal;
use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\ItemPedido;
use SisGG\FinalBundle\Entity\Ingrediente;
use SisGG\FinalBundle\Entity\ProductoVenta;

/**
 * Description of ElaboracionController
 *
 * @author martin
 */
class ElaboracionController extends Controller {

    public function elaboracionAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COCINA_GESTIONAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pedidosIniciados = $this->getDoctrine()->getRepository("SisGGFinalBundle:Pedido")->findIniciadosOVistosSinTanda();
        foreach ($pedidosIniciados as $pedido) {
            $pedido->setEstado("Visto");
        }
        $this->getDoctrine()->getEntityManager()->flush();
        $historialPedidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Pedido")->findByEstado('Listo');
        /* @var $gestorCocina GestorCocinas */
        $gestorCocina = $this->get('gestor_cocinas');
        $unaCocina = $gestorCocina->getCocina();
        $marcasOriginales = array();

        // Create an array of the current Tag objects in the database
        foreach ($unaCocina->getMarcasTemporales() as $marcaTemporal) {
            $marcasOriginales[] = $marcaTemporal;
        }
        $form = $this->createForm(new CocinaType(), $unaCocina);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                /* @var $marca MarcaTemporal */
                foreach ($unaCocina->getMarcasTemporales() as $marca) {
                    $marca->setCocina($unaCocina);
                }
                foreach ($unaCocina->getMarcasTemporales() as $marca) {
                    foreach ($marcasOriginales as $key => $toDel) {
                        if ($toDel->getId() === $marca->getId()) {
                            unset($marcasOriginales[$key]);
                        }
                    }
                }
                /* @var $marcaTemporal MarcaTemporal */
                foreach ($marcasOriginales as $marcaTemporal) {
                    $marcaTemporal->getCocina()->getMarcasTemporales()->removeElement($marcaTemporal);
                    $this->getDoctrine()->getEntityManager()->remove($marcaTemporal);
                }
                $this->getDoctrine()->getEntityManager()->flush();
                $unaCocina->cambiarNumeroTandas($unaCocina->getNumeroTandas());
                $this->getDoctrine()->getEntityManager()->flush();
                return $this->redirect($this->generateUrl('elaboracion_pedidos'));
            }
        }
        $parameters = array('historialPedidos' => $historialPedidos, 'form' => $form->createView(), 'pedidosIniciados' => $pedidosIniciados, 'tandas' => $unaCocina->getTandas(), 'gestor_autenticacion' => $gestor, 'idCocina' => $unaCocina->getId(), 'cocina' => $unaCocina);
        return $this->render('SisGGFinalBundle:Elaboracion:elaboracion.html.twig', $parameters);
    }

    public function configurarCocina() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COCINA_GESTIONAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pedidosIniciados = $this->getDoctrine()->getRepository("SisGGFinalBundle:Pedido")->findIniciadosOVistosSinTanda();
        foreach ($pedidosIniciados as $pedido) {
            $pedido->setEstado("Visto");
        }
        $this->getDoctrine()->getEntityManager()->flush();
        $historialPedidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Pedido")->findByEstado('Listo');
        /* @var $gestorCocina GestorCocinas */
        $gestorCocina = $this->get('gestor_cocinas');
        $unaCocina = $gestorCocina->getCocina();
        $form = $this->createForm(new CocinaType(), $unaCocina);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush();
                return $this->redirect($this->generateUrl('elaboracion'));
            }
        }
        $parameters = array('historialPedidos' => $historialPedidos, 'form' => $form->createView(), 'pedidosIniciados' => $pedidosIniciados, 'tandas' => $unaCocina->getTandas(), 'gestor_autenticacion' => $gestor, 'idCocina' => $unaCocina->getId());
        return $this->render('SisGGFinalBundle:Elaboracion:elaboracion.html.twig', $parameters);
    }

    public function nuevasComandasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $pedidosIniciados = $em->getRepository("SisGGFinalBundle:Pedido")->findIniciados();
        if ($pedidosIniciados != null) {
            $pedidos = array();
            /* @var $pedido Pedido */
            foreach ($pedidosIniciados as $pedido) {
                $itemsPedido = array();
                /* @var $itemPedido ItemPedido */
                foreach ($pedido->getItemPedido() as $itemPedido) {
                    if ($itemPedido->getEstado() != 'Listo' and $itemPedido->getEstado() != 'Cancelado' and $itemPedido->getEstado() != 'Entregado' and $itemPedido->getEstado() != 'listo' and $itemPedido->getEstado() != 'cancelado' and $itemPedido->getEstado() != 'entregado') {
                        $ingredientes = array();
                        /* @var $ingrediente Ingrediente */
                        foreach ($itemPedido->getIngredientes() as $ingrediente) {
                            $ingredientes[] = $ingrediente->getExclusion();
                        }
                        $itemsPedido[] = array('productoVenta' => $itemPedido->getProductoVenta()->getNombre(), 'cantidad' => $itemPedido->getCantidad(), 'ingredientes' => $ingredientes);
                    }
                }
                $pedidos[] = array('id' => $pedido->getId(), 'fecha' => $pedido->getFechapedido()->format("F j, Y g:i:m a"), 'tipo' => $pedido->getTipo(), 'itemsPedido' => $itemsPedido);
                $pedido->setEstado("Visto");
            }
            $em->flush();
            //$parameters = array('form' => null, 'pedidosIniciados' => $pedidosIniciados);
            return new Response(json_encode($pedidos));
            //return $this->render('SisGGFinalBundle:Elaboracion:nuevasComandas.html.twig', $parameters);
        }
    }

    public function registrarElaboradosAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COCINA_GESTIONAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $idLineaTanda = $this->getRequest()->get("idLineaTanda");
        $unaLineaTanda = $em->getRepository("SisGGFinalBundle:LineaTanda")->find($idLineaTanda);
        if ($this->getRequest()->getMethod() === "POST") {
            $cantidad = $this->getRequest()->get("cantidad");
            $unaLineaTanda->registrarElaborados($cantidad);
            $em->flush();
            $parameters = array('form' => null, 'tanda' => $unaLineaTanda->getTanda());
            return $this->render('SisGGFinalBundle:Tanda:resumen.html.twig', $parameters);
        }
        $parameters = array('lineaTanda' => $unaLineaTanda, 'gestor_autenticacion' => $gestor);
        return $this->render("SisGGFinalBundle:Elaboracion:registrarLineaElaborada.html.twig", $parameters);
    }

    public function despacharPedidoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COCINA_GESTIONAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $retorno = $this->get('gestor_pedidos')->despacharPedido($this->getRequest()->get("idPedido"));
        $array = array('respuesta' => $retorno);
        return new Response(json_encode($array));
    }

}