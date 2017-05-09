<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class CargoEmpleado extends Cargo implements Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CargoSistema")
     * @ORM\JoinColumn(name="cargoSistema", referencedColumnName="id" )
     * @Gedmo\Versioned
     */
    private $cargoSistema;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity="PersonaEmpleado")
     * @ORM\JoinColumn(name="empleado", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $empleado;
    /**
     * @return string
     */
    public function serialize() {
        return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data) {
        $this->id = unserialize($data);
    }
    public function __toString() {
        return $this->getNombre();
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
     * Set cargoSistema
     *
     * @param \SisGG\FinalBundle\Entity\CargoSistema $cargoSistema
     * @return CargoEmpleado
     */
    public function setCargoSistema(\SisGG\FinalBundle\Entity\CargoSistema $cargoSistema = null)
    {
        $this->cargoSistema = $cargoSistema;
    
        return $this;
    }

    /**
     * Get cargoSistema
     *
     * @return \SisGG\FinalBundle\Entity\CargoSistema 
     */
    public function getCargoSistema()
    {
        return $this->cargoSistema;
    }

    /**
     * Set empleado
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $empleado
     * @return CargoEmpleado
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
     * Set activo
     *
     * @param boolean $activo
     * @return CargoEmpleado
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
}