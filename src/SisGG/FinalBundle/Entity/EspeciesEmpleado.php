<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class EspeciesEmpleado extends EgresoEmpleado implements Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ItemEspecies",mappedBy="especies", cascade="persist")
     */
    protected $item;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $descuento;

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

    public function isSueldoEmpleado() {
        return false;
    }

    public function isAdicionalEmpleado() {
        return false;
    }

    public function isCancelarEmpleado() {
        return false;
    }

    public function isEspeciesEmpleado() {
        return true;
    }

    public function isContadoEmpleado() {
        return false;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add item
     *
     * @param \SisGG\FinalBundle\Entity\ItemEspecies $item
     * @return EspeciesEmpleado
     */
    public function addItem(\SisGG\FinalBundle\Entity\ItemEspecies $item) {
        $this->item[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \SisGG\FinalBundle\Entity\ItemEspecies $item
     */
    public function removeItem(\SisGG\FinalBundle\Entity\ItemEspecies $item) {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItem() {
        return $this->item;
    }

    public function sumarItems(PersonaEmpleado $empleado) {
        if ($this->item == null) {
            return -1;
        }
        $total = 0.00;
        /*@var $value ItemEspecies */
        foreach ($this->item as $value) {
            $linea = 0.00;
            if ($value->getProducto()->getPrecioVenta()!=null){
                $value->setPrecioCosto($value->getProducto()->getPrecioVenta());
            }else{
                $value->setPrecioCosto(0.00);
            }
            $linea = $value->getCantidad() * $value->getProducto()->getPrecioVenta();
            $value->setTotal($linea);
            $total+=$linea;
            $value->setEspecies($this);
        }
        
        $desc=$empleado->getCargo()->getDescuento();
        $this->setDescuento($desc);
        $desc=($total*$desc/100);
        $this->setMonto($total-$desc);
    }

    /**
     * Set descuento
     *
     * @param string $descuento
     * @return EspeciesEmpleado
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    
        return $this;
    }

    /**
     * Get descuento
     *
     * @return string 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }
}