<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * Description of Usuario
 *
 * @author sergio
 * @ORM\Entity
 * @ORM\Table(name="mov_empleado")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Gedmo\Loggable
 */
class MovEmpleado implements \Serializable {
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
     *      invalidMessage = "El monto debe ser superior o igual a 0 (cero)."
     * )
      * @Assert\NotBlank(message="Ingrese un monto.")
     * @ORM\Column(type="decimal", scale=2, nullable=false)
     * @Gedmo\Versioned
     */
    private $monto;
    /**
     *
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=false)
     * @Gedmo\Versioned
     */
    private $fecha;
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $fechaEmision;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;
    /**
     * @ORM\ManyToOne(targetEntity="CuentaEmpleado")
     * @ORM\JoinColumn(name="cuenta", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $cuenta;
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
    public function getId()
    {
        return $this->id;
    }

   

    /**
     * Set monto
     *
     * @param float $monto
     * @return MovEmpleado
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return MovEmpleado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     * @return MovEmpleado
     */
    public function setFechaEmision()
    {
        $this->fechaEmision = new \DateTime('now');
    
        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime 
     */
    public function getFechaEmision()
    {
        return $this->fechaEmision;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return MovEmpleado
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
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaEmpleado $cuenta
     * @return MovEmpleado
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaEmpleado $cuenta = null)
    {
        $this->cuenta = $cuenta;
    
        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \SisGG\FinalBundle\Entity\CuentaEmpleado 
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }
    public function isEgresoEmpleado() {
        return false;
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