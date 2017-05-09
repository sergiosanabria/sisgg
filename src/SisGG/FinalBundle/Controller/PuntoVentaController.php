<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\PuntoVenta;
use SisGG\FinalBundle\Form\PuntoVentaType;
/**
 * Description of PuntoVentaController
 *
 * @author martin
 */
class PuntoVentaController extends Controller {

    public function puntosVentaAction() {
        $puntosVenta = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:PuntoVenta")->findAll();
        return $this->render("SisGGFinalBundle:PuntoVenta:puntosVenta.html.twig",array("form"=>null,"puntosVenta"=>$puntosVenta));
    }
    
    public function nuevoPuntoVentaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $unPuntoVenta = new PuntoVenta();
        $form = $this->createForm(new PuntoVentaType(), $unPuntoVenta);
        if ($this->getRequest()->getMethod()==="POST"){
            $form->bindRequest($this->getRequest());
            if ($form->isValid()){
                $em->persist($unPuntoVenta);
                $em->flush();
                return $this->redirect($this->generateUrl("puntos_venta"));
            }
            
        }
        $parameters = array("form" =>$form->createView());
        return $this->render("SisGGFinalBundle:PuntoVenta:nuevoPuntoVenta.html.twig",$parameters);
    }
    
    public function editarPuntoVentaAction() {
        
    }
    
    public function borrarPuntoVentaAction(){
        
    }

}