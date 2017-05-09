<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasa")
 * @Gedmo\Loggable
 */
class Tasa implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    private $nombre;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $descripcion;
    /**
     * @ORM\Column(type="string")
     */
    private $abrev;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $valor;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pivote;
    /**
     * @ORM\ManyToOne(targetEntity="UnidadMedida",inversedBy="tasas")
     */
    private $um;
    /**
     * @ORM\OneToMany(targetEntity="ProductoVenta",mappedBy="tasa")
     */
    protected $productosVenta;
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
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
     * Set nombre
     *
     * @param string $nombre
     * @return Tasa
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Tasa
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
     * Set pivote
     *
     * @param boolean $pivote
     * @return Tasa
     */
    public function setPivote($pivote)
    {
        $this->pivote = $pivote;
    
        return $this;
    }

    /**
     * Get pivote
     *
     * @return boolean 
     */
    public function getPivote()
    {
        return $this->pivote;
    }

    /**
     * Set um
     *
     * @param SisGG\FinalBundle\Entity\UnidadMedida $um
     * @return Tasa
     */
    public function setUm(\SisGG\FinalBundle\Entity\UnidadMedida $um = null)
    {
        $this->um = $um;
    
        return $this;
    }

    /**
     * Get um
     *
     * @return SisGG\FinalBundle\Entity\UnidadMedida 
     */
    public function getUm()
    {
        return $this->um;
    }

    /**
     * Set valor
     *
     * @param float $valor
     * @return Tasa
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set abrev
     *
     * @param string $abrev
     * @return Tasa
     */
    public function setAbrev($abrev)
    {
        $this->abrev = $abrev;
    
        return $this;
    }

    /**
     * Get abrev
     *
     * @return string 
     */
    public function getAbrev()
    {
        return $this->abrev;
    }
    public function __toString() {
        
        return $this->nombre." (".$this->getValor()." ".$this->getUm()->obtenerPivote()->getAbrev().")";
    }
    
    
    public function perteneceAIngredientes($ingtes){
        foreach ($ingtes as $value) {
            if (($value->getTasa()==$this)){
                return true;
            }
        }
        return false;
    }
     public function perteneceAProductos($productos){
        foreach ($productos as $value) {
            if (($value->getTasa()==$this)){
                return true;
            }
        }
        return false;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productosVenta = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add productosVenta
     *
     * @param \SisGG\FinalBundle\Entity\ProductoVenta $productosVenta
     * @return Tasa
     */
    public function addProductosVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productosVenta)
    {
        $this->productosVenta[] = $productosVenta;
    
        return $this;
    }

    /**
     * Remove productosVenta
     *
     * @param \SisGG\FinalBundle\Entity\ProductoVenta $productosVenta
     */
    public function removeProductosVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productosVenta)
    {
        $this->productosVenta->removeElement($productosVenta);
    }

    /**
     * Get productosVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductosVenta()
    {
        return $this->productosVenta;
    }
}