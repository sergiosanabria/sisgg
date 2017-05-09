<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class CancelarEmpleado extends EgresoEmpleado implements Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function isSueldoEmpleado() {
        return false;
    }

    public function isAdicionalEmpleado() {
        return false;
    }

    public function isCancelarEmpleado() {
        return true;
    }

    public function isContadoEmpleado() {
        return false;
    }

    public function isEspeciesEmpleado() {
        return false;
    }

}