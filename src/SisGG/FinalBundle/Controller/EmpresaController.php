<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Form\EmpresaType;
use SisGG\FinalBundle\Entity\Image;
use SisGG\FinalBundle\Form\ImageType;
use SisGG\FinalBundle\Form\CiudadType;
use SisGG\FinalBundle\Form\ProvinciaType;

/**
 * Description of EmpresaController
 *
 * @author sergio
 */
class EmpresaController extends Controller {

    public function editarEmpresaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::EMPRESA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $this->getDoctrine()->getRepository('SisGGFinalBundle:Empresa')->find(1);
        $newfondo = new Image();
        $path = null;
        $id = null;
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findBy(array(), array('nombre' => 'asc'));
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        if ($empresa->getFoto() != null) {
            $id = $empresa->getFoto()->getId();
        }
        if ($id != null) {
            $newfondo = $empresa->getFoto();
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $newfondo->getPath();
        } else {
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }
        $formulario = $this->createForm(new EmpresaType(), $empresa);
        $idP = null;
        if ($empresa->getDireccion()->getCiudad()->getProvincia()->getId() != null) {
            $idP = $empresa->getDireccion()->getCiudad()->getProvincia()->getId();
        }

        $parameters = array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $idP, 'form' => $formulario->createView(), 'error' => null, 'errorProv' => null, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null);
        return $this->render('SisGGFinalBundle:Empresa:editarEmpresa.html.twig', $parameters);
    }

    public function modificarEmpresaAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::EMPRESA_EDITAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $empresa = new Empresa();
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $this->getDoctrine()->getRepository('SisGGFinalBundle:Empresa')->find(1);

        //Imagen
        $tipo = 5;
        $newfondo = new Image();
        $path = null;
        $id = null;
        if ($empresa->getFoto() != null) {
            $id = $empresa->getFoto()->getId();
        }
        if ($id != null) {
            // $newfondo = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
            $imgen = $this->createForm(new ImageType(), $newfondo);
            $path = $empresa->getFoto()->getPath();
            if ($this->getRequest()->get('tipo') != null) {
                $empresa->setFoto(null);
                $tipo = 1;
            }
        } else {
            $tipo = 0;
            $imgen = $this->createForm(new ImageType(), $newfondo);
        }

        $imgen->bindRequest($this->getRequest());
        //
        $formulario = $this->createForm(new EmpresaType(), $empresa);
        $formCiudad = $this->createForm(new CiudadType());
        $formvPro = $this->createForm(new ProvinciaType());
        $provincias = $this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Provincia")->findAll();
        //$formulario->bindRequest($this->getRequest()); 
        $datos = array();
        // Create an array of the current Tag objects in the database
        foreach ($empresa->getTelefonos() as $item) {
            $datos[] = $item;
        }
        $formulario->bindRequest($this->getRequest());
        if ($formulario->isValid()) {
            $error = $this->modficar($empresa, $datos);
            if ($error == null) {
                $msj = "Los datos de la empresa se han modificado correctamente";
                //Imagen
                if ($tipo == 1) {

                    $idNull = $empresa->getFoto()->getId();

                    $em->persist($newfondo);
                    $empresa->setFoto($newfondo);

                    $fotonull = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($idNull);
                    $fotonull->removeUpload();
                    $em->remove($fotonull);


                    $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                    $foto->removeUpload();
                    $em->remove($foto);
                    $em->flush();
                } elseif ($tipo == 0) {
                    $id = $empresa->getFoto()->getId();
                    $em->persist($newfondo);
                    $empresa->setFoto($newfondo);
                    $em->flush();
                    $foto = $this->getDoctrine()->getRepository('SisGGFinalBundle:Image')->find($id);
                    $foto->removeUpload();
                    $em->remove($foto);
                    $em->flush();
                }
                //
                $this->getRequest()->getSession()->set('msjEmpresa', $msj);
                return $this->redirect($this->generateUrl('index'));
            } else {

                return $this->render('SisGGFinalBundle:Empresa:editarEmpresa.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $empresa->getDireccion()->getCiudad()->getProvincia()->getId(), 'form' => $formulario->createView(), 'error' => $error, 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
            }
        }
        return $this->render('SisGGFinalBundle:Empresa:editarEmpresa.html.twig', array('pathFoto' => $path, 'imagen' => $imgen->createView(), 'idP' => $empresa->getDireccion()->getCiudad()->getProvincia()->getId(), 'form' => $formulario->createView(), 'error' => 'Verifique que los campos ingresados sean correctos.', 'provincias' => $provincias, 'formCiudad' => $formCiudad->createView(), 'formProv' => $formvPro->createView(), 'msj' => null));
    }

    private function modficar(Empresa $empresa, $datos) {
        $em = $this->getDoctrine()->getEntityManager();
        //telefonos
        if ($empresa->getTelefonos() != null) {
            foreach ($empresa->getTelefonos() as $value) {
                $value->setEmpresa($empresa);
            }
            foreach ($empresa->getTelefonos() as $item) {
                foreach ($datos as $key => $toDel) {
                    if ($toDel->getId() === $item->getId()) {
                        unset($datos[$key]);
                    }
                }
            }
            foreach ($datos as $item) {
                $item->setEmpresa(null);
                $em->remove($item);
            }
        }
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($empresa->getInicioAct() <= $hoy) {
            $em->flush();
            return null;
        } else {
            return 'La fecha de inicio de actividades debe ser inferior al dia de hoy';
        }
    }
     public function indexAction() {
        $qr = new \Endroid\QrCode\QrCode();
        $qr->setText('MARCELO');
        $qr->setSize(300);
        $qr->render('/tmp/codeqr.png');
        return $this->render('SisGGFinalBundle:Empresa:principal.html.twig', array('form' => null));
     }
    public function parametrosAction() {
        return $this->render('SisGGFinalBundle:Empresa:parametros.html.twig', array('form' => null));
    }

}

?>