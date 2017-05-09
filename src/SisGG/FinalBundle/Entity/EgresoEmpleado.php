<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Producto;
/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 */
class EgresoEmpleado extends MovEmpleado implements \Serializable {
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
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
    public function isEgresoEmpleado() {
        return true;
    }
    public function isIngresoEmpleado() {
        return false;
    }
    
    public function isSueldoEmpleado() {
      return  false;
    }
    public function isAdicionalEmpleado() {
        return  false;
    }
    public function isCancelarEmpleado() {
        return  false;
    }
    public function isContadoEmpleado() {
        return  false;
    }
     public function isEspeciesEmpleado() {
        return false;
    }
}