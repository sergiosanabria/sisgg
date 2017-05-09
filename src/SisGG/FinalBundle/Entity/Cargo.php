<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @UniqueEntity(fields="nombre", message="Ya existe un cargo con este nombre.")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Gedmo\Loggable
 */
class Cargo implements \Serializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Ingrese un nombre para el cargo.")
     * @Gedmo\Versioned
     */
    private $nombre;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;
    /**
     * @Assert\NotBlank(message="Error al ingresar la forma de pago")
     * @Assert\Range(
     *      min = 0,
     *      max = 2,
     *      invalidMessage = "Error al ingresar la forma de pago."
     * )
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Type(type="integer", message="El valor {{ value }} no es un número.")
     * @Gedmo\Versioned
     */
    private $tipo;
    /**
     * @Assert\Range(
     *      min = 1,
     *      max = 31,
     *      invalidMessage = "El día debe ser entre el 1 y el 31."
     * )
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer", message="El valor {{ value }} no es un número.")
     * @Gedmo\Versioned
     */
    private $porDia;
    /**
     * @Assert\Range(
     *      min = 1,
     *      max = 28,
     *      invalidMessage = "El día debe ser entre el 1 y el 28."
     * )
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer", message="El valor {{ value }} no es un número.")
     * @Gedmo\Versioned
     */
    private $porFecha;
    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 6,
     *      invalidMessage = "Debe ser un entero de 0 a 6"
     * )
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer", message="El valor {{ value }} no es un número.")
     * @Gedmo\Versioned
     */
    private $porDiaSemana;
   
    /**
     * @Assert\Range(
     *      min = 0,
     *      invalidMessage = "El monto debe ser superior o igual a 0 (cero)."
     * )
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $monto;
    /**
     * @Assert\Range(
     *      min = 0,
     *      invalidMessage = "El monto debe ser superior o igual a 0 (cero)."
     * )
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $negativo;
    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      invalidMessage = "El monto debe ser superior o igual a 0 (cero)."
     * )
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $descuento;
    
     /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
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
     * @return Cargo
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
     * Set porDia
     *
     * @param integer $porDia
     * @return Cargo
     */
    public function setPorDia($porDia)
    {
        $this->porDia = $porDia;
    
        return $this;
    }

    /**
     * Get porDia
     *
     * @return integer 
     */
    public function getPorDia()
    {
        return $this->porDia;
    }

    /**
     * Set porFecha
     *
     * @param integer $porFecha
     * @return Cargo
     */
    public function setPorFecha($porFecha)
    {
        $this->porFecha = $porFecha;
    
        return $this;
    }

    /**
     * Get porFecha
     *
     * @return integer 
     */
    public function getPorFecha()
    {
        return $this->porFecha;
    }

    /**
     * Set porDiaSemana
     *
     * @param integer $porDiaSemana
     * @return Cargo
     */
    public function setPorDiaSemana($porDiaSemana)
    {
        $this->porDiaSemana = $porDiaSemana;
    
        return $this;
    }

    /**
     * Get porDiaSemana
     *
     * @return integer 
     */
    public function getPorDiaSemana()
    {
        return $this->porDiaSemana;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return Cargo
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set negativo
     *
     * @param float $negativo
     * @return Cargo
     */
    public function setNegativo($negativo)
    {
        $this->negativo = $negativo;
    
        return $this;
    }

    /**
     * Get negativo
     *
     * @return float 
     */
    public function getNegativo()
    {
        return $this->negativo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Cargo
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
     * Set tipo
     *
     * @param integer $tipo
     * @return Cargo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

   

    /**
     * Set descuento
     *
     * @param string $descuento
     * @return Cargo
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
    
    public function getDesc() {
        return "% ".$this->descuento;
    }
}