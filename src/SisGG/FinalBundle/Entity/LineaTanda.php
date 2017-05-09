<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
* @Gedmo\Loggable
 */
class LineaTanda implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="float",nullable=true)
     * @Gedmo\Versioned
     */
    private $cantidad;
    /**
     * @ORM\Column(type="float",nullable=true)
     * @Gedmo\Versioned 
     */
    private $cantidadElaborados;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $consideraciones;

    /**
     * @ORM\ManyToOne(targetEntity="ProductoVenta")
     * @Gedmo\Versioned
     */
    private $productoVenta;

    /**
     * @ORM\ManyToMany(targetEntity="Ingrediente")
     */
    private $ingredientes;

    /**
     * @ORM\ManyToOne(targetEntity="Tanda",inversedBy="lineasTanda")
     * @Gedmo\Versioned
     */
    protected $tanda;

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * @return string
     */
    public function __toString() {
        $retorno = $this->getProductoVenta() . "(";
        foreach ($this->getIngredientes() as $ingrediente) {
            $retorno = $retorno . $ingrediente->getExclusion();
        }
        if ($this->getConsideraciones() != null) {
            $retorno = $retorno . '- Consideraciones:' . $this->getConsideraciones();
        }
        $retorno = $retorno . ")";
        return $retorno;
    }
    /**
     * @param \SisGG\FinalBundle\Entity\ItemPedido $itemPedido
     * @return boolean
     */
    public function esIgual(ItemPedido $itemPedido) {
        $retorno = false;
        if ($this->getProductoVenta()==$itemPedido->getProductoVenta()&&$this->contieneIngredientes($itemPedido->getIngredientes())){
            $retorno = true;
        }
        return $retorno;
    }
    /**
     * @param integer $cantidad
     */
    public function registrarElaborados($cantidad){
        $this->cantidadElaborados = $this->cantidadElaborados +$cantidad;
    }
    
    /**
     * @return boolean
     */
    public function contieneIngredientes($ingredientes) {
        $retorno = false;
        if ($ingredientes->count()==$this->getIngredientes()->count()){
            $retorno = true;
            foreach ($ingredientes as $ingrediente) {
                if (!$this->getIngredientes()->contains($ingrediente)){
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }

    /**
     * @param string $data
     */
    public function unserialize($data) {
        $this->id = unserialize($data);
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
     * Set cantidad
     *
     * @param float $cantidad
     * @return LineaTanda
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
     * @return LineaTanda
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
     * @return LineaTanda
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
     * @return LineaTanda
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
     * Set tanda
     *
     * @param SisGG\FinalBundle\Entity\Tanda $tanda
     * @return LineaTanda
     */
    public function setTanda(\SisGG\FinalBundle\Entity\Tanda $tanda = null) {
        $this->tanda = $tanda;

        return $this;
    }

    /**
     * Get tanda
     *
     * @return SisGG\FinalBundle\Entity\Tanda 
     */
    public function getTanda() {
        return $this->tanda;
    }


    /**
     * Set cantidadElaborados
     *
     * @param float $cantidadElaborados
     * @return LineaTanda
     */
    public function setCantidadElaborados($cantidadElaborados)
    {
        $this->cantidadElaborados = $cantidadElaborados;
    
        return $this;
    }

    /**
     * Get cantidadElaborados
     *
     * @return float 
     */
    public function getCantidadElaborados()
    {
        return $this->cantidadElaborados;
    }
}