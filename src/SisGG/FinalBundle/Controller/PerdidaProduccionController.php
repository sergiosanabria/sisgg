<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\PreElaboradoType;
use SisGG\FinalBundle\Entity\PreElaborado;
use SisGG\FinalBundle\Entity\MateriaPrima;
use SisGG\FinalBundle\Form\MateriaPrimaType;
use SisGG\FinalBundle\Entity\ProductoProduccion;

/**
 * Description of PerdidaProduccionController
 *
 * @author sergio
 */
class PerdidaProduccionController extends Controller {

    public function perdidaProduccion1Action() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_PERDIDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pes = $this->getDoctrine()->getRepository('SisGGFinalBundle:ProductoProduccion')->findBy(array('activo' => true));
        $msj = $this->getRequest()->getSession()->get('msjRPR');
        $this->getRequest()->getSession()->remove('msjRPR');
        if ($this->getRequest()->getMethod() === 'POST') {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->find($this->getRequest()->get('id'));
            $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            $cantidad = $this->getRequest()->get('cantidad');
            $desc = $this->getRequest()->get('obsProd');
            $cantidad = $cantidad * ($tasa->getValor() / $pe->getTasa()->getValor());
            $msj = $this->getEmpresa()->perdidaProduccion1($pe, $cantidad, $desc, $this);
            //$parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
            $this->getRequest()->getSession()->set('msjRPR', 'Se ha registrado correctamente.');
            return $this->redirect($this->generateUrl('perdidaProduccion1'));
        }
        $parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
        return $this->render('SisGGFinalBundle:PerdidaProduccion:registroProduccion1.html.twig', $parameters);
    }

    public function obtnerChk($j) {
        for ($i = 0; $i < $j; $i++) {
            if ($this->getRequest()->get('chk' . $i) == 1)
                $array[] = 1;
            else
                $array[] = 0;
        }
        return $array;
    }

    public function calcularCantidad1Action() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->get('id');
            $pe = new ProductoProduccion();
            $pe = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:ProductoProduccion')->find($id);
            $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            $cantidad = $this->getRequest()->get('cantidad');
//            if ($pe->isPreElaborado()) {
//                $cantidad = ( $cantidad) * ($tasa->getValor() / $pe->getTasa()->getValor());
//                $resto = $pe->getCantidad() - $cantidad;
//                $parameters = array('cantidad' => $cantidad, 'tasa' => $tasa, 'resto' => $resto, 'tipo' => 0, 'mp' => $pe);
//            } else {
//                $cantidad = ( $cantidad) * ($tasa->getValor() / $pe->getTasa()->getValor());
//                $resto = $pe->getCantidad() - $cantidad;
//                $parameters = array('cantidad' => $cantidad, 'tasa' => $tasa, 'resto' => $resto, 'tipo' => 0, 'mp' => $pe);
//            }
            $cant= ( $cantidad) * ($tasa->getValor() / $pe->getTasa()->getValor());
            $resto = $pe->getCantidad() - $cantidad;
            $importe = $pe->getPrecioCosto() * $cant;
            $parameters = array('cantidad' => $cant,'importe'=>$importe ,'tasa' => $tasa, 'resto' => $resto, 'tipo' => 0, 'mp' => $pe);
            return $this->render('SisGGFinalBundle:PerdidaProduccion:resultado1.html.twig', $parameters);
        }
    }

    public function datosPPAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->find($this->getRequest()->get('id'));
            if ($pe->isPreElaborado()) {
                $form = $this->createForm(new PreElaboradoType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:PerdidaProduccion:detalles.html.twig', array('form' => $form->createView(), 'pe' => $pe, 'ingredientes' => $pe->getIngredientes()));
            } else {
                $form = $this->createForm(new MateriaPrimaType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:PerdidaProduccion:detalles.html.twig', array('form' => $form->createView(), 'pe' => $pe, 'ingredientes' => null));
            }
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>