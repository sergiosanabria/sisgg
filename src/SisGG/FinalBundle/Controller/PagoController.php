<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\TipoPago;
use SisGG\FinalBundle\Form\TipoPagoType;
use SisGG\FinalBundle\Entity\Compra;
use SisGG\FinalBundle\Form\CompraType;
use SisGG\FinalBundle\Entity\ItemCompra;
use SisGG\FinalBundle\Entity\NotaPedido;
use SisGG\FinalBundle\Entity\ItemNotaPedido;
use SisGG\FinalBundle\Entity\AtrTipoPago;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Entity\TipoCobro;

/**
 * Description of PagoController
 *
 * @author sergio
 */
class PagoController extends Controller {
    /*
      public function nuevoTipoPagoAction() {
      $tipo = new TipoPago();
      $formulario = $this->createForm(new TipoPagoType(), $tipo);

      if ($this->getRequest()->getMethod() === 'POST') {
      $formulario->bindRequest($this->getRequest());
      if ($formulario->isValid()) {
      $empresa = $this->getEmpresa();
      $r = $empresa->altaTipoPago($tipo, $this);
      if ($r == null) {
      $msj = "El tipo de pago " . $tipo->getNombre() . " ha sido exitosamente registrado.";
      $this->getRequest()->getSession()->set('msjTP', $msj);
      return $this->redirect($this->generateUrl('tiposPagos'));
      } else {
      return $this->render('SisGGFinalBundle:Pago:nuevoTipoPago.html.twig', array('form' => $formulario->createView(), 'error' => $r));
      }
      } else {
      return $this->render('SisGGFinalBundle:Pago:nuevoTipoPago.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
      }
      }
      return $this->render('SisGGFinalBundle:Pago:nuevoTipoPago.html.twig', array('form' => $formulario->createView(), 'error' => null));
      }

      public function editarTipoPagoAction() {
      $tipo = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoPago')->find($this->getRequest()->get('id'));
      $formulario = $this->createForm(new TipoPagoType(), $tipo);
      if ($this->getRequest()->getMethod() === 'POST') {
      $formulario->bindRequest($this->getRequest());
      if ($formulario->isValid()) {
      $empresa = $this->getEmpresa();
      $r = $empresa->modificarTipoPago($tipo, $this);
      if ($r == null) {
      $msj = "El tipo de pago " . $tipo->getNombre() . " ha sido exitosamente modificado.";
      $this->getRequest()->getSession()->set('msjTP', $msj);
      return $this->redirect($this->generateUrl('tiposPagos'));
      } else {
      return $this->render('SisGGFinalBundle:Pago:editarTipoPago.html.twig', array('form' => $formulario->createView(), 'id' => $tipo->getId(), 'nombre' => $tipo->getNombre(), 'error' => $r));
      }
      } else {
      return $this->render('SisGGFinalBundle:Pago:editarTipoPago.html.twig', array('form' => $formulario->createView(), 'id' => $tipo->getId(), 'nombre' => $tipo->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos."));
      }
      }
      return $this->render('SisGGFinalBundle:Pago:editarTipoPago.html.twig', array('form' => $formulario->createView(), 'id' => $tipo->getId(), 'nombre' => $tipo->getNombre(), 'error' => null));
      }

      public function eliminarTipoPagoAction() {
      $tipo = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoPago')->find($this->getRequest()->get('id'));
      $empresa = $this->getEmpresa();
      $r = $empresa->eliminarTipoPago($tipo, $this);
      if ($r == null) {
      $msj = "El tipo de pago " . $tipo->getNombre() . " ha sido exitosamente eliminado.";
      $this->getRequest()->getSession()->set('msjTP', $msj);
      return $this->redirect($this->generateUrl('tiposPagos'));
      } else {
      $this->getRequest()->getSession()->set('error', $r);
      return $this->redirect($this->generateUrl('tiposPagos'));
      }
      }

      public function tiposPagosAction() {
      $tipos = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoPago')->findAll();
      $msj = $this->getRequest()->getSession()->get('msjTP');
      $error = $this->getRequest()->getSession()->get('error');
      $this->getRequest()->getSession()->remove('msjTP');
      $this->getRequest()->getSession()->remove('error');
      $parameters = array('msj' => $msj, 'form' => null, 'error' => $error, 'tipos' => $tipos);
      return $this->render('SisGGFinalBundle:Pago:tipos.html.twig', $parameters);
      } */

