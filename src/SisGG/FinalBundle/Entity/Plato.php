<?php
namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\ProductoVenta;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Plato extends ProductoVenta implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
    
     /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $precioCosto;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidad;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $precioVenta;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $cantidadMinima;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $margen;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $margenMin;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $supera;
    /**
     * @ORM\OneToOne(targetEntity="Image",cascade={"persist"})
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id", nullable=true)
     */
    protected $foto;
    /**
     * @ORM\OneToMany(targetEntity="Ingrediente",mappedBy="plato")
     */
    protected $ingredientes;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $superaMin;

    /**
     * @ORM\ManyToOne(targetEntity="IVA")
     */
    protected $iva;

    /**
     * @ORM\ManyToOne(targetEntity="Tasa",inversedBy="productosVenta")
     */
    protected $tasa;

    private $conIng;
    /**
     * @return boolean
     */
    public function isPlato() {
        return true;
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

    public function __toString() {
        return $this->getNombre();
    }
    
    public function toArray(){
        $retorno = array();
        $retorno ['id'] = $this->id;
        $retorno ['nombre'] = $this->nombre;
        $retorno ['precioVenta'] = $this->precioVenta;
        $retorno ['iva'] = $this->iva->getId();
        return $retorno;
    }

    /**
     * @return int
     */
    public function getCantidadDisponible() {
        $retorno = $this->getIngredientesObligatorios()->first()->getCantidadDisponible();
        foreach ($this->getIngredientesObligatorios() as $ingrediente) {
            if ($ingrediente->getCantidad() < $retorno) {
                $retorno = $insgrediente->getCantidadDisponible();
            }
        }
        return $retorno;
    }
    
    
    public function verificarDisponibilidad(Tasa $tasa,$cantidad){
        $retorno = array();
        $cantidadTotal = $tasa->getValor() * $cantidad;
        foreach ($this->getIngredientes() as $ingrediente) {
            $cantidadNecesaria = ($ingrediente->getTasa()->getValor() * $ingrediente->getCantidad())*$cantidadTotal;
            $cantidadDisponible = $ingrediente->getProductoProduccion()->getTasa()->getValor() * $ingrediente->getProductoProduccion()->getCantidad();
            if ($cantidadDisponible-$cantidadNecesaria<0){
                $retorno [ ] = $ingrediente;
            }
        }
        return $retorno;
    }
    
    
    /**
     * Verifica si es posible realizar la cantidad especificada, en caso
     * de que no se pueda devuelve un array con los faltantes obligatorios
     */
    public function comprobarDisponibilidad(&$productosUtilizados,$cantidadAElaborar,$ingredientes){
        $faltantes = array(); // variable para determinar los faltantes 
        foreach ($this->getIngredientes() as $ingrediente) {
            if (!$ingredientes->contains($ingrediente)){
                $cantidadNecesaria = $ingrediente->getTasa()->getValor() * $ingrediente->getCantidad();
                $cantidadDisponible = $ingrediente->getProductoProduccion()->getTasa()->getValor() * $ingrediente->getProductoProduccion()->getCantidad();
                $cantidadUtilizadaEnItems = 0;
                if(array_key_exists($ingrediente->getProductoProduccion()->getId(), $productosUtilizados)){
                    $cantidadUtilizadaEnItems = $productosUtilizados[$ingrediente->getProductoProduccion()->getId()];
                }
                if (($cantidadDisponible-$cantidadUtilizadaEnItems-($cantidadNecesaria*$cantidadAElaborar)>0)){
                    $cantidadUtilizada = ($cantidadNecesaria*$cantidadAElaborar)/$ingrediente->getProductoProduccion()->getTasa()->getValor() ;//En la unidad del Producto de Produccion 
                    if(array_key_exists($ingrediente->getProductoProduccion()->getId(), $productosUtilizados)){
                        $productosUtilizados[$ingrediente->getProductoProduccion()->getId()] = $productosUtilizados[$ingrediente->getProductoProduccion()->getId()] + $cantidadUtilizada;
                    }else{
                        $productosUtilizados[$ingrediente->getProductoProduccion()->getId()] = $cantidadUtilizada;
                    }
                }else{
                    $faltantes[] = $ingrediente;
                }
            }
        }
        return $faltantes;
    }
    /**
     * Descontar 
     */
    public function descontar($cantidadAElaborar,$ingredientes = null,&$superados){
        foreach ($this->getIngredientes() as $ingrediente) {
            if (!$ingredientes->contains($ingrediente)){
                //En la tasa del Producto para la Produccion
                $cantidadNecesaria = ($ingrediente->getTasa()->getValor() * $ingrediente->getCantidad() * $cantidadAElaborar)/$ingrediente->getProductoProduccion()->getTasa()->getValor();
                $ingrediente->getProductoProduccion()->descontar($cantidadNecesaria,$superados);
            }
        }
    }
    
    /*public function comprobarDisponibilidad(Tasa $tasa,$cantidad){
        $retorno = array();
        $cantidadTotal = $tasa->getValor() * $cantidad;
        foreach ($this->getIngredientesObligatorios() as $ingrediente) {
            $cantidadNecesaria = ($ingrediente->getTasa()->getValor() * $ingrediente->getCantidad())*$cantidadTotal;
            $cantidadDisponible = $ingrediente->getProductoProduccion()->getTasa()->getValor() * $ingrediente->getProductoProduccion()->getCantidad();
            if ($cantidadDisponible-$cantidadNecesaria<0){
                $retorno [ ] = $ingrediente;
            }
        }
        return $retorno;
    }*/

    /**
     * Get ingredientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getIngredientesObligatorios() {
        $ingredientesObligatorios = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($this->getIngredientes() as $ingrediente) {
            if ($ingrediente->getObligatorio()) {
                $ingredientesObligatorios [] = $ingrediente;
            }
        }
        return $ingredientesObligatorios;
    }

    

    /**
     * Set id
     *
     * @param integer $id
     * @return Plato
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
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
     * Add ingredientes
     *
     * @param SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     * @return Plato
     */
    public function addIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes) {
        $this->ingredientes[] = $ingredientes;

        return $this;
    }

    /**
     * Remove ingredientes
     *
     * @param SisGG\FinalBundle\Entity\Ingrediente $ingredientes
     */
    public function removeIngrediente(\SisGG\FinalBundle\Entity\Ingrediente $ingredientes) {
        $this->ingredientes->removeElement($ingredientes);
    }

    /**
     * Get ingredientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getIngredientes() {
        return $this->ingredientes;
    }


    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Plato
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
     * Set precioCosto
     *
     * @param float $precioCosto
     * @return Plato
     */
    public function setPrecioCosto($precioCosto)
    {
        $this->precioCosto = $precioCosto;
    
        return $this;
    }

    /**
     * Get precioCosto
     *
     * @return float 
     */
    public function getPrecioCosto()
    {
        return $this->precioCosto;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     * @return Plato
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadDeseada
     *
     * @param float $cantidadDeseada
     * @return Plato
     */
    public function setCantidadDeseada($cantidadDeseada)
    {
        $this->cantidadDeseada = $cantidadDeseada;
    
        return $this;
    }

    /**
     * Get cantidadDeseada
     *
     * @return float 
     */
    public function getCantidadDeseada()
    {
        return $this->cantidadDeseada;
    }

    /**
     * Set precioVenta
     *
     * @param float $precioVenta
     * @return Plato
     */
    public function setPrecioVenta($precioVenta)
    {
        $this->precioVenta = $precioVenta;
    
        return $this;
    }

    /**
     * Get precioVenta
     *
     * @return float 
     */
    public function getPrecioVenta()
    {
        return $this->precioVenta;
    }

    /**
     * Set cantidadMinima
     *
     * @param float $cantidadMinima
     * @return Plato
     */
    public function setCantidadMinima($cantidadMinima)
    {
        $this->cantidadMinima = $cantidadMinima;
    
        return $this;
    }

    /**
     * Get cantidadMinima
     *
     * @return float 
     */
    public function getCantidadMinima()
    {
        return $this->cantidadMinima;
    }

    /**
     * Set margen
     *
     * @param float $margen
     * @return Plato
     */
    public function setMargen($margen)
    {
        $this->margen = $margen;
    
        return $this;
    }

    /**
     * Get margen
     *
     * @return float 
     */
    public function getMargen()
    {
        return $this->margen;
    }

    /**
     * Set margenMin
     *
     * @param float $margenMin
     * @return Plato
     */
    public function setMargenMin($margenMin)
    {
        $this->margenMin = $margenMin;
    
        return $this;
    }

    /**
     * Get margenMin
     *
     * @return float 
     */
    public function getMargenMin()
    {
        return $this->margenMin;
    }

    /**
     * Set supera
     *
     * @param boolean $supera
     * @return Plato
     */
    public function setSupera($supera)
    {
        $this->supera = $supera;
    
        return $this;
    }

    /**
     * Get supera
     *
     * @return boolean 
     */
    public function getSupera()
    {
        return $this->supera;
    }


    /**
     * Set iva
     *
     * @param \SisGG\FinalBundle\Entity\IVA $iva
     * @return Plato
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
    


    /**
     * Set foto
     *
     * @param \SisGG\FinalBundle\Entity\Image $foto
     * @return Plato
     */
    public function setFoto(\SisGG\FinalBundle\Entity\Image $foto = null)
    {
        $this->foto = $foto;
    
        return $this;
    }

    /**
     * Get foto
     *
     * @return \SisGG\FinalBundle\Entity\Image 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set superaMin
     *
     * @param boolean $superaMin
     * @return Plato
     */
    public function setSuperaMin($superaMin)
    {
        $this->superaMin = $superaMin;
    
        return $this;
    }

    /**
     * Get superaMin
     *
     * @return boolean 
     */
    public function getSuperaMin()
    {
        return $this->superaMin;
    }
    
   public function isMargenSuperior(){
        if ($this->margen==null)
            $this->margen=0.00;
        if ($this->margenMin==null)
            $this->margenMin=0.00;
        if ($this->margenMin > $this->margen){
            $this->supera=true;
            return true;
        }
        $this->supera=false;
        return false;
    }
    //Actualiza el precio y los ceoficientes de los ingredientes y su precio total
    public function actualizarIngredientes(){
        foreach ($this->getIngredientes() as $value) {
           $value->calcularCoeficiente();
        }
        $this->sumarCosto();
        
    }
    public function isSuperaMin(){
        if ($this->cantidad==null)
            $this->cantidad=0.00;
        if ($this->cantidadMinima==null)
            $this->cantidadMinima=0.00;
            
        if ($this->cantidadMinima > $this->cantidad){
            $this->superaMin=true;
            return true;
        }
        $this->superaMin=false;
        return false;
    }
    //Verifica si el producto pre-elaborado contiene o se repite un un ingrediente con ese producto produccion
    public function existeIngrediente(Ingrediente $ingrediente, $tipo=null) {
        foreach ($this->getIngredientes() as $value) {
            If ($ingrediente->getProductoProduccion() == $value->getProductoProduccion()){
                if ($tipo!=null){
                    return true;
                }
                if (($ingrediente->getId()!=$value->getId()))
                return true;
            }
                
        }
        return false;
    }

    public function sumarCosto() {
        $total = 0.00;
        $costo=0.00;
        foreach ($this->getIngredientes() as $value) {
            $costo=$value->getProductoProduccion()->getPrecioCosto() * $value->getCoeficiente()*$value->getCantidad();
            $value->calcularPrecioCosto($costo);
            $total = $costo + $total;
        }
         $this->setPrecioCosto($total);
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $descuentos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredientes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add descuentos
     *
     * @param \SisGG\FinalBundle\Entity\DescuentoProductoVenta $descuentos
     * @return Plato
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

    /**
     * Set tasa
     *
     * @param \SisGG\FinalBundle\Entity\Tasa $tasa
     * @return Plato
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null)
    {
        $this->tasa = $tasa;
    
        return $this;
    }

    /**
     * Get tasa
     *
     * @return \SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa()
    {
        return $this->tasa;
    }
    public function getConIng() {
        return $this->conIng;
    }

    public function setConIng($conIng) {
        $this->conIng = $conIng;
    }


}