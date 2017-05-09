<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of MarcaTemporalRepository
 *
 * @author martin
 */
class MarcaTemporalRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findNuevos($usuario) {
        return $this->getEntityManager()
                        ->createQuery("SELECT m FROM SisGGFinalBundle:Mensaje m WHERE m.nuevo = true AND (m.usuario = :usuario OR (m.usuario is NULL))")
                        ->setParameter("usuario", $usuario)
                        ->getResult();
    }
    
    public function findHijos($id) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:CategoriaProductoVenta c WHERE c.padre = :padre")->setParameter('padre', $id)
                        ->getResult();
    }
}

?>