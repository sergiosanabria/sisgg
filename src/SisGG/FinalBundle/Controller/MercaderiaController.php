<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\MercaderiaType;
use SisGG\FinalBundle\Entity\Mercaderia;
use SisGG\FinalBundle\Entity\Image;
use SisGG\FinalBundle\Form\ImageType;
//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of MercaderiaController
 *
 * @author sergio
 */
class MercaderiaController extends Controller {

    public function nuevaMercaderiaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new MercaderiaType());
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $parameters = array('form' => $formulario->createView(), 'imagen' => $imgen->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:Mercaderia:nuevaMercaderia.html.twig', $parameters);
    }

    public function altaMercaderiaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $mercaderia = new Mercaderia();
        $empresa = $this->getEmpresa();
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $imgen->bindRequest($this->getRequest());
        $formulario = $this->createForm(new MercaderiaType(), $mercaderia);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            if ($mercaderia->getMargenMin() == null)
                $mercaderia->setMargenMin(0.00);
            if ($mercaderia->getMargen() == null)
                $mercaderia->setMargen(0.00);
            if (!($mercaderia->getMargenMin() > $mercaderia->getMargen())) {
                $r = $empresa->altaMercaderia($mercaderia, $this);
                if ($r == null) {
                    $msj = "La mercaderia " . $mercaderia->getNombre() . " ha sido exitosamente registrada.";
                    //Imagen
                    $id = $mercaderia->getFoto()->getId();
                    $em->persist($newfondo);
                    $mercaderia->setFoto($newfondo);
                    $em->flush();
                    $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                    $em->remove($foto);
                    $em->flush();
                    //
                    $this->getRequest()->getSession()->set('msjMer', $msj);
                    return $this->redirect($this->generateUrl('mercaderias', array('msj' => null, 'error' => null)));
                } else {
                    return $this->render('SisGGFinalBundle:Mercaderia:nuevaMercaderia.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Mercaderia:nuevaMercaderia.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => 'El margen mínimo no debe ser mayor que margen de ganancia.'));
            }
        }
        return $this->render('SisGGFinalBundle:Mercaderia:nuevaMercaderia.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarMercaderiaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mercaderia');
        $mercaderia = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($mercaderia->getFoto() != null) {
            $id = $mercaderia->getFoto()->getId();
        }
        if ($id != null) {
            $newfondo = $mercaderia->getFoto();
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $newfondo->getPath();
        } else {
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }
        $formulario = $this->createForm(new MercaderiaType(), $mercaderia);
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'id' => $mercaderia->getId(), 'imagen' => $imgen->createView(), 'nombre' => $mercaderia->getNombre(), 'pathFoto' => $path);
        return $this->render('SisGGFinalBundle:Mercaderia:editarMercaderia.html.twig', $parameters);
    }

    public function modificarMercaderiaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mercaderia');
        $mercaderia = $repositorio->find($this->getRequest()->get('id'));
        //imagen
        $tipo = 5;
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($mercaderia->getFoto() != null) {
            $id = $mercaderia->getFoto()->getId();
        }
        if ($id != null) {
            // $newfondo = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $mercaderia->getFoto()->getPath();
            if ($this->getRequest()->get('tipo') != null) {
                $mercaderia->setFoto(null);
                $tipo = 1;
            }
        } else {
            $tipo = 0;
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }

        $imgen->bindRequest($this->getRequest());

        //
        $formulario = $this->createForm(new MercaderiaType(), $mercaderia);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            if ($mercaderia->getMargenMin() == null)
                $mercaderia->setMargenMin(0.00);
            if ($mercaderia->getMargen() == null)
                $mercaderia->setMargen(0.00);
            if (!($mercaderia->getMargenMin() > $mercaderia->getMargen())) {
                $r = $empresa->modificarMercaderia($mercaderia, $this);
                if ($r == null) {
                    $msj = "La mercaderia " . $mercaderia->getNombre() . " ha sido exitosamente modificada.";
                    if ($tipo == 1) {

                        $idNull = $mercaderia->getFoto()->getId();

                        $em->persist($newfondo);
                        $mercaderia->setFoto($newfondo);

                        $fotonull = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($idNull);
                        $fotonull->removeUpload();
                        $em->remove($fotonull);


                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    } elseif ($tipo == 0) {
                        $id = $mercaderia->getFoto()->getId();
                        $em->persist($newfondo);
                        $mercaderia->setFoto($newfondo);
                        $em->flush();
                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    }
                    $this->getRequest()->getSession()->set('msjMer', $msj);
                    return $this->redirect($this->generateUrl('mercaderias', array('msj' => null, 'error' => null)));
                } else {
                    $parameters = array('form' => $formulario->createView(), 'pathFoto' => $path, 'imagen' => $imgen->createView(), 'error' => $r, 'id' => $mercaderia->getId(), 'nombre' => $mercaderia->getNombre());
                    return $this->render('SisGGFinalBundle:Mercaderia:editarMercaderia.html.twig', $parameters);
                }
            } else {
                return $this->render('SisGGFinalBundle:Mercaderia:editarMercaderia.html.twig', array('pathFoto' => $path, 'id' => $mercaderia->getId(), 'imagen' => $imgen->createView(), 'nombre' => $mercaderia->getNombre(), 'form' => $formulario->createView(), 'error' => 'El margen mínimo no debe ser mayor que margen de ganancia.'));
            }
        }
        $parameters = array('form' => $formulario->createView(), 'pathFoto' => $path, 'id' => $mercaderia->getId(), 'imagen' => $imgen->createView(), 'nombre' => $mercaderia->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos.");
        return $this->render('SisGGFinalBundle:Mercaderia:editarMercaderia.html.twig', $parameters);
    }

    public function eliminarMercaderiaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Mercaderia');
        $mercaderia = $repositorio->find($this->getRequest()->get('id'));
        $msj = $empresa->eliminarMercaderia($mercaderia, $this);
        if ($this->getRequest()->get('tipo') == null) {
            $this->getRequest()->getSession()->set('msjMer', $msj);
            return $this->redirect($this->generateUrl('mercaderias'));
        } else {
            $this->getRequest()->getSession()->set('msjPV', $msj);
            return $this->redirect($this->generateUrl('pvs'));
        }
    }

    public function mercaderiasAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $mercaderia = $this->getDoctrine()->getRepository("SisGGFinalBundle:Mercaderia")->find($this->getRequest()->get('id'));
            $path = null;
            if ($mercaderia->getFoto() != null) {
                $path = $mercaderia->getFoto()->getPath();
            }
            $form = $this->createForm(new MercaderiaType(), $mercaderia, array('disabled' => true));
            return $this->render('SisGGFinalBundle:Mercaderia:detalles.html.twig', array('form' => $form->createView(), 'merca' => $mercaderia, 'path' => $path));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::MERCADERIA_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:MateriaPrima');
            $mercaderias = null;
            $cantidad = 0;
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Mercaderia');
            if ($estado == 1) {
                $mercaderias = $repositorio->findBy(array('activo' => false));
            } else {
                $mercaderias = $repositorio->findBy(array('activo' => true));
                $inactivos = $repositorio->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $this->getRequest()->getSession()->get('msjMer');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjMer');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error, 'mercaderias' => $mercaderias);
            return $this->render('SisGGFinalBundle:Mercaderia:mercaderias.html.twig', $parameters);
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    //Imprimir
//    /**
//     * @Pdf()
//     */
//    public function impDetallesMercaAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $producto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'producto' => $producto, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Mercaderia:imp_detalles.' . $format . '.twig', $parameters);
//    }
    
    
    
 

}
