<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="unidad_medida")
 */
class UnidadMedida implements \Serializable {

   /**
    * @var string $id
    *
    * @ORM\Column(name="id", type="string", length=15, nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="UUID")
    */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $nombre; 
    /**
     * @ORM\Column(type="text", nullable=true )
     */
    private $descripcion;   
    /**
     * @ORM\OneToMany(targetEntity="Tasa",mappedBy="um", cascade={"remove"})
     */    
    private $tasas;
     /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="ums")
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
        $this->tasas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return UnidadMedida
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return UnidadMedida
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Add tasas
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasas
     * @return UnidadMedida
     */
    public function addTasa(\SisGG\FinalBundle\Entity\Tasa $tasas)
    {
        $this->tasas[] = $tasas;
    
        return $this;
    }

    /**
     * Remove tasas
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasas
     */
    public function removeTasa(\SisGG\FinalBundle\Entity\Tasa $tasas)
    {
        $this->tasas->removeElement($tasas);
    }

    /**
     * Get tasas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTasas()
    {
        return $this->tasas;
    }
    
    public function obtenerPivote(){
        foreach ($this->getTasas() as $value) {
            if ($value->getPivote()){
                return $value;
            }
        }
        return null;
    }
    public function obtenerTasa($tasa){
        foreach ($this->getTasas() as $value) {
            if ($value==$tasa){
                return $value;
            }
        }
        return null;
    
    }
    

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return UnidadMedida
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}