<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\MovimientoRepository")
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 */
class Movimiento implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true)
     */
    protected $aclaracion;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Lote",inversedBy="movimientos")
     * @Gedmo\Versioned
     */
    protected $lote;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro")
     * @Gedmo\Versioned
     */
    protected $tipo;
    
    /**
     * @ORM\ManyToOne(targetEntity="Concepto")
     * @Gedmo\Versioned
     */
    protected $concepto;
    
    public $t;  
    

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
        $this->fecha = new \DateTime("now");
    }
    
    /**
     * @return string
     */
    public function getTipoMovimiento() {
        return 'Movimiento';
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
     * Set importe
     *
     * @param float $importe
     * @return Movimiento
     */
    public function setImporte($importe) {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float 
     */
    public function getImporte() {
        return $this->importe;
    }

    /**
     * Set aclaracion
     *
     * @param string $aclaracion
     * @return Movimiento
     */
    public function setAclaracion($aclaracion) {
        $this->aclaracion = $aclaracion;

        return $this;
    }

    /**
     * Get aclaracion
     *
     * @return string 
     */
    public function getAclaracion() {
        return $this->aclaracion;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Movimiento
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
     * Set concepto
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $concepto
     * @return Movimiento
     */
    public function setConcepto(\SisGG\FinalBundle\Entity\Concepto $concepto = null) {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return \SisGG\FinalBundle\Entity\Concepto 
     */
    public function getConcepto() {
        return $this->concepto;
    }

    /**
     * Set lote
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lote
     * @return Movimiento
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
     * @return Movimiento
     */
    public function setTipo(\SisGG\FinalBundle\Entity\TipoCobro $tipo = null)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return \SisGG\FinalBundle\Entity\TipoCobro 
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}