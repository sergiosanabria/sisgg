<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Redireccion;
use SisGG\FinalBundle\Form\RedireccionType;
use Symfony\Component\HttpFoundation\Response;
/**
 * @author martin
 */
class RedireccionController extends Controller {
    
    public function nuevaRedireccionAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $url = $this->getRequest()->get("url");
        $parametros = $this->getRequest()->get("parametros");
        $parametros = unserialize($parametros);
        $unaRedireccion = $this->get('gestor_redirecciones')->nuevaRedireccion($this->getUser(),$url,$parametros);
        $form = $this->createForm(new RedireccionType(), $unaRedireccion);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($unaRedireccion);
                    $em->flush();
            }
        }
        return $this->render('SisGGFinalBundle:Redireccion:nuevaRedireccion.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'parametros'=>$parametros));
    }
    
    public function obtenerRedireccionesAction(){
        return new Response(json_encode($this->get('gestor_redirecciones')->obtenerRedirecciones($this->getUser())));
    }
    
}

?>
