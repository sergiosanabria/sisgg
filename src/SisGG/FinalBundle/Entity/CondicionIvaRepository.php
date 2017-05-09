<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author martin
 */
class CondicionIvaRepository extends EntityRepository {

    public function findOperacion($tipoOperacion,$de,$a) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:CondicionIva c WHERE c.tipoOperacion = ?1 AND c.de = ?2 AND c.a = ?3")
                        ->setParameter(1, $tipoOperacion)
                        ->setParameter(2, $de)
                        ->setParameter(3, $a)
                        ->getResult();
    }
}

?>