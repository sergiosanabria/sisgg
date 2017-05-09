<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Form\ProveedorType;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Entity\Proveedor;
use SisGG\FinalBundle\Form\CiudadType;
use SisGG\FinalBundle\Form\ProvinciaType;

//use Ps\PdfBundle\Annotation\Pdf;

/**
 * Description of ProveedorController
 *
 * @author sergio
 */
class ProveedorController extends Controller
{

    public function nuevoProveedorAction()
    {
        $formulario = $this->createForm(new ProveedorType());
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        return $this->render('SisGGFinalBundle:Proveedor:nuevoProveedor.html.twig', array('form' => $formulario->createView(), 'error' => null, 'errorProv' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function altaProveedorAction(Request $request, Empresa $empresa = null)
    {
        $empresa = $this->getEmpresa();
        $proveedor = new Proveedor();
        $formulario = $this->createForm(new ProveedorType(), $proveedor);
        $formulario->handleRequest($request);
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        if ($formulario->isValid()) {
            $r = $empresa->addProveedor($proveedor, $this);
            if ($r == null) {
                $msj = "El " . $proveedor->getRazonSocial() . " CUIT: " . $proveedor->getCuit() . " ha sido registrado exitosamente.";
                $request->getSession()->set('msjPro', $msj);
                return $this->redirect($this->generateUrl('proveedores'));
            } else {
                return $this->render('SisGGFinalBundle:Proveedor:nuevoProveedor.html.twig', array('form' => $formulario->createView(), 'error' => null, 'errorProv' => $r, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
            }
        }
        return $this->render('SisGGFinalBundle:Proveedor:nuevoProveedor.html.twig', array('form' => $formulario->createView(), 'errorProv' => "Verifique que los campos ingresados sean correctos.", 'error' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function proveedoresAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $proveedor = $this->getDoctrine()->getRepository("SisGGFinalBundle:Proveedor")->find($this->getRequest()->get('id'));
            $form = $this->createForm(new ProveedorType(), $proveedor, array('disabled' => true));
            return $this->render('SisGGFinalBundle:Proveedor:detalles.html.twig', array('p' => $proveedor, 'form' => $form->createView(), 'telefonos' => $proveedor->getTelefonos()));
        } else {

            $estado = null;
            $estado = $msj = $this->getRequest()->get('estado');
            $proveedores = null;
            $cantidad = 0;
            if ($estado == 1) {
                $proveedores = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->findBy(array('activo' => false));
            } else {
                $proveedores = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->findBy(array('activo' => true));
                $inactivos = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->findBy(array('activo' => false));
                $cantidad = count($inactivos);
            }
            $msj = $this->getRequest()->getSession()->get('msjPro');
            $this->getRequest()->getSession()->remove('msjPro');
            $error = $this->getRequest()->getSession()->get('error');
            $this->getRequest()->getSession()->remove('error');
            $parameters = array('proveedores' => $proveedores, 'msj' => $msj, 'estado' => $estado, 'form' => null, 'cantidad' => $cantidad, 'error' => $error);
            return $this->render('SisGGFinalBundle:Proveedor:proveedores.html.twig', $parameters);
        }
    }

    public function editarProveedorAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $error = $this->getRequest()->getSession()->get('errorProv');
        $this->getRequest()->getSession()->remove('errorProv');
        $repositorio = $em->getRepository('SisGGFinalBundle:Proveedor');
        $proveedor = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $formulario = $this->createForm(new ProveedorType(), $proveedor);
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        $var = $this->getRequest()->getSession()->get('NPyPro1');
        if ($var != null) {
            $this->getRequest()->getSession()->remove('errorProv');
            $this->getRequest()->getSession()->remove('NPyPro1');
            $this->getRequest()->getSession()->set('NPyPro2', $var);
        }
        return $this->render('SisGGFinalBundle:Proveedor:editarProveedor.html.twig', array('NPyPro' => $var, 'idP' => $proveedor->getDireccion()->getCiudad()->getProvincia()->getId(), 'id' => $proveedor->getId(), 'nombre' => $proveedor->getRazonSocial(), 'form' => $formulario->createView(), 'error' => $error, 'errorProv' => $error, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function modificarProveedorAction()
    {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Proveedor');
        $proveedor = $repositorio->findOneBy(array('id' => $this->getRequest()->get('id')));
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        $datos = array();
        // Create an array of the current Tag objects in the database
        foreach ($proveedor->getTelefonos() as $item) {
            $datos[] = $item;
        }
        $formulario = $this->createForm(new ProveedorType(), $proveedor);
        $formulario->handleRequest($this->getRequest());
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        if ($formulario->isValid()) {

            $r = $empresa->modificarProveedor($proveedor, $datos, $this);
            if ($r == null) {
                $msj = "El " . $proveedor->getRazonSocial() . " CUIT: " . $proveedor->getCuit() . " ha sido modificado exitosamente.";
                $var = $this->getRequest()->getSession()->get('NPyPro2');
                if ($this->getRequest()->get('NPyPro') != null) {
                    if ($var == $this->getRequest()->get('NPyPro')) {
                        $tipo = $this->getRequest()->getSession()->get('tipoNPSes');
                        $this->getRequest()->getSession()->remove('NPyPro2');
                        $this->getRequest()->getSession()->remove('tipoNPSes');
                        return $this->redirect($this->generateUrl('nuevaCompra', array('tipo' => $tipo, 'np' => $var)));
                    }
                }
                $this->getRequest()->getSession()->set('msjPro', $msj);
                return $this->redirect($this->generateUrl('activarProveedor'));
            } else {
                return $this->render('SisGGFinalBundle:Proveedor:editarProveedor.html.twig', array('id' => $proveedor->getId(), 'nombre' => $proveedor->getRazonSocial(), 'form' => $formulario->createView(), 'error' => null, 'errorProv' => $r, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
            }
        }
        return $this->render('SisGGFinalBundle:Proveedor:editarProveedor.html.twig', array('id' => $proveedor->getId(), 'nombre' => $proveedor->getRazonSocial(), 'form' => $formulario->createView(), 'error' => null, 'errorProv' => "Verifique que los campos ingresados sean correctos.", 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    public function eliminarProveedorAction(Empresa $empresa = null)
    {
        $empresa = $this->getEmpresa();
        $em = $this->getDoctrine()->getEntityManager();
        $repositorio = $em->getRepository('SisGGFinalBundle:Proveedor');
        $proveedor = $repositorio->find($this->getRequest()->get('id'));
        $r = $empresa->eliminarProveedor($proveedor, $this);
        if ($r == null) {
            $msj = "El proveedor" . $proveedor->getRazonSocial() . " ha sido eliminado correctamente.";
            $this->getRequest()->getSession()->set('msjPro', $msj);
        } else {
            $this->getRequest()->getSession()->set('error', $r);
        }
        return $this->redirect($this->generateUrl('proveedores'));
    }

    public function activarProveedorAction()
    {
        //ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getEntityManager();
            $id = null;
            $id = $this->getRequest()->get('id');
            if ($id != null) {
                /* @var Proveedor $proveedor */
                $proveedor = $em->getRepository("SisGGFinalBundle:Proveedor")->find($id);
                $proveedor->setActivo(true);
                $em->flush($proveedor);
                return new Response(json_encode(array('rta' => 'ok', 'nombre' => $proveedor->getRazonSocial())));
            } else {
                return new Response(json_encode(array('rta' => 'no')));
            }
        }
    }

    public function cargarProveedorAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $nombre = $this->getRequest()->get('name');
            $tipo = $this->getRequest()->get('tipo');
            $proveedores = null;
            if ($tipo != null) {
                $proveedores = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->findBy(array('activo' => 1, 'responsable' => $tipo));
            } else {
                $proveedores = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->findBy(array('activo' => 1));
            }
            return $this->render('SisGGFinalBundle:Proveedor:cargarProveedor.html.twig', array('ps' => $proveedores, 'name' => $nombre));
        }
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa
     */
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
//    public function impDetallesProvAction()
//    {
//        $proveedor = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->find($this->getRequest()->get('id'));
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'proveedor' => $proveedor, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Proveedor:imp_detalles.' . $format . '.twig', $parameters);
//    }
//
//    /**
//     * @Pdf()
//     */
//    public function impListaProvAction()
//    {
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Proveedor')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Proveedor:imp_lista.' . $format . '.twig', $parameters);
//    }

}

?>