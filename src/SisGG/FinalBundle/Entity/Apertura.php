<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Movimiento;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable 
 * @ORM\HasLifecycleCallbacks()
 */
class Apertura extends Movimiento implements \Serializable {

    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @Gedmo\Versioned
     */
    protected $usuario;

    /**
     * @ORM\ManyToOne(targetEntity="Caja")
     * @Gedmo\Versioned
     */
    protected $caja;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro")
     * @Gedmo\Versioned
     */
    protected $tipo;

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
     * @return string
     */
    public function getTipoMovimiento() {
        return 'Apertura';
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->fecha = new \DateTime("now");
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
     * @var float
     */
    protected $importe;

    /**
     * @var string
     */
    protected $aclaracion;

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Apertura
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Apertura
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Set caja
     *
     * @param \SisGG\FinalBundle\Entity\Caja $caja
     * @return Apertura
     */
    public function setCaja(\SisGG\FinalBundle\Entity\Caja $caja = null) {
        $this->caja = $caja;

        return $this;
    }

    /**
     * Get caja
     *
     * @return \SisGG\FinalBundle\Entity\Caja 
     */
    public function getCaja() {
        return $this->caja;
    }

    /**
     * @var \SisGG\FinalBundle\Entity\Lote
     */
    protected $lote;

    /**
     * Set lote
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lote
     * @return Apertura
     */
    public function setLote(\SisGG\FinalBundle\Entity\Lote $lote = null) {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return \SisGG\FinalBundle\Entity\Lote 
     */
    public function getLote() {
        return $this->lote;
    }

    /**
     * Set tipo
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipo
     * @return Apertura
     */
    public function setTipo(\SisGG\FinalBundle\Entity\TipoCobro $tipo = null) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \SisGG\FinalBundle\Entity\TipoCobro 
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * @var \SisGG\FinalBundle\Entity\Concepto
     */
    protected $concepto;


    /**
     * Set concepto
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $concepto
     * @return Apertura
     */
    public function setConcepto(\SisGG\FinalBundle\Entity\Concepto $concepto = null)
    {
        $this->concepto = $concepto;
    
        return $this;
    }

    /**
     * Get concepto
     *
     * @return \SisGG\FinalBundle\Entity\Concepto 
     */
    public function getConcepto()
    {
        return $this->concepto;
    }
}