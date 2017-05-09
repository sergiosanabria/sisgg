<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_especies")
 * @Gedmo\Loggable
 */
class ItemEspecies {
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
     * @ORM\ManyToOne(targetEntity="Producto")
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
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $precioCosto;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="EspeciesEmpleado")
     * @ORM\JoinColumn(name="especies", referencedColumnName="id")
     */
     private $especies;

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
     * Set cantidad
     *
     * @param float $cantidad
     * @return ItemEspecies
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
     * Set precioCosto
     *
     * @param float $precioCosto
     * @return ItemEspecies
     */
    public function setPrecioCosto($precioCosto)
    {
        $this->precioCosto = $precioCosto;
    
        return $this;
    }

    /**
     * Get precioCosto
     *
     * @return float 
     */
    public function getPrecioCosto()
    {
        return $this->precioCosto;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return ItemEspecies
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set producto
     *
     * @param \SisGG\FinalBundle\Entity\Producto $producto
     * @return ItemEspecies
     */
    public function setProducto(\SisGG\FinalBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return \SisGG\FinalBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return ItemEspecies
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return \SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa()
    {
        return $this->tasa;
    }

 

    /**
     * Set especies
     *
     * @param \SisGG\FinalBundle\Entity\EspeciesEmpleado $especies
     * @return ItemEspecies
     */
    public function setEspecies(\SisGG\FinalBundle\Entity\EspeciesEmpleado $especies = null)
    {
        $this->especies = $especies;
    
        return $this;
    }

    /**
     * Get especies
     *
     * @return \SisGG\FinalBundle\Entity\EspeciesEmpleado 
     */
    public function getEspecies()
    {
        return $this->especies;
    }
}