<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use SisGG\FinalBundle\Validator\Constraints as MyAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\CondicionIvaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class CondicionIva implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un nombre")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $abreviatura;

    /**
     * @ORM\OneToMany(targetEntity="Cliente",mappedBy="condicionIva")
     */
    private $clientes;

    /**
     * @ORM\OneToMany(targetEntity="Operacion",mappedBy="de")
     */
    private $operacionesDe;

    /**
     * @ORM\OneToMany(targetEntity="Operacion",mappedBy="a")
     */
    private $operacionesA;

    /**
     * @ORM\Column(type="string")
     */
    private $estado;

    public function __toString() {
        return ($this->getAbreviatura() != null ? $this->getNombre() . "(" . $this->getAbreviatura() . ")" : $this->getNombre());
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
     * Set nombre
     *
     * @param string $nombre
     * @return CondicionIva
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setEstado("activo");
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operacionesDe = new \Doctrine\Common\Collections\ArrayCollection();
        $this->operacionesA = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add clientes
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $clientes
     * @return CondicionIva
     */
    public function addCliente(\SisGG\FinalBundle\Entity\Cliente $clientes) {
        $this->clientes[] = $clientes;

        return $this;
    }

    /**
     * Remove clientes
     *
     * @param \SisGG\FinalBundle\Entity\Cliente $clientes
     */
    public function removeCliente(\SisGG\FinalBundle\Entity\Cliente $clientes) {
        $this->clientes->removeElement($clientes);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClientes() {
        return $this->clientes;
    }

    /**
     * Add operacionesDe
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operacionesDe
     * @return CondicionIva
     */
    public function addOperacionesDe(\SisGG\FinalBundle\Entity\Operacion $operacionesDe) {
        $this->operacionesDe[] = $operacionesDe;

        return $this;
    }

    /**
     * Remove operacionesDe
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operacionesDe
     */
    public function removeOperacionesDe(\SisGG\FinalBundle\Entity\Operacion $operacionesDe) {
        $this->operacionesDe->removeElement($operacionesDe);
    }

    /**
     * Get operacionesDe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperacionesDe() {
        return $this->operacionesDe;
    }

    /**
     * Add operacionesA
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operacionesA
     * @return CondicionIva
     */
    public function addOperacionesA(\SisGG\FinalBundle\Entity\Operacion $operacionesA) {
        $this->operacionesA[] = $operacionesA;

        return $this;
    }

    /**
     * Remove operacionesA
     *
     * @param \SisGG\FinalBundle\Entity\Operacion $operacionesA
     */
    public function removeOperacionesA(\SisGG\FinalBundle\Entity\Operacion $operacionesA) {
        $this->operacionesA->removeElement($operacionesA);
    }

    /**
     * Get operacionesA
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperacionesA() {
        return $this->operacionesA;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return CondicionIva
     */
    public function setAbreviatura($abreviatura) {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura() {
        return $this->abreviatura;
    }


    /**
     * Set estado
     *
     * @param string $estado
     * @return CondicionIva
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
}