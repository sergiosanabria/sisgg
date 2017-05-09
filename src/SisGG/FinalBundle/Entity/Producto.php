<?php

namespace SisGG\FinalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="SisGG\FinalBundle\Entity\ProductoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @UniqueEntity(fields="nombre", message="Ya existe un producto con este nombre. Ingrese otro.")
 */
class Producto implements \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    protected $id;

    /**
     * @Gedmo\Versioned  
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ingrese un nombre.")
     * @Gedmo\Versioned
     */
    protected $nombre;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $activo;

    /**
     *
     * @ORM\ManyToOne (targetEntity="Empresa", inversedBy="productos")
     */
    private $empresa;

    //pon tu codigo aqui

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
     * Set nombre
     *
     * @param string $nombre
     * @return Producto
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
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     * @return Producto
     */
    public function setActivo($activo) {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean 
     */
    public function getActivo() {
        return $this->activo;
    }

    /**
     * Set tasa
     *
     * @param SisGG\FinalBundle\Entity\Tasa $tasa
     * @return Producto
     */
    public function setTasa(\SisGG\FinalBundle\Entity\Tasa $tasa = null) {
        $this->tasa = $tasa;

        return $this;
    }

    /**
     * Get tasa
     *
     * @return SisGG\FinalBundle\Entity\Tasa 
     */
    public function getTasa() {
        return $this->tasa;
    }

    public function isPreElaborado() {
        return false;
    }

    public function isMateriaPrima() {
        return false;
    }

    public function isPlato() {
        return false;
    }

    public function isMercaderia() {
        return false;
    }

    public function isMantenimiento() {
        return false;
    }

    public function existeProducto($productos, $tipo = null) {
        foreach ($productos as $value) {
            If ($this->getNombre() == $value->getNombre()) {
                if ($tipo != null) {
                    return true;
                }
                if (($this->getId() != $value->getId())) {
                    return true;
                }
            }
        }
        return false;
    }

    public function estadisticas($compras = null) {
        if ($compras == null)
            $compras = $this->getEmpresa()->getCompras();
        $monto = 0.00;
        $cantidad = 0.00;
        $tasas = null;
        if ($compras == null) {
            return null;
        }
        foreach ($compras as $value) {
            foreach ($value->getItem() as $producto) {
                if ($producto->getProducto() == $this) {
                    $id = $producto->getTasa()->getId();
                    $monto = $producto->getTotal();
                    $cantidad = $producto->getCantidad();
                    $this->obtenerTasa($id, $monto, $cantidad, $tasas);
                }
            }
        }

        for ($i = 0; $i < count($tasas); $i++) {
            $unaTasa = $this->getEmpresa()->obtenerTasa($tasas[$i]['id']);
            $tasas[$i]['id'] = $unaTasa;
        }

        return $tasas;
    }

    private function obtenerTasa($id, $monto, $cantidad, &$tasas) {
        if ($tasas == null) {
            $tasas[] = array('id' => $id, 'monto' => $monto, 'cantidad' => $cantidad);
            return false;
        }
        for ($i = 0; $i < count($tasas); $i++) {
            if ($tasas[$i]['id'] == $id) {
                $tasas[$i]['monto'] = $monto + $tasas[$i]['monto'];
                $tasas[$i]['cantidad'] = $cantidad + $tasas[$i]['cantidad'];
                return true;
            }
        }
        $tasas[] = array('id' => $id, 'monto' => $monto, 'cantidad' => $cantidad);
        return false;
    }

    public function catPadres($flat = true) {
        $categoria = '';
        $padre = $this->getCategoria();
        if ($flat) {
            for ($i = 0; $i < 100; $i++) {

                if ($padre == null) {
                    break;
                }
                $categoria = $padre->getNombre() . $categoria;
                $padre = $padre->getPadre();
            }

            return $categoria;
        }else{
            $categoria=null;
            for ($i = 0; $i < 100; $i++) {

                if ($padre == null) {
                    break;
                }
                $categoria[]= $padre->getNombre();
                $padre = $padre->getPadre();
            }
            
            
            return array_reverse($categoria);
        }
    }

    /**
     * Set empresa
     *
     * @param SisGG\FinalBundle\Entity\Empresa $empresa
     * @return Producto
     */
    public function setEmpresa(\SisGG\FinalBundle\Entity\Empresa $empresa = null) {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return SisGG\FinalBundle\Entity\Empresa 
     */
    public function getEmpresa() {
        return $this->empresa;
    }
    
    public function getQR(){
        $qr=new \Endroid\QrCode\QrCode($this->getEmpresa()->getIp().'/SisGG/web/public/p/qrPV'.$this->getId().'.html');
        $qr->setSize(2000);
        $qr->render('/tmp/qrPV'.$this->getId());
        return '/tmp/qrPV'.$this->getId();
    }

}