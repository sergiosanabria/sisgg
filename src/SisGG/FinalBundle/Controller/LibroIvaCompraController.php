<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of MantenimientoController
 *
 * @author sergio
 */
class LibroIvaCompraController extends Controller
{

//    /**
//     * @Pdf()
//     */
//    public function imprimirLICAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::LIBROIVACOMPRA_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $mes = $this->getRequest()->get('mes');
//        $año = $this->getRequest()->get('año');
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:LibroIvaCompra')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
//        $parameters = array('form' => null, 'items' => $array, 'tasas' => $tasas, 'info' => $info, 'año' => $año, 'mes' => $mes, 'empresa' => $this->getEmpresa(), 'totales' => $this->sumarIvas($tasas, $array));
//        return $this->render('SisGGFinalBundle:LibroIvaCompra:imprimirivaCompras.' . $format . '.twig', $parameters);
//    }

    public function buscarIvaComprasAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $empresa = $this->getEmpresa();
            $array = null;
            if ($tipo == 1) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarLICMesAño($mes, $año);
            }
            $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
            $parameters = array('form' => null, 'items' => $array, 'tasas' => $tasas, 'totales' => $this->sumarIvas($tasas, $array));
            return $this->render('SisGGFinalBundle:LibroIvaCompra:cargarTabla.html.twig', $parameters);
        }
    }

    public function ivaComprasAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::LIBROIVACOMPRA_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $hoy = new \DateTime('now');
        $ivas = $this->getEmpresa()->buscarLICMesAño(date_format($hoy, 'n'), date_format($hoy, 'Y'));
        $tasas = $this->getDoctrine()->getRepository('SisGGFinalBundle:IVA')->findAll();
        $msj = $this->getRequest()->getSession()->get('msjIVAC');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjIVAC');
        $this->getRequest()->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'form' => null, 'error' => $error, 'items' => $ivas, 'tasas' => $tasas, 'totales' => $this->sumarIvas($tasas, $ivas));
        return $this->render('SisGGFinalBundle:LibroIvaCompra:ivaCompras.html.twig', $parameters);
    }

    private function sumarIvas($tasas, $ivas)
    {
        $totales = null;
        $acre = 0.00;
        $total = 0.00;
        $neto = 0.00;
        if ($tasas == null) {
            return array('totales' => $totales, 'neto' => $neto, 'acre' => $acre, 'total' => $total);
        }
        foreach ($tasas as $value) {
            /* @var $value \SisGG\FinalBundle\Entity\IVA */
            $totales [] = array('tasa' => $value, 'total' => 0);
        }
        if ($ivas == null) {
            return array('totales' => $totales, 'neto' => $neto, 'acre' => $acre, 'total' => $total);
        }

        foreach ($ivas as $value) {
            /* @var $value \SisGG\FinalBundle\Entity\LibroIvaCompra */
            $value->sumar($totales);
            $acre += $value->getAcrecent();
            $total += $value->getTotal();
            $neto += $value->getNeto();
        }
        return array('totales' => $totales, 'neto' => $neto, 'acre' => $acre, 'total' => $total);
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}
