<?php

namespace SisGG\FinalBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Form\ClienteType;
use SisGG\FinalBundle\Model\GestorClientes;
use SisGG\FinalBundle\Entity\GrupoCliente;
use SisGG\FinalBundle\Form\GrupoClienteType;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class GrupoClienteController extends Controller {

    public function nuevoGrupoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $grupoCliente = new GrupoCliente();
        $form = $this->createForm(new GrupoClienteType(), $grupoCliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($grupoCliente);
                    $em->flush();
                    return $this->redirect($this->generateUrl('grupos_clientes'));
            }
        }
        return $this->render('SisGGFinalBundle:GrupoCliente:nuevoGrupoCliente.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
    
    public function editarGrupoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unGrupoCliente = $em->getRepository("SisGGFinalBundle:GrupoCliente")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new GrupoClienteType(), $unGrupoCliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('grupos_clientes'));
            }
        }
        return $this->render('SisGGFinalBundle:GrupoCliente:editarGrupoCliente.html.twig', array('form' => $form->createView(),'id'=>$unGrupoCliente->getId(),'gestor_autenticacion'=>$gestor));
    }

    public function borrarGrupoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:GrupoCliente');
        $grupoCliente = $repositorio->find($this->getRequest()->get('id'));
        $grupoCliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($grupoCliente);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('grupos_clientes',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('grupos_clientes'));
    }
    
    public function activarGrupoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:GrupoCliente');
        $grupoCliente = $repositorio->find($this->getRequest()->get('id'));
        $grupoCliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($grupoCliente);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('grupos_clientes',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('grupos_clientes'));
    }
    
    
    public function gruposClientesAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:GrupoCliente');
        $gruposClientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:GrupoCliente:gruposClientes.html.twig', array('form' => null, 'gruposClientes' => $gruposClientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }

}