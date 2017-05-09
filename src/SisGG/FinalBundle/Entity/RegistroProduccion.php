<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @ORM\Table(name="registroProduccion")
 * @Gedmo\Loggable
 */
class RegistroProduccion implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
     /**
     * @ORM\ManyToOne(targetEntity="ProductoProduccion")
     * @ORM\JoinColumn(name="productoProduccion", referencedColumnName="id", unique=false)
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
     * @ORM\Column(type="text", nullable=true)
      * @Gedmo\Versioned
     */
    private $descripcion;
     
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $tipo;
     /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
      * @Gedmo\Versioned
     */
    private $cantidad;
     /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
      * @Gedmo\Versioned
     */
    private $costo;
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fecha;
    /**
     * @ORM\OneToMany(targetEntity="ItemRegistroProduccion",mappedBy="registroProduccion", cascade={"persist", "remove"})
     */
    protected $itemRegistroProduccion;
     /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="registrosProduccion")
     */
    
    private $empresa;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemRegistroProduccion = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * @return RegistroProduccion
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return RegistroProduccion
     */
    public function setFecha()
    {
        $this->fecha =  new \DateTime("now");;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set producto
     *
     * @param SisGG\FinalBundle\Entity\ProductoProduccion $producto
     * @return RegistroProduccion
     */
    public function setProducto(ProductoProduccion $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return SisGG\FinalBundle\Entity\PreElaborado 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Add itemRegistroProduccion
     *
     * @param SisGG\FinalBundle\Entity\ItemRegistroProduccion $itemRegistroProduccion
     * @return RegistroProduccion
     */
    public function addItemRegistroProduccion(\SisGG\FinalBundle\Entity\ItemRegistroProduccion $itemRegistroProduccion)
    {
        $this->itemRegistroProduccion[] = $itemRegistroProduccion;
    
        return $this;
    }

    /**
     * Remove itemRegistroProduccion
     *
     * @param SisGG\FinalBundle\Entity\ItemRegistroProduccion $itemRegistroProduccion
     */
    public function removeItemRegistroProduccion(\SisGG\FinalBundle\Entity\ItemRegistroProduccion $itemRegistroProduccion)
    {
        $this->itemRegistroProduccion->removeElement($itemRegistroProduccion);
    }

    /**
     * Get itemRegistroProduccion
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItemRegistroProduccion()
    {
        return $this->itemRegistroProduccion;
    }
    
    public function agregarItem(ProductoProduccion $ing, $cantidad, $costo,$tasa, $utilizado){
        $item = new ItemRegistroProduccion();
        $item->setCantidad($cantidad);
        $item->setUtilizado($utilizado);
        $item->setProducto($ing);
        $item->setRegistroProduccion($this);
        $item->setCosto($costo);
        $item->setTasa($tasa);
        $this->addItemRegistroProduccion($item);

    }

    /**
     * Set costo
     *
     * @param float $costo
     * @return RegistroProduccion
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
     * @return RegistroProduccion
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
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return RegistroProduccion
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return RegistroProduccion
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
     * Set tipo
     *
     * @param boolean $tipo
     * @return RegistroProduccion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return boolean 
     */
    public function getTipo()
    {
        return $this->tipo;
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