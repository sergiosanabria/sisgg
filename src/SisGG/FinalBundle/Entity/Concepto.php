<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ConceptoRepository")
 * @Gedmo\Loggable
 */
class Concepto implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned  
     */
    protected $nombre;
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned   
     */
    protected $descripcion;
    
    /**
     * @ORM\Column(type="string",nullable=true) 
     */
    protected $estado;
    
    /**
     * 0 = Salida
     * 1 = Entrada
     * @ORM\Column(type="boolean",nullable=true)
     */
    protected $tipo;
    
    /**
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="conceptos")
     */
    protected $empresa;

    
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
     * 
     * @return string
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
     * @return Concepto
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
     * @return Concepto
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
     * Set tipo
     *
     * @param boolean $tipo
     * @return Concepto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return boolean 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    public function getTipoConcepto(){
        return  $this->getTipo() ? "Entrada" : "Salida";
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Concepto
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return \SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Concepto
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