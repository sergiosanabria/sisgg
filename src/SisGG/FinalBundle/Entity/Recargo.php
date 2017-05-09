<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;


/**
    * @ORM\Entity
    * @ORM\HasLifecycleCallbacks()
    * @Gedmo\Loggable
 */
class Recargo implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un nombre")
     *  @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener números")
     * @Gedmo\Versioned 
     */
    protected $nombre;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $porcentaje;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $tipoPorcentaje;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $tipoImporte;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $bonificacionImporte;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $estado;
    
    /**
     * @ORM\ManyToMany(targetEntity="TipoPedido",inversedBy="recargos")
     */
    protected $tiposPedidos;
    
    /**
     * @ORM\OneToMany(targetEntity="ItemRecargoVenta",mappedBy="recargo") 
     */
    private $itemsRecargoVenta;
    
    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     * @Gedmo\Versioned
     */
    protected $iva;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $ivaIncluido;
    
    /**
     * @return boolean
     */
    public function isActivo(){
        return $this->estado=="activo";
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
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
    }
 
    
    public function __toString() {
        return $this->getTipoImporte() == true ? $this->nombre : $this->nombre." (".number_format($this->porcentaje,2,'.','')."%)";
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
     * Set nombre
     *
     * @param string $nombre
     * @return Recargo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set porcentaje
     *
     * @param string $porcentaje
     * @return Recargo
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
     * Set importe
     *
     * @param string $importe
     * @return Recargo
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return string 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set tipoPorcentaje
     *
     * @param boolean $tipoPorcentaje
     * @return Recargo
     */
    public function setTipoPorcentaje($tipoPorcentaje)
    {
        $this->tipoPorcentaje = $tipoPorcentaje;
    
        return $this;
    }

    /**
     * Get tipoPorcentaje
     *
     * @return boolean 
     */
    public function getTipoPorcentaje()
    {
        return $this->tipoPorcentaje;
    }

    /**
     * Set tipoImporte
     *
     * @param boolean $tipoImporte
     * @return Recargo
     */
    public function setTipoImporte($tipoImporte)
    {
        $this->tipoImporte = $tipoImporte;
    
        return $this;
    }

    /**
     * Get tipoImporte
     *
     * @return boolean 
     */
    public function getTipoImporte()
    {
        return $this->tipoImporte;
    }

    /**
     * Set bonificacionImporte
     *
     * @param string $bonificacionImporte
     * @return Recargo
     */
    public function setBonificacionImporte($bonificacionImporte)
    {
        $this->bonificacionImporte = $bonificacionImporte;
    
        return $this;
    }

    /**
     * Get bonificacionImporte
     *
     * @return string 
     */
    public function getBonificacionImporte()
    {
        return $this->bonificacionImporte;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Recargo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Recargo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Add tiposPedidos
     *
     * @param \SisGG\FinalBundle\Entity\TipoPedido $tiposPedidos
     * @return Recargo
     */
    public function addTiposPedido(\SisGG\FinalBundle\Entity\TipoPedido $tiposPedidos)
    {
        $this->tiposPedidos[] = $tiposPedidos;
    
        return $this;
    }

    /**
     * Remove tiposPedidos
     *
     * @param \SisGG\FinalBundle\Entity\TipoPedido $tiposPedidos
     */
    public function removeTiposPedido(\SisGG\FinalBundle\Entity\TipoPedido $tiposPedidos)
    {
        $this->tiposPedidos->removeElement($tiposPedidos);
    }

    /**
     * Get tiposPedidos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTiposPedidos()
    {
        return $this->tiposPedidos;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tiposPedidos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    
    public function getTipo(){
        return $this->getTipoImporte()?"Importe":"Porcentual";
    }

    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return Recargo
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

    /**
     * Set ivaIncluido
     *
     * @param boolean $ivaIncluido
     * @return Recargo
     */
    public function setIvaIncluido($ivaIncluido)
    {
        $this->ivaIncluido = $ivaIncluido;
    
        return $this;
    }

    /**
     * Get ivaIncluido
     *
     * @return boolean 
     */
    public function getIvaIncluido()
    {
        return $this->ivaIncluido;
    }

    /**
     * Add itemsRecargoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta
     * @return Recargo
     */
    public function addItemsRecargoVenta(\SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta)
    {
        $this->itemsRecargoVenta[] = $itemsRecargoVenta;
    
        return $this;
    }

    /**
     * Remove itemsRecargoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta
     */
    public function removeItemsRecargoVenta(\SisGG\FinalBundle\Entity\ItemRecargoVenta $itemsRecargoVenta)
    {
        $this->itemsRecargoVenta->removeElement($itemsRecargoVenta);
    }

    /**
     * Get itemsRecargoVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsRecargoVenta()
    {
        return $this->itemsRecargoVenta;
    }
}