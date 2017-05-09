<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\CategoriaProductoVentaType;
use SisGG\FinalBundle\Entity\CategoriaProductoVenta;
use SisGG\FinalBundle\Entity\Empresa;

/**
 * Description of CategoriaController
 *
 * @author sergio
 */
class CategoriaPVController extends Controller {

    public function nuevaCategoriaPVAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new CategoriaProductoVentaType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:CategoriaPV:nuevaCategoriaPV.html.twig', $parameters);
    }

    public function altaCategoriaPVAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $categoria = new CategoriaProductoVenta();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new CategoriaProductoVentaType(), $categoria);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->altaCategoriaPV($categoria, $this);
            if ($r == null) {
                $msj = "La Categoria " . $categoria->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjCPV', $msj);
                return $this->redirect($this->generateUrl('categoriasPV', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:CategoriaPV:nuevaCategoriaPV.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:CategoriaPV:nuevaCategoriaPV.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }

    public function editarCategoriaPVAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->get('idCat1') != null) {
            $em = $this->getDoctrine()->getEntityManager();
            $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoVenta');
            $categoria = $repositorio->findOneBy(array('nombre' => $this->getRequest()->get('idCat1')));
            $formulario = $this->createForm(new CategoriaProductoVentaType(), $categoria);
            //$this->getRequest()->getSession()->set('idCPV', $categoria->getId());
            if ($categoria->getPadre() == null)
                $parameters = array('form' => $formulario->createView(), 'error' => null,'nombre'=>$categoria->getNombre(), 'padre' => "0",'id'=>$categoria->getId());
            else {
                $parameters = array('form' => $formulario->createView(), 'error' => null,'nombre'=>$categoria->getNombre(), 'padre' => "1",'id'=>$categoria->getId());
            }
            return $this->render('SisGGFinalBundle:CategoriaPV:editarCategoriaPV.html.twig', $parameters);
        }
        $this->getRequest()->getSession()->set('errorCPV', 'Debe seleccionar una categoria para poder editarla.' . $this->getRequest()->get('nombreCat'));
        return $this->redirect('categoriasPV');
    }

    public function modificarCategoriaPVAction(Empresa $empresa = null) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoVenta');
        $categoria = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new CategoriaProductoVentaType(), $categoria);
        //$this->getRequest()->getSession()->set('idCPV', $categoria->getId());
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarCategoriaPV($categoria, $this);
            if ($r == null) {
                $msj = "La categoria " . $categoria->getNombre() . " ha sido exitosamente modificada.";
                $this->getRequest()->getSession()->set('msjCPV', $msj);
                return $this->redirect($this->generateUrl('categoriasPV', array('msj' => null, 'error' => null)));
            } else {
                if ($categoria->getPadre() == null) {
                    $parameters = array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'error' => $r, 'padre' => "0",'id'=>$categoria->getId());
                    return $this->render('SisGGFinalBundle:CategoriaPV:editarCategoriaPV.html.twig', $parameters);
                } else {
                    $parameters = array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'error' => $r, 'padre' => "1",'id'=>$categoria->getId());
                    return $this->render('SisGGFinalBundle:CategoriaPV:editarCategoriaPV.html.twig', $parameters);
                }
            }
        }
        if ($categoria->getPadre() == null) {
            return $this->render('SisGGFinalBundle:CategoriaPV:editarCategoriaPV.html.twig', array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId(), 'padre' => "0", 'error' => "Verifique que los campos ingresados sean correctos."));
        } else {
            return $this->render('SisGGFinalBundle:CategoriaPV:editarCategoriaPV.html.twig', array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId(), 'padre' => "1", 'error' => "Verifique que los campos ingresados sean correctos."));
        }
    }

    public function eliminarCategoriaPVAction(Empresa $empresa = null) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->get('idCat2') != null) {
            $empresa = $this->getEmpresa();
            $em = $this->getDoctrine()->getEntityManager();
            $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoVenta');
            $categoria = $repositorio->findOneBy(array('nombre' => $this->getRequest()->get('idCat2')));
            $r = $empresa->eliminarCategoriaPV($categoria, $this);
            if ($r == null) {
                $msj = "La categoria " . $categoria->getNombre() . " ha sido exitosamente eliminada.";
                $this->getRequest()->getSession()->set('msjCPV', $msj);
                return $this->redirect($this->generateUrl('categoriasPV', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:CategoriaPV:categoriasPV.html.twig', array('msj' => null, 'error' => $r, 'form'=>null));
            }
        }
        return $this->render('SisGGFinalBundle:CategoriaPV:categoriasPV.html.twig', array('msj' => null, 'form'=>null,'error' => 'Debe seleccionar una categoria para poder eliminarla.'));
    }

    public function categoriasPVAction() {
//        $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CategoriaProductoVenta');
//        $categorias = $repositorio->findAll();
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPV_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $msj = $this->getRequest()->getSession()->get('msjCPV');
        $error = $this->getRequest()->getSession()->get('errorCPV');
        
        $this->getRequest()->getSession()->remove('msjCPV');
        $this->getRequest()->getSession()->remove('errorCPV');
        $parameters = array('msj' => $msj,'form'=>null, 'error' => $error);
        return $this->render('SisGGFinalBundle:CategoriaPV:categoriasPV.html.twig', $parameters);
    }

    public function buscarCategoriaPV1Action() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery("SELECT  c from SisGGFinalBundle:CategoriaProductoVenta c  WHERE  c.nombre  LIKE :n and c.activo=1");
            $query->setParameters(array(
                ':n' => $this->getRequest()->get('n') . '%'
            ));
            $categoria = $query->getResult();
            //      $categoria=  $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:CategoriaProductoVenta')->findOneBy(array('nombre'=>$this->getRequest()->get('n')));

            if ($categoria != null)
                return new Response(json_encode(array('id' => $categoria[0]->getId(), 'n' => $categoria[0]->getNombre(), 'd' => $categoria[0]->getDescripcion())));
            else {
                return new Response('');
            }
        }
    }

    public function enviarCategoriasAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $categorias = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:CategoriaProductoVenta')->findBy(array('activo' => 1));
            //$categorias = $this->getEmpresa()->getCategoriasPV();
            foreach ($categorias as $o) {
                if ($o->getProductoVenta()->isEmpty()) {
                    if ($o->getPadre() == null) {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => 0, 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 1);
                    } else {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => $o->getPadre()->getId(), 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 1);
                    }
                } else {
                    if ($o->getPadre() == null) {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => 0, 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 0);
                    } else {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => $o->getPadre()->getId(), 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 0);
                    }
                }
            }

            return new Response(json_encode($arreglo));
        }
    }

    public function enviarCategoriasPVAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $categorias = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:CategoriaProductoVenta')->findBy(array('activo' => 1));
            //$categorias = $this->getEmpresa()->getCategoriasPV();
            foreach ($categorias as $o) {
                if ((!$o->getProductoVenta()->isEmpty()) || ($o->getHijo()->isEmpty())) {
                    if ($o->getPadre() == null) {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => 0, 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 1);
                    } else {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => $o->getPadre()->getId(), 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 1);
                    }
                } else {
                    if ($o->getPadre() == null) {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => 0, 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 0);
                    } else {
                        $arreglo [] = array('id' => $o->getId(), 'padre' => $o->getPadre()->getId(), 'nombre' => $o->getNombre(), 'desc' => $o->getDescripcion(), 'add' => 0);
                    }
                }
            }

            return new Response(json_encode($arreglo));
        }
    }
        public function cargarCategoriaPVAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CategoriaProductoVenta');
            $ums = $repositorio->findBy(array('activo' => 1));
            return $this->render('SisGGFinalBundle:CategoriaPV:cargarCategoriaPV.html.twig', array('pvs'=>$ums));
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>