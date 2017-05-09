<?php

namespace SisGG\FinalBundle\Controller;

//use Ps\PdfBundle\Annotation\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Plato;
use SisGG\FinalBundle\Entity\Image;
use SisGG\FinalBundle\Form\ImageType;
use SisGG\FinalBundle\Form\PlatoType;

/**
 * Description of PlatoController
 *
 * @author sergio
 */
class PlatoController extends Controller
{

    public function nuevoPlatoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new PlatoType());
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $parameters = array('form' => $formulario->createView(), 'imagen' => $imgen->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:Plato:nuevoPlato.html.twig', $parameters);
    }

    public function altaPlatoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_NUEVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $plato = new Plato();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new PlatoType(), $plato);
        $newfondo = new Image();
        $imgen = $this->createForm(new ImageType(), $newfondo);
        $imgen->bindRequest($this->getRequest());
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            if ($plato->getMargenMin() == null)
                $plato->setMargenMin(0.00);
            if ($plato->getMargen() == null)
                $plato->setMargen(0.00);
            if (!($plato->getMargenMin() > $plato->getMargen())) {
                $r = $empresa->altaPlato($plato, $this);
                if ($r == null) {
                    $msj = "El plato " . $plato->getNombre() . " ha sido exitosamente registrado.";
                    //Imagen
                    $id = $plato->getFoto()->getId();
                    $em->persist($newfondo);
                    $plato->setFoto($newfondo);
                    $em->flush();
                    $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                    $em->remove($foto);
                    $em->flush();
                    //
                    $this->getRequest()->getSession()->set('msjPla', $msj);
                    $this->getRequest()->getSession()->set('idPla', $plato->getId());
                    return $this->redirect($this->generateUrl('platos', array('msj' => null, 'error' => null)));
                } else {
                    return $this->render('SisGGFinalBundle:Plato:nuevoPlato.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => $r));
                }
            } else {
                return $this->render('SisGGFinalBundle:Plato:nuevoPlato.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => 'El margen mínimo no debe ser mayor que margen de ganancia.'));
            }
        }
        return $this->render('SisGGFinalBundle:Plato:nuevoPlato.html.twig', array('imagen' => $imgen->createView(), 'form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos." . $plato->getMargen()));
    }

    public function editarPlatoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Plato');
        $plato = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($plato->getFoto() != null) {
            $id = $plato->getFoto()->getId();
        }
        if ($id != null) {
            //ver si funciona con el get de la foto
            $newfondo = $plato->getFoto();
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $newfondo->getPath();
        } else {
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }
        $formulario = $this->createForm(new PlatoType(), $plato);
        $parameters = array('form' => $formulario->createView(), 'error' => null, 'imagen' => $imgen->createView(), 'id' => $plato->getId(), 'ingredientes' => $plato->getIngredientes(), 'nombre' => $plato->getNombre(), 'pathFoto' => $path);
        return $this->render('SisGGFinalBundle:Plato:editarPlato.html.twig', $parameters);
    }

    public function modificarPlatoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Plato');
        $plato = $repositorio->find($this->getRequest()->get('id'));
        //imagen
        $tipo = 5;
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($plato->getFoto() != null) {
            $id = $plato->getFoto()->getId();
        }
        if ($id != null) {
            // $newfondo = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $plato->getFoto()->getPath();
            if ($this->getRequest()->get('tipo') != null) {
                $plato->setFoto(null);
                $tipo = 1;
            }
        } else {
            $tipo = 0;
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }

        $imgen->bindRequest($this->getRequest());


        $formulario = $this->createForm(new PlatoType(), $plato);
        $formulario->bindRequest($this->getRequest());

        if ($formulario->isValid()) {
            if ($plato->getMargenMin() == null)
                $plato->setMargenMin(0.00);
            if ($plato->getMargen() == null)
                $plato->setMargen(0.00);
            if (!($plato->getMargenMin() > $plato->getMargen())) {
                $r = $empresa->modificarPlato($plato, $this, true);
                if ($r == null) {
                    $msj = "El plato " . $plato->getNombre() . " ha sido exitosamente modificada.";
                    if ($tipo == 1) {

                        $idNull = $plato->getFoto()->getId();

                        $em->persist($newfondo);
                        $plato->setFoto($newfondo);

                        $fotonull = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($idNull);
                        $fotonull->removeUpload();
                        $em->remove($fotonull);


                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    } elseif ($tipo == 0) {
                        $id = $plato->getFoto()->getId();
                        $em->persist($newfondo);
                        $plato->setFoto($newfondo);
                        $em->flush();
                        $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                        $foto->removeUpload();
                        $em->remove($foto);
                        $em->flush();
                    }
                    $this->getRequest()->getSession()->set('msjPla', $msj);
                    return $this->redirect($this->generateUrl('platos'));
                } else {
                    $parameters = array('form' => $formulario->createView(), 'ingredientes' => $plato->getIngredientes(), 'pathFoto' => $path, 'error' => $r, 'imagen' => $imgen->createView(), 'id' => $plato->getId(), 'nombre' => $plato->getNombre());
                    return $this->render('SisGGFinalBundle:Plato:editarPlato.html.twig', $parameters);
                }
            } else {
                $parameters = array('form' => $formulario->createView(), 'ingredientes' => $plato->getIngredientes(), 'pathFoto' => $path, 'error' => 'El margen minimo es mayor al mergen de ganancia.', 'imagen' => $imgen->createView(), 'id' => $plato->getId(), 'nombre' => $plato->getNombre());
                return $this->render('SisGGFinalBundle:Plato:editarPlato.html.twig', $parameters);
            }
        }
        $parameters = array('form' => $formulario->createView(), 'ingredientes' => $plato->getIngredientes(), 'id' => $plato->getId(), 'pathFoto' => $path, 'imagen' => $imgen->createView(), 'nombre' => $plato->getNombre(), 'error' => "Verifique que los campos ingresados sean correctos.");
        return $this->render('SisGGFinalBundle:Plato:editarPlato.html.twig', $parameters);
    }

    public function actualizarPCPlatoAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Plato');
        /*@var $plato Plato*/
        $plato = $repositorio->find($this->getRequest()->get('id'));
        $plato->actualizarIngredientes();
        return new Response(json_encode(array('precio' => $plato->getPrecioCosto())));
    }

    public function eliminarPlatoAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_BORRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Plato');
        $plato = $repositorio->find($this->getRequest()->get('id'));
        $msj = $empresa->eliminarPlato($plato, $this);
        if ($this->getRequest()->get('tipo') == null) {
            $this->getRequest()->getSession()->set('msjPla', $msj);
            return $this->redirect($this->generateUrl('platos'));
        } else {
            $this->getRequest()->getSession()->set('msjPV', $msj);
            return $this->redirect($this->generateUrl('pvs'));
        }
    }

    public function platosAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $plato = $this->getDoctrine()->getRepository("SisGGFinalBundle:Plato")->find($this->getRequest()->get('id'));
            $form = $this->createForm(new PlatoType(), $plato, array('disabled' => true));
            $path = null;
            if ($plato->getFoto() != null) {
                $path = $plato->getFoto()->getPath();
            }
            return $this->render('SisGGFinalBundle:Plato:detalles.html.twig', array('form' => $form->createView(), 'plato' => $plato, 'path' => $path, 'ingredientes' => $plato->getIngredientes()));
        } else {
            $gestor = $this->get("gestor_autenticacion");
            if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PLATO_LISTAR)) {
                throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
            }
            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:Plato');
            $platos = null;
            $cantidad = 0;
            if ($estado == 1) {
                $platos = $repositorio->findBy(array('activo' => false));
            } else {
                $platos = $repositorio->findBy(array('activo' => true));
                $inactivos = $repositorio->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $this->getRequest()->getSession()->get('msjPla');
            $id = $this->getRequest()->getSession()->get('idPla');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('msjPla');
            $this->getRequest()->getSession()->remove('idPla');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('msj' => $msj, 'error' => $error, 'platos' => $platos, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'idPla' => $id);
            return $this->render('SisGGFinalBundle:Plato:platos.html.twig', $parameters);
        }
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

    public function trasformarDecimales($num)
    {
        return str_replace(".", ",", $num);
    }

    //Imprimir

//    /**
//     * @Pdf()
//     */
//    public function impDetallesPlatoAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::PRODUCTO_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $producto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Producto')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'tipo' => $this->getRequest()->get('tipo'), 'producto' => $producto, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Plato:imp_detalles.' . $format . '.twig', $parameters);
//    }

}
