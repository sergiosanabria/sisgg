<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class MovimientoRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findMovimientos($lote) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Movimiento c WHERE c.lote = :lote")
                        ->setParameter('lote', $lote)
                        ->getResult();
    }
}

?>
