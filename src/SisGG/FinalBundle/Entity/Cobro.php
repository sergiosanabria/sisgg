<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @Gedmo\Loggable
 */
class Cobro implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="cobros")
     * @Gedmo\Versioned
     */
    protected $venta;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $aclaracion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro",inversedBy="cobros")
     */
    protected $tipoCobro;
    
    /**
     * @ORM\OneToMany(targetEntity="Valor",mappedBy="cobro",cascade={"persist"})
     */
    protected $valores;
    
    /**
     * @ORM\OneToOne(targetEntity="Cobro", inversedBy="unCobro",cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $unRecibo;
    
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

    public function __toString() {
        $retorno = '';
        if ($this->getVenta()!=null){
            $retorno = 'Factura '.$this->getVenta()->getTipoFactura()->getNomenclatura().' '.$this->getVenta()->getNumeroFactura();
        }elseif ($this->getMovimientoCuentaCorriente()!=null){
            $retorno = 'Recibo ';
        }
        /* @var $valor Valor */
        foreach ($this->getValores() as $valor) {
            $retorno = $retorno.' '. $valor->getCampo()->getNombre().':'.$valor->getValor();
        }
        return $retorno;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set importe
     *
     * @param float $importe
     * @return Cobro
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;
    
        return $this;
    }

    /**
     * Get importe
     *
     * @return float 
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set aclaracion
     *
     * @param string $aclaracion
     * @return Cobro
     */
    public function setAclaracion($aclaracion)
    {
        $this->aclaracion = $aclaracion;
    
        return $this;
    }

    /**
     * Get aclaracion
     *
     * @return string 
     */
    public function getAclaracion()
    {
        return $this->aclaracion;
    }

    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return Cobro
     */
    public function setVenta(\SisGG\FinalBundle\Entity\Venta $venta = null)
    {
        $this->venta = $venta;
    
        return $this;
    }

    /**
     * Get venta
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * Set tipoCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipoCobro
     * @return Cobro
     */
    public function setTipoCobro(\SisGG\FinalBundle\Entity\TipoCobro $tipoCobro = null)
    {
        $this->tipoCobro = $tipoCobro;
    
        return $this;
    }

    /**
     * Get tipoCobro
     *
     * @return \SisGG\FinalBundle\Entity\TipoCobro 
     */
    public function getTipoCobro()
    {
        return $this->tipoCobro;
    }

    /**
     * Add valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     * @return Cobro
     */
    public function addValore(\SisGG\FinalBundle\Entity\Valor $valores)
    {
        $this->valores[] = $valores;
    
        return $this;
    }

    /**
     * Remove valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     */
    public function removeValore(\SisGG\FinalBundle\Entity\Valor $valores)
    {
        $this->valores->removeElement($valores);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set movimientoCuentaCorriente
     *
     * @param \SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber $movimientoCuentaCorriente
     * @return Cobro
     */
    public function setMovimientoCuentaCorriente(\SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber $movimientoCuentaCorriente = null)
    {
        $this->movimientoCuentaCorriente = $movimientoCuentaCorriente;
    
        return $this;
    }

    /**
     * Get movimientoCuentaCorriente
     *
     * @return \SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber 
     */
    public function getMovimientoCuentaCorriente()
    {
        return $this->movimientoCuentaCorriente;
    }

    /**
     * Set unRecibo
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $unRecibo
     * @return Cobro
     */
    public function setUnRecibo(\SisGG\FinalBundle\Entity\Cobro $unRecibo = null)
    {
        $this->unRecibo = $unRecibo;
    
        return $this;
    }

    /**
     * Get unRecibo
     *
     * @return \SisGG\FinalBundle\Entity\Cobro 
     */
    public function getUnRecibo()
    {
        return $this->unRecibo;
    }
}