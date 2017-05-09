<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class LibroIvaVentaRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findByMesAño($mes,$año) {
        $hasta = new \DateTime($año . '-' . $mes . '-' . cal_days_in_month(CAL_GREGORIAN, $mes, $año));
        $desde = new \DateTime($año . '-' . $mes . '-' . '1');
        return $this->getEntityManager()
                        ->createQuery("SELECT l FROM SisGGFinalBundle:LibroIvaVenta l WHERE l.fecha BETWEEN ?1 AND ?2")
                        ->setParameter(1, $desde)
                        ->setParameter(2, $hasta)
                        ->getResult();
    }
}

?>
