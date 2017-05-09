<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\IVA;
use SisGG\FinalBundle\Form\IVAType;

/**
 * Description of MantenimientoController
 *
 * @author sergio
 */
class IVAController extends Controller {

    public function nuevoIVAAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::IVA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $iva = new IVA();
        $formulario = $this->createForm(new IVAType(), $iva);
        if ($this->getRequest()->getMethod() === 'POST') {
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaIVA($iva, $this);
                if ($r == null) {
                    $msj = "El IVA ha sido exitosamente registrado.";
                    $this->getRequest()->getSession()->set('msjIVA', $msj);
                    return $this->redirect($this->generateUrl('ivas'));
                } else {
                    return $this->render('SisGGFinalBundle:IVA:nuevoIVA.html.twig', array('form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:IVA:nuevoIVA.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:IVA:nuevoIVA.html.twig', array('form' => $formulario->createView(), 'error' => null));
    }

    public function editarIVAAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::IVA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $iva = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new IVAType(), $iva);
        if ($this->getRequest()->getMethod() === 'POST') {
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->modificarIVA($iva, $this);
                if ($r == null) {
                    $msj = "El IVA ha sido exitosamente modificado.";
                    $this->getRequest()->getSession()->set('msjIVA', $msj);
                    return $this->redirect($this->generateUrl('ivas'));
                } else {
                    return $this->render('SisGGFinalBundle:IVA:editarIVA.html.twig', array('form' => $formulario->createView(), 'id' => $iva->getId(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:IVA:editarIVA.html.twig', array('form' => $formulario->createView(), 'id' => $iva->getId(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:IVA:editarIVA.html.twig', array('form' => $formulario->createView(), 'id' => $iva->getId(), 'error' => null));
    }

    public function eliminarIVAAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::IVA_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $iva IVA */
        $iva = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->find($this->getRequest()->get('id'));
        $this->getRequest()->getSession()->set('msjiva', 'El IVA ha sido exitosamente eliminado.');
        $iva->setActivo(false);
        $em->flush();
        return $this->redirect($this->generateUrl('ivas'));
    }

    public function activarIVAAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::IVA_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var $iva IVA */
                $iva = $em->getRepository("SisGGFinalBundle:IVA")->find($id);
                $iva->setActivo(true);
                $em->flush($iva);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $iva->__toString())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function ivasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::IVA_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $estado = null;
        $estado = $msj = $this->getRequest()->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA');
        $ivas = null;
        $cantidad = 0;
        if ($estado == 1) {
            $ivas = $repositorio->findBy(array('activo' => false));
        } else {
            $ivas = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad = count($inactivos);
        }
        $msj = $this->getRequest()->getSession()->get('msjIVA');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjIVA');
        $this->getRequest()->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'registros' => $ivas);
        return $this->render('SisGGFinalBundle:IVA:ivas.html.twig', $parameters);
    }

    public function obtenerIVAAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $iva = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->find($this->getRequest()->get('id'));
            return new Response(json_encode(array('id' => $iva->getId(), 'tasa' => $iva->getTasa(), 'gravado' => $iva->getGravado())));
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>