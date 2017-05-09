<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Ciudad;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\Provincia;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Direccion implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Ciudad")
     * @ORM\JoinColumn(name="ciudad_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="Seleccione una Ciudad")
     * @Gedmo\Versioned
     */
    protected $ciudad;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese una calle")
     * @Gedmo\Versioned
     */ 
    protected $calle;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ingrese un número")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="Solo puede contener números")  
     * @Gedmo\Versioned
     */
    protected $numero;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $manzana;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $edificio;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $escalera;
    /**
     * @ORM\Column(type="string",nullable=true)     
     * @Gedmo\Versioned
     */
    protected $piso;
    /**
     * @ORM\Column(type="string",nullable=true)     
     * @Gedmo\Versioned
     */
    protected $departamento;
    
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
     * @return string
     */
    public function __toString() {
        $retorno = $this->getCiudad()->getNombre().'('.$this->getCiudad()->getProvincia()->getNombre().") ";
        if ($this->getCalle()!=null){
            $retorno = $retorno.''.$this->getCalle();
        }
        if ($this->getNumero()!=null){
            $retorno = $retorno.' '.$this->getNumero();
        }
        if ($this->getManzana()!=null){
            $retorno = $retorno.' - Mz:'.$this->getManzana();
        }
        if ($this->getEdificio()!=null){
            $retorno = $retorno.' - Edif:'.$this->getEdificio();
        }
        if ($this->getEscalera()!=null){
            $retorno = $retorno.' - Esc:'.$this->getEscalera();
        }
        if ($this->getPiso()!=null){
            $retorno = $retorno.' - Piso:'.$this->getPiso();
        }
        if ($this->getDepartamento()!=null){
            $retorno = $retorno.' - Dpto:'.$this->getDepartamento();
        }
        return $retorno;
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
     * Set calle
     *
     * @param string $calle
     * @return Direccion
     */
    public function setCalle($calle)
    {
        $this->calle = $calle;
    
        return $this;
    }

    /**
     * Get calle
     *
     * @return string 
     */
    public function getCalle()
    {
        return $this->calle;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Direccion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set manzana
     *
     * @param string $manzana
     * @return Direccion
     */
    public function setManzana($manzana)
    {
        $this->manzana = $manzana;
    
        return $this;
    }

    /**
     * Get manzana
     *
     * @return string 
     */
    public function getManzana()
    {
        return $this->manzana;
    }

    /**
     * Set edificio
     *
     * @param string $edificio
     * @return Direccion
     */
    public function setEdificio($edificio)
    {
        $this->edificio = $edificio;
    
        return $this;
    }

    /**
     * Get edificio
     *
     * @return string 
     */
    public function getEdificio()
    {
        return $this->edificio;
    }

    /**
     * Set escalera
     *
     * @param string $escalera
     * @return Direccion
     */
    public function setEscalera($escalera)
    {
        $this->escalera = $escalera;
    
        return $this;
    }

    /**
     * Get escalera
     *
     * @return string 
     */
    public function getEscalera()
    {
        return $this->escalera;
    }

    /**
     * Set piso
     *
     * @param string $piso
     * @return Direccion
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;
    
        return $this;
    }

    /**
     * Get piso
     *
     * @return string 
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     * @return Direccion
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return string 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set ciudad
     *
     * @param SisGG\FinalBundle\Entity\Ciudad $ciudad
     * @return Direccion
     */
    public function setCiudad(\SisGG\FinalBundle\Entity\Ciudad $ciudad = null)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return SisGG\FinalBundle\Entity\Ciudad 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }
}