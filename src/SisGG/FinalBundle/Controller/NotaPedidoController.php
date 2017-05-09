<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\NotaPedidoType;
use SisGG\FinalBundle\Entity\NotaPedido;
use SisGG\FinalBundle\Entity\ItemNotaPedido;
//use Ps\PdfBundle\Annotation\Pdf;
use \Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Description of NotaPedidoController
 *
 * @author sergio
 */
class NotaPedidoController extends Controller
{

//Editando = 1, Finalizado=2 , Entregado=3
    public function nuevaNotaPedidoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::NOTAPEDIDO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $productos = $this->obtenerProducto();
        $empresa = $this->getEmpresa();
        $np = new NotaPedido();
        $np->getItem()->add(new ItemNotaPedido());
        $formulario = $this->createForm(new NotaPedidoType(), $np, array('attr' => array('productos' => $productos, 'clase' => 1)));
        $formulario->bindRequest($this->getRequest());
        if ($this->getRequest()->getMethod() === 'POST') {
            if ($formulario->isValid()) {
                $tipo = $this->getRequest()->get('tipo');
                if ($tipo != null) {
                    $r = $empresa->altaNotaPedido($np, $tipo, $this);
                    if ($r == null) {
                        $msj = "La nota de pedido ha sido exitosamente registrada.";
                        $this->getRequest()->getSession()->set('msjNP', $msj);
                        return $this->redirect($this->generateUrl('notas'));
                    } else {
                        $parameters = array('form' => $formulario->createView(), 'error' => $r, 'pps' => $productos['productos']);
                        return $this->render('SisGGFinalBundle:NotaPedido:nuevaNotaPedido.html.twig', $parameters);
                    }
                } else {
                    $parameters = array('form' => $formulario->createView(), 'error' => "ERROR", 'pps' => $productos['productos']);
                    return $this->render('SisGGFinalBundle:NotaPedido:nuevaNotaPedido.html.twig', $parameters);
                }
            }
            return $this->render('SisGGFinalBundle:NotaPedido:nuevaNotaPedido.html.twig', array('form' => $formulario->createView(), 'pps' => $productos['productos'], 'error' => "Verifique que los campos ingresados sean correctos."));
        }
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'pps' => $productos['productos']);
        return $this->render('SisGGFinalBundle:NotaPedido:nuevaNotaPedido.html.twig', $parameters);
    }

    public function editarNotaPedidoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::NOTAPEDIDO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $productos = $this->obtenerProducto();
        $empresa = $this->getEmpresa();
        $np = $this->getDoctrine()->getRepository("SisGGFinalBundle:NotaPedido")->find($this->getRequest()->get('id'));
        //$np->getItem()->add(new ItemNotaPedido());
        $formulario = $this->createForm(new NotaPedidoType(), $np, array('attr' => array('productos' => $productos, 'clase' => 1)));
        $tipo = $this->getRequest()->get('tipo');
        if ($tipo != null) {
            if ($this->getRequest()->getMethod() === 'POST') {
                if ($tipo == 3) {
                    $r = $empresa->modificarNotaPedido($np, $tipo, null, $this);
                    if ($r == null) {
                        $msj = 'La nota de pedido ha pasado a estado "entregado".';
                        $this->getRequest()->getSession()->set('msjNP', $msj);
                        return 'OK';
                    }
                }
                $datos = array();
                // Create an array of the current Tag objects in the database
                foreach ($np->getItem() as $item) {
                    $datos[] = $item;
                }
                $formulario->bindRequest($this->getRequest());

                if ($formulario->isValid()) {
                    if ($tipo == 1) {
                        $r = $empresa->modificarNotaPedido($np, $tipo, $datos, $this);
                        if ($r == null) {
                            $msj = "La nota de pedido ha sido exitosamente modificada.";
                            $this->getRequest()->getSession()->set('msjNP', $msj);
                            return $this->redirect($this->generateUrl('notas'));
                        } else {
                            $parameters = array('form' => $formulario->createView(), 'id' => $np->getId(), 'error' => "Verifique los campos ingresados.", 'pps' => $productos['productos']);
                            return $this->render('SisGGFinalBundle:NotaPedido:editarNotaPedido.html.twig', $parameters);
                        }
                    } elseif ($tipo == 2) {
                        $r = $empresa->modificarNotaPedido($np, $tipo, $datos, $this);
                        if ($r == null) {
                            $msj = "La nota de pedido ha sido exitosamente modificada y finalizada.";
                            $this->getRequest()->getSession()->set('msjNP', $msj);
                            return $this->redirect($this->generateUrl('notas'));
                        } else {
                            $parameters = array('form' => $formulario->createView(), 'id' => $np->getId(), 'error' => $r, 'pps' => $productos['productos']);
                            return $this->render('SisGGFinalBundle:NotaPedido:editarNotaPedido.html.twig', $parameters);
                        }
                    }
                    return $this->redirect($this->generateUrl('notas'));
                }
                return $this->render('SisGGFinalBundle:NotaPedido:editarNotaPedido.html.twig', array('form' => $formulario->createView(), 'id' => $np->getId(), 'pps' => $productos['productos'], 'error' => "Verifique que los campos ingresados sean correctos."));
            }

            if ($np->getEstado() == 1) {
                $parameters = array('form' => $formulario->createView(), 'error' => null, 'pps' => $productos['productos'], 'id' => $np->getId());
                return $this->render('SisGGFinalBundle:NotaPedido:editarNotaPedido.html.twig', $parameters);
            } else {
                $this->getRequest()->getSession()->set('error', 'No se puede editar esta nota pedido, ya que no se encuentra en edición');
                return $this->redirect($this->generateUrl('notas'));
            }
        } else {
            if ($np->getEstado() == 1) {
                $parameters = array('form' => $formulario->createView(), 'error' => null, 'pps' => $productos['productos'], 'id' => $np->getId());
                return $this->render('SisGGFinalBundle:NotaPedido:editarNotaPedido.html.twig', $parameters);
            } else {
                $this->getRequest()->getSession()->set('error', 'No se puede editar esta nota pedido, ya que no se encuentra en edición');
                return $this->redirect($this->generateUrl('notas'));
            }
        }
    }

