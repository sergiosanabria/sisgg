<?php

namespace SisGG\FinalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SisGG\FinalBundle\Entity\Campo;
use SisGG\FinalBundle\Entity\Ciudad;
use SisGG\FinalBundle\Entity\Cocina;
use SisGG\FinalBundle\Entity\Concepto;
use SisGG\FinalBundle\Entity\CondicionIva;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\Empresa;
use SisGG\FinalBundle\Entity\IVA;
use SisGG\FinalBundle\Entity\Provincia;
use SisGG\FinalBundle\Entity\Rol;
use SisGG\FinalBundle\Entity\TipoCobro;
use SisGG\FinalBundle\Entity\TipoFactura;
use SisGG\FinalBundle\Entity\TipoOperacion;
use SisGG\FinalBundle\Entity\TipoPedido;
use SisGG\FinalBundle\Entity\Usuario;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $responsableInscripto = new CondicionIva();
        $responsableInscripto->setNombre("IVA RESPONSABLE INSCRIPTO");
        $responsableInscripto->setAbreviatura("RI");
        
        $monotributista = new CondicionIva();
        $monotributista->setNombre("RESPONSABLE MONOTRIBUTO");
        $monotributista->setAbreviatura("RM");
        
        $consumidorFinal = new CondicionIva();
        $consumidorFinal->setNombre("CONSUMIDOR FINAL");
        $consumidorFinal->setAbreviatura("CF");
        
        $exento = new CondicionIva();
        $exento->setNombre("EXENTO");
        $consumidorFinal->setAbreviatura("NR");
        
        $manager->persist($responsableInscripto);   
        $manager->persist($monotributista);
        $manager->persist($consumidorFinal);
        $manager->persist($exento);
        $manager->flush();
        
        $unaProvincia = new Provincia();
        $unaProvincia->setNombre("Misiones");
        $unaCiudad = new Ciudad();
        $unaCiudad->setNombre("Posadas");
        $unaCiudad->setCodigoPostal('3300');
        $unaProvincia->addCiudad($unaCiudad);
        $unaCiudad->setProvincia($unaProvincia);
        $manager->persist($unaProvincia);
        $manager->persist($unaCiudad);
        $manager->flush();
        
        
        $empresa = new Empresa();
        $empresa->setCondicionIva($monotributista);
        $empresa->setCuit("20-20202020-2");
        $empresa->setDescripcion("Empresa dedicada a la venta de comidas");
        $empresa->setNombre("Example");
        $empresa->setSlogan("Tu alegria a la hora de comer es la sonrisa de un niño");
        /*@var $caja \SisGG\FinalBundle\Entity\Caja */
        $caja=$manager->getRepository('SisGGFinalBundle:Caja')->find(1);
        $caja->setEmpresa($empresa);
        $empresa->addCaja($caja);
        
        $direccion = new Direccion();
        $direccion->setCiudad($unaCiudad);
        $direccion->setCalle('Av. Siempre Viva');
        $direccion->setNumero(123);
        $empresa->setDireccion($direccion);
        $iva = new IVA();
        $iva->setGravado(true);
        $iva->setTasa(21.00);
        $iva->setDescripcion("Tasa de Iva 21% Gravado");
        $iva->setActivo(true);
        $empresa->addIva($iva);
        $iva->setEmpresa($empresa);
        $gestor = $GLOBALS['kernel']->getContainer()->get("gestor_autenticacion");
        /* @var $rol Rol */
        $rol = $gestor->nuevoRol();
        $rol->setActivo(1);
        foreach ($rol->getPermisos() as $permiso) {
            $permiso->setRole($rol);
        }
        $rol->setRole("Administrador");
        $userAdmin = new Usuario();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('admin');
        $userAdmin->setIsActive(true);
        $userAdmin->setEmail("example@example.com");
        $userAdmin->setApellido("Example");
        $userAdmin->setNombre("Example");
        $userAdmin->setRole($rol);
        $userAdmin->setEmpresa($empresa);
        $factory = $GLOBALS['kernel']->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($userAdmin);
        $password = $encoder->encodePassword($userAdmin->getPassword(), $userAdmin->getSalt());
        $userAdmin->setPassword($password);
        $manager->persist($empresa);
        $manager->persist($userAdmin);
        $manager->persist($iva);
        $manager->persist($direccion);
        $manager->persist($rol);
        $manager->flush();
        
        
        $unConcepto1 = new Concepto();
        $unConcepto1->setDescripcion("Pago de una compra a un proveedor.");
        $empresa->addConcepto($unConcepto1);
        $unConcepto1->setEmpresa($empresa);
        $unConcepto1->setNombre("Pago por compra");
        $unConcepto1->setEstado('activo');
        $unConcepto1->setTipo(0);
        $manager->persist($unConcepto1);
        
        $unConcepto2 = new Concepto();
        $unConcepto2->setDescripcion("Cobro de una venta a un cliente.");
        $empresa->addConcepto($unConcepto2);
        $unConcepto2->setEmpresa($empresa);
        $unConcepto2->setNombre("Cobro por venta");
        $unConcepto2->setEstado("activo");
        $unConcepto2->setTipo(1);
        $manager->persist($unConcepto2);
        
        
        $unConcepto3 = new Concepto();
        $unConcepto3->setDescripcion("Pago a un empleado por servicio.");
        $empresa->addConcepto($unConcepto3);
        $unConcepto3->setEmpresa($empresa);
        $unConcepto3->setNombre("Pago a un empleado");
        $unConcepto3->setTipo(0);
        $unConcepto3->setEstado("activo");
        $manager->persist($unConcepto3);
        
        $efectivoRegistrable = new TipoCobro();
        $efectivoRegistrable->setNombre("Efectivo registrable");
        $efectivoRegistrable->setDescripcion("Implica un movimiento en Caja");
        $efectivoRegistrable->setLiquido(true);
        $efectivoRegistrable->setEditable(false);
        $efectivoRegistrable->setDarCambio(true);
        $efectivoRegistrable->setActivo(true);
        $efectivoRegistrable->setEmpresa($empresa);
        $efectivoNoRegistrable = new TipoCobro();
        $efectivoNoRegistrable->setNombre("Efectivo no registrable");
        $efectivoNoRegistrable->setDescripcion("No implica un movimiento en caja");
        $efectivoNoRegistrable->setLiquido(false);
        $efectivoNoRegistrable->setEditable(false);
        $efectivoNoRegistrable->setDarCambio(true);
        $efectivoNoRegistrable->setActivo(true);
        $efectivoNoRegistrable->setEmpresa($empresa);
        $tarjetaCredito = new TipoCobro();
        $tarjetaCredito->setNombre("Tarjeta de Credito");
        $tarjetaCredito->setDescripcion("Tarjeta de Credito");
        $tarjetaCredito->setLiquido(true);
        $tarjetaCredito->setDarCambio(true);
        $tarjetaCredito->setActivo(true);
        $tarjetaCredito->setEmpresa($empresa);
        $campo = new Campo();
        $campo->setNombre("Numero de Comprobante");
        $campo->setRequerido(true);
        $campo->setTipoDato("text");
        $campo->setPatron("[0-9]{4}");
        $campo->setEjemplo("0001");
        $tarjetaCredito->addCampo($campo);
        $campo->setTipoCobro($tarjetaCredito);
        $manager->persist($efectivoRegistrable);
        $manager->persist($efectivoNoRegistrable);
        $manager->persist($tarjetaCredito);
        $manager->flush();
        
        $cocina = new Cocina();
        $cocina->setNombre("Cocina Principal");
        $cocina->setNumeroTandas(1);
        $manager->persist($cocina);
        $manager->flush();
        
        $tipoMostrador = new TipoPedido();
        $tipoMostrador->setNombre("mostrador");
        $tipoDelivery = new TipoPedido();
        $tipoDelivery->setNombre("delivery");
        $tipoMesa = new TipoPedido();
        $tipoMesa->setNombre("mesa");
        $manager->persist($tipoMesa);
        $manager->persist($tipoMostrador);
        $manager->persist($tipoDelivery);
        $manager->flush();
        
        $tipoFacturaA = new TipoFactura();
        $tipoFacturaA->setNombre("Factura A");
        $tipoFacturaA->setDescripcion("Factura extendida por RESPONSABLE INSCRIPTO a RESPONSABLE INSCRIPTO");
        $tipoFacturaA->setNomenclatura("A");
        $tipoFacturaA->setCodigo("001");
        
        $tipoFacturaB = new TipoFactura();
        $tipoFacturaB->setNombre("Factura B");
        $tipoFacturaA->setDescripcion("Factura extendida por RESPONSABLE INSCRIPTO a CONSUMIDOR FINAL, MONOTRIBUTISTA ó EXCENTO ");
        $tipoFacturaB->setNomenclatura("B");
        $tipoFacturaB->setCodigo("006");
        
        $tipoFacturaC = new TipoFactura();
        $tipoFacturaC->setNombre("Factura C");
        $tipoFacturaA->setDescripcion("Factura extendida por MONOTRIBUTISTA a RESPONSABLE INSCRIPTO, CONSUMIDOR FINAL, MONOTRIBUTISTA ó EXCENTO");
        $tipoFacturaC->setNomenclatura("C");
        $tipoFacturaC->setCodigo("011");
        
        $manager->persist($tipoFacturaA);
        $manager->persist($tipoFacturaB);
        $manager->persist($tipoFacturaC);
        $manager->flush();
        
        
        $tipoOperacionVenta = new TipoOperacion();
        $tipoOperacionVenta->setNombre("Venta");
        $tipoOperacionVenta->setDescripcion("Operación que representa una venta por parte de la empresa a un cliente");
        
        $tipoOperacionCompra = new TipoOperacion();
        $tipoOperacionCompra->setNombre("Compra");
        $tipoOperacionCompra->setDescripcion("Operación que representa una compra por parte de la empresa a un proveedor");
        
        $manager->persist($tipoOperacionVenta);
        $manager->persist($tipoOperacionCompra);
        $manager->flush();
        
        
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 3; // el orden en el cual serán cargados los accesorios
    }
    

}
