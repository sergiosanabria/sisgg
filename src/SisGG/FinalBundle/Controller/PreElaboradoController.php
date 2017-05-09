<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\PreElaboradoType;
use SisGG\FinalBundle\Entity\PreElaborado;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of PreElaboradoController
 *
 * @author sergio
 */
class PreElaboradoController extends Controller {

    public function nuevoPEAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new PreElaboradoType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:PreElaborado:nuevoPE.html.twig', $parameters);
    }

    public function altaPEAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pe = new PreElaborado();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new PreElaboradoType(), $pe);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->altaPreElaborado($pe, $this);
            if ($r == null) {
                $msj = "El producto pre-elaborado " . $pe->getNombre() . " ha sido exitosamente registrado.";
                $this->getRequest()->getSession()->set('msjPE', $msj);
                $this->getRequest()->getSession()->set('idPE', $pe->getId());
                return $this->redirect($this->generateUrl('pes', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:PreElaborado:nuevoPE.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:PreElaborado:nuevoPE.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarPEAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:PreElaborado');
        $pe = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $formulario = $this->createForm(new PreElaboradoType(), $pe);
        $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
        if ($pe->esIngrediente($ingredientes)) {
            $parameters = array('form' => $formulario->createView(), 'error' => null, 'nombrePE' => $pe->getNombre(), 'ingredientes' => $pe->getIngredientes(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 1);
            return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
        }
        if (!($pe->getIngredientes()->isEmpty())) {
            $parameters = array('form' => $formulario->createView(), 'error' => null, 'nombrePE' => $pe->getNombre(), 'ingredientes' => $pe->getIngredientes(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 2);
            return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
        }
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'nombrePE' => $pe->getNombre(), 'ingredientes' => $pe->getIngredientes(), 'id' => $pe->getId(), 'tasa' => null);
        return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
    }

    public function modificarPEAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:PreElaborado');
        $pe = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new PreElaboradoType(), $pe);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarPreElaborado($pe, $this, true);
            if ($r == null) {
                $msj = "El producto pre-elaborado " . $pe->getNombre() . " ha sido exitosamente modificado.";
                $this->getRequest()->getSession()->set('msjPE', $msj);
                return $this->redirect($this->generateUrl('pes', array('msj' => null, 'error' => null)));
            } else {
                $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
                if ($pe->esIngrediente($ingredientes)) {
                    $parameters = array('form' => $formulario->createView(), 'error' => $r, 'ingredientes' => $pe->getIngredientes(), 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 1);
                    return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
                }
                if (!($pe->getIngredientes()->isEmpty())) {
                    $parameters = array('form' => $formulario->createView(), 'error' => $r, 'ingredientes' => $pe->getIngredientes(), 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 2);
                    return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
                }
                $parameters = array('form' => $formulario->createView(), 'ingredientes' => $pe->getIngredientes(), 'error' => $r, 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => null);
                return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
            }
        }
        $r = "Verifique que los campos ingresados sean correctos.";
        $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
        if ($pe->esIngrediente($ingredientes)) {
            $parameters = array('form' => $formulario->createView(), 'ingredientes' => $pe->getIngredientes(), 'error' => $r, 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 1);
            return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
        }
        if (!($pe->getIngredientes()->isEmpty())) {
            $parameters = array('form' => $formulario->createView(), 'ingredientes' => $pe->getIngredientes(), 'error' => $r, 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => $pe->getTasa()->getNombre(), 'tipo' => 2);
            return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
        }
        $parameters = array('form' => $formulario->createView(), 'ingredientes' => $pe->getIngredientes(), 'error' => $r, 'nombrePE' => $pe->getNombre(), 'id' => $pe->getId(), 'tasa' => null);
        return $this->render('SisGGFinalBundle:PreElaborado:editarPE.html.twig', $parameters);
    }

    public function eliminarPEAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:PreElaborado');
        $mp = $repositorio->find($this->getRequest()->get('id'));
        $msj = $empresa->eliminarPreElaborado($mp, $this);
        if ($this->getRequest()->get('tipo') == null) {
            $this->getRequest()->getSession()->set('msjPE', $msj);
            return $this->redirect($this->generateUrl('pes'));
        } else {
            $this->getRequest()->getSession()->set('msjPP', $msj);
            return $this->redirect($this->generateUrl('pps'));
        }
    }

    public function pesAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->find($this->getRequest()->get('id'));
            $form = $this->createForm(new PreElaboradoType(), $pe, array('disabled' => true));
            return $this->render('SisGGFinalBundle:PreElaborado:detalles.html.twig', array('form' => $form->createView(), 'pe' => $pe, 'ingredientes' => $pe->getIngredientes()));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PREELABORADO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:PreElaborado');
            $pes = null;
            $cantidad = 0;
            if ($estado == 1) {
                $pes = $repositorio->findBy(array('activo' => false));
            } else {
                $pes = $repositorio->findBy(array('activo' => true));
                $inactivos = $repositorio->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $this->getRequest()->getSession()->get('msjPE');
            if ($msj == null)
                $msj = $this->getRequest()->getSession()->get('msjIng');
            $id = $this->getRequest()->getSession()->get('idPE');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('idPE');
            $this->getRequest()->getSession()->remove('msjIng');
            $this->getRequest()->getSession()->remove('msjPE');
            $parameters = array('msj' => $msj, 'error' => $error, 'pes' => $pes, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'idPE' => $id);
            return $this->render('SisGGFinalBundle:PreElaborado:pes.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    public function popAction() {
        //if ($this->getRequest()->isXmlHttpRequest()) {
        $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:Producto")->find($this->getRequest()->get('id'));
        $flag = false;
        if ($pe->isPreElaborado()) {
            $flag = true;
        }
        // $form = $this->createForm(new PreElaboradoType(), $pe, array('disabled' => true));
        return $this->render('SisGGFinalBundle:PreElaborado:pop.html.twig', array('form' => null, 'obj' => $pe, 'flag' => $flag));
        // }
    }

//    /**
//     * @Pdf()
//     */
//    public function impDetallesPEAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $producto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'producto' => $producto, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:PreElaborado:imp_detalles.' . $format . '.twig', $parameters);
//    }

}
