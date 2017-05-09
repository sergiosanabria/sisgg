<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use SisGG\FinalBundle\Entity\ProductoProduccion;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class MateriaPrima extends ProductoProduccion implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $precioCosto;
     /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidad;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $superaMin;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidadMinima;
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="mercaderias")
     */
    protected $proveedor;
    /**
     * @ORM\ManyToOne(targetEntity="Tasa")
     * @ORM\JoinColumn(name="tasa_id", referencedColumnName="id")
     */
    protected $tasa;
    
    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     */
    protected $iva;

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

    public function getCantidadDisponible() {
        parent::getCantidadDisponible();
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
     * @param integer $cantidad
     * @return MateriaPrima
     */
    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad() {
        return $this->cantidad;
    }


    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return MateriaPrima
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
     * Set precioCosto
     *
     * @param float $precioCosto
     * @return MateriaPrima
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
     * Set cantidadDeseada
     *
     * @param float $cantidadDeseada
     * @return MateriaPrima
     */
    public function setCantidadDeseada($cantidadDeseada)
    {
        $this->cantidadDeseada = $cantidadDeseada;
    
        return $this;
    }

    /**
     * Get cantidadDeseada
     *
     * @return float 
     */
    public function getCantidadDeseada()
    {
        return $this->cantidadDeseada;
    }

    /**
     * Set superaMin
     *
     * @param boolean $superaMin
     * @return MateriaPrima
     */
    public function setSuperaMin($superaMin)
    {
        $this->superaMin = $superaMin;
    
        return $this;
    }

    /**
     * Get superaMin
     *
     * @return boolean 
     */
    public function getSuperaMin()
    {
        return $this->superaMin;
    }

    /**
     * Set cantidadMinima
     *
     * @param float $cantidadMinima
     * @return MateriaPrima
     */
    public function setCantidadMinima($cantidadMinima)
    {
        $this->cantidadMinima = $cantidadMinima;
    
        return $this;
    }

    /**
     * Get cantidadMinima
     *
     * @return float 
     */
    public function getCantidadMinima()
    {
        return $this->cantidadMinima;
    }

    /**
     * Set proveedor
     *
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return MateriaPrima
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return MateriaPrima
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
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return MateriaPrima
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva()
    {
        return $this->iva;
    }
    
      public function isMateriaPrima() {
        return true;
    }
    
    public function isSuperaMin(){
        if ($this->cantidad==null)
            $this->cantidad=0.00;
        if ($this->cantidadMinima==null)
            $this->cantidadMinima=0.00;
        if ($this->cantidadMinima > $this->cantidad){
            $this->superaMin=true;
            return true;
        }
        $this->superaMin=false;
        return false;
    }
}