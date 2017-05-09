<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\CuentaCorriente;
use SisGG\FinalBundle\Form\CuentaCorrienteType;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\MovimientoCuentaCorrienteHaberType;
use SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteDebe;
use SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber;
use SisGG\FinalBundle\Entity\Recibo;
use SisGG\FinalBundle\Form\ReciboType;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Entity\EnLetras;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class CuentaCorrienteController extends Controller {

    public function cuentasCorrientesAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CuentaCorriente');
        $cuentasCorrientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:CuentaCorriente:cuentasCorrientes.html.twig', array('form' => null, "cuentasCorrientes" => $cuentasCorrientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function nuevoCuentaCorrienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unCuentaCorriente = $this->get("gestor_cuenta_corriente")->nuevaCuentaCorriente($this->getRequest()->get('id'));
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new CuentaCorrienteType(),$unCuentaCorriente);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $unCuentaCorriente->setEstado('activo');
                    $em->persist($unCuentaCorriente);
                    $em->flush();
                    return $this->redirect($this->generateUrl("cuentas_corrientes"));
                }
            }
        return $this->render('SisGGFinalBundle:CuentaCorriente:nuevoCuentaCorriente.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));  
    }
    
    public function detalleCuentaCorrienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unaCuentaCorriente = $this->get("gestor_cuenta_corriente")->getCuentaCorriente($this->getRequest()->get('id'));
        return $this->render('SisGGFinalBundle:CuentaCorriente:detalleCuentaCorriente.html.twig',array('form' => null,'gestor_autenticacion'=>$gestor,'unaCuentaCorriente'=>$unaCuentaCorriente,'id'=>$this->getRequest()->get('id')));  
    }
    
    public function registrarCobroCuentaCorrienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $unRecibo Recibo*/
        $unRecibo = $this->get("gestor_cuenta_corriente")->nuevoRecibo($this->getRequest()->get('id'));
        $form = $this->createForm(new ReciboType(),$unRecibo);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $this->get("gestor_cuenta_corriente")->guardarRecibo($unRecibo,$this->getUser());
                    return $this->redirect($this->generateUrl("cuentas_corrientes"));
                }
            }
        return $this->render('SisGGFinalBundle:CuentaCorriente:registrarCobro.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id"))); 
    }
    
    public function detalleCobroAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $unRecibo Recibo*/
        $unRecibo = $this->getDoctrine()->getRepository("SisGGFinalBundle:Recibo")->find($this->getRequest()->get('id'));
        return $this->render('SisGGFinalBundle:CuentaCorriente:detalleCobro.html.twig',array('form' => null,'gestor_autenticacion'=>$gestor,'unRecibo'=>$unRecibo)); 
    }
    
    public function editarCuentaCorrienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $unCuentaCorriente = $this->getDoctrine()->getRepository("SisGGFinalBundle:CuentaCorriente")->find($this->getRequest()->get("id"));
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new CuentaCorrienteType(),$unCuentaCorriente);
        if ($this->getRequest()->getMethod() === 'POST') {
        $form->bindRequest($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->flush();
                    return $this->redirect($this->generateUrl("cuentas_corrientes"));
                }
            }
        return $this->render('SisGGFinalBundle:CuentaCorriente:editarCuentaCorriente.html.twig',array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'id'=>$this->getRequest()->get("id")));  
    }
    
    public function activarCuentaCorrienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CuentaCorriente');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("cuentas_corrientes"));
    }
    
    public function borrarCuentaCorrienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CuentaCorriente');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("cuentas_corrientes"));
    }
    
    public function cuentaCorrienteAction(){
        $retorno = null;
        /*@var $unaCuentaCorriente CuentaCorriente*/
        $unaCuentaCorriente = $this->get("gestor_cuenta_corriente")->getCuentaCorrienteCliente($this->getRequest()->get('id'));
        if ( $unaCuentaCorriente != null){
            $retorno = array('existe'=>true,"maximo"=>$unaCuentaCorriente->getMaximo());
        }else{
            $retorno = array('existe'=>false);
        }
        return new Response(json_encode($retorno));
    }
    
//    /**
//     * @Pdf()
//     */
//    public function impListaCuentasCorrientesAction() {
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:CuentaCorriente')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:CuentaCorriente:imp_lista.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impCuentaCorrienteAction(){
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $format = $this->get('request')->get('_format');
//        $unaCuentaCorriente = $this->get("gestor_cuenta_corriente")->getCuentaCorriente($this->getRequest()->get('id'));
//        return $this->render('SisGGFinalBundle:CuentaCorriente:imp_cuenta_corriente.' . $format . '.twig',array('form' => null,'gestor_autenticacion'=>$gestor, 'empresa' => $this->getEmpresa(),'unaCuentaCorriente'=>$unaCuentaCorriente,'id'=>$this->getRequest()->get('id')));
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impReciboAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)){
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $format = $this->get('request')->get('_format');
//        /* @var $unRecibo Recibo*/
//        $unRecibo = $this->getDoctrine()->getRepository("SisGGFinalBundle:Recibo")->find($this->getRequest()->get('id'));
//        $enLetras = new EnLetras();
//        $importeEnLetras = $enLetras->ValorEnLetras($unRecibo->getTotal(), "pesos");
//        return $this->render('SisGGFinalBundle:CuentaCorriente:imp_recibo.' . $format . '.twig',array('importeEnLetras'=>$importeEnLetras,'form' => null,'gestor_autenticacion'=>$gestor, 'empresa' => $this->getEmpresa(),'unaRecibo'=>$unRecibo,'id'=>$this->getRequest()->get('id')));
//    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }
}