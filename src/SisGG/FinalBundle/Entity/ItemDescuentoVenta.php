<?php
namespace SisGG\FinalBundle\Entity;

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ItemPedido;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\TipoPedido;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class ItemDescuentoVenta implements \Serializable {
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="DescuentoCliente",inversedBy="itemsDescuentoVenta") 
     */
    private $descuento;

    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="itemsDescuento",cascade={"persist"})
     */
    private $venta;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $totalDescuento;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $totalDescuentoSinIva;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $porcentaje;
    
    

    /**
     *  @ORM\Column(type="string") 
     * @Gedmo\Versioned
     */
    private $detalle;
    
    public function __toString() {
        return "".$this->id;
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set totalDescuento
     *
     * @param string $totalDescuento
     * @return ItemDescuentoPedido
     */
    public function setTotalDescuento($totalDescuento)
    {
        $this->totalDescuento = $totalDescuento;
    
        return $this;
    }

    /**
     * Get totalDescuento
     *
     * @return string 
     */
    public function getTotalDescuento()
    {
        return $this->totalDescuento;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     * @return ItemDescuentoPedido
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;
    
        return $this;
    }

    /**
     * Get detalle
     *
     * @return string 
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set descuento
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoCliente $descuento
     * @return ItemDescuentoPedido
     */
    public function setDescuento(\SisGG\FinalBundle\Entity\DescuentoCliente $descuento = null)
    {
        $this->descuento = $descuento;
        $this->setDetalle($descuento->getDescripcion()!=null?$descuento->getDescripcion():$descuento->getNombre());
        $this->setPorcentaje($descuento->getPorcentaje());
        return $this;
    }
    
    public function calcularDescuentos($subtotalConIva,$subtotalSinIva){
        $this->setTotalDescuento($subtotalConIva - ($subtotalConIva * $this->getPorcentaje() /100));
        $this->setTotalDescuentoSinIva($subtotalSinIva - ($subtotalSinIva * $this->getPorcentaje() /100));
    }
    

    /**
     * Get descuento
     *
     * @return \SisGG\FinalBundle\Entity\DescuentoCliente 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set pedido
     *
     * @param \SisGG\FinalBundle\Entity\Pedido $pedido
     * @return ItemDescuentoPedido
     */
    public function setPedido(\SisGG\FinalBundle\Entity\Pedido $pedido = null)
    {
        $this->pedido = $pedido;
    
        return $this;
    }

    /**
     * Get pedido
     *
     * @return \SisGG\FinalBundle\Entity\Pedido 
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return ItemDescuentoVenta
     */
    public function setVenta(\SisGG\FinalBundle\Entity\Venta $venta = null)
    {
        $this->venta = $venta;
    
        return $this;
    }

    /**
     * Get venta
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * Set porcentaje
     *
     * @param string $porcentaje
     * @return ItemDescuentoVenta
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;
    
        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return string 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set totalDescuentoSinIva
     *
     * @param string $totalDescuentoSinIva
     * @return ItemDescuentoVenta
     */
    public function setTotalDescuentoSinIva($totalDescuentoSinIva)
    {
        $this->totalDescuentoSinIva = $totalDescuentoSinIva;
    
        return $this;
    }

    /**
     * Get totalDescuentoSinIva
     *
     * @return string 
     */
    public function getTotalDescuentoSinIva()
    {
        return $this->totalDescuentoSinIva;
    }
}