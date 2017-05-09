<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author martin
 */
class OperacionRepository extends EntityRepository {

    public function findOperacion($tipoOperacion,$de,$a) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Operacion c WHERE c.tipoOperacion = ?1 AND c.de = ?2 AND c.a = ?3")
                        ->setParameter(1, $tipoOperacion->getId())
                        ->setParameter(2, $de->getId())
                        ->setParameter(3, $a->getId())
                        ->getResult();
    }
      public function findFacturasCompra($tipoOperacion,$de) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Operacion c WHERE c.tipoOperacion = ?1 AND c.de = ?2 ")
                        ->setParameter(1, $tipoOperacion)
                        ->setParameter(2, $de)
                        ->getResult();
    }
}

?>