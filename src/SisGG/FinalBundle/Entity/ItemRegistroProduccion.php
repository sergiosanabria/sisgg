<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @ORM\Table(name="itemRegistroProduccion")
 * @Gedmo\Loggable
 */
class ItemRegistroProduccion implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $cantidad;
    /**
     * @ORM\ManyToOne(targetEntity="ProductoProduccion")
     * @ORM\JoinColumn(name="producto", referencedColumnName="id", unique=false)
     * @Gedmo\Versioned
     */
     private $producto;
       /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @ORM\JoinColumn(name="tasa_id", referencedColumnName="id", unique=false)
    * @Gedmo\Versioned
     */
     private $tasa;
    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $utilizado;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $costo;
     /**
     * @ORM\ManyToOne(targetEntity="RegistroProduccion")
     * @ORM\JoinColumn(name="registroProduccion", referencedColumnName="id")
      * @Gedmo\Versioned
     */
     private $registroProduccion;
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
     * Set cantidad
     *
     * @param float $cantidad
     * @return ItemRegistroProduccion
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set utilizado
     *
     * @param boolean $utilizado
     * @return ItemRegistroProduccion
     */
    public function setUtilizado($utilizado)
    {
        $this->utilizado = $utilizado;
    
        return $this;
    }

    /**
     * Get utilizado
     *
     * @return boolean 
     */
    public function getUtilizado()
    {
        return $this->utilizado;
    }


  
    /**
     * Set costo
     *
     * @param float $costo
     * @return ItemRegistroProduccion
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;
    
        return $this;
    }

    /**
     * Get costo
     *
     * @return float 
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return ItemRegistroProduccion
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa()
    {
        return $this->tasa;
    }

    /**
     * Set registroProduccion
     *
     * @param SisGG\FinalBundle\Entity\RegistroProduccion $registroProduccion
     * @return ItemRegistroProduccion
     */
    public function setRegistroProduccion(\SisGG\FinalBundle\Entity\RegistroProduccion $registroProduccion = null)
    {
        $this->registroProduccion = $registroProduccion;
    
        return $this;
    }

    /**
     * Get registroProduccion
     *
     * @return SisGG\FinalBundle\Entity\RegistroProduccion 
     */
    public function getRegistroProduccion()
    {
        return $this->registroProduccion;
    }

    /**
     * Set producto
     *
     * @param SisGG\FinalBundle\Entity\ProductoProduccion $producto
     * @return ItemRegistroProduccion
     */
    public function setProducto(\SisGG\FinalBundle\Entity\ProductoProduccion $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return SisGG\FinalBundle\Entity\ProductoProduccion 
     */
    public function getProducto()
    {
        return $this->producto;
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
}