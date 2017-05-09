<?php
namespace SisGG\FinalBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Usuario;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Description of Rol
 *
 * @author martin
 * @ORM\Entity
 * @Gedmo\Loggable
 * @UniqueEntity(fields="role", message="Ya existe un rol con este nombre. Ingrese otro.")
 */
class Rol implements RoleInterface, \Serializable{
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
      * @Gedmo\Versioned
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Ingrese el nombre.")
     * @Gedmo\Versioned
     */
    private $role;
    /**
     * @ORM\Column(type="boolean")
     * @Gedmo\Versioned
     */
    private $activo;
    /**
     * @ORM\OneToMany(targetEntity="Usuario",mappedBy="role")
     */
    protected $usuarios;
    /**
     * @ORM\OneToMany(targetEntity="Permiso",mappedBy="role",cascade="persist")
     */
    protected $permisos;
    /**
     * @return string
     */
    public function getRole() {
        return $this->role;
    }
    /**
     * @return string
     */
    public function __toString() {
        return $this->role;
    }
    
    /**
     * @return string
     */
    public function serialize()
    {
      return serialize($this->id);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
      $this->id = unserialize($data);
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->permisos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set role
     *
     * @param string $role
     * @return Rol
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }


    /**
     * Add usuarios
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarios
     * @return Rol
     */
    public function addUsuario(\SisGG\FinalBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios[] = $usuarios;
    
        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\SisGG\FinalBundle\Entity\Usuario $usuarios)
    {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }


    /**
     * Add permisos
     *
     * @param \SisGG\FinalBundle\Entity\Permiso $permisos
     * @return Rol
     */
    public function addPermiso(\SisGG\FinalBundle\Entity\Permiso $permisos)
    {
        $this->permisos[] = $permisos;
    
        return $this;
    }

    /**
     * Remove permisos
     *
     * @param \SisGG\FinalBundle\Entity\Permiso $permisos
     */
    public function removePermiso(\SisGG\FinalBundle\Entity\Permiso $permisos)
    {
        $this->permisos->removeElement($permisos);
    }

    /**
     * Get permisos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPermisos()
    {
        return $this->permisos;
    }
    
    public function obtenerPermiso($permiso) {
        if ($this->permisos==null){
            return false;
        }
        
        foreach ($this->permisos as $value) {
            /*@var $value Permiso */
            if ($value->getObjeto()==$permiso){
                return $value->getOtorgado();
            }
        }
        return false;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Rol
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;
    
        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo()
    {
        return $this->activo;
    }
}