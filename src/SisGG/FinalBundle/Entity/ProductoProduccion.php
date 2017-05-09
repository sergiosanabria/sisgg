<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Producto;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @Gedmo\Loggable
 */
class ProductoProduccion extends Producto implements \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="CategoriaProductoProduccion", inversedBy="productoProduccion")
     */
    private $categoria;
    
    public function getCantidadDisponible() {
        return 0;
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
    
    public function descontar($cantidad,&$superados){
        $this->setCantidad($cantidad < $this->getCantidad() ? $this->getCantidad() - $cantidad : 0.00 );
        $superados = $superados | $this->isSuperaMin();
    }
    //Verifica si el producto produccion es un ingrediente
    public function esIngrediente($ingredientes){
        foreach ($ingredientes as $value) {
            if ($value->getProductoProduccion()==$this){
                return true;
            }
        }
        return false;
    }
    
    //obtiene el ingrediente 
    public function obtenerIngrediente($ingredientes){
        $arreglo=null;
        foreach ($ingredientes as $value) {
            if ($value->getProductoProduccion()==$this){
                $arreglo [] = $value;
            }
        }
        return $arreglo;
    }
    public function isPreElaborado() {
        return false;
    }
    public function isMateriaPrima() {
        return false;
    }
  
    
    /**
     * @var string $nombre
     */
    protected $nombre;


    public function __toString() {
        return $this->getNombre();
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
     * Set categoria
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoria
     * @return ProductoProduccion
     */
    public function setCategoria(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return SisGG\FinalBundle\Entity\CategoriaProductoProduccion 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return ProductoProduccion
     */
    public function setIva(\SisGG\FinalBundle\Entity\IVA $iva = null)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return \SisGG\FinalBundle\Entity\IVA 
     */
    public function getIva()
    {
        return $this->iva;
    }
}