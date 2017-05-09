<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Movimiento;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable 
 */
class ItemCierre {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cierre",inversedBy="itemCierre",cascade="persist")
     */
    private $cierre;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro",inversedBy="itemsCierre")
     * @Gedmo\Versioned
     */
    protected $tipoCobro;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importeSistema;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     * @Gedmo\Versioned
     */
    protected $importeReal;
    
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
     * Set cierre
     *
     * @param \SisGG\FinalBundle\Entity\Cierre $cierre
     * @return ItemCierre
     */
    public function setCierre(\SisGG\FinalBundle\Entity\Cierre $cierre = null)
    {
        $this->cierre = $cierre;
    
        return $this;
    }

    /**
     * Get cierre
     *
     * @return \SisGG\FinalBundle\Entity\Cierre 
     */
    public function getCierre()
    {
        return $this->cierre;
    }

    /**
     * Set tipoCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipoCobro
     * @return ItemCierre
     */
    public function setTipoCobro(\SisGG\FinalBundle\Entity\TipoCobro $tipoCobro = null)
    {
        $this->tipoCobro = $tipoCobro;
    
        return $this;
    }

    /**
     * Get tipoCobro
     *
     * @return \SisGG\FinalBundle\Entity\TipoCobro 
     */
    public function getTipoCobro()
    {
        return $this->tipoCobro;
    }

    /**
     * Set importeSistema
     *
     * @param float $importeSistema
     * @return ItemCierre
     */
    public function setImporteSistema($importeSistema)
    {
        $this->importeSistema = $importeSistema;
    
        return $this;
    }

    /**
     * Get importeSistema
     *
     * @return float 
     */
    public function getImporteSistema()
    {
        return $this->importeSistema;
    }

    /**
     * Set importeReal
     *
     * @param float $importeReal
     * @return ItemCierre
     */
    public function setImporteReal($importeReal)
    {
        $this->importeReal = $importeReal;
    
        return $this;
    }

    /**
     * Get importeReal
     *
     * @return float 
     */
    public function getImporteReal()
    {
        return $this->importeReal;
    }
}