    public function pagosAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PAGO_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $total = 0;
        $pagos = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pago')->findAll();
        $msj = $this->getRequest()->getSession()->get('msjPag');
        $error = $this->getRequest()->getSession()->get('error');
        $this->getRequest()->getSession()->remove('msjPag');
        $this->getRequest()->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'form' => null, 'total' => $total, 'error' => $error, 'registros' => $pagos);
        return $this->render('SisGGFinalBundle:Pago:pagos.html.twig', $parameters);
    }

    /*
      public function tiposPagosJSONAction() {
      $tipos = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoPago')->findAll();
      $array = null;
      foreach ($tipos as $value) {
      $array[] = array('id' => $value->getId(), 'nombre' => $value->getNombre(), 'desc' => $value->getDescripcion());
      }
      return new Response(json_encode($array));
      }
     */
    /*
      function leerAction() {
      if ($this->getRequest()->isXmlHttpRequest()) {
      $txt = $this->getRequest()->get('txt');
      return new response(json_encode(array('href' => print_r($this->voz($txt), true))));
      }
      $parameters = array('msj' => null, 'form' => null, 'error' => null);
      return $this->render('SisGGFinalBundle:Pago:leer.html.twig', $parameters);
      }

      function voz($texto, $lang = "es") {
      $url = "http://vozme.com/text2voice.php";
      $md5 = md5($texto);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "text=" . $texto . "&lang=" . $lang . "&md5=" . $md5);

      $s = curl_exec($ch); //Ojo, hay un espacio en exec
      curl_close($ch);

      $exp_info = '!http(.+)' . $md5 . '(.+)mp3!U';
      preg_match_all($exp_info, $s, $original);

      if (count($original) > 0) {
      return $original[0][0];
      } else {
      return $s;
      }
      } */

    public function obtenerValoresAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->get('id');
            $array = null;
            /* @var $pago Pago */
            $pago = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pago')->find($id);
            if ($pago->getValores() != null) {
                foreach ($pago->getValores() as $value) {
                    $array[] = array('nombre' => $value->getCampo()->getNombre(), 'valor' => $value->getValor());
                }
            }
            return new Response(json_encode($array));
        }
    }

    /* public function mostrarTipoPagoAction() {
      $id = $this->getRequest()->get('id');
      $tipo = $this->getDoctrine()->getRepository('SisGGFinalBundle:TipoPago')->find($id);
      $atr = null;
      if ($this->getRequest()->get('atr') != null) {
      $atr = $this->getDoctrine()->getRepository('SisGGFinalBundle:AtrTipoPago')->find($this->getRequest()->get('atr'));
      }
      $parameters = array('tp' => $tipo, 'atr' => $atr, 'form' => null);
      return $this->render('SisGGFinalBundle:Pago:formTipoPago.html.twig', $parameters);
      } */
    /*
      public function nuevoAtrAction() {
      $atr1 = $this->getRequest()->get('atr1');
      $atr2 = $this->getRequest()->get('atr2');
      $atr3 = $this->getRequest()->get('atr3');
      $atr4 = $this->getRequest()->get('atr4');
      $atr5 = $this->getRequest()->get('atr5');
      $new = new AtrTipoPago();
      $new->setAtr1($atr1);
      $new->setAtr2($atr2);
      $new->setAtr3($atr3);
      $new->setAtr4($atr4);
      $new->setAtr5($atr5);
      $this->getDoctrine()->getEntityManager()->persist($new);
      $this->getDoctrine()->getEntityManager()->flush();
      return new Response(json_encode(array('id' => $new->getId())));
      } */

    public function pagoRegistrableAction() {

        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PAGO_REGISTRAR)) {
            return $this->render('SisGGFinalBundle:Pago:registrar.html.twig', array('tipo' => 4));
        }
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $pago \SisGG\FinalBundle\Entity\Pago */
        $pago = $em->getRepository('SisGGFinalBundle:Pago')->find($this->getRequest()->get('id'));

        if ($pago->getTipoCobro()->getNombre() == 'Efectivo no registrable') {
            $registrable = $em->getRepository('SisGGFinalBundle:TipoCobro')->findOneBy(array('nombre' => 'Efectivo registrable'));
            $pago->setTipoCobro($registrable);
            $salida = $pago->crearSalida($this->getUser());
            if ($salida) {
                return $this->render('SisGGFinalBundle:Pago:registrar.html.twig', array('tipo' => 3));
            }
            $em->flush($pago);
            return $this->render('SisGGFinalBundle:Pago:registrar.html.twig', array('tipo' => 1));
        } else {
            return $this->render('SisGGFinalBundle:Pago:registrar.html.twig', array('tipo' => 2));
            ;
        }
    }

    public function buscarPagoAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $pagos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Pago')->findAll();

            $empresa = $this->getEmpresa();
            $array = null;
            $total = 0.00;
            if ($tipo == -1) {
                $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pago')->findAll();
                foreach ($array as $value) {
                    $total+=$value->getImporte();
                }
            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $empresa->buscarPago($tiempo, $clase, $pagos);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarPagoEntreFechas($fecha1, $fecha2, $pagos);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarPagoFecha($fecha1, $clase, $pagos);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarPagoMesAño($mes, $año, $pagos);
                if ($array != null)
                    $total = array_pop($array);
            }
            $parameters = array('registros' => $array, 'form' => null, 'total' => $total);
            return $this->render('SisGGFinalBundle:Pago:cargarTabla.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaPagoAction() {
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        $url = $this->getRequest()->get('imgSRC');
//        if ($url != -1) {
//            $img = '/tmp/compra.png';
//            file_put_contents($img, file_get_contents($url));
//        } else {
//            $img = '';
//        }
//
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pago')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'img' => $img, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Pago:imp_lista.' . $format . '.twig', $parameters);
//    }

}
