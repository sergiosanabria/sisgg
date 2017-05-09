<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Movimiento;
use \DateTime;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Salida extends Movimiento implements \Serializable
{
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
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;
    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro")
     * @Gedmo\Versioned
     */
    protected $tipo;
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;
    /**
     * @ORM\ManyToOne(targetEntity="Concepto")
     * @Gedmo\Versioned
     */
    protected $concepto;
    /**
     * @ORM\OneToOne(targetEntity="Pago", inversedBy="salida")
     * @Gedmo\Versioned
     **/
    protected $pago;
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
    }

    /**
     * @return string
     */
    public function getTipoMovimiento(){
        return 'Salida';
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
     * @var string
     */
    protected $aclaracion;

    /**
     * Set importe
     *
     * @param float $importe
     * @return Salida
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Salida
     */
    public function setFecha($fecha = null)
    {
        $this->fecha = new DateTime('now');
    
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
     * Set aclaracion
     *
     * @param string $aclaracion
     * @return Salida
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
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Salida
     */
    public function setUsuario(\SisGG\FinalBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set caja
     *
     * @param \SisGG\FinalBundle\Entity\Caja $caja
     * @return Salida
     */
    public function setCaja(\SisGG\FinalBundle\Entity\Caja $caja = null)
    {
        $this->caja = $caja;
    
        return $this;
    }

    /**
     * Get caja
     *
     * @return \SisGG\FinalBundle\Entity\Caja 
     */
    public function getCaja()
    {
        return $this->caja;
    }

    
    

    /**
     * Set concepto
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $concepto
     * @return Salida
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
     * @var \SisGG\FinalBundle\Entity\Lote
     */
    protected $lote;


    /**
     * Set lote
     *
     * @param \SisGG\FinalBundle\Entity\Lote $lote
     * @return Salida
     */
    public function setLote(\SisGG\FinalBundle\Entity\Lote $lote = null)
    {
        $this->lote = $lote;
    
        return $this;
    }

    /**
     * Get lote
     *
     * @return \SisGG\FinalBundle\Entity\Lote 
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set tipo
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipo
     * @return Salida
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

    /**
     * Set pago
     *
     * @param \SisGG\FinalBundle\Entity\Pago $pago
     * @return Salida
     */
    public function setPago(\SisGG\FinalBundle\Entity\Pago $pago = null)
    {
        $this->pago = $pago;
    
        return $this;
    }

    /**
     * Get pago
     *
     * @return \SisGG\FinalBundle\Entity\Pago 
     */
    public function getPago()
    {
        return $this->pago;
    }
    
    
}