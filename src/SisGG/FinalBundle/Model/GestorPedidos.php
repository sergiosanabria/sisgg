<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Model\GestorSolicitudes;
use SisGG\FinalBundle\Model\GestorMensajes;
use SisGG\FinalBundle\Entity\Tanda;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\ItemPedido;
use SisGG\FinalBundle\Entity\Mercaderia;
use SisGG\FinalBundle\Entity\ItemDescuentoPedido;
use SisGG\FinalBundle\Entity\ItemRecargoPedido;

/**
 * Description of GestorPedidos
 *
 * @author martin
 */
class GestorPedidos {
    /* @var $em \Doctrine\ORM\EntityManager */

    private $em = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorSolicitudes */
    private $gestorSolicitudes = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorMensajes */
    private $gestorMensajes = null;

    public function __construct(EntityManager $entityManager, GestorSolicitudes $gestorSolicitudes, GestorMensajes $gestorMensajes) {
        $this->em = $entityManager;
        $this->gestorSolicitudes = $gestorSolicitudes;
        $this->gestorMensajes = $gestorMensajes;
    }

    /**
     * @return EntityRepository
     */
    private function getRepository() {
        return $this->em->getRepository("SisGGFinalBundle:Pedido");
    }

    /**
     * 
     * @param type $id
     * @return Pedido
     */
    public function getPedido($id) {
        return $this->getRepository()->find($id);
    }

    public function nuevoPedido(Pedido $unPedido, $idSolicitud, $usuario) {
        foreach ($unPedido->getItemPedido() as $itemPedido) { //Hago las asociaciones que no tiene hechas
            $itemPedido->setPedido($unPedido);
        }
        $respuesta = $this->gestorSolicitudes->verRespuesta($idSolicitud);
        $faltantes = $unPedido->comprobarDisponibilidad();
        if (empty($faltantes) || $respuesta == true) {
            $faltantes = array();
            $unPedido->setUsuario($usuario);
            /* @var $itemPedido ItemPedido */
            foreach ($unPedido->getItemPedido() as $itemPedido) { //Hago las asociaciones que no tiene hechas
                if ($itemPedido->getProductoVenta()->isMercaderia() && !($itemPedido->getEstado()=='Cancelado' || $itemPedido->getEstado()=='cancelado')) {
                        $itemPedido->setEstado("Listo");
                }
            }
            $unPedido->setEstado("Listo");
            if (!$unPedido->isListo()){
                $unPedido->setEstado("Iniciado");
            }
            $this->em->persist($unPedido);
            if ($unPedido->descontar()) {
                $this->gestorMensajes->addMensaje('Hay productos con stock mínimo.', $usuario,2);
            }
            $this->em->flush();
        }
        return $faltantes;
    }

    public function editarPedido(Pedido $unPedido, $itemsOriginales, $idSolicitud, $usuario) {
        $pedido = $this->getPedido($unPedido->getId());
        foreach ($unPedido->getItemPedido() as $item) {
            $item->setPedido($unPedido);
        }
        $respuesta = $this->gestorSolicitudes->verRespuesta($idSolicitud);
        $faltantes = $unPedido->comprobarDisponibilidad();
        if (empty($faltantes) || $respuesta == true) {
            $faltantes = array();
            if ($unPedido->descontar()) {
                $this->gestorMensajes->addMensaje('Hay productos con stock mínimo.', $usuario, 2);
            }
            $unaTanda = $pedido->getTanda();
            /* @var $itemPedido ItemPedido */
            foreach ($unPedido->getItemPedido() as $itemPedido) { //Hago las asociaciones que no tiene hechas
                if ($itemPedido->getProductoVenta()->isMercaderia() && !($itemPedido->getEstado()=='Cancelado' || $itemPedido->getEstado()=='cancelado')) {
                        $itemPedido->setEstado("Listo");
                }
            }
            $unPedido->setEstado("Listo");
            if (!$unPedido->isListo()){
                $unPedido->setEstado("Actualizado");
            }
            /*if ($unaTanda != null) {
              $unaTanda->desvincularComanda($pedido);
              $this->em->flush($unaTanda);
              $unaTanda->vincularComanda($unPedido);
              $this->em->flush($unaTanda);
            }*/
            $this->em->flush();
        }
        return $faltantes;
    }

    public function despacharPedido($id) {
        $retorno = $this->getPedido($id)->despachar();
        if ($retorno) {
            $this->gestorMensajes->addMensaje('Ya esta listo el pedido para ' . $this->getPedido($id)->getDestino(), $this->getPedido($id)->getUsuario(), 1);
        }
        $this->em->flush();
        return $retorno;
    }

    public function cancelarPedido($id) {
        $unPedido = $this->getPedido($id);
        $unPedido->setEstado("Cancelado");
        if ($unPedido->getTanda() != null) {
            $unPedido->getTanda()->desvincularComanda($unPedido);
        }
        $unPedido->setMesa(null);
        $this->em->flush();
    }

    /**
     * @param \SisGG\FinalBundle\Entity\Pedido $unPedido
     */
    public function cambiarPedido(Pedido $unPedido) {
        if ($unPedido->getTipo() === 'Mostrador') {
            if ($unPedido->getMesa() != null) {
                $unPedido->getMesa()->setPedidoActivo(null);
                $unPedido->setMesa(null);
            }
        } elseif ($unPedido->getTipo() === 'Mesa') {
            $unPedido->getMesa()->setPedidoActivo($unPedido);
        } elseif ($unPedido->getTipo() === 'Delivery') {
            if ($unPedido->getMesa() != null) {
                $unPedido->getMesa()->setPedidoActivo(null);
                $unPedido->setMesa(null);
            }
        }
        $this->em->flush($unPedido);
    }

}

?>
