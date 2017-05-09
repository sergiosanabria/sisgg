<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Pais;
use SisGG\FinalBundle\Form\PaisType;
/**
 * Description of PaisController
 *
 * @author martin
 */
class PaisController extends Controller {

    public function paisesAction() {
       $paises = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Pais")->findAll();
       return $this->render("SisGGFinalBundle:Pais:paises.html.twig",array('form'=>null,'paises'=>$paises));
    }
    
    public function nuevoPaisAction() {
        $unPais = new Pais();
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new PaisType,$unPais);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($unPais);
                    $em->flush();
                    return $this->redirect($this->generateUrl('paises'));
                }
            }
        return $this->render('SisGGFinalBundle:Pais:nuevoPais.html.twig',array('form' => $form->createView()));  
    }
    
    public function editarPaisAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $unPais = $em->getRepository("SisGGFinalBundle:Pais")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new PaisType,$unPais);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush($unPais);
                    return $this->redirect($this->generateUrl('paises'));
                }
        }
        return $this->render('SisGGFinalBundle:Pais:editarPais.html.twig',array('form' => $form->createView(),'id'=>$this->getRequest()->get("id")));  
    }
    
    public function eliminarPaisAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository("SisGGFinalBundle:Pais");
        $unPais = $repositorio->find($this->getRequest()->get("id"));
        $this->getDoctrine()->getEntityManager()->remove($unPais);
        $em->flush();
    }

}