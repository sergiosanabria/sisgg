<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\CargoSistema;
use SisGG\FinalBundle\Form\CargoSistemaType;

/**
 * Description of MantenimientoController
 *
 * @author sergio
 */
class CargoController extends Controller {

    private $lista;
    private $cargoSis;
    private $form;

    public function nuevoCargoAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $cargo = new CargoSistema();
        $formulario = $this->createForm(new CargoSistemaType(), $cargo, array('attr' => array('tipo' => 1)));
        if ($request->getMethod() === 'POST') {
            $formulario->handleRequest($request);
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaCargo($cargo, $this);
                if ($r == null) {
                    $msj = "El cargo " . $cargo->getNombre() . " ha sido exitosamente registrado.";
                    $request->getSession()->set('msjCar', $msj);
                    return $this->redirect($this->generateUrl('cargos'));
                } else {
                    return $this->render('SisGGFinalBundle:Cargo:nuevoCargo.html.twig', array('form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Cargo:nuevoCargo.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:Cargo:nuevoCargo.html.twig', array('form' => $formulario->createView(), 'error' => null));
    }

    public function editarCargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $cargo = new CargoSistema();
        $cargo = $this->getDoctrine()->getRepository("SisGGFinalBundle:CargoSistema")->find($request->get('id'));
        if ($cargo == null) {
            $request->getSession()->set('error', 'Error al ingresar los datos');
            return $this->redirect($this->generateUrl('cargos'));
        }
        $formulario = $this->createForm(new CargoSistemaType(), $cargo, array('attr' => array('tipo' => 2)));

        return $this->render('SisGGFinalBundle:Cargo:editarCargo.html.twig', array('cargo' => $cargo, 'form' => $formulario->createView(), 'error' => null));
    }

    public function modificarCargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();

        $cargo = new CargoSistema();
        $lista = null;
        if ($request->get('id') == null) {
            $request->getSession()->set('error', 'Error al establecer los datos.');
            return $this->redirect($this->generateUrl('cargos'));
        }
        $cargo = $this->getDoctrine()->getRepository("SisGGFinalBundle:CargoSistema")->find($request->get('id'));

        $formulario = $this->createForm(new CargoSistemaType(), $cargo, array('attr' => array('tipo' => 2)));

        $formulario->handleRequest($request);
        if ($cargo->getTodos() == 1) {
            $lista = explode(",", $request->get('ids'));
            if ($request->get('ids') == null) {
                return $this->render('SisGGFinalBundle:Cargo:editarCargo.html.twig', array('cargo' => $cargo, 'form' => $formulario->createView(), 'error' => 'Debe seleccionar uno o más empleados' . count($lista)));
            }
        }
        if ($formulario->isValid()) {
            $empresa = $this->getEmpresa();
            $error = $empresa->modificarCargo($cargo, $this, $lista);
            if ($error == null) {
                $error = 'No se ha modificado los datos del cargo de ningun empleado. Quizas sea mejor si elije la acción "".';
            } elseif (is_string($error)) {

                return $this->render('SisGGFinalBundle:Cargo:editarCargo.html.twig', array('cargo' => $cargo, 'form' => $formulario->createView(), 'error' => $error));
            } elseif (is_int($error)) {
                $msj = "El cargo " . $cargo->getNombre() . " ha sido modficicado exitosamente.";
                $request->getSession()->set('msjCar', $msj);
                return $this->redirect($this->generateUrl('cargos'));
            } else {
                $cargo = $this->getDoctrine()->getRepository("SisGGFinalBundle:CargoSistema")->find($request->get('id'));
                $formulario = $this->createForm(new CargoSistemaType(), $cargo, array('disabled' => true, "read_only" => true, 'attr' => array('tipo' => 2)));
                //return $this->redirect($this->generateUrl('imc'), array('cargo'), array('lista'=>$error, 'vista'=>$formulario->createView(), 'cargo'=>$cargo));
                return $this->render('SisGGFinalBundle:Cargo:informe.html.twig', array('cargo' => $cargo, 'form' => $formulario->createView(), 'error' => null, 'lista' => $error));
            }
        }else{
            return $this->render('SisGGFinalBundle:Cargo:editarCargo.html.twig', array('cargo' => $cargo, 'form' => $formulario->createView(), 'error' => 'Verifique que los datos ingresados sean correctos.', 'lista' => null));
        }
    }

