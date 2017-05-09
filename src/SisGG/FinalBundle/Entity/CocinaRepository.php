<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Repositorio de Cocina
 *
 * @author martin
 */
class CocinaRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findOther(array $criteria) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Cliente c") //WHERE c.telefono.nacional = :nacional and c.telefono.caracteristica = :caracteristica and c.telefono.numero = :numero")
                        //->setParameters(array('nacional'=>$criteria['telefono']->getNacional(),'caracteristica'=>$criteria['telefono']->getCaracteristica(),'numero'=>$criteria['telefono']->getNumero()))
                
                        ->getResult();
    }
}

?>
