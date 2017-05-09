<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @ORM\Table(name="pago")
 * @Gedmo\Loggable
 */
class Pago implements\Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Ingrese un monto de pago.") 
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importe;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $aclaracion;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $fecha;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $fechaEmision;

    /**
     * @ORM\ManyToOne(targetEntity="Compra")
     * @ORM\JoinColumn(name="compra", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $compra;

    /**
     * @ORM\ManyToOne(targetEntity="FacturaServicio")
     * @ORM\JoinColumn(name="facturaServicio", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $facturaServicio;

    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro",inversedBy="pagos")
     * @Gedmo\Versioned
     */
    protected $tipoCobro;

    /**
     * @ORM\OneToMany(targetEntity="Valor",mappedBy="pago",cascade={"persist"})
     */
    protected $valores;

    /**
     * @ORM\OneToOne(targetEntity="Salida", mappedBy="pago",cascade={"persist"})
     * @Gedmo\Versioned
     * */
    protected $salida;

    /**
     * @ORM\OneToOne(targetEntity="ContadoEmpleado", mappedBy="pago",cascade={"persist"})
     * @Gedmo\Versioned
     * */
    protected $contado;

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
    public function getId() {
        return $this->id;
    }

   

    /**
     * Set compra
     *
     * @param SisGG\FinalBundle\Entity\Compra $compra
     * @return Pago
     */
    public function setCompra(\SisGG\FinalBundle\Entity\Compra $compra = null) {
        $this->compra = $compra;

        return $this;
    }

    /**
     * Get compra
     *
     * @return SisGG\FinalBundle\Entity\Compra 
     */
    public function getCompra() {
        return $this->compra;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Pago
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
     * Set facturaServicio
     *
     * @param \SisGG\FinalBundle\Entity\FacturaServicio $facturaServicio
     * @return Pago
     */
    public function setFacturaServicio(\SisGG\FinalBundle\Entity\FacturaServicio $facturaServicio = null) {
        $this->facturaServicio = $facturaServicio;

        return $this;
    }

    /**
     * Get facturaServicio
     *
     * @return \SisGG\FinalBundle\Entity\FacturaServicio 
     */
    public function getFacturaServicio() {
        return $this->facturaServicio;
    }

    public function setFechaEmision() {
        $this->fechaEmision = new \DateTime("now");
        return $this;
    }

    public function getAtr() {
        return $this->atr;
    }

    public function setAtr($atr) {
        $this->atr = $atr;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime 
     */
    public function getFechaEmision() {
        return $this->fechaEmision;
    }

    public function tieneMonto() {
        if ($this->importe > 0)
            return true;
        return false;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set importe
     *
     * @param string $importe
     * @return Pago
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
     * Set aclaracion
     *
     * @param string $aclaracion
     * @return Pago
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
     * Set tipoCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipoCobro
     * @return Pago
     */
    public function setTipoCobro(\SisGG\FinalBundle\Entity\TipoCobro $tipoCobro = null) {
        $this->tipoCobro = $tipoCobro;

        return $this;
    }

    /**
     * Get tipoCobro
     *
     * @return \SisGG\FinalBundle\Entity\TipoCobro 
     */
    public function getTipoCobro() {
        return $this->tipoCobro;
    }

    /**
     * Add valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     * @return Pago
     */
    public function addValore(\SisGG\FinalBundle\Entity\Valor $valores) {
        $this->valores[] = $valores;

        return $this;
    }

    /**
     * Remove valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     */
    public function removeValore(\SisGG\FinalBundle\Entity\Valor $valores) {
        $this->valores->removeElement($valores);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValores() {
        return $this->valores;
    }

    public function isMenorHoy() {
        $hoy = new \DateTime("now");
        if ($this->fecha <= $hoy) {
            return true;
        }
        return FALSE;
    }

    public function asociarValores() {
        if ($this->valores != null) {
            foreach ($this->valores as $value) {
                $value->setPago($this);
            }
        }
    }

    public function asociarValoresViejos(Pago $pago) {
        if ($pago->valores != null) {
            foreach ($pago->valores as $value) {
                $valor = new Valor();
                $valor->setCampo($value->getCampo());
                $valor->setValor($value->getValor());
                $valor->setPago($this);
                $this->addValore($valor);
            }
        }
    }

    /**
     * Set salida
     *
     * @param \SisGG\FinalBundle\Entity\Salida $salida
     * @return Pago
     */
    public function setSalida(\SisGG\FinalBundle\Entity\Salida $salida = null) {
        $this->salida = $salida;

        return $this;
    }

    /**
     * Get salida
     *
     * @return \SisGG\FinalBundle\Entity\Salida 
     */
    public function getSalida() {
        return $this->salida;
    }

    public function crearSalida(Usuario $usuario, $empresa = null) {
        if ($this->getTipoCobro()->getLiquido()) {

            $salida = new Salida();
            $concepto = null;
            /* @var $empresa Empresa */
            if ($empresa == null) {
                if ($this->getCompra() != null) {
                    $empresa = $this->getCompra()->getEmpresa();
                } elseif ($this->getFacturaServicio() != null) {
                    $empresa = $this->getFacturaServicio()->getEmpresa();
                } elseif ($this->getContado() != null) {
                    $empresa = $this->getContado()->getCuenta()->getEmpleado()->getEmpresa();
                }
                $concepto = $empresa->obtenerConcepto(1);
                if (!($empresa->estaCajaAbierta())) {
                    return true;
                }
            } else {
                $concepto = $empresa->obtenerConcepto(3);
            }
            $salida->setConcepto($concepto);
            $caja = $empresa->obtenerCaja(1);
            $salida->setCaja($caja);
            $salida->setLote($caja->getLotes()->first());
            $salida->setImporte($this->getImporte());
            $salida->setFecha();
            $salida->setUsuario($usuario);
            $salida->setTipo($this->getTipoCobro());

            $this->setSalida($salida);
            $salida->setPago($this);
            return false;
        }
        return false;
    }

    /**
     * Set contado
     *
     * @param \SisGG\FinalBundle\Entity\ContadoEmpleado $contado
     * @return Pago
     */
    public function setContado(\SisGG\FinalBundle\Entity\ContadoEmpleado $contado = null) {
        $this->contado = $contado;

        return $this;
    }

    /**
     * Get contado
     *
     * @return \SisGG\FinalBundle\Entity\ContadoEmpleado 
     */
    public function getContado() {
        return $this->contado;
    }

}