<?php

namespace SisGG\FinalBundle\Controller;

//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Entity\AdicionalEmpleado;
use SisGG\FinalBundle\Entity\CancelarEmpleado;
use SisGG\FinalBundle\Entity\ContadoEmpleado;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Entity\Image;
use SisGG\FinalBundle\Entity\PersonaEmpleado;
use SisGG\FinalBundle\Form\AdicionalEmpleadoType;
use SisGG\FinalBundle\Form\CancelarEmpleadoType;
use SisGG\FinalBundle\Form\CiudadType;
use SisGG\FinalBundle\Form\ContadoEmpleadoType;
use SisGG\FinalBundle\Form\EspeciesEmpleadoType;
use SisGG\FinalBundle\Form\ImageType;
use SisGG\FinalBundle\Form\PersonaEmpleadoType;
use SisGG\FinalBundle\Form\ProvinciaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Form\CargoEmpleadoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SisGG\FinalBundle\Entity\EspeciesEmpleado;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PersonaEmpleadoController
 *
 * @author sergio
 */
class PersonaEmpleadoController extends Controller
{

    function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new \ReflectionObject($sourceObject);
        $destinationReflection = new \ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    public function nuevaPersonaEAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $pe = new PersonaEmpleado ();
        $formulario = $this->createForm(new PersonaEmpleadoType(), $pe, array('attr' => array('tipo' => 1)));
        $formCiudad = $this->createForm(new CiudadType());
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        return $this->render('SisGGFinalBundle:PersonaEmpleado:nuevaPersonaE.html.twig', array('form' => $formulario->createView(), 'imagen' => $imgen->createView(), 'error' => null, 'errorProv' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function altaPersonaEAction(Request $request, Empresa $empresa = null)
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $pe = new PersonaEmpleado ();
        $formulario = $this->createForm(new PersonaEmpleadoType(), $pe, array('attr' => array('tipo' => 1)));
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        //imagen
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $imgen->handleRequest($request);
        //
        $formulario->handleRequest($request);
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        // return $this->render('SisGGFinalBundle:PersonaEmpleado:nuevaPersonaE.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'errorProv' => "Verifique que los campos ingresados sean correctos.".$pe->getUsername(), 'error' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));

        if ($formulario->isValid()) {
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($pe);
            $password = $encoder->encodePassword($pe->getPassword(), $pe->getSalt());
            $pe->setPassword($password);
            $r = $empresa->altaPersonaEmpleado($pe, $this);
            if ($r == null) {

                $msj = "El empleado " . $pe->getApellido() . " DNI: " . $pe->getDni() . " ha sido registrado exitosamente.";
                //Imagen
                $id = $pe->getFoto()->getId();
                $em->persist($newfondo);
                $pe->setFoto($newfondo);
                $em->flush();
                $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                $em->remove($foto);
                $em->flush();
                //
                $request->getSession()->set('msjPeE', $msj);
                return $this->redirect($this->generateUrl('controlPagos'));
            } else {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:nuevaPersonaE.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => null, 'errorProv' => $r, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
            }
        }
        return $this->render('SisGGFinalBundle:PersonaEmpleado:nuevaPersonaE.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'errorProv' => "Verifique que los campos ingresados sean correctos.", 'error' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function editarPersonaEAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));

        if ($empleado == null) {
            $request->getSession()->set('error', 'Error al ingresar los datos');
            return $this->redirect($this->generateUrl('controlPagos'));
        }
        $empleado->setCargoAct($empleado->getCargo());
        $formulario = $this->createForm(new PersonaEmpleadoType(), $empleado, array('attr' => array('tipo' => 2, 'empleado' => $empleado)));
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $r = 0;
        if ($empleado->getPrimerPago() != null) {
            $r = 1;
        }

        //imagen
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($empleado->getFoto() != null) {
            $id = $empleado->getFoto()->getId();
        }
        if ($id != null) {
            $newfondo = $empleado->getFoto();
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $newfondo->getPath();
        } else {
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }
        //
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        return $this->render('SisGGFinalBundle:PersonaEmpleado:editarPersonaE.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'empleado' => $empleado, 'idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'form' => $formulario->createView(), 'r' => $r, 'error' => null, 'errorProv' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function modificarPersonaEAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $this->getEmpresa();
        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
        if ($empleado == null) {
            $request->getSession()->set('error', 'Error al ingresar los datos');
            return $this->redirect($this->generateUrl('controlPagos'));
        }
        //Imagen
        $tipo = 5;
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($empleado->getFoto() != null) {
            $id = $empleado->getFoto()->getId();
        }
        if ($id != null) {
            // $newfondo = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $empleado->getFoto()->getPath();
            if ($request->get('tipo') != null) {
                $empleado->setFoto(null);
                $tipo = 1;
            }
        } else {
            $tipo = 0;
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }

        $imgen->handleRequest($request);
        //
        $formulario = $this->createForm(new PersonaEmpleadoType(), $empleado, array('attr' => array('tipo' => 2, 'empleado' => $empleado)));
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        $r = 0;
        //$formulario->handleRequest($request); 
        $datos = array();
        // Create an array of the current Tag objects in the database
        foreach ($empleado->getTelefonos() as $item) {
            $datos[] = $item;
        }
        if ($empleado->getPrimerPago() != null) {
            $r = 1;
            $formulario->handleRequest($request);
            if ($formulario->isValid()) {
                $error = $empresa->modificarPersonaEmpleado($empleado, $datos, $this);
                if ($error == null) {
                    $msj = "El empleado " . $empleado->getApellido() . " DNI: " . $empleado->getDni() . " ha sido modficicado exitosamente.";
                    //Imagen
                    if ($tipo == 1) {

                        $idNull = $empleado->getFoto()->getId();

                        $em->persist($newfondo);
                        $empleado->setFoto($newfondo);

                        $fotonull = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($idNull);
                        $fotonull->removeUpload();
                        $em->remove($fotonull);


                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    } elseif ($tipo == 0) {
                        $id = $empleado->getFoto()->getId();
                        $em->persist($newfondo);
                        $empleado->setFoto($newfondo);
                        $em->flush();
                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    }
                    //
                    $request->getSession()->set('msjPeE', $msj);
                    return $this->redirect($this->generateUrl('controlPagos'));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:editarPersonaE.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => $error, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
                }
            }
        } else {
            $r = 0;
            $formulario->handleRequest($request);
            //return $this->render('SisGGFinalBundle:PersonaEmpleado:editarPersonaE.html.twig', array('empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => $empleado->getCargo()->getNombre() , 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
            if ($formulario->isValid()) {
                $error = $empresa->modificarPersonaEmpleado($empleado, $datos, $this);
                if ($error == null) {
                    $msj = "El empleado " . $empleado->getApellido() . " DNI: " . $empleado->getDni() . " ha sido modficicado exitosamente.";
                    //Imagen
                    if ($tipo == 1) {

                        $idNull = $empleado->getFoto()->getId();

                        $em->persist($newfondo);
                        $empleado->setFoto($newfondo);

                        $fotonull = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($idNull);
                        $fotonull->removeUpload();
                        $em->remove($fotonull);


                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    } elseif ($tipo == 0) {
                        $id = $empleado->getFoto()->getId();
                        $em->persist($newfondo);
                        $empleado->setFoto($newfondo);
                        $em->flush();
                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    }
                    //
                    $request->getSession()->set('msjPeE', $msj);
                    return $this->redirect($this->generateUrl('controlPagos'));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:editarPersonaE.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => $error, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
                }
            }
        }

        return $this->render('SisGGFinalBundle:PersonaEmpleado:editarPersonaE.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => 'Verifique que los campos ingresados sean correctos.', 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function editarCargoEmpAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
        if ($empleado == null) {
            $request->getSession()->set('error', 'Error al ingresar los datos');
            return $this->redirect($this->generateUrl('controlPagos'));
        }
        $empleado->setCargoAct($empleado->getCargo());
        $formulario = $this->createForm(new PersonaEmpleadoType(), $empleado, array('attr' => array('tipo' => 3, 'empleado' => $empleado)));

        $r = 0;
        if ($empleado->getPrimerPago() != null) {
            $r = 1;
        }

        return $this->render('SisGGFinalBundle:PersonaEmpleado:editarCargo.html.twig', array('empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => null));
    }

    public function modificarCargoEmpAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
        if ($empleado == null) {
            $request->getSession()->set('error', 'Error al ingresar los datos');
            return $this->redirect($this->generateUrl('controlPagos'));
        }
        $r = 0;
        $formulario = $this->createForm(new PersonaEmpleadoType(), $empleado, array('attr' => array('tipo' => 3, 'empleado' => $empleado)));
        $error = $empresa->modificarCargoEmpleado($empleado, $this);
        if ($error != null) {
            return $this->render('SisGGFinalBundle:PersonaEmpleado:editarCargo.html.twig', array('idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => $error));
        }
        $formulario->handleRequest($request);
        if ($empleado->getPrimerPago() != null) {
            if ($formulario->isValid()) {
                $error = $empresa->modificarCargoEmpleado($empleado, $this);
                if ($error == null) {
                    $msj = "El empleado" . $empleado->getApellido() . " DNI: " . $empleado->getDni() . " ha sido modficicado exitosamente.";
                    $request->getSession()->set('msjPeE', $msj);
                    return $this->redirect($this->generateUrl('controlPagos'));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:editarCargo.html.twig', array('idP' => $empleado->getDireccion()->getCiudad()->getProvincia()->getId(), 'empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => $error));
                }
            }
        }

        return $this->render('SisGGFinalBundle:PersonaEmpleado:editarCargo.html.twig', array('empleado' => $empleado, 'form' => $formulario->createView(), 'r' => $r, 'error' => 'ele'));
    }

    public function popDetallesEmpleadoAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            return $this->render('SisGGFinalBundle:PersonaEmpleado:pop.html.twig', array('form' => null, 'empleado' => $empleado));
        }
    }

    public function cantidadPendientesAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            return new Response(json_encode($empleado->cantiadDePendientes()));
        }
    }

    public function verDetallesEmpleadoAction(Request $request)
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_DETALLES)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
        $empleado->setCargoAct($empleado->getCargo());
        $cargos = $this->reverseCollection($empleado->getCargos());
        $form = $this->createForm(new PersonaEmpleadoType(), $empleado, array('disabled' => true, 'attr' => array('tipo' => 1)));

