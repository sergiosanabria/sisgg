<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use SisGG\FinalBundle\Entity\ItemIvaVenta;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\VentaRepository")
 * @UniqueEntity({"serie","numero","factura"})
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class NotaCredito implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="cajas")
     * @Gedmo\Versioned
     */
    protected $usuario;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    protected $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="ventas")
     * @Gedmo\Versioned
     */
    protected $cliente;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva")
     * @Gedmo\Versioned
     */
    protected $condicionIva;

    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva")
     * @Gedmo\Versioned
     */
    protected $condicionIvaEmpresa;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $cuit;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $estado;

    /**
     * @ORM\OneToOne(targetEntity="Venta")
     * @Gedmo\Versioned
     */
    protected $factura;

    /**
     * @Assert\Range(
     *      min = "1",
     *      max= "9998",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 9998."
     * )
     * @Assert\NotBlank(message="Ingrese el número de serie.")
     * @ORM\Column(type="string", length=4, nullable=false)
     * @Gedmo\Versioned
     */
    private $serie;

    /**
     * @Assert\Range(
     *      min = "1",
     *      max = "99999999",
     *      invalidMessage = "No puede ingresar un número menor a 1 o mayor a 99999999."
     * )
     * @Assert\NotBlank(message="Ingrese el número.")
     * @ORM\Column(type="string", length=8, nullable=false)
     * @Gedmo\Versioned
     */
    private $numero;
    
    /**
     * @return string
     */
    public function getNumeroNotaCredito() {
        return str_pad($this->getSerie(), 4, 0, STR_PAD_LEFT) . '-' . str_pad($this->getNumero(), 8, 0, STR_PAD_LEFT);
    }

    public function __toString() {
        return "Nota de Credito ".$this->getFactura()->getTipoFactura()->getNomenclatura() . " " . $this->getNumeroNotaCredito();
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
     * @return NotaCredito
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
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
     * Set nombre
     *
     * @param string $nombre
     * @return NotaCredito
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return NotaCredito
     */
    public function setCuit($cuit)
    {
        $this->cuit = $cuit;
    
        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit()
    {
        return $this->cuit;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return NotaCredito
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
     * Set serie
     *
     * @param string $serie
     * @return NotaCredito
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
    
        return $this;
    }

    /**
     * Get serie
     *
     * @return string 
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return NotaCredito
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return NotaCredito
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
     * Set cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     * @return NotaCredito
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
     * Set condicionIva
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIva
     * @return NotaCredito
     */
    public function setCondicionIva(\SisGG\FinalBundle\Entity\CondicionIva $condicionIva = null)
    {
        $this->condicionIva = $condicionIva;
    
        return $this;
    }

    /**
     * Get condicionIva
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIva()
    {
        return $this->condicionIva;
    }

    /**
     * Set condicionIvaEmpresa
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIvaEmpresa
     * @return NotaCredito
     */
    public function setCondicionIvaEmpresa(\SisGG\FinalBundle\Entity\CondicionIva $condicionIvaEmpresa = null)
    {
        $this->condicionIvaEmpresa = $condicionIvaEmpresa;
    
        return $this;
    }

    /**
     * Get condicionIvaEmpresa
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIvaEmpresa()
    {
        return $this->condicionIvaEmpresa;
    }

    /**
     * Set factura
     *
     * @param \SisGG\FinalBundle\Entity\Venta $factura
     * @return NotaCredito
     */
    public function setFactura(\SisGG\FinalBundle\Entity\Venta $factura = null)
    {
        $this->factura = $factura;
        $this->setCliente($factura->getCliente());
        $this->setCondicionIva($factura->getCondicionIva());
        $this->setCondicionIvaEmpresa($factura->getCondicionIvaEmpresa());
        $this->setCuit($factura->getCuit());
        $this->setNombre($factura->getNombre());
        return $this;
    }

    /**
     * Get factura
     *
     * @return \SisGG\FinalBundle\Entity\Venta 
     */
    public function getFactura()
    {
        return $this->factura;
    }
}