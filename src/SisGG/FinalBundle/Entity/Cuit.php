<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="cuit")
 */
class Cuit {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string",length=2)
     */
    protected $tipoglobal;
    /**
     * @ORM\Column(type="integer",length=10)
     * @Assert\NotBlank(message="Ingrese un cuit") 
     * @Assert\Regex("/^\w+/")
     */
    protected $numero;
    /**
     * @ORM\Column(type="integer", length=1)
     */
    protected $verificador;
    



    public function __toString() {
        return $this->tipoglobal.'-'.$this->numero.'-'.$this->verificador;
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
     * Set tipoglobal
     *
     * @param string $tipoglobal
     * @return Cuit
     */
    public function setTipoglobal($tipoglobal)
    {
        $this->tipoglobal = $tipoglobal;
    
        return $this;
    }

    /**
     * Get tipoglobal
     *
     * @return string 
     */
    public function getTipoglobal()
    {
        return $this->tipoglobal;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Cuit
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set verificador
     *
     * @param integer $verificador
     * @return Cuit
     */
    public function setVerificador($verificador)
    {
        $this->verificador = $verificador;
    
        return $this;
    }

    /**
     * Get verificador
     *
     * @return integer 
     */
    public function getVerificador()
    {
        return $this->verificador;
    }
}