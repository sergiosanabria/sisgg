<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\FacturaServicio;
use SisGG\FinalBundle\Form\FacturaServicioType;
use SisGG\FinalBundle\Entity\Servicio;
use SisGG\FinalBundle\Entity\Empresa;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of FacturaServicioController
 *
 * @author sergio
 */
class FacturaServicioController extends Controller {

    public function nuevaFSAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::FACTURASERVICIO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $fs = new FacturaServicio ();
//        $id = $this->getRequest()->get('id');
//        $servicio = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Servicio")->find($id);
//        $empresa = new Empresa();
//        $empresa = $this->getEmpresa();
        //Valida el tipo de factura segun la condicion de la empresa ante el IVA
//        if ($servicio->getResponsable()) {
//            if (!($empresa->getResponsable())) {
//                $this->getRequest()->getSession()->set('error', 'Error en la condición del IVA');
//                return $this->redirect($this->generateUrl('servicios'));
//            }
//        } else {
        $formulario = $this->createForm(new FacturaServicioType(), $fs, array('attr' => array('tipo' => 0)));
        $parameters = array('form' => $formulario->createView(),  'error' => null,);
        return $this->render('SisGGFinalBundle:FacturaServicio:FSC.html.twig', $parameters);
        // }
    }

    public function altaFSAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::FACTURASERVICIO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $fs = new FacturaServicio ();
        $empresa = new Empresa();
        $empresa = $this->getEmpresa();
        //Valida el tipo de factura segun la condicion de la empresa ante el IVA
//        if ($servicio->getResponsable()) {
//            if (!($empresa->getResponsable())) {
//                $this->getRequest()->getSession()->set('error', 'Error en la condición del IVA');
//                return $this->redirect($this->generateUrl('servicios'));
//            }
//        } else {
        $formulario = $this->createForm(new FacturaServicioType(), $fs, array('attr' => array('tipo' => 0)));
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $empresa = $this->getEmpresa();
            $r = $empresa->altaFacturaServicio($fs, $this);
            if ($r == null) {
                $msj = "La factura de servicio ha sido exitosamente registrado.";
                $this->getRequest()->getSession()->set('msjFS', $msj);
                return $this->redirect($this->generateUrl('fs'));
            } else {
                return $this->render('SisGGFinalBundle:FacturaServicio:FSC.html.twig', array('form' => $formulario->createView(),  'error' => $r));
            }
        } else {
            return $this->render('SisGGFinalBundle:FacturaServicio:FSC.html.twig', array('form' => $formulario->createView(),  'error' => "Verifique que los campos ingresados sean correctos."));
        }

        //}
    }

    public function fsAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $fs = $this->getDoctrine()->getRepository("SisGGFinalBundle:FacturaServicio")->find($this->getRequest()->get('id'));
            $parameters = array('fs' => $fs, 'id' => $fs->getId(), 'form' => null);
            return $this->render('SisGGFinalBundle:FacturaServicio:detalles.html.twig', $parameters);
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::FACTURASERVICIO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $total = 0;
            $empresa = $this->getEmpresa();
            $hoy = new \DateTime('now');
            $fs = $empresa->buscarFacturaServicioMesAño(0, (date_format($hoy, 'Y')));
            if ($fs != null)
                $total = array_pop($fs);
            //$fs = $this->getDoctrine()->getRepository('SisGGFinalBundle:FacturaServicio')->findAll();
            $msj = $this->getRequest()->getSession()->get('msjFS');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjFS');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'error' => $error, 'registros' => $fs, 'total' => $total, 'form' => null);
            return $this->render('SisGGFinalBundle:FacturaServicio:fs.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    public function buscarFacturaServicioAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $empresa = $this->getEmpresa();
            $array = null;
            $total = 0.00;
            if ($tipo == -1) {
                 $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:FacturaServicio')->findAll();
                foreach ($array as $value) {
                    $total+=$value->getTotal();
                }
            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $empresa->buscarFacturaServicio($tiempo, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarFacturaServicioEntreFechas($fecha1, $fecha2);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarFacturaServicioFecha($fecha1, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarFacturaServicioMesAño($mes, $año);
                if ($array != null)
                    $total = array_pop($array);
            }
            $parameters = array('registros' => $array, 'form' => null, 'total' => $total);
            return $this->render('SisGGFinalBundle:FacturaServicio:cargarTabla.html.twig', $parameters);
        }
    }

//    public function impListaFSAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::FACTURASERVICIO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:FacturaServicio')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:FacturaServicio:imp_lista.' . $format . '.twig', $parameters);
//    }

}

?>
