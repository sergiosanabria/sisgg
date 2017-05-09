<?php

namespace SisGG\FinalBundle\Controller;

use SisGG\FinalBundle\Entity\Telefono;;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Form\ClienteType;
use SisGG\FinalBundle\Model\GestorClientes;
use SisGG\FinalBundle\Entity\DescuentoCliente;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of ClienteController
 *
 * @author martin
 */
class ClienteController extends Controller {

    public function nuevoClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $cliente = new Cliente();
        $form = $this->createForm(new ClienteType(), $cliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($cliente);
                    $em->flush();
                    return $this->redirect($this->generateUrl('clientes'));
            }
        }
        $provincias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        return $this->render('SisGGFinalBundle:Cliente:nuevoCliente.html.twig', array('form' => $form->createView(),'gestor_autenticacion'=>$gestor,'provincias'=>$provincias));
    }
    
    public function detallesAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $id = $this->getRequest()->get('id');
        $unCliente = $em->getRepository("SisGGFinalBundle:Cliente")->find($id);
        return $this->render('SisGGFinalBundle:Cliente:detallesCliente.html.twig', array('form' => null,'id'=>$id,'cliente'=>$unCliente,'gestor_autenticacion'=>$gestor));
    }

    public function clienteAction() {
         /*@var $unCliente Cliente*/
         $unCliente = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Cliente")->find($this->getRequest()->get("id"));
         $descuentos = array();
         /*@var $descuento DescuentoCliente*/
         foreach ($unCliente->getDescuentosActivos() as $descuento) {
             $descuentos[]=array("id"=>$descuento->getId(),"nombre"=>$descuento->getNombre(),"isPorcentaje"=>$descuento->getTipoPorcentaje(),"porcentaje"=>$descuento->getPorcentaje(),"importe"=>$descuento->getImporte(),"minimo"=>$descuento->getMinimo(),"maximo"=>$descuento->getMaximo());
         }
         $cliente = array("descuentos"=>$descuentos);
         return new Response(json_encode($cliente));
    }
    
    public function editarClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $unCliente = $em->getRepository("SisGGFinalBundle:Cliente")->find($this->getRequest()->get("id"));
        $form = $this->createForm(new ClienteType(), $unCliente);
        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('clientes'));
            }
        }
        $provincias = $this->getDoctrine()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        return $this->render('SisGGFinalBundle:Cliente:editarCliente.html.twig', array('form' => $form->createView(),'id'=>$this->getRequest()->get("id"),'gestor_autenticacion'=>$gestor,'provincias'=>$provincias));
    }

    public function borrarClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Cliente');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('inactivo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("clientes"));
    }
    
    public function activarClienteAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Cliente');
        $cliente = $repositorio->find($this->getRequest()->get('id'));
        $cliente->setEstado('activo');
        $this->getDoctrine()->getEntityManager()->flush($cliente);
        return $this->redirect($this->generateUrl("clientes"));
    }
    
    
    public function clientesAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Cliente');
        $clientes = $repositorio->findAll();
        $estado = $this->getRequest()->get('estado');
        return $this->render('SisGGFinalBundle:Cliente:clientes.html.twig', array('form' => null, 'clientes' => $clientes,'gestor_autenticacion'=>$gestor,'estado'=>$estado));
    }
    
    public function pordefectoClienteAction(){
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Cliente');
        $clientes = $repositorio->findAll();
        /*@var $cliente Cliente*/
        foreach ($clientes as $cliente) {
            $cliente->setPorDefecto($cliente->getId()==$this->getRequest()->get('id')?true:false);
        }
        $this->getDoctrine()->getEntityManager()->flush();
        return new Response(json_encode(array()));
    }

}