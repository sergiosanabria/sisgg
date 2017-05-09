<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="item_LIC")
 */
class ItemLIC {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tasa;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $total;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $gravado;
    
    /**
     * @ORM\ManyToOne(targetEntity="LibroIvaCompra")
     * @ORM\JoinColumn(name="lic", referencedColumnName="id")
     */
     private $lic;

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
     * Set total
     *
     * @param float $total
     * @return ItemLIC
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
     * Set gravado
     *
     * @param boolean $gravado
     * @return ItemLIC
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

    /**
     * Set lic
     *
     * @param \SisGG\FinalBundle\Entity\LibroIvaCompra $lic
     * @return ItemLIC
     */
    public function setLic(\SisGG\FinalBundle\Entity\LibroIvaCompra $lic = null)
    {
        $this->lic = $lic;
    
        return $this;
    }

    /**
     * Get lic
     *
     * @return \SisGG\FinalBundle\Entity\LibroIvaCompra 
     */
    public function getLic()
    {
        return $this->lic;
    }

    /**
     * Set tasa
     *
     * @param float $tasa
     * @return ItemLIC
     */
    public function setTasa($tasa)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return float 
     */
    public function getTasa()
    {
        return $this->tasa;
    }
}