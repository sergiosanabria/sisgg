<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Form\VentaType;
use SisGG\FinalBundle\Entity\LineaVenta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Model\GestorPedidos;
use SisGG\FinalBundle\Model\GestorCajas;
use SisGG\FinalBundle\Entity\Entrada;
use SisGG\FinalBundle\Entity\Empresa;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Entity\ItemLIV;
use SisGG\FinalBundle\Entity\LibroIvaVenta;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Entity\ItemRecargoVenta;
use SisGG\FinalBundle\Entity\ItemRecargoPedido;
use SisGG\FinalBundle\Entity\ItemDescuentoVenta;
use SisGG\FinalBundle\Entity\ItemDescuentoPedido;
use SisGG\FinalBundle\Form\NotaCreditoType;
use SisGG\FinalBundle\Entity\NotaCredito;

class VentaController extends Controller {

    public function ventasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::VENTA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $ventas = $this->getDoctrine()->getRepository('SisGGFinalBundle:Venta')->findAll();
        $parameters = array("form" => null, 'gestor_autenticacion' => $gestor, 'ventas' => $ventas);
        return $this->render("SisGGFinalBundle:Venta:ventas.html.twig", $parameters);
    }

    public function buscarVentasAction() {
        $tipo = $this->getRequest()->get('tipo');
        $gestionVenta = $this->get('gestor_ventas');
        $array = null;
        $total = 0.00;
        if ($tipo == -1) {
            $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:Venta')->findAll();
            foreach ($array as $value) {
                $total+=$value->getTotal();
            }
        } elseif ($tipo == 0) {
            $clase = $this->getRequest()->get('clase');
            $tiempo = $this->getRequest()->get('tiempo');
            $array = $gestionVenta->buscarVenta($tiempo, $clase);
            if ($array != null)
                $total = array_pop($array);
        } elseif ($tipo == 1) {
            $fecha1 = $this->getRequest()->get('fecha1');
            $fecha2 = $this->getRequest()->get('fecha2');
            $partes = explode("/", $fecha1);
            $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
            $partes = explode("/", $fecha2);
            $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
            $array = $gestionVenta->buscarVentaEntreFechas($fecha1, $fecha2);
            if ($array != null)
                $total = array_pop($array);
        } elseif ($tipo == 2) {
            $clase = $this->getRequest()->get('clase');
            $fecha1 = $this->getRequest()->get('fecha1');
            $partes = explode("/", $fecha1);
            $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
            $array = $gestionVenta->buscarVentaFecha($fecha1, $clase);
            if ($array != null)
                $total = array_pop($array);
        } elseif ($tipo == 3) {
            $mes = $this->getRequest()->get('mes');
            $año = $this->getRequest()->get('año');
            $array = $gestionVenta->buscarVentaMesAño($mes, $año);
            if ($array != null)
                $total = array_pop($array);
        }
        $parameters = array('ventas' => $array, 'form' => null, 'total' => $total);
        return $this->render('SisGGFinalBundle:Venta:cargarTabla.html.twig', $parameters);
    }

    public function facturarAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::VENTA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $id = $request->get('id');
        $unaVenta = $this->get("gestor_ventas")->nuevaVenta($this->getUser(),$id);
        $form = $this->createForm(new VentaType(),$unaVenta);
        if ($request->getMethod() === "POST") {
            $form->handleRequest($request);
            if ($form->isValid()){
                $unaVenta = $this->get("gestor_ventas")->guardarVenta($this->getUser(),$id,$unaVenta);
                return $this->redirect($this->generateUrl("ventas"));
            }
        }
        $ivas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:IVA")->findAll();
        $parameters = array("form" => $form->createView(),'form_error'=>$form->getErrorsAsString(), "habilitado" => $this->get("gestor_cajas")->getCaja()->getAbierta(), 'gestor_autenticacion' => $gestor, 'tiposCobro' => $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:TipoCobro")->findAll(), 'ivas' => $ivas,'id' => $request->get('id'));
        return $this->render("SisGGFinalBundle:Venta:facturar.html.twig", $parameters);        
        
    }
    
    public function anularVentaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::VENTA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $id = $this->getRequest()->get('id');
        /*@var $unaVenta Venta*/
        $unaVenta = $this->get("gestor_ventas")->anularVenta($this->getUser(),$id);
        if ($unaVenta->getTipoFactura()->getNotaCreditoAnulacion()==false){
            return $this->redirect($this->generateUrl("ventas"));
        }
        $unaNotaCredito = $this->get("gestor_ventas")->nuevaNotaCredito($this->getUser(),$id);
        $form = $this->createForm(new NotaCreditoType(),$unaNotaCredito);
        if ($this->getRequest()->getMethod() === "POST") {
            $form->handleRequest($this->getRequest());
            if ($form->isValid()){
                $unaVenta = $this->get("gestor_ventas")->guardarNotaCredito($this->getUser(),$unaNotaCredito);
                return $this->redirect($this->generateUrl("notas_credito"));
            }
        }
        $parameters = array("form" => $form->createView(),'form_error'=>$form->getErrorsAsString(), "habilitado" => $this->get("gestor_cajas")->getCaja()->getAbierta(), 'gestor_autenticacion' => $gestor, 'tiposCobro' => $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:TipoCobro")->findAll(), 'id' => $this->getRequest()->get('id'));
        return $this->render("SisGGFinalBundle:NotaCredito:nuevaNotaCredito.html.twig", $parameters); 
    }

    public function clienteAction() {
        $unCliente = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Cliente")->find($this->getRequest()->get("id"));
        return new Response(json_encode($unCliente->toArray()));
    }

    public function productoVentaAction() {
        $unProductoVenta = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:ProductoVenta")->find($this->getRequest()->get("id"));
        return new Response(json_encode($unProductoVenta->toArray()));
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaVentasAction() {
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Venta')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Venta:imp_lista.' . $format . '.twig', $parameters);
//    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }
    
    public function numeroFacturaAction(){
        $tipo = $this->getRequest()->get('tipo');
        $retorno = null;
        $gestorCaja = $this->get('gestor_cajas');
        if ($tipo === 'A'){
            $retorno = array('puntoVenta'=>$gestorCaja->getCaja()->getPuntoVentaA(),'numero'=>$gestorCaja->getCaja()->getUltimaFacturaA()+1);
        }elseif($tipo === 'B'){
            $retorno = array('puntoVenta'=>$gestorCaja->getCaja()->getPuntoVentaB(),'numero'=>$gestorCaja->getCaja()->getUltimaFacturaB()+1);
        }else{
            $retorno = array('puntoVenta'=>$gestorCaja->getCaja()->getPuntoVentaC(),'numero'=>$gestorCaja->getCaja()->getUltimaFacturaC()+1);
        }
        if ($retorno['puntoVenta']==null){
            $retorno['puntoVenta']=1;
        }
        $retorno['puntoVenta'] = str_pad($retorno['puntoVenta'], 4,"0",STR_PAD_LEFT);
        $retorno['numero'] = str_pad($retorno['numero'], 8,"0",STR_PAD_LEFT);
        return new Response(json_encode($retorno));
    }
    
    public function detallesVentaAction(){
        /*@var $venta Venta*/
        $venta = $this->getDoctrine()->getRepository('SisGGFinalBundle:Venta')->find($this->getRequest()->get('id'));
        $ivas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
        $parameters = array("form" => null, "habilitado" => $this->get("gestor_cajas")->getCaja()->getAbierta(), 'gestor_autenticacion' => $this->get("gestor_autenticacion"), 'tiposCobro' => $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:TipoCobro")->findAll(),'venta'=>$venta,'ivas'=>$ivas);
        return $this->render("SisGGFinalBundle:Venta:detallesVenta.html.twig", $parameters);
    }
    
//    /**
//     * @Pdf()
//     */
//    public function impVentaAction() {
//        /*@var $venta Venta*/
//        $venta = $this->getDoctrine()->getRepository('SisGGFinalBundle:Venta')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array("form" => null,'venta'=>$venta,'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Venta:imp_venta.'.$format.'.twig', $parameters);
//    }
    
    

}
