<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Ciudad;
use SisGG\FinalBundle\Form\CiudadType;
use SisGG\FinalBundle\Form\ProvinciaType;
use SisGG\FinalBundle\Entity\Provincia;
use SisGG\FinalBundle\Entity\Empresa;

/**
 * Description of CiudadController
 *
 * @author martin
 */
class CiudadController extends Controller {

    public function nuevaCiudadAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new CiudadType());
        $formProv = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        $msj = $this->getRequest()->getSession()->get('msjCiu');
        $this->getRequest()->getSession()->remove('msjCiu');
        $parameters = array('form' => $formulario->createView(), 'formProv' => $formProv->createView(), 'provincias' => $provincias, 'msj' => $msj, 'error' => null);
        return $this->render('SisGGFinalBundle:Ciudad:nuevaCiudad.html.twig', $parameters);
    }

    public function altaCiudadAction(Empresa $empresa = null) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $ciudad = new Ciudad();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new CiudadType, $ciudad);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->agregarCiudad($ciudad, $this);
            if ($r == null) {
                $msj = "La Ciudad " . $ciudad->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjCiu', $msj);
                return $this->redirect($this->generateUrl('ciudades', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:Ciudad:nuevaCiudad.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        $parameters = array('form' => $formulario->createView(), 'error' => "Verifique los campos cargados." . $ciudad->getProvincia());
        return $this->render('SisGGFinalBundle:Ciudad:nuevaCiudad.html.twig', $parameters);
    }

    public function altaCiudadComboAction(Empresa $empresa = null) {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_NUEVO)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $p = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->find($this->getRequest()->get('prov'));
            $ciudad = new Ciudad();
            $empresa = $this->getEmpresa();
            $nombre = $this->getRequest()->get('nomCiu');
            $cp = $this->getRequest()->get('codPos');


            If ($nombre != null && is_numeric($cp)) {
                $ciudad->setNombre($nombre);
                $ciudad->setCodigoPostal($cp);
                $ciudad->setProvincia($p);

                // if ($formulario->isValid()) {
                $r = $empresa->agregarCiudad($ciudad, $this);
                if ($r == null) {
                    $msj = "La Ciudad " . $ciudad->getNombre() . " ha sido exitosamente registrada.";
                    //$this->getRequest()->getSession()->set('msj', $msj);
                    return $this->render('SisGGFinalBundle:Ciudad:errorCiu.html.twig', array('error' => null, 'msje' => $msj));
                } else {
                    return $this->render('SisGGFinalBundle:Ciudad:errorCiu.html.twig', array('msje' => null, 'error' => $r));
                }
                // }
                $parameters = array('form' => $formulario->createView(), 'error' => "Verifique los campos cargados.", 'msje' => null);
                return $this->render('SisGGFinalBundle:Ciudad:errorCiu.html.twig', $parameters);
            } else {
                return $this->render('SisGGFinalBundle:Ciudad:errorCiu.html.twig', array('error' => "Verifique que los campos ingresados sean correctos.", 'msje' => null));
            }
        } else {
            throw $this->createNotFoundException('La página solitada no puede ser accedida.');
        }
    }

    public function editarCiudadAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ciudad');
        $ciudad = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new CiudadType(), $ciudad);
        //$this->getRequest()->getSession()->set('idCiu', $ciudad->getId());
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'id' => $ciudad->getId(), 'nombre' => $ciudad->getNombre());
        return $this->render('SisGGFinalBundle:Ciudad:editarCiudad.html.twig', $parameters);
    }

    public function modificarCiudadAction(Empresa $empresa = null) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $this->getEmpresa();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ciudad');
        $ciudad = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new CiudadType(), $ciudad);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarCiudad($ciudad, $this);
            if ($r == null) {
                $msj = "La Ciudad " . $ciudad->getNombre() . " ha sido exitosamente modificada.";
                $this->getRequest()->getSession()->set('msjCiu', $msj);
                return $this->redirect($this->generateUrl('ciudades', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:Ciudad:editarCiudad.html.twig', array('form' => $formulario->createView(), 'id' => $ciudad->getId(), 'error' => $r, 'nombre' => $ciudad->getNombre()));
            }
        }
        return $this->render('SisGGFinalBundle:Ciudad:editarCiudad.html.twig', array('form' => $formulario->createView(), 'id' => $ciudad->getId(), 'nombre' => $ciudad->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function eliminarCiudadAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ciudad');
        $ciudad = $repositorio->find($this->getRequest()->get('id'));
        $ciudades = $repositorio->findAll();
        $r = $empresa->eliminarCiudad($ciudad, $this);
        if ($r == null) {
            $msj = "La Ciuadad " . $ciudad->getNombre() . " ha sido exitosamente eliminada.";
            if ($this->getRequest()->get('tipo') != null) {
                $this->getRequest()->getSession()->set('msjProv', $msj);
                return $this->redirect($this->generateUrl('provincias'));
            } else {
                $this->getRequest()->getSession()->set('msjCiu', $msj);
                return $this->redirect($this->generateUrl('ciudades', array('msj' => null, 'error' => null)));
            }
        } else {
            if ($this->getRequest()->get('tipo') != null) {
                $this->getRequest()->getSession()->set('errorProv', $r);
                return $this->redirect($this->generateUrl('provincias'));
            } else {
                $this->getRequest()->getSession()->set('errorCiu', $r);
                return $this->redirect($this->generateUrl('ciudades'));
            }
        }
    }

    public function ciudadesAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CIUDAD_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Ciudad');
        $ciudades = $repositorio->findAll();
        $msj = $this->getRequest()->getSession()->get('msjCiu');
        $this->getRequest()->getSession()->remove('msjCiu');
        $error = $this->getRequest()->getSession()->get('errorCiu');
        $this->getRequest()->getSession()->remove('errorCiu');
        $parameters = array('ciudades' => $ciudades, 'form' => null, 'msj' => $msj, 'error' => $error);
        return $this->render('SisGGFinalBundle:Ciudad:ciudades.html.twig', $parameters);
    }

    public function ciudadesprovinciaAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $provincia = $this->getDoctrine()->getRepository('SisGGFinalBundle:Provincia')->find($this->getRequest()->get('id'));
            $ciudades = $this->getDoctrine()->getRepository('SisGGFinalBundle:Ciudad')->findBy(array('provincia' => $provincia), array('nombre' => 'asc'));
            $parameters = array('ciudades' => $ciudades, 'tipo' => $this->getRequest()->get('tipo'));
            return $this->render('SisGGFinalBundle:Ciudad:ciudadesProvincias.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}