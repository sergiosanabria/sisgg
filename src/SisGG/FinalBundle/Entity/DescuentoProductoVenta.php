<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 * @UniqueEntity(fields="nombre", message="Ya existe un descuento con este nombre. Ingrese otro.")
 */
class DescuentoProductoVenta extends Descuento implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="ProductoVenta",inversedBy="descuentos")
     */
    protected $productoVenta;
    
    /**
     * @ORM\ManyToMany(targetEntity="CategoriaProductoVenta",inversedBy="descuentos")
     */
    protected $categoriaProductoVenta;

    public function __toString() {
        return $this->nombre;
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

    
    /**
     * @var string
     */
    protected $nombre;

    /**
     * @var string
     */
    protected $porcentaje;

    /**
     * @var string
     */
    protected $descripcion;

    /**
     * @var string
     */
    protected $estado;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productoVenta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categoriaProductoVenta = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return DescuentoProductoVenta
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
     * @return DescuentoProductoVenta
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return DescuentoProductoVenta
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
     * @return DescuentoProductoVenta
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
     * Add productoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     * @return DescuentoProductoVenta
     */
    public function addProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta)
    {
        $this->productoVenta[] = $productoVenta;
    
        return $this;
    }

    /**
     * Remove productoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     */
    public function removeProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta)
    {
        $this->productoVenta->removeElement($productoVenta);
    }

    /**
     * Get productoVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductoVenta()
    {
        return $this->productoVenta;
    }

    /**
     * Add categoriaProductoVenta
     *
     * @param \SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriaProductoVenta
     * @return DescuentoProductoVenta
     */
    public function addCategoriaProductoVenta(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriaProductoVenta)
    {
        $this->categoriaProductoVenta[] = $categoriaProductoVenta;
    
        return $this;
    }

    /**
     * Remove categoriaProductoVenta
     *
     * @param \SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriaProductoVenta
     */
    public function removeCategoriaProductoVenta(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriaProductoVenta)
    {
        $this->categoriaProductoVenta->removeElement($categoriaProductoVenta);
    }

    /**
     * Get categoriaProductoVenta
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategoriaProductoVenta()
    {
        return $this->categoriaProductoVenta;
    }
}