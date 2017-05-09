<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Tanda;

/**
 * @author martin
 */
class TandaController extends Controller {

    public function tandasAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $tandas = $em->getRepository("SisGGFinalBundle:Tanda")->findTandas();
        $parameters = array('form'=>null,'tandas'=>$tandas);
        return $this->render("SisGGFinalBundle:Tanda:tandas.html.twig",$parameters);
    }
    
    public function nuevaTandaAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $unaTanda = new Tanda();
        $parameters = array('form'=>null,'tanda'=>$unaTanda);
        $em->persist($unaTanda);
        $em->flush();
        return $this->render('SisGGFinalBundle:Tanda:tanda.html.twig',$parameters);
    }
    
    public function vincularPedidoAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $unaTanda = $em->getRepository("SisGGFinalBundle:Tanda")->find($this->getRequest()->get('tanda'));
        $unPedido = $em->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get('pedido'));
        $unaTanda->vincularComanda($unPedido);
        $em->flush();
        $parameters = array('form'=>null,'tanda'=>$unaTanda);
        return $this->render('SisGGFinalBundle:Tanda:resumen.html.twig',$parameters);
    }
    
    public function desvincularPedidoAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $unaTanda = $em->getRepository("SisGGFinalBundle:Tanda")->find($this->getRequest()->get('tanda'));
        $unPedido = $em->getRepository("SisGGFinalBundle:Pedido")->find($this->getRequest()->get('pedido'));
        $unaTanda->desvincularComanda($unPedido);
        $em->flush();
        $parameters = array('form'=>null,'tanda'=>$unaTanda);
        return $this->render('SisGGFinalBundle:Tanda:resumen.html.twig',$parameters);
    }

    public function resumenAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $unaTanda = $em->getRepository("SisGGFinalBundle:Tanda")->find($this->getRequest()->get('idTanda'));
        $parameters = array('form'=>null,'tanda'=>$unaTanda);
        return $this->render('SisGGFinalBundle:Tanda:resumen.html.twig',$parameters);
    }
    
}