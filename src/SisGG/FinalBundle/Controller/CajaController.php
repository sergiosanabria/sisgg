<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Form\AperturaType;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\Cierre;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Form\CierreType;
use SisGG\FinalBundle\Form\CajaType;
use \DateTime;

/**
 * Description of ClienteController
 *
 * @author martin
 */
class CajaController extends Controller
{

    public function cajaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $tiposCobro = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->findRegistrables();
        $caja = $this->get('gestor_cajas')->getCaja();
        return $this->render('SisGGFinalBundle:Caja:detallesCaja.html.twig', array('form' => null, 'gestor_autenticacion' => $gestor, 'caja' => $caja, 'tiposCobro' => $tiposCobro));
    }

    public function aperturaCajaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $apertura = new Apertura();
        $apertura->setUsuario($this->getUser());
        $apertura->setFecha(new DateTime());
        $mensaje = null;
        $caja = $this->get('gestor_cajas')->getCaja();
        $form = $this->createForm(new AperturaType, $apertura, array('attr' => array('min' => $caja->getMinimoApertura())));
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $mensaje = $this->get('gestor_cajas')->aperturaCaja($apertura);
                if ($mensaje == null) {
                    return $this->redirect($this->generateUrl('caja'));
                }
            }
        }
        return $this->render('SisGGFinalBundle:Caja:aperturaCaja.html.twig', array('form' => $form->createView(), 'mensaje' => $mensaje, 'gestor_autenticacion' => $gestor, 'caja' => $caja));
    }

    public function cierreCajaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $cierre = $this->get('gestor_cajas')->arqueo();
        $cierre->setUsuario($this->getUser());
        $cierre->setFecha(new DateTime());
        $mensaje = null;
        $caja = $this->get('gestor_cajas')->getCaja();
        $form = $this->createForm(new CierreType, $cierre);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $mensaje = $this->get('gestor_cajas')->cierreCaja($cierre);
                if ($mensaje == null) {
                    return $this->redirect($this->generateUrl('caja'));
                }
            }
        }
        $tiposCobro = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->findRegistrables();
        return $this->render('SisGGFinalBundle:Caja:cierreCaja.html.twig', array('form' => $form->createView(), 'mensaje' => $mensaje, 'gestor_autenticacion' => $gestor, 'caja' => $caja, 'tiposCobro' => $tiposCobro));
    }

//    /**
//     * @Pdf()
//     */
//    public function arqueoCajaAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $cierre = $this->get('gestor_cajas')->arqueo();
//        $cierre->setUsuario($this->getUser());
//        $cierre->setFecha(new DateTime());
//        $caja = $this->get('gestor_cajas')->getCaja();
//        $format = $this->get('request')->get('_format');
//        $tiposCobro = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->findRegistrables();
//        $parameters = array("form" => null, 'cierre' => $cierre, 'lote' => $caja->getLotes()->first(), 'empresa' => $this->getDoctrine()->getRepository("SisGGFinalBundle:Empresa")->find(1), 'tiposCobro' => $tiposCobro);
//        return $this->render('SisGGFinalBundle:Caja:arqueo.' . $format . '.twig', $parameters);
//    }

    public function editarCajaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
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
        return $this->render('SisGGFinalBundle:Caja:caja.html.twig', array('form' => $form->createView(), 'gestor_autenticacion' => $gestor));
    }

}