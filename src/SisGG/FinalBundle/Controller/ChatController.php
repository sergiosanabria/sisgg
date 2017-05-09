<?php

namespace SisGG\FinalBundle\Controller;

use DateTime;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\ChatMensaje;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\ConfigMenu;
/**
 * Description of AgendaController
 *
 * @author sergio
 */
class ChatController extends Controller {

    public function chatAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_CHAT)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $usuarios = $em->getRepository('SisGGFinalBundle:Usuario')->findBy(array('isActive' => true) , array('apellido'=>'ASC', 'nombre'=>'ASC'));
        //$yo  = $this->get('security.context')->getToken()->getUser(); 
        $msj = $this->getRequest()->getSession()->get('msjChat');
        $error = $this->getRequest()->getSession()->get('errorChat');
        $this->getRequest()->getSession()->remove('msjChat');
        $this->getRequest()->getSession()->remove('errorChat');
        $parameters = array('msj' => $msj, 'form' => null, 'error' => $error, 'usuarios' => $usuarios);
        return $this->render('SisGGFinalBundle:Chat:chat.html.twig', $parameters);
    }

    public function enviarChatAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_CHAT)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $em = $this->getDoctrine()->getEntityManager();
            $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('destino'));
            $yo = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('yo'));
            $msj = new ChatMensaje($yo);
            $msj->setDestino($destino);
            $msj->setUsuario($yo);
            $msj->setMensaje($this->getRequest()->get('msj'));
            $msj->setFechaEnvio();
            $msj->setLeido(1);
            $msj->setEnvio(1);
//        $em = $this->getDoctrine()->getEntityManager();
//        $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find(2);
//        $yo = $em->getRepository('SisGGFinalBundle:Usuario')->find(1);
//        $msj = new ChatMensaje($yo);
//        $msj->setDestino($destino);
//        $msj->setUsuario($yo);
//        $msj->setMensaje('Soy admin, pedro');
//        $msj->setFechaEnvio();
//        $msj->setLeido(1);
//        $msj->setEnvio(0);
            $em->persist($msj);
            $em->flush($msj);
            $array[] = $msj;
            return $this->render('SisGGFinalBundle:Chat:cargarChat.html.twig', array('msjs' => $array, 'form' => null));
        }
    }

    public function cargarChatAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_CHAT)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }


            $em = $this->getDoctrine()->getEntityManager();
            $usuarios = $em->getRepository('SisGGFinalBundle:Usuario')->findBy(array('isActive' => true));
            $yo = $this->getUser();
            $array = null;
            $retorno = null;

            if ($this->getRequest()->get('tipo') == 1) {
                $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('destino'));
                $array = $yo->obtenerInteractuados($destino, true);
                $noLeidos = $yo->obtenerMensajesNoLeidos($destino);
                if (count($noLeidos) > 0) {
                    foreach ($noLeidos as $value) {
                        $value->setLeido(0);
                        $value->setFechaRecibo(new DateTime('now'));
                        $em->flush($value);
                    }
                }
                return $this->render('SisGGFinalBundle:Chat:cargarChat.html.twig', array('msjs' => $array, 'form' => null));
            } elseif ($this->getRequest()->get('tipo') == 2) {
                //cargar los no leidos
                $abiertos = $this->getRequest()->get('abiertos');
                if ($abiertos != NULL && count($abiertos) > 0) {
                    foreach ($abiertos as $id) {
                        /* @var $destino \SisGG\FinalBundle\Entity\Usuario */
                        $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($id);
                        $noLeidos = $yo->obtenerMensajesNoLeidos($destino);
                        if (count($noLeidos) > 0) {
                            foreach ($noLeidos as $value) {
                                $value->setLeido(0);
                                $value->setFechaRecibo(new DateTime('now'));
                                $em->flush($value);
                            }
                            $data = "";
                            foreach ($noLeidos as $msj) {
                                /* @var $msj ChatMensaje */
                                if ($yo == $msj->getUsuario()) {
                                    $pos = "right";
                                    $nombre = "Yo";
                                } else {
                                    $pos = "left";
                                    $nombre = $msj->getUsuario();
                                }
                                $data.='<li class="' . $pos . '">' . '<span class="message"><span class="arrow"></span>' . '<span class="from"><strong>' . $nombre . ' </strong></span><span class="time"> Enviado: ' . date_format($msj->getFechaEnvio(), 'd/m/y H:i') . '</span>' . '<span class="text">' . $msj->getMensaje() . '</span></span></li>';
                            }

                            $retorno [] = array('id' => $id, 'data' => $data);
                        } else {
                            $retorno [] = '@error';
                        }
                    }
                } else {
                    return new Response('@error');
                }

                return new Response(json_encode($retorno));
            } elseif ($this->getRequest()->get('tipo') == 3) {
                //cantidad de no leidos
                $retorno=null;
                if ($usuarios != null && count($usuarios) > 0) {
                    foreach ($usuarios as $destino) {
                        /* @var $destino \SisGG\FinalBundle\Entity\Usuario */
                        if ($destino != $yo) {
                            $noLeidos = $yo->obtenerMensajesNoLeidos($destino);
                            if ($noLeidos != null && count($noLeidos) > 0) {
                                $canNo=count($noLeidos);
                                $codigo = $noLeidos[$canNo-1]->getId();
                                $retorno[] = array('id' => $destino->getId(), 'codigo' => $codigo, 'val' => $canNo, 'msj' => $yo->cantidadMensajes($destino->getId()), 'nombre' => $destino->getApellido() . ', ' . $destino->getNombre());
                            }
                        }
                    }
                } else {
                    $retorno[0] = array('id' => 'error');
                }
                if ($retorno==null){
                    $retorno[0] = array('id' => 'error');
                }
                

                return new Response(json_encode($retorno));
            } elseif ($this->getRequest()->get('tipo') == 4) {
                //eliminar
                $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('destino'));
                $array = $yo->obtenerInteractuados($destino, true);
                foreach ($array as $value) {
                    if ($value->getEliminar() == null) {
                        $value->setEliminar($this->getRequest()->get('yo'));
                        $em->flush();
                    } elseif ($value->getEliminar() == $this->getRequest()->get('destino')) {
                        $em->remove($value);
                        $em->flush();
                    }
                }
                return $this->render('SisGGFinalBundle:Chat:cargarChat.html.twig', array('msjs' => $yo->obtenerInteractuados($destino, true), 'form' => null));
            } elseif ($this->getRequest()->get('tipo') == 5) {
                //cantidad de nuevos
                $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('destino'));
                $nuevos = $yo->obtenerMensajesNuevos($destino);
                if (count($nuevos) > 0) {
                    foreach ($nuevos as $value) {
                        $value->setEnvio(0);
                        $em->flush($value);
                    }
                    return new Response(json_encode(array('val' => count($nuevos))));
                } else {
                    return new Response(json_encode(array('val' => 0)));
                }
            }
        }
    }

    public function eliminarConversacionAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $manager = $this->getDoctrine()->getEntityManager();
