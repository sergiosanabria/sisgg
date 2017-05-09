<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 *
 * @author martin
 */
class LibroIvaVentaController extends Controller {

   

//    /**
//     * @Pdf()
//     */
//    public function imprimirLIVAction() {
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $mes=$this->getRequest()->get('mes');
//        $año=$this->getRequest()->get('año');
//        $info=$this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:LibroIvaVenta')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
//        $parameters = array('form' => null, 'items' => $array, 'tasas'=>$tasas,'info'=>$info,'año'=>$año,'mes'=>$mes, 'empresa'=>  $this->getDoctrine()->getRepository('SisGGFinalBundle:Empresa')->find(1));
//        return $this->render('SisGGFinalBundle:LibroIvaVenta:imprimirivaVentas.' . $format . '.twig', $parameters);
//    }

    
    public function buscarIvaVentasAction(){
        $tipo = $this->getRequest()->get('tipo');
        $array = null;
        if ($tipo == 1) {
            $mes = $this->getRequest()->get('mes');
            $año = $this->getRequest()->get('año');
            $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:LibroIvaVenta')->findByMesAño($mes, $año);
        }
        $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
        $parameters = array( 'form' => null, 'items' => $array, 'tasas'=>$tasas);
        return $this->render('SisGGFinalBundle:LibroIvaVenta:cargarTabla.html.twig', $parameters);
    }

    public function ivaVentasAction() {
            $hoy = new \DateTime('now');
            $ivas = $this->getDoctrine()->getRepository('SisGGFinalBundle:LibroIvaVenta')->findByMesAño(date_format($hoy, 'n'), date_format($hoy, 'Y'));
            $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
            $msj = $this->getRequest()->getSession()->get('msjIVAC');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjIVAC');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'form' => null, 'error' => $error, 'items' => $ivas, 'tasas'=>$tasas);
            return $this->render('SisGGFinalBundle:LibroIvaVenta:ivaVentas.html.twig', $parameters);
    }

}
