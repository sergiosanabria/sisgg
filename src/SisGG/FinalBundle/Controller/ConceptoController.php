<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Concepto;
use SisGG\FinalBundle\Form\ConceptoType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class ConceptoController extends Controller {

    public function conceptosAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Concepto');
        $clientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:Concepto:conceptos.html.twig', array('form' => null, 'conceptos' => $clientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function nuevoConceptoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unConcepto = new Concepto();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new ConceptoType(),$unConcepto);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $unConcepto->setEstado('activo');
                    $em->persist($unConcepto);
                    $em->flush();
                    return $this->redirect($this->generateUrl('conceptos'));
                }
            }
        return $this->render('SisGGFinalBundle:Concepto:nuevoConcepto.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));  
    }
    
    public function editarConceptoAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unConcepto = $this->getDoctrine()->getRepository("SisGGFinalBundle:Concepto")->find($this->getRequest()->get("id"));
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new ConceptoType(),$unConcepto);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->redirect($this->generateUrl('conceptos'));
                }
            }
        return $this->render('SisGGFinalBundle:Concepto:editarConcepto.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id")));  
    }
    
    public function activarConceptoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Concepto');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("conceptos"));
    }
    
    public function borrarConceptoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Concepto');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("conceptos"));
    }
    
    public function conceptoAction(){
        $em = $this->getDoctrine()->getEntityManager();
        /*@var $concepto Concepto*/
        $concepto= $em->getRepository("SisGGFinalBundle:Concepto")->find($this->getRequest()->get("id"));
        $retorno = array('concepto'=>$concepto,"aclaracion"=>$concepto->getDescripcion());
        return new Response(json_encode($retorno));
    }
    
    public function conceptosEntradaSalidaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->getRequest()->get("id")==0){
            $conceptos= $em->getRepository("SisGGFinalBundle:Concepto")->findEntradaSalida(1);
        }else{
            $conceptos= $em->getRepository("SisGGFinalBundle:Concepto")->findEntradaSalida(0);
        }
        $retorno = array();
        /*@var $concepto Concepto*/
        foreach ($conceptos as $concepto) {
            $retorno[ ] = array('id'=>$concepto->getId(),'nombre'=>$concepto->getNombre());
        }
        return new Response(json_encode($retorno));
    }
}