//    /**
//     * @Pdf()
//     */
//    public function notasAction()
//    {
//        if ($this->getRequest()->isXmlHttpRequest()) {
//            $np = $this->getDoctrine()->getRepository("SisGGFinalBundle:NotaPedido")->find($this->getRequest()->get('id'));
//            $id = null;
//            $id = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->findOneBy(array('notaPedido' => $np));
//            if ($id != null) {
//                $id = $id->getId();
//            }
//
//            $form = $this->createForm(new NotaPedidoType(), $np, array('disabled' => true, 'attr' => array('clase' => 0)));
//            return $this->render('SisGGFinalBundle:NotaPedido:detalles.html.twig', array('form' => $form->createView(), 'id' => $id, 'fecha' => $np->getFecha(), 'id' => $np->getId(), 'items' => $np->getItem()));
//        } else {
//            $gestor = $this->get("gestor_autenticacion");
//            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::NOTAPEDIDO_LISTAR)) {
//                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//            }
//            $nps = $this->getDoctrine()->getRepository('SisGGFinalBundle:NotaPedido')->findAll();
//            $msj = $this->getRequest()->getSession()->get('msjNP');
//            $error = $this->getRequest()->getSession()->get('error');
//            $this->getRequest()->getSession()->remove('msjNP');
//            $this->getRequest()->getSession()->remove('error');
//            $parameters = array('msj' => $msj, 'error' => $error, 'registros' => $nps, 'form' => null);
//            return $this->render('SisGGFinalBundle:NotaPedido:notas.html.twig', $parameters);
//        }
//        $parameters = array('msj' => $msj, 'error' => $error, 'registros' => $nps, 'form' => null);
//        return $this->render('SisGGFinalBundle:NotaPedido:notas.html.twig', $parameters);
//    }

    public function cargarMMPAction()
    {
        // if ($this->getRequest()->isXmlHttpRequest()) {
        $productos = $this->obtenerProducto();
        if ($this->getRequest()->get('tipo') == null) {
            return $this->render('SisGGFinalBundle:NotaPedido:cargar.html.twig', array('form' => null, 'ps' => $productos['productos'], 'pr' => $productos['productos'][0]->getid()));
        } else {
            return $this->render('SisGGFinalBundle:Compra:cargarTablaProductos.html.twig', array('form' => null, 'pps' => $productos['productos']));
        }

        // }
    }

    public function buscarNotasAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $empresa = $this->getEmpresa();

            $array = null;
            if ($tipo == -1) {
                $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:NotaPedido')->findAll();
            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $empresa->buscarNota($tiempo, $clase);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarNotaEntreFechas($fecha1, $fecha2);

                //$array=$empresa->buscarNotaEntreFechas(new \DateTime('2013/04/15'), new \DateTime('2013/04/16'));
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarNotaFecha($fecha1, $clase);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarNotaMesAño($mes, $año);
            }
            $parameters = array('registros' => $array, 'form' => null);
            return $this->render('SisGGFinalBundle:NotaPedido:cargarTabla.html.twig', $parameters);
        }
    }

    private function obtenerProducto()
    {
        $productos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Producto')->findBy(array(), array('nombre' => 'asc'));
        foreach ($productos as $value) {
            if (($value->isMateriaPrima() || $value->isMercaderia() || $value->isMantenimiento()) && ($value->getActivo())) {
                $array[] = $value;
            }
        }
        $return = array('productos' => $array, 'tasas' => $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Tasa')->findBy(array('um' => $array[0]->getTasa()->getUm()->getId()))); //$array[0]->getTasa()->getUm()->getTasas());
        return $return;
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
//    public function impDetallesNPAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::NOTAPEDIDO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $qr = new \Endroid\QrCode\QrCode();
//        $qr->setText('la pija de don cangrejo es la mejor pija para ti y para mi, tucucucutu pija...');
//        $qr->setSize(300);
//        $qr->render('/tmp/codeqr.png');
//        $np = $this->getDoctrine()->getRepository("SisGGFinalBundle:NotaPedido")->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'np' => $np, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:NotaPedido:imp_detalles.' . $format . '.twig', $parameters);
//    }

}

?>