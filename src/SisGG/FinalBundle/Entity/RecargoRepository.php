<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class RecargoRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findRecargos($id) {
        return $this->getEntityManager()
                        ->createQuery("SELECT r FROM SisGGFinalBundle:Recargo r JOIN SisGGFinalBundle:TipoPedido WHERE r. = 1")
                        ->getResult();
    }
}

?>
