<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Form\AperturaType;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Form\CajaType;
use \DateTime;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class LoteController extends Controller {

    public function lotesAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $lotes = $this->get("gestor_cajas")->getCaja()->getLotes();
        return $this->render("SisGGFinalBundle:Lote:lotes.html.twig",array('form'=>null,'gestor_autenticacion'=>$gestor,'lotes'=>$lotes));
    }
    
    public function aperturaCajaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $apertura = new Apertura();
        $apertura->setUsuario($this->getUser());
        $apertura->setFecha(new DateTime());
        $mensaje=null;
        $caja = $this->get('gestor_cajas')->getCaja();
        $form = $this->createForm(new AperturaType, $apertura, array('attr'=>array('min'=>$caja->getMinimoApertura())));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $mensaje = $this->get('gestor_cajas')->aperturaCaja($apertura);
                if($mensaje ==null){
                    return $this->redirect($this->generateUrl('clientes'));
                }                
            }
        }
        return $this->render('SisGGFinalBundle:Caja:aperturaCaja.html.twig', array('form' => $form->createView(),'mensaje'=>$mensaje,'gestor_autenticacion'=>$gestor,'caja'=>$caja));
    }
    
    public function editarCajaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $caja Caja */
        $caja = $this->get('gestor_cajas')->getCaja();
        $form = $this->createForm(new CajaType(), $caja);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $this->getDoctrine()->getEntityManager()->flush($caja);                
            }
        }
        return $this->render('SisGGFinalBundle:Caja:caja.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
    
    public function loteAction(){
        $gestor = $this->get('gestor_autenticacion');
        $lote = $this->getDoctrine()->getRepository("SisGGFinalBundle:Lote")->find($this->getRequest()->get('id'));
        return $this->render('SisGGFinalBundle:Lote:lote.html.twig', array('form' => null,'gestor_autenticacion'=>$gestor,'lote'=>$lote));
    }

}