        return $this->render('SisGGFinalBundle:PersonaEmpleado:detalles.html.twig', array('form' => $form->createView(), 'error' => null, 'primero' => $empleado->getPrimerPago(), 'empleado' => $empleado, 'cargos' => $cargos, 'provincia' => $empleado->getDireccion()->getCiudad()->getProvincia()->getNombre()));
    }

    private function reverseCollection($collection)
    {
        $itemArray = null;
        if ($collection == null || (count($collection) == 0)) {
            return null;
        }
        foreach ($collection as $item) {
            $itemArray[] = $item;
        }
        return array_reverse($itemArray);
    }

    public function actTablaMovEmpAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $parameters = array('empleado' => $empleado, 'form' => null);
            return $this->render('SisGGFinalBundle:PersonaEmpleado:actMov.html.twig', $parameters);
        }
    }

    public function empleadosAction(Request $request)
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($request->getSession()->get('vengoDeControl') == null) {
            $request->getSession()->remove('vengoDeControl');
            return $this->redirect($this->generateUrl('controlPagos'));
        } else {
            $request->getSession()->remove('vengoDeControl');
        }

        $estado = null;
        $estado = $msj = $request->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:PersonaEmpleado');
        $empleados = null;
        $cantidad = 0;
        if ($estado == 1) {
            $empleados = $repositorio->findBy(array('activo' => false));
        } else {
            $empleados = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad = count($inactivos);
        }

        $msj = $request->getSession()->get('msjPeE');
        $request->getSession()->remove('msjPeE');
        $error = $request->getSession()->get('error');
        $request->getSession()->remove('error');
        $parameters = array('registros' => $empleados, 'msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error);
        return $this->render('SisGGFinalBundle:PersonaEmpleado:empleados.html.twig', $parameters);
    }

    public function activarEmpleadoAction(Request $request)
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $request->get('id');
            if ($id != null) {
                /* @var Producto $producto */
                $empleado = $em->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($id);
                $empleado->setActivo(true);
                $empleado->setIsActive(true);
                $em->flush($empleado);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $empleado->getApellido() . ' DNI ' . $empleado->getDni())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function eliminarEmpleadoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:PersonaEmpleado');
        $empleado = $repositorio->find($request->get('id'));
        $empleado->setActivo(false);
        $empleado->setIsActive(false);
        $em->flush($empleado);
        $request->getSession()->set('msjPeE', 'El empleado ' . $empleado->getApellido() . ' ha sido correctamente eliminado.');
        return $this->redirect($this->generateUrl('controlPagos'));
    }

    public function pendientesAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = -1;
            }
            $registros = $empleado->generarListaPendientes();
            if ($registros == -1)
                $registros = null;
            //$this->getDoctrine()->getEntityManager()->flush();
            return $this->render('SisGGFinalBundle:PersonaEmpleado:pendientesA.html.twig', array('form' => null, 'error' => $request->get('error'), 'msj' => $request->get('msj'), 'empleado' => $empleado, 'r' => $r, 'id' => $request->get('id'), 'registros' => $registros));
        }
    }

    public function listaLiquidacionAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = 0;
            }
            $registros = $empleado->generarListaLiquidacion();
            if ($registros == -1)
                $registros = null;
            //$this->getDoctrine()->getEntityManager()->flush();
            return $this->render('SisGGFinalBundle:PersonaEmpleado:pendientesLiq.html.twig', array('form' => null, 'empleado' => $empleado, 'r' => $r, 'id' => $request->get('id'), 'registros' => $registros));
        }
    }

    public function controlPagosAction(Request $request)
    {
        //listar
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empleados = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->findAll();
        $var = null;
        foreach ($empleados as $value) {
            $var[] = $value->controlarPago();
            $this->getDoctrine()->getEntityManager()->flush();
        }
        $estado = null;
        $estado = $msj = $request->get('estado');
        $this->getDoctrine()->getEntityManager()->flush();
        $request->getSession()->set('vengoDeControl', 'true');
        return $this->redirect($this->generateUrl('empleados', array('estado' => $estado)));
        //$this->getDoctrine()->getEntityManager()->flush();
        // return $this->render('SisGGFinalBundle:PersonaEmpleado:pendientes.html.twig', array('form' => null, 'error'=> null, 'registros'=>$registros));
    }

    public function registrarPagoEmpleadoAction()
    {
        //ver que hacia
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            if ($request->get('id') == null) {
                $request->getSession()->set('error', 'Error al ingresar los datos');
                return $this->redirect($this->generateUrl('controlPagos'));
            }
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            if ($empleado == null) {
                $request->getSession()->set('error', 'Error al ingresar los datos');
                return $this->redirect($this->generateUrl('controlPagos'));
            }
            $error = null;
            if ($request->get('tipo') == '1') {
                $error = $empleado->pagoPrimerPendiente();
            } elseif ($request->get('tipo') == '2') {
                $j = $empleado->cantiadDePendientes();
                for ($i = 0; $i < $j; $i++) {
                    $error = $empleado->pagoPrimerPendiente();
                }
            } else {
                $request->getSession()->set('error', 'Error al ingresar los datos');
                return $this->redirect($this->generateUrl('controlPagos'));
            }
            $this->getDoctrine()->getEntityManager()->flush();
            $msj = null;
            if ($error == 1) {
                $error = null;
            }
            if ($error == null or $error == 1) {
                $msj = "Se han registrado correctamente.";
            }
            return $this->redirect($this->generateUrl('pendientes', array('id' => $request->get('id'), 'msj' => $msj, 'error' => $error)));
        }
    }

    public function nuevoContadoEmpAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $formulario = $this->createForm(new ContadoEmpleadoType());
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = -1;
            }
            return $this->render('SisGGFinalBundle:PersonaEmpleado:contado.html.twig', array('error' => null, 'form' => $formulario->createView(), 'empleado' => $empleado, 'r' => $r));
        }
    }

    public function altaContadoEmpAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $con = new ContadoEmpleado ();
            $formulario = $this->createForm(new ContadoEmpleadoType(), $con);
            $formulario->handleRequest($request);
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('cuenta'));
            $con->setCuenta($empleado->getCuenta());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaContadoEmpleado($con, $empleado, $this);
                if (is_numeric($r)) {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:contado.html.twig', array('r' => 0, 'empleado' => $empleado, 'error' => null, 'mov' => $r, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:contado.html.twig', array('r' => true, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:contado.html.twig', array('r' => 1, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
    }

    public function nuevoAdicionalEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $formulario = $this->createForm(new AdicionalEmpleadoType());
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = -1;
            }
            return $this->render('SisGGFinalBundle:PersonaEmpleado:adicional.html.twig', array('error' => null, 'form' => $formulario->createView(), 'empleado' => $empleado, 'r' => $r));
        }
    }

    public function altaAdicionalEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $con = new AdicionalEmpleado ();
            $formulario = $this->createForm(new AdicionalEmpleadoType(), $con);
            $formulario->handleRequest($request);
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('cuentaADIC'));
            $con->setCuenta($empleado->getCuenta());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaAdicionalEmpleado($con, $empleado, $this);
                if ($r == null) {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:adicional.html.twig', array('r' => 0, 'empleado' => $empleado, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:adicional.html.twig', array('r' => true, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:adicional.html.twig', array('r' => 1, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
    }

    public function nuevoCancelarEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $formulario = $this->createForm(new CancelarEmpleadoType());
            $empleado = new PersonaEmpleado();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = -1;
            }
            return $this->render('SisGGFinalBundle:PersonaEmpleado:cancelar.html.twig', array('error' => null, 'form' => $formulario->createView(), 'empleado' => $empleado, 'r' => $r));
        }
    }

    public function altaCancelarEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $con = new CancelarEmpleado ();
            $formulario = $this->createForm(new CancelarEmpleadoType(), $con);
            $formulario->handleRequest($request);
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('cuentaCANC'));
            $con->setCuenta($empleado->getCuenta());
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaCancelarEmpleado($con, $empleado, $this);
                if ($r == null) {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:cancelar.html.twig', array('r' => 0, 'empleado' => $empleado, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:cancelar.html.twig', array('r' => true, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:cancelar.html.twig', array('r' => 1, 'empleado' => $empleado, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
    }

    public function nuevoEspeciesEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $formulario = $this->createForm(new EspeciesEmpleadoType());
            $empleado = new PersonaEmpleado();
            $pps = $this->obtenerProducto();
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('id'));
            $r = 1;
            if (!($empleado->getActivo())) {
                $r = -1;
            }

            return $this->render('SisGGFinalBundle:PersonaEmpleado:especies.html.twig', array('error' => null, 'form' => $formulario->createView(), 'pps' => $pps, 'empleado' => $empleado, 'r' => $r));
        }
    }

    public function altaEspeciesEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
            }
            $con = new EspeciesEmpleado ();
            $formulario = $this->createForm(new EspeciesEmpleadoType(), $con);
            $formulario->handleRequest($request);
            $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('cuentaESP'));
            $con->setCuenta($empleado->getCuenta());

            $pps = $this->obtenerProducto();
            if ($formulario->isValid()) {
                $empresa = $this->getEmpresa();
                $r = $empresa->altaEspeciesEmpleado($con, $empleado, $this);
                if (is_numeric($r)) {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:especies.html.twig', array('r' => 0, 'mov' => $r, 'empleado' => $empleado, 'pps' => $pps, 'error' => null, 'form' => $formulario->createView()));
                } else {
                    return $this->render('SisGGFinalBundle:PersonaEmpleado:especies.html.twig', array('r' => true, 'empleado' => $empleado, 'pps' => $pps, 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:PersonaEmpleado:especies.html.twig', array('r' => 1, 'empleado' => $empleado, 'pps' => $pps, 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
    }

    public function cambiarDescuentoAction()
    {
        // if ($request->isXmlHttpRequest()) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_GESTION)) {
            return $this->render('SisGGFinalBundle:PersonaEmpleado:sinPermiso.html.twig');
        }
        $error = null;
        $r = true;
        $empleado = new PersonaEmpleado();
        $empleado = $this->getDoctrine()->getRepository("SisGGFinalBundle:PersonaEmpleado")->find($request->get('idEmpleado'));
        if ($request->getMethod() === 'POST') {
            $descuento = $request->get('txtDescuento');
            if (is_numeric($descuento)) {
                if ($descuento <= 100 && $descuento >= 0) {
                    $empleado->getCargo()->setDescuento($descuento);
                    $this->getDoctrine()->getEntityManager()->flush();
                    $r = false;
                } else {
                    $error = "Ingrese un valor numérico.";
                }
            } else {
                $error = "Ingrese un valor numérico.";
            }
        }
        return $this->render('SisGGFinalBundle:PersonaEmpleado:descuento.html.twig', array('error' => $error, 'empleado' => $empleado, 'r' => $r));

        // }
    }

    public function detallesEspeciesEmpAction()
    {
        if ($request->isXmlHttpRequest()) {
            $especies = $this->getDoctrine()->getRepository("SisGGFinalBundle:EspeciesEmpleado")->find($request->get('id'));
            $form = $this->createForm(new EspeciesEmpleadoType(), $especies, array('disabled' => true));
            return $this->render('SisGGFinalBundle:PersonaEmpleado:detallesEspecies.html.twig', array('form' => $form->createView(), 'esp' => $especies, 'fecha' => $especies->getFecha(), 'id' => $especies->getId(), 'items' => $especies->getItem()));
        }
    }

    private function obtenerProducto()
    {
        $productos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Producto')->findAll();
        foreach ($productos as $value) {
            if (($value->isPlato() || $value->isMercaderia()) && ($value->getActivo())) {
                $array[] = $value;
            }
        }
        return $array;
    }

    public function cargarMerPlaAction()
    {
        //ajax
        if ($request->isXmlHttpRequest()) {
            $productos = $this->obtenerProducto();
            return $this->render('SisGGFinalBundle:NotaPedido:cargar.html.twig', array('form' => null, 'ps' => $productos, 'pr' => $productos[0]->getid()));
        }
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    //Imprimir

//    /**
//     * @Pdf()
//     */
//    public function impEgresoEmpleadoAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_IMP_COMPROBANTE)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $mov = $this->getDoctrine()->getRepository('SisGGFinalBundle:MovEmpleado')->find($request->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'tipo' => $request->get('tipo'), 'mov' => $mov, 'empleado' => $mov->getCuenta()->getEmpleado(), 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:PersonaEmpleado:imp_retiro.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impDetallesPerEmpAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_IMP_DETALLES)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $empleado = $this->getDoctrine()->getRepository('SisGGFinalBundle:PersonaEmpleado')->find($request->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'empleado' => $empleado, 'empresa' => $this->getEmpresa(), 'chk3' => $request->get('chk3'), 'chk2' => $request->get('chk2'), 'chk1' => $request->get('chk1'));
//        return $this->render('SisGGFinalBundle:PersonaEmpleado:imp_detalles.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impListaEmpAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PERSONAE_IMP_LISTA)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $request->get('ids'));
//        $info = $request->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:PersonaEmpleado')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:PersonaEmpleado:imp_lista.' . $format . '.twig', $parameters);
//    }

}
