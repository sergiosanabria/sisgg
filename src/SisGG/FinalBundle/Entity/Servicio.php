<?php
namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="servicio")
 * @UniqueEntity(fields={"nombre","nombreEmpresa"}, message="Ya existe un servicio con este nombre.")
 * @Gedmo\Loggable
 */
class Servicio   {
     
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese el nombre.")
     * @Gedmo\Versioned
     */
    private $nombre;
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Ingrese el nombre de la empresa.")
     * @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre de la empresa no puede contener numeros.") 
     * @Gedmo\Versioned
     */
    private $nombreEmpresa;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\Regex(pattern="/^\d/",
     *     match=true,
     *     message="El CUIT solo puede contener números")  
     * @Gedmo\Versioned
     */
    protected $cuit;
    
     /**
     * @ORM\Column(type="text", nullable=true)
      * @Gedmo\Versioned
     */
    private $descripcion;
     /**
     * @ORM\Column(type="string", nullable=true)
      * @Gedmo\Versioned
     */
    private $codigo;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $activo;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="servicios")
     */
    private $empresa;

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
     * @return Servicio
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Servicio
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
     * Set codigo
     *
     * @param string $codigo
     * @return Servicio
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Servicio
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Servicio
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
     * Set nombreEmpresa
     *
     * @param string $nombreEmpresa
     * @return Servicio
     */
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;
    
        return $this;
    }

    /**
     * Get nombreEmpresa
     *
     * @return string 
     */
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return Servicio
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;
    
        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit()
    {
        return $this->cuit;
    }
    
    public function getNe() {
        return $this->nombre." ($this->nombreEmpresa)";
    }
    
    public function __toString() {
        return $this->nombre." ($this->nombreEmpresa)";
    }

}