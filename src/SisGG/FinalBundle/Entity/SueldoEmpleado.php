<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class SueldoEmpleado extends IngresoEmpleado implements \Serializable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $inicio;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $fin;
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
    
    public function isSueldoEmpleado() {
        return  true;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return SueldoEmpleado
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;
    
        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return SueldoEmpleado
     */
    public function setFin($fin)
    {
        $this->fin = $fin;
    
        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }
}