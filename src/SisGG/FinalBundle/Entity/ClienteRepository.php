<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class ClienteRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findOther(array $criteria) {
        return $this->getEntityManager()
                        ->createQuery("SELECT c FROM SisGGFinalBundle:Cliente c") //WHERE c.telefono.nacional = :nacional and c.telefono.caracteristica = :caracteristica and c.telefono.numero = :numero")
                        //->setParameters(array('nacional'=>$criteria['telefono']->getNacional(),'caracteristica'=>$criteria['telefono']->getCaracteristica(),'numero'=>$criteria['telefono']->getNumero()))
                
                        ->getResult();
    }
    
    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findPorDefecto() {
        return $this->getEntityManager()
                       ->createQuery("SELECT c FROM SisGGFinalBundle:Cliente c WHERE c.porDefecto = true") //WHERE c.telefono.nacional = :nacional and c.telefono.caracteristica = :caracteristica and c.telefono.numero = :numero")
                       ->getResult();
    }
}

?>
