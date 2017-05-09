<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 */
class ItemLIV {
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
     * @ORM\ManyToOne(targetEntity="LibroIvaVenta",inversedBy="item")
     */
     private $liv;

    

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
     * @return ItemLIV
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
     * @return ItemLIV
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
     * Set gravado
     *
     * @param boolean $gravado
     * @return ItemLIV
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
     * Set liv
     *
     * @param \SisGG\FinalBundle\Entity\LibroIvaVenta $liv
     * @return ItemLIV
     */
    public function setLiv(\SisGG\FinalBundle\Entity\LibroIvaVenta $liv = null)
    {
        $this->liv = $liv;
    
        return $this;
    }

    /**
     * Get liv
     *
     * @return \SisGG\FinalBundle\Entity\LibroIvaVenta 
     */
    public function getLiv()
    {
        return $this->liv;
    }
}