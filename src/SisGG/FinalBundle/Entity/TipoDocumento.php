<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 */
class TipoDocumento implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $nombre;
    
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $abreviatura;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
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
     * Constructor
     */
    public function __construct()
    {
        $this->persona = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @return type
     */
    public function __toString() {
        return $this->abreviatura;
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
     * @return TipoDocumento
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
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return TipoDocumento
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;
    
        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return TipoDocumento
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
}