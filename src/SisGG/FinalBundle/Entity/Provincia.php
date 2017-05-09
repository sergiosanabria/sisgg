<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @UniqueEntity(fields="nombre", message="Ya existe una provincia con este nombre.")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Provincia implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string",length=60,unique=true)
     */
    protected $nombre;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $estado;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $porDefecto;
    
    /**
     * @ORM\OneToMany(targetEntity="Ciudad",mappedBy="provincia")
     * @ORM\OrderBy({"nombre" = "DESC"})
     */
    protected $ciudad;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="provincias")
     */
    
    private $empresa;
    /**
     * 
     * @return string
     */
    public function __toString() {
        return $this->nombre;
    }
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
    }
    
    /**
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ciudad = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Provincia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Provincia
     */
    public function setId($id)
    {
        $this->id = $id;
    
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
     * Add ciudad
     *
     * @param SisGG\FinalBundle\Entity\Ciudad $ciudad
     * @return Provincia
     */
    public function addCiudad(\SisGG\FinalBundle\Entity\Ciudad $ciudad)
    {
        $this->ciudad[] = $ciudad;
    
        return $this;
    }

    /**
     * Remove ciudad
     *
     * @param SisGG\FinalBundle\Entity\Ciudad $ciudad
     */
    public function removeCiudad(\SisGG\FinalBundle\Entity\Ciudad $ciudad)
    {
        $this->ciudad->removeElement($ciudad);
    }

    /**
     * Get ciudad
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Provincia
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Provincia
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
     * Set porDefecto
     *
     * @param boolean $porDefecto
     * @return Provincia
     */
    public function setPorDefecto($porDefecto)
    {
        $this->porDefecto = $porDefecto;
    
        return $this;
    }

    /**
     * Get porDefecto
     *
     * @return boolean 
     */
    public function getPorDefecto()
    {
        return $this->porDefecto;
    }
}