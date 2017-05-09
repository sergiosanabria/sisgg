<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\PreElaboradoType;
use SisGG\FinalBundle\Form\MateriaPrimaType;
use SisGG\FinalBundle\Form\MercaderiaType;
use SisGG\FinalBundle\Form\PlatoType;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of ProductoController
 *
 * @author sergio
 */
class ProductoController extends Controller
{

    public function ppsAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->find($this->getRequest()->get('id'));
            if ($this->getRequest()->get('tipo') == "1") {
                $form = $this->createForm(new PreElaboradoType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:PreElaborado:detalles.html.twig', array('form' => $form->createView(), 'pe' => $pe, 'ingredientes' => $pe->getIngredientes()));
            } else {
                $form = $this->createForm(new MateriaPrimaType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:MateriaPrima:detalles.html.twig', array('form' => $form->createView(), 'id' => $pe->getId()));
            }
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_LISTAR_PRODUCTOPRODUCCION)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $pes = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->findBy(array('activo' => 1));
            $mps = $this->getDoctrine()->getRepository("SisGGFinalBundle:MateriaPrima")->findBy(array('activo' => 1));
            $msj = $this->getRequest()->getSession()->get('msjPP');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjPP');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('pes' => $pes, 'mps' => $mps, 'error' => $error, 'msj' => $msj, 'form' => null);
            return $this->render('SisGGFinalBundle:Producto:PP.html.twig', $parameters);
        }
    }


    public function pvsAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->find($this->getRequest()->get('id'));
            $path = null;
            if ($pe->getFoto() != null) {
                $path = $pe->getFoto()->getPath();
            }
            if ($this->getRequest()->get('tipo') == "1") {
                $form = $this->createForm(new PlatoType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:Plato:detalles.html.twig', array('form' => $form->createView(), 'plato' => $pe, 'path' => $path, 'ingredientes' => $pe->getIngredientes()));
            } else {
                $form = $this->createForm(new MercaderiaType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:Mercaderia:detalles.html.twig', array('form' => $form->createView(), 'merca' => $pe, 'path' => $path));
            }
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_LISTAR_PRODUCTPVENTA)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            //Tipo: 1=supera margen
            $tipo = $this->getRequest()->get('tipo');
            $materia = null;
            if ($tipo == 1) {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1, 'supera' => true));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1, 'supera' => true));
            } elseif ($tipo == 2) {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1, 'precioCosto' => 0));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1, 'precioCosto' => 0));
            } elseif ($tipo == 3) {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:PreElaborado")->findBy(array('activo' => 1, 'superaMin' => true));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1, 'superaMin' => true));
                $materia = $this->getDoctrine()->getRepository("SisGGFinalBundle:MateriaPrima")->findBy(array('activo' => 1, 'superaMin' => true));
            } else {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1));
            }


            $msj = $this->getRequest()->getSession()->get('msjPV');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjPV');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('platos' => $platos, 'mercaderias' => $mercaderias, 'materias' => $materia, 'error' => $error, 'msj' => $msj, 'form' => null);
            return $this->render('SisGGFinalBundle:Producto:PV.html.twig', $parameters);
        }
    }

    public function activarProductoAction()
    {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var Producto $producto */
                $producto = $em->getRepository("SisGGFinalBundle:Producto")->find($id);
                $producto->setActivo(true);
                $em->flush($producto);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $producto->getNombre())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function actTabIngAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $producto = $this->getDoctrine()->getRepository("SisGGFinalBundle:Producto")->find($this->getRequest()->get('id'));
            return $this->render('SisGGFinalBundle:Producto:actTabIng.html.twig', array('ingredientes' => $producto->getIngredientes()));
        }
    }

    public function obtenerProductoAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $producto = $this->getDoctrine()->getRepository("SisGGFinalBundle:Producto")->findOneBy(array('id' => $this->getRequest()->get('id')));
            if ($producto->getTasa() != null) {
                $pv = 0.00;
                if ($producto->isMercaderia() || $producto->isPlato()) {
                    $pv = $producto->getPrecioVenta();
                }
                return new Response(json_encode(array('id' => $producto->getId(), 'nombre' => $producto->getNombre(), 'precioVenta' => $pv, 'precioCosto' => $producto->getPrecioCosto(), 'iva' => $producto->getIva()->getId(), 'tasa' => $producto->getTasa()->__toString())));
            } else {
                return new Response(json_encode(array('id' => $producto->getId(), 'nombre' => $producto->getNombre(), 'precioCosto' => $producto->getPrecioCosto(), 'iva' => $producto->getIva()->getId(),)));
            }
        }
    }

    public function obtenerProductoVentaAction()
    {
        //if ($this->getRequest()->isXmlHttpRequest()) {
        $producto = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->find($this->getRequest()->get('id'));
        $tasas = array();
        foreach ($producto->getTasa()->getUM()->getTasas() as $tasa) {
            $tasas[] = array('id' => $tasa->getId(), 'nombre' => $tasa->__toString());
        }
        $ingredientes = array();
        foreach ($producto->getIngredientes() as $ingrediente) {
            if (!$ingrediente->getObligatorio())
                $ingredientes[] = array('id' => $ingrediente->getId(), 'nombre' => $ingrediente->getExclusion());
        }
        $retorno = array('id' => $producto->getId(), 'nombre' => $producto->getNombre(), 'precioCosto' => $producto->getPrecioCosto(), 'precioVenta' => $producto->getPrecioVenta(), 'tasa' => array('id' => $producto->getTasa()->getId(), 'nombre' => $producto->getTasa()->__toString()), 'tasas' => $tasas, 'ingredientes' => $ingredientes);
        return new Response(json_encode($retorno));
        //}
    }
