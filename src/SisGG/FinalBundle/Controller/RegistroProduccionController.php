<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\PreElaboradoType;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of RegistroProduccionController
 *
 * @author sergio
 */
class RegistroProduccionController extends Controller
{

    public function registroProduccion1Action()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_CANTIDAD)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $msj = $this->getRequest()->getSession()->get('msjRPR');
        $this->getRequest()->getSession()->remove('msjRPR');
        $pes = $this->getDoctrine()->getRepository('SisGGFinalBundle:PreElaborado')->findBy(array('activo' => true));
        if ($this->getRequest()->getMethod() === 'POST') {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->find($this->getRequest()->get('id'));
            $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            $cantidad = $this->getRequest()->get('cantidad');
            $desc = $this->getRequest()->get('obsProd');
            $cantidad = $cantidad * ($tasa->getValor() / $pe->getTasa()->getValor());
            $array = $this->obtnerChk($pe->getIngredientes()->count());
            $msj = $this->getEmpresa()->registrarProduccion1($pe, $cantidad, $desc, $array, $this);
            $this->getRequest()->getSession()->set('msjRPR', 'Se ha registrado correctamente.');
            return $this->redirect($this->generateUrl('registroProduccion1'));
//            $parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
//            return $this->render('SisGGFinalBundle:RegistroProduccion:registroProduccion1.html.twig', $parameters);
        }
        $parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
        return $this->render('SisGGFinalBundle:RegistroProduccion:registroProduccion1.html.twig', $parameters);
    }

    public function obtnerChk($j)
    {
        for ($i = 0; $i < $j; $i++) {
            if ($this->getRequest()->get('chk' . $i) == 1)
                $array[] = 1;
            else
                $array[] = 0;
        }
        return $array;
    }

    public function registroProduccion2Action()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_INGREDIENTE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pes = $this->getDoctrine()->getRepository('SisGGFinalBundle:PreElaborado')->findBy(array('activo' => true));
        $msj = $this->getRequest()->getSession()->get('msjRPR');
        $this->getRequest()->getSession()->remove('msjRPR');
        if ($this->getRequest()->getMethod() === 'POST') {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->find($this->getRequest()->get('id'));
            $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            $cantidad = $this->getRequest()->get('menor');
            $desc = $this->getRequest()->get('obsProd');
            $cantidad = $cantidad * ($tasa->getValor() / $pe->getTasa()->getValor());
            $array = $this->obtnerChk($pe->getIngredientes()->count());
            $msj = $this->getEmpresa()->registrarProduccion1($pe, $cantidad, $desc, $array, $this);
            $this->getRequest()->getSession()->set('msjRPR', 'Se ha registrado correctamente.');
            return $this->redirect($this->generateUrl('registroProduccion1'));
//            $parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
//            return $this->render('SisGGFinalBundle:RegistroProduccion:registroProduccion2.html.twig', $parameters);
        }
        $parameters = array('form' => null, 'msj' => $msj, 'error' => null, 'pes' => $pes);
        return $this->render('SisGGFinalBundle:RegistroProduccion:registroProduccion2.html.twig', $parameters);
    }

    public function calcularCantidad1Action()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->get('id');
            $pe = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:PreElaborado')->find($id);
            $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            $cantidad = $this->getRequest()->get('cantidad');
            $cantidad = $cantidad * ($tasa->getValor() / $pe->getTasa()->getValor());
            $parameters = array('cantidad' => $cantidad, 'ing' => $pe->getIngredientes());
            return $this->render('SisGGFinalBundle:RegistroProduccion:resultado1.html.twig', $parameters);
        }
    }

    public function calcularCantidad2Action()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->get('id');
            $pe = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:PreElaborado')->find($id);
            $parameters = array('ing' => $pe->getIngredientes(), 'tipo' => 0);
            return $this->render('SisGGFinalBundle:RegistroProduccion:resultado2.html.twig', $parameters);
        }
    }

    public function calcularCantidad3Action()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $ids = $this->getRequest()->get('ids');
            $tasas = $this->getRequest()->get('tasas');
            $cantidades = $this->getRequest()->get('cantidades');
            $valor = 0.00;
            $set = 0.00;
            $id = $this->getRequest()->get('id');
            $pe = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:PreElaborado')->find($id);
            $tasaNueva = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($this->getRequest()->get('tasa'));
            for ($index = 0; $index < count($cantidades); $index++) {
                $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($tasas[$index]);
                $ing = $this->getDoctrine()->getRepository("SisGGFinalBundle:Ingrediente")->find($ids[$index]);
                $valor = (($tasa->getValor() * $cantidades[$index]) / ($ing->getCantidad() * $ing->getTasa()->getValor()));
                $array[] = $valor * ($pe->getTasa()->getValor() / $tasaNueva->getValor());
            }
            $aux = 0.00;
            $aux = $array[0];
            for ($index = 0; $index < count($array); $index++) {
                if ($aux > $array[$index]) {
                    $aux = $array[$index];
                }
            }
            $cantidad = $aux * ($tasaNueva->getValor() / $pe->getTasa()->getValor());
            for ($index = 0; $index < count($cantidades); $index++) {
                $tasa = $this->getDoctrine()->getRepository("SisGGFinalBundle:Tasa")->find($tasas[$index]);
                $ing = $this->getDoctrine()->getRepository("SisGGFinalBundle:Ingrediente")->find($ids[$index]);
                $set = ($ing->getProductoProduccion()->getCantidad()) - ($cantidad * $ing->getCantidad() * $ing->getCoeficiente());
                $resto[] = $set;
            }

            $parameters = array('menor' => $aux, 'resto' => $resto, 'ing' => $pe->getIngredientes(), 'array' => $array, 'tipo' => 1, 'tasas' => $tasas, 'cantidades' => $cantidades);
            return $this->render('SisGGFinalBundle:RegistroProduccion:resultado2.html.twig', $parameters);
        }
