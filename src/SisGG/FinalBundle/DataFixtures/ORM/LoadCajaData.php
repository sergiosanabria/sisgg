<?php

namespace SisGG\FinalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\Concepto;
class LoadCajaData extends AbstractFixture implements OrderedFixtureInterface{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $caja = new Caja();
        $caja->setAbierta(false);
        $concepto = new Concepto();
        $concepto->setNombre("Cobro por Venta");
        $concepto->setDescripcion("Cobro por Venta");
        $concepto->setTipo(true);
	$concepto->setEstado('activo');
        $manager->persist($caja);
        $manager->persist($concepto);
        $manager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // el orden en el cual serán cargados los accesorios
    }

}


