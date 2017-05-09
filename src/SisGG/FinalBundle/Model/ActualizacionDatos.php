<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\Lote;
use SisGG\FinalBundle\Entity\Entrada;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Entity\TipoCobro;
use SisGG\FinalBundle\Entity\ItemCierre;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\Cierre;
use SisGG\FinalBundle\Entity\Movimiento;
use SisGG\FinalBundle\Entity\Salida;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Entity\TipoFactura;
use SisGG\FinalBundle\Entity\Valor;

/**
 * Description of GestorCajas
 *
 * @author martin
 */
class GestorCajas {
    /* @var $em \Doctrine\ORM\EntityManager */
    private $em = null;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager(){
        return $this->em;
    }

    public function getRepository($name){
        $entityName = 'SisGGFinalBundle:'.$name;
        $repository = $this->getEntityManager()->getRepository($entityName);
        return $repository;
    }
}

?>