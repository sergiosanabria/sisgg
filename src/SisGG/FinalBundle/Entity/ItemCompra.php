<?php

namespace SisGG\FinalBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_compra")
 * @Gedmo\Loggable
 */
class ItemCompra {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

     /**
     * @Assert\NotBlank(message="Ingrese un número.")
     * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=3, nullable=true)
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
     * @Assert\NotBlank(message="Ingrese un número.")
     * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $precioCosto;
    /**
     * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    
    /**
     * @ORM\ManyToOne(targetEntity="Compra")
     * @ORM\JoinColumn(name="compra", referencedColumnName="id")
     * @Gedmo\Versioned
     */
     private $compra;
     /**
      * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $pIVA;
     /**
      * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gIVA;
     /**
      * 
      * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    private $tIVA;
     
     
     private $iva;
     /**
      * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=2, nullable=true)
      * @Gedmo\Versioned
     */
     private $bonificacion;
     private $descuento;

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
     * @return ItemCompra
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
     * @return ItemCompra
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
     * @return ItemCompra
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
     * @param SisGG\FinalBundle\Entity\Producto $producto
     * @return ItemCompra
     */
    public function setProducto(\SisGG\FinalBundle\Entity\Producto $producto = null)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return SisGG\FinalBundle\Entity\Producto 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return ItemCompra
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
     * Set compra
     *
     * @param SisGG\FinalBundle\Entity\Compra $compra
     * @return ItemCompra
     */
    public function setCompra(\SisGG\FinalBundle\Entity\Compra $compra = null)
    {
        $this->compra = $compra;
    
        return $this;
    }

    /**
     * Get compra
     *
     * @return SisGG\FinalBundle\Entity\Compra 
     */
    public function getCompra()
    {
        return $this->compra;
    }
    
    public function getIva() {
        return $this->iva;
    }

    public function setIva(IVA $iva) {
        $this->iva = $iva;
    }
    
    public function getDescuento() {
        return $this->descuento;
    }

    public function setDescuento( $descuento) {
        $this->descuento = $descuento;
    }

    public function isDescuentoValido() {
        if ($this->bonificacion==null)
            $this->bonificacion=0.00;
        if ($this->bonificacion>=0 && $this->bonificacion<=100 )
            return true;
        return false;
        
    }




    /**
     * Set pIVA
     *
     * @param float $pIVA
     * @return ItemCompra
     */
    public function setPIVA($pIVA)
    {
        $this->pIVA = $pIVA;
    
        return $this;
    }

    /**
     * Get pIVA
     *
     * @return float 
     */
    public function getPIVA()
    {
        return $this->pIVA;
    }

    /**
     * Set gIVA
     *
     * @param boolean $gIVA
     * @return ItemCompra
     */
    public function setGIVA($gIVA)
    {
        $this->gIVA = $gIVA;
    
        return $this;
    }

    /**
     * Get gIVA
     *
     * @return boolean 
     */
    public function getGIVA()
    {
        return $this->gIVA;
    }

    /**
     * Set tIVA
     *
     * @param float $tIVA
     * @return ItemCompra
     */
    public function setTIVA($tIVA)
    {
        $this->tIVA = $tIVA;
    
        return $this;
    }

    /**
     * Get tIVA
     *
     * @return float 
     */
    public function getTIVA()
    {
        return $this->tIVA;
    }

    /**
     * Set bonificacion
     *
     * @param float $bonificacion
     * @return ItemCompra
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;
    
        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return float 
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }
}