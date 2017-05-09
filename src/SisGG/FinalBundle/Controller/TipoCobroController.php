<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\TipoCobro;
use SisGG\FinalBundle\Form\TipoCobroType;
use SisGG\FinalBundle\Entity\Campo;

/**
 * Description of TipoDocumentoController
 *
 * @author martin
 */
class TipoCobroController extends Controller {

    public function tiposCobroAction() {
        $gestor = $this->get("gestor_autenticacion");
        $estado=null;
        $estado=$msj = $this->getRequest()->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoCobro');
        $tiposCobro=null;
        $cantidad=0;
        if ($estado==1){
            $tiposCobro = $repositorio->findBy(array('activo' => false));
        }else{
            $tiposCobro = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad=  count($inactivos);
        }
        $msj=null;
        $msj=  $this->getRequest()->getSession()->get('msjTC');
        $this->getRequest()->getSession()->remove('msjTC');
        $error=null;
        $error=  $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjTC');
        return $this->render("SisGGFinalBundle:TipoCobro:tiposCobro.html.twig", array('msj' => $msj,'estado'=>$estado ,'form' => null,'cantidad'=>$cantidad,'error'=>$error , "tiposCobro" => $tiposCobro, 'gestor_autenticacion' => $gestor));
    }

    public function nuevoTipoCobroAction() {
        $gestor = $this->get("gestor_autenticacion");
        $em = $this->getDoctrine()->getEntityManager();
        $tipoCobro = new TipoCobro();
        $form = $this->createForm(new TipoCobroType(), $tipoCobro);
        if ($this->getRequest()->getMethod() === "POST") {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $tipoCobro->setActivo(true);
                foreach ($tipoCobro->getCampos() as $campo) { //Hago las asociaciones que no tiene hechas
                    $campo->setTipoCobro($tipoCobro);
                }
                
                $this->getRequest()->getSession()->set('msjTC', 'El nuevo tipo de cobro/pago ha sido correctamente registrado.');
                $em->persist($tipoCobro);
                $em->flush();
                return $this->redirect($this->generateUrl("tipos_cobro"));
            }
        }
        $parameters = array("form" => $form->createView(), 'gestor_autenticacion' => $gestor);
        return $this->render("SisGGFinalBundle:TipoCobro:nuevoTipoCobro.html.twig", $parameters);
    }

    public function editarTipoCobroAction() {
        $gestor = $this->get("gestor_autenticacion");
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $tipoCobro TipoCobro */
        $tipoCobro = $em->getRepository("SisGGFinalBundle:TipoCobro")->find($this->getRequest()->get('id'));
        if ($tipoCobro->getEditable()==false){
            $this->getRequest()->getSession()->set('error', 'El tipo de cobro/pago no puede ser editado o eliminado.');
            return $this->redirect($this->generateUrl("tipos_cobro"));
        }
        $camposOriginales = array();
        foreach ($tipoCobro->getCampos() as $campo) {
            $camposOriginales[] = $campo;
        }
        $form = $this->createForm(new TipoCobroType(), $tipoCobro);
        if ($this->getRequest()->getMethod() === "POST") {
           
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                foreach ($tipoCobro->getCampos() as $campo) {
                    foreach ($camposOriginales as $key => $toDel) {
                        if ($toDel->getId() === $campo->getId()) {
                            unset($camposOriginales[$key]);
                        }
                    }
                }
                foreach ($camposOriginales as $campo) {
                    $campo->setTipoCobro(null);
                    $tipoCobro->removeCampo($campo);
                    $em->persist($campo);
                }
                foreach ($tipoCobro->getCampos() as $campo) { //Hago las asociaciones que no tiene hechas
                    $campo->setTipoCobro($tipoCobro);
                }
                $em->persist($tipoCobro);
                $em->flush();
                $this->getRequest()->getSession()->set('msjTC', 'El tipo de cobro/pago ha sido correctamente modificado.');
                return $this->redirect($this->generateUrl("tipos_cobro"));
            }
        }
        $parameters = array("form" => $form->createView(), 'gestor_autenticacion' => $gestor, 'id' => $this->getRequest()->get('id'));
        return $this->render("SisGGFinalBundle:TipoCobro:editarTipoCobro.html.twig", $parameters);
    }
      public function eliminarTipoCobroAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:TipoCobro');
        $tipo = $repositorio->find($this->getRequest()->get('id'));
        if ($tipo->getEditable()==false){
            $this->getRequest()->getSession()->set('error', 'El tipo de cobro/pago no puede ser editado o eliminado.');
            return $this->redirect($this->generateUrl("tipos_cobro"));
        }
        $tipo->setActivo(false);
        $em->flush($tipo);
        $this->getRequest()->getSession()->set('msjTC', 'El  tipo de cobro/pago ha sido correctamente eliminado.');
        return $this->redirect($this->generateUrl('tipos_cobro'));
    }
     public function activarTipoCobroAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /*@var $tipo TipoCobro */
                $tipo = $em->getRepository("SisGGFinalBundle:TipoCobro")->find($id);
                $tipo->setActivo(true);
                $em->flush($tipo);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $tipo->getNombre() )));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function tipoCobroAction() {
        $retorno = array();
        /* @var $tipoCobro TipoCobro*/
        if (!$this->getRequest()->get('id',null)){
            $tiposCobro = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->findBy(array('activo'=>true));
            /* @var $campo Campo*/
            foreach ($tiposCobro as $tipoCobro) {
                $campos = array('id'=>$tipoCobro->getId(),'nombre'=>$tipoCobro->getNombre());
                $retorno[] = $campos;
            }
        }else{
            /*@var $tipoCobro TipoCobro*/
            $tipoCobro = $this->getDoctrine()->getRepository("SisGGFinalBundle:TipoCobro")->find($this->getRequest()->get('id'));
            $campos = array();
            /* @var $campo Campo*/
            foreach ($tipoCobro->getCampos() as $campo) {
                $campos[] = array('id'=>$campo->getId(),'ejemplo'=>$campo->getEjemplo(),'nombre'=>$campo->getNombre(),'patron'=>$campo->getPatron(),'requerido'=>$campo->getRequerido(),'tipoDato' => $campo->getTipoDato());
            }
            $retorno = array('id'=>$tipoCobro->getId(),'nombre'=>$tipoCobro->getNombre(),'darCambio'=>$tipoCobro->getDarCambio(),'montoMaximo'=>$tipoCobro->getMontoMaximo(),'montoMinimo'=>$tipoCobro->getMontoMinimo(),'campos'=>$campos);
        }
        return new Response(json_encode($retorno));
    }   

}