<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\MateriaPrima;
use SisGG\FinalBundle\Form\MateriaPrimaType;
use SisGG\FinalBundle\Entity\Empresa;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of MateriaPrimaController
 *
 * @author sergio
 */
class MateriaPrimaController extends Controller {

    public function nuevaMPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new MateriaPrimaType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:MateriaPrima:nuevaMP.html.twig', $parameters);
    }

    public function altaMPAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $mp = new MateriaPrima();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new MateriaPrimaType(), $mp);
        $formulario->handleRequest($request);
        if ($formulario->isValid()) {
            $r = $empresa->altaMateriaPrima($mp, $this);
            if ($r == null) {
                $msj = "La materia prima " . $mp->getNombre() . " ha sido exitosamente registrada.";
                $request->getSession()->set('msjMP', $msj);
                return $this->redirect($this->generateUrl('mps', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:MateriaPrima:nuevaMP.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:MateriaPrima:nuevaMP.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarMPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:MateriaPrima');
        $mp = $repositorio->findOneBy(array('id' => $request->get('id')));
        $formulario = $this->createForm(new MateriaPrimaType(), $mp);
        $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
        if ($mp->esIngrediente($ingredientes)) {
            $parameters = array('form' => $formulario->createView(), 'error' => null, 'nombre' => $mp->getNombre(), 'id' => $mp->getId(), 'tasa' => $mp->getTasa()->getNombre());
            return $this->render('SisGGFinalBundle:MateriaPrima:editarMP.html.twig', $parameters);
        }
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'id' => $mp->getId(), 'nombre' => $mp->getNombre(), 'tasa' => null);
        return $this->render('SisGGFinalBundle:MateriaPrima:editarMP.html.twig', $parameters);
    }

    public function modificarMPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:MateriaPrima');
        $mp = $repositorio->find($request->get('id'));
        $formulario = $this->createForm(new MateriaPrimaType(), $mp);
        $formulario->handleRequest($request);
        if ($formulario->isValid()) {
            $r = $empresa->modificarMateriaPrima($mp, $this);
            if ($r == null) {
                $msj = "La materia prima " . $mp->getNombre() . " ha sido exitosamente modificada.";
                $request->getSession()->set('msjMP', $msj);
                return $this->redirect($this->generateUrl('mps', array('msj' => null, 'error' => null)));
            } else {
                $parameters = array('form' => $formulario->createView(), 'error' => $r, 'id' => $mp->getId(), 'nombre' => $mp->getNombre(), 'tasa' => $mp->getTasa()->getNombre());
                return $this->render('SisGGFinalBundle:MateriaPrima:editarMP.html.twig', $parameters);
            }
        }
        $parameters = array('form' => $formulario->createView(), 'nombre' => $mp->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos.", 'id' => $mp->getId(), 'tasa' => $mp->getTasa()->getNombre());
        return $this->render('SisGGFinalBundle:MateriaPrima:editarMP.html.twig', $parameters);
    }

    public function eliminarMPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:MateriaPrima');
        $mp = $repositorio->find($request->get('id'));
        $msj = $empresa->eliminarMateriaPrima($mp, $this);
        if ($request->get('tipo') == null) {
            $request->getSession()->set('msjMP', $msj);
            return $this->redirect($this->generateUrl('mps'));
        } else {
            $request->getSession()->set('msjPP', $msj);
            return $this->redirect($this->generateUrl('pps'));
        }
    }


    public function mpsAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $mp = $this->getDoctrine()->getRepository("SisGGFinalBundle:MateriaPrima")->find($request->get('id'));
            $form = $this->createForm(new MateriaPrimaType(), $mp, array('disabled' => true));
            return $this->render('SisGGFinalBundle:MateriaPrima:detalles.html.twig', array('form' => $form->createView()));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MATERIAPRIMA_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $request->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:MateriaPrima');
            $mps = null;
            $cantidad = 0;
            if ($estado == 1) {
                $mps = $repositorio->findBy(array('activo' => false));
            } else {
                $mps = $repositorio->findBy(array('activo' => true));
                $inactivos = $repositorio->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $request->getSession()->get('msjMP');
            $error = $request->getSession()->get('error');
            $request->getSession()->remove('msjMP');
            $request->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'mps' => $mps);
            return $this->render('SisGGFinalBundle:MateriaPrima:mps.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    //Imprimir

//    /**
//     * @Pdf()
//     */
//    public function impDetallesMPAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $producto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($request->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'producto' => $producto, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:MateriaPrima:imp_detalles.' . $format . '.twig', $parameters);
//    }

}

?>