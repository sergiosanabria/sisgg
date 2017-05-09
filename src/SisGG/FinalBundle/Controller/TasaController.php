<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\TasaType;
use SisGG\FinalBundle\Entity\Tasa;
use SisGG\FinalBundle\Entity\Empresa;
/**
 * Description of TasaController
 *
 * @author sergio
 */
class TasaController extends Controller {

    public function indexAction() {
        return new Response('Hello world!');
    }
    public function nuevaTasaAction() {
        $repositorio = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:UnidadMedida');
        $um = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new TasaType());
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'umPadre'=>$um->getNombre());
        return $this->render('SisGGFinalBundle:Tasa:nuevaTasa.html.twig', $parameters);
    }
    public function altaTasaAction() {
        $tasa = new Tasa();
        $empresa = $this->getEmpresa();        
        $formulario = $this->createForm(new TasaType(), $tasa);
        $formulario->bindRequest($this->getRequest());
        if ($tasa->getValor()==0)
            $tasa->setValor (1);
        if ($formulario->isValid()) {
            $r = $empresa->altaTasa ($tasa, $this);
            if ($r == null) {
                $msj = "La tasa " . $tasa->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjTas', $msj);
                return $this->redirect($this->generateUrl('ums', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:Tasa:nuevaTasa.html.twig', array('form' => $formulario->createView(), 'error' => $r, 'umPadre'=>$tasa->getUm()));
            }
        }
        return $this->render('SisGGFinalBundle:Tasa:nuevaTasa.html.twig', array('form' => $formulario->createView(), 'umPadre'=>$tasa->getUm(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }
    public function editarTasaAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Tasa');
        $tasa = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new TasaType(), $tasa);
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'pivote'=>$tasa->getPivote(),  'umPadre'=>$tasa->getUm(),'id'=>$tasa->getId(),'nombre'=>$tasa->getNombre());
        return $this->render('SisGGFinalBundle:Tasa:editarTasa.html.twig', $parameters);
    }

    public function modificarTasaAction(Empresa $empresa = null) {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Tasa');
        $tasa = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new TasaType(), $tasa);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarTasa($tasa, $this);
            if ($r == null) {
                $msj = "La tasa " . $tasa->getNombre() . " ha sido exitosamente modificada.";
                $this->getRequest()->getSession()->set('msjTas', $msj);
                return $this->redirect($this->generateUrl('ums', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:Tasa:editarTasa.html.twig', array('form' => $formulario->createView(), 'pivote'=>$tasa->getPivote(),'id'=>$tasa->getId(),'nombre'=>$tasa->getNombre(),  'umPadre'=>$tasa->getUm(), 'id' => $tasa->getId(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:Tasa:editarTasa.html.twig', array('form' => $formulario->createView(),'id'=>$tasa->getId(), 'pivote'=>$tasa->getPivote(),'nombre'=>$tasa->getNombre(),  'umPadre'=>$tasa->getUm(), 'id' => $tasa->getId(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }
      public function eliminarTasaAction() {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Tasa');
        $tasa = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $msj = $empresa->eliminarTasa($tasa, $this);
        if ($msj!=null){
            $this->getRequest()->getSession()->set('msjTas', $msj);
            return $this->redirect($this->generateUrl('ums'));
        }else{
             $this->getRequest()->getSession()->set('error', "La tasa ".$tasa->getNombre()." no puede ser elimida puesto que esta asociada a un producto o un ingrediente.");
            return $this->redirect($this->generateUrl('ums'));
        }
        
    }
    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>
