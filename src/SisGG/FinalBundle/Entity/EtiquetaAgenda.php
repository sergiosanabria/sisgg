<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="etiqueta", message="Ya existe una etiqueta con este nombre.")
 * @UniqueEntity(fields="color", message="Este color ya fue asignado a una etiqueta.")
 */
class EtiquetaAgenda {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Ingrese el asunto.")
     */
    private $etiqueta;
    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\NotBlank(message="Ingrese el color.")
     */
    private $color;
     /**
     *
     * @ORM\ManyToOne (targetEntity="Usuario", inversedBy="etiquetas")
     */
    private $usuario;
    
    public function __toString() {
        return $this->etiqueta;
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
     * Set etiqueta
     *
     * @param string $etiqueta
     * @return EtiquetaAgenda
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;
    
        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return string 
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return EtiquetaAgenda
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return EtiquetaAgenda
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}