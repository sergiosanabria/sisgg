<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="nombre",message="Ya existe un pais con este nombre")
 * @Gedmo\Loggable
 */
class Pais implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un nombre")
     */
    protected $nombre;
    /**
     * @ORM\OneToMany(targetEntity="Provincia",mappedBy="pais")
     */
    protected $provincia;
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
        $this->provincia = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * @return string Description
     */
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
     * @return Pais
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
     * Add provincia
     *
     * @param SisGG\FinalBundle\Entity\Provincia $provincia
     * @return Pais
     */
    public function addProvincia(\SisGG\FinalBundle\Entity\Provincia $provincia)
    {
        $this->provincia[] = $provincia;
    
        return $this;
    }

    /**
     * Remove provincia
     *
     * @param SisGG\FinalBundle\Entity\Provincia $provincia
     */
    public function removeProvincia(\SisGG\FinalBundle\Entity\Provincia $provincia)
    {
        $this->provincia->removeElement($provincia);
    }

    /**
     * Get provincia
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
}