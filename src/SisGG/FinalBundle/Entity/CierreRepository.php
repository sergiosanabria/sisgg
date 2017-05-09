<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class CierreRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findSiguienteNumeroCierre() {
        return $this->getEntityManager()
                        ->createQuery("SELECT COUNT(c) FROM SisGGFinalBundle:Cierre c")
                        ->getScalarResult();
    }
}

?>
