<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class LineaVenta implements \Serializable {

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
    protected $cantidad;

    /**
     * @ORM\ManyToOne(targetEntity="ProductoVenta")
     * @Gedmo\Versioned
     */
    protected $productoVenta;

    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     * @Gedmo\Versioned
     */
    public $ivaProductoVenta;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $tasaIva;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $precioUnitario;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $precioUnitarioSinIva;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @Gedmo\Versioned
     */
    public $tasa;

    /**
     * @Assert\NotBlank(message="Ingrese un número.")
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $bonificacion;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $precioNeto;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $precioNetoSinIva;

    /**
     * @ORM\Column(type="decimal",precision=10, scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $total;

    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="lineasVenta")
     * @Gedmo\Versioned
     */
    protected $venta;

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
        return $this->descripcion;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }

    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
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
     * @return LineaVenta
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
        $this->setPrecioNeto($this->getCantidad() * ($this->getPrecioUnitario() - ($this->getPrecioUnitario() * $this->getProductoVenta()->getPorcentajeDescuento() / 100)));
        $this->setPrecioNetoSinIva($this->getCantidad() * ($this->getPrecioUnitarioSinIva() - ($this->getPrecioUnitarioSinIva() * $this->getProductoVenta()->getPorcentajeDescuento() / 100)));

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
     * Set descripcion
     *
     * @param string $descripcion
     * @return LineaVenta
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set precioUnitario
     *
     * @param float $precioUnitario
     * @return LineaVenta
     */
    public function setPrecioUnitario($precioUnitario) {
        $this->precioUnitario = $precioUnitario;

        return $this;
    }

    /**
     * Get precioUnitario
     *
     * @return float 
     */
    public function getPrecioUnitario() {
        return $this->precioUnitario;
    }

    /**
     * Set neto
     *
     * @param float $neto
     * @return LineaVenta
     */
    public function setNeto($neto) {
        $this->neto = $neto;

        return $this;
    }

    /**
     * Get neto
     *
     * @return float 
     */
    public function getNeto() {
        return $this->neto;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return LineaVenta
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal() {
        return $this->cantidad * ($this->getPrecioUnitario() / ($this->getBonificacion() / 100 + 1));
    }

    /**
     * Set productoVenta
     *
     * @param \SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     * @return LineaVenta
     */
    public function setProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta = null) {
        $this->productoVenta = $productoVenta;
        $this->setPrecioUnitario($productoVenta->getPrecioVenta());
        $this->setPrecioUnitarioSinIva($productoVenta->getPrecioVenta() / ($productoVenta->getIva()->getTasa() / 100 + 1));
        $this->setIvaProductoVenta($productoVenta->getIva());
        $this->setTasaIva($productoVenta->getIva()->getTasa());
        $this->setBonificacion($productoVenta->getPorcentajeDescuento());
        $this->setDescripcion(($productoVenta->getDescripcion() != null ? $productoVenta->getDescripcion() : $productoVenta) . "
            " . $productoVenta->getIva()->getDescripcionItem());
        return $this;
    }

    /**
     * Get productoVenta
     *
     * @return \SisGG\FinalBundle\Entity\ProductoVenta 
     */
    public function getProductoVenta() {
        return $this->productoVenta;
    }

    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return LineaVenta
     */
    public function setVenta(\SisGG\FinalBundle\Entity\Venta $venta = null) {
        $this->venta = $venta;

        return $this;
    }

    /**
     * Get venta
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getVenta() {
        return $this->venta;
    }

    /**
     * Set bonificacion
     *
     * @param float $bonificacion
     * @return LineaVenta
     */
    public function setBonificacion($bonificacion) {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return float 
     */
    public function getBonificacion() {
        return $this->bonificacion;
    }

    /**
     * Set ivaProductoVenta
     *
     * @param \SisGG\FinalBundle\Entity\IVA $ivaProductoVenta
     * @return LineaVenta
     */
    public function setIvaProductoVenta(\SisGG\FinalBundle\Entity\IVA $ivaProductoVenta = null) {
        $this->ivaProductoVenta = $ivaProductoVenta;
        $this->setTasaIva($ivaProductoVenta->getTasa());
        return $this;
    }

    /**
     * Get ivaProductoVenta
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIvaProductoVenta() {
        return $this->ivaProductoVenta;
    }

    /**
     * Set pIVA
     *
     * @param string $pIVA
     * @return LineaVenta
     */
    public function setPIVA($pIVA) {
        $this->pIVA = $pIVA;

        return $this;
    }

    /**
     * Get pIVA
     *
     * @return string 
     */
    public function getPIVA() {
        return $this->pIVA;
    }

    /**
     * Set gIVA
     *
     * @param boolean $gIVA
     * @return LineaVenta
     */
    public function setGIVA($gIVA) {
        $this->gIVA = $gIVA;

        return $this;
    }

    /**
     * Get gIVA
     *
     * @return boolean 
     */
    public function getGIVA() {
        return $this->gIVA;
    }

    /**
     * Set tIVA
     *
     * @param string $tIVA
     * @return LineaVenta
     */
    public function setTIVA($tIVA) {
        $this->tIVA = $tIVA;

        return $this;
    }

    /**
     * Get tIVA
     *
     * @return string 
     */
    public function getTIVA() {
        return $this->tIVA;
    }

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return LineaVenta
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

    /**
     * Set tasaIva
     *
     * @param string $tasaIva
     * @return LineaVenta
     */
    public function setTasaIva($tasaIva) {
        $this->tasaIva = $tasaIva;

        return $this;
    }

    /**
     * Get tasaIva
     *
     * @return string 
     */
    public function getTasaIva() {
        return $this->tasaIva;
    }

    /**
     * Set precioNeto
     *
     * @param string $precioNeto
     * @return LineaVenta
     */
    public function setPrecioNeto($precioNeto) {
        $this->precioNeto = $precioNeto;

        return $this;
    }

    /**
     * Get precioNeto
     *
     * @return string 
     */
    public function getPrecioNeto() {
        return $this->precioNeto;
    }

    /**
     * Set precioUnitarioSinIva
     *
     * @param string $precioUnitarioSinIva
     * @return LineaVenta
     */
    public function setPrecioUnitarioSinIva($precioUnitarioSinIva) {
        $this->precioUnitarioSinIva = $precioUnitarioSinIva;

        return $this;
    }

    /**
     * Get precioUnitarioSinIva
     *
     * @return string 
     */
    public function getPrecioUnitarioSinIva() {
        return $this->precioUnitarioSinIva;
    }

    /**
     * Set precioNetoSinIva
     *
     * @param string $precioNetoSinIva
     * @return LineaVenta
     */
    public function setPrecioNetoSinIva($precioNetoSinIva) {
        $this->precioNetoSinIva = $precioNetoSinIva;

        return $this;
    }

    /**
     * Get precioNetoSinIva
     *
     * @return string 
     */
    public function getPrecioNetoSinIva() {
        return $this->precioNetoSinIva;
    }

}