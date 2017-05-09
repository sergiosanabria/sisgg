<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\CategoriaProductoVentaRepository")
 */
class CategoriaProductoVenta implements \Serializable
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
     * @ORM\OneToMany(targetEntity="ProductoVenta", mappedBy="categoria")
     */
    private $productoVenta;
    /**
     * @ORM\OneToMany(targetEntity="CategoriaProductoVenta",  cascade={"persist"}, mappedBy="padre")
     */
    private $hijo;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaProductoVenta", inversedBy="hijo")
     * @ORM\JoinColumn(name="padre_id", referencedColumnName="id")
     */
    private $padre;
    /**
     * @ORM\Column(type="boolean")
     */
    private $activo;
     /**
     * @ORM\ManyToOne(targetEntity="Empresa",inversedBy="categoriasPV")
     */
    private $empresa;
    
    /**
     * @ORM\ManyToMany(targetEntity="DescuentoProductoVenta",mappedBy="categoriaProductoVenta")
     */
    protected $descuentos;
    
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
    
    public function __toString() {
        return $this->nombre;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productoVenta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hijo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return CategoriaProductoVenta
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
     * Add productoVenta
     *
     * @param SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     * @return CategoriaProductoVenta
     */
    public function addProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta)
    {
        $this->productoVenta[] = $productoVenta;
    
        return $this;
    }

    /**
     * Remove productoVenta
     *
     * @param SisGG\FinalBundle\Entity\ProductoVenta $productoVenta
     */
    public function removeProductoVenta(\SisGG\FinalBundle\Entity\ProductoVenta $productoVenta)
    {
        $this->productoVenta->removeElement($productoVenta);
    }

    /**
     * Get productoVenta
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProductoVenta()
    {
        return $this->productoVenta;
    }

    /**
     * Add hijo
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $hijo
     * @return CategoriaProductoVenta
     */
    public function addHijo(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $hijo)
    {
        $this->hijo[] = $hijo;
    
        return $this;
    }

    /**
     * Remove hijo
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $hijo
     */
    public function removeHijo(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $hijo)
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

    /**
     * Set padre
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $padre
     * @return CategoriaProductoVenta
     */
    public function setPadre(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $padre = null)
    {
        $this->padre = $padre;
    
        return $this;
    }

    /**
     * Get padre
     *
     * @return SisGG\FinalBundle\Entity\CategoriaProductoVenta 
     */
    public function getPadre()
    {
        return $this->padre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return CategoriaProductoVenta
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
     * @return CategoriaProductoVenta
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
    
    public function vacioConProducto(){
        foreach ($this->getProductoVenta() as $value) {
            if (($value->getActivo())){
                return false;
            }
        }
        return true;
        
        }
        
    

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return CategoriaProductoVenta
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

    /**
     * Add descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     * @return CategoriaProductoVenta
     */
    public function addDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos[] = $descuentos;
    
        return $this;
    }

    /**
     * Remove descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     */
    public function removeDescuento(\SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos)
    {
        $this->descuentos->removeElement($descuentos);
    }

    /**
     * Get descuentos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }
    
    public function getDescuentosActivos(){
        $retorno = array();
        /* @var $descuento Descuento*/
        foreach ($this->getDescuentos() as $descuento) {
            if ($descuento->isActivo()){
                $retorno[ ] = $descuento;
            }
        }
        if ($this->getPadre() != null){
            foreach ($this->getPadre()->getDescuentosActivos() as $descuento) {
                if ($descuento->isActivo() && !in_array($descuento, $retorno)){
                    $retorno[ ] = $descuento;
                }
            }
        }
        return $retorno;
    }
}