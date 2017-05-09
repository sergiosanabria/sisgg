<?php


namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\LibroIvaVentaRepository")
 */
class LibroIvaVenta {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $fecha;
    /**
     * @Assert\Choice(choices = {"A", "B", "C"}, message = "Tipo no válido.")
     * @ORM\Column(type="string", length=1)
     */
    protected $tipo;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cuit;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $razonSocial;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    protected $neto;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    protected $acrecent;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     */
    protected $total;
    /**
     * @ORM\ManyToOne(targetEntity="Cliente")
     */
    protected $cliente;
    /**
     * @ORM\OneToOne(targetEntity="Venta")
     */
    protected $venta;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="ivaCompras")
     */
    private $empresa;
    /**
     * @ORM\OneToMany(targetEntity="ItemLIV",mappedBy="liv", cascade="persist")
     */
    protected $item;        

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return LibroIvaVenta
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
     * Set tipo
     *
     * @param string $tipo
     * @return LibroIvaVenta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return LibroIvaVenta
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
     * Set razonSocial
     *
     * @param string $razonSocial
     * @return LibroIvaVenta
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;
    
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return string 
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set neto
     *
     * @param string $neto
     * @return LibroIvaVenta
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;
    
        return $this;
    }

    /**
     * Get neto
     *
     * @return string 
     */
    public function getNeto()
    {
        return $this->neto;
    }

    /**
     * Set acrecent
     *
     * @param string $acrecent
     * @return LibroIvaVenta
     */
    public function setAcrecent($acrecent)
    {
        $this->acrecent = $acrecent;
    
        return $this;
    }

    /**
     * Get acrecent
     *
     * @return string 
     */
    public function getAcrecent()
    {
        return $this->acrecent;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return LibroIvaVenta
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
     * Set cliente
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $cliente
     * @return LibroIvaVenta
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
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return LibroIvaVenta
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
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return LibroIvaVenta
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
     * Add item
     *
     * @param \SisGG\FinalBundle\Entity\ItemLIV $item
     * @return LibroIvaVenta
     */
    public function addItem(\SisGG\FinalBundle\Entity\ItemLIV $item)
    {
        $this->item[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param \SisGG\FinalBundle\Entity\ItemLIV $item
     */
    public function removeItem(\SisGG\FinalBundle\Entity\ItemLIV $item)
    {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItem()
    {
        return $this->item;
    }
}