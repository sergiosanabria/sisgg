<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\TipoDocumento;
use SisGG\FinalBundle\Form\TipoDocumentoType;
/**
 * Description of TipoDocumentoController
 *
 * @author martin
 */
class TipoDocumentoController extends Controller {

    public function tiposDocumentoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoDocumento');
        $clientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:TipoDocumento:tiposDocumento.html.twig', array('form' => null, 'tiposDocumento' => $clientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function nuevoTipoDocumentoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unTipoDocumento = new TipoDocumento();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new TipoDocumentoType(),$unTipoDocumento);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $unTipoDocumento->setEstado('activo');
                    $em->persist($unTipoDocumento);
                    $em->flush();
                    return $this->redirect($this->generateUrl('tipos_documento'));
                }
            }
        return $this->render('SisGGFinalBundle:TipoDocumento:nuevoTipoDocumento.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));  
    }
    
    public function editarTipoDocumentoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unTipoDocumento = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoDocumento")->find($this->getRequest()->get("id"));
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new TipoDocumentoType(),$unTipoDocumento);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->redirect($this->generateUrl('tipos_documento'));
                }
            }
        return $this->render('SisGGFinalBundle:TipoDocumento:editarTipoDocumento.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id")));  
    }
    
    public function activarTipoDocumentoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoDocumento');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("tipos_documento"));
    }
    
    public function borrarTipoDocumentoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoDocumento');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("tipos_documento"));
    }
    
    

}