    public function eliminarCargoAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:CargoSistema');
        /* @var $cargo CargoSistema */
        $cargo = $repositorio->find($request->get('id'));
        $cargo->setActivo(false);
        $em->flush($cargo);
        $request->getSession()->set('msjCar', 'El cargo ha sido correctamente eliminado.');
        return $this->redirect($this->generateUrl('cargos'));
    }

    public function activarCargoAction() {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $request->get('id');
            if ($id != null) {
                /* @var $cargo CargoSistema */
                $cargo = $em->getRepository("SisGGFinalBundle:CargoSistema")->find($id);
                $cargo->setActivo(true);
                $em->flush($cargo);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $cargo->getNombre())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function imcAction() {
        return $this->render('SisGGFinalBundle:Cargo:informe.html.twig', array('cargo' => $request->get('cargo'), 'form' => $this->$request->get('vista'), 'error' => null, 'lista' => $this->$request->get('lista')));
    }

    public function comprobarSaldoCargoAction() {
        if ($request->isXmlHttpRequest()) {
            $empresa = $this->getEmpresa();
            $empresa = $this->getEmpresa();
            $cargo = $this->getDoctrine()->getRepository("SisGGFinalBundle:CargoSistema")->find($request->get('id'));
            $array = $empresa->comprobarSaldoCargo($cargo);
            if ($array != null) {
                return $this->render('SisGGFinalBundle:Cargo:saldos.html.twig', array('empleado' => null, 'form' => null, 'lista' => $array));
            }
        }
    }

    public function cargosAction(Request $request) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CARGO_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $estado = null;
        $estado = $msj = $request->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CargoSistema');
        $cargos = null;
        $cantidad = 0;
        if ($estado == 1) {
            $cargos = $repositorio->findBy(array('activo' => false));
        } else {
            $cargos = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad = count($inactivos);
        }
        $msj = $request->getSession()->get('msjCar');
        $error = $request->getSession()->get('error');
        $request->getSession()->remove('msjCar');
        $request->getSession()->remove('error');
        $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'registros' => $cargos);
        return $this->render('SisGGFinalBundle:Cargo:cargos.html.twig', $parameters);
    }

    public function cargarCargoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $nombre = $request->get('name');
            $cargos = $this->getDoctrine()->getRepository('SisGGFinalBundle:CargoSistema')->findBy(array('activo' => true));
            return $this->render('SisGGFinalBundle:Cargo:cargarCargo.html.twig', array('ps' => $cargos, 'name' => $nombre));
        }
    }

    public function obtenerCargoAction(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $cargo = new CargoSistema();
            $cargo = $this->getDoctrine()->getRepository('SisGGFinalBundle:CargoSistema')->findOneBy(array('id'=>$request->get('id'),'activo'=>true ));
            if ($cargo != null)
                return new Response(json_encode(array('rta' => 1, 'id' => $cargo->getId(), 'descuento'=>$cargo->getDescuento() ,'nombre' => $cargo->getNombre(), 'porDia' => $cargo->getPorDia(), 'porFecha' => $cargo->getPorFecha(), 'porSemana' => $cargo->getPorDiaSemana(), 'tipo' => $cargo->getTipo(), 'monto' => $cargo->getMonto(), 'negativo' => $cargo->getNegativo(), 'desc' => $cargo->getDescripcion())));
            return new Response(json_encode(array('rta' => 0)));
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>
