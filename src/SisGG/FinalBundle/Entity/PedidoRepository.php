<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Description of CategoriaProductoVentaRepository
 *
 * @author martin
 */
class PedidoRepository extends EntityRepository {

    //Devuelve todas las categorias que no tienen ningun padre asociado
    public function findIniciados() {
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.estado = 'Iniciado' OR p.estado = 'Actualizado'")
                        ->getResult();
    }
    
    public function findIniciadosOVistosSinTanda() {
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE (p.estado = 'Iniciado' OR p.estado = 'Visto' OR p.estado = 'Actualizado') AND p.tanda is NULL")
                        ->getResult();
    }
    
    public function findPedidos($ids){
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.id in (:ids)")
                        ->setParameter('ids', $ids)
                        ->getResult();
    }
    
    public function findDelDia($dia){
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.fechapedido > :dia AND (p.estado = 'Iniciado' OR p.estado = 'Actualizado' OR p.estado = 'Visto' OR p.estado = 'Listo') ORDER BY p.fechapedido DESC")
                        ->setParameter('dia', $dia)
                        ->getResult();
    }
    
    public function findHistorial(){
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.estado != 'Iniciado' AND p.estado != 'Actualizado' AND p.estado != 'Visto' AND p.estado != 'Listo' ORDER BY p.fechapedido DESC")
                        ->getResult();
    }
    
    public function findDelivery($dia){
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.fechapedido > :dia AND (p.estado = 'Iniciado' OR p.estado = 'Actualizado' OR p.estado = 'Visto' OR p.estado = 'Listo' ) AND p.tipo='delivery'  ORDER BY p.fechapedido DESC")
                        ->setParameter('dia', $dia)
                        ->getResult();
    }
    
    public function findRegistroEnvio($dia){
        return $this->getEntityManager()
                        ->createQuery("SELECT p FROM SisGGFinalBundle:Pedido p WHERE p.fechapedido > :dia AND (p.estado = 'Iniciado' OR p.estado = 'Actualizado' OR p.estado = 'Visto' OR p.estado = 'Listo' OR p.estado = 'Facturado' ) AND p.tipoPedido = 3  ORDER BY p.fechapedido DESC")
                        ->setParameter('dia', $dia)
                        ->getResult();
    }
}

?>
