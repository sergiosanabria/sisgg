<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="item_nota_pedido")
 * @Gedmo\Loggable
 */
class ItemNotaPedido {

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
     * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $precioCosto;
    /**
     * @Assert\NotBlank(message="Ingrese un número.")
    * @Assert\Type(type="decimal", message="Este valor {{ value }} no es un tipo valido.")
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="NotaPedido")
     * @ORM\JoinColumn(name="notaPedido", referencedColumnName="id")
     * @Gedmo\Versioned
     */
     private $notaPedido;
   

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
     * @return ItemNotaPedido
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
     * @return ItemNotaPedido
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
     * Set producto
     *
     * @param SisGG\FinalBundle\Entity\Producto $producto
     * @return ItemNotaPedido
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
     * @return ItemNotaPedido
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
     * Set notaPedido
     *
     * @param SisGG\FinalBundle\Entity\NotaPedido $notaPedido
     * @return ItemNotaPedido
     */
    public function setNotaPedido(\SisGG\FinalBundle\Entity\NotaPedido $notaPedido = null)
    {
        $this->notaPedido = $notaPedido;
    
        return $this;
    }

    /**
     * Get notaPedido
     *
     * @return SisGG\FinalBundle\Entity\NotaPedido 
     */
    public function getNotaPedido()
    {
        return $this->notaPedido;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return ItemNotaPedido
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

   
}