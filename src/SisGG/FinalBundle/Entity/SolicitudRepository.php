<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author martin
 */
class SolicitudRepository extends EntityRepository {

    //Devuelve todas las solicitudes nuevas que no tiene una respuesta asociada
    public function findSolicitudesNuevas() {
        return $this->getEntityManager()
                        ->createQuery("SELECT s FROM SisGGFinalBundle:Solicitud s WHERE s.respuesta is NULL")
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