//IMPRIMIR

//    /**
//     * @Pdf()
//     */
//    public function impListaProductoAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $tipo = $this->getRequest()->get('tipo');
//        $texto = $this->getRequest()->get('texto');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($id);
//        }
//
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'tipo' => $tipo, 'texto' => $texto, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Producto:imp_lista.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impListaProductoCategoriaAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $tipo = $this->getRequest()->get('tipo');
//        $texto = $this->getRequest()->get('texto');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($id);
//        }
//
//        /* @var $empresa \SisGG\FinalBundle\Entity\Empresa */
//        $empresa = $this->getEmpresa();
//
//        $productos = $empresa->calificarPorCategorias($array);
//        for ($i = 0; $i < count($productos); $i++) {
//            asort($productos[$i]['productos']);
//        }
//
//        // return  new Response(json_encode($productos));
//
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'tipo' => $tipo, 'texto' => $texto, 'items' => $productos, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Producto:imp_listaCat.' . $format . '.twig', $parameters);
//    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    public function generarHtmlFotoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_GESTIONMENU)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        try {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->findBy(array('activo' => true));

            if ($pe != null && count($pe)) {
                /* @var $producto Mercaderia */
                foreach ($pe as $producto) {
                    $pagina = $this->render('SisGGFinalBundle:Producto:fotoProducto.html.twig', array('producto' => $producto));
                    file_put_contents(__DIR__ . '/../../../../web/public/p/qrPV' . $producto->getId() . '.html', $pagina->getContent());
                }
                return $this->redirect($this->generateUrl('gestionMenu'));
            }
        } catch (Exception $exc) {
            $this->getRequest()->getSession()->set('error', $exc->getTraceAsString());
        }

        //  return $this->render('SisGGFinalBundle:Producto:fotoProducto.html.twig', array('producto'=>$producto, 'dir'=>md5('hola')));
    }

    public function generarMenuDigitalAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_GESTIONMENU)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        try {
            $empresa = $this->getEmpresa();
            $array = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->findBy(array('activo' => true));
            $productos = $empresa->calificarPorCategorias($array);
            //return $this->render('SisGGFinalBundle:Producto:menuDigital.html.twig', array('productos'=>$productos, 'empresa'=>$empresa));
            $pagina = $this->render('SisGGFinalBundle:Producto:menuDigital.html.twig', array('productos' => $productos, 'empresa' => $empresa));
            file_put_contents(__DIR__ . '/../../../../web/public/p/menuDigital.html', $pagina->getContent());
            $this->getRequest()->getSession()->set('msjMenu', "Se ha creado correctamente el menú digital.");
            return $this->redirect($this->generateUrl('gestionMenu'));
        } catch (Exception $exc) {
            $this->getRequest()->getSession()->set('error', $exc->getTraceAsString());
        }
    }

    public function gestionMenuAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_GESTIONMENU)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->isXmlHttpRequest()) {
            $pe = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->find($this->getRequest()->get('id'));
            $path = null;
            if ($pe->getFoto() != null) {
                $path = $pe->getFoto()->getPath();
            }
            if ($this->getRequest()->get('tipo') == "1") {
                $form = $this->createForm(new PlatoType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:Plato:detalles.html.twig', array('form' => $form->createView(), 'plato' => $pe, 'path' => $path, 'ingredientes' => $pe->getIngredientes()));
            } else {
                $form = $this->createForm(new MercaderiaType(), $pe, array('disabled' => true));
                return $this->render('SisGGFinalBundle:Mercaderia:detalles.html.twig', array('form' => $form->createView(), 'merca' => $pe, 'path' => $path));
            }
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_LISTAR_PRODUCTPVENTA)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            //Tipo: 1=supera margen
            $tipo = $this->getRequest()->get('tipo');
            if ($tipo == 1) {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1, 'supera' => true));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1, 'supera' => true));
            } elseif ($tipo == 2) {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1, 'precioCosto' => 0));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1, 'precioCosto' => 0));
            } else {
                $platos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->findBy(array('activo' => 1));
                $mercaderias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->findBy(array('activo' => 1));
            }

            $config = $this->getDoctrine()->getRepository("SisGGFinalBundle:ConfigMenu")->findAll();
            $msj = $this->getRequest()->getSession()->get('msjMenu');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjPV');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('platos' => $platos, 'mercaderias' => $mercaderias, 'config' => $config, 'error' => $error, 'msj' => $msj, 'form' => null);
            return $this->render('SisGGFinalBundle:Producto:menu.html.twig', $parameters);
        }
    }

