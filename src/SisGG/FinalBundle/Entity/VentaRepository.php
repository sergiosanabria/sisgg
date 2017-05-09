<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\EntityRepository;
/*
 *
 * @author martin
 */
class VentaRepository extends EntityRepository {

    public function findVentas() {
        return $this->getEntityManager()
                        ->createQuery("SELECT v FROM SisGGFinalBundle:Venta v")
                        ->getResult();
    }
    
    public function findCantidadVentasEntreFechas(\DateTime $diaMenor,\DateTime $diaMayor,$productos = null){
            if ($productos == null || empty($productos)){
            return $this->getEntityManager()
                        ->createQuery("SELECT SUM(l.cantidad) FROM SisGGFinalBundle:Venta v JOIN v.lineasVenta l WHERE (v.fecha BETWEEN ?1 AND ?2)")
                        ->setParameter(1, $diaMenor)
                        ->setParameter(2, $diaMayor)
                        ->getResult();
            }else{
                $string = $productos[0];
                for ($index = 1; $index < count($productos); $index++) {
                    $string = $string.",".$productos[$index];
                }
                $consulta = "SELECT SUM(l.cantidad) FROM SisGGFinalBundle:Venta v JOIN v.lineasVenta l JOIN l.productoVenta p WHERE ((v.fecha BETWEEN ?1 AND ?2) AND (p.id IN (".$string.")))";
                return $this->getEntityManager()
                        ->createQuery($consulta)
                        ->setParameter(1, $diaMenor)
                        ->setParameter(2, $diaMayor)
                        ->getResult();
            
            }
    }
    
    public function findRecaudacionVentasEntreFechas(\DateTime $diaMenor,\DateTime $diaMayor,$productos = null){
            if ($productos == null|| empty($productos)){
            return $this->getEntityManager()
                        ->createQuery("SELECT SUM(l.total) FROM SisGGFinalBundle:Venta v JOIN v.lineasVenta l WHERE (v.fecha BETWEEN ?1 AND ?2)")
                        ->setParameter(1, $diaMenor)
                        ->setParameter(2, $diaMayor)
                        ->getResult();
            }else{
                $string = $productos[0];
                for ($index = 1; $index < count($productos); $index++) {
                    $string = $string.",".$productos[$index];
                }
                $consulta = "SELECT SUM(l.total) FROM SisGGFinalBundle:Venta v JOIN v.lineasVenta l JOIN l.productoVenta p WHERE ((v.fecha BETWEEN ?1 AND ?2) AND (p.id IN (".$string.")))";
                return $this->getEntityManager()
                        ->createQuery($consulta)
                        ->setParameter(1, $diaMenor)
                        ->setParameter(2, $diaMayor)
                        ->getResult();
            
            }
    }
}

?>