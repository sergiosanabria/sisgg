<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Sector;
use SisGG\FinalBundle\Form\SectorType;
use SisGG\FinalBundle\Entity\Mesa;

/**
 * Description of SectorController
 *
 * @author martin
 */
class SectorController extends Controller {

    //Muestra los sectores que se encuentran en registrados en el sistema.
    public function sectoresAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Sector")->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render("SisGGFinalBundle:Sector:sectores.html.twig", array('estado'=>$estado,'form' => null, 'sectores' => $sectores, 'gestor_autenticacion' => $gestor));
    }

    //Registra un Nuevo Sector en el Sistema
    public function nuevoSectorAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unSector = new Sector();
        $form = $this->createForm(new SectorType(), $unSector);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                if ($unSector->getMesas() != null) {
                    foreach ($unSector->getMesas() as $mesa) {
                        $mesa->setSector($unSector);
                    }
                }
                $em->persist($unSector);
                $em->flush();
                return $this->redirect($this->generateUrl("sectores"));
            }
        }
        return $this->render('SisGGFinalBundle:Sector:nuevoSector.html.twig', array('form' => $form->createView(), 'gestor_autenticacion' => $gestor));
    }

    //Actualiza el Sector
    public function actualizarSectorAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unSector = $em->getRepository("SisGGFinalBundle:Sector")->find($this->getRequest()->get("id"));
        $mesasIniciales = array();
        // Create an array of the current Tag objects in the database
        foreach ($unSector->getMesas() as $mesa) {
            $mesasIniciales[] = $mesa;
        }
        $form = $this->createForm(new SectorType(), $unSector);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()) {
                if ($unSector->getMesas() != null) {
                    foreach ($unSector->getMesas() as $mesa) {
                        $mesa->setSector($unSector);
                    }
                }
                foreach ($unSector->getMesas() as $mesa) {
                    foreach ($mesasIniciales as $key => $toDel) {
                        if ($toDel->getId() === $mesa->getId()) {
                            unset($mesasIniciales[$key]);
                        }
                    }
                }
                foreach ($mesasIniciales as $mesa) {
                    // remove the Task from the Tag
                    $mesa->setSector(null);

                    // if it were a ManyToOne relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $em->remove($mesa);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $em->remove($tag);
                }
                $em->persist($unSector);
                $em->flush();
                return $this->redirect($this->generateUrl("sectores"));
            }
        }
        return $this->render('SisGGFinalBundle:Sector:actualizarSector.html.twig', array('form' => $form->createView(), 'id' => $this->getRequest()->get('id'), 'gestor_autenticacion' => $gestor));
    }

    public function activarSectorAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Sector');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("sectores"));
    }

    public function borrarSectorAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Sector');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("sectores"));
    }

    public function sectoresMesasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $historialPedidos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pedido')->findHistorial();
        $sectores = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Sector")->findAll();
        return $this->render('SisGGFinalBundle:Sector:sectoresMesas.html.twig', array('historialPedidos' => $historialPedidos, 'form' => null, 'gestor_autenticacion' => $gestor, 'sectores' => $sectores));
    }

    public function detallesMesaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $mesa = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get('id'));
        return $this->render('SisGGFinalBundle:Sector:detallesMesa.html.twig', array('form' => null, 'gestor_autenticacion' => $gestor, 'mesa' => $mesa));
    }

    public function liberarMesaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $mesa Mesa */
        $mesa = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Mesa")->find($this->getRequest()->get('id'));
        $mesa->getPedidoActivo()->setEstado('Terminado');
        $mesa->getPedidoActivo()->setMesa(null);
        $mesa->setPedidoActivo(null);
        $this->getDoctrine()->getEntityManager()->flush();
        return $this->redirect($this->generateUrl("sectores_mesas"));
    }

    //Agrupa Mesas para que puedan ser utilizadas como una sola
    public function agruparMesas() {
        
    }

    //Desagrupa las Mesas
    public function desagruparMesas() {
        
    }

}