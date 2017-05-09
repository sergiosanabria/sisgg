<?php


namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="libro_iva_compra")
 * @Gedmo\Loggable
 */
class LibroIvaCompra {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    protected $fecha;
    /**
     * @Assert\Choice(choices = {"A", "B", "C"}, message = "Tipo no válido.")
     * @ORM\Column(type="string", length=1)
     * @Gedmo\Versioned
     */
    protected $tipo;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $cuit;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $razonSocial;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    protected $neto;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    protected $acrecent;
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    protected $total;
    /**
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     */
    protected $proveedor;
    /**
     * @ORM\OneToOne(targetEntity="Compra")
     * @ORM\JoinColumn(name="compra_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    protected $compra;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="ivaCompras")
     */
    private $empresa;
    /**
     * @ORM\OneToMany(targetEntity="ItemLIC",mappedBy="lic", cascade="persist")
     */
    protected $item;        
     /**
     * @ORM\Column(type="string", nullable=true)
     * @Gedmo\Versioned
     */
    protected $condIva;
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
     * @return LibroIvaCompra
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
     * @return LibroIvaCompra
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
     * @return LibroIvaCompra
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
     * @return LibroIvaCompra
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
     * @param float $neto
     * @return LibroIvaCompra
     */
    public function setNeto($neto)
    {
        $this->neto = $neto;
    
        return $this;
    }

    /**
     * Get neto
     *
     * @return float 
     */
    public function getNeto()
    {
        return $this->neto;
    }

    /**
     * Set noGravado
     *
     * @param float $noGravado
     * @return LibroIvaCompra
     */
    public function setNoGravado($noGravado)
    {
        $this->noGravado = $noGravado;
    
        return $this;
    }

    /**
     * Get noGravado
     *
     * @return float 
     */
    public function getNoGravado()
    {
        return $this->noGravado;
    }

    /**
     * Set gravado
     *
     * @param float $gravado
     * @return LibroIvaCompra
     */
    public function setGravado($gravado)
    {
        $this->gravado = $gravado;
    
        return $this;
    }

    /**
     * Get gravado
     *
     * @return float 
     */
    public function getGravado()
    {
        return $this->gravado;
    }

    /**
     * Set acrecent
     *
     * @param float $acrecent
     * @return LibroIvaCompra
     */
    public function setAcrecent($acrecent)
    {
        $this->acrecent = $acrecent;
    
        return $this;
    }

    /**
     * Get acrecent
     *
     * @return float 
     */
    public function getAcrecent()
    {
        return $this->acrecent;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return LibroIvaCompra
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set proveedor
     *
     * @param \SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return LibroIvaCompra
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set compra
     *
     * @param \SisGG\FinalBundle\Entity\Compra $compra
     * @return LibroIvaCompra
     */
    public function setCompra(\SisGG\FinalBundle\Entity\Compra $compra = null)
    {
        $this->compra = $compra;
    
        return $this;
    }

    /**
     * Get compra
     *
     * @return \SisGG\FinalBundle\Entity\Compra 
     */
    public function getCompra()
    {
        return $this->compra;
    }

    /**
     * Set empresa
     *
     * @param \SisGG\FinalBundle\Entity\Empresa $empresa
     * @return LibroIvaCompra
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
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add item
     *
     * @param \SisGG\FinalBundle\Entity\ItemLIC $item
     * @return LibroIvaCompra
     */
    public function addItem(\SisGG\FinalBundle\Entity\ItemLIC $item)
    {
        $this->item[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param \SisGG\FinalBundle\Entity\ItemLIC $item
     */
    public function removeItem(\SisGG\FinalBundle\Entity\ItemLIC $item)
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
    
    public function sumar(&$totales) {
        for ($i = 0; $i < count($this->item); $i++) {
            $value=  $this->item[$i];
            $totales[$i]['total']+=$value->getTotal();
        }

    }
    
    
    
    

    /**
     * Set condIva
     *
     * @param string $condIva
     * @return LibroIvaCompra
     */
    public function setCondIva($condIva)
    {
        $this->condIva = $condIva;
    
        return $this;
    }

    /**
     * Get condIva
     *
     * @return string 
     */
    public function getCondIva()
    {
        return $this->condIva;
    }
}