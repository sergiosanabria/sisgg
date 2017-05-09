<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ItemVentaRepository")
 * @Gedmo\Loggable
 */
class ItemIvaVenta implements \Serializable{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     * @Gedmo\Versioned
     */
    protected $iva;
    
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $tasa;
    
    /**
     * @ORM\Column(type="decimal", scale=3, nullable=true)
     * @Gedmo\Versioned
     */
    private $total;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $gravado;
    
    /**
     * @ORM\ManyToOne(targetEntity="Venta",inversedBy="itemsIvaVenta")
     * @Gedmo\Versioned
     */
    private $venta;
    
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tasa
     *
     * @param string $tasa
     * @return ItemIvaVenta
     */
    public function setTasa($tasa)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return string 
     */
    public function getTasa()
    {
        return $this->tasa;
    }

    /**
     * Set total
     *
     * @param string $total
     * @return ItemIvaVenta
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
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return ItemIvaVenta
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set venta
     *
     * @param \SisGG\FinalBundle\Entity\Venta $venta
     * @return ItemIvaVenta
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
     * Set gravado
     *
     * @param boolean $gravado
     * @return ItemIvaVenta
     */
    public function setGravado($gravado)
    {
        $this->gravado = $gravado;
    
        return $this;
    }

    /**
     * Get gravado
     *
     * @return boolean 
     */
    public function getGravado()
    {
        return $this->gravado;
    }
}