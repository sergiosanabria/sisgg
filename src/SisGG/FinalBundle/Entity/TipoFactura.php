<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author martin
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class TipoFactura implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $nombre;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $nomenclatura;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $codigo;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    private $discriminarIva;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    private $notaCreditoAnulacion;

    /**
     * @ORM\OneToMany(targetEntity="Operacion",mappedBy="tipoFactura")
     */
    private $operaciones;
    
    /**
     * @ORM\OneToMany(targetEntity="Venta",mappedBy="tipoFactura")
     */
    private $ventas;

    public function __toString() {
        return $this->getNombre();
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
     * Constructor
     */
    public function __construct() {
        $this->operaciones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ventas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TipoFactura
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoFactura
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
     * Set nomenclatura
     *
     * @param string $nomenclatura
     * @return TipoFactura
     */
    public function setNomenclatura($nomenclatura) {
        $this->nomenclatura = $nomenclatura;

        return $this;
    }

    /**
     * Get nomenclatura
     *
     * @return string 
     */
    public function getNomenclatura() {
        return $this->nomenclatura;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return TipoFactura
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Add operaciones
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operaciones
     * @return TipoFactura
     */
    public function addOperacione(\SisGG\FinalBundle\Entity\Operacion $operaciones) {
        $this->operaciones[] = $operaciones;

        return $this;
    }

    /**
     * Remove operaciones
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operaciones
     */
    public function removeOperacione(\SisGG\FinalBundle\Entity\Operacion $operaciones) {
        $this->operaciones->removeElement($operaciones);
    }

    /**
     * Get operaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperaciones() {
        return $this->operaciones;
    }

    /**
     * Add ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     * @return TipoFactura
     */
    public function addVenta(\SisGG\FinalBundle\Entity\Venta $ventas) {
        $this->ventas[] = $ventas;

        return $this;
    }

    /**
     * Remove ventas
     *
     * @param \SisGG\FinalBundle\Entity\Venta $ventas
     */
    public function removeVenta(\SisGG\FinalBundle\Entity\Venta $ventas) {
        $this->ventas->removeElement($ventas);
    }

    /**
     * Get ventas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVentas() {
        return $this->ventas;
    }


    /**
     * Set discriminarIva
     *
     * @param boolean $discriminarIva
     * @return TipoFactura
     */
    public function setDiscriminarIva($discriminarIva)
    {
        $this->discriminarIva = $discriminarIva;
    
        return $this;
    }

    /**
     * Get discriminarIva
     *
     * @return boolean 
     */
    public function getDiscriminarIva()
    {
        return $this->discriminarIva;
    }

    /**
     * Set notaCreditoAnulacion
     *
     * @param boolean $notaCreditoAnulacion
     * @return TipoFactura
     */
    public function setNotaCreditoAnulacion($notaCreditoAnulacion)
    {
        $this->notaCreditoAnulacion = $notaCreditoAnulacion;
    
        return $this;
    }

    /**
     * Get notaCreditoAnulacion
     *
     * @return boolean 
     */
    public function getNotaCreditoAnulacion()
    {
        return $this->notaCreditoAnulacion;
    }
}