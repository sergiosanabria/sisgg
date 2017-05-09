<?php
namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="iva")
 * @UniqueEntity(fields={"tasa", "gravado"}, message="Ya existe una tasa con este porcentaje.")
 * @Gedmo\Loggable
 */
class IVA   {
     
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;
    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "La tasa debe ser mayor a 0 (cero).",
     *      maxMessage = "La tasa debe ser inferior a 100."
     * )
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank(message="Ingrese la tasa.")
     * @Gedmo\Versioned
     */
    private $tasa;
    private $nombre;
     /**
     * @ORM\Column(type="text", nullable=true)
      * @Gedmo\Versioned
     */
    private $descripcion;
     /**
      * @Assert\Choice(choices = {true, false}, message = "Tipo no válido.")
     * @ORM\Column(type="boolean")
      * @Gedmo\Versioned
     */
    private $gravado;
    /**
      * @Assert\Choice(choices = {true, false}, message = "Tipo no válido.")
     * @ORM\Column(type="boolean", nullable=true)
      * @Gedmo\Versioned
     */
    private $activo;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="ivas")
     */
    private $empresa;

    public function __toString() {
        if ($this->gravado)
            return $this->tasa.' % - Gravado';
        else
            return $this->tasa.' % - No gravado';
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
     * Set tasa
     *
     * @param float $tasa
     * @return IVA
     */
    public function setTasa($tasa)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return float 
     */
    public function getTasa()
    {
        return $this->tasa;
    }

    /**
     * Set gravado
     *
     * @param boolean $gravado
     * @return IVA
     */
    public function setGravado($gravado)
    {
        $this->gravado = $gravado;
    
        return $this;
    }

    /**
     * Get gravado
     *
     * @return boolean 
     */
    public function getGravado()
    {
        return $this->gravado;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return IVA
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return IVA
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
     * Set activo
     *
     * @param boolean $activo
     * @return IVA
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
    
      public function getDescripcionItem(){
        return "Alicuota de Iva ".$this;
    }

}