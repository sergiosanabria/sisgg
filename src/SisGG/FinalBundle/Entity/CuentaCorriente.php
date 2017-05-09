<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 * @UniqueEntity(fields="cliente", message="Este cliente tiene una cuenta corriente asociada.")
 * @UniqueEntity(fields="numeroCuenta", message="Este número de cuenta tiene una cuenta corriente asociada.")
 */
class CuentaCorriente implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $numeroCuenta;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $saldo;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $maximo;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $plazoGeneracionRecibos;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $diasEntreAvisos;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $estado;

    /**
     * @ORM\OneToMany(targetEntity="MovimientoCuentaCorriente",mappedBy="cuenta",cascade={"persist"})
     */
    private $movimientos;
    
    /**
     * @ORM\OneToOne(targetEntity="Cliente", inversedBy="cuenta")
     */
    protected $cliente;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $fechaCreacion;
    
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $fechaEliminacion;

    public function __toString() {
        return $this->getNumero();
    }

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
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
        $this->setFechaCreacion(new \DateTime('now'));
    }
    
    /**
     * @return string
     */
    public function getNumero() {
        return str_pad($this->numeroCuenta, 8, 0, STR_PAD_LEFT);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set saldo
     *
     * @param string $saldo
     * @return CuentaCorriente
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    
        return $this;
    }

    /**
     * Get saldo
     *
     * @return string 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Add movimientos
     *
     * @param \SisGG\FinalBundle\Entity\MovimientoCuentaCorriente $movimientos
     * @return CuentaCorriente
     */
    public function addMovimiento(\SisGG\FinalBundle\Entity\MovimientoCuentaCorriente $movimientos)
    {
        $this->movimientos[] = $movimientos;
    
        return $this;
    }

    /**
     * Remove movimientos
     *
     * @param \SisGG\FinalBundle\Entity\MovimientoCuentaCorriente $movimientos
     */
    public function removeMovimiento(\SisGG\FinalBundle\Entity\MovimientoCuentaCorriente $movimientos)
    {
        $this->movimientos->removeElement($movimientos);
    }

    /**
     * Get movimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientos()
    {
        return $this->movimientos;
    }

    /**
     * Set cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     * @return CuentaCorriente
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
    
        return $this;
    }

    /**
     * Get cliente
     *
     * @return \SisGG\FinalBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return CuentaCorriente
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return CuentaCorriente
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaEliminacion
     *
     * @param \DateTime $fechaEliminacion
     * @return CuentaCorriente
     */
    public function setFechaEliminacion($fechaEliminacion)
    {
        $this->fechaEliminacion = $fechaEliminacion;
    
        return $this;
    }

    /**
     * Get fechaEliminacion
     *
     * @return \DateTime 
     */
    public function getFechaEliminacion()
    {
        return $this->fechaEliminacion;
    }

    /**
     * Set maximo
     *
     * @param string $maximo
     * @return CuentaCorriente
     */
    public function setMaximo($maximo)
    {
        $this->maximo = $maximo;
    
        return $this;
    }

    /**
     * Get maximo
     *
     * @return string 
     */
    public function getMaximo()
    {
        return $this->maximo;
    }

    /**
     * Set plazoGeneracionRecibos
     *
     * @param integer $plazoGeneracionRecibos
     * @return CuentaCorriente
     */
    public function setPlazoGeneracionRecibos($plazoGeneracionRecibos)
    {
        $this->plazoGeneracionRecibos = $plazoGeneracionRecibos;
    
        return $this;
    }

    /**
     * Get plazoGeneracionRecibos
     *
     * @return integer 
     */
    public function getPlazoGeneracionRecibos()
    {
        return $this->plazoGeneracionRecibos;
    }

    /**
     * Set diasEntreAvisos
     *
     * @param integer $diasEntreAvisos
     * @return CuentaCorriente
     */
    public function setDiasEntreAvisos($diasEntreAvisos)
    {
        $this->diasEntreAvisos = $diasEntreAvisos;
    
        return $this;
    }

    /**
     * Get diasEntreAvisos
     *
     * @return integer 
     */
    public function getDiasEntreAvisos()
    {
        return $this->diasEntreAvisos;
    }

    /**
     * Set numeroCuenta
     *
     * @param integer $numeroCuenta
     * @return CuentaCorriente
     */
    public function setNumeroCuenta($numeroCuenta)
    {
        $this->numeroCuenta = $numeroCuenta;
    
        return $this;
    }

    /**
     * Get numeroCuenta
     *
     * @return integer 
     */
    public function getNumeroCuenta()
    {
        return $this->numeroCuenta;
    }
}