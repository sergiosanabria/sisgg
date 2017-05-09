<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Permiso implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $objeto;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $otorgado;

    /**
     * @ORM\ManyToOne(targetEntity="Rol",inversedBy="permisos")
     */
    protected $role;

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
     * Set objeto
     *
     * @param string $objeto
     * @return Permiso
     */
    public function setObjeto($objeto)
    {
        $this->objeto = $objeto;
    
        return $this;
    }

    /**
     * Get objeto
     *
     * @return string 
     */
    public function getObjeto()
    {
        return $this->objeto;
    }

    /**
     * Set otorgado
     *
     * @param boolean $otorgado
     * @return Permiso
     */
    public function setOtorgado($otorgado)
    {
        $this->otorgado = $otorgado;
    
        return $this;
    }

    /**
     * Get otorgado
     *
     * @return boolean 
     */
    public function getOtorgado()
    {
        return $this->otorgado;
    }

    /**
     * Set role
     *
     * @param \SisGG\FinalBundle\Entity\Rol $role
     * @return Permiso
     */
    public function setRole(\SisGG\FinalBundle\Entity\Rol $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return \SisGG\FinalBundle\Entity\Rol 
     */
    public function getRole()
    {
        return $this->role;
    }
}