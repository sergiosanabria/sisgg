<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Mantenimiento;
use SisGG\FinalBundle\Form\MantenimientoType;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of MantenimientoController
 *
 * @author sergio
 */
class MantenimientoController extends Controller
{

    public function nuevoMantenimientoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new MantenimientoType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:Mantenimiento:nuevoMantenimiento.html.twig', $parameters);
    }

    public function altaMantenimientoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $mantenimiento = new Mantenimiento();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new MantenimientoType(), $mantenimiento);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->altaMantenimiento($mantenimiento, $this);
            if ($r == null) {
                $msj = "El producto mantenimiento " . $mantenimiento->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjMan', $msj);
                return $this->redirect($this->generateUrl('mantenimientos'));
            } else {
                return $this->render('SisGGFinalBundle:Mantenimiento:nuevoMantenimiento.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:Mantenimiento:nuevoMantenimiento.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarMantenimientoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mantenimiento');
        $mantenimiento = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $formulario = $this->createForm(new MantenimientoType(), $mantenimiento);
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'id' => $mantenimiento->getId(), 'nombre' => $mantenimiento->getNombre());
        return $this->render('SisGGFinalBundle:Mantenimiento:editarMantenimiento.html.twig', $parameters);
    }

    public function modificarMantenimientoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mantenimiento');
        $mantenimiento = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new MantenimientoType(), $mantenimiento);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarMantenimiento($mantenimiento, $this);
            if ($r == null) {
                $msj = "El producto " . $mantenimiento->getNombre() . " ha sido exitosamente modificado.";
                $this->getRequest()->getSession()->set('msjMan', $msj);
                return $this->redirect($this->generateUrl('mantenimientos'));
            } else {
                $parameters = array('form' => $formulario->createView(), 'error' => $r, 'id' => $mantenimiento->getId(), 'nombre' => $mantenimiento->getNombre());
                return $this->render('SisGGFinalBundle:Mantenimiento:editarMantenimiento.html.twig', $parameters);
            }
        }
        $parameters = array('form' => $formulario->createView(), 'id' => $mantenimiento->getId(), 'nombre' => $mantenimiento->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos.");
        return $this->render('SisGGFinalBundle:Mantenimiento:editarMantenimiento.html.twig', $parameters);
    }

    public function eliminarMantenimientoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mantenimiento');
        $mantenimiento = $repositorio->find($this->getRequest()->get('id'));
        $msj = $empresa->eliminarMantenimiento($mantenimiento, $this);
        if ($this->getRequest()->get('tipo') == null) {
            $this->getRequest()->getSession()->set('msjMan', $msj);
            return $this->redirect($this->generateUrl('mantenimientos'));
        } else {
            $this->getRequest()->getSession()->set('msjMan', $msj);
            return $this->redirect($this->generateUrl('mantenimientos'));
        }
    }

    public function mantenimientosAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $mercaderia = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mantenimiento")->find($this->getRequest()->get('id'));
            $form = $this->createForm(new MantenimientoType(), $mercaderia, array('disabled' => true));
            return $this->render('SisGGFinalBundle:Mantenimiento:detalles.html.twig', array('form' => $form->createView()));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MANTENIMIENTO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Mantenimiento');
            $mantenimientos = null;
            $cantidad = 0;
            if ($estado == 1) {
                $mantenimientos = $repositorio->findBy(array('activo' => false));
            } else {
                $mantenimientos = $repositorio->findBy(array('activo' => true));
                $inactivos = $repositorio->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $this->getRequest()->getSession()->get('msjMan');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjMan');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'registros' => $mantenimientos);
            return $this->render('SisGGFinalBundle:Mantenimiento:mantenimientos.html.twig', $parameters);
        }
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impDetallesManteAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $producto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'producto' => $producto, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Mantenimiento:imp_detalles.' . $format . '.twig', $parameters);
//    }

}
