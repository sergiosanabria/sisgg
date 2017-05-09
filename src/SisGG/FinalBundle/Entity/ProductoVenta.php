<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Producto;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\Descuento;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ProductoVentaRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Gedmo\Loggable
 */
class ProductoVenta extends Producto implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaProductoVenta", inversedBy="productoVenta")
     */
    private $categoria;
    
    /**
     * @ORM\ManyToMany(targetEntity="DescuentoProductoVenta",mappedBy="productoVenta")
     */
    protected $descuentos;
    
    /**
     * @return boolean
     */
    public function isMercaderia() {
        return false;
    }

    /**
     * @return boolean
     */
    public function isPlato() {
        return false;
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

    public function __toString() {
        return $this->getNombre();
    }

    public function toArray() {
        $retorno = array();
        $retorno ['id'] = $this->getId();
        $retorno ['nombre'] = $this->getNombre();
        return $retorno;
    }

    /**
     * @var string $nombre
     */
    protected $nombre;

    /**
     * @return int
     */
    public function getCantidadDisponible() {
        return 0;
    }

    /**
     * Verifica si es posible realizar la cantidad especificada, en caso
     * de que no se pueda devuelve un array con los faltantes
     */
    public function verificarDisponibilidad(Tasa $tasa, $cantidad) {
        return array();
    }

    /**
     * Verifica si es posible realizar la cantidad especificada, en caso
     * de que no se pueda devuelve un array con los faltantes obligatorios
     */
    public function comprobarDisponibilidad(&$productosUtilizados, $cantidadAElaborar, $ingredientes) {
        return array();
    }

    /**
     * Descuenta del Stock
     */
    public function descontar($cantidadAElaborar, $ingredientes = null, &$superados) {
        return null;
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
     * Set nombre
     *
     * @param string $nombre
     * @return ProductoVenta
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set categoria
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoria
     * @return ProductoVenta
     */
    public function setCategoria(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoria = null) {
        $this->categoria = $categoria;
        return $this;
    }

    /**
     * Get categoria
     *
     * @return SisGG\FinalBundle\Entity\CategoriaProductoVenta 
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return ProductoVenta
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null) {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva() {
        return $this->iva;
    }

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return ProductoVenta
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null) {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return \SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa() {
        return $this->tasa;
    }

    public function actualizarMargen() {
        if ($this->getPrecioCosto() != 0.00 && $this->getPrecioCosto() != null) {
            $valor = (($this->getPrecioVenta() / $this->getPrecioCosto()) - 1) * 100;
            if ($valor >= 0.00)
                $this->setMargen($valor);
            else
                $this->setMargen(0);
            return false;
        }else {
            $valor = (($this->getPrecioVenta() / 0.01) - 1) * 100;
            if ($valor >= 0.00)
                $this->setMargen($valor);
            else
                $this->setMargen(0);
            return true;
        }
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    

    /**
     * Add descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     * @return ProductoVenta
     */
    public function addDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos[] = $descuentos;
    
        return $this;
    }

    /**
     * Remove descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     */
    public function removeDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos->removeElement($descuentos);
    }

    /**
     * Get descuentos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }
    
    public function getDescuentosActivos(){
        $retorno = array();
        /* @var $descuento Descuento*/
        foreach ($this->getDescuentos() as $descuento) {
            if ($descuento->isActivo()){
                $retorno[ ] = $descuento;
            }
        }
        foreach ($this->getCategoria()->getDescuentosActivos() as $descuento) {
            if (!in_array($descuento, $retorno)){
                $retorno[ ] = $descuento; 
            }
        }
        return $retorno;
    }
    
    public function getPorcentajeDescuento(){
        $retorno = 0.00;
        /* @var $descuento Descuento*/
        foreach ($this->getDescuentosActivos() as $descuento) {
             if ($descuento->isActivo()){
                $retorno = $retorno + $descuento->getPorcentaje();
             }   
        }
        return $retorno;
    }
}