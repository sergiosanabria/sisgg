<?php

namespace SisGG\FinalBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Recargo;
use SisGG\FinalBundle\Form\RecargoType;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class RecargoController extends Controller {

    public function nuevoRecargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $recargo = new Recargo();
        $recargo->setTipoPorcentaje(true);
        $form = $this->createForm(new RecargoType(), $recargo);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($recargo);
                    $em->flush();
                    return $this->redirect($this->generateUrl('recargos'));
            }
        }
        return $this->render('SisGGFinalBundle:Recargo:nuevoRecargo.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
    
    public function editarRecargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unRecargo = $em->getRepository("SisGGFinalBundle:Recargo")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new RecargoType(), $unRecargo);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('recargos'));
            }
        }
        return $this->render('SisGGFinalBundle:Recargo:editarRecargo.html.twig', array('form' => $form->createView(),'id'=>$unRecargo->getId(),'gestor_autenticacion'=>$gestor));
    }

    public function borrarRecargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Recargo');
        $recargo = $repositorio->find($this->getRequest()->get('id'));
        $recargo->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($recargo);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('recargos',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('recargos'));
    }
    
    public function activarRecargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Recargo');
        $recargo = $repositorio->find($this->getRequest()->get('id'));
        $recargo->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($recargo);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('recargos',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('recargos'));
    }
    
    
    public function recargosAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Recargo');
        $recargos = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:Recargo:recargos.html.twig', array('form' => null, 'recargos' => $recargos,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }

}