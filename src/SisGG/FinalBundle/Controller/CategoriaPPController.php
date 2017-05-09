<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\CategoriaProductoProduccionType;
use SisGG\FinalBundle\Entity\CategoriaProductoProduccion;
use SisGG\FinalBundle\Entity\Empresa;

/**
 * Description of CategoriaPPController
 *
 * @author sergio
 */
class CategoriaPPController extends Controller {

    public function nuevaCategoriaPPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $formulario = $this->createForm(new CategoriaProductoProduccionType());
        $parameters = array('form' => $formulario->createView(), 'error' => null);
        return $this->render('SisGGFinalBundle:CategoriaPP:nuevaCategoriaPP.html.twig', $parameters);
    }
    
    public function altaCategoriaPPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_NUEVO)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $categoria = new CategoriaProductoProduccion();
        $empresa = $this->getEmpresa();
        $formulario = $this->createForm(new CategoriaProductoProduccionType(), $categoria);
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->altaCategoriaPP($categoria, $this);
            if ($r == null) {
                $msj = "La Categoria " . $categoria->getNombre() . " ha sido exitosamente registrada.";
                $this->getRequest()->getSession()->set('msjCPP', $msj);
                return $this->redirect($this->generateUrl('categoriasPP', array('msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:CategoriaPP:nuevaCategoriaPP.html.twig', array('form' => $formulario->createView(), 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:CategoriaPP:nuevaCategoriaPP.html.twig', array('form' => $formulario->createView(), 'error' => "Verifique que los campos ingresados sean correctos."));
    }
    
    public function editarCategoriaPPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->get('idCat1') != null) {
            $em = $this->getDoctrine()->getEntityManager();
            $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoProduccion');
            $categoria = $repositorio->findOneBy(array('nombre' => $this->getRequest()->get('idCat1')));
            $formulario = $this->createForm(new CategoriaProductoProduccionType(), $categoria);
            $this->getRequest()->getSession()->set('idCPP', $categoria->getId());
            if ($categoria->getPadre() == null)
                $parameters = array('form' => $formulario->createView(), 'error' => null, 'padre' => "0",'nombre'=>$categoria->getNombre() , 'id'=>$categoria->getId());
            else {
                $parameters = array('form' => $formulario->createView(), 'error' => null, 'padre' => "1",'nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId());
            }
            return $this->render('SisGGFinalBundle:CategoriaPP:editarCategoriaPP.html.twig', $parameters);
        }
        $this->getRequest()->getSession()->set('errorCPP', 'Debe seleccionar una categoria para poder editarla.' . $this->getRequest()->get('nombreCat'));
        return $this->redirect('categoriasPP');
    }

    public function modificarCategoriaPPAction(Empresa $empresa = null) {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_EDITAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoProduccion');
        $categoria = $repositorio->find($this->getRequest()->get('id'));
        $formulario = $this->createForm(new CategoriaProductoProduccionType(), $categoria);
        //$this->getRequest()->getSession()->set('idCPP', $categoria->getId());
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $r = $empresa->modificarCategoriaPP($categoria, $this);
            if ($r == null) {
                $msj = "La categoria " . $categoria->getNombre() . " ha sido exitosamente modificada.";
                $this->getRequest()->getSession()->set('msjCPP', $msj);
                return $this->redirect($this->generateUrl('categoriasPP', array('msj' => null, 'error' => null)));
            } else {
                if ($categoria->getPadre() == null) {
                    $parameters = array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'error' => $r, 'padre' => "0", 'id'=>$categoria->getId());
                    return $this->render('SisGGFinalBundle:CategoriaPP:editarCategoriaPP.html.twig', $parameters);
                } else {
                    $parameters = array('form' => $formulario->createView(), 'error' => $r,'nombre'=>$categoria->getNombre(), 'padre' => "1", 'id'=>$categoria->getId());
                    return $this->render('SisGGFinalBundle:CategoriaPP:editarCategoriaPP.html.twig', $parameters);
                }
            }
        }
        if ($categoria->getPadre() == null) {
            return $this->render('SisGGFinalBundle:CategoriaPP:editarCategoriaPP.html.twig', array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId(), 'padre' => "0", 'error' => "Verifique que los campos ingresados sean correctos."));
        } else {
            return $this->render('SisGGFinalBundle:CategoriaPP:editarCategoriaPP.html.twig', array('form' => $formulario->createView(),'nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId(), 'padre' => "1", 'error' => "Verifique que los campos ingresados sean correctos."));
        }
    }
    
      public function eliminarCategoriaPPAction(Empresa $empresa = null) {
          $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->get('idCat2') != null) {
            $empresa = $this->getEmpresa();
            $em = $this->getDoctrine()->getEntityManager();
            $repositorio = $em->getRepository('SisGGFinalBundle:CategoriaProductoProduccion');
            $categoria = $repositorio->findOneBy(array('nombre' => $this->getRequest()->get('idCat2')));
            $r = $empresa->eliminarCategoriaPP($categoria, $this);
            if ($r == null) {
                $msj = "La categoria " . $categoria->getNombre() . " ha sido exitosamente eliminada.";
                $this->getRequest()->getSession()->set('msjCPP', $msj);
                return $this->redirect($this->generateUrl('categoriasPP', array('form'=>null,'msj' => null, 'error' => null)));
            } else {
                return $this->render('SisGGFinalBundle:CategoriaPP:categoriasPP.html.twig', array('form'=>null,'msj' => null, 'error' => $r));
            }
        }
        return $this->render('SisGGFinalBundle:CategoriaPP:categoriasPP.html.twig', array('form'=>null,'msj' => null, 'error' => 'Debe seleccionar una categoria para poder eliminarla.'));
    }
    
    public function categoriasPPAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CATEGORIAPP_LISTAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $msj = $this->getRequest()->getSession()->get('msjCPP');
        $error = $this->getRequest()->getSession()->get('errorCPP');
        $parameters = array('msj' => $msj, 'error' => $error, 'form'=>null);
        $this->getRequest()->getSession()->remove('msjCPP');
        $this->getRequest()->getSession()->remove('errorCPP');
        return $this->render('SisGGFinalBundle:CategoriaPP:categoriasPP.html.twig', $parameters);
    }
    public function buscarCategoriaPP1Action() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery("SELECT  c from SisGGFinalBundle:CategoriaProductoProduccion c  WHERE  c.nombre  LIKE :n and c.activo=1");
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

    public function enviarCategoriasPAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $categorias = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:CategoriaProductoProduccion')->findBy(array('activo' => 1));
            foreach ($categorias as $o) {
                if ($o->getProductoProduccion()->isEmpty()) {
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

    public function enviarCategoriasPPAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $categorias = $this->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:CategoriaProductoProduccion')->findBy(array('activo' => 1));
            //$categorias = $this->getEmpresa()->getCategoriasPV();
            foreach ($categorias as $o) {
                if ((!$o->getProductoProduccion()->isEmpty()) || ($o->getHijo()->isEmpty())) {
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
    
    public function cargarCategoriaPPAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $repositorio = $this->getDoctrine()->getRepository('SisGGFinalBundle:CategoriaProductoProduccion');
            $ums = $repositorio->findBy(array('activo' => 1));
            return $this->render('SisGGFinalBundle:CategoriaPP:cargarCategoriaPP.html.twig', array('cpp'=>$ums));
        }
    }
    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

}

?>