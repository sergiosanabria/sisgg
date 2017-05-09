<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\CondicionIva;
use SisGG\FinalBundle\Form\CondicionIvaType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class CondicionIvaController extends Controller {

    public function condicionesIvaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CondicionIva');
        $clientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:CondicionIva:condicionesIva.html.twig', array('form' => null, 'condicionesIva' => $clientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function nuevoCondicionIvaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unCondicionIva = new CondicionIva();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new CondicionIvaType(),$unCondicionIva);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $unCondicionIva->setEstado('activo');
                    $em->persist($unCondicionIva);
                    $em->flush();
                    return $this->redirect($this->generateUrl('condiciones_iva'));
                }
            }
        return $this->render('SisGGFinalBundle:CondicionIva:nuevoCondicionIva.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));  
    }
    
    public function editarCondicionIvaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unCondicionIva = $this->getDoctrine()->getRepository("SisGGFinalBundle:CondicionIva")->find($this->getRequest()->get("id"));
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new CondicionIvaType(),$unCondicionIva);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->redirect($this->generateUrl('condiciones_iva'));
                }
            }
        return $this->render('SisGGFinalBundle:CondicionIva:editarCondicionIva.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id")));  
    }
    
    public function activarCondicionIvaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CondicionIva');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("condiciones_iva"));
    }
    
    public function borrarCondicionIvaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CondicionIva');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("condiciones_iva"));
    }
    
    public function condicionIvaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        /*@var $condicionIva CondicionIva*/
        $condicionIva= $em->getRepository("SisGGFinalBundle:CondicionIva")->find($this->getRequest()->get("id"));
        $retorno = array('condicionIva'=>$condicionIva,"aclaracion"=>$condicionIva->getDescripcion());
        return new Response(json_encode($retorno));
    }
    
    public function condicionesIvaEntradaSalidaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->getRequest()->get("id")==0){
            $condicionesIva= $em->getRepository("SisGGFinalBundle:CondicionIva")->findEntradaSalida(1);
        }else{
            $condicionesIva= $em->getRepository("SisGGFinalBundle:CondicionIva")->findEntradaSalida(0);
        }
        $retorno = array();
        /*@var $condicionIva CondicionIva*/
        foreach ($condicionesIva as $condicionIva) {
            $retorno[ ] = array('id'=>$condicionIva->getId(),'nombre'=>$condicionIva->getNombre());
        }
        return new Response(json_encode($retorno));
    }
}