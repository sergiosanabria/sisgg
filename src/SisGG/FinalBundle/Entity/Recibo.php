<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\VentaRepository")
 * @UniqueEntity({"serie","numero"})
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Recibo implements \Serializable {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
     /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="cajas")
     * @Gedmo\Versioned
     */
    private $usuario;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Versioned
     */
    private $fecha;
    
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
     * @ORM\ManyToOne(targetEntity="Cliente",inversedBy="ventas")
     */
    private $cliente;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $nombre;
    
     /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    
    /**
     * @ORM\OneToOne(targetEntity="Cobro", inversedBy="unRecibo",cascade={"persist"})
     * @Gedmo\Versioned
     */
    private $unCobro;
    
    /**
     * @Assert\Valid
     * @ORM\OneToOne(targetEntity="MovimientoCuentaCorrienteHaber", mappedBy="unRecibo")
     * @Gedmo\Versioned
     */
    private $movimientoCuentaCorriente;
    
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
        return "Recibo " . str_pad($this->getSerie(), 4, 0, STR_PAD_LEFT) . '-' . str_pad($this->getNumero(), 8, 0, STR_PAD_LEFT);
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Recibo
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
     * @return Recibo
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
     * Set estado
     *
     * @param string $estado
     * @return Recibo
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
     * Set total
     *
     * @param string $total
     * @return Recibo
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return string 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set usuario
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuario
     * @return Recibo
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
     * @return Recibo
     */
    public function setCliente(\SisGG\FinalBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;
        $this->setNombre($cliente."");
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
     * Set unCobro
     *
     * @param \SisGG\FinalBundle\Entity\Cobro $unCobro
     * @return Recibo
     */
    public function setUnCobro(\SisGG\FinalBundle\Entity\Cobro $unCobro = null)
    {
        $this->unCobro = $unCobro;
    
        return $this;
    }

    /**
     * Get unCobro
     *
     * @return \SisGG\FinalBundle\Entity\Cobro 
     */
    public function getUnCobro()
    {
        return $this->unCobro;
    }

    /**
     * Set movimientoCuentaCorriente
     *
     * @param \SisGG\FinalBundle\Entity\MovimientoCuentaCorrienteHaber $movimientoCuentaCorriente
     * @return Recibo
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
     * Set serie
     *
     * @param string $serie
     * @return Recibo
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
     * @return Recibo
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
}