<?php

namespace SisGG\FinalBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert; 
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @ORM\Table(name="nota_pedido")
 * @Gedmo\Loggable
 */
class NotaPedido {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="datetime")
      * @Gedmo\Versioned
     */
    private $fecha;
    /**
     * @Assert\Range(
     *      min = "1",
     *      max = "4",
     *      invalidMessage = "El estado introducido es incorrecto."
     * )
     * @ORM\Column(type="smallint", nullable=true)
     * @Gedmo\Versioned
     */
    private $estado;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    /**
     * @ORM\OneToMany(targetEntity="ItemNotaPedido",mappedBy="notaPedido", cascade="persist")
     */
    protected $item;
    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="notas")
     * @Gedmo\Versioned
     */   
    private $empresa;
     /**
     * @ORM\OneToMany(targetEntity="Compra", mappedBy="compra")
     */
    private $compra;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->item = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString() {
        return 'np';
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
     * @return NotaPedido
     */
    public function setFecha()
    {
        $this->fecha =  new \DateTime("now");;   
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
     * Set total
     *
     * @param float $total
     * @return NotaPedido
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
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedor
     * @return NotaPedido
     */
    public function setProveedor(\SisGG\FinalBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return SisGG\FinalBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Add item
     *
     * @param SisGG\FinalBundle\Entity\ItemNotaPedido $item
     * @return NotaPedido
     */
    public function addItem(\SisGG\FinalBundle\Entity\ItemNotaPedido $item)
    {
        $this->item[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param SisGG\FinalBundle\Entity\ItemNotaPedido $item
     */
    public function removeItem(\SisGG\FinalBundle\Entity\ItemNotaPedido $item)
    {
        $this->item->removeElement($item);
    }

    /**
     * Get item
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return NotaPedido
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return NotaPedido
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
    

    /**
     * Add compra
     *
     * @param \SisGG\FinalBundle\Entity\Compra $compra
     * @return NotaPedido
     */
    public function addCompra(\SisGG\FinalBundle\Entity\Compra $compra)
    {
        $this->compra[] = $compra;
    
        return $this;
    }

    /**
     * Remove compra
     *
     * @param \SisGG\FinalBundle\Entity\Compra $compra
     */
    public function removeCompra(\SisGG\FinalBundle\Entity\Compra $compra)
    {
        $this->compra->removeElement($compra);
    }

    /**
     * Get compra
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompra()
    {
        return $this->compra;
    }
}