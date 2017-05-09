<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields="nombre", message="Ya existe un tipo de pago con este nombre. Ingrese otro.")
 * @ORM\Entity
 * @ORM\Table(name="tipo_pago")
 */
class TipoPago implements\Serializable {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese el nombre.")
     */
    private $nombre;
     /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $atr1;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $atr2;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $atr3;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $atr4;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $atr5;
    
//TIPOS: 0=numerico, 1=numerico obligatotio, 2=string, 3= string obligatorio
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tipo1;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tipo2;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tipo3;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tipo4;/**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tipo5;/**
    
    
    
    
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="tipoPagos")
     */   
    private $empresa;
    
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
     * @return TipoPago
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

    public function __toString() {
        return $this->nombre;
    }

    
    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return TipoPago
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
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return TipoPago
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set atr1
     *
     * @param string $atr1
     * @return TipoPago
     */
    public function setAtr1($atr1)
    {
        $this->atr1 = $atr1;
    
        return $this;
    }

    /**
     * Get atr1
     *
     * @return string 
     */
    public function getAtr1()
    {
        return $this->atr1;
    }

    /**
     * Set atr2
     *
     * @param string $atr2
     * @return TipoPago
     */
    public function setAtr2($atr2)
    {
        $this->atr2 = $atr2;
    
        return $this;
    }

    /**
     * Get atr2
     *
     * @return string 
     */
    public function getAtr2()
    {
        return $this->atr2;
    }

    /**
     * Set atr3
     *
     * @param string $atr3
     * @return TipoPago
     */
    public function setAtr3($atr3)
    {
        $this->atr3 = $atr3;
    
        return $this;
    }

    /**
     * Get atr3
     *
     * @return string 
     */
    public function getAtr3()
    {
        return $this->atr3;
    }

    /**
     * Set atr4
     *
     * @param string $atr4
     * @return TipoPago
     */
    public function setAtr4($atr4)
    {
        $this->atr4 = $atr4;
    
        return $this;
    }

    /**
     * Get atr4
     *
     * @return string 
     */
    public function getAtr4()
    {
        return $this->atr4;
    }

    /**
     * Set atr5
     *
     * @param string $atr5
     * @return TipoPago
     */
    public function setAtr5($atr5)
    {
        $this->atr5 = $atr5;
    
        return $this;
    }

    /**
     * Get atr5
     *
     * @return string 
     */
    public function getAtr5()
    {
        return $this->atr5;
    }

    /**
     * Set tipo1
     *
     * @param integer $tipo1
     * @return TipoPago
     */
    public function setTipo1($tipo1)
    {
        $this->tipo1 = $tipo1;
    
        return $this;
    }

    /**
     * Get tipo1
     *
     * @return integer 
     */
    public function getTipo1()
    {
        return $this->tipo1;
    }

    /**
     * Set tipo2
     *
     * @param integer $tipo2
     * @return TipoPago
     */
    public function setTipo2($tipo2)
    {
        $this->tipo2 = $tipo2;
    
        return $this;
    }

    /**
     * Get tipo2
     *
     * @return integer 
     */
    public function getTipo2()
    {
        return $this->tipo2;
    }

    /**
     * Set tipo3
     *
     * @param integer $tipo3
     * @return TipoPago
     */
    public function setTipo3($tipo3)
    {
        $this->tipo3 = $tipo3;
    
        return $this;
    }

    /**
     * Get tipo3
     *
     * @return integer 
     */
    public function getTipo3()
    {
        return $this->tipo3;
    }

    /**
     * Set tipo4
     *
     * @param integer $tipo4
     * @return TipoPago
     */
    public function setTipo4($tipo4)
    {
        $this->tipo4 = $tipo4;
    
        return $this;
    }

    /**
     * Get tipo4
     *
     * @return integer 
     */
    public function getTipo4()
    {
        return $this->tipo4;
    }

    /**
     * Set tipo5
     *
     * @param integer $tipo5
     * @return TipoPago
     */
    public function setTipo5($tipo5)
    {
        $this->tipo5 = $tipo5;
    
        return $this;
    }

    /**
     * Get tipo5
     *
     * @return integer 
     */
    public function getTipo5()
    {
        return $this->tipo5;
    }
}