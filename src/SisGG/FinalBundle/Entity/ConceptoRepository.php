<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class ConceptoRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findEntradaSalida($tipo) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Concepto c WHERE c.tipo = ?1")
                        ->setParameter(1, $tipo)
                        ->getResult();
    }
}

?>
