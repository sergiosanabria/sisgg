<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Image;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Entity\Mercaderia;
use SisGG\FinalBundle\Entity\CategoriaProductoVenta;
use SisGG\FinalBundle\Form\ClienteType;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Entity\ProductoVenta;
use SisGG\FinalBundle\Entity\Ingrediente;
use SisGG\FinalBundle\Entity\Plato;
use SisGG\FinalBundle\Entity\Provincia;
use SisGG\FinalBundle\Entity\MateriaPrima;
use SisGG\FinalBundle\Entity\Ciudad;
use SisGG\FinalBundle\Entity\Pais;
use SisGG\FinalBundle\Entity\Localidad;
use SisGG\FinalBundle\Entity\Tasa;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\Acl;

class DefaultController extends Controller {

    public function indexAction() {
        //         $p1 = new Plato();
        //          $p1->setNombre('Pizza Muzzarella');
        //          $p1->setPrecioVenta(50.63);
        //          $p1->setTasa($this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Tasa")->find(1));
        //          $p1->setSupera(false);
        //          $pp1 = new MateriaPrima();
        //          $pp1->setNombre('Relleno');
        //          $pp1->setCantidad(10);
        //          $pp1->setTasa($this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Tasa")->find(2));
        //          $pp2 = new MateriaPrima();
        //          $pp2->setNombre('Aceituna');
        //          $pp2->setTasa($this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Tasa")->find(2));
        //          $pp2->setCantidad(10);
        //          $i1 = new Ingrediente();
        //          $i1->setProductoProduccion($pp1);
        //          $i1->setCantidad(1);
        //          $i1->setTasa($this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Tasa")->find(3));
        //          $i1->setObligatorio(true);
        //          $i2 = new Ingrediente();
        //          $i2->setTasa($this->getDoctrine()->getEntityManager()->getRepository("SisGGFinalBundle:Tasa")->find(3));
        //          $i2->setProductoProduccion($pp2);
        //          $i2->setCantidad(9);
        //          $i2->setObligatorio(false);
        //          $p1->addIngrediente($i1);
        //          $p1->addIngrediente($i2);
        //          $i1->setPlato($p1);
        //          $i2->setPlato($p1);
        //          $pais = new Pais();
        //          $pais->setNombre("Argentina");
        //          $provincia = new Provincia();
        //          $provincia->setNombre('Corrientes');
        //          $provincia->setPais($pais);
        //          $pais->addProvincia($provincia);
        //          $ciudad = new Localidad();
        //          $ciudad->setNombre('Ituzaingo');
        //          $ciudad->setProvincia($provincia);
        //          $provincia->addLocalidad($ciudad);
        //          $categoria = new CategoriaProductoVenta();
        //          $categoria->setNombre("Pizza");
        //          $categoria2 = new CategoriaProductoVenta();
        //          $categoria2->setNombre("Pizzeta");
        //          $categoria3 = new CategoriaProductoVenta();
        //          $categoria3->setNombre("Pizzona");
        //          $categoria->getHijo()->add($categoria2);
        //          $categoria2->setPadre($categoria);
        //          $categoria2->getHijo()->add($categoria3);
        //          $categoria3->setPadre($categoria2);
        //          $p1->setCategoria($categoria3);
        //          $categoria->getProductoVenta()->add($p1);
        //          $em = $this->getDoctrine()->getEntityManager();
        //          $em->persist($pp1);
        //          $em->persist($pp2);
        //          $em->persist($i1);
        //          $em->persist($i2);
        //          $em->persist($p1);
        //          $em->persist($pais);
        //          $em->persist($provincia);
        //          $em->persist($ciudad);
        //          $em->persist($categoria);
        //          $em->persist($categoria2);
        //          $em->persist($categoria3);
        //          $em->flush();
//           $message = \Swift_Message::newInstance()
//                  ->setSubject('Hello Email')
//                  ->setTo('martinfer.69@gmail.com')
//                  ->setBody(
//                      $this->renderView(
//                          'SisGGFinalBundle:Mail:email.txt.twig',
//                          array('name' => "martin")
//                      )
//                  )
//              ;
//              $this->get('mailer')->send($message);
//        $empresa= new \SisGG\FinalBundle\Entity\Empresa();
//        $empresa->addMensaje('HOLAAA', 1, $this);
//        
//        $aclProvider = $this->get('security.acl.provider');
//        $objectIdentity = new ObjectIdentity('class', 'SisGG\FinalBundle\Controller\PedidoController');
//        /** @var $acl Acl*/
//        $acl = $aclProvider->findAcl($objectIdentity);
//        $role = $this->getUser()->getRole();
//        $securityIdentity = new RoleSecurityIdentity($role);
//        $acl->insertClassAce($securityIdentity,  \Symfony\Component\Security\Acl\Permission\MaskBuilder::MASK_VIEW,0,false);
//        $aclProvider->updateAcl($acl);
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(),  \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CLIENTE_BORRAR)){
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        return $this->render("SisGGFinalBundle:Cajero:cajero.html.twig", array('form' => null, 'gestor_autenticacion' => $gestor));
    }

}
