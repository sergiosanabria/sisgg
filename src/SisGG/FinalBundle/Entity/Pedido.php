<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ItemPedido;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\TipoPedido;
use SisGG\FinalBundle\Entity\ItemRecargoPedido;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\PedidoRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Pedido implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="pedidos")
     * @Gedmo\Versioned
     */
    private $cliente;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Versioned  
     */
    private $fechapedido;

    /**
     * @ORM\OneToMany(targetEntity="ItemPedido",mappedBy="pedido",cascade="persist")
     */
    private $itemPedido;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="Empresa",inversedBy="pedidos")
     * @Gedmo\Versioned
     */
    private $empresa;

    /**
     * @ORM\ManyToOne(targetEntity="Tanda",inversedBy="comandas")
     * @Gedmo\Versioned
     */
    private $tanda;

    /**
     * @ORM\ManyToOne(targetEntity="RegistroEnvio",inversedBy="pedidos")
     * @Gedmo\Versioned
     */
    private $registroEnvio;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="pedidos")
     * @Gedmo\Versioned
     */
    private $usuario;

    /**
     * @ORM\OneToOne(targetEntity="Direccion",cascade="persist")
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $direccion;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $solicitante;

    /**
     * @ORM\OneToOne(targetEntity="Mesa", inversedBy="pedidoActivo")
     * @Gedmo\Versioned
     */
    private $mesa;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $motivoCancelacion;

    /**
     * @ORM\ManyToOne(targetEntity="Cocina",inversedBy="pedidos") 
     */
    private $cocina;

    /**
     * @ORM\ManyToOne(targetEntity="TipoPedido",inversedBy="pedidos")
     * @Gedmo\Versioned
     */
    private $tipoPedido;

    /**
     * @return boolean
     */
    public function isMostrador() {
        /* @var $tipoPedido TipoPedido */
        $tipoPedido = $this->tipoPedido;
        return $tipoPedido->isTipo("mostrador") || $tipoPedido->isTipo("Mostrador");
    }

    /**
     * @return boolean
     */
    public function isDelivery() {
        /* @var $tipoPedido TipoPedido */
        $tipoPedido = $this->tipoPedido;
        return $tipoPedido->isTipo("delivery") || $tipoPedido->isTipo("Delivery");
    }

    /**
     * @return boolean
     */
    public function isMesa() {
        /* @var $tipoPedido TipoPedido */
        $tipoPedido = $this->tipoPedido;
        return $tipoPedido->isTipo("Mesa") || $tipoPedido->isTipo("mesa");
    }

    /**
     * @return boolean
     */
    public function isListo() {
        /* @var $itemPedido ItemPedido */
        foreach ($this->getItemPedido() as $itemPedido) {
            if ($itemPedido->getEstado() != 'Listo' and $itemPedido->getEstado() != 'listo' and $itemPedido->getEstado() != 'cancelado' and $itemPedido->getEstado() != 'Cancelado' and $itemPedido->getEstado() != 'entregado' and $itemPedido->getEstado() != 'Entregado') {
                return false;
            }
        }
        return true;
    }

    public function getResumen() {
        $retorno = "";
        foreach ($this->getItemPedido() as $item) {
            $retorno = $retorno . $item->getCantidad() . " " . $item . "
";
        }
        return $retorno;
    }

    public function getTipo() {
        $retorno = null;
        if ($this->isMesa()) {
            $retorno = "Mesa";
        } elseif ($this->isDelivery()) {
            $retorno = "Delivery";
        } elseif ($this->isMostrador()) {
            $retorno = "Mostrador";
        }
        return $retorno;
    }

    /**
     * Por cada item que tiene asignado comprueba si puede ser elaborado
     * Si no puede especifica 1-El/los item/s que no se pueden elaborar 2- La cantidad que podria llegar a elaborarse
     * Nota: Debido a que los Productos utilizados en el caso de los platos pueden verse involucrado en mas de una linea
     * se deben ir acumulando las cantidades que se van utilizando a medida que se van evaluando los items  
     */
    public function comprobarDisponibilidad() {
        $productosUtilizados = array();
        $faltantes = array();
        foreach ($this->getItemPedido() as $item) {
            $faltante = $item->comprobarDisponibilidad($productosUtilizados);
            if (!empty($faltante)) {
                $faltantes[] = array("itemPedido" => $item, "ingredientes" => $faltante);
            }
        }
        return $faltantes;
    }

    /**
     * Descontar
     */
    public function descontar() {
        $superados = false;
        foreach ($this->getItemPedido() as $item) {
            if ($item->getEstado() != 'cancelado' && $item->getEstado() != 'Cancelado')
                $item->descontar($superados);
        }
        return $superados;
    }

    public function despachar() {
        $retorno = $this->getTanda()->despacharPedido($this);
        if ($retorno) {
            $this->setEstado("Listo");
            $this->setTanda(null);
        }
        return $retorno;
    }

    public function getDestino() {
        if ($this->isMostrador()) {
            return 'el cliente ' . $this->getSolicitante();
        } else if ($this->isDelivery()) {
            return 'el cliente ' . $this->getSolicitante() . '(Direccion: ' . $this->getDireccion() . ')';
        } else {
            return 'la Mesa ' . $this->getMesa() . ' del Sector ' . $this->getMesa()->getSector();
        }
        return null;
    }
    
    public function getIdentificacion() {
        if ($this->isMostrador()) {
            return $this->getSolicitante();
        } else if ($this->isDelivery()) {
            return $this->getSolicitante() . '(Direccion: ' . $this->getDireccion() . ')';
        } else {
            return $this->getMesa() . ', Sector ' . $this->getMesa()->getSector();
        }
        return null;
    }

    /**
     * @param \SisGG\FinalBundle\Entity\Mesa $mesa
     */
    public function cambiarMesa(Mesa $mesa) {
        $retorno = null;
        if ($mesa->getPedidoActivo() == null) {
            $this->getMesa()->setPedidoActivo(null);
            $this->setMesa($mesa);
            $mesa->setPedidoActivo($this);
            $mesa->setEstado($this->getMesa()->getEstado());
            $this->getMesa()->setEstado("Libre");
        } else {
            $retorno = 'La Mesa se encuentra ocupada.';
        }
        return $retorno;
    }

    public function __toString() {
        return "" . $this->id;
    }

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data) {
        $this->id = unserialize($data);
    }

    public function getPrecio() {
        $subtotal = $this->getSubtotal();
        $precio = $subtotal - $this->getTotalDescuentos($subtotal);
        return $precio;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->fechapedido = new \DateTime("now");
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set fechapedido
     *
     * @param \DateTime $fechapedido
     * @return Pedido
     */
    public function setFechapedido($fechapedido) {
        $this->fechapedido = $fechapedido;

        return $this;
    }

    /**
     * Get fechapedido
     *
     * @return \DateTime 
     */
    public function getFechapedido() {
        return $this->fechapedido;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Pedido
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set cliente
     *
     * @param SisGG\FinalBundle\Entity\Cliente $cliente
     * @return Pedido
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null) {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return SisGG\FinalBundle\Entity\Cliente 
     */
    public function getCliente() {
        return $this->cliente;
    }

    /**
     * Add itemPedido
     *
     * @param SisGG\FinalBundle\Entity\ItemPedido $itemPedido
     * @return Pedido
     */
    public function addItemPedido(\SisGG\FinalBundle\Entity\ItemPedido $itemPedido) {
        $this->itemPedido[] = $itemPedido;

        return $this;
    }

    /**
     * Remove itemPedido
     *
     * @param SisGG\FinalBundle\Entity\ItemPedido $itemPedido
     */
    public function removeItemPedido(\SisGG\FinalBundle\Entity\ItemPedido $itemPedido) {
        $this->itemPedido->removeElement($itemPedido);
    }

    /**
     * Get itemPedido
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItemPedido() {
        return $this->itemPedido;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Pedido
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * Set tanda
     *
     * @param SisGG\FinalBundle\Entity\Tanda $tanda
     * @return Pedido
     */
    public function setTanda(\SisGG\FinalBundle\Entity\Tanda $tanda = null) {
        $this->tanda = $tanda;

        return $this;
    }

    /**
     * Get tanda
     *
     * @return SisGG\FinalBundle\Entity\Tanda 
     */
    public function getTanda() {
        return $this->tanda;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Pedido
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set solicitante
     *
     * @param string $solicitante
     * @return Pedido
     */
    public function setSolicitante($solicitante) {
        $this->solicitante = $solicitante;

        return $this;
    }

    /**
     * Get solicitante
     *
     * @return string 
     */
    public function getSolicitante() {
        return $this->solicitante;
    }

    /**
     * Set direccion
     *
     * @param \SisGG\FinalBundle\Entity\Direccion $direccion
     * @return Pedido
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null) {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return \SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion() {
        return $this->direccion;
    }

    /**
     * Set mesa
     *
     * @param \SisGG\FinalBundle\Entity\Mesa $mesa
     * @return Pedido
     */
    public function setMesa(\SisGG\FinalBundle\Entity\Mesa $mesa = null) {
        $this->mesa = $mesa;

        return $this;
    }

    /**
     * Get mesa
     *
     * @return \SisGG\FinalBundle\Entity\Mesa 
     */
    public function getMesa() {
        return $this->mesa;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Pedido
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Set motivoCancelacion
     *
     * @param string $motivoCancelacion
     * @return Pedido
     */
    public function setMotivoCancelacion($motivoCancelacion) {
        $this->motivoCancelacion = $motivoCancelacion;

        return $this;
    }

    /**
     * Get motivoCancelacion
     *
     * @return string 
     */
    public function getMotivoCancelacion() {
        return $this->motivoCancelacion;
    }

    /**
     * Set cocina
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $cocina
     * @return Pedido
     */
    public function setCocina(\SisGG\FinalBundle\Entity\Pedido $cocina = null) {
        $this->cocina = $cocina;

        return $this;
    }

    /**
     * Get cocina
     *
     * @return \SisGG\FinalBundle\Entity\Pedido 
     */
    public function getCocina() {
        return $this->cocina;
    }

    /**
     * Set registroEnvio
     *
     * @param \SisGG\FinalBundle\Entity\RegistroEnvio $registroEnvio
     * @return Pedido
     */
    public function setRegistroEnvio(\SisGG\FinalBundle\Entity\RegistroEnvio $registroEnvio = null) {
        $this->registroEnvio = $registroEnvio;

        return $this;
    }

    /**
     * Get registroEnvio
     *
     * @return \SisGG\FinalBundle\Entity\RegistroEnvio 
     */
    public function getRegistroEnvio() {
        return $this->registroEnvio;
    }

    /**
     * Set tipoPedido
     *
     * @param \SisGG\FinalBundle\Entity\TipoPedido $tipoPedido
     * @return Pedido
     */
    public function setTipoPedido(\SisGG\FinalBundle\Entity\TipoPedido $tipoPedido = null) {
        $this->tipoPedido = $tipoPedido;

        return $this;
    }

    /**
     * Get tipoPedido
     *
     * @return \SisGG\FinalBundle\Entity\TipoPedido 
     */
    public function getTipoPedido() {
        return $this->tipoPedido;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->itemPedido = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getSubtotal() {
        $subtotal = 0.00;
        /* @var $itemPedido ItemPedido */
        foreach ($this->getItemPedido() as $itemPedido) {
            if ($itemPedido->getEstado() != 'Cancelado' and $itemPedido->getEstado() != 'cancelado')
                $subtotal = $subtotal + ($itemPedido->getPrecio() - ($itemPedido->getDescuento() * $itemPedido->getPrecio() / 100));
        }
        return $subtotal + $this->getTotalRecargos($subtotal);
    }

    public function getTotalRecargos($subtotal) {
        $totalRecargo = 0.00;
        /* @var $recargo Recargo */
        foreach ($this->getTipoPedido()->getRecargosActivos() as $recargo) {
            if ($recargo->getTipoPorcentaje()) {
                $totalRecargo = $subtotal * $recargo->getPorcentaje() / 100;
            } else {
                $totalRecargo = $recargo->getImporte();
            }
        }
        return $totalRecargo;
    }

    public function getTotalDescuentos($subtotal) {
        $totalDescuento = 0.00;
        /* @var $recargo Recargo */
        if ($this->getCliente() != null) {
            foreach ($this->getCliente()->getDescuentosActivos() as $descuento) {
                    $totalDescuento = $subtotal * ($descuento->getPorcentaje() / 100);
            }
        }
        return $totalDescuento;
    }

}