//        $parameters = array('array' => $array, 'menor'=>$aux);
//        return $this->render('SisGGFinalBundle:RegistroProduccion:resultado3.html.twig', $parameters);
    }

    public function datosPEAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->find($this->getRequest()->get('id'));
            $form = $this->createForm(new PreElaboradoType(), $pe, array('disabled' => true));
            return $this->render('SisGGFinalBundle:RegistroProduccion:detalles.html.twig', array('form' => $form->createView(), 'pe' => $pe, 'ingredientes' => $pe->getIngredientes()));
        }
    }

    public function registrosAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $registros = $this->getDoctrine()->getRepository("SisGGFinalBundle:ItemRegistroProduccion")->findBy(array('registroProduccion' => $this->getRequest()->get('id')));
            return $this->render('SisGGFinalBundle:RegistroProduccion:item.html.twig', array('items' => $registros, 'id' => $this->getRequest()->get('id')));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $registros = $this->getDoctrine()->getRepository("SisGGFinalBundle:RegistroProduccion")->findAll();
            return $this->render('SisGGFinalBundle:RegistroProduccion:registros.html.twig', array('form' => null, 'registros' => $registros, 'msj' => null, 'error' => null));
        }
    }

    public function buscarRPAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $empresa = $this->getEmpresa();

            $array = null;
            if ($tipo == -1) {
                $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:RegistroProduccion')->findAll();
            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $empresa->buscarRP($tiempo, $clase);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarRPEntreFechas($fecha1, $fecha2);

                //$array=$empresa->buscarNotaEntreFechas(new \DateTime('2013/04/15'), new \DateTime('2013/04/16'));
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarRPFecha($fecha1, $clase);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarNotaMesAño($mes, $año);
            }
            $parameters = array('registros' => $array, 'form' => null);
            return $this->render('SisGGFinalBundle:RegistroProduccion:cargarTabla.html.twig', $parameters);
        }
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaRegProdAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:RegistroProduccion')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:RegistroProduccion:imp_lista.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impDetallesRegProdAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::REGISTRO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $item = $this->getDoctrine()->getRepository('SisGGFinalBundle:RegistroProduccion')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'producto' => $item, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:RegistroProduccion:imp_detalles.' . $format . '.twig', $parameters);
//    }

}
