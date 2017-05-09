<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class TandaRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findTandas() {
        return $this->getEntityManager()
                        ->createQuery("SELECT t FROM SisGGFinalBundle:Tanda t WHERE t.estado='Vigente'")
                        ->getResult();
    }
    
    public function findResumen($id){
        return $this->getEntityManager()
                        ->createQuery("SELECT SUM(it.cantidad) cantidad,pv.nombre FROM SisGGFinalBundle:Tanda t JOIN t.comandas c JOIN c.itemPedido it JOIN it.productoVenta pv WHERE t.id = :id GROUP BY pv")
                        ->setParameter("id", $id)
                        ->getResult();
    }
}

?>