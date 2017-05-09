<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\IngredienteType;
use SisGG\FinalBundle\Entity\Ingrediente;
use SisGG\FinalBundle\Form\PreElaboradoType;

/**
 * Description of IngredientePreElaboradoController
 *
 * @author sergio
 */
class IngredienteController extends Controller {

    public function nuevoIngredienteAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new IngredienteType(), null, array('attr' => array('tipo' => 1)));
        $repositorio = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:PreElaborado');
        $pe = $repositorio->find($request->get('id'));
        $msj = $request->getSession()->get('msjIng');
        $request->getSession()->remove('msjIng');
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $parameters = array('form' => $formulario->createView(), 'msj' => $msj, 'error' => null, 'pps' => $pps, 'pe' => $pe);
        return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngrediente.html.twig', $parameters);
    }

    public function altaIngredienteAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $ingrediente = new Ingrediente();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 1)));
        $formulario->handleRequest($request);
        if ($ingrediente->getCantidad() == 0)
            $ingrediente->setCantidad(1);
        if ($formulario->isValid() && $ingrediente->getProductoProduccion() != null) {
            $r = $empresa->altaIngrediente($ingrediente, $this);
            if ($r == null) {
                $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente registrado.";
                $request->getSession()->set('msjIng', $msj);
                return $this->redirect($this->generateUrl('nuevoIngrediente', array('id' => $ingrediente->getPreElaborado()->getId())));
            } else {
                $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
                return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngrediente.html.twig', array('form' => $formulario->createView(), 'pe' => $ingrediente->getPreElaborado(), 'msj' => null, 'error' => $r, 'pps' => $pps, 'id' => $ingrediente->getPreElaborado()->getId()));
            }
        } else {
            $error = ' Seleccione un producto.';
        }
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $r = "Verifique que los campos ingresados sean correctos.";
        return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngrediente.html.twig', array('form' => $formulario->createView(), 'pe' => $ingrediente->getPreElaborado(), 'msj' => null, 'error' => $r . $error, 'pps' => $pps, 'id' => $ingrediente->getPreElaborado()->getId()));
    }

    public function editarIngredienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $ingrediente = $em->getRepository('SisGGFinalBundle:Ingrediente')->find($request->get('id'));
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 1)));
        $msj = $request->getSession()->get('msjIng');
        $request->getSession()->remove('msjIng');
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $parameters = array('form' => $formulario->createView(), 'msj' => $msj, 'error' => null, 'id' => $ingrediente->getId(), 'pps' => $pps, 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'pe' => $ingrediente->getPreElaborado(), 'ing' => $ingrediente);
        return $this->render('SisGGFinalBundle:Ingrediente:editarIngrediente.html.twig', $parameters);
    }

    public function modificarIngredienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ingrediente', array('attr' => array('tipo' => 1)));
        $ingrediente = $repositorio->find($request->get('id'));
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 1)));
        $formulario->handleRequest($request);
        if ($ingrediente->getCantidad() == 0)
            $ingrediente->setCantidad(1);
        if ($formulario->isValid()) {
            $r = $empresa->modificarIngrediente($ingrediente, $this);
            if ($r == null) {
                $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente modificado.";
                $request->getSession()->set('msjIng', $msj);
                return $this->redirect($this->generateUrl('nuevoIngrediente', array('id' => $ingrediente->getPreElaborado()->getId())));
            } else {
                $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
                $parameters = array('form' => $formulario->createView(), 'msj' => null, 'error' => $r, 'pps' => $pps, 'id' => $ingrediente->getId(), 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'pe' => $ingrediente->getPreElaborado() , 'ing' => $ingrediente);
                return $this->render('SisGGFinalBundle:Ingrediente:editarIngrediente.html.twig', $parameters);
            }
        }
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $r = "Verifique que los campos ingresados sean correctos.";
        $parameters = array('form' => $formulario->createView(), 'msj' => $msj, 'error' => null, 'pps' => $pps, 'id' => $ingrediente->getId(), 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'pe' => $ingrediente->getPreElaborado(), 'ing' => $ingrediente);
        return $this->render('SisGGFinalBundle:Ingrediente:editarIngrediente.html.twig', $parameters);
    }

    public function eliminarIngredienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ingrediente');
        $ingrediente = $repositorio->find($request->get('id'));
        $r = $empresa->eliminarIngrediente($ingrediente, $this);
        if ($r != null) {
            $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente eliminada.";
            if ($request->get('lugar') == 'ing') {
                return $this->redirect($this->generateUrl('nuevoIngrediente', array('id' => $ingrediente->getPreElaborado()->getId())));
            }
            if ($request->get('tipo') == null) {
                $request->getSession()->set('msjPE', $msj);
                return $this->redirect($this->generateUrl('pes'));
            } elseif ($request->get('tipo') == '1') {
                $request->getSession()->set('msjPP', $msj);
                return $this->redirect($this->generateUrl('pps'));
            }
            return null;
        }
    }

    public function cargarPPAction() {
        if ($request->isXmlHttpRequest()) {
            $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
            return $this->render('SisGGFinalBundle:Ingrediente:cargarPP.html.twig', array('pps' => $pps));
        }
    }

    public function nuevoIngredientePlatoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new IngredienteType(), null, array('attr' => array('tipo' => 2)));
        $repositorio = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Plato');
        $plato = $repositorio->find($request->get('id'));
        $msj = $request->getSession()->get('msjIng');
        $request->getSession()->remove('msjIng');
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $parameters = array('form' => $formulario->createView(), 'msj' => $msj, 'error' => null, 'pps' => $pps, 'plato' => $plato);
        return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngredientePlato.html.twig', $parameters);
    }

    public function altaIngredientePlatoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $ingrediente = new Ingrediente();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 2)));
        $formulario->handleRequest($request);
        if ($ingrediente->getCantidad() == 0)
            $ingrediente->setCantidad(1);
        if ($formulario->isValid() && $ingrediente->getProductoProduccion() != null) {
            $r = $empresa->altaIngrediente($ingrediente, $this);
            if ($r == null) {
                $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente registrado.";
                $request->getSession()->set('msjIng', $msj);
                return $this->redirect($this->generateUrl('nuevoIngredientePlato', array('id' => $ingrediente->getPlato()->getId())));
            } else {
                $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
                return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngredientePlato.html.twig', array('form' => $formulario->createView(), 'plato' => $ingrediente->getPlato(), 'msj' => null, 'error' => $r, 'pps' => $pps, 'id' => $ingrediente->getPlato()->getId()));
            }
        } else {
            $error = ' Seleccione un producto.';
        }
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $r = "Verifique que los campos ingresados sean correctos.";
        return $this->render('SisGGFinalBundle:Ingrediente:nuevoIngredientePlato.html.twig', array('form' => $formulario->createView(), 'plato' => $ingrediente->getPlato(), 'msj' => null, 'error' => $r . $error, 'pps' => $pps, 'id' => $ingrediente->getPlato()->getId()));
    }

    public function editarIngredientePlatoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $ingrediente = $em->getRepository('SisGGFinalBundle:Ingrediente')->find($request->get('id'));
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 2)));
        $msj = $request->getSession()->get('msjIng');
        $request->getSession()->remove('msjIng');
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $parameters = array('form' => $formulario->createView(), 'msj' => $msj, 'error' => null, 'ing' => $ingrediente, 'id' => $ingrediente->getId(), 'pps' => $pps, 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'plato' => $ingrediente->getPlato());
        return $this->render('SisGGFinalBundle:Ingrediente:editarIngredientePlato.html.twig', $parameters);
    }

    public function modificarIngredientePlatoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ingrediente');
        $ingrediente = $repositorio->find($request->get('id'));
        $formulario = $this->createForm(new IngredienteType(), $ingrediente, array('attr' => array('tipo' => 2)));
        $formulario->handleRequest($request);
        if ($ingrediente->getCantidad() == 0)
            $ingrediente->setCantidad(1);
        if ($formulario->isValid()) {
            $r = $empresa->modificarIngrediente($ingrediente, $this);
            if ($r == null) {
                $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente modificado.";
                $request->getSession()->set('msjIng', $msj);
                return $this->redirect($this->generateUrl('nuevoIngredientePlato', array('id' => $ingrediente->getPlato()->getId())));
            } else {
                $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
                $parameters = array('form' => $formulario->createView(), 'msj' => null, 'error' => $r, 'pps' => $pps, 'id' => $ingrediente->getId(), 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'plato' => $ingrediente->getPlato(), 'ing' => $ingrediente);
                return $this->render('SisGGFinalBundle:Ingrediente:editarIngredientePlato.html.twig', $parameters);
            }
        }
        $pps = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoProduccion")->findBy(array('activo' => 1));
        $r = "Verifique que los campos ingresados sean correctos.";
        $parameters = array('form' => $formulario->createView(), 'msj' => null, 'error' => null, 'pps' => $pps, 'ing' => $ingrediente, 'id' => $ingrediente->getId(), 'nombre' => $ingrediente->getProductoProduccion()->getNombre(), 'plato' => $ingrediente->getPlato());
        return $this->render('SisGGFinalBundle:Ingrediente:editarIngredientePlato.html.twig', $parameters);
    }

    public function eliminarIngredientePlatoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::INGREDIENTE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Ingrediente');
        $ingrediente = $repositorio->find($request->get('id'));
        $plato = $ingrediente->getPlato();
        $r = $empresa->eliminarIngrediente($ingrediente, $this);
        if ($r != null) {
            $msj = "El ingrediente " . $ingrediente->getProductoProduccion() . " ha sido exitosamente eliminada.";
            if ($request->get('lugar') == 'ing') {
                return $this->redirect($this->generateUrl('nuevoIngredientePlato', array('id' => $plato->getId())));
            }
            if ($request->get('tipo') == null) {
                $request->getSession()->set('msjPla', $msj);
                return $this->redirect($this->generateUrl('platos'));
            } else {
                $request->getSession()->set('msjPV', $msj);
                return $this->redirect($this->generateUrl('pvs'));
            }
        }
    }

    public function costoIngredienteAction() {
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $padre \SisGG\FinalBundle\Entity\Producto */
        
        $tasa = $em->getRepository('SisGGFinalBundle:Tasa')->find($request->get('tasa'));
        $producto = $em->getRepository('SisGGFinalBundle:Producto')->find($request->get('producto'));
        $cantidad = $request->get('cantidad');
        if ($request->get('tipo') == "1") {
            $ingrediente = $em->getRepository('SisGGFinalBundle:Ingrediente')->find($request->get('id'));      
            $ingrediente->setCantidad($cantidad);
            $padre=$ingrediente->getPlato();
            if ($padre==null){
                $padre=$ingrediente->getPreElaborado();
            }
            $ingrediente->setProductoProduccion($producto);
            $ingrediente->setTasa($tasa);
        } else {
            $ingrediente = new Ingrediente();
            $padre = $em->getRepository('SisGGFinalBundle:Producto')->find($request->get('padre'));
            $ingrediente->setCantidad($cantidad);
            $ingrediente->setProductoProduccion($producto);
            $ingrediente->setTasa($tasa);
            if ($padre->isPlato()) {
                $ingrediente->setPlato($padre);
            } else {
                $ingrediente->setPreElaborado($padre);
            }
            $padre->addIngrediente($ingrediente);
        }

        
            $ingrediente->calcularCoeficiente();
            $padre->sumarCosto();
        
        return new Response(json_encode(array('padre' => $padre->getPrecioCosto(), 'ing' => $ingrediente->getPrecioCosto())));
    }

    public function cargarTasaIngredienteAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $producto = $em->getRepository('SisGGFinalBundle:Producto')->find($request->get('id'));
        return $this->render('SisGGFinalBundle:UM:cargarTasa.html.twig', array('nombre' => "", 'um'=>$producto->getTasa()->getUm(), 'tasas' => $producto->getTasa()->getUm()->getTasas()));
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>