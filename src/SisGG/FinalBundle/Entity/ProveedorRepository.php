<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author sergio
 */
class ProveedorRepository extends EntityRepository {

    public function findByCondicion($tipo) {
      return  $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Proveedor p inner join p.condicionIva c inner join c.operacionesA o inner join o.tipoFactura t WHERE p.activo=1 and t.nomenclatura= :tipo order by p.razonSocial asc")
                        ->setParameter('tipo', $tipo)
                        ->getResult();
    }
    
}

?>