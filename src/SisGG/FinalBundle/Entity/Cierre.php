<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Movimiento;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\CierreRepository")
 * @Gedmo\Loggable 
 * @ORM\HasLifecycleCallbacks()
 */
class Cierre extends Movimiento implements \Serializable {

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
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $conformidad;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $extraerTotal;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;
    
    /**
     * @ORM\OneToMany(targetEntity="ItemCierre",mappedBy="cierre",cascade="persist")
     */
    protected $itemCierre;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro")
     * @Gedmo\Versioned
     */
    protected $tipo;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Gedmo\Versioned
     */
    protected $numeroCierre;
    
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
        $this->fecha = new \DateTime("now");
    }
    
    /**
     * @return string
     */
    public function getTipoMovimiento() {
        return 'Cierre';
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
     * @var \SisGG\FinalBundle\Entity\Lote
     */
    protected $lote;

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Cierre
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
     * @param float $importe
     * @return Cierre
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
     * @return Cierre
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Cierre
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
     * @return Cierre
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
     * Set tipo
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipo
     * @return Cierre
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
     * Set lote
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lote
     * @return Cierre
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
     * Constructor
     */
    public function __construct()
    {
        $this->itemCierre = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add itemCierre
     *
     * @param \SisGG\FinalBundle\Entity\ItemCierre $itemCierre
     * @return Cierre
     */
    public function addItemCierre(\SisGG\FinalBundle\Entity\ItemCierre $itemCierre)
    {
        $this->itemCierre[] = $itemCierre;
    
        return $this;
    }

    /**
     * Remove itemCierre
     *
     * @param \SisGG\FinalBundle\Entity\ItemCierre $itemCierre
     */
    public function removeItemCierre(\SisGG\FinalBundle\Entity\ItemCierre $itemCierre)
    {
        $this->itemCierre->removeElement($itemCierre);
    }

    /**
     * Get itemCierre
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemCierre()
    {
        return $this->itemCierre;
    }
    /**
     * @var \SisGG\FinalBundle\Entity\Concepto
     */
    protected $concepto;


    /**
     * Set concepto
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $concepto
     * @return Cierre
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

    /**
     * Set conformidad
     *
     * @param string $conformidad
     * @return Cierre
     */
    public function setConformidad($conformidad)
    {
        $this->conformidad = $conformidad;
    
        return $this;
    }

    /**
     * Get conformidad
     *
     * @return string 
     */
    public function getConformidad()
    {
        return $this->conformidad;
    }

    /**
     * Set numeroCierre
     *
     * @param integer $numeroCierre
     * @return Cierre
     */
    public function setNumeroCierre($numeroCierre)
    {
        $this->numeroCierre = $numeroCierre;
    
        return $this;
    }

    /**
     * Get numeroCierre
     *
     * @return integer 
     */
    public function getNumeroCierre()
    {
        return $this->numeroCierre;
    }

    /**
     * Set extraerTotal
     *
     * @param boolean $extraerTotal
     * @return Cierre
     */
    public function setExtraerTotal($extraerTotal)
    {
        $this->extraerTotal = $extraerTotal;
    
        return $this;
    }

    /**
     * Get extraerTotal
     *
     * @return boolean 
     */
    public function getExtraerTotal()
    {
        return $this->extraerTotal;
    }
}