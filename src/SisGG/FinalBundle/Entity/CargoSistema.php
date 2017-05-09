<?php

namespace SisGG\FinalBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class CargoSistema extends Cargo implements Serializable{
     
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @ORM\OneToMany(targetEntity="CargoEmpleado",mappedBy="cargoSistema")
     */
    private $cargos;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $activo;
    
    private $todos;
    private $primerPago;
     /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="cargosSistema")
     */
    private $empresa;
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
        $this->cargos = new ArrayCollection();
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
     * Add cargos
     *
     * @param \SisGG\FinalBundle\Entity\CargoEmpleado $cargos
     * @return CargoSistema
     */
    public function addCargo(\SisGG\FinalBundle\Entity\CargoEmpleado $cargos)
    {
        $this->cargos[] = $cargos;
    
        return $this;
    }

    /**
     * Remove cargos
     *
     * @param \SisGG\FinalBundle\Entity\CargoEmpleado $cargos
     */
    public function removeCargo(\SisGG\FinalBundle\Entity\CargoEmpleado $cargos)
    {
        $this->cargos->removeElement($cargos);
    }

    /**
     * Get cargos
     *
     * @return Collection 
     */
    public function getCargos()
    {
        return $this->cargos;
    }

    /**
     * Set empresa
     *
     * @param Empresa $empresa
     * @return CargoSistema
     */
    public function setEmpresa(Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
    
    public function getTodos() {
        return $this->todos;
    }

    public function setTodos($todos) {
        $this->todos = $todos;
    }
    function getCargo(){
        foreach ($this->cargos as $value) {
            if ($value->getActivo()){
                return $value;
            }
        }
        return null;
    }

    public function setPrimerPago( $primerPago) {
        $this->primerPago = $primerPago;
        return $this;
    }

    /**
     * Get primerPago
     *
     * @return DateTime 
     */
    public function getPrimerPago() {
        return $this->primerPago;
    }




    /**
     * Set activo
     *
     * @param boolean $activo
     * @return CargoSistema
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