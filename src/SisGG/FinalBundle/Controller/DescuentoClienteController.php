<?php

namespace SisGG\FinalBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Form\ClienteType;
use SisGG\FinalBundle\Model\GestorClientes;
use SisGG\FinalBundle\Entity\DescuentoCliente;
use SisGG\FinalBundle\Form\DescuentoClienteType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class DescuentoClienteController extends Controller {

    public function nuevoDescuentoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $descuentoCliente = new DescuentoCliente();
        $descuentoCliente->setTipoPorcentaje(true);
        $form = $this->createForm(new DescuentoClienteType(), $descuentoCliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($descuentoCliente);
                    $em->flush();
                    return $this->redirect($this->generateUrl('descuentos_clientes'));
            }
        }
        return $this->render('SisGGFinalBundle:DescuentoCliente:nuevoDescuentoCliente.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor));
    }
    
    public function editarDescuentoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unDescuentoCliente = $em->getRepository("SisGGFinalBundle:DescuentoCliente")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new DescuentoClienteType(), $unDescuentoCliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('descuentos_clientes'));
            }
        }
        return $this->render('SisGGFinalBundle:DescuentoCliente:editarDescuentoCliente.html.twig', array('form' => $form->createView(),'id'=>$unDescuentoCliente->getId(),'gestor_autenticacion'=>$gestor));
    }

    public function borrarDescuentoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoCliente');
        $descuentoCliente = $repositorio->find($this->getRequest()->get('id'));
        $descuentoCliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($descuentoCliente);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('descuentos_clientes',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('descuentos_clientes'));
    }
    
    public function activarDescuentoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoCliente');
        $descuentoCliente = $repositorio->find($this->getRequest()->get('id'));
        $descuentoCliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($descuentoCliente);
        if ($this->getRequest()->get('estado')!=null)
            return $this->redirect($this->generateUrl('descuentos_clientes',array('estado'=>  $this->getRequest()->get('estado'))));
        return $this->redirect($this->generateUrl('descuentos_clientes'));
    }
    
    
    public function descuentosClientesAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:DescuentoCliente');
        $descuentosClientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:DescuentoCliente:descuentosClientes.html.twig', array('form' => null, 'descuentosClientes' => $descuentosClientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function descuentosClienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Cliente');
        /* @var $unCliente Cliente*/
        $unCliente = $repositorio->find($this->getRequest()->get('id'));
        $descuentos = array();
        /* @var $descuento DescuentoCliente*/
        foreach ($unCliente->getDescuentosActivos() as $descuento) {
            $descuentos[ ] = array("id"=>$descuento->getId(),"nombre"=>$descuento->getNombre(),"tipoImporte"=>$descuento->getTipoImporte()?"Importe":"Porcentual","importe"=>$descuento->getImporte(),"porcentaje"=>$descuento->getPorcentaje(),"montoMinimo"=>$descuento->getMinimo(),"montoMaximo"=>$descuento->getMaximo());
        }
        return new Response(json_encode($descuentos));
    }

}