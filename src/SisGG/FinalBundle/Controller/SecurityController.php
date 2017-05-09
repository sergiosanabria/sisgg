<?php

namespace SisGG\FinalBundle\Controller;

use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\PersonaEmpleado;
use SisGG\FinalBundle\Form\PersonaEmpleadoType;
use SisGG\FinalBundle\Form\RolType;
use SisGG\FinalBundle\Form\UsuarioType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use \ReflectionObject;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Entity\Rol;

class SecurityController extends Controller {

    public function loginAction() {
//        $factory = $this->get('security.encoder_factory');
//        $usuario = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Usuario")->find(1);
//
//        $encoder = $factory->getEncoder($usuario);
//        $password = $encoder->encodePassword('admin', $usuario->getSalt());
//        $usuario->setPassword($password);
//        $em = $this->getDoctrine()->getEntityManager();
//        $msj = "La contraseña del usuario " . $usuario . " ha sido exitosamente modificada.";
//        $em->flush();
//
//        exit;
//
//


        $peticion = $this->getRequest();
        $sesion = $peticion->getSession();
        set_time_limit(500);
        if ($peticion->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $sesion->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('SisGGFinalBundle:Security:inicioSesion.html.twig', array('last_username' => $sesion->get(SecurityContext::LAST_USERNAME), 'error' => $error,));
    }

    public function usuarioNuevoAction(Request $peticion) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_NUEVO)) {
          throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
          }
        $usuario = new Usuario();
        $formulario = $this->createForm(new UsuarioType(), $usuario, array('attr' => array('tipo' => 1)));
        if ($peticion->getMethod() == 'POST') {
            $formulario->bindRequest($peticion);
            if ($formulario->isValid()) {
                $usuario = $formulario->getData();
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($usuario);
                $password = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
                $usuario->setPassword($password);
                $empresa = $this->getEmpresa();
                $usuario->setEmpresa($empresa);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($usuario);
                $em->flush();
                return $this->redirect($this->generateUrl('usuarios'));
            } else {
                return $this->render("SisGGFinalBundle:Usuario:nuevoUsuario.html.twig", array("form" => $formulario->createView(), 'error' => 'Verifique los campos ingresados.', 'gestor_autenticacion' => $gestor));
            }
        }
        return $this->render("SisGGFinalBundle:Usuario:nuevoUsuario.html.twig", array("form" => $formulario->createView(), 'error' => null, 'gestor_autenticacion' => $gestor));
    }

    function cast($destination, $sourceObject) {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
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


    public function editarUsuarioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $usuario = new Usuario();
        $usuario = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('id'));
        $formulario = null;
        if ($usuario->isEmpleado()) {
            $formulario = $this->createForm(new PersonaEmpleadoType(), $usuario, array('attr' => array('tipo' => 4)));
        } else {
            $formulario = $this->createForm(new UsuarioType(), $usuario, array('attr' => array('tipo' => 0)));
        }
        if (!($usuario->getIsActive())) {
            $this->getRequest()->getSession()->set('errorUsr', 'Usuario inválido.');
            return $this->redirect($this->generateUrl('usuarios'));
        }
        if ($this->getRequest()->getMethod() === 'POST') {
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                $em->flush();
                $msj = "El usuario " . $usuario . " ha sido exitosamente modificado.";
                $this->getRequest()->getSession()->set('msjUsr', $msj);
                return $this->redirect($this->generateUrl('usuarios'));
            } else {
                return $this->render('SisGGFinalBundle:Usuario:editarUsuario.html.twig', array('form' => $formulario->createView(), 'id' => $usuario->getId(), 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        return $this->render('SisGGFinalBundle:Usuario:editarUsuario.html.twig', array('form' => $formulario->createView(), 'id' => $usuario->getId(), 'error' => null));
    }

    public function perfilUsuarioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_PERFIL)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $msj = null;
        $msj = $this->getRequest()->getSession()->get('msjUsr');
        $this->getRequest()->getSession()->remove('msjUsr');
        $usuario = new Usuario();
        $usuario = $this->getUser();
        $formulario = $this->createForm(new UsuarioType(), $usuario, array('attr' => array('tipo' => 0), 'disabled' => true));
        return $this->render("SisGGFinalBundle:Usuario:perfil.html.twig", array("form" => $formulario->createView(), 'msj' => $msj, 'gestor_autenticacion' => $gestor));
    }

    public function cambiarContraseniaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_CAMBIARCONTRASENIA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $usuario = new Usuario();
        $usuario = $this->getUser();
        $formulario = null;
        if ($usuario->isEmpleado()) {
            $formulario = $this->createForm(new PersonaEmpleadoType(), $usuario, array('attr' => array('tipo' => 5)));
        } else {
            $formulario = $this->createForm(new UsuarioType(), $usuario, array('attr' => array('tipo' => 2)));
        }
        if (!($usuario->getIsActive())) {
            $this->getRequest()->getSession()->set('errorUsr', 'Usuario inválido.');
            return $this->redirect($this->generateUrl('usuarios'));
        }

        if ($this->getRequest()->getMethod() === 'POST') {
            $usuario2 = new Usuario();
            $usuario2->setPassword($this->getRequest()->get('contrasenia'));
            $usuario2->setSalt($usuario->getSalt());
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($usuario2);
            $password2 = $encoder->encodePassword($usuario2->getPassword(), $usuario2->getSalt());
            $vieja = $usuario->getPassword();
            $formulario->bindRequest($this->getRequest());
            if ($formulario->isValid()) {

                if ($vieja == $password2) {
                    $encoder = $factory->getEncoder($usuario);
                    $password = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
                    $usuario->setPassword($password);
                    $em = $this->getDoctrine()->getEntityManager();
                    $msj = "La contraseña del usuario " . $usuario . " ha sido exitosamente modificada.";
                    $em->flush();
                    $this->getRequest()->getSession()->set('msjCont', $msj);
                    return $this->redirect($this->generateUrl('cambiarContrasenia'));
                } else {

                    return $this->render('SisGGFinalBundle:Usuario:cambiarContrasenia.html.twig', array('form' => $formulario->createView(),'msj'=>null, 'id' => $usuario->getId(), 'rr' => true, 'error' => "La contraseña antigua es incorrecta."));
                }
            } else {
                return $this->render('SisGGFinalBundle:Usuario:cambiarContrasenia.html.twig', array('form' => $formulario->createView(),'msj'=>null, 'id' => $usuario->getId(), 'rr' => false, 'error' => "Verifique que los campos ingresados sean correctos."));
            }
        }
        $msj=null;
        $msj=$this->getRequest()->getSession()->get('msjCont');
        $this->getRequest()->getSession()->remove('msjCont');
        return $this->render('SisGGFinalBundle:Usuario:cambiarContrasenia.html.twig', array('form' => $formulario->createView(),'msj'=>$msj ,'id' => $usuario->getId(), 'error' => null, 'rr' => false));
    }

    public function eliminarUsuarioAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $usuario = new Usuario();
        $usuario = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('id'));
        if (!($usuario->getIsActive())) {
            $this->getRequest()->getSession()->set('errorUsr', 'Usuario inválido.');
            return $this->redirect($this->generateUrl('usuarios'));
        }
        $usuario->setIsActive(0);
        $em = $this->getDoctrine()->getEntityManager();
        $msj = "El usuario " . $usuario . " ha sido exitosamente desactivado.";
        $this->getRequest()->getSession()->set('msjUsr', $msj);
        $em->flush();

        return $this->redirect($this->generateUrl('usuarios'));
    }

    public function activarUsuarioAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var Producto $producto */
                $usuario = $em->getRepository("SisGGFinalBundle:Usuario")->find($id);
                $usuario->setIsActive(true);
                $em->flush($usuario);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $usuario->getApellido() . ' ' . $usuario->getNombre())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function usuariosAction() {

        if ($this->getRequest()->isXmlHttpRequest()) {
            /* var $usuario Usuario */
            $usuario = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario')->findOneBy(array('username' => $this->getRequest()->get('usuario')));
            $formulario = $this->createForm(new UsuarioType(), $usuario, array('disabled' => true, 'attr' => array('tipo' => 0)));
            return $this->render('SisGGFinalBundle:Usuario:usuario.html.twig', array('form' => $formulario->createView()));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario');
            $usuarios = null;
            $cantidad = 0;
            if ($estado == 1) {
                $usuarios = $repositorio->findBy(array('isActive' => false));
            } else {
                $usuarios = $repositorio->findBy(array('isActive' => true));
                $inactivos = $repositorio->findBy(array('isActive' => false));
                $cantidad = count($inactivos);
            }

            $msj = null;
            $error = null;
            $msj = $this->getRequest()->getSession()->get('msjUsr');
            $this->getRequest()->getSession()->remove('msjUsr');
            $error = $this->getRequest()->getSession()->get('errorUsr');
            $this->getRequest()->getSession()->remove('errorUsr');
            $parameters = array('estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'usuarios' => $usuarios, 'msj' => $msj, 'error' => $error);
            return $this->render("SisGGFinalBundle:Usuario:usuarios.html.twig", $parameters);
        }
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaUsuarioAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Usuario:imp_lista.' . $format . '.twig', $parameters);
//    }

    public function recuperarContraseniaAction() {
        //1 pantall 2 mail 3 ambos
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::USUARIO_RECUPERARCONTRASENIA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $tipo = $this->getRequest()->get('tipoRec');
        if ($tipo < 1 || $tipo > 4) {
            $this->getRequest()->getSession()->set('errorUsr', 'Error al recuperar la clave');
            return $this->redirect('usuarios');
        }
        $id = $this->getRequest()->get('idUsr');
        if ($id == -1) {
            $this->getRequest()->getSession()->set('errorUsr', 'Error al recuperar la clave');
            return $this->redirect('usuarios');
        }
        $contra = $this->getRequest()->get('contrasenia');
        if ($contra == null) {
            $this->getRequest()->getSession()->set('errorUsr', 'Error al recuperar la clave');
            return $this->redirect('usuarios');
        }
        /* @var $usuario Usuario */
        $usuario = $this->getDoctrine()->getRepository('SisGGFinalBundle:Usuario')->find($id);
        $gestor = $this->get("gestor_autenticacion");
        /* @var $empresa Empresa */
        $empresa = $this->getEmpresa();
        $usuario->setPassword($contra);
        if ($tipo == 1) {
            $this->getRequest()->getSession()->set('msjUsr', 'La nueva contraseña es ' . $contra);
        } elseif ($tipo == 2) {

            if (!($gestor->hayConexion())) {
                $this->getRequest()->getSession()->set('errorUsr', 'No hay conexión a internet, no es posible enviar el e-mail.');
                return $this->redirect('usuarios');
            }
            $transport = $this->get('mailer')->getTransport();
            $ext = $transport->getExtensionHandlers();
            $auth_handler = $ext[0];
            $auth_handler->setUserName($empresa->getEmail());
            $auth_handler->setPassword($empresa->getContrasenia());
            $message = Swift_Message::newInstance()
                    ->setSubject('Recuperar contraseña - SisGG ')
                    ->setFrom($empresa->getEmail())
                    ->setTo($usuario->getEmail())
                    ->setBody('Su nueva contraseña es: ' . $contra);
            $this->get('mailer')->send($message);
            $this->getRequest()->getSession()->set('msjUsr', 'La contraseña ha sido sido restablecida y enviada a su e-mail.');
        } elseif ($tipo == 3) {
            if (($gestor->hayConexion())) {
                $transport = $this->get('mailer')->getTransport();
                $ext = $transport->getExtensionHandlers();
                $auth_handler = $ext[0];
                $auth_handler->setUserName($empresa->getEmail());
                $auth_handler->setPassword($empresa->getContrasenia());
                $message = Swift_Message::newInstance()
                        ->setSubject('Recuperar contraseña - SisGG ')
                        ->setFrom($empresa->getEmail())
                        ->setTo($usuario->getEmail())
                        ->setBody('Su nueva contraseña es: ' . $contra);
                $this->get('mailer')->send($message);
                $this->getRequest()->getSession()->set('msjUsr', 'La nueva contraseña es ' . $contra . '. Ha sido enviada a su e-mail.');
            } else {
                $this->getRequest()->getSession()->set('msjUsr', 'La nueva contraseña es ' . $contra . '.');
                $this->getRequest()->getSession()->set('errorUsr', 'No hay conexión a internet, no es posible enviar el e-mail.');
            }
        } elseif ($tipo == 4) {
            $this->getRequest()->getSession()->set('msjUsr', 'La contraseña ha sido sido restablecida.');
        }


        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($usuario);
        $password = $encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
        $usuario->setPassword($password);
        $em->flush();
        return $this->redirect('usuarios');
    }

    public function rolNuevoAction(Request $peticion) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::ROl_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $role = $gestor->nuevoRol();
        foreach ($role->getPermisos() as $permiso) { //Hago las asociaciones que no tiene hechas
            $permiso->setRole($role);
        }
        $formulario = $this->createForm(new RolType, $role);
        if ($peticion->getMethod() == 'POST') {
            $formulario->bindRequest($peticion);
            if ($formulario->isValid()) {
                $role = $formulario->getData();
                $role->setActivo(true);
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($role);
                $em->flush();
                $this->getRequest()->getSession()->set('msjrol', 'El rol ' . $role->getRole() . ' ha sido exitosamente creado.');
                return $this->redirect($this->generateUrl('gestion_roles'));
            }
        }
        return $this->render("SisGGFinalBundle:Rol:nuevoRol.html.twig", array("form" => $formulario->createView(), 'gestor_autenticacion' => $gestor));
    }

    public function editarRolAction(Request $peticion) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::ROl_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $role = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Rol")->find($this->getRequest()->get("id"));
        $formulario = $this->createForm(new RolType, $role);
        if ($peticion->getMethod() == 'POST') {
            $formulario->bindRequest($peticion);
            if ($formulario->isValid()) {
                $role = $formulario->getData();
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($role);
                $em->flush();
                $this->getRequest()->getSession()->set('msjrol', 'El rol ' . $role->getRole() . ' ha sido exitosamente modificado.');
                return $this->redirect($this->generateUrl('gestion_roles'));
            }
        }
        return $this->render("SisGGFinalBundle:Rol:editarRol.html.twig", array("form" => $formulario->createView(), 'gestor_autenticacion' => $gestor, 'id' => $this->getRequest()->get("id")));
    }

    public function eliminarRolAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::ROl_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $rol Rol */
        $rol = $this->getDoctrine()->getRepository('SisGGFinalBundle:Rol')->find($this->getRequest()->get('id'));
        if (count($rol->getPermisos()) < 0 && (count($rol->getUsuarios()) < 0)) {
            $em->remove($rol);
            $this->getRequest()->getSession()->set('msjrol', 'El rol ' . $rol->getRole() . ' ha sido exitosamente eliminado.');
        } else {
            $this->getRequest()->getSession()->set('msjrol', 'El rol ' . $rol->getRole() . ' ha sido exitosamente eliminado.');
            $rol->setActivo(false);
        }
        $em->flush();
        return $this->redirect($this->generateUrl('gestion_roles'));
    }

    public function activarRolAction() {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::ROl_ACTIVAR)) {
                return new Response(json_encode(array('rta' => 'no')));
            }
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var $rol Rol */
                $rol = $em->getRepository("SisGGFinalBundle:Rol")->find($id);
                $rol->setActivo(true);
                $em->flush($rol);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $rol->getRole())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function gestionRolesAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::ROl_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $estado = null;
        $estado = $msj = $this->getRequest()->get('estado');
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Rol');
        $roles = null;
        $cantidad = 0;
        if ($estado == 1) {
            $roles = $repositorio->findBy(array('activo' => false));
        } else {
            $roles = $repositorio->findBy(array('activo' => true));
            $inactivos = $repositorio->findBy(array('activo' => false));
            $cantidad = count($inactivos);
        }
        $msj = $this->getRequest()->getSession()->get('msjrol');
        $this->getRequest()->getSession()->remove('msjrol');
        return $this->render("SisGGFinalBundle:Rol:roles.html.twig", array('estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'msj' => $msj, 'roles' => $roles));
    }

    /**
     * Get empresa
     *
     * @return Empresa
     */
    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}
