<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"nacional","caracteristica","numero"}, message="El telefono ingresado ya se encuentra registrado") 
 * @Gedmo\Loggable
 */
class Telefono implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string",length=4)
     * @Assert\NotBlank(message="Ingrese la caracteristica nacional")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="Solo puede contener números") 
     */
    protected $nacional;
    /**
     * @ORM\Column(type="integer",length=4)
     * @Assert\NotBlank(message="Ingrese la caracteristica")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="Solo puede contener números") 
     */
    protected $caracteristica;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ingrese el número")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="Solo puede contener números") 
     */
    protected $numero;
    /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="telefonos")
     */
    protected $cliente;
    
    /**
     * @ORM\ManyToOne(targetEntity="Persona",inversedBy="telefonos")
     */
    protected $persona;
    /**
     * @ORM\ManyToOne(targetEntity="PersonaEmpleado",inversedBy="telefonos")
     */
    protected $empleado;
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="telefonos")
     */
    protected $proveedor;
    /**
     * @ORM\ManyToOne(targetEntity="Empresa",inversedBy="telefonos")
     */
    protected $empresa;
    
    public function __toString() {
        return $this->nacional.'-'.$this->caracteristica.'-'.$this->numero;
    }
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
     * Set nacional
     *
     * @param string $nacional
     * @return Telefono
     */
    public function setNacional($nacional)
    {
        $this->nacional = $nacional;
    
        return $this;
    }

    /**
     * Get nacional
     *
     * @return string 
     */
    public function getNacional()
    {
        return $this->nacional;
    }

    /**
     * Set caracteristica
     *
     * @param integer $caracteristica
     * @return Telefono
     */
    public function setCaracteristica($caracteristica)
    {
        $this->caracteristica = $caracteristica;
    
        return $this;
    }

    /**
     * Get caracteristica
     *
     * @return integer 
     */
    public function getCaracteristica()
    {
        return $this->caracteristica;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Telefono
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
     * Set cliente
     *
     * @param SisGG\FinalBundle\Entity\Cliente $cliente
     * @return Telefono
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return SisGG\FinalBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set persona
     *
     * @param \SisGG\FinalBundle\Entity\Persona $persona
     * @return Telefono
     */
    public function setPersona(\SisGG\FinalBundle\Entity\Persona $persona = null)
    {
        $this->persona = $persona;
    
        return $this;
    }

    /**
     * Get persona
     *
     * @return \SisGG\FinalBundle\Entity\Persona 
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * Set empleado
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $empleado
     * @return Telefono
     */
    public function setEmpleado(\SisGG\FinalBundle\Entity\PersonaEmpleado $empleado = null)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return \SisGG\FinalBundle\Entity\PersonaEmpleado 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set proveedor
     *
     * @param \SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return Telefono
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Telefono
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
}