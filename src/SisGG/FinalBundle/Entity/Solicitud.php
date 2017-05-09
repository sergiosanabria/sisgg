<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\SolicitudRepository")
 * @Gedmo\Loggable
 */
class Solicitud implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string") 
     */
    protected $encabezado;
    /**
     * @ORM\OneToMany(targetEntity="DetalleSolicitud",mappedBy="solicitud",cascade={"persist","remove"})
     */
    protected $detalles;
    /**
     *  @ORM\Column(type="boolean",nullable=true) 
     */
    protected $respuesta;
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
     * Constructor
     */
    public function __construct()
    {
        $this->detalles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set encabezado
     *
     * @param string $encabezado
     * @return Solicitud
     */
    public function setEncabezado($encabezado)
    {
        $this->encabezado = $encabezado;
    
        return $this;
    }

    /**
     * Get encabezado
     *
     * @return string 
     */
    public function getEncabezado()
    {
        return $this->encabezado;
    }

    /**
     * Add detalles
     *
     * @param SisGG\FinalBundle\Entity\DetalleSolicitud $detalles
     * @return Solicitud
     */
    public function addDetalle(\SisGG\FinalBundle\Entity\DetalleSolicitud $detalles)
    {
        $this->detalles[] = $detalles;
    
        return $this;
    }

    /**
     * Remove detalles
     *
     * @param SisGG\FinalBundle\Entity\DetalleSolicitud $detalles
     */
    public function removeDetalle(\SisGG\FinalBundle\Entity\DetalleSolicitud $detalles)
    {
        $this->detalles->removeElement($detalles);
    }

    /**
     * Get detalles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * Set respuesta
     *
     * @param boolean $respuesta
     * @return Solicitud
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    
        return $this;
    }

    /**
     * Get respuesta
     *
     * @return boolean 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }
}