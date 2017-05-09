<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\DescuentoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @UniqueEntity(fields="nombre", message="Ya existe un producto con este nombre. Ingrese otro.")
 * @Gedmo\Loggable
 */
class Descuento implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un nombre")
     *  @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener números")
     * @Gedmo\Versioned 
     */
    protected $nombre;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $porcentaje;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $estado;
    
    public function isActivo(){
        $retorno = false;
        if ($this->estado == 'activo')
            $retorno = true;
        return $retorno;
    }

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
     * @return Descuento
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
     * @return Descuento
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
     * Set importe
     *
     * @param string $importe
     * @return Descuento
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return string 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set tipoImporte
     *
     * @param boolean $tipoImporte
     * @return Descuento
     */
    public function setTipoImporte($tipoImporte)
    {
        $this->tipoImporte = $tipoImporte;
    
        return $this;
    }

    /**
     * Get tipoImporte
     *
     * @return boolean 
     */
    public function getTipoImporte()
    {
        return $this->tipoImporte;
    }

    /**
     * Set minimo
     *
     * @param string $minimo
     * @return Descuento
     */
    public function setMinimo($minimo)
    {
        $this->minimo = $minimo;
    
        return $this;
    }

    /**
     * Get minimo
     *
     * @return string 
     */
    public function getMinimo()
    {
        return $this->minimo;
    }

    /**
     * Set maximo
     *
     * @param string $maximo
     * @return Descuento
     */
    public function setMaximo($maximo)
    {
        $this->maximo = $maximo;
    
        return $this;
    }

    /**
     * Get maximo
     *
     * @return string 
     */
    public function getMaximo()
    {
        return $this->maximo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Descuento
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
     * @return Descuento
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
     * Set tipoPorcentaje
     *
     * @param boolean $tipoPorcentaje
     * @return Descuento
     */
    public function setTipoPorcentaje($tipoPorcentaje)
    {
        $this->tipoPorcentaje = $tipoPorcentaje;
    
        return $this;
    }

    /**
     * Get tipoPorcentaje
     *
     * @return boolean 
     */
    public function getTipoPorcentaje()
    {
        return $this->tipoPorcentaje;
    }
}