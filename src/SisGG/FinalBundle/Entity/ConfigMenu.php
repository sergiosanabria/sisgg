<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @ORM\Entity
 */
class ConfigMenu implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    private $id;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $nombre;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $incluye;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $negrita;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $color;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $cursiva;
    
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

    public function __toString() {
        return $this->nombre;
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
     * @return ConfigMenu
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
     * Set incluye
     *
     * @param boolean $incluye
     * @return ConfigMenu
     */
    public function setIncluye($incluye)
    {
        $this->incluye = $incluye;
    
        return $this;
    }

    /**
     * Get incluye
     *
     * @return boolean 
     */
    public function getIncluye()
    {
        return $this->incluye;
    }

    /**
     * Set negrita
     *
     * @param boolean $negrita
     * @return ConfigMenu
     */
    public function setNegrita($negrita)
    {
        $this->negrita = $negrita;
    
        return $this;
    }

    /**
     * Get negrita
     *
     * @return boolean 
     */
    public function getNegrita()
    {
        return $this->negrita;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return ConfigMenu
     */
    public function setColor($color)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set cursiva
     *
     * @param boolean $cursiva
     * @return ConfigMenu
     */
    public function setCursiva($cursiva)
    {
        $this->cursiva = $cursiva;
    
        return $this;
    }

    /**
     * Get cursiva
     *
     * @return boolean 
     */
    public function getCursiva()
    {
        return $this->cursiva;
    }
}