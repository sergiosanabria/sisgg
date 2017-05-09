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
class ItemRecargoVenta implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Recargo",inversedBy="itemsRecargoVenta") 
     */
    private $recargo;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2)
     * @Gedmo\Versioned
     */
    private $tasaIva;
    
    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     * @Gedmo\Versioned
     */
    public $iva;
    
    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="itemsRecargo",cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $venta;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2)
     * @Gedmo\Versioned
     */
    private $totalRecargo;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2)
     * @Gedmo\Versioned
     */
    private $totalRecargoSinIva;

    /**
     *  @ORM\Column(type="string") 
     * @Gedmo\Versioned
     */
    private $detalle;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $porcentaje;
    
    
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
     * Set totalRecargo
     *
     * @param string $totalRecargo
     * @return ItemRecargoPedido
     */
    public function setTotalRecargo($totalRecargo)
    {
        $this->totalRecargo = $totalRecargo;
    
        return $this;
    }

    /**
     * Get totalRecargo
     *
     * @return string 
     */
    public function getTotalRecargo()
    {
        return $this->totalRecargo;
    }
    
    /**
     * Get totalRecargo
     *
     * @return string 
     */
    public function getTotalRecargoConIva()
    {
        return $this->totalRecargo;
    }
    
    /**
     * Get totalRecargo
     *
     * @return string 
     */
    public function getTotalRecargoSinIva()
    {
        return $this->totalRecargoSinIva;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     * @return ItemRecargoPedido
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
     * Set recargo
     *
     * @param \SisGG\FinalBundle\Entity\Recargo $recargo
     * @return ItemRecargoPedido
     */
    public function setRecargo(\SisGG\FinalBundle\Entity\Recargo $recargo = null)
    {
        $this->recargo = $recargo;
        $this->setDetalle($recargo->getDescripcion()!=null?$recargo->getDescripcion():$recargo->getNombre());
        $this->setTotalRecargo($recargo->getTipoPorcentaje()?$this->getVenta()->getSubtotal()*$recargo->getPorcentaje()/100 : $recargo->getImporte());
        $this->setTotalRecargoSinIva($this->getTotalRecargo() / ($recargo->getIva()->getTasa()/100+1));
        $this->setTasaIva($recargo->getIva()->getTasa());
        $this->setIva($recargo->getIva());
        return $this;
    }

    /**
     * Get recargo
     *
     * @return \SisGG\FinalBundle\Entity\Recargo 
     */
    public function getRecargo()
    {
        return $this->recargo;
    }


    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return ItemRecargoVenta
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
     * @return ItemRecargoVenta
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
     * Set tasaIva
     *
     * @param string $tasaIva
     * @return ItemRecargoVenta
     */
    public function setTasaIva($tasaIva)
    {
        $this->tasaIva = $tasaIva;
    
        return $this;
    }

    /**
     * Get tasaIva
     *
     * @return string 
     */
    public function getTasaIva()
    {
        return $this->tasaIva;
    }

    /**
     * Set totalRecargoSinIva
     *
     * @param string $totalRecargoSinIva
     * @return ItemRecargoVenta
     */
    public function setTotalRecargoSinIva($totalRecargoSinIva)
    {
        $this->totalRecargoSinIva = $totalRecargoSinIva;
    
        return $this;
    }

    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return ItemRecargoVenta
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva()
    {
        return $this->iva;
    }
}