//    /**
//     * @Pdf()
//     */
//    public function impMenuCategoriaAction()
//    {
////        $gestor = $this->get("gestor_autenticacion");
////        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_GESTIONMENU)) {
////            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
////        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $config = $this->getDoctrine()->getRepository("SisGGFinalBundle:ConfigMenu")->findAll();
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null) {
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($id);
//            }
//        }
//
//        /* @var $empresa \SisGG\FinalBundle\Entity\Empresa */
//        $empresa = $this->getEmpresa();
//        $productos = $empresa->calificarPorCategorias($array);
//        for ($i = 0; $i < count($productos); $i++) {
//            asort($productos[$i]['productos']);
//        }
//
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'config' => $config, 'items' => $productos, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Producto:imp_menuCat.' . $format . '.twig', $parameters);
//    }

    public function guardarConfigMenuAction()
    {
        if ($this->getRequest()->get('tipo') != null) {
            $config = $this->getDoctrine()->getRepository("SisGGFinalBundle:ConfigMenu")->findAll();
            return $this->render('SisGGFinalBundle:Producto:cargarConfig.html.twig', array('config' => $config));
        }
        for ($i = 1; $i < 6; $i++) {
            /* @var $config  ConfigMenu */
            $config = $this->getDoctrine()->getRepository("SisGGFinalBundle:ConfigMenu")->find($i);
            $config->setColor($this->getRequest()->get("color" . $i));
            $config->setCursiva($this->getRequest()->get("cursiva" . $i));
            $config->setIncluye($this->getRequest()->get("check" . $i));
            $config->setNegrita($this->getRequest()->get("negrita" . $i));
            $this->getDoctrine()->getEntityManager()->flush();
        }
        return new Response('OK');
    }


}
