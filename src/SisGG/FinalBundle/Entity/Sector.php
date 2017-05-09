<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="nombre", message="Ya existe un sector con este nombre")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 */
class Sector implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     */
    protected $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Mesa",mappedBy="sector",cascade={"persist"})
     * @ORM\OrderBy({"numero" = "ASC"})
     */
    protected $mesas;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;

    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    public function __toString() {
        return $this->nombre;
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
    public function unserialize($data) {
        $this->id = unserialize($data);
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->mesas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Sector
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Add mesas
     *
     * @param SisGG\FinalBundle\Entity\Mesa $mesas
     * @return Sector
     */
    public function addMesa(\SisGG\FinalBundle\Entity\Mesa $mesas) {
        $this->mesas[] = $mesas;

        return $this;
    }

    /**
     * Remove mesas
     *
     * @param SisGG\FinalBundle\Entity\Mesa $mesas
     */
    public function removeMesa(\SisGG\FinalBundle\Entity\Mesa $mesas) {
        $this->mesas->removeElement($mesas);
    }

    /**
     * Get mesas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMesas() {
        return $this->mesas;
    }


    /**
     * Set estado
     *
     * @param string $estado
     * @return Sector
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