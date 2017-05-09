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
 * @ORM\Table(name="cuenta_empleado")
 * @Gedmo\Loggable
 */
class CuentaEmpleado {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $ultimoLote;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $pendiente;

    /**
     * @Assert\Range(
     *      min = 0,
     *      invalidMessage = "El saldo debe ser superior o igual a 0 (cero)."
     * )
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $saldo;

    /**
     * @ORM\OneToMany(targetEntity="MovEmpleado",mappedBy="cuenta", cascade="persist")
     */
    private $movimientos;
     /**
     * @ORM\OneToOne(targetEntity="PersonaEmpleado", mappedBy="cuenta")
      * @Gedmo\Versioned
     */
    private $empleado;

    /**
     * Constructor
     */
    public function __construct() {
        $this->movimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ultimoLote
     *
     * @param \DateTime $ultimoLote
     * @return CuentaEmpleado
     */
    public function setUltimoLote($ultimoLote) {
        $this->ultimoLote = $ultimoLote;

        return $this;
    }

    /**
     * Get ultimoLote
     *
     * @return \DateTime 
     */
    public function getUltimoLote() {
        return $this->ultimoLote;
    }

    /**
     * Add movimientos
     *
     * @param \SisGG\FinalBundle\Entity\MovEmpleado $movimientos
     * @return CuentaEmpleado
     */
    public function addMovimiento(\SisGG\FinalBundle\Entity\MovEmpleado $movimientos) {
        $this->movimientos[] = $movimientos;

        return $this;
    }

    /**
     * Remove movimientos
     *
     * @param \SisGG\FinalBundle\Entity\MovEmpleado $movimientos
     */
    public function removeMovimiento(\SisGG\FinalBundle\Entity\MovEmpleado $movimientos) {
        $this->movimientos->removeElement($movimientos);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientos() {
        return $this->movimientos;
    }

    /**
     * Set pendiente
     *
     * @param boolean $pendiente
     * @return CuentaEmpleado
     */
    public function setPendiente($pendiente) {
        $this->pendiente = $pendiente;

        return $this;
    }

    /**
     * Get pendiente
     *
     * @return boolean 
     */
    public function getPendiente() {
        return $this->pendiente;
    }

    public function existeFechaSueldo(\DateTime $fecha) {
        foreach ($this->movimientos as $value) {
            if ($value->isSueldoEmpleado() && $value->getFecha() == $fecha) {
                return true;
            }
        }
        return false;
    }

    public function existeFechaSueldoSuperior(\DateTime $fecha) {
        foreach ($this->movimientos as $value) {
            if ($value->isSueldoEmpleado() && $value->getFecha() >= $fecha) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     * @return CuentaEmpleado
     */
    public function setSaldo($saldo) {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo() {
        return $this->saldo;
    }
/*public function isEgresoEmpleado() {
        return false;
    }
    public function isIngresoEmpleado() {
        return false;
    }*/
    public function getHaber() {
        $total=0.00;
        foreach ($this->movimientos as $value) {
            if ($value->isIngresoEmpleado()){
                $total+=$value->getMonto();
            }
        }
        return $total;
    }
    
    public function getDebe() {
        $total=0.00;
        foreach ($this->movimientos as $value) {
            if ($value->isEgresoEmpleado()){
                $total+=$value->getMonto();
            }
        }
        return $total;
    }


    /**
     * Set empleado
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $empleado
     * @return CuentaEmpleado
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
}