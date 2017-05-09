<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\RedireccionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable
 */
class Redireccion implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario")
     */
    private $usuarioDe;

    /**
     * @ORM\ManyToOne(targetEntity="Usuario",inversedBy="redirecciones")
     */
    private $usuarioA;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="array",nullable=true)
     */
    private $parametros;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visto;
    
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue() {
        $this->setVisto(false);
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
     * Set url
     *
     * @param string $url
     * @return Redireccion
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set parametros
     *
     * @param array $parametros
     * @return Redireccion
     */
    public function setParametros($parametros) {
        $this->parametros = $parametros;

        return $this;
    }

    /**
     * Get parametros
     *
     * @return array 
     */
    public function getParametros() {
        return $this->parametros;
    }

    /**
     * Set usuarioDe
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarioDe
     * @return Redireccion
     */
    public function setUsuarioDe(\SisGG\FinalBundle\Entity\Usuario $usuarioDe = null) {
        $this->usuarioDe = $usuarioDe;

        return $this;
    }

    /**
     * Get usuarioDe
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuarioDe() {
        return $this->usuarioDe;
    }

    /**
     * Set usuarioA
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarioA
     * @return Redireccion
     */
    public function setUsuarioA(\SisGG\FinalBundle\Entity\Usuario $usuarioA = null) {
        $this->usuarioA = $usuarioA;

        return $this;
    }

    /**
     * Get usuarioA
     *
     * @return \SisGG\FinalBundle\Entity\Usuario 
     */
    public function getUsuarioA() {
        return $this->usuarioA;
    }


    /**
     * Set visto
     *
     * @param boolean $visto
     * @return Redireccion
     */
    public function setVisto($visto)
    {
        $this->visto = $visto;
    
        return $this;
    }

    /**
     * Get visto
     *
     * @return boolean 
     */
    public function getVisto()
    {
        return $this->visto;
    }
}