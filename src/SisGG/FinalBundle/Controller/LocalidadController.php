<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Localidad;
use SisGG\FinalBundle\Form\LocalidadType;
/**
 * Description of LocalidadController
 *
 * @author martin
 */
class LocalidadController extends Controller {

    public function localidadesAction() {
       $provincia = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->find($this->getRequest()->get("id"));
       return $this->render("SisGGFinalBundle:Localidad:localidades.html.twig",array('form'=>null,'provincia'=>$provincia));
    }

    public function nuevaLocalidadAction() {
       $em = $this->getDoctrine()->getEntityManager(); 
       $unaLocalidad = new Localidad();
       if ($this->getRequest()->get("id")!=null){$unaLocalidad->setProvincia($em->getRepository("SisGGFinalBundle:Provincia")->find($this->getRequest()->get("id")));}
       $form = $this->createForm(new LocalidadType(), $unaLocalidad);
       if ($this->getRequest()->getMethod()==="POST"){
               $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($unaLocalidad);
                    $em->flush();
                    return $this->redirect($this->generateUrl('localidades')."?id=".$unaLocalidad->getProvincia()->getId());
                }
            }
        return $this->render('SisGGFinalBundle:Localidad:nuevaLocalidad.html.twig',array('form' => $form->createView()));  
    }
    
    public function editarLocalidadAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $unaLocalidad = $em->getRepository("SisGGFinalBundle:Localidad")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new LocalidadType,$unaLocalidad);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush($unaLocalidad);
                    return $this->redirect($this->generateUrl('localidades')."?id=".$unaLocalidad->getProvincia()->getId());
                }
        }
        return $this->render('SisGGFinalBundle:Localidad:editarLocalidad.html.twig',array('form' => $form->createView(),'id'=>$this->getRequest()->get("id")));   
    }
    
    public function eliminarLocalidadAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio= $em->getRepository("SisGGFinalBundle:Localidad");
        $unaLocalidad = $repositorio->find($this->getRequest()->get("id"));
        $this->getDoctrine()->getEntityManager()->remove($unaLocalidad);
        $em->flush();
    }

}