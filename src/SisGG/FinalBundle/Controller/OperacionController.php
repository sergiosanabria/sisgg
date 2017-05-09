<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Operacion;
use SisGG\FinalBundle\Form\OperacionType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class OperacionController extends Controller {

    public function operacionesAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Operacion');
        $clientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:Operacion:operaciones.html.twig', array('form' => null, 'operaciones' => $clientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function nuevoOperacionAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unOperacion = new Operacion();
        $form = $this->createForm(new OperacionType(),$unOperacion);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($unOperacion);
                    $em->flush();
                    return $this->redirect($this->generateUrl('operaciones'));
                }
            }
        return $this->render('SisGGFinalBundle:Operacion:nuevoOperacion.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));  
    }
    
    public function editarOperacionAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unOperacion = $this->getDoctrine()->getRepository("SisGGFinalBundle:Operacion")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new OperacionType(),$unOperacion);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->redirect($this->generateUrl('operaciones'));
                }
            }
        return $this->render('SisGGFinalBundle:Operacion:editarOperacion.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id")));  
    }
    
    public function activarOperacionAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Operacion');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("operaciones"));
    }
    
    public function borrarOperacionAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Operacion');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("operaciones"));
    }
    
    public function operacionAction(){
        $em = $this->getDoctrine()->getEntityManager();
        /*@var $operacion Operacion*/
        $operacion= $em->getRepository("SisGGFinalBundle:Operacion")->find($this->getRequest()->get("id"));
        $retorno = array('operacion'=>$operacion,"aclaracion"=>$operacion->getDescripcion());
        return new Response(json_encode($retorno));
    }
    
    public function operacionesEntradaSalidaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->getRequest()->get("id")==0){
            $operaciones= $em->getRepository("SisGGFinalBundle:Operacion")->findEntradaSalida(1);
        }else{
            $operaciones= $em->getRepository("SisGGFinalBundle:Operacion")->findEntradaSalida(0);
        }
        $retorno = array();
        /*@var $operacion Operacion*/
        foreach ($operaciones as $operacion) {
            $retorno[ ] = array('id'=>$operacion->getId(),'nombre'=>$operacion->getNombre());
        }
        return new Response(json_encode($retorno));
    }
}