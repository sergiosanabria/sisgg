<?php
    namespace SisGG\FinalBundle\Model;
    
    use Doctrine\ORM\EntityManager;
    use SisGG\FinalBundle\Entity\PedidoMostrador;
    use SisGG\FinalBundle\Entity\Solicitud;
    use SisGG\FinalBundle\Entity\DetalleSolicitud;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author martin
 */
class GestorSolicitudes {
    
    var $em = null;
    
    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
   
    /**
    * @param \SisGG\FinalBundle\Entity\Pedido $pedido
    */ 
   public function enviarSolicitud(\SisGG\FinalBundle\Entity\Pedido $pedido){
       $unaSolicitud = new Solicitud();
       $unaSolicitud->setEncabezado("Solicitud Nueva");
       foreach ($pedido->getItemPedido() as $itemPedido) {
           $unDetalle = new DetalleSolicitud();
           $unDetalle->setTexto($itemPedido->getCantidad()."-".$itemPedido->__toString());
           $unDetalle->setSolicitud($unaSolicitud);
           $unaSolicitud->addDetalle($unDetalle);
       }
       $this->em->persist($unaSolicitud);
       $this->em->flush();
       return $unaSolicitud->getId();
   }
   
   
   public function responderSolicitud($solicitud,$respuesta){
       /* @var $unaSolicitud \SisGG\FinalBundle\Entity\Solicitud */
       $unaSolicitud = $this->em->getRepository("SisGGFinalBundle:Solicitud")->find($solicitud);
       $unaSolicitud->setRespuesta($respuesta);
       $this->em->flush();
   }
   
   
   public function verRespuesta($solicitud){
       /* @var $unaSolicitud \SisGG\FinalBundle\Entity\Solicitud */
       if ($solicitud!=null){
           $unaSolicitud = $this->em->getRepository("SisGGFinalBundle:Solicitud")->find($solicitud);
           return $unaSolicitud->getRespuesta();
       }       
       return null; 
   }
   /**
    * @return \Doctrine\Common\Collections\ArrayCollection
    */
   public function verSolicitudes(){
       return $this->em->getRepository("SisGGFinalBundle:Solicitud")->findSolicitudesNuevas();
       
   }
}

?>
