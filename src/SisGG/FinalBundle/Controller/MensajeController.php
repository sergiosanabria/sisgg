<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Mensaje;

/**
 * Description of MensajeController
 *
 * @author sergio
 */
class MensajeController extends Controller {

//    public function mostrarNuevosAction() {
//        $mensajes = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->findBy(array('nuevo' => 1));
//        $array = null;
//        if ($mensajes != null) {
//            foreach ($mensajes as $value) {
//                $array[] = array('id' => $value->getId(), 'mensaje' => $value->getMensaje(), 'hay' => true);
//                $value->setNuevo(0);
//                $this->getDoctrine()->getEntityManager()->flush();
//            }
//            $this->getDoctrine()->getEntityManager()->flush();
//            return new Response(json_encode($array));
//        } else {
//            $array[] = array('id' => 0, 'mensaje' => null, 'hay' => false);
//            return new Response(json_encode($array));
//        }
//    }
    
    public function mostrarNuevosAction() {
        $mensajes = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->findNuevos($this->getUser());
        $array = null;
        if ($mensajes != null) {
            foreach ($mensajes as $value) {
                $array[] = array('id' => $value->getId(), 'mensaje' => $value->getMensaje(), 'hay' => true);
                $value->setNuevo(0);
                $this->getDoctrine()->getEntityManager()->flush();
            }
            $this->getDoctrine()->getEntityManager()->flush();
            return new Response(json_encode($array));
        } else {
            $array[] = array('id' => 0, 'mensaje' => null, 'hay' => false);
            return new Response(json_encode($array));
        }
    }

    public function marcarLeidoAction() {
        $mensaje = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->findAll();
        if ($mensaje !=null){
            foreach ($mensaje as $value) {
                $value->setLeido(1);
            }
        }
        
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response('');
    }
    public function eliminarMsjAction() {
        $mensaje = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->find($this->getRequest()->get('id'));
        $this->getDoctrine()->getEntityManager()->remove($mensaje);
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response('');
    }

    public function mostrarNotificacionesNoLeidosAction() {
        $mensajes = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->findBy(
             array(), 
             array('id' => 'DESC')
           ); ;
        $noLeidos=0;
        if ($mensajes != null) {
            foreach ($mensajes as $value) {
                if ($value->getLeido()==false)
                $noLeidos++;
            }
            $parameters = array('form' => null, 'mensajes' => $mensajes, 'noLeidos'=>$noLeidos);
            return $this->render('SisGGFinalBundle:Mensaje:notificaciones.html.twig', $parameters);
       
        }
        $parameters = array('form' => null, 'mensajes' => $mensajes, 'noLeidos'=>$noLeidos);
            return $this->render('SisGGFinalBundle:Mensaje:notificaciones.html.twig', $parameters);
    }

}

?>
