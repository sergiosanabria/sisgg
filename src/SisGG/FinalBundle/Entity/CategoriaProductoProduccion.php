<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity
 */
class CategoriaProductoProduccion implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string",unique=true)
     */
    protected $nombre;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;
    /**
     * @ORM\OneToMany(targetEntity="CategoriaProductoProduccion", mappedBy="padre")
     */
    private $hijo;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaProductoProduccion", inversedBy="hijo")
     * @ORM\JoinColumn(name="padre_id", referencedColumnName="id")
     */
    private $padre;
    
    /**
     * @ORM\OneToMany(targetEntity="ProductoProduccion", mappedBy="categoria")
     */
    private $productoProduccion;
     /**
     * @ORM\Column(type="boolean")
     */
    private $activo;
    /**
     * @ORM\ManyToOne(targetEntity="Empresa",inversedBy="categoriasPP")
     */
    private $empresa;
    
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
        $this->hijo = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString() {
        return $this->nombre;
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
     * Set nombre
     *
     * @param string $nombre
     * @return CategoriaProductoProduccion
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
     * Add hijo
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $hijo
     * @return CategoriaProductoProduccion
     */
    public function addHijo(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $hijo)
    {
        $this->hijo[] = $hijo;
    
        return $this;
    }

    /**
     * Remove hijo
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $hijo
     */
    public function removeHijo(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $hijo)
    {
        $this->hijo->removeElement($hijo);
    }

    /**
     * Get hijo
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getHijo()
    {
        return $this->hijo;
    }

    public function vacioConProducto(){
        foreach ($this->getProductoProduccion() as $value) {
            if (($value->getActivo())){
                return false;
            }
        }
        return true;
        
        }
    /**
     * Set padre
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $padre
     * @return CategoriaProductoProduccion
     */
    public function setPadre(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $padre = null)
    {
        $this->padre = $padre;
    
        return $this;
    }

    /**
     * Get padre
     *
     * @return SisGG\FinalBundle\Entity\CategoriaProductoProduccion 
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * Add productoProduccion
     *
     * @param SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion
     * @return CategoriaProductoProduccion
     */
    public function addProductoProduccion(\SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion)
    {
        $this->productoProduccion[] = $productoProduccion;
    
        return $this;
    }

    /**
     * Remove productoProduccion
     *
     * @param SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion
     */
    public function removeProductoProduccion(\SisGG\FinalBundle\Entity\ProductoProduccion $productoProduccion)
    {
        $this->productoProduccion->removeElement($productoProduccion);
    }

    /**
     * Get productoProduccion
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProductoProduccion()
    {
        return $this->productoProduccion;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CategoriaProductoProduccion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return CategoriaProductoProduccion
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;
    
        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return CategoriaProductoProduccion
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