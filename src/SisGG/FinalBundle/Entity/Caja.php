<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\CajaRepository")
 * @Gedmo\Loggable 
 */
class Caja implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $puntoVentaA;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimaFacturaA;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $puntoVentaB;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimaFacturaB;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $puntoVentaC;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimaFacturaC;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $serieNotaCreditoA;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimoNumeroNotaCreditoA;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $serieNotaCreditoB;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimoNumeroNotaCreditoB;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $serieNotaCreditoC;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimoNumeroNotaCreditoC;
    
    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Gedmo\Versioned
     */
    protected $serieRecibo;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Gedmo\Versioned
     */
    protected $ultimoNumeroRecibo;

    /**
     * @ORM\Column(type="decimal", scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $minimoApertura;

    /**
     * @ORM\OneToMany(targetEntity="Lote",mappedBy="caja")
     * @ORM\OrderBy({"fechaApertura" = "DESC"})
     */
    protected $lotes;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $abierta;

    /**
     * @ORM\Column(type="decimal", scale=2,nullable=true)
     * @Gedmo\Versioned
     */
    protected $saldo;
    
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="cajas")
     * @Gedmo\Versioned
     */
    private $empresa;

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
     * Constructor
     */
    public function __construct() {
        $this->lotes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set minimoApertura
     *
     * @param float $minimoApertura
     * @return Caja
     */
    public function setMinimoApertura($minimoApertura) {
        $this->minimoApertura = $minimoApertura;

        return $this;
    }

    /**
     * Get minimoApertura
     *
     * @return float 
     */
    public function getMinimoApertura() {
        return $this->minimoApertura;
    }

    /**
     * Set abierta
     *
     * @param boolean $abierta
     * @return Caja
     */
    public function setAbierta($abierta) {
        $this->abierta = $abierta;

        return $this;
    }

    /**
     * Get abierta
     *
     * @return boolean 
     */
    public function getAbierta() {
        return $this->abierta;
    }

    /**
     * Add lotes
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lotes
     * @return Caja
     */
    public function addLote(\SisGG\FinalBundle\Entity\Lote $lotes) {
        $this->lotes[] = $lotes;

        return $this;
    }

    /**
     * Remove lotes
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lotes
     */
    public function removeLote(\SisGG\FinalBundle\Entity\Lote $lotes) {
        $this->lotes->removeElement($lotes);
    }

    /**
     * Get lotes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLotes() {
        return $this->lotes;
    }


    /**
     * Set saldo
     *
     * @param float $saldo
     * @return Caja
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    
        return $this;
    }

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set puntoVentaA
     *
     * @param integer $puntoVentaA
     * @return Caja
     */
    public function setPuntoVentaA($puntoVentaA)
    {
        $this->puntoVentaA = $puntoVentaA;
    
        return $this;
    }

    /**
     * Get puntoVentaA
     *
     * @return integer 
     */
    public function getPuntoVentaA()
    {
        return $this->puntoVentaA;
    }

    /**
     * Set ultimaFacturaA
     *
     * @param integer $ultimaFacturaA
     * @return Caja
     */
    public function setUltimaFacturaA($ultimaFacturaA)
    {
        $this->ultimaFacturaA = $ultimaFacturaA;
    
        return $this;
    }

    /**
     * Get ultimaFacturaA
     *
     * @return integer 
     */
    public function getUltimaFacturaA()
    {
        return $this->ultimaFacturaA;
    }

    /**
     * Set puntoVentaB
     *
     * @param integer $puntoVentaB
     * @return Caja
     */
    public function setPuntoVentaB($puntoVentaB)
    {
        $this->puntoVentaB = $puntoVentaB;
    
        return $this;
    }

    /**
     * Get puntoVentaB
     *
     * @return integer 
     */
    public function getPuntoVentaB()
    {
        return $this->puntoVentaB;
    }

    /**
     * Set ultimaFacturaB
     *
     * @param integer $ultimaFacturaB
     * @return Caja
     */
    public function setUltimaFacturaB($ultimaFacturaB)
    {
        $this->ultimaFacturaB = $ultimaFacturaB;
    
        return $this;
    }

    /**
     * Get ultimaFacturaB
     *
     * @return integer 
     */
    public function getUltimaFacturaB()
    {
        return $this->ultimaFacturaB;
    }

    /**
     * Set puntoVentaC
     *
     * @param integer $puntoVentaC
     * @return Caja
     */
    public function setPuntoVentaC($puntoVentaC)
    {
        $this->puntoVentaC = $puntoVentaC;
    
        return $this;
    }

    /**
     * Get puntoVentaC
     *
     * @return integer 
     */
    public function getPuntoVentaC()
    {
        return $this->puntoVentaC;
    }

    /**
     * Set ultimaFacturaC
     *
     * @param integer $ultimaFacturaC
     * @return Caja
     */
    public function setUltimaFacturaC($ultimaFacturaC)
    {
        $this->ultimaFacturaC = $ultimaFacturaC;
    
        return $this;
    }

    /**
     * Get ultimaFacturaC
     *
     * @return integer 
     */
    public function getUltimaFacturaC()
    {
        return $this->ultimaFacturaC;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Caja
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
     * Set serieNotaCreditoA
     *
     * @param integer $serieNotaCreditoA
     * @return Caja
     */
    public function setSerieNotaCreditoA($serieNotaCreditoA)
    {
        $this->serieNotaCreditoA = $serieNotaCreditoA;
    
        return $this;
    }

    /**
     * Get serieNotaCreditoA
     *
     * @return integer 
     */
    public function getSerieNotaCreditoA()
    {
        return $this->serieNotaCreditoA;
    }

    /**
     * Set ultimoNumeroNotaCreditoA
     *
     * @param integer $ultimoNumeroNotaCreditoA
     * @return Caja
     */
    public function setUltimoNumeroNotaCreditoA($ultimoNumeroNotaCreditoA)
    {
        $this->ultimoNumeroNotaCreditoA = $ultimoNumeroNotaCreditoA;
    
        return $this;
    }

    /**
     * Get ultimoNumeroNotaCreditoA
     *
     * @return integer 
     */
    public function getUltimoNumeroNotaCreditoA()
    {
        return $this->ultimoNumeroNotaCreditoA;
    }

    /**
     * Set serieNotaCreditoB
     *
     * @param integer $serieNotaCreditoB
     * @return Caja
     */
    public function setSerieNotaCreditoB($serieNotaCreditoB)
    {
        $this->serieNotaCreditoB = $serieNotaCreditoB;
    
        return $this;
    }

    /**
     * Get serieNotaCreditoB
     *
     * @return integer 
     */
    public function getSerieNotaCreditoB()
    {
        return $this->serieNotaCreditoB;
    }

    /**
     * Set ultimoNumeroNotaCreditoB
     *
     * @param integer $ultimoNumeroNotaCreditoB
     * @return Caja
     */
    public function setUltimoNumeroNotaCreditoB($ultimoNumeroNotaCreditoB)
    {
        $this->ultimoNumeroNotaCreditoB = $ultimoNumeroNotaCreditoB;
    
        return $this;
    }

    /**
     * Get ultimoNumeroNotaCreditoB
     *
     * @return integer 
     */
    public function getUltimoNumeroNotaCreditoB()
    {
        return $this->ultimoNumeroNotaCreditoB;
    }

    /**
     * Set serieNotaCreditoC
     *
     * @param integer $serieNotaCreditoC
     * @return Caja
     */
    public function setSerieNotaCreditoC($serieNotaCreditoC)
    {
        $this->serieNotaCreditoC = $serieNotaCreditoC;
    
        return $this;
    }

    /**
     * Get serieNotaCreditoC
     *
     * @return integer 
     */
    public function getSerieNotaCreditoC()
    {
        return $this->serieNotaCreditoC;
    }

    /**
     * Set ultimoNumeroNotaCreditoC
     *
     * @param integer $ultimoNumeroNotaCreditoC
     * @return Caja
     */
    public function setUltimoNumeroNotaCreditoC($ultimoNumeroNotaCreditoC)
    {
        $this->ultimoNumeroNotaCreditoC = $ultimoNumeroNotaCreditoC;
    
        return $this;
    }

    /**
     * Get ultimoNumeroNotaCreditoC
     *
     * @return integer 
     */
    public function getUltimoNumeroNotaCreditoC()
    {
        return $this->ultimoNumeroNotaCreditoC;
    }

    /**
     * Set serieRecibo
     *
     * @param integer $serieRecibo
     * @return Caja
     */
    public function setSerieRecibo($serieRecibo)
    {
        $this->serieRecibo = $serieRecibo;
    
        return $this;
    }

    /**
     * Get serieRecibo
     *
     * @return integer 
     */
    public function getSerieRecibo()
    {
        return $this->serieRecibo;
    }

    /**
     * Set ultimoNumeroRecibo
     *
     * @param integer $ultimoNumeroRecibo
     * @return Caja
     */
    public function setUltimoNumeroRecibo($ultimoNumeroRecibo)
    {
        $this->ultimoNumeroRecibo = $ultimoNumeroRecibo;
    
        return $this;
    }

    /**
     * Get ultimoNumeroRecibo
     *
     * @return integer 
     */
    public function getUltimoNumeroRecibo()
    {
        return $this->ultimoNumeroRecibo;
    }
}