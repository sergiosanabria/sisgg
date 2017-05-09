<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class CajaRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findCaja() {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Caja c WHERE c.id = 1")
                        ->getResult();
    }
}

?>
