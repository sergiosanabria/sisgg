<?php

namespace SisGG\FinalBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @UniqueEntity(fields={"numero", "serie", "proveedor", "tipo"}, message="Factura no válida")
 * @ORM\Table(name="compra")
 * @Gedmo\Loggable
 */
class Compra implements\Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")}
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id", unique=false)
     * @Gedmo\Versioned
     */
    private $proveedor;

    /**
     * @ORM\ManyToOne(targetEntity="NotaPedido")
     * @ORM\JoinColumn(name="notaPedido_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $notaPedido;

    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $fechaFactura;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fechaEmision;

    /**
     * @Assert\Range(
     *      min = "0",
     *      max = "1",
     *      invalidMessage = "El estado introducido es incorrecto."
     * )
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;

    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $subTotal;

    /**
     * @Assert\NotBlank(message="Ingrese un número.")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      invalidMessage = "El porcentaje debe estar en el rango de 0 a 100."
     * )
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $descuento;

    /**
     * @Assert\NotBlank(message="Ingrese un número.")
     * @Assert\Range(
     *      min = 0
     * )
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $otrosImp;

    /**
     * @Assert\Choice(choices = {"A", "B", "C", "X"}, message = "Tipo no válido.")
     * @ORM\Column(type="string", length=1)
     * @Gedmo\Versioned
     */
    protected $tipo;

    /**
     * @Assert\Range(
     *      min = 1,
     *      max= 9998,
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Assert\NotBlank(message="Ingrese el número de serie.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $serie;

    /**
     * @Assert\Range(
     *      min = 1,
     *      max = 99999999,
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Assert\NotBlank(message="Ingrese el número.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="ItemCompra",mappedBy="compra", cascade="persist")
     */
    protected $item;

    /**
     * @ORM\OneToMany(targetEntity="Pago",mappedBy="compra", cascade="persist")
     */
    protected $pagos;

    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="compras")
     */
    private $empresa;
    private $tipos;
       /**
     * @ORM\ManyToOne(targetEntity="TipoOperacion",inversedBy="compras")
     */
    private $tipoOperacion;

    /**
     * @ORM\ManyToOne(targetEntity="TipoFactura",inversedBy="compras")
     */
    private $tipoFactura;
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
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return 'hola';
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
     * Set fechaFactura
     *
     * @param \DateTime $fechaFactura
     * @return Compra
     */
    public function setFechaFactura($fechaFactura) {
        $this->fechaFactura = $fechaFactura;

        return $this;
    }

    /**
     * Get fechaFactura
     *
     * @return \DateTime 
     */
    public function getFechaFactura() {
        return $this->fechaFactura;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     * @return Compra
     */
    public function setFechaEmision() {
        $this->fechaEmision = new \DateTime("now");
        return $this;
    }

    public function isMenorHoy() {
        $hoy = new \DateTime("now");
        if ($this->fechaFactura <= $hoy) {
            return true;
        }
        return FALSE;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime 
     */
    public function getFechaEmision() {
        return $this->fechaEmision;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Compra
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return Compra
     */
    public function setTotal($total) {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Set proveedor
     *
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return Compra
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null) {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor() {
        return $this->proveedor;
    }

    /**
     * Set notaPedido
     *
     * @param SisGG\FinalBundle\Entity\NotaPedido $notaPedido
     * @return Compra
     */
    public function setNotaPedido(\SisGG\FinalBundle\Entity\NotaPedido $notaPedido = null) {
        $this->notaPedido = $notaPedido;

        return $this;
    }

    /**
     * Get notaPedido
     *
     * @return SisGG\FinalBundle\Entity\NotaPedido 
     */
    public function getNotaPedido() {
        return $this->notaPedido;
    }

    /**
     * Add pagos
     *
     * @param SisGG\FinalBundle\Entity\Pago $pagos
     * @return Compra
     */
    public function addPago(\SisGG\FinalBundle\Entity\Pago $pagos) {
        $this->pagos[] = $pagos;

        return $this;
    }

    /**
     * Remove pagos
     *
     * @param SisGG\FinalBundle\Entity\Pago $pagos
     */
    public function removePago(\SisGG\FinalBundle\Entity\Pago $pagos) {
        $this->pagos->removeElement($pagos);
    }

    /**
     * Get pagos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPagos() {
        return $this->pagos;
    }

    /**
     * Add item
     *
     * @param SisGG\FinalBundle\Entity\ItemCompra $item
     * @return Compra
     */
    public function addItem(\SisGG\FinalBundle\Entity\ItemCompra $item) {
        $this->item[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param SisGG\FinalBundle\Entity\ItemCompra $item
     */
    public function removeItem(\SisGG\FinalBundle\Entity\ItemCompra $item) {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItem() {
        return $this->item;
    }

    /**
     * Set serie
     *
     * @param integer $serie
     * @return Compra
     */
    public function setSerie($serie) {
        $this->serie = str_pad($serie, 4, "0", STR_PAD_LEFT);

        return $this;
    }

    /**
     * Get serie
     *
     * @return integer 
     */
    public function getSerie() {
        return $this->serie;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Compra
     */
    public function setNumero($numero) {

        $this->numero = str_pad($numero, 8, "0", STR_PAD_LEFT);

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero() {
        return $this->numero;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Compra
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Compra
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo() {
        return $this->tipo;
    }

    public function getTipos() {
        return $this->tipos;
    }

    public function setTipos($tipos) {
        $this->tipos = $tipos;
    }

    public function existeTipo($tipo) {
        foreach ($this->tipos as $value) {
            if ($tipo == $value)
                return true;
        }
        return false;
    }

    /**
     * Set descuento
     *
     * @param float $descuento
     * @return Compra
     */
    public function setDescuento($descuento) {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return float 
     */
    public function getDescuento() {
        return $this->descuento;
    }

    public function isDescuentoValido() {
        if ($this->descuento == null)
            $this->descuento = 0.00;
        if ($this->descuento >= 0 && $this->descuento <= 100)
            return true;
        return false;
    }

    public function getTotalPagos() {
        $total = 0.00;

        if ($this->pagos != null) {
            foreach ($this->pagos as $value) {
                $monto = 0.00;
                $monto = $value->getImporte();
                $total+=$monto;
            }
        }
        return $total;
    }

    public function getSaldo() {
        $total = 0.00;
        $pagos = 0.00;
        $total = $this->total;
        $pagos = $this->getTotalPagos();
        return $total - $pagos;
    }

    public function pagar(Pago $pago, Usuario $usuario) {
        $saldo = 0.00;
        $monto = 0.00;
        $resto = 0.00;
        $saldo = $this->getSaldo();
        $monto = $pago->getImporte();
        $resto = $monto - $saldo;
        if (round($resto, 3) >= 0.000) {
            $this->setEstado(1);
            $newPago = new Pago();
            $newPago->setCompra($this);
            $newPago->setFecha($pago->getFecha());
            $newPago->setFechaEmision();
            $newPago->setImporte($saldo);
            $newPago->setAclaracion($pago->getAclaracion());
            $newPago->setTipoCobro($pago->getTipoCobro());
            $salida = $newPago->crearSalida($usuario);
            if ($salida) {
                return "El pago con importe ".$newPago->getImporte()." y fecha ". date_format($newPago->getFecha(), 'd/m/Y')." no puede realizarse pusto que la caja esta cerrada.";
            }
            $newPago->asociarValoresViejos($pago);
            $this->addPago($newPago);
            
        } else {
            
            $newPago = new Pago();
            $newPago->setCompra($this);
            $newPago->setFecha($pago->getFecha());
            $newPago->setFechaEmision();
            $newPago->setImporte($pago->getImporte());
            $newPago->setAclaracion($pago->getAclaracion());
            
            $newPago->setTipoCobro($pago->getTipoCobro());
            $salida = $newPago->crearSalida($usuario);
            if ($salida) {
                return "El pago con importe ".$newPago->getImporte()." y fecha ". date_format($newPago->getFecha(), 'd/m/Y')." no puede realizarse pusto que la caja esta cerrada.";
            }
            $newPago->asociarValoresViejos($pago);
            $this->addPago($newPago);
           
        }
        return $resto;
    }

    function totalPagos() {
        $total = 0.00;
        foreach ($this->pagos as $value) {
            $total+=$value->getImporte();
        }

        return $total;
    }

    /**
     * Set otrosImp
     *
     * @param float $otrosImp
     * @return Compra
     */
    public function setOtrosImp($otrosImp) {
        $this->otrosImp = $otrosImp;

        return $this;
    }

    /**
     * Get otrosImp
     *
     * @return float 
     */
    public function getOtrosImp() {
        return $this->otrosImp;
    }

    /**
     * Set subTotal
     *
     * @param float $subTotal
     * @return Compra
     */
    public function setSubTotal($subTotal) {
        $this->subTotal = $subTotal;

        return $this;
    }

    /**
     * Get subTotal
     *
     * @return float 
     */
    public function getSubTotal() {
        return $this->subTotal;
    }

    public function tipoEstado() {
        if ($this->estado) {
            return 'Totalmente pago';
        }
        return 'Parcialmente pago';
    }

    public function existePago(Pago $pago) {
        if ($this->pagos == null) {
            return false;
        }
        foreach ($this->pagos as $value) {
            if ($value == $pago) {
                return true;
            }
        }
        return false;
    }


    /**
     * Set tipoOperacion
     *
     * @param \SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion
     * @return Compra
     */
    public function setTipoOperacion(\SisGG\FinalBundle\Entity\TipoOperacion $tipoOperacion = null)
    {
        $this->tipoOperacion = $tipoOperacion;
    
        return $this;
    }

    /**
     * Get tipoOperacion
     *
     * @return \SisGG\FinalBundle\Entity\TipoOperacion 
     */
    public function getTipoOperacion()
    {
        return $this->tipoOperacion;
    }

    /**
     * Set tipoFactura
     *
     * @param \SisGG\FinalBundle\Entity\TipoFactura $tipoFactura
     * @return Compra
     */
    public function setTipoFactura(\SisGG\FinalBundle\Entity\TipoFactura $tipoFactura = null)
    {
        $this->tipoFactura = $tipoFactura;
    
        return $this;
    }

    /**
     * Get tipoFactura
     *
     * @return \SisGG\FinalBundle\Entity\TipoFactura 
     */
    public function getTipoFactura()
    {
        return $this->tipoFactura;
    }
    
    
    public function getNro() {
        return $this->getSerie().'-'.$this->getNumero();
    }
}