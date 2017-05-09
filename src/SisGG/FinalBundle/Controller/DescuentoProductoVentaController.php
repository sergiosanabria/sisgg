<?php

namespace SisGG\FinalBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\CategoriaProductoVenta;
use SisGG\FinalBundle\Entity\ProductoVenta;
use SisGG\FinalBundle\Form\ProductoVentaType;
use SisGG\FinalBundle\Entity\DescuentoProductoVenta;
use SisGG\FinalBundle\Form\DescuentoProductoVentaType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ProductoVentaController
 *
 * @author martin
 */
class DescuentoProductoVentaController extends Controller {

    public function nuevoDescuentoProductoVentaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $descuentoProductoVenta = new DescuentoProductoVenta();
        $form = $this->createForm(new DescuentoProductoVentaType(), $descuentoProductoVenta);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($descuentoProductoVenta);
                    $em->flush();
                    return $this->redirect($this->generateUrl('descuentos_producto_ventas'));
            }
        }
        return $this->render('SisGGFinalBundle:DescuentoProductoVenta:nuevoDescuentoProductoVenta.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
    
    public function editarDescuentoProductoVentaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unDescuentoProductoVenta = $em->getRepository("SisGGFinalBundle:DescuentoProductoVenta")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new DescuentoProductoVentaType(), $unDescuentoProductoVenta);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('descuentos_producto_ventas'));
            }
        }
        return $this->render('SisGGFinalBundle:DescuentoProductoVenta:editarDescuentoProductoVenta.html.twig', array('form' => $form->createView(),'id'=>$unDescuentoProductoVenta->getId(),'gestor_autenticacion'=>$gestor));
    }

    public function borrarDescuentoProductoVentaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoProductoVenta');
        $descuentoProductoVenta = $repositorio->find($this->getRequest()->get('id'));
        $descuentoProductoVenta->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($descuentoProductoVenta);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('descuentos_producto_ventas',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('descuentos_producto_ventas'));
    }
    
    public function activarDescuentoProductoVentaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoProductoVenta');
        $descuentoProductoVenta = $repositorio->find($this->getRequest()->get('id'));
        $descuentoProductoVenta->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($descuentoProductoVenta);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('descuentos_producto_ventas',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('descuentos_producto_ventas'));
    }
    
    
    public function descuentosProductoVentasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoProductoVenta');
        $descuentosProductoVentas = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:DescuentoProductoVenta:descuentosProductoVentas.html.twig', array('form' => null, 'descuentosProductoVentas' => $descuentosProductoVentas,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function descuentosProductoVentaAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:ProductoVenta');
        /* @var $unProductoVenta ProductoVenta*/
        $unProductoVenta = $repositorio->find($this->getRequest()->get('id'));
        $descuentos = array();
        /* @var $descuento DescuentoProductoVenta*/
        foreach ($unProductoVenta->getDescuentosActivos() as $descuento) {
            $descuentos[ ] = array("id"=>$descuento->getId(),"nombre"=>$descuento->getNombre(),"tipoImporte"=>$descuento->getTipoImporte()?"Importe":"Porcentual","importe"=>$descuento->getImporte(),"porcentaje"=>$descuento->getPorcentaje(),"montoMinimo"=>$descuento->getMinimo(),"montoMaximo"=>$descuento->getMaximo());
        }
        return new Response(json_encode($descuentos));
    }

}