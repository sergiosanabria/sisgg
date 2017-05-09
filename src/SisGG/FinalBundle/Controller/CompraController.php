<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Compra;
use SisGG\FinalBundle\Form\CompraType;
use SisGG\FinalBundle\Entity\ItemCompra;
use SisGG\FinalBundle\Form\ItemCompraType;
use SisGG\FinalBundle\Form\PagoType;
use SisGG\FinalBundle\Entity\NotaPedido;
use SisGG\FinalBundle\Entity\ItemNotaPedido;
use SisGG\FinalBundle\Entity\Proveedor;
use SisGG\FinalBundle\Entity\Empresa;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of CompraController
 *
 * @author sergio
 */
class CompraController extends Controller
{

    public function tipoFacturaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_NUEVO)) {
            return $this->render('SisGGFinalBundle:Compra:modal.html.twig', array('facturas' => -1, 'id' => null));
            //throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = new Empresa();
        $empresa = $this->getEmpresa();
        $id = $this->getRequest()->get('id');
        /* @var $operacion \SisGG\FinalBundle\Entity\TipoOperacion */

        $operacion = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:TipoOperacion")->find(2);

        if ($id == null) {
            $facturas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Operacion")->findFacturasCompra($operacion, $empresa->getCondicionIva());
            return $this->render('SisGGFinalBundle:Compra:modal.html.twig', array('facturas' => $facturas, 'id' => $id));

        } else {
            /* @var $np NotaPedido */
            $np = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:NotaPedido")->find($id);
            $a = $np->getProveedor()->getCondicionIva();
            /* @var $operacion \SisGG\FinalBundle\Entity\TipoOperacion */
            $facturas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Operacion")->findOperacion($operacion, $empresa->getCondicionIva(), $a);
            return $this->render('SisGGFinalBundle:Compra:modal.html.twig', array('facturas' => $facturas, 'id' => $id, 'np' => $np));

        }

    }

    private function tipoAceptable($tipo, $idNp = null, $compra = null)
    {
        /* @var $operacion \SisGG\FinalBundle\Entity\TipoOperacion */
        $operacion = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:TipoOperacion")->find(2);
        $empresa = $this->getEmpresa();
        if ($idNp == null) {
            $facturas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Operacion")->findFacturasCompra($operacion, $empresa->getCondicionIva());
        } else {
            if ($compra != null) {
                /* @var $compra Compra */
                $a = $compra->getProveedor()->getCondicionIva();
                /* @var $operacion \SisGG\FinalBundle\Entity\TipoOperacion */
                $facturas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Operacion")->findOperacion($operacion, $empresa->getCondicionIva(), $a);
            } else {
                /* @var $np NotaPedido */
                $np = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:NotaPedido")->find($idNp);
                $a = $np->getProveedor()->getCondicionIva();
                /* @var $operacion \SisGG\FinalBundle\Entity\TipoOperacion */
                $facturas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Operacion")->findOperacion($operacion, $empresa->getCondicionIva(), $a);
            }
        }

        if ($facturas != null) {
            foreach ($facturas as $value) {
                /* @var $value \SisGG\FinalBundle\Entity\Operacion */
                if ($value->getTipoFactura()->getNomenclatura() == $tipo) {
                    return true;
                }
            }
        }
        return false;
    }

    public function nuevaCompraAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $productos = $this->obtenerProducto();
        $compra = new Compra();
        //$compra->getItem()->add(new ItemCompra());
        $tipo = $this->getRequest()->get('tipo');
        $empresa = new Empresa();
        $empresa = $this->getEmpresa();


        $id = $this->getRequest()->get('np');
        if ($id != null) {
            $np = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:NotaPedido")->find($id);
            if ($np->getEstado() == 3) {
//                if ((($tipo == 'A' || $tipo == 'B' ) && $np->getProveedor()->getResponsable() == false)) {
//                    $this->getRequest()->getSession()->set('errorProv', 'Para poder realizar factura ' . $tipo . ', el proveedor debe cambiar su condición de IVA a Responsable Inscripto.');
//                    $this->getRequest()->getSession()->set('NPyPro1', $id);
//                    $this->getRequest()->getSession()->set('tipoNPSes', $tipo);
//                    return $this->redirect($this->generateUrl('editarProveedor', array('id' => $np->getProveedor()->getId())));
//                }if ((($tipo == 'C' ) && $np->getProveedor()->getResponsable() == true)) {
//                    $this->getRequest()->getSession()->set('errorProv', 'Para poder realizar factura ' . $tipo . ', el proveedor debe cambiar su condición de IVA a Monotributista.');
//                    $this->getRequest()->getSession()->set('NPyPro1', $id);
//                    $this->getRequest()->getSession()->set('tipoNPSes', $tipo);
//                    return $this->redirect($this->generateUrl('editarProveedor', array('id' => $np->getProveedor()->getId())));
//                }
                if (!($this->tipoAceptable($tipo, $id))) {
                    $this->getRequest()->getSession()->set('error', 'El tipo de factura es incorrecto.');
                    return $this->redirect($this->generateUrl('compras'));
                }
                $compra->setTipo($tipo);
                $compra->setProveedor($np->getProveedor());
                $compra->setTotal($np->getTotal());
                foreach ($np->getItem() as $value) {
                    $item = new ItemCompra();
                    $item->setCantidad($value->getCantidad());
                    $item->setCompra($compra);
                    $item->setPrecioCosto($value->getPrecioCosto());
                    $item->setIva($value->getProducto()->getIVA());
                    $item->setProducto($value->getProducto());
                    $item->setTasa($value->getTasa());
                    $item->setTotal($value->getPrecioCosto() * $value->getCantidad());
                    $compra->addItem($item);
                }
            } else {
                $this->getRequest()->getSession()->set('error', 'La nota de pedido no está permitada para ser facturada.');
                return $this->redirect($this->generateUrl('notas'));
            }
        } else {
            if (!($this->tipoAceptable($tipo))) {
                $this->getRequest()->getSession()->set('error', 'El tipo de factura es incorrecto.');
                return $this->redirect($this->generateUrl('compras'));
            }
            $compra->setTipo($tipo);
        }
        $ivas = null;
        if ($tipo == 'A') {
            $ivas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:IVA")->findBy(array('activo' => 1));
        }

        $formulario = $this->createForm(new CompraType(), $compra, array('attr' => array('clase' => 1, 'tipo' => $tipo)));
        $parameters = array('form' => $formulario->createView(), 'ivas' => $ivas, 'np' => $id, 'error' => null, 'pps' => $productos['productos']);
        return $this->render('SisGGFinalBundle:Compra:nuevaCompra' . $tipo . '.html.twig', $parameters);
    }

    public function altaCompraAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $productos = $this->obtenerProducto();
        $empresa = $this->getEmpresa();
        $compra = new Compra();
        $a = $this->createForm(new CompraType(), $compra, array('attr' => array('clase' => 1, 'tipo' => 'C')));
        $a->bindRequest($this->getRequest());
        $formulario = $this->createForm(new CompraType(), $compra, array('attr' => array('clase' => 1, 'tipo' => $compra->getTipo())));
        $formulario->bindRequest($this->getRequest());
        $id = $this->getRequest()->get('np');
        if ($id != null) {
            $np = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:NotaPedido")->find($id);
            if ($np->getEstado() == 3) {
                $np->setEstado(4);
                $compra->setNotaPedido($np);
            } else {
                $this->getRequest()->getSession()->set('error', 'La nota de pedido no está permitada para ser facturada.');
                return $this->redirect($this->generateUrl('notas'));
            }
        }
        $ivas = null;
        if ($compra->getTipo() == 'A') {
            $ivas = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:IVA")->findBy(array('activo' => 1));
        }
        if ($formulario->isValid()) {
            if (!($this->tipoAceptable($compra->getTipo(), -1, $compra))) {
                $this->getRequest()->getSession()->set('error', 'El tipo de factura es incorrecto.');
                return $this->redirect($this->generateUrl('compras'));
            }
            $r = $empresa->altaCompra($compra, $this);
            if ($r == null) {
                $msj = "La compra ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjCom', $msj);
                return $this->redirect($this->generateUrl('compras'));
            } else {
                $parameters = array('form' => $formulario->createView(), 'ivas' => $ivas, 'np' => $id, 'error' => $r, 'pps' => $productos['productos']);
                return $this->render('SisGGFinalBundle:Compra:nuevaCompra' . $compra->getTipo() . '.html.twig', $parameters);
            }
        } else {

            $parameters = array('form' => $formulario->createView(), 'ivas' => $ivas, 'error' => "Verifique que los campos ingresados sean corretos.", 'np' => $id, 'pps' => $productos['productos']);
            return $this->render('SisGGFinalBundle:Compra:nuevaCompra' . $compra->getTipo() . '.html.twig', $parameters);
        }
    }

    public function editarPagoCompraAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_PAGO_POR_FACTURA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $c = new Compra ();
        $compra = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->find($this->getRequest()->get('id'));
        $libro = $this->getDoctrine()->getRepository("SisGGFinalBundle:LibroIvaCompra")->findOneBy(array('compra' => $this->getRequest()->get('id')));
        if ($compra->getEstado()) {
            $this->getRequest()->getSession()->set('error', 'Esta factura ya se encuentra totalmente paga.');
            return $this->redirect($this->generateUrl('compras'));
        }

        $formulario = $this->createForm(new CompraType(), $c, array('attr' => array('clase' => 2)));
        $parameters = array('compra' => $compra, 'libro' => $libro, 'error' => NULL, 'id' => $compra->getId(), 'form' => $formulario->createView(), 'pagos' => $compra->getPagos(), 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
    }

    public function registrarPagoCompraAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_PAGO_POR_FACTURA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $c = new Compra ();
        $formulario = $this->createForm(new CompraType(), $c, array('attr' => array('clase' => 2)));
        $formulario->bindRequest($this->getRequest());
        $id = $this->getRequest()->get('id');
        $array = null;
        if ($id != null) {
            $libro = $this->getDoctrine()->getRepository("SisGGFinalBundle:LibroIvaCompra")->findOneBy(array('compra' => $this->getRequest()->get('id')));
            $compra = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->find($id);
            if ($compra->getPagos() != null) {
                foreach ($compra->getPagos() as $value) {
                    $array [] = $value;
                }
            }
            if ($compra->getEstado()) {
                $this->getRequest()->getSession()->set('error', 'Esta factura ya se encuentra totalmente paga.');
                return $this->redirect($this->generateUrl('compras'));
            }
            if (count($c->getPagos()) > 0) {
                foreach ($c->getPagos() as $value) {
                    if (is_a($value->getFecha(), 'DateTime')) {
                        $date = date_format($value->getFecha(), 'd/m/Y');
                        $partes = explode("/", $date);
                        $value->setFechaEmision();
                        if (!(checkdate($partes[1], $partes[0], $partes[2]))) {
                            $parameters = array('compra' => $compra, 'id' => $compra->getId(), 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
                            return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
                        }
                    } else {
                        $parameters = array('compra' => $compra, 'libro' => $libro, 'id' => $compra->getId(), 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
                        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
                    }
                    if (!(is_numeric($value->getImporte()))) {
                        $parameters = array('compra' => $compra, 'libro' => $libro, 'id' => $compra->getId(), 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
                        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
                    }
                    $compra->addPago($value);
                    $value->setCompra($compra);
                }
            } else {
                $parameters = array('compra' => $compra, 'libro' => $libro, 'id' => $compra->getId(), 'error' => 'Debe ingresar al menos un pago para poder registrarlo, sino cancele la operación.', 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
                return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
            }
        } else {
            $this->getRequest()->getSession()->set('error', 'Error al ingresar los datos.');
            return $this->redirect($this->generateUrl('compras'));
        }

        // if ($formulario->isValid()) {
        $r = $empresa->registrarPagoCompra($compra, $c, $this);
        if ($r == null) {
            $msj = "Los pagos han sido exitosamente registrados.";
            $this->getRequest()->getSession()->set('msjCom', $msj);
            return $this->redirect($this->generateUrl('compras'));
        } else {
            $parameters = array('compra' => $compra, 'libro' => $libro, 'id' => $compra->getId(), 'error' => $r, 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
            return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
        }
        // }

        $parameters = array('compra' => $compra, 'libro' => $libro, 'id' => $compra->getId(), 'error' => 'Verifique que los datos ingresados sean correctos.', 'form' => $formulario->createView(), 'pagos' => $array, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompra.html.twig', $parameters);
    }

    public function editarPagoCompraProvAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_PAGO_POR_PROVEEDOR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $c = new Compra ();
        $proveedor = $this->getDoctrine()->getRepository("SisGGFinalBundle:Proveedor")->find($this->getRequest()->get('id'));
        if ($proveedor == null) {
            $this->getRequest()->getSession()->set('error', 'Error en selección del proveedor.');
            return $this->redirect($this->generateUrl('compras'));
        }
        $registros = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->findBy(array('proveedor' => $this->getRequest()->get('id'), 'estado' => 0));
        $formulario = $this->createForm(new CompraType(), $c, array('attr' => array('clase' => 2)));
        $parameters = array('proveedor' => $proveedor, 'registros' => $registros, 'ids' => null, 'tipo' => 0, 'error' => NULL, 'id' => $this->getRequest()->get('id'), 'form' => $formulario->createView());
        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
    }

    public function registrarPagoCompraProvAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_PAGO_POR_PROVEEDOR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $c = new Compra ();
        $formulario = $this->createForm(new CompraType(), $c, array('attr' => array('clase' => 2)));
        $formulario->bindRequest($this->getRequest());
        $id = $this->getRequest()->get('id');
        $tipo = $this->getRequest()->get('tipo');
        $array = null;
        if ($tipo == 1) {
            $lista = explode(",", $this->getRequest()->get('ids'));
            foreach ($lista as $iden) {
                if ($iden != null)
                    $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->find($iden);
            }
        }
        if ($id != null) {
            $proveedor = $this->getDoctrine()->getRepository("SisGGFinalBundle:Proveedor")->find($this->getRequest()->get('id'));
            if ($proveedor == null) {
                $this->getRequest()->getSession()->set('error', 'Error en selección del proveedor.');
                return $this->redirect($this->generateUrl('compras'));
            }
            $registros = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->findBy(array('proveedor' => $id, 'estado' => 0));
            if (count($c->getPagos()) > 0) {
                foreach ($c->getPagos() as $value) {
                    if (is_a($value->getFecha(), 'DateTime')) {
                        $date = date_format($value->getFecha(), 'd/m/Y');
                        $partes = explode("/", $date);
                        $value->setFechaEmision();
                        if (!(checkdate($partes[1], $partes[0], $partes[2]))) {
                            $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView());
                            return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
                        }
                    } else {
                        $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView());
                        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
                    }
                    if (!(is_numeric($value->getImporte()))) {
                        $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => 'Error en el formato de la fecha.', 'form' => $formulario->createView());
                        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
                    }
                }
            } else {
                $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => 'Debe ingresar al menos un pago para poder registrarlo, sino cancele la operación.', 'form' => $formulario->createView());
                return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
            }
        } else {
            $this->getRequest()->getSession()->set('error', 'Error al ingresar los datos.');
            return $this->redirect($this->generateUrl('compras'));
        }
        // if ($formulario->isValid()) {
        $r = null;
        if ($tipo == 1) {
            $r = $empresa->registrarPagoCompraProv($c, $array, $this);
        } elseif ($tipo == 2) {
            $r = $empresa->registrarPagoCompraProv($c, $registros, $this);
        }

        if ($r == null) {
            $msj = "Los pagos han sido exitosamente registrados.";
            $this->getRequest()->getSession()->set('msjCom', $msj);
            return $this->redirect($this->generateUrl('compras'));
        } else {
            $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => $r, 'form' => $formulario->createView());
            return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
        }
        // }

        $parameters = array('proveedor' => $proveedor, 'ids' => $array, 'tipo' => $tipo, 'registros' => $registros, 'id' => $id, 'error' => 'Verifique que los datos ingresados sean correctos.', 'form' => $formulario->createView());
        return $this->render('SisGGFinalBundle:Compra:registrarPagoCompraProv.html.twig', $parameters);
    }

    public function comprasAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $compra = $this->getDoctrine()->getRepository("SisGGFinalBundle:Compra")->find($this->getRequest()->get('id'));
            $libro = $this->getDoctrine()->getRepository("SisGGFinalBundle:LibroIvaCompra")->findOneBy(array('compra' => $this->getRequest()->get('id')));
            $parameters = array('compra' => $compra, 'id' => $compra->getId(), 'form' => null, 'pagos' => $compra->getPagos(), 'libro' => $libro, 'items' => $compra->getItem(), 'totalItem' => $compra->getTotal());
            return $this->render('SisGGFinalBundle:Compra:detalles.html.twig', $parameters);
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $total = 0;
            $empresa = $this->getEmpresa();
            $compras = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->findAll();
            $msj = $this->getRequest()->getSession()->get('msjCom');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjCom');
            $this->getRequest()->getSession()->remove('error');
            // 'fechas' => $empresa->agruparCompras(), 
            $parameters = array('msj' => $msj, 'error' => $error, 'registros' => $compras, 'total' => $total, 'form' => null);
            return $this->render('SisGGFinalBundle:Compra:compras.html.twig', $parameters);
        }
    }

    public function buscarComprasAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $empresa = new \SisGG\FinalBundle\Entity\Empresa();
            $empresa = $this->getEmpresa();
            $array = null;
            $total = 0.00;
            if ($tipo == -1) {
                $array = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->findAll();
                foreach ($array as $value) {
                    $total += $value->getTotal();
                }
            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $empresa->buscarCompra($tiempo, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarCompraEntreFechas($fecha1, $fecha2);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $empresa->buscarCompraFecha($fecha1, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $empresa->buscarCompraMesAño($mes, $año);
                if ($array != null)
                    $total = array_pop($array);
            }
            $parameters = array('registros' => $array, 'form' => null, 'total' => $total);
            return $this->render('SisGGFinalBundle:Compra:cargarTabla.html.twig', $parameters);
        }
    }

    private function obtenerProducto()
    {
        $productos = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Producto')->findAll();
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
//    public function impListaComAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::COMPRA_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        if ($this->getRequest()->getMethod() === 'POST') {
//            $lista = explode(",", $this->getRequest()->get('ids'));
//            $info = $this->getRequest()->get('info');
//            $array = null;
//            $url = $this->getRequest()->get('imgSRC');
//            if ($url != -1) {
//                $img = '/tmp/compra.png';
//                file_put_contents($img, file_get_contents($url));
//            } else {
//                $img = '';
//            }
//
//
//            foreach ($lista as $id) {
//                if ($id != null)
//                    $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->find($id);
//            }
//            $format = $this->get('request')->get('_format');
//            $parameters = array('form' => null, 'items' => $array, 'img' => $img, 'info' => $info, 'empresa' => $this->getEmpresa());
//            return $this->render('SisGGFinalBundle:Compra:imp_lista.' . $format . '.twig', $parameters);
//        }
//    }

}

?>
