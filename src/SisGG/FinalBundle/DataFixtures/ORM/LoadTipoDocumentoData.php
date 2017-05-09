<?php

namespace SisGG\FinalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SisGG\FinalBundle\Entity\TipoDocumento;
class LoadTipoDocumentoData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        $unTipoDocumento = new TipoDocumento();
        $unTipoDocumento->setNombre("Documento Nacional de Identidad");
        $unTipoDocumento->setAbreviatura("DNI");
        $manager->persist($unTipoDocumento);
        $manager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2; // el orden en el cual serán cargados los accesorios
    }
    

}
