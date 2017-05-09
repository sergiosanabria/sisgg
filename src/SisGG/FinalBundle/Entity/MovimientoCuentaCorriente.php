<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\Loggable
 */
class MovimientoCuentaCorriente {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="CuentaCorriente",inversedBy="movimientos",cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $cuenta;

    /**
     * @ORM\Column(type="decimal",scale=2)
     * @Gedmo\Versioned
     */
    private $importe;
    
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $estado;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $descripcion;

    
    public function isDebe(){
        return false;
    }
    
    public function isHaber(){
        return false;
    }
    
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
        $this->setFecha(new \DateTime('now'));
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return MovimientoCuentaCorriente
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set importe
     *
     * @param string $importe
     * @return MovimientoCuentaCorriente
     */
    public function setImporte($importe) {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return string 
     */
    public function getImporte() {
        return $this->importe;
    }

    

    /**
     * Set cuenta
     *
     * @param \SisGG\FinalBundle\Entity\CuentaCorriente $cuenta
     * @return MovimientoCuentaCorriente
     */
    public function setCuenta(\SisGG\FinalBundle\Entity\CuentaCorriente $cuenta = null) {
        $this->cuenta = $cuenta;
        $cuenta->addMovimiento($this);
        
        return $this;
    }

    /**
     * Get cuenta
     *
     * @return \SisGG\FinalBundle\Entity\CuentaCorriente 
     */
    public function getCuenta() {
        return $this->cuenta;
    }


    /**
     * Set estado
     *
     * @param string $estado
     * @return MovimientoCuentaCorriente
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

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return MovimientoCuentaCorriente
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
}