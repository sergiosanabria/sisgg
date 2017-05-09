<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Form\CobroType;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Form\VentaType;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class CobroController extends Controller {

    function cambiarCobroAction(){
        $unaVenta = new Venta();
        $unCobro = new \SisGG\FinalBundle\Entity\Cobro();
        $unCobro->setTipoCobro($this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->find($this->getRequest()->get("id")));
        foreach ($unCobro->getTipoCobro()->getCampos() as $campo) {
            $valor = new \SisGG\FinalBundle\Entity\Valor();
            $valor->setCampo($campo);
            $unCobro->addValore($valor);
        }
        $unaVenta->addCobro($unCobro);
        $form = $this->createForm(new VentaType(), $unaVenta);
        $parameters = array("form" => $form->createView());
        return $this->render("SisGGFinalBundle:Cobro:cambiarCobro.html.twig", $parameters);
    }
   
}