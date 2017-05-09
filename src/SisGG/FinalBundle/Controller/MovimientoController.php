<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Form\AperturaType;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Form\CajaType;
use SisGG\FinalBundle\Entity\Movimiento;
use SisGG\FinalBundle\Entity\Entrada;
use SisGG\FinalBundle\Entity\Salida;
use SisGG\FinalBundle\Form\EntradaType;
use SisGG\FinalBundle\Form\SalidaType;
use Doctrine\Common\Collections\ArrayCollection;
use SisGG\FinalBundle\Form\MovimientoType;
use SisGG\FinalBundle\Model\GestorCajas;
use \DateTime;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class MovimientoController extends Controller {

    public function movimientosAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $movimientos = array();
        $movimientos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Movimiento")->findMovimientos($this->getDoctrine()->getRepository("SisGGFinalBundle:Lote")->find($this->getRequest()->get('id')));
        return $this->render("SisGGFinalBundle:Movimiento:movimientos.html.twig",array('form'=>null,'gestor_autenticacion'=>$gestor,'movimientos'=>$movimientos));
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

    public function nuevoMovimientoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $caja GestorCajas */
        $gestorCaja = $this->get('gestor_cajas');
        $movimiento = new Movimiento();
        $movimiento->t = 'Entrada';
        $form = $this->createForm(new MovimientoType(), $movimiento);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $gestorCaja->nuevoMovimiento($movimiento,$this->getUser());
                return $this->redirect($this->generateUrl('caja'));                
            }
        }
        return $this->render('SisGGFinalBundle:Movimiento:nuevoMovimiento.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
}