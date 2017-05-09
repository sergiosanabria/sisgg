<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Direccion;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @UniqueEntity(fields="dni", message="Ya existe una persona con este DNI.")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class Persona  implements \Serializable{
   /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un dni")
     * @Assert\Regex(pattern="/\d/",
     *     match=true,
     *     message="El dni solo puede contener números")  
     */
    private $dni;
    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un apellido")
     * @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El apellido no puede contener números") 
     */
    private $apellido;
    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese un nombre")
     *  @Assert\Regex(pattern="/\d/",
     *     match=false,
     *     message="El nombre no puede contener números") 
          */
    private $nombre;
    /**
     * @ORM\OneToOne(targetEntity="Direccion",cascade={"persist"})
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     */
    private $direccion;
    
    /**
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email valido."
     * )
     * @ORM\Column(type="string",nullable=true)
     */
    private $email;
    /**
     * 
     * @ORM\Column(type="boolean")
     */
    private $activo;
    /**
     * @ORM\OneToMany(targetEntity="Telefono",mappedBy="persona",cascade="persist")
     */
    private $telefonos;
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
     * Set dni
     *
     * @param integer $dni
     * @return Persona
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
        return $this;
    }

    /**
     * Get dni
     *
     * @return integer 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     * @return Persona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    
        return $this;
    }

    /**
     * Get apellido
     *
     * @return string 
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Persona
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
     * Set direccion
     *
     * @param string $direccion
     * @return Persona
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }
    
    

    /**
     * Get direccion
     *
     * @return SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->telefonos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add telefonos
     *
     * @param SisGG\FinalBundle\Entity\Telefono $telefonos
     * @return Persona
     */
    public function addTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos)
    {
        $this->telefonos[] = $telefonos;
    
        return $this;
    }

    /**
     * Remove telefonos
     *
     * @param SisGG\FinalBundle\Entity\Telefono $telefonos
     */
    public function removeTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos)
    {
        $this->telefonos->removeElement($telefonos);
    }

    /**
     * Get telefonos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTelefonos()
    {
        return $this->telefonos;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Persona
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Persona
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
}