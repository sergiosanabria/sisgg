<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use SisGG\FinalBundle\Entity\Pedido;
use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\ProductoVenta;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class ItemPedido implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $precioUnitario;
    
    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $descuento;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    private $precio;
    
    /**
     * @ORM\Column(type="float",nullable=true)
     * @Gedmo\Versioned
     */
    private $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @ORM\JoinColumn(name="tasa_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $tasa;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $consideraciones;

    /**
     * @ORM\ManyToOne(targetEntity="ProductoVenta")
     * @ORM\JoinColumn(name="productoventa_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $productoVenta;

    /**
     * @ORM\ManyToMany(targetEntity="Ingrediente")
     */
    private $ingredientes;

    /**
     * @ORM\ManyToOne(targetEntity="Pedido",inversedBy="itemPedido")
     * @Gedmo\Versioned
     */
    protected $pedido;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $estado;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Gedmo\Versioned
     */
    protected $fechaHora;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $vinculado;

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * Recibe como parametro los productos que son utilizados por los demas item del pedido
     * al que pertenece y las cantidades utilizadas.
     * Comprueba si el item puede ser elaborado, 
     * En el caso que pueda ser elaborado devuelve un array vacio
     * y actualiza los productosUtilizados
     * En el caso de que no pueda ser elaborado devuelve un array con lo siguiente
     *      El/los Producto/s con cantidades faltantes y la cantidad que podria llegar a elaborarse
     */
    public function comprobarDisponibilidad(&$productosUtilizados) {
        $cantidadAElaborar = $this->getTasa()->getValor() * $this->getCantidad();
        $retorno = $this->getProductoVenta()->comprobarDisponibilidad($productosUtilizados, $cantidadAElaborar, $this->getIngredientes());
        return $retorno;
    }

    public function descontar(&$superados) {
        $cantidadAElaborar = $this->getTasa()->getValor() * $this->getCantidad();
        $this->getProductoVenta()->descontar($cantidadAElaborar, $this->getIngredientes(), $superados);
    }

    /**
     * @param string $data
     */
    public function unserialize($data) {
        $this->id = unserialize($data);
    }

    /**
     * @return string
     */
    public function __toString() {
        $retorno = $this->getProductoVenta() . '';
        if ($this->getIngredientes()->count() > 0) {
            $retorno = $retorno . "(";
            foreach ($this->getIngredientes() as $ingrediente) {
                $retorno = $retorno . $ingrediente->getExclusion();
            }
            if ($this->getConsideraciones() != null) {
                $retorno = $retorno . '- Consideraciones:' . $this->getConsideraciones();
            }
            $retorno = $retorno . ")";
        }
        return $retorno;
    }

    public function sinConsideraciones() {
        $retorno = $this->getProductoVenta() . "(";
        foreach ($this->getIngredientes() as $ingrediente) {
            $retorno = $retorno . $ingrediente->getExclusion();
        }
        $retorno = $retorno . ")";
        return $retorno;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->precio = $this->getProductoVenta()->getPrecioVenta() * $this->getCantidad();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdateValue() {
        $this->precio = $this->getProductoVenta()->getPrecioVenta() * $this->getCantidad();
    }

    public function compararItem(ItemPedido $item) {
        $retorno = false;
        if ($this->getCantidad() == $item->getCantidad() && $this->getIngredientes() == $item->getIngredientes() && $this->getProductoVenta() == $item->getProductoVenta() && $this->getTasa() == $this->getTasa()) {
            $retorno = true;
        }
        return $retorno;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set precio
     *
     * @param float $precio
     * @return ItemPedido
     */
    public function setPrecio($precio) {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio() {
        return $this->precio;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return ItemPedido
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad() {
        return $this->cantidad;
    }

    /**
     * Set consideraciones
     *
     * @param string $consideraciones
     * @return ItemPedido
     */
    public function setConsideraciones($consideraciones) {
        $this->consideraciones = $consideraciones;

        return $this;
    }

    /**
     * Get consideraciones
     *
     * @return string 
     */
    public function getConsideraciones() {
        return $this->consideraciones;
    }

    /**
     * Set productoVenta
     *
     * @param SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     * @return ItemPedido
     */
    public function setProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta = null) {
        $this->productoVenta = $productoVenta;

        return $this;
    }

    /**
     * Get productoVenta
     *
     * @return SisGG\FinalBundle\Entity\ProductoVenta 
     */
    public function getProductoVenta() {
        return $this->productoVenta;
    }

    /**
     * Add ingredientes
     *
     * @param SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     * @return ItemPedido
     */
    public function addIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes) {
        $this->ingredientes[] = $ingredientes;

        return $this;
    }

    /**
     * Remove ingredientes
     *
     * @param SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     */
    public function removeIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes) {
        $this->ingredientes->removeElement($ingredientes);
    }

    /**
     * Get ingredientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getIngredientes() {
        return $this->ingredientes;
    }

    /**
     * Set pedido
     *
     * @param SisGG\FinalBundle\Entity\Pedido $pedido
     * @return ItemPedido
     */
    public function setPedido(\SisGG\FinalBundle\Entity\Pedido $pedido = null) {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return SisGG\FinalBundle\Entity\Pedido 
     */
    public function getPedido() {
        return $this->pedido;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return ItemPedido
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null) {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa() {
        return $this->tasa;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return ItemPedido
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
     * Set fechaHora
     *
     * @param \DateTime $fechaHora
     * @return ItemPedido
     */
    public function setFechaHora($fechaHora) {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    /**
     * Get fechaHora
     *
     * @return \DateTime 
     */
    public function getFechaHora() {
        return $this->fechaHora;
    }

    /**
     * Set vinculado
     *
     * @param boolean $vinculado
     * @return ItemPedido
     */
    public function setVinculado($vinculado) {
        $this->vinculado = $vinculado;

        return $this;
    }

    /**
     * Get vinculado
     *
     * @return boolean 
     */
    public function getVinculado() {
        return $this->vinculado;
    }


    /**
     * Set precioUnitario
     *
     * @param string $precioUnitario
     * @return ItemPedido
     */
    public function setPrecioUnitario($precioUnitario)
    {
        $this->precioUnitario = $precioUnitario;
    
        return $this;
    }

    /**
     * Get precioUnitario
     *
     * @return string 
     */
    public function getPrecioUnitario()
    {
        return $this->precioUnitario;
    }

    /**
     * Set descuento
     *
     * @param string $descuento
     * @return ItemPedido
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    
        return $this;
    }

    /**
     * Get descuento
     *
     * @return string 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }
}