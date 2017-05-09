<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class CategoriaProductoVentaRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findPadres() {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:CategoriaProductoVenta c WHERE (c.padre is NULL)")
                        ->getResult();
    }
    
    public function findHijos($id) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:CategoriaProductoVenta c WHERE c.padre = :padre")->setParameter('padre', $id)
                        ->getResult();
    }
}

?>
