<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\AgendaType;
use SisGG\FinalBundle\Entity\Agenda;
use SisGG\FinalBundle\Entity\EtiquetaAgenda;
use SisGG\FinalBundle\Form\EtiquetaAgendaType;

/**
 * Description of AgendaController
 *
 * @author sergio
 */
class AgendaController extends Controller {

    public function agendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        //$em = $this->getDoctrine()->getEntityManager();
        $etiquetas = $this->getUser()->getEtiquetas();
        $msj = $this->getRequest()->getSession()->get('msjMer');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjMer');
        $this->getRequest()->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'form' => null, 'error' => $error, 'etiquetas' => $etiquetas);
        return $this->render('SisGGFinalBundle:Agenda:agenda.html.twig', $parameters);
    }

    public function agendasPendientesAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->get('tipo') == "1") {
                //lista de pendientes
                return $this->render('SisGGFinalBundle:Agenda:pendientes.html.twig', array('pendientes' => $this->getUser()->obtenerAgendaPendientes()));
            } elseif ($this->getRequest()->get('tipo') == "2") {
                //marcar como visto
                $agenda = new Agenda();
                $em = $this->getDoctrine()->getEntityManager();
                $agenda = $em->getRepository('SisGGFinalBundle:Agenda')->find($this->getRequest()->get('id'));
                $agenda->setVisto(true);
                $em->flush();
                return new Response(json_encode(array('ok' => 'ok')));
            } elseif ($this->getRequest()->get('tipo') == "3") {
                if ($this->getUser()->obtenerAgendaPendientes() != null) {
                    return new Response(json_encode(array('ok' => true, 'cantidad' => count($this->getUser()->obtenerAgendaPendientes()))));
                } else {
                    return new Response(json_encode(array('ok' => false)));
                }
            }
        }
    }

    public function nuevoAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agenda = new Agenda();
            $formulario = $this->createForm(new AgendaType(), $agenda, array('attr' => array('tipo' => 1)));
            return $this->render('SisGGFinalBundle:Agenda:nuevoAgenda.html.twig', array('error' => null, 'form' => $formulario->createView(), 'r' => 1));
        }
    }

    public function altaAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agenda = new Agenda();
            $formulario = $this->createForm(new AgendaType(), $agenda, array('attr' => array('tipo' => 2, 'id' => $this->getUser()->getId())));
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaAgenda($agenda, $this);
                if ($r == null) {
                    $agenda->setUsuario($this->getUser());
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->render('SisGGFinalBundle:Agenda:nuevoAgenda.html.twig', array('r' => 0, 'agenda' => $agenda, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:Agenda:nuevoAgenda.html.twig', array('r' => true, 'agenda' => $agenda, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Agenda:nuevoAgenda.html.twig', array('r' => true, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos.aa"));
            }
        }
    }

    public function editarAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agenda = new Agenda();
            $em = $this->getDoctrine()->getEntityManager();
            $agenda = $em->getRepository('SisGGFinalBundle:Agenda')->find($this->getRequest()->get('id'));
            $formulario = $this->createForm(new AgendaType(), $agenda, array('attr' => array('tipo' => 2, 'id' => $this->getUser()->getId())));
            return $this->render('SisGGFinalBundle:Agenda:editarAgenda.html.twig', array('error' => null, 'form' => $formulario->createView(), 'r' => 1));
        }
    }

    public function modificarAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agenda = new Agenda();
            $em = $this->getDoctrine()->getEntityManager();
            $agenda = $em->getRepository('SisGGFinalBundle:Agenda')->find($this->getRequest()->get('idAgenda'));
            $formulario = $this->createForm(new AgendaType(), $agenda, array('attr' => array('tipo' => 2, 'id' => $this->getUser()->getId())));
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->modificarAgenda($agenda, $this);
                if ($r == null) {
                    return $this->render('SisGGFinalBundle:Agenda:editarAgenda.html.twig', array('r' => 0, 'agenda' => $agenda, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:Agenda:editarAgenda.html.twig', array('r' => true, 'agenda' => $agenda, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Agenda:editarAgenda.html.twig', array('r' => true, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos.aa"));
            }
        }
    }

    public function eliminarAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agenda = new Agenda();
            $em = $this->getDoctrine()->getEntityManager();
            $agenda = $em->getRepository('SisGGFinalBundle:Agenda')->find($this->getRequest()->get('idAgenda'));
            $formulario = $this->createForm(new AgendaType(), $agenda, array('attr' => array('tipo' => 2, 'id' => $this->getUser()->getId())));
            $empresa = $this->getEmpresa();
            $r = $empresa->eliminarAgenda($agenda, $this);
            return $this->render('SisGGFinalBundle:Agenda:editarAgenda.html.twig', array('error' => null, 'form' => $formulario->createView(), 'r' => 2));
        }
    }

    public function cargarAgendaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $agendas = $this->getUser()->getAgendas();
            $array = null;
            foreach ($agendas as $value) {
                $array[] = array('id' => $value->getId(), 'title' => $value->getAsunto(), 'allDay' => $value->getTodos(), 'color' => '#' . $value->getEtiqueta()->getColor(), 'start' => date_format($value->getInicioFec(), 'm-d-Y ') . ' ' . date_format($value->getInicioHora(), ' H:i'), 'end' => date_format($value->getFinFec(), 'm-d-Y ') . ' ' . date_format($value->getFinHora(), ' H:i'));
            }
            $array = json_encode($array);

            return new Response($array);
        }
    }

    public function nuevaEtiquetaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $formulario = $this->createForm(new EtiquetaAgendaType());
            return $this->render('SisGGFinalBundle:Agenda:nuevaEtiqueta.html.twig', array('error' => null, 'form' => $formulario->createView(), 'r' => 1));
        }
    }

    public function altaEtiquetaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $etiqueta = new EtiquetaAgenda();
            $formulario = $this->createForm(new EtiquetaAgendaType(), $etiqueta);
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaEtiqueta($etiqueta, $this);
                if ($r == null) {
                    $etiqueta->setUsuario($this->getUser());
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->render('SisGGFinalBundle:Agenda:nuevaEtiqueta.html.twig', array('r' => 0, 'etiqueta' => $etiqueta, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:Agenda:nuevaEtiqueta.html.twig', array('r' => true, 'etiqueta' => $etiqueta, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Agenda:nuevaEtiqueta.html.twig', array('r' => true, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos.aa" . $etiqueta->getColor()));
            }
        }
    }

    public function editarEtiquetaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $etiqueta = new EtiquetaAgenda();
            $em = $this->getDoctrine()->getEntityManager();
            $etiqueta = $em->getRepository('SisGGFinalBundle:EtiquetaAgenda')->find($this->getRequest()->get('id'));
            $formulario = $this->createForm(new EtiquetaAgendaType(), $etiqueta, array('attr' => array('tipo' => 2)));
            return $this->render('SisGGFinalBundle:Agenda:editarEtiqueta.html.twig', array('error' => null, 'form' => $formulario->createView(), 'r' => 1));
        }
    }

    public function modificarEtiquetaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $etiqueta = new EtiquetaAgenda();
            $em = $this->getDoctrine()->getEntityManager();
            $etiqueta = $em->getRepository('SisGGFinalBundle:EtiquetaAgenda')->find($this->getRequest()->get('idEtiqueta'));
            $formulario = $this->createForm(new EtiquetaAgendaType(), $etiqueta, array('attr' => array('tipo' => 2)));
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->modificarEtiqueta($etiqueta, $this);
                if ($r == null) {
                    return $this->render('SisGGFinalBundle:Agenda:editarEtiqueta.html.twig', array('r' => 0, 'agenda' => $etiqueta, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:Agenda:editarEtiqueta.html.twig', array('r' => true, 'agenda' => $etiqueta, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Agenda:editarEtiqueta.html.twig', array('r' => true, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos.aa"));
            }
        }
    }

    public function eliminarEtiquetaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $etiqueta = new EtiquetaAgenda();
            $em = $this->getDoctrine()->getEntityManager();
            $etiqueta = $em->getRepository('SisGGFinalBundle:EtiquetaAgenda')->find($this->getRequest()->get('id'));
            $formulario = $this->createForm(new EtiquetaAgendaType(), $etiqueta, array('attr' => array('tipo' => 2)));
            $empresa = $this->getEmpresa();
            $r = $empresa->eliminarEtiqueta($etiqueta, $this);
            return $this->render('SisGGFinalBundle:Agenda:editarEtiqueta.html.twig', array('error' => $r, 'form' => $formulario->createView(), 'r' => 2));
        }
    }

    public function cargarEtiquetasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_AGENDA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
//        if ($this->getRequest()->isXmlHttpRequest()) {
            $etiquetas = $this->getUser()->getEtiquetas();
            return $this->render('SisGGFinalBundle:Agenda:cargarEtiquetas.html.twig', array('etiquetas' => $etiquetas, 'tipo' => $this->getRequest()->get('tipo'), 'form' => null));
//        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>