//        $destino = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('destino'));
//        $yo = $em->getRepository('SisGGFinalBundle:Usuario')->find($this->getRequest()->get('yo'));
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::GESTION_CHAT)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $empresa = $this->getEmpresa();
            $caja = new Caja();
            $caja->setPuntoVenta(1);
            $caja->setUltimaFactura(1);
            $caja->setAbierta(false);
            $caja->setEmpresa($empresa);
            $manager->persist($caja);
            $manager->flush();
        }
    }

    public function abrirWSAction() {
        $manager = $this->getDoctrine()->getEntityManager();
        $menu1 = new ConfigMenu();
        $menu1->setColor('#FFFFFF');
        $menu1->setCursiva(false);
        $menu1->setIncluye(true);
        $menu1->setNegrita(true);
        $menu1->setNombre('Categoría');
        $manager->persist($menu1);
        $manager->flush();
        
        
        $menu2 = new ConfigMenu();
        $menu2->setColor('#FFFFFF');
        $menu2->setCursiva(false);
        $menu2->setIncluye(false);
        $menu2->setNegrita(true);
        $menu2->setNombre('Código QR');
        $manager->persist($menu2);
        $manager->flush();
        
        $menu3 = new ConfigMenu();
        $menu3->setColor('#FFFFFF');
        $menu3->setCursiva(true);
        $menu3->setIncluye(true);
        $menu3->setNegrita(true);
        $menu3->setNombre('Ingredientes');
        $manager->persist($menu3);
        $manager->flush();
        
        $menu4 = new ConfigMenu();
        $menu4->setColor('#FFFFFF');
        $menu4->setCursiva(false);
        $menu4->setIncluye(true);
        $menu4->setNegrita(true);
        $menu4->setNombre('Nombre');
        $manager->persist($menu4);
        $manager->flush();
        
        $menu5 = new ConfigMenu();
        $menu5->setColor('#FFFFFF');
        $menu5->setCursiva(false);
        $menu5->setIncluye(true);
        $menu5->setNegrita(true);
        $menu5->setNombre('Precio de venta');
        $manager->persist($menu5);
        $manager->flush();
    }

    public function actChatAction() {
        /* @var $empresa \SisGG\FinalBundle\Entity\Empresa */
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $producto = $em->getRepository('SisGGFinalBundle:ProductoVenta')->findAll();
        return new Response(json_encode($empresa->calificarPorCategorias($producto)));
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    public function notificacionNoLeidosAction() {
        if ($usuarios != null && count($usuarios) > 0) {
            foreach ($usuarios as $destino) {
                /* @var $destino \SisGG\FinalBundle\Entity\Usuario */
                if ($destino != $yo)
                    $retorno[] = array('id' => $destino->getId(), 'val' => $yo->cantidadNoLeidos($destino->getId()), 'msj' => $yo->cantidadMensajes($destino->getId()), 'nombre' => $destino->getApellido() . ', ' . $destino->getNombre());
            }
        }else {
            $retorno = "@error";
        }
    }

}

?>
