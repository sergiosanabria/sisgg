<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Gedmo\Loggable 
 */
class Campo implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $nombre;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $requerido;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     */
    protected $unico;
    
    /**
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    protected $tipoDato;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $patron;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    protected $ejemplo;
    
    /**
     * @ORM\ManyToOne(targetEntity="TipoCobro",inversedBy="campos")
     * @Gedmo\Versioned
     */
    protected $tipoCobro;
    
    /**
     * @ORM\OneToMany(targetEntity="Valor",mappedBy="campo")
     */
    protected $valores;

    public function __toString() {
        return $this->nombre;
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
     * Constructor
     */
    public function __construct()
    {
        $this->valores = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Campo
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
     * Set requerido
     *
     * @param boolean $requerido
     * @return Campo
     */
    public function setRequerido($requerido)
    {
        $this->requerido = $requerido;
    
        return $this;
    }

    /**
     * Get requerido
     *
     * @return boolean 
     */
    public function getRequerido()
    {
        return $this->requerido;
    }

    /**
     * Set unico
     *
     * @param boolean $unico
     * @return Campo
     */
    public function setUnico($unico)
    {
        $this->unico = $unico;
    
        return $this;
    }

    /**
     * Get unico
     *
     * @return boolean 
     */
    public function getUnico()
    {
        return $this->unico;
    }

    /**
     * Set tipoDato
     *
     * @param string $tipoDato
     * @return Campo
     */
    public function setTipoDato($tipoDato)
    {
        $this->tipoDato = $tipoDato;
    
        return $this;
    }

    /**
     * Get tipoDato
     *
     * @return string 
     */
    public function getTipoDato()
    {
        return $this->tipoDato;
    }

    /**
     * Set patron
     *
     * @param string $patron
     * @return Campo
     */
    public function setPatron($patron)
    {
        $this->patron = $patron;
    
        return $this;
    }

    /**
     * Get patron
     *
     * @return string 
     */
    public function getPatron()
    {
        return $this->patron;
    }

    /**
     * Set ejemplo
     *
     * @param string $ejemplo
     * @return Campo
     */
    public function setEjemplo($ejemplo)
    {
        $this->ejemplo = $ejemplo;
    
        return $this;
    }

    /**
     * Get ejemplo
     *
     * @return string 
     */
    public function getEjemplo()
    {
        return $this->ejemplo;
    }

    /**
     * Set tipoCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tipoCobro
     * @return Campo
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
     * Add valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     * @return Campo
     */
    public function addValore(\SisGG\FinalBundle\Entity\Valor $valores)
    {
        $this->valores[] = $valores;
    
        return $this;
    }

    /**
     * Remove valores
     *
     * @param \SisGG\FinalBundle\Entity\Valor $valores
     */
    public function removeValore(\SisGG\FinalBundle\Entity\Valor $valores)
    {
        $this->valores->removeElement($valores);
    }

    /**
     * Get valores
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValores()
    {
        return $this->valores;
    }
}