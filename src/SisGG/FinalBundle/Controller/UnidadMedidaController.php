<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\UnidadMedidaType;
use SisGG\FinalBundle\Entity\UnidadMedida;
use SisGG\FinalBundle\Entity\Empresa;

/**
 * Description of UnidadMedidaController
 *
 * @author sergio
 */
class UnidadMedidaController extends Controller {

    public function nuevaUMAction() {
        $formulario = $this->createForm(new UnidadMedidaType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:UM:nuevaUM.html.twig', $parameters);
    }

    public function altaUMAction() {
        $um = new UnidadMedida();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new UnidadMedidaType(), $um);
        $nombre = $this->getRequest()->get('nombre');
        $desc = $this->getRequest()->get('desc');
        $abrev = $this->getRequest()->get('abrev');
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->altaUM($um, $nombre, $desc, $abrev, $this);
            if ($r == null) {
                $msj = "La unidad de medida " . $um->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjUM', $msj);
                return $this->redirect($this->generateUrl('ums', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:UM:nuevaUM.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:UM:nuevaUM.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarUMAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:UnidadMedida');
        $um = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new UnidadMedidaType(), $um);
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'id' => $um->getId(), 'nombre' => $um->getNombre());
        return $this->render('SisGGFinalBundle:UM:editarUM.html.twig', $parameters);
    }

    public function modificarUMAction(Empresa $empresa = null) {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:UnidadMedida');
        $um = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new UnidadMedidaType(), $um);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarUM($um, $this);
            if ($r == null) {
                $msj = "La unidad de medida " . $um->getNombre() . " ha sido exitosamente modificada.";
                $this->getRequest()->getSession()->set('msjUM', $msj);
                return $this->redirect($this->generateUrl('ums', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:UM:editarUM.html.twig', array('form' => $formulario->createView(), 'id' => $um->getId(), 'nombre' => $um->getNombre(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:UM:editarUM.html.twig', array('form' => $formulario->createView(), 'id' => $um->getId(), 'nombre' => $um->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }
    public function eliminarUMAction() {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:UnidadMedida');
        $um = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $msj = $empresa->eliminarUM($um, $this);
        if ($msj!=null){
            $this->getRequest()->getSession()->set('msjUM', $msj);
            return $this->redirect($this->generateUrl('ums'));
        }else{
             $this->getRequest()->getSession()->set('error', "La unidad de medida ".$um->getNombre()." no puede ser elimida puesto que contiene tasas asociadas a un productos o ingredientes.");
            return $this->redirect($this->generateUrl('ums'));
        }
        
    }
    public function umsAction() {
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:UnidadMedida');
        $ums = $repositorio->findAll();
        $msj1 = $this->getRequest()->getSession()->get('msjUM');
        $msj2 = $this->getRequest()->getSession()->get('msjTas');
        $this->getRequest()->getSession()->remove('msjTas');
        $this->getRequest()->getSession()->remove('msjUM');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('error');
        if ($msj1 != null) {
            $parameters = array('msj' => $msj1,'form'=>null ,'error' => $error, 'ums' => $ums);
            return $this->render('SisGGFinalBundle:UM:ums.html.twig', $parameters);
        }
        if ($msj2 != null) {
            $parameters = array('msj' => $msj2,'form'=>null, 'error' => $error, 'ums' => $ums);
            return $this->render('SisGGFinalBundle:UM:ums.html.twig', $parameters);
        }
        $parameters = array('msj' => $msj2,'form'=>null, 'error' => $error, 'ums' => $ums);
        return $this->render('SisGGFinalBundle:UM:ums.html.twig', $parameters);
    }

    public function cargarUMAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:UnidadMedida');
            $ums = $repositorio->findAll();
            return $this->render('SisGGFinalBundle:UM:cargarUM.html.twig', array('ums' => $ums));
        }
    }

    public function cargarTasaAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:UnidadMedida');
            $um = $repositorio->findOneBy(array('nombre' => $this->getRequest()->get('name')));
            return $this->render('SisGGFinalBundle:UM:cargarTasa.html.twig', array('tasas' => $um->getTasas(), 'nombre' => null));
        }
    }

    public function seleccionarTasaAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Tasa');
            $nombre = $this->borrarTxt();
            $tasa = $repositorio->findOneBy(array('nombre' => $nombre));
            $um = $tasa->getUm();
            return $this->render('SisGGFinalBundle:UM:cargarTasa.html.twig', array('tasas' => $um->getTasas(), 'nombre' => $tasa));
        }
    }

    private function borrarTxt() {
        $nombre = $this->getRequest()->get('name');
        $array = str_split($nombre);
        for ($index = 0; $index < count($array); $index++) {
            if ($array[$index] == "(") {
                $nombre = substr($nombre, 0, $index - count($array));
            }
        }
        return $nombre;
    }

    public function buscarUMdeTasaAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Tasa');
            $nombre = $this->borrarTxt();
            $tasa = $repositorio->findOneBy(array('nombre' => $nombre));
            if ($tasa != null)
                return new Response(json_encode(array('name' => $tasa->getUm()->getNombre())));
            else {
                return new Response('');
            }
        }
    }
    public function tasaCVRAction() {
        $valor=0.00;
        //if ($this->getRequest()->isXmlHttpRequest()) {
            
            $tasa1 = $this->getDoctrine()->getRepository('SisGGFinalBundle:Tasa')->find($this->getRequest()->get('tasa1'));
            $tasa2 = $this->getDoctrine()->getRepository('SisGGFinalBundle:Tasa')->find($this->getRequest()->get('tasa2'));
            $cantidad = $this->getRequest()->get('cantidad');
            $valor=$cantidad*($tasa1->getValor()/$tasa2->getValor());
                return new Response(json_encode(array('valor' => $valor)));
           
       // }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    
    
}

?>
