<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Servicio;
use SisGG\FinalBundle\Form\ServicioType;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of MantenimientoController
 *
 * @author sergio
 */
class ServicioController extends Controller {

    public function nuevoServicioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $servicio = new Servicio();
        $formulario = $this->createForm(new ServicioType(), $servicio, array('attr' => array('tipo' => $empresa->getResponsable())));
        if ($this->getRequest()->getMethod() === 'POST') {
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaServicio($servicio, $this);
                if ($r == null) {
                    $msj = "El servicio " . $servicio->getNombre() . " ha sido exitosamente registrado.";
                    $this->getRequest()->getSession()->set('msjSer', $msj);
                    return $this->redirect($this->generateUrl('servicios'));
                } else {
                    return $this->render('SisGGFinalBundle:Servicio:nuevoServicio.html.twig', array('form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Servicio:nuevoServicio.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:Servicio:nuevoServicio.html.twig', array('form' => $formulario->createView(), 'error' => null));
    }

    public function editarServicioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $servicio = new Servicio();
        $servicio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Servicio')->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new ServicioType(), $servicio, array('attr' => array('tipo' => $empresa->getResponsable())));
        if ($this->getRequest()->getMethod() === 'POST') {
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->modificarServicio($servicio, $this);
                if ($r == null) {
                    $msj = "El servicio " . $servicio->getNombre() . " ha sido exitosamente modificado.";
                    $this->getRequest()->getSession()->set('msjSer', $msj);
                    return $this->redirect($this->generateUrl('servicios'));
                } else {
                    return $this->render('SisGGFinalBundle:Servicio:editarServicio.html.twig', array('form' => $formulario->createView(), 'id' => $servicio->getId(), 'nombre' => $servicio->getNombre(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Servicio:editarServicio.html.twig', array('form' => $formulario->createView(), 'id' => $servicio->getId(), 'nombre' => $servicio->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:Servicio:editarServicio.html.twig', array('form' => $formulario->createView(), 'id' => $servicio->getId(), 'nombre' => $servicio->getNombre(), 'error' => null));
    }

    public function eliminarServicioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $tipo = $this->getDoctrine()->getRepository('SisGGFinalBundle:Servicio')->find($this->getRequest()->get('id'));
        $empresa = $this->getEmpresa();
        $r = $empresa->eliminarServicio($tipo, $this);
        if ($r != null) {
            $msj = "El servicio " . $tipo->getNombre() . " ha sido exitosamente eliminado.";
            $this->getRequest()->getSession()->set('msjSer', $msj);
            return $this->redirect($this->generateUrl('servicios'));
        }
    }

    public function serviciosAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $estado = null;
        $estado = $msj = $this->getRequest()->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Servicio');
        $servicios = null;
        $cantidad = 0;
        if ($estado == 1) {
            $servicios = $repositorio->findBy(array('activo' => false));
        } else {
            $servicios = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad = count($inactivos);
        }
        $msj = $this->getRequest()->getSession()->get('msjSer');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjSer');
        $this->getRequest()->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'registros' => $servicios);
        return $this->render('SisGGFinalBundle:Servicio:servicios.html.twig', $parameters);
    }

    public function activarServicioAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var $servicio Servicio */
                $servicio = $em->getRepository("SisGGFinalBundle:Servicio")->find($id);
                $servicio->setActivo(true);
                $em->flush($servicio);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $servicio->getNombre())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function cargarServiciosAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $servicios = $this->getDoctrine()->getRepository('SisGGFinalBundle:Servicio')->findBy(array('activo' => true), array('nombre' => 'asc'));
            return $this->render('SisGGFinalBundle:Servicio:cargarServicio.html.twig', array('servicios' => $servicios));
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaServicioAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::SERVICIO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Servicio')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Servicio:imp_lista.' . $format . '.twig', $parameters);
//    }

}

?>