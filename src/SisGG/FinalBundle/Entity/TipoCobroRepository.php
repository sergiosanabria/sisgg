<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author martin
 */
class TipoCobroRepository extends EntityRepository {

    public function findRegistrables() {
        return $this->getEntityManager()
                        ->createQuery("SELECT t FROM SisGGFinalBundle:TipoCobro t WHERE t.liquido = true")
                        ->getResult();
    }
}

?>