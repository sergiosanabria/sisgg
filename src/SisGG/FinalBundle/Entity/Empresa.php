<?php

namespace SisGG\FinalBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SisGG\FinalBundle\Entity\Provincia;
use SisGG\FinalBundle\Entity\Proveedor;
use SisGG\FinalBundle\Entity\Direccion;
use SisGG\FinalBundle\Entity\CategoriaProductoVenta;
use SisGG\FinalBundle\Entity\CategoriaProductoProduccion;
use SisGG\FinalBundle\Entity\Mercaderia;
use SisGG\FinalBundle\Entity\MateriaPrima;
use SisGG\FinalBundle\Entity\PreElaborado;
use SisGG\FinalBundle\Entity\Ingrediente;
use SisGG\FinalBundle\Entity\Mensaje;

/**
 * @ORM\Entity
 * @Gedmo\Loggable
 */
class Empresa implements \Serializable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Gedmo\Versioned
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     * @Assert\NotBlank(message="Ingrese un nombre para su empresa")
     * @Gedmo\Versioned
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Ingrese un slogan para su empresa")
     * @Gedmo\Versioned
     */
    private $slogan;

    /**
     * @Assert\Choice(choices = {true, false}, message = "Tipo no válido.")
     * @ORM\Column(type="boolean",nullable=true)
     * @Gedmo\Versioned
     * 
     */
    protected $responsable;

    /**
     * @Assert\Range(
     *      min = 10,
     *      max = 80,
     *      invalidMessage = "Rango de edad incorrecto."
     * )
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    protected $edad;
    
    /**
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email valido."
     * )
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @ORM\Column(type="string",  nullable=true)
     * @Assert\NotBlank(message="Ingrese un contraseña para la cuenta su empresa.")
     * @Gedmo\Versioned
     */
    private $contrasenia;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank
     * @Gedmo\Versioned
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $cuit;

    /** 
     * @ORM\Column(type="string",nullable=true) 
     * @Gedmo\Versioned 
     */ 
    private $ip;
    
    /**
     * @Assert\Date(message="El formato de la fecha es incorrecto.")
     * @ORM\Column(type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $inicioAct;

    /**
     * @ORM\OneToOne(targetEntity="Direccion")
     * @ORM\JoinColumn(name="direccion_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $direccion;

    /**
     * @ORM\OneToMany(targetEntity="Telefono",mappedBy="empresa",cascade="persist")
     */
    protected $telefonos;

    /**
     * @ORM\OneToOne(targetEntity="Image",cascade={"persist"})
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id", nullable=true)
     */
    protected $foto;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $carpeta;

    /**
     * @ORM\Column(type="string",nullable=true)
     * @Gedmo\Versioned
     */
    private $carpetaAuditoria;

    /**
     * @ORM\OneToMany(targetEntity="Cliente",mappedBy="empresa")
     */
    private $clientes;

    /**
     * @ORM\OneToMany(targetEntity="CategoriaProductoVenta", mappedBy="empresa")
     */
    private $categoriasPV;

    /**
     * @ORM\OneToMany(targetEntity="CategoriaProductoProduccion", mappedBy="empresa")
     */
    private $categoriasPP;

    /**
     * @ORM\OneToMany(targetEntity="Proveedor", mappedBy="empresa")
     */
    private $proveedores;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="empresa")
     */
    private $productos;

    /**
     * @ORM\OneToMany(targetEntity="Provincia", mappedBy="empresa")
     */
    private $provincias;

    /**
     * @ORM\OneToMany(targetEntity="RegistroProduccion", mappedBy="empresa")
     */
    private $registrosProduccion;

    /**
     * @ORM\OneToMany(targetEntity="UnidadMedida", mappedBy="empresa")
     */
    private $ums;

    /**
     * @ORM\OneToMany(targetEntity="NotaPedido", mappedBy="empresa")
     */
    private $notas;

    /**
     * @ORM\OneToMany(targetEntity="Compra", mappedBy="empresa")
     */
    private $compras;

    /**
     * @ORM\OneToMany(targetEntity="FacturaServicio", mappedBy="empresa")
     */
    private $facturasServicio;

    /**
     * @ORM\OneToMany(targetEntity="Servicio", mappedBy="empresa")
     */
    private $servicios;

    /**
     * @ORM\OneToMany(targetEntity="IVA", mappedBy="empresa")
     */
    private $ivas;

    /**
     * @ORM\OneToMany(targetEntity="Mantenimiento", mappedBy="empresa")
     */
    private $mantenimientos;

    /**
     * @ORM\OneToMany(targetEntity="LibroIvaCompra", mappedBy="empresa")
     */
    private $ivaCompras;

    /**
     * @ORM\OneToMany(targetEntity="TipoPago", mappedBy="empresa")
     */
    private $tipoPagos;

    /**
     * @ORM\OneToMany(targetEntity="PersonaEmpleado", mappedBy="empresa")
     */
    private $personaEmpleados;

    /**
     * @ORM\OneToMany(targetEntity="CargoSistema", mappedBy="empresa")
     */
    private $cargosSistema;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="empresa")
     */
    private $usuarios;

    /**
     * @ORM\OneToMany(targetEntity="Caja", mappedBy="empresa")
     */
    private $cajas;

    /**
     * @ORM\OneToMany(targetEntity="Concepto", mappedBy="empresa")
     */
    private $conceptos;

    /**
     * @ORM\OneToMany(targetEntity="TipoCobro", mappedBy="empresa")
     */
    private $tiposCobro;

    /**
     * @ORM\ManyToOne(targetEntity="CondicionIva")
     */
    private $condicionIva;

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

    //SergioINICIO
    //ProveedorINICIO
    public function addProveedor(Proveedor $prov, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $existe = $em->getRepository('SisGGFinalBundle:Proveedor')->findOneBy(array('razonSocial' => $prov->getRazonSocial()));
        if ($existe == null) {
            if ($prov->getCuit() != '')
                $existe = $em->getRepository('SisGGFinalBundle:Proveedor')->findOneBy(array('cuit' => $prov->getCuit()));
            else
                $existe = null;
            if ($existe == null) {
                $prov->setActivo(true);
                $this->guardar($prov, $c);
                $this->guardar($prov->getDireccion(), $c);
                if ($prov->getTelefonos() != null) {
                    foreach ($prov->getTelefonos() as $value) {
                        $value->setProveedor($prov);
                    }
                }
                $this->addProveedore($prov);
                $prov->setEmpresa($this);
                $em->flush();
                return null;
            } else {
                return "El proveedor con el CUIT " . $prov->getCuit() . " ya se encuentra registrado.";
            }
        } else {
            return "El proveedor con la Razón social " . $prov->getRazonSocial() . " ya se encuentra registrado.";
        }
    }

    public function modificarProveedor(Proveedor $prov, $datos = null, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $proveedores = $em->getRepository('SisGGFinalBundle:Proveedor')->findAll();
        $r = $prov->existe($proveedores);
        if ($r == null) {
            if ($prov->getTelefonos() != null) {
                foreach ($prov->getTelefonos() as $value) {
                    $value->setProveedor($prov);
                }
                foreach ($prov->getTelefonos() as $item) {
                    foreach ($datos as $key => $toDel) {
                        if ($toDel->getId() === $item->getId()) {
                            unset($datos[$key]);
                        }
                    }
                }
                foreach ($datos as $item) {
                    $item->setProveedor(null);
                    $em->remove($item);
                }
                $em->persist($prov);
                $em->flush();
                return null;
            }
        } else {
            return $r;
        }
    }

    public function eliminarProveedor(Proveedor $prov, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($prov->getActivo()) {
            $prov->setActivo(false);
            $em->flush();
            return null;
        } else {
            return "Este proveedor ya se encuentra eliminado.";
        }
    }

    //ProveedorFIN
    //MercaderiaINICIO

    public function altaMercaderia(Mercaderia $mercaderia, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mercaderias = $em->getRepository('SisGGFinalBundle:Mercaderia')->findBy(array('activo' => 1));
        if ($mercaderia->getCategoria()->getHijo()->isEmpty()) {
            if (!($mercaderia->existeProducto($mercaderias, 1))) {
                $mercaderia->getProveedor()->addMercaderia($mercaderia);
                $mercaderia->getCategoria()->addProductoVenta($mercaderia);
                $mercaderia->setActivo(true);
                $mercaderia->setEmpresa($this);
                if ($mercaderia->getPrecioCosto() == null) {
                    $mercaderia->setPrecioCosto(0.00);
                }
                if ($mercaderia->actualizarMargen())
                    $this->addMensaje('Hay productos/platos  con precios de costo igual a cero.', 3, $c);
                if ($mercaderia->isMargenSuperior()) {
                    $this->addMensaje('Hay productos con margen mínimo superior al de ganancia.', 1, $c);
                }
                if ($mercaderia->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
                }
                $this->guardar($mercaderia, $c);
                $em->flush($mercaderia);
                return null;
            } else {
                return "La mercaderia " . $mercaderia->getNombre() . " ya se encuentra registrada.";
            }
        } else {
            return "El producto no pudo ser registrado, puesto que la categoria " . $mercaderia->getCategoria() . " contiene categorias." . $mercaderias[0]->getNombre();
        }
    }

    public function modificarMercaderia(Mercaderia $mercaderia, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mercaderias = $em->getRepository('SisGGFinalBundle:Mercaderia')->findBy(array('activo' => 1));
        if ($mercaderia->getCategoria()->getHijo()->isEmpty()) {
            if (!($mercaderia->existeProducto($mercaderias))) {
                if ($mercaderia->getPrecioCosto() == null) {
                    $mercaderia->setPrecioCosto(0.00);
                }
                if ($mercaderia->actualizarMargen())
                    $this->addMensaje('Hay productos/platos  con precios de costo igual a cero.', 3, $c);
                if ($mercaderia->isMargenSuperior()) {
                    $this->addMensaje('Hay productos con margen mínimo superior al de ganancia.', 1, $c);
                }
                if ($mercaderia->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
                }



                $em->flush();
                return null;
            } else {

                return "La mercaderia " . $mercaderia->getNombre() . " ya se encuentra registrada.";
            }
        } else {

            return "El producto no pudo ser modificado, puesto que la categoria " . $mercaderia->getCategoria() . " contiene categorias.";
        }
    }

    public function eliminarMercaderia(Mercaderia $mercaderia, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mercaderia->setActivo(0);
        $em->flush();
        return "El producto " . $mercaderia->getNombre() . " ha sido correctamente eliminado.";
    }
    //MercaderiaFIN
    //MateriaPrimaINICIO
    public function altaMateriaPrima(MateriaPrima $mp, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mps = $em->getRepository('SisGGFinalBundle:MateriaPrima')->findBy(array('activo' => 1));
        if ($mp->getCategoria()->getHijo()->isEmpty()) {
            if (!($mp->existeProducto($mps, 1))) {
                if ($mp->getPrecioCosto() == null) {
                    $mp->setPrecioCosto(0.00);
                }
                if ($mp->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo. ', 2, $c);
                }
                $mp->getProveedor()->addMateriasprima($mp);
                $mp->getCategoria()->addProductoProduccion($mp);
                $mp->setActivo(true);
                $mp->setEmpresa($this);
                $this->guardar($mp, $c);
                $em->flush($mp);
                return null;
            } else {
                return "La materia prima " . $mp->getNombre() . " ya se encuentra registrada.";
            }
        } else {
            return "La metria prima no pudo ser registrada, puesto que la categoria " . $mp->getCategoria() . " contiene categorias.";
        }
    }

    public function modificarMateriaPrima(MateriaPrima $mp, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($mp->getCategoria()->getHijo()->isEmpty()) {
            $mps = $em->getRepository('SisGGFinalBundle:MateriaPrima')->findBy(array('activo' => 1));
            if (!($mp->existeProducto($mps))) {
                if ($mp->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
                }
                if ($mp->getPrecioCosto() == null) {
                    $mp->setPrecioCosto(0.00);
                }
                if ($mp->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo. ', 2, $c);
                }
                $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
                if (($mp->esIngrediente($ingredientes))) {
                    $ingrediente = $mp->obtenerIngrediente($ingredientes);
                    if ($ingrediente != null) {
                        foreach ($ingrediente as $value) {
                            if ($value->getPreElaborado() != null) {
                                $this->modificarPreElaborado($value->getPreElaborado(), $c);
                            } else {
                                $value->getPlato()->actualizarIngredientes();
                                if ($value->getPlato()->actualizarMargen())
                                    $this->addMensaje('Hay productos/platos con precios de costo igual a cero.', 3, $c);
                                if ($value->getPlato()->isMargenSuperior()) {
                                    $this->addMensaje('Hay platos con margen mínimo superior al de ganancia.', 1, $c);
                                }
                            }
                        }
                    }
                }
                $em->flush();
                return null;
            } else {
                return "La materia prima " . $mp->getNombre() . " ya se encuentra registrada.";
            }
        } else {
            return "El producto no pudo ser modificado, puesto que la categoria " . $mp->getCategoria() . " contiene categorias.";
        }
    }

    public function eliminarMateriaPrima(MateriaPrima $mp, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mp->setActivo(0);
        $em->flush();
        return "El producto " . $mp->getNombre() . " ha sido correctamente elimindo.";
    }

    //MateriaPrimaFIN
    //PreElaboradoINICIO
    public function altaPreElaborado(PreElaborado $pe, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $pes = $em->getRepository('SisGGFinalBundle:PreElaborado')->findBy(array('activo' => 1));
        if ($pe->getCategoria()->getHijo()->isEmpty()) {
            if (!($pe->existeProducto($pes, 1))) {
                $pe->getCategoria()->addProductoProduccion($pe);
                if ($pe->getPrecioCosto() == null) {
                    $pe->setPrecioCosto(0.00);
                }
                if ($pe->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo. ', 2, $c);
                }
                $pe->setActivo(true);
                $pe->setEmpresa($this);
                $this->guardar($pe, $c);
                $em->flush($pe);
                return null;
            } else {
                return "El producto pre-elaborado " . $pe->getNombre() . " ya se encuentra registrado.";
            }
        } else {
            return "El producto pre-elaborado no pudo ser registrado, puesto que la categoria " . $pe->getCategoria() . " contiene categorias.";
        }
    }

    public function modificarPreElaborado(PreElaborado $pe, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c, $dePantalla = false) {
        $em = $c->getDoctrine()->getEntityManager();
        $pes = $em->getRepository('SisGGFinalBundle:PreElaborado')->findBy(array('activo' => 1));
        if ($pe->getCategoria()->getHijo()->isEmpty()) {
            if (!($pe->existeProducto($pes))) {
                //if (!($pe->getIngredientes()->isEmpty())) {
                if ($dePantalla && $pe->getConIng()) {
                    $pe->actualizarIngredientes();
                }

                //}
                if ($pe->getPrecioCosto() == null) {
                    $pe->setPrecioCosto(0.00);
                }
                if ($pe->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo. ', 2, $c);
                }
                $ingredientes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
                if (($pe->esIngrediente($ingredientes))) {
                    $ingrediente = $pe->obtenerIngrediente($ingredientes);
                    if ($ingrediente != null) {
                        foreach ($ingrediente as $value) {
                            if ($value->getPreElaborado() != null) {
                                $value->getPreElaborado()->actualizarIngredientes();
                            } else {
                                $value->getPlato()->actualizarIngredientes();
                                if ($value->getPlato()->actualizarMargen())
                                    $this->addMensaje('Hay productos/platos con precios de costo igual a cero.', 3, $c);
                                if ($value->getPlato()->isMargenSuperior()) {
                                    $this->addMensaje('Hay platos con margen mínimo superior al de ganancia.', 1, $c);
                                }
                            }
                        }
                    }
                }
                $em->flush();
                return null;
            } else {
                return "El producto pre-elaborado " . $pe->getNombre() . " ya se encuentra registrado.";
            }
        } else {
            return "El producto pre-elaborado no pudo ser modificado, puesto que la categoria " . $pe->getCategoria() . " contiene categorias.";
        }
    }

    public function eliminarPreElaborado(PreElaborado $pe, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $pe->setActivo(0);
        $em->flush();
        return "El producto pre-elaborado " . $pe->getNombre() . " ha sido correctamente elimindo.";
    }
    //PreElaboradoFIN
    //PlatoINICIO tipoPrecio=1-->margen * precioCosto tipoPrecio=0-->((venta/costo)-1)*100;
    public function altaPlato(Plato $plato, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $platos = $em->getRepository('SisGGFinalBundle:Plato')->findBy(array('activo' => 1));
        if ($plato->getCategoria()->getHijo()->isEmpty()) {
            if (!($plato->existeProducto($platos))) {
                $plato->getCategoria()->addProductoVenta($plato);
                $plato->setActivo(true);
                $plato->setEmpresa($this);
//                $valor = 0.00;
//                if ($plato->getTipoPrecio()){
//                    $valor=$plato->getMargen()*(($plato->getPrecioCosto()/100)+1);
//                    $plato->setPrecioVenta($valor);
//                }else{
//                    if ($plato->getPrecioCosto()!=0){
//                        $valor=(($plato->getPrecioVenta()/$plato->getPrecioCosto())-1)*100;
//                        $plato->setMargen($valor);                        
//                    }
//                }
                if ($plato->getPrecioCosto() == null) {
                    $plato->setPrecioCosto(0.00);
                }
                if ($plato->actualizarMargen())
                    $this->addMensaje('Hay productos/platos  con precios de costo igual a cero.', 3, $c);
                if ($plato->isMargenSuperior()) {
                    $this->addMensaje('Hay productos con margen mínimo superior al de ganancia.', 1, $c);
                }
                if ($plato->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
                }
                $this->guardar($plato, $c);
                $em->flush($plato);
                return null;
            } else {
                return "El plato " . $plato->getNombre() . " ya se encuentra registrado.";
            }
        } else {
            return "El producto no pudo ser registrado, puesto que la categoria " . $plato->getCategoria() . " contiene categorias.";
        }
    }

    public function modificarPlato(Plato $plato, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c, $dePantalla = false) {
        $em = $c->getDoctrine()->getEntityManager();
        $platos = $em->getRepository('SisGGFinalBundle:Plato')->findBy(array('activo' => 1));
        if ($plato->getCategoria()->getHijo()->isEmpty()) {
            if (!($plato->existeProducto($platos))) {
//                $valor=0.00;
//                if ($plato->getTipoPrecio()==1){
//                    $valor=$plato->getMargen()*(($plato->getPrecioCosto()/100)+1);
//                    $plato->setPrecioVenta($valor);
//                }else{
//                    if ($plato->getPrecioCosto()!=0){
//                        $valor=(($plato->getPrecioVenta()/$plato->getPrecioCosto())-1)*100;
//                        $plato->setMargen($valor);                        
//                    }
//                }
                if ($dePantalla && $plato->getConIng()) {
                    $plato->actualizarIngredientes();
                }
                if ($plato->getPrecioCosto() == null) {
                    $plato->setPrecioCosto(0.00);
                }
                if ($plato->actualizarMargen())
                    $this->addMensaje('Hay productos/platos  con precios de costo igual a cero.', 3, $c);
                if ($plato->isMargenSuperior()) {
                    $this->addMensaje('Hay productos con margen mínimo superior al de ganancia.', 1, $c);
                }
                if ($plato->isSuperaMin()) {
                    $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
                }

                if ($plato->isMargenSuperior()) {
                    $this->addMensaje('Hay productos con margen mínimo  superior al de ganancia.', 1, $c);
                }

                $em->flush();
                return null;
            } else {
                return "El plato " . $plato->getNombre() . " ya se encuentra registrado.";
            }
        } else {

            return "El producto no pudo ser modificado, puesto que la categoria " . $plato->getCategoria() . " contiene categorias.";
        }
    }

    public function eliminarPlato(Plato $pe, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $pe->setActivo(0);
        $em->flush();
        return "El producto pre-elaborado " . $pe->getNombre() . " ha sido correctamente elimindo.";
    }


    //PlatoFIN
    //MantenimientoINICIO
    public function altaMantenimiento(Mantenimiento $mantenimiento, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mantenimientos = $em->getRepository('SisGGFinalBundle:Mantenimiento')->findBy(array('activo' => 1));
        if (!($mantenimiento->existeProducto($mantenimientos, 1))) {
            $mantenimiento->setActivo(true);
            $mantenimiento->setEmpresa($this);
            $this->guardar($mantenimiento, $c);
            $em->flush($mantenimiento);
            return null;
        } else {
            return "El producto mantenimiento " . $mantenimiento->getNombre() . " ya se encuentra registrado.";
        }
    }

    public function modificarMantenimiento(Mantenimiento $mantenimiento, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mantenimientos = $em->getRepository('SisGGFinalBundle:Mantenimiento')->findBy(array('activo' => 1));
        if (!($mantenimiento->existeProducto($mantenimientos))) {
            $em->flush();
            return null;
        } else {
            return "El producto mantenimiento " . $mantenimiento->getNombre() . " ya se encuentra registrado.";
        }
    }

    public function eliminarMantenimiento(Mantenimiento $mantenimiento, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $mantenimiento->setActivo(0);
        $em->flush();
        return "El producto " . $mantenimiento->getNombre() . " ha sido correctamente eliminado.";
    }

    //MantenimientoFIN
    //IngredienteINICIO

    public function altaIngrediente(Ingrediente $ingrediente, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $padre = $ingrediente->getPreElaborado();
        if ($padre == null)
            $padre = $ingrediente->getPlato();
        if (!($ingrediente->getProductoProduccion() == $padre)) {
            if (!($padre->existeIngrediente($ingrediente, 1))) {
                $padre->addIngrediente($ingrediente);
                $ingrediente->calcularCoeficiente();
                $padre->sumarCosto();
                if ($ingrediente->getPreElaborado() == null) {
                    if ($ingrediente->getPlato()->actualizarMargen())
                        $this->addMensaje('Hay productos/platos con precios de costo igual a cero.', 3, $c);
                }else {
                    $this->modificarPreElaborado($ingrediente->getPreElaborado(), $c);
                }
                $this->guardar($ingrediente, $c);
                $em->flush();
                return null;
            } else {
                return "El producto ingrediente " . $ingrediente->getProductoProduccion() . " ya se encuentra agregado en la receta.";
            }
        } else {

            return "El ingrediente agregado es el mismo que el producto pre-elebaorado $padre. Seleecione otro producto.";
        }
    }

    public function modificarIngrediente(Ingrediente $ingrediente, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $padre = $ingrediente->getPreElaborado();
        if ($padre == null)
            $padre = $ingrediente->getPlato();
        if (!($ingrediente->getProductoProduccion() == $padre)) {
            if (!$padre->existeIngrediente($ingrediente)) {
                $ingrediente->calcularCoeficiente();
                $padre->sumarCosto();
                if ($ingrediente->getPreElaborado() == null) {
                    if ($ingrediente->getPlato()->actualizarMargen())
                        $this->addMensaje('Hay productos/platos con precios de costo igual a cero.', 3, $c);
                }else {
                    $this->modificarPreElaborado($ingrediente->getPreElaborado(), $c);
                }
                $em->flush();
                return null;
            } else {
                return "El producto ingrediente " . $ingrediente->getProductoProduccion() . " ya se encuentra agregado en la receta.";
            }
        } else {
            return "El ingrediente agregado es el mismo que el producto pre-elebaorado $padre. Seleecione otro producto.";
        }
    }

    public function eliminarIngrediente(Ingrediente $ingrediente, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($ingrediente->getPreElaborado() != null) {
            $ingrediente->getPreElaborado()->removeIngrediente($ingrediente);
            $ingrediente->getPreElaborado()->actualizarIngredientes();
            $this->modificarPreElaborado($ingrediente->getPreElaborado(), $c);
        } else {
            $ingrediente->getPlato()->removeIngrediente($ingrediente);
            $ingrediente->getPlato()->actualizarIngredientes();
        }
        $this->eliminar($ingrediente, $c);
        $em->flush();
        return "El ingrediente" . $ingrediente->getProductoProduccion() . " ha sido correctamente elimindo.";
    }

    //IngredienteFIN
    //RegitrarProduccionINICIO
     public function registrarProduccion1(PreElaborado $pe, $cantidad, $desc, $array, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $pe->registrarProduccion1($cantidad, $array);
        foreach ($pe->getIngredientes() as $value) {
            if ($value->getProductoProduccion()->isSuperaMin()) {
                $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
            }
        }
        $o->setEmpresa($this);
        $o->setDescripcion($desc);
        $o->setTipo(1);
        $this->guardar($o, $c);
        $em->flush();
        return "OK";
    }

    //RegitrarProduccionFIN
    //PerdidaProduccionINICIO
    public function perdidaProduccion1(ProductoProduccion $pe, $cantidad, $desc, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $stock = $pe->getCantidad();
        if ($stock == null) {
            $pe->setCantidad(0.00);
            $stock = 0.00;
        }
        $resto = $stock - $cantidad;
        if ($resto < 0) {
            $resto = 0.00;
        }
        $pe->setCantidad($resto);
        if ($pe->isSuperaMin()) {
            $this->addMensaje('Hay productos con stock mínimo.', 2, $c);
        }

        $newReg = new RegistroProduccion();
        $newReg->setCantidad($cantidad);
        $newReg->setCosto($cantidad * $pe->getPrecioCosto());
        $newReg->setDescripcion($desc);
        $newReg->setEmpresa($this);
        $newReg->setFecha();
        $newReg->setProducto($pe);
        $newReg->setTasa($pe->getTasa());
        $newReg->setTipo(0);
        $this->guardar($newReg, $c);
        $em->flush();
        return "OK";
    }


    //PerdidaProduccionFIN
    //UnidadMedidaINICIO
    public function altaUM(UnidadMedida $um, $nombre, $desc, $abrev, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:UnidadMedida')->findOneBy(array('nombre' => $um->getNombre()));
        if ($o == null) {
            $this->guardar($um, $c);
            $tasa = new Tasa();
            $tasa->setNombre($nombre);
            $tasa->setAbrev($abrev);
            $tasa->setDescripcion($desc);
            $tasa->setValor(1);
            $tasa->setPivote(true);
            $tasa->setUm($um);
            $um->addTasa($tasa);
            $um->setEmpresa($this);
            $this->guardar($tasa, $c);
            $em->flush();
            return null;
        } else {
            return "La unidad de medida " . $um->getNombre() . " ya se encuentra registrada.";
        }
    }

    public function modificarUM(UnidadMedida $um, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:UnidadMedida')->findOneBy(array('nombre' => $um->getNombre()));
        if ($o != null) {
            if ($o->getId() == $um->getId()) {
                $em->flush();
            } else {
                return "La unidad de medida " . $um->getNombre() . " ya se encuentra registrada.";
            }
        } else {
            $em->flush();
        }
    }

    public function eliminarUM(UnidadMedida $um, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $ingtes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
        $productos = $em->getRepository('SisGGFinalBundle:Producto')->findAll();
        if (($this->tasasAsociadas($um->getTasas(), $ingtes, $productos))) {
            $this->eliminar($um, $c);
            $em->flush();
            return "La tasa " . $um->getNombre() . " ha sido correctamente eliminada";
        }
        return null;
    }

    private function tasasAsociadas($tasas, $ingtes, $productos) {
        foreach ($tasas as $value) {
            if (($value->perteneceAIngredientes($ingtes))) {
                return false;
            }

            if (($value->perteneceAProductos($productos))) {
                return false;
            }
        }
        return true;
    }

    //UnidadMedidaFIN
    //TasaINICIO
    public function altaTasa(Tasa $tasa, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:Tasa')->findOneBy(array('nombre' => $tasa->getNombre()));
        if ($o == null) {
            $o = $em->getRepository('SisGGFinalBundle:Tasa')->findOneBy(array('abrev' => $tasa->getAbrev()));
            if ($o == null) {
                $tasa->setPivote(false);
                $tasa->getUm()->addTasa($tasa);
                $this->guardar($tasa, $c);
                $em->flush();
                return null;
            } else {
                return "La tasa " . $tasa->getNombre() . " o la abreviatura " . $tasa->getAbrev() . " ya se encuentra registrada.";
            }
        } else {
            return "La tasa " . $tasa->getNombre() . " o la abreviatura " . $tasa->getAbrev() . " ya se encuentra registrada.";
        }
    }

    public function modificarTasa(Tasa $tasa, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:Tasa')->findOneBy(array('nombre' => $tasa->getNombre()));
        if ($o == null) {
            $o = $em->getRepository('SisGGFinalBundle:Tasa')->findOneBy(array('abrev' => $tasa->getAbrev()));
            if ($o == null) {
                $em->flush();
            } else {
                if ($o->getId() == $tasa->getId()) {
                    $em->flush();
                } else {
                    return "La tasa " . $tasa->getNombre() . " o la abreviatura " . $tasa->getAbrev() . " ya se encuentra registrada.";
                }
            }
        } else {
            if ($o->getId() == $tasa->getId()) {
                $o = $em->getRepository('SisGGFinalBundle:Tasa')->findOneBy(array('abrev' => $tasa->getAbrev()));
                if ($o == null) {
                    $em->flush();
                } else {
                    if ($o->getId() == $tasa->getId()) {
                        $em->flush();
                    } else {
                        return "La tasa " . $tasa->getNombre() . " o la abreviatura " . $tasa->getAbrev() . " ya se encuentra registrada.";
                    }
                }
            } else {
                return "La tasa " . $tasa->getNombre() . " o la abreviatura " . $tasa->getAbrev() . " ya se encuentra registrada.";
            }
        }
    }

    public function eliminarTasa(Tasa $tasa, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $ingtes = $em->getRepository('SisGGFinalBundle:Ingrediente')->findAll();
        $productos = $em->getRepository('SisGGFinalBundle:Producto')->findAll();
        if ($tasa->getPivote()) {
            return null;
        }


        if (!($tasa->perteneceAIngredientes($ingtes))) {
            if (!($tasa->perteneceAProductos($productos))) {
                $this->eliminar($tasa, $c);
                $em->flush();
                return "La tasa " . $tasa->getNombre() . " ha sido correctamente eliminada";
            }
        }
        return null;
    }

    //TasaFIN
    //CategoriaPVINICIO

    public function altaCategoriaPV(CategoriaProductoVenta $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:CategoriaProductoVenta')->findOneBy(array('nombre' => $categoria->getNombre()));
        $categoria->setActivo(1);
        if ($o == null) {
            if ($categoria->getPadre() != null) {
                if ($categoria->getPadre()->getProductoVenta()->isEmpty()) {
                    $this->guardar($categoria, $c);
                    $categoria->setEmpresa($this);
                    $this->addCategoriasPV($categoria);
                    $em->flush();
                } else {
                    return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria " . $categoria->getPadre()->getNombre() . " posee productos. Seleecione otra que no contega.";
                }
            } else {
                $this->guardar($categoria, $c);
                $categoria->setEmpresa($this);
                $this->addCategoriasPV($categoria);
                $em->flush();
            }
        } else {
            return "La categoria " . $categoria->getPadre() . " ya se encuentra registrada.";
        }
    }

    public function modificarCategoriaPV(CategoriaProductoVenta $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        //$o=$em->getRepository('SisGGFinalBundle:CategoriaProductoVenta')->findOneBy(array('nombre'=>$categoria->getNombre(), 'id'=>$categoria->getId()));
        $query = $em->createQuery('SELECT c from SisGGFinalBundle:CategoriaProductoVenta c WHERE  (c.nombre  = :n and c.id <> :id )'); //and c.activo = 1 ver el unique de la tabla
        $query->setParameters(array(
            ':n' => $categoria->getNombre(),
            ':id' => $categoria->getId()
        ));
        if ($query->getResult() == null) {
            if ($categoria->getPadre() != null) {
                if ($categoria->getPadre()->getProductoVenta()->isEmpty()) {
                    if ($categoria->getPadre() != $categoria) {
                        $em->flush();
                    } else {
                        return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria padre " . $categoria->getPadre()->getNombre() . " es la misma que la que desea modificar.";
                    }
                } else {
                    return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria " . $categoria->getPadre()->getNombre() . " posee productos. Seleecione otra que no contega.";
                }
            } else {
                $em->flush();
            }
        } else {
            return "La categoria " . $categoria->getNombre() . " ya se encuentra registrada.";
        }
    }

    public function eliminarCategoriaPV(CategoriaProductoVenta $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if (($categoria->getProductoVenta()->isEmpty() && $categoria->getHijo()->isEmpty())) {
            $this->eliminar($categoria, $c);
            $em->flush();
            return null;
        } else {
            if ($categoria->getHijo()->isEmpty()) {
                if ($categoria->vacioConProducto()) {
                    $categoria->setActivo(0);
                    $em->flush();
                    return null;
                } else {
                    return "No se puede eliminar la categoria " . $categoria->getNombre() . " puesto que contiene categorias o productos registradas. Eliminelas primero.";
                }
            } else {
                return "No se puede eliminar la categoria " . $categoria->getNombre() . " puesto que contiene categorias o productos registradas. Eliminelas primero.";
            }
        }
    }

    //CategoriaPVFIN
    //CategoriaPPINICIO

    public function altaCategoriaPP(CategoriaProductoProduccion $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $o = $em->getRepository('SisGGFinalBundle:CategoriaProductoProduccion')->findOneBy(array('nombre' => $categoria->getNombre()));
        $categoria->setActivo(1);
        if ($o == null) {
            if ($categoria->getPadre() != null) {
                if ($categoria->getPadre()->getProductoProduccion()->isEmpty()) {
                    $this->guardar($categoria, $c);
                    $categoria->setEmpresa($this);
                    $this->addCategoriasPP($categoria);
                    $em->flush();
                } else {
                    return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria " . $categoria->getPadre()->getNombre() . " posee productos. Seleecione otra que no contega.";
                }
            } else {
                $this->guardar($categoria, $c);
                $categoria->setEmpresa($this);
                $this->addCategoriasPP($categoria);
                $em->flush();
            }
        } else {
            return "La categoria " . $categoria->getPadre() . " ya se encuentra registrada.";
        }
    }

    public function modificarCategoriaPP(CategoriaProductoProduccion $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();

        $query = $em->createQuery('SELECT c from SisGGFinalBundle:CategoriaProductoProduccion c WHERE  (c.nombre  = :n and c.id <> :id )'); //and c.activo = 1 ver el unique de la tabla
        $query->setParameters(array(
            ':n' => $categoria->getNombre(),
            ':id' => $categoria->getId()
        ));
        if ($query->getResult() == null) {
            if ($categoria->getPadre() != null) {
                if ($categoria->getPadre()->getProductoProduccion()->isEmpty()) {
                    if ($categoria->getPadre() != $categoria) {
                        $em->flush();
                    } else {
                        return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria padre " . $categoria->getPadre()->getNombre() . " es la misma que la que desea modificar.";
                    }
                } else {
                    return "La categoria " . $categoria->getNombre() . " no puede ser registrada, ya que la categoria " . $categoria->getPadre()->getNombre() . " posee productos. Seleecione otra que no contega.";
                }
            } else {
                $em->flush();
            }
        } else {
            return "La categoria " . $categoria->getNombre() . " ya se encuentra registrada.";
        }
    }

    public function eliminarCategoriaPP(CategoriaProductoProduccion $categoria, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if (($categoria->getProductoProduccion()->isEmpty() && $categoria->getHijo()->isEmpty())) {
            $this->eliminar($categoria, $c);
            $em->flush();
            return null;
        } else {
            if ($categoria->getHijo()->isEmpty()) {
                if ($categoria->vacioConProducto()) {
                    $categoria->setActivo(0);
                    $em->flush();
                    return null;
                } else {
                    return "No se puede eliminar la categoria " . $categoria->getNombre() . " puesto que contiene categorias o productos registradas. Eliminelas primero.";
                }
            } else {
                return "No se puede eliminar la categoria " . $categoria->getNombre() . " puesto que contiene categorias o productos registradas. Eliminelas primero.";
            }
        }
    }

    //CategoriaPPFIN
    //ProvinciaINICIO

    public function agregarProvincia(Provincia $prov, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $this->guardar($prov, $c);
        $this->addProvincia($prov);
        $prov->setEmpresa($this);
        $em->flush();
    }

    public function modificarProvincia(Provincia $prov, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
    }

    public function eliminarProvincia(Provincia $prov, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($prov->getCiudad()->isEmpty()) {
            $this->eliminar($prov, $c);
            $this->removeProvincia($prov);
            $em->flush();
            return null;
        } else {
            return "No se puede eliminar la Provincia " . $prov->getNombre() . " puesto que contiene ciudades registradas. Eliminelas primero.";
        }
    }

    //ProvinciaFIN
    //CiudadINICIO

    public function agregarCiudad(Ciudad $ciudad, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $this->guardar($ciudad, $c);
        $ciudad->getProvincia()->addCiudad($ciudad);
        $ciudad->setProvincia($ciudad->getProvincia());
        $em->flush();
    }

    public function modificarCiudad(Ciudad $ciudad, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
    }

    public function eliminarCiudad(Ciudad $ciudad, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT DISTINCT d from SisGGFinalBundle:Direccion d JOIN d.ciudad c WHERE  c.id = :id');
        $query->setParameters(array(
            ':id' => $ciudad->getId(),
        ));
        $nomCiu = $query->getResult();
        if ($nomCiu != null) {
            return "La Ciudad " . $ciudad->getNombre() . " no puede ser eliminada puesto que está asignada a una dirección.";
        } else {
            $this->eliminar($ciudad, $c);
            $em->flush($ciudad);
        }
    }

    //CiudadFIN
    //NotaPedidoINCIO
     public function altaNotaPedido(NotaPedido $nota, $tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $nota->setFecha();
        $nota->setEstado($tipo);
        $total = 0.00;
        if (count($nota->getItem()) <= 0 && $tipo == 2) {
            return 'Para finalizar la nota de pedido debe agregar al menos un producto.';
        }
        if ($nota->getItem() != null) {
            foreach ($nota->getItem() as $value) {
                $value->setNotaPedido($nota);
                $total+= $value->getPrecioCosto() * $value->getCantidad();
            }
        }
        $nota->setTotal($total);
        $this->guardar($nota, $c);
        $em->flush();
        return null;
    }

    public function modificarNotaPedido(NotaPedido $nota, $tipo, $datos, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($nota->getEstado() == 1) {
            $total = 0.00;
            if (count($nota->getItem()) <= 0 && $tipo == 2) {
                return 'Para finalizar la nota de pedido debe agregar al menos un producto.';
            }
            $nota->setFecha();
            $nota->setEstado($tipo);
            if ($nota->getItem() != null) {
                foreach ($nota->getItem() as $value) {
                    $value->setNotaPedido($nota);
                    $total+= $value->getPrecioCosto() * $value->getCantidad();
                }
            }
            $nota->setTotal($total);
            foreach ($nota->getItem() as $item) {
                foreach ($datos as $key => $toDel) {
                    if ($toDel->getId() === $item->getId()) {
                        unset($datos[$key]);
                    }
                }
            }
            foreach ($datos as $item) {
                $item->setNotaPedido(null);
                $em->remove($item);
            }
            $em->persist($nota);
            $em->flush();
            return null;
        } elseif ($nota->getEstado() == 2 || $nota->getEstado() == 3) {
            $nota->setEstado($tipo);
            $em->flush();
            return null;
        }
        return 'Error al especificar el estado.';
    }

    //NotaPedidoFIN
    //CompraINICIO
     //CompraINICIO
    public function altaCompra(Compra $compra, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        //Estado: Pagado=1, Parcialmente=0
        //Tipo: Modificar tasas=1, Modificar precios=2, Modificar todos=3, Dejar asi=0
        $em = $c->getDoctrine()->getEntityManager();
        $compra->setFechaEmision();
        $compra->setEmpresa($this);
        $total = 0.00;
        $pagos = 0.00;
        $ivas = null;
        $libro = null;
        $usuario = $c->getUser();
        foreach ($this->ivas as $value) {
            $ivas[$value->getId()] = 0.00;
        }

        if (!($compra->isMenorHoy())) {
            return "La fecha de la factura debe ser inferior al día de hoy.";
        }
        if (count($compra->getItem()) > 0) {
            if (!($compra->isDescuentoValido()))
                return 'El porcentaje de descuento es incorrecto.';


            //Factura A

            if ($compra->getTipo() == 'A') {
                $sub = 0.00;
                foreach ($compra->getItem() as $value) {
                    if ($value->getDescuento()) {
                        $precio = $value->getPrecioCosto() * $value->getCantidad();
                        $desc = 0.00;
                        if ($value->isDescuentoValido()) {
                            $desc = (($precio * $value->getBonificacion()) / 100);
                        }
                        $sub = ($precio - $desc) + $sub;
                        $fila = ($precio * ($value->getIva()->getTasa() / 100 + 1)) - $desc;
                        $iva = ($precio * ($value->getIva()->getTasa() / 100 + 1)) - $precio;
                        $value->setPIVA($value->getIva()->getTasa());
                        $value->setGIVA($value->getIva()->getGravado());
                        $value->setTIVA($iva);
                        $value->setTotal($fila);
                        $total+=$value->getTotal();
                        $ivas[$value->getIva()->getId()] = $ivas[$value->getIva()->getId()] + $iva;
                    } else {
                        $pCosto = ($value->getPrecioCosto() / ($value->getIva()->getTasa() / 100 + 1) );
                        $precio = $pCosto * $value->getCantidad();
                        if ($value->isDescuentoValido()) {
                            $desc = (($precio * $value->getBonificacion()) / 100);
                        }
                        $sub = ($precio - $desc) + $sub;
                        $fila = ($precio * ($value->getIva()->getTasa() / 100 + 1)) - $desc;
                        $iva = ($precio * ($value->getIva()->getTasa() / 100 + 1)) - $precio;
                        $value->setPrecioCosto($pCosto);
                        $value->setPIVA($value->getIva()->getTasa());
                        $value->setGIVA($value->getIva()->getGravado());
                        $value->setTIVA($iva);
                        $value->setTotal($fila);
                        $total+=$value->getTotal();
                        $ivas[$value->getIva()->getId()] = $ivas[$value->getIva()->getId()] + $iva;
                    }
                }
                $total = ($sub - ($compra->getDescuento() * $sub / 100)) + array_sum($ivas);
                $compra->setSubTotal($sub);
                $total = $total + $compra->getOtrosImp();
                $compra->setTotal($total);
                //return ($total . '-' . ($sub - ($compra->getDescuento() * $sub / 100)) . '-' . array_sum($ivas));
                $libro = $this->registrarLibroIVACompra($compra, $ivas);
            }
            if ($compra->getTipo() == 'C') {
                foreach ($compra->getItem() as $value) {
                    if ($value->isDescuentoValido())
                        $fila = ($value->getPrecioCosto() * $value->getCantidad()) - ($value->getPrecioCosto() * $value->getCantidad() * ($value->getBonificacion() / 100));
                    else
                        return 'El porcentaje de descuento en el el producto ' . $value->getProducto()->getNombre() . ' es incorrecto.';
                    $value->setPIVA(null);
                    $value->setGIVA(null);
                    $value->setTIVA(null);
                    $value->setTotal($fila);
                    $total+=$value->getTotal();
                }
                $compra->setSubTotal($total);
                $total = $total - ($compra->getDescuento() * $total / 100);
                $total = $total + $compra->getOtrosImp();
                $compra->setTotal($total);
                $libro = $this->registrarLibroIVACompra($compra, $ivas);
            }



            if ($compra->getTipo() == 'B') {
                foreach ($compra->getItem() as $value) {
                    if ($value->isDescuentoValido())
                        $fila = ($value->getPrecioCosto() * $value->getCantidad()) - ($value->getPrecioCosto() * $value->getCantidad() * ($value->getBonificacion() / 100));
                    else
                        return 'El porcentaje de descuento en el el producto ' . $value->getProducto()->getNombre() . ' es incorrecto.';
                    $value->setPIVA(null);
                    $value->setGIVA(null);
                    $value->setTIVA(null);
                    $value->setTotal($fila);
                    $total+=$value->getTotal();
                }
                $compra->setSubTotal($total);
                $total = $total - ($compra->getDescuento() * $total / 100);
                $total = $total + $compra->getOtrosImp();
                $compra->setTotal($total);
                $libro = $this->registrarLibroIVACompra($compra, $ivas);
            }


            if ($compra->getTipo() == 'X') {
                foreach ($compra->getItem() as $value) {
                    if ($value->isDescuentoValido())
                        $fila = ($value->getPrecioCosto() * $value->getCantidad()) - ($value->getPrecioCosto() * $value->getCantidad() * ($value->getBonificacion() / 100));
                    else
                        return 'El porcentaje de descuento en el el producto ' . $value->getProducto()->getNombre() . ' es incorrecto.';
                    $value->setPIVA(null);
                    $value->setGIVA(null);
                    $value->setTIVA(null);
                    $value->setTotal($fila);
                    $total+=$value->getTotal();
                }
                // $libro = $this->registrarLibroIVACompra($compra, $ivas);
                $compra->setSubTotal($total);
                $total = $total - ($compra->getDescuento() * $total / 100);
                $total = $total + $compra->getOtrosImp();
                $compra->setTotal($total);
            }


            if ($compra->getPagos() != null) {
                foreach ($compra->getPagos() as $value) {
                    $value->setFechaEmision();
                    $value->setCompra($compra);
//                    $atr = $em->getRepository('SisGGFinalBundle:AtrTipoPago')->find($value->getAtr());
//                    $value->setAtrs($atr);
                    $pagos+=$value->getImporte();
                    if (!($value->isMenorHoy())) {
                        return "La fecha del pago debe ser inferior al día de hoy.";
                    }
                    $value->asociarValores();
                    $salida = $value->crearSalida($usuario);
                    if ($salida) {
                        return "El pago con importe " . $value->getImporte() . " y fecha " . date_format($value->getFecha(), 'd/m/Y') . " no puede realizarse pusto que la caja esta cerrada.";
                    }
                }
            }
            $total = round($total, 3);
            if ($pagos <= $total) {
                $compra->setTotal(round($total, 3));
                if ($total == $pagos) {
                    $compra->setEstado(1);
                } else {
                    $compra->setEstado(0);
                }
            } else {
                return "La suma de los pago no puede ser superior al monto total de la factura.";
            }

            foreach ($compra->getItem() as $value) {
                $value->setCompra($compra);
                if (!($value->getProducto()->isMantenimiento())) {
                    $tasaItem = 0.00;
                    $cantidad = 0.00;
                    if ($value->getTasa() != null) {
                        $t = $em->getRepository('SisGGFinalBundle:Tasa')->find($value->getTasa()->getId());
                        $tasaItem = $t->getValor();
                    }
                    $tasaProducto = 0.00;
                    if ($value->getProducto()->getTasa() != null) {
                        $t = $em->getRepository('SisGGFinalBundle:Tasa')->find($value->getProducto()->getTasa()->getId());
                        $tasaProducto = $t->getValor();
                        $cantidad = (($tasaItem / $tasaProducto) * $value->getCantidad()) + $value->getProducto()->getCantidad();
                    }

                    $value->getProducto()->setCantidad($cantidad);
                }
                if ($compra->existeTipo(1)) {
                    if ($value->getTasa() != null) {
                        $tasaItem = 0.00;
                        $cantidad = 0.00;
                        if ($value->getTasa() != null) {
                            $t = $em->getRepository('SisGGFinalBundle:Tasa')->find($value->getTasa()->getId());
                            $tasaItem = $t->getValor();
                        }
                        $tasaProducto = 0.00;
                        if ($value->getProducto()->getTasa() != null) {
                            if (!($value->getProducto()->isMantenimiento())) {
                                $t = $em->getRepository('SisGGFinalBundle:Tasa')->find($value->getProducto()->getTasa()->getId());
                                $tasaProducto = $t->getValor();
                                $cantidad = (($tasaProducto / $tasaItem) * $value->getCantidad()) * $value->getProducto()->getCantidad();
                            }
                        }
                        $value->getProducto()->setCantidad($cantidad);
                        $value->getProducto()->setTasa($value->getTasa());
                    }
                }
                if ($compra->existeTipo(2)) {
                    $value->getProducto()->setPrecioCosto($value->getPrecioCosto());
                }
                if ($compra->existeTipo(3)) {
                    $value->getProducto()->setIVA($value->getIva());
                }
                if ($compra->existeTipo(4)) {
                    $value->getProducto()->setProveedor($compra->getProveedor());
                }
                if ($value->getProducto()->isMercaderia()) {
                    $this->modificarMercaderia($value->getProducto(), $c);
                }
                if ($value->getProducto()->isMateriaPrima()) {
                    $this->modificarMateriaPrima($value->getProducto(), $c);
                }
                if ($value->getProducto()->isMantenimiento()) {
                    $this->modificarMantenimiento($value->getProducto(), $c);
                }
            }


            $this->guardar($compra, $c);
            if ($libro != null)
                $this->guardar($libro, $c);
            $em->flush();
            return null;
        } else {
            return 'Para registrar la compra debe ingresar al menos un producto.';
        }
    }

    private function registrarLibroIVACompra(Compra $compra, $ivas) {
        $libro = new LibroIvaCompra();
        if ($compra->getTipo() == 'A') {
            foreach ($this->ivas as $value) {
                $item = new ItemLIC();
                $item->setGravado($value->getGravado());
                $item->setTasa($value->getTasa());
                $item->setLic($libro);
                $item->setTotal($ivas[$value->getId()]);
                $libro->addItem($item);
            }
        }
        $libro->setAcrecent($compra->getOtrosImp());
        $libro->setCompra($compra);
        $libro->setCuit($compra->getProveedor()->getCuit());
        $libro->setEmpresa($this);
        $libro->setFecha($compra->getFechaFactura());
        $libro->setNoGravado(0);
        $libro->setProveedor($compra->getProveedor());
        $libro->setRazonSocial($compra->getProveedor()->getRazonSocial());
        $libro->setCondIva($compra->getProveedor()->getCondicionIva()->getAbreviatura());
        $libro->setTipo($compra->getTipo());
        $libro->setNeto($compra->getTotal() - array_sum($ivas) - $compra->getOtrosImp());
        $libro->setTotal($compra->getTotal());
        return $libro;
    }

    public function registrarPagoCompra(Compra $compra, Compra $vieja, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $usuario = $c->getUser();
        if ($compra->getEstado()) {
            return 'Esta compra ya se encuentra totalmente paga.';
        } else {
            $pagos = 0.00;

            if ($compra->getPagos() != null) {
                foreach ($compra->getPagos() as $value) {
                    $pagos+=$value->getImporte();
                    if ($vieja->existePago($value)) {
                        $value->asociarValores();
                        if (!($value->isMenorHoy())) {
                            return "La fecha del pago debe ser inferior al día de hoy.";
                        }
                        $salida = $value->crearSalida($usuario);
                        if ($salida) {
                            return "El pago con importe " . $value->getImporte() . " y fecha " . date_format($value->getFecha(), 'd/m/Y') . " no puede realizarse pusto que la caja esta cerrada.";
                        }
                    }
                }
            }
            $total = 0.00;
            if (round($pagos, 3) <= round($compra->getTotal(), 3)) {
                $total = $compra->getTotal();
                if (round($pagos, 3) == round($compra->getTotal(), 3)) {
                    $compra->setEstado(1);
                } else {
                    $compra->setEstado(0);
                }
            } else {
                return "La suma de los pago no puede ser superior al monto total de la factura." . $pagos . $compra->getTotal();
            }
            $em->flush();
            return null;
        }
    }

    public function registrarPagoCompraProv(Compra $compra, $compras, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $usuario = $c->getUser();
        $pagos = 0.00;
        $total = 0.00;
        if ($compras != null) {
            foreach ($compras as $value) {
                $total+=$value->getSaldo();
            }
        }

        if ($compra->getPagos() != null) {
            foreach ($compra->getPagos() as $value) {
                $pagos+=$value->getImporte();
                if (!($value->isMenorHoy())) {
                    return "La fecha del pago debe ser inferior al día de hoy.";
                }
            }
        }

        if (round($pagos, 3) > round($total, 3)) {
            return "La suma de los pagos no puede ser superior al monto total de la factura." . ($pagos - $total);
        }
        foreach ($compras as $value) {
            if ($value->getEstado())
                break;
            if ($compra->getPagos() != null) {
                foreach ($compra->getPagos() as $pago) {
                    if (!($value->getEstado())) {
                        if ($pago->tieneMonto()) {
                            $resto = $value->pagar($pago, $usuario);
                            if (is_string($resto)) {
                                return $resto;
                            }
                            if ($resto <= 0) {
                                $compra->removePago($pago);
                            } else {
                                $pago->setImporte($resto);
                            }
                        }
                    } else {
                        break;
                    }
                }
            }
        }

        $em->flush();
        return null;
    }

    public function agruparCompras($compras = null, $tipo = 1) {
        $retorno = null;
        //compras = $this->compras;
        if ($compras == null) {
            return null;
        }
        if ($tipo == 1) {
            foreach ($compras as $value) {
                //por dias
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        $dia = new \DateTime(date_format($value->getFechaFactura(), 'Y/m/d'));
                        if ($item[0]['fecha'] == $dia) {
                            $retorno[$j][] = array('fecha' => $dia, 'factura' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $dia, 'factura' => $value);
                } else {
                    $dia = new \DateTime(date_format($value->getFechaFactura(), 'Y/m/d'));
                    $retorno [0][] = array('fecha' => $dia, 'factura' => $value);
                }
            }
        } elseif ($tipo == 2) {
            foreach ($compras as $value) {
                //por semana
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        $semana = date_format($value->getFechaFactura(), 'y-W-M');
                        if ($item[0]['fecha'] == $semana) {
                            $retorno[$j][] = array('fecha' => $semana, 'factura' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $semana, 'factura' => $value);
                } else {
                    $semana = date_format($value->getFechaFactura(), 'y-W-M');
                    $retorno [0][] = array('fecha' => $semana, 'factura' => $value);
                }
            }
        } elseif ($tipo == 3) {
            foreach ($compras as $value) {
                //por meses
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        //$mes = date_format($value->getFechaFactura(), 'M-y');
                        $mes = new \DateTime(date_format($value->getFechaFactura(), 'Y/m/') . '1');

                        if ($item[0]['fecha'] == $mes) {
                            $retorno[$j][] = array('fecha' => $mes, 'factura' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $mes, 'factura' => $value);
                } else {
                    //$mes = date_format($value->getFechaFactura(), 'M-y');
                    $mes = new \DateTime(date_format($value->getFechaFactura(), 'Y/m/') . '1');
                    $retorno [0][] = array('fecha' => $mes, 'factura' => $value);
                }
            }
        }
        return $retorno;
    }


    //CompraFIN
    //TipoPagoINCIO

    function altaTipoPago(TipoPago $tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $tipo->setEmpresa($this);
        $this->guardar($tipo, $c);
        $em->flush();
        return null;
    }

    function modificarTipoPago(TipoPago $tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
        return null;
    }

    function eliminarTipoPago(TipoPago $tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $find = $em->getRepository('SisGGFinalBundle:Pago')->findOneBy(array('tipoPago' => $tipo->getId()));
        if ($find == null) {
            $this->eliminar($tipo, $c);
            $em->flush();
            return null;
        } else {
            return 'Este tipo de pago no puede eliminarse, ya que se encuentra asignado a uno o más pagos.';
        }
    }

    public function agruparPagos($pagos = null, $tipo = 1) {
        $retorno = null;
        //compras = $this->compras;
        if ($pagos == null) {
            return null;
        }
        if ($tipo == 1) {
            foreach ($pagos as $value) {
                //por dias
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        $dia = new \DateTime(date_format($value->getFecha(), 'Y/m/d'));
                        if ($item[0]['fecha'] == $dia) {
                            $retorno[$j][] = array('fecha' => $dia, 'pago' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $dia, 'pago' => $value);
                } else {
                    $dia = new \DateTime(date_format($value->getFecha(), 'Y/m/d'));
                    $retorno [0][] = array('fecha' => $dia, 'pago' => $value);
                }
            }
        } elseif ($tipo == 2) {
            foreach ($pagos as $value) {
                //por semana
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        $semana = date_format($value->getFecha(), 'y-W-M');
                        if ($item[0]['fecha'] == $semana) {
                            $retorno[$j][] = array('fecha' => $semana, 'pago' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $semana, 'pago' => $value);
                } else {
                    $semana = date_format($value->getFecha(), 'y-W-M');
                    $retorno [0][] = array('fecha' => $semana, 'pago' => $value);
                }
            }
        } elseif ($tipo == 3) {
            foreach ($pagos as $value) {
                //por meses
                if ($retorno != null) {
                    $j = 0;
                    $flat = true;
                    foreach ($retorno as $item) {
                        //$mes = date_format($value->getFecha(), 'M-y');
                        $mes = new \DateTime(date_format($value->getFecha(), 'Y/m/') . '1');
                        if ($item[0]['fecha'] == $mes) {
                            $retorno[$j][] = array('fecha' => $mes, 'pago' => $value);
                            $flat = false;
                        }
                        $j++;
                    }
                    if ($flat)
                        $retorno [][] = array('fecha' => $mes, 'pago' => $value);
                } else {
                    //$mes = date_format($value->getFecha(), 'M-y');
                    $mes = new \DateTime(date_format($value->getFecha(), 'Y/m/') . '1');
                    $retorno [0][] = array('fecha' => $mes, 'pago' => $value);
                }
            }
        }
        return $retorno;
    }

    //TipoPagoFIN
    //IVAINICIO
    function altaIVA(IVA $iva, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $iva->setEmpresa($this);
        $iva->setActivo(true);
        $this->guardar($iva, $c);
        $em->flush();
        return null;
    }

    function modificarIVA(IVA $iva, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
        return null;
    }

    function eliminarIVA(IVA $iva, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $iva->setActivo(0);
        $em->flush();
        return "El servicio " . $servicio->getNombre() . " ha sido correctamente elimindo.";
    }

    //IVAFIN
    //ServicioINCIO
    function altaServicio(Servicio $servicio, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $servicio->setEmpresa($this);
        $servicio->setActivo(1);
        $this->guardar($servicio, $c);
        $em->flush();
        return null;
    }

    function modificarServicio(Servicio $servicio, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
        return null;
    }

    function eliminarServicio(Servicio $servicio, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $servicio->setActivo(0);
        $em->flush();
        return "El servicio " . $servicio->getNombre() . " ha sido correctamente elimindo.";
    }

    //ServcioFIN
    //CargoINICIO
    function altaCargo(CargoSistema $cargo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $cargo->setEmpresa($this);
        $cargo->setActivo(true);
        $this->guardar($cargo, $c);
        $em->flush();
        return null;
    }

    function modificarCargo(CargoSistema $cargo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c, $lista = null) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($cargo->getTodos() == 0) {
            $em->flush();
            return 1;
        }
        $array = null;
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($hoy >= $cargo->getPrimerPago()) {
            return 'Error en el establecimiento de una de las fechas.' . $cargo->getPrimerPago();
        }
        if ($cargo->getTipo() == 0) {
            $dia = date_format($cargo->getPrimerPago(), 'j');
            if ($dia != $cargo->getPorFecha()) {
                return 'El dia del mes del primer pago debe coincidir con el dia del pago.';
            }
        } elseif ($cargo->getTipo() == 2) {
            $dia = date_format($cargo->getPrimerPago(), 'w');
            if ($dia != $cargo->getPorDiaSemana()) {
                return 'El dia de la semana del primer pago debe coincidir con el dia del pago.';
            }
        }


        foreach ($lista as $id) {
            $empleado = $this->existeEmpleadoEnCargo($cargo, $id);
            if ($empleado != null) {
                if ($empleado->getPrimerPago() == null) {
                    if ($empleado->liquidarListaLiquidacion() == 1) {
                        $empleado->setPrimerPago($cargo->getPrimerPago());
                        $saldo = 0.00;
                        $saldo = $empleado->getCuenta()->getSaldo();
                        if ($saldo >= ($cargo->getNegativo() * (-1))) {
                            $cargoNew = new CargoEmpleado();
                            $cargoNew->setCargoSistema($empleado->getCargo()->getCargoSistema());
                            $cargoNew->setNombre($cargo->getNombre());
                            $cargoNew->setDescripcion($cargo->getDescripcion());
                            $cargoNew->setMonto($cargo->getImporte());
                            $cargoNew->setNegativo($cargo->getNegativo());
                            $cargoNew->setPorDia($cargo->getPorDia());
                            $cargoNew->setPorDiaSemana($cargo->getPorDiaSemana());
                            $cargoNew->setPorFecha($cargo->getPorFecha());
                            $cargoNew->setTipo($cargo->getTipo());
                            $cargoNew->setEmpleado($empleado);
                            $empleado->setPrimerPago($cargo->getPrimerPago());
                            $empleado->addCargo($cargoNew);
                            $old = $empleado->getCargo();
                            $old = $old->setActivo(0);
                            $cargoNew->setActivo(1);
                            $empleado->controlarPago();
                            $this->guardar($cargoNew, $c);
                            $em->flush();
                            // return 'Correctom';
                            $array[] = array('empleado' => $empleado, 'txt' => 3);
                        } else {
                            // return 'El saldo debe ser mayor o igual al monto en descubierto.';
                            $array[] = array('empleado' => $empleado, 'txt' => 2);
                        }
                    } else {
                        //'Error en la generacion de la liquidación.' 1
                        $array[] = array('empleado' => $empleado, 'txt' => 1);
                    }
                } else {

                    if ($empleado->getCuenta()->getMovimientos() != null) {
                        if (count($empleado->getCuenta()->getMovimientos()) == 0) {
                            $saldo = 0.00;
                            $saldo = $empleado->getCuenta()->getSaldo();
                            if ($saldo >= ($cargo->getNegativo() * (-1))) {
                                $cargoNew = $empleado->getCargo();
                                $cargoNew->setCargoSistema($empleado->getCargo()->getCargoSistema());
                                $cargoNew->setNombre($cargo->getNombre());
                                $cargoNew->setDescripcion($cargo->getDescripcion());
                                $cargoNew->setMonto($cargo->getMonto());
                                $cargoNew->setNegativo($cargo->getNegativo());
                                $cargoNew->setPorDia($cargo->getPorDia());
                                $cargoNew->setPorDiaSemana($cargo->getPorDiaSemana());
                                $cargoNew->setPorFecha($cargo->getPorFecha());
                                $cargoNew->setTipo($cargo->getTipo());
                                $empleado->controlarPago();
                                $em->flush();
                                // return 'Correcto';
                                $array[] = array('empleado' => $empleado, 'txt' => 3);
                            }
                        } else {
                            $saldo = 0.00;
                            $saldo = $empleado->getCuenta()->getSaldo();
                            if ($saldo >= ($cargo->getNegativo() * (-1))) {
                                $cargoNew = new CargoEmpleado();
                                $cargoNew->setCargoSistema($empleado->getCargo()->getCargoSistema());
                                $cargoNew->setNombre($cargo->getNombre());
                                $cargoNew->setDescripcion($cargo->getDescripcion());
                                $cargoNew->setMonto($cargo->getMonto());
                                $cargoNew->setNegativo($cargo->getNegativo());
                                $cargoNew->setPorDia($cargo->getPorDia());
                                $cargoNew->setPorDiaSemana($cargo->getPorDiaSemana());
                                $cargoNew->setPorFecha($cargo->getPorFecha());
                                $cargoNew->setTipo($cargo->getTipo());
                                $cargoNew->setEmpleado($empleado);
                                $empleado->addCargo($cargoNew);
                                $old = $empleado->getCargo();
                                $old = $old->setActivo(0);
                                $cargoNew->setActivo(1);
                                $empleado->controlarPago();
                                $this->guardar($cargoNew, $c);
                                $em->flush();
                                // return 'Correcto';
                                $array[] = array('empleado' => $empleado, 'txt' => 3);
                            }
                        }
                    } else {
                        $saldo = 0.00;
                        $saldo = $empleado->getCuenta()->getSaldo();
                        if ($saldo >= ($cargo->getNegativo() * (-1))) {
                            $cargoNew = $empleado->getCargo();
                            $cargoNew->setCargoSistema($empleado->getCargo()->getCargoSistema());
                            $cargoNew->setNombre($cargo->getNombre());
                            $cargoNew->setDescripcion($cargo->getDescripcion());
                            $cargoNew->setMonto($cargo->getMonto());
                            $cargoNew->setNegativo($cargo->getNegativo());
                            $cargoNew->setPorDia($cargo->getPorDia());
                            $cargoNew->setPorDiaSemana($cargo->getPorDiaSemana());
                            $cargoNew->setPorFecha($cargo->getPorFecha());
                            $cargoNew->setTipo($cargo->getTipo());
                            $empleado->controlarPago();
                            $em->flush();
                            // return 'Correcto';
                            $array[] = array('empleado' => $empleado, 'txt' => 3);
                        }
                    }
                }
            }
        }

        return $array;
    }

    public function existeEmpleadoEnCargo(CargoSistema $cargo, $id) {
        if ($cargo->getCargos() == null) {
            return null;
        }
        foreach ($cargo->getCargos() as $value) {
            if ($value->getActivo()) {
                if ($value->getEmpleado()->getId() == $id) {
                    return $value->getEmpleado();
                }
            }
        }
        return null;
    }

    public function comprobarSaldoCargo(CargoSistema $cargo) {
        $array = null;
//        if ($cargo->getTodos()) {
        foreach ($cargo->getCargos() as $cemp) {
            if ($cemp->getActivo()) {
                $empleado = $cemp->getEmpleado();
                if ($empleado->getPrimerPago() == null) {
                    if ($empleado->liquidarListaLiquidacion() == 1) {
                        $empleado->setPrimerPago($cargo->getPrimerPago());
                        $saldo = 0.00;
                        $saldo = $empleado->getCuenta()->getSaldo();
                        if ($saldo >= ($cargo->getNegativo() * (-1))) {
                            // return 'Correcto';
                            $array[] = array('id' => $empleado->getId(), 'txt' => 3);
                        } else {
                            // return 'El saldo debe ser mayor o igual al monto en descubierto.';
                            $array[] = array('id' => $empleado->getId(), 'txt' => 2);
                        }
                    } else {
                        //'Error en la generacion de la liquidación.' 1
                        $array[] = array('id' => $empleado->getId(), 'txt' => 1);
                    }
                } else {

                    $saldo = 0.00;
                    $saldo = $empleado->getCuenta()->getSaldo();
                    if ($saldo >= ($cargo->getNegativo() * (-1))) {
                        // return 'Correcto';
                        $array[] = array('id' => $empleado->getId(), 'txt' => 3);
                    } else {
                        // return 'El saldo debe ser mayor o igual al monto en descubierto.';
                        $array[] = array('id' => $empleado->getId(), 'txt' => 2);
                    }
                }
            }
        }
//        }else{
//              return 1;
//        }
        return $array;
    }

    //CargoFIN
    //PersonaEmpleadoINICIO
    function altaPersonaEmpleado(PersonaEmpleado $pe, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $pe->setEmpresa($this);
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($pe->getEdad() < $pe->getEmpresa()->getEdad()) {
            return 'El empleado debe ser mayor de ' . $pe->getEmpresa()->getEdad() . 'años de edad.';
        }
        if ($hoy < $pe->getFechaIngreso() || $hoy < $pe->getFechaNac() || $hoy > $pe->getPrimerPago()) {
            return 'Error en el establecimiento de una de las fechas.';
        }
        if ($pe->getCargoAct()->getTipo() == 0) {
            $dia = date_format($pe->getPrimerPago(), 'j');
            if ($dia != $pe->getCargoAct()->getPorFecha()) {
                return 'El dia del mes del primer pago debe coincidir con el dia del pago.' . $pe->getCargoAct()->getPorFecha() . '-' . $dia;
            }
        } elseif ($pe->getCargoAct()->getTipo() == 2) {
            $dia = date_format($pe->getPrimerPago(), 'w');
            if ($dia != $pe->getCargoAct()->getPorDiaSemana()) {
                return 'El dia de la semana del primer pago debe coincidir con el dia del pago.';
            }
        }
        
        $pe->setActivo(1);
        $this->guardar($pe, $c);
        $em->flush();

        $cargo = new CargoEmpleado();
        $cargo = $pe->getCargoAct();
        $cargo->setActivo(1);
        $cargo->setEmpleado($pe);
        $pe->addCargo($cargo);
        $pe->crearCuenta();
        if ($pe->getTelefonos() != null) {
            foreach ($pe->getTelefonos() as $value) {
                $value->setEmpleado($pe);
            }
        }

        $this->guardar($cargo, $c);
        $em->flush();
        return null;
    }

    function modificarPersonaEmpleado(PersonaEmpleado $pe, $datos = null, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($pe->getEdad() < $pe->getEmpresa()->getEdad()) {
            return 'El empleado debe ser mayor de ' . $pe->getEmpresa()->getEdad() . 'años de edad.';
        }
        if ($pe->getPrimerPago() != null) {

            if ($hoy < $pe->getFechaIngreso() || $hoy < $pe->getFechaNac() || $hoy > $pe->getPrimerPago()) {
                return 'Error en el establecimiento de una de las fechas.';
            }
            if ($pe->getCargoAct()->getTipo() == 0) {
                $dia = date_format($pe->getPrimerPago(), 'j');
                if ($dia != $pe->getCargoAct()->getPorFecha()) {
                    return 'El dia del mes del primer pago debe coincidir con el dia del pago.' . $pe->getCargoAct()->getPorFecha() . '-' . $dia;
                }
            } elseif ($pe->getCargoAct()->getTipo() == 2) {
                $dia = date_format($pe->getPrimerPago(), 'w');
                if ($dia != $pe->getCargoAct()->getPorDiaSemana()) {
                    return 'El dia de la semana del primer pago debe coincidir con el dia del pago.';
                }
            }
            $cargo = new CargoEmpleado();
            $cargo = $pe->getCargo();
            $cargo->setCargoSistema($pe->getCargoAct()->getCargoSistema());
            $cargo->setNombre($pe->getCargoAct()->getNombre());
            $cargo->setDescripcion($pe->getCargoAct()->getDescripcion());
            $cargo->setMonto($pe->getCargoAct()->getMonto());
            $cargo->setNegativo($pe->getCargoAct()->getNegativo());
            $cargo->setPorDia($pe->getCargoAct()->getPorDia());
            $cargo->setPorDiaSemana($pe->getCargoAct()->getPorDiaSemana());
            $cargo->setPorFecha($pe->getCargoAct()->getPorFecha());
            $cargo->setTipo($pe->getCargoAct()->getTipo());
            //$cargo = $pe->getCargoAct();
            $pe->controlarPago();
            //telefonos
            if ($pe->getTelefonos() != null) {
                foreach ($pe->getTelefonos() as $value) {
                    $value->setEmpleado($pe);
                }
                foreach ($pe->getTelefonos() as $item) {
                    foreach ($datos as $key => $toDel) {
                        if ($toDel->getId() === $item->getId()) {
                            unset($datos[$key]);
                        }
                    }
                }
                foreach ($datos as $item) {
                    $item->setEmpleado(null);
                    $em->remove($item);
                }
            }
        } else {
            if ($hoy < $pe->getFechaNac()) {
                return 'Error en el establecimiento de una de las fechas.';
            }
            if ($pe->getTelefonos() != null) {
                foreach ($pe->getTelefonos() as $value) {
                    $value->setEmpleado($pe);
                }
                foreach ($pe->getTelefonos() as $item) {
                    foreach ($datos as $key => $toDel) {
                        if ($toDel->getId() === $item->getId()) {
                            unset($datos[$key]);
                        }
                    }
                }
                foreach ($datos as $item) {
                    $item->setEmpleado(null);
                    $em->remove($item);
                }
            }
        }
        $em->flush();
        return null;
    }

    function modificarCargoEmpleado(PersonaEmpleado $empleado, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $hoy = new \DateTime('now');
        $hoy = new \DateTime(date_format($hoy, 'Y/m/d'));
        if ($empleado->getPrimerPago() == null) {
            if ($empleado->liquidarListaLiquidacion() == 1) {
                return null;
            } else {
                return 'Error en la generacion de la liquidación.';
            }
        }
        if ($hoy >= $empleado->getPrimerPago()) {
            return 'Error en el establecimiento de una de las fechas.';
        }
        if ($empleado->getCargoAct()->getTipo() == 0) {
            $dia = date_format($empleado->getPrimerPago(), 'j');
            if ($dia != $empleado->getCargoAct()->getPorFecha()) {
                return 'El dia del mes del primer pago debe coincidir con el dia del pago.' . $pe->getCargoAct()->getPorFecha() . '-' . $dia;
            }
        } elseif ($empleado->getCargoAct()->getTipo() == 2) {
            $dia = date_format($empleado->getPrimerPago(), 'w');
            if ($dia != $empleado->getCargoAct()->getPorDiaSemana()) {
                return 'El dia de la semana del primer pago debe coincidir con el dia del pago.';
            }
        }
        $saldo = 0.00;
        $saldo = $empleado->getCuenta()->getSaldo();
        if ($saldo >= ($empleado->getCargoAct()->getNegativo() * (-1))) {
            $cargo = new CargoEmpleado();
            $cargo = $empleado->getCargoAct();
            $cargo->setEmpleado($empleado);
            $empleado->addCargo($cargo);
            $old = $empleado->getCargo();
            $old = $old->setActivo(0);
            $cargo->setActivo(1);
            $this->guardar($cargo, $c);
            $empleado->controlarPago();
            $em->flush();
            return null;
        } else {
            return 'El saldo debe ser mayor o igual al monto en descubierto.';
        }
    }

    //contado
    function altaContadoEmpleado(ContadoEmpleado $con, PersonaEmpleado $empleado, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $usuario = $c->getUser();
        $r = $empleado->disponibilidadContado($con);
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($con->getFecha() > $fecha) {
            return 'La fecha de pago no puede ser superior al dia de hoy.';
        }

        if ($r < 0) {
            return 'El monto a pagar supera el haber y al monto en descubierto.';
        }
        $pago = $con->pagar($usuario);
        if ($pago) {
            return 'El pago no puede realizarse ya que la caja no se encuentra abierta.';
        }
        $con->setFechaEmision();

        $saldo = 0.00;
        $saldo = $empleado->getCuenta()->getSaldo() - $con->getMonto();
        $empleado->getCuenta()->setSaldo($saldo);
        $empleado->getCuenta()->addMovimiento($con);
        $em->flush();
        return $con->getId();
    }

    //adicional
    function altaAdicionalEmpleado(AdicionalEmpleado $con, PersonaEmpleado $empleado, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($con->getFecha() > $fecha) {
            return 'La fecha de pago no puede ser superior al dia de hoy.';
        }
        $con->setFechaEmision();

        $saldo = 0.00;
        $saldo = $empleado->getCuenta()->getSaldo() + $con->getMonto();
        $empleado->getCuenta()->setSaldo($saldo);
        $empleado->getCuenta()->addMovimiento($con);
        $em->flush();
        return null;
    }

    //cancelar
    function altaCancelarEmpleado(CancelarEmpleado $con, PersonaEmpleado $empleado, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $r = $empleado->disponibilidadContado($con);
        if ($r < 0) {
            return 'El monto a pagar supera el haber y al monto en descubierto.';
        }
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($con->getFecha() > $fecha) {
            return 'La fecha de pago no puede ser superior al dia de hoy.';
        }
        $con->setFechaEmision();

        $saldo = 0.00;
        $saldo = $empleado->getCuenta()->getSaldo() - $con->getMonto();
        $empleado->getCuenta()->setSaldo($saldo);
        $empleado->getCuenta()->addMovimiento($con);
        $em->flush();
        return null;
    }

    //cancelar
    function altaEspeciesEmpleado(EspeciesEmpleado $con, PersonaEmpleado $empleado, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $con->sumarItems($empleado);
        $r = $empleado->disponibilidadContado($con);
        if ($r < 0) {
            return 'El monto a pagar supera el haber y al monto en descubierto.';
        }
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($con->getFecha() > $fecha) {
            return 'La fecha de pago no puede ser superior al dia de hoy.';
        }

        $con->setFechaEmision();

        $saldo = 0.00;
        $saldo = $empleado->getCuenta()->getSaldo() - $con->getMonto();
        $empleado->getCuenta()->setSaldo($saldo);
        $empleado->getCuenta()->addMovimiento($con);
        $em->flush();
        return $con->getId();
    }

    //PersonaEmpleadoFIN
    //FacturaServicioINCIO
    function altaFacturaServicio(FacturaServicio $servicio, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
//        $em = $c->getDoctrine()->getEntityManager();
//        $servicio->setEmpresa($this);
//        $servicio->setFechaEmision();
//        if ($servicio->getFechaPago() < $servicio->getFechaFactura())
//            return 'La fecha de pago debe ser superior a la fecha de la factura.';
//        if ($servicio->getTotal() <= 0)
//            return 'El valor de factura debe ser mayor a cero.';
//
//        $pago = new Pago();
//        $pago->setFacturaServicio($servicio);
//        $pago->setFecha($servicio->getFechaPago());
//        $pago->setMonto($servicio->getTotal());
//        $pago->setTipoPago($servicio->getTipoPago());
//        $pago->setObservaciones($servicio->getObs());
//        $this->guardar($servicio, $c);
//        $this->guardar($pago, $c);
//        $em->flush();
//        return null;

        $em = $c->getDoctrine()->getEntityManager();
        $servicio->setFechaEmision();
        $servicio->setEmpresa($this);
        $total = 0.00;
        $pagos = 0.00;
        $usuario = $c->getUser();
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));

        if ($servicio->getFechaFactura() > $fecha)
            return 'La fecha de la fecha de la factura no puede ser superior al día de hoy.';
        if ($servicio->getTotal() <= 0)
            return 'El valor de factura debe ser mayor a cero.';

        if ($servicio->getPagos() != null) {
            foreach ($servicio->getPagos() as $value) {
                $value->setFechaEmision();
                $value->setFacturaServicio($servicio);
                $pagos+=$value->getImporte();
                $value->asociarValores();
                $salida = $value->crearSalida($usuario);
                if ($salida) {
                    return "El pago con importe " . $value->getImporte() . " y fecha " . date_format($value->getFecha(), 'd/m/Y') . " no puede realizarse pusto que la caja esta cerrada.";
                }
                if (!($value->isMenorHoy())) {
                    return "La fecha del pago debe ser inferior al día de hoy.";
                }
            }
        } else {
            return "Debe ingresar al menos un pago.";
        }
        $pagos = round($pagos, 3);
        $total = round($servicio->getTotal(), 3);
        if ($pagos == $total) {
            $servicio->setTotal($total);
        } else {
            return "La suma de los pago no puede ser superior al monto total de la factura.";
        }

        $this->guardar($servicio, $c);
        $em->flush();
        return null;
    }

    //FacturaServicioFIN
    //MENSAJE
    public function addMensaje($txt, $tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $msj = $this->existeTipoMsj($tipo, $c);
        if ($msj != null) {
            $msj->setNuevo(1);
            $msj->setLeido(false);
            $c->getDoctrine()->getEntityManager()->flush($msj);
        } else {
            $mensaje = new Mensaje();
            $mensaje->setMensaje($txt);
            $mensaje->setLeido(false);
            $mensaje->setTipo($tipo);
            $mensaje->setNuevo(1);
            $this->guardar($mensaje, $c);
            $c->getDoctrine()->getEntityManager()->flush($mensaje);
        }
    }

    public function existeTipoMsj($tipo, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $existe = $c->getDoctrine()->getEntityManager()->getRepository('SisGGFinalBundle:Mensaje')->findOneBy(array('tipo' => $tipo));
        return $existe;
    }

    //MENSAJEFIN
    //EtiquetaINICIO
    function altaEtiqueta(EtiquetaAgenda $etiqueta, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $this->guardar($etiqueta, $c);
        $em->flush();
        return null;
    }

    function modificarEtiqueta(EtiquetaAgenda $etiqueta, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->flush();
        return null;
    }

    function eliminarEtiqueta(EtiquetaAgenda $etiqueta, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $array = $em->getRepository('SisGGFinalBundle:Agenda')->findAll();
        foreach ($array as $value) {
            if ($value->getEtiqueta() == $etiqueta) {
                return 'La etiqueta no puede ser eliminada ya que se encuentra asignada a un evento.';
            }
        }
        $this->eliminar($etiqueta, $c);
        $em->flush();
        return null;
    }

    //EtiquetaFIN
    //AgendaINICIO
    function altaAgenda(Agenda $agenda, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $array = $em->getRepository('SisGGFinalBundle:Agenda')->findAll();
        if ($agenda->getInicioFec() > $agenda->getFinFec()) {
            return 'Error en el intervalo de fechas.';
        }
        if ($agenda->getInicioHora() > $agenda->getFinHora()) {
            return 'Error en el intervalo de horas.';
        }
//        if ($array != null) {
//            foreach ($array as $value) {
//                
//                if (((($agenda->getInicioFec() >= $value->getInicioFec() && $agenda->getInicioFec() <= $value->getFinFec()) || ($agenda->getFinFec() >= $value->getInicioFec() && $agenda->getFinFec() <= $value->getFinFec())) )) {
//                    //return 'El evento superpone a otro. Cambie las fechas.';
//                    if ((((($agenda->getInicioHora() >= $value->getInicioHora()) && ($agenda->getInicioHora() <= $value->getFinHora()) )|| ($agenda->getFinHora() >= $value->getInicioHora() && $agenda->getFinHora() <= $value->getFinHora())))) {
//                        return 'El evento superpone a otro. Cambie las horas.'. 'p1:'.date_format( $value->getInicioFec() , 'd/m/Y H:i'). 'p2:'.date_format( $value->getFinFec() , 'd/m/Y H:i'). 'f1:'.date_format( $agenda->getInicioFec() , 'd/m/Y H:i'). 'f2:'.date_format( $agenda->getFinFec() , 'd/m/Y H:i'). 'ph1:'.date_format( $value->getInicioHora() , 'd/m/Y H:i'). 'ph2:'.date_format( $value->getFinHora() , 'd/m/Y H:i'). 'H1:'.date_format( $agenda->getInicioHora() , 'd/m/Y H:i'). 'H2:'.date_format( $agenda->getFinHora() , 'd/m/Y H:i')   ;
//                    }
//                }
//            }
//        }
        $this->guardar($agenda, $c);
        $em->flush();
        return null;
    }

    function modificarAgenda(Agenda $agenda, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        if ($agenda->getInicioFec() > $agenda->getFinFec()) {
            return 'Error en el intervalo de fechas.';
        }
        if ($agenda->getInicioHora() > $agenda->getFinHora()) {
            return 'Error en el intervalo de horas.';
        }

        $em->flush();
        return null;
    }

    function eliminarAgenda(Agenda $agenda, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $this->eliminar($agenda, $c);
        $em->flush();
        return null;
    }

    //AgendaFIN
    //ABMFIN


    private function guardar($o, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->persist($o);
    }

    private function eliminar($o, \Symfony\Bundle\FrameworkBundle\Controller\Controller $c) {
        $em = $c->getDoctrine()->getEntityManager();
        $em->remove($o);
    }

    //ABMFIN
    //BUSCARINICIO
    //
    //BUSCARNOTAPEDIDO
    /**
     * Busca notas de pedido desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int   $tiempo      La diferencia del tiempo menor o igual a la fecha actual.
     * @param char    $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarNota($tiempo, $tipo) {
        $array = null;
        $hoy = new \DateTime('now');
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($tipo == 3) {
            $fecha->modify('-' . $tiempo . ' month');
        } elseif ($tipo == 2) {
            $fecha->modify('-' . $tiempo . ' week');
        } elseif ($tipo == 1) {
            $fecha->modify('-' . $tiempo . ' day');
        } elseif ($tipo == 4) {
            $fecha->modify('-' . $tiempo . ' year');
        }

        foreach ($this->getNotas() as $value) {
            $otra = $value->getFecha();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if ($otra >= $fecha && $otra <= $hoy)
                $array[] = $value;
        }
        return $array;
    }

    /**
     * Busca notas de pedido, desde una fecha determinada, hasta otra.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param DateTime  $fecha2     Rango mayor de la fecha. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha. 
     */
    public function buscarNotaEntreFechas(\DateTime $fecha1, \DateTime $fecha2) {
        $array = null;
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($this->getNotas() as $value) {
            $otra = $value->getFecha();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if (($otra >= $fecha1) && ($otra <= $fecha2))
                $array[] = $value;
        }
        return $array;
    }

    /**
     * Busca notas de pedido, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param int  $tipo     0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarNotaFecha(\DateTime $fecha1, $tipo) {
        $array = null;

        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($this->getNotas() as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1))
                    $array[] = $value;
            }
            return $array;
        }elseif ($tipo == 1) {
            foreach ($this->getNotas() as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra <= $fecha1))
                    $array[] = $value;
            }
            return $array;
        }elseif ($tipo == 2) {
            $hoy = new \DateTime('now');
            foreach ($this->getNotas() as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra >= $fecha1) && ($fecha1 <= $hoy))
                    $array[] = $value;
            }
            return $array;
        }
    }

    /**
     * Busca notas de pedido, de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarNotaMesAño($mes, $año) {
        $array = null;

        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($this->getNotas() as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes))
                    $array[] = $value;
            }
            return $array;
        }elseif ($mes == 0 && $año > 0) {
            foreach ($this->getNotas() as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año))
                    $array[] = $value;
            }
            return $array;
        }
        return $array;
    }

    public function isFechaValida($fecha) {
        if (is_a($fecha, 'DateTime')) {
            return true;
        }
        return false;
    }

    function isBisiesto(\DateTime $date) {
        $date = date_format($date, 'Y/m/d');
        $partes = explode("/", $date);
        if (!checkdate(02, 29, $partes[0])) {
            return false;
        } else {
            return true;
        }
    }

    public function cambiarFormatoFecha(\DateTime $fecha) {
        $date = date_format($fecha, 'd/m/Y');
        $partes = explode("/", $date);
        if (!(checkdate($partes[1], $partes[0], $partes[2]))) {
            
        }
    }

    //BUSCARLibroIvaCompra
    /**
     * Busca registros de libro iva compras, de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarLICMesAño($mes, $año) {
        $array = null;

        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($this->ivaCompras as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes))
                    $array[] = $value;
            }
            return $array;
        }elseif ($mes == 0 && $año > 0) {
            foreach ($this->ivaCompras as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año))
                    $array[] = $value;
            }
            return $array;
        }
        return $array;
    }

    //BUSCARCompras
    /**
     * Busca factura de compras desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int   $tiempo      La diferencia del tiempo menor o igual a la fecha actual.
     * @param char    $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarCompra($tiempo, $tipo) {
        $array = null;
        $total = 0.00;
        $hoy = new \DateTime('now');
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($tipo == 3) {
            $fecha->modify('-' . $tiempo . ' month');
        } elseif ($tipo == 2) {
            $fecha->modify('-' . $tiempo . ' week');
        } elseif ($tipo == 1) {
            $fecha->modify('-' . $tiempo . ' day');
        } elseif ($tipo == 4) {
            $fecha->modify('-' . $tiempo . ' year');
        }

        foreach ($this->compras as $value) {
            $otra = $value->getFechaFactura();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if ($otra >= $fecha && $otra <= $hoy) {
                $array[] = $value;
                $total+=$value->getTotal();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca factura de compras, desde una fecha determinada, hasta otra.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param DateTime  $fecha2     Rango mayor de la fecha. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha. 
     */
    public function buscarCompraEntreFechas(\DateTime $fecha1, \DateTime $fecha2) {
        $array = null;
        $total = 0.00;
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($this->compras as $value) {
            $otra = $value->getFechaFactura();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if (($otra >= $fecha1) && ($otra <= $fecha2)) {
                $array[] = $value;
                $total+=$value->getTotal();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca factura de compras, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param int  $tipo     0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarCompraFecha(\DateTime $fecha1, $tipo) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($this->compras as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 1) {
            foreach ($this->compras as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra <= $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 2) {
            $hoy = new \DateTime('now');
            foreach ($this->compras as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra >= $fecha1) && ($fecha1 <= $hoy)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    /**
     * Busca factura de compras, de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarCompraMesAño($mes, $año) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($this->compras as $value) {
                $otra = $value->getFechaFactura();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($mes == 0 && $año > 0) {
            foreach ($this->compras as $value) {
                $otra = $value->getFechaFactura();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    //BUSCARFacturaServicio
    /**
     * Busca Factura Servicio desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int   $tiempo      La diferencia del tiempo menor o igual a la fecha actual.
     * @param char    $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarFacturaServicio($tiempo, $tipo) {
        $array = null;
        $total = 0.00;
        $hoy = new \DateTime('now');
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($tipo == 3) {
            $fecha->modify('-' . $tiempo . ' month');
        } elseif ($tipo == 2) {
            $fecha->modify('-' . $tiempo . ' week');
        } elseif ($tipo == 1) {
            $fecha->modify('-' . $tiempo . ' day');
        } elseif ($tipo == 4) {
            $fecha->modify('-' . $tiempo . ' year');
        }

        foreach ($this->facturasServicio as $value) {
            $otra = $value->getFechaFactura();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if ($otra >= $fecha && $otra <= $hoy) {
                $array[] = $value;
                $total+=$value->getTotal();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca Factura Servicio, desde una fecha determinada, hasta otra.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param DateTime  $fecha2     Rango mayor de la fecha. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha. 
     */
    public function buscarFacturaServicioEntreFechas(\DateTime $fecha1, \DateTime $fecha2) {
        $array = null;
        $total = 0.00;
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($this->facturasServicio as $value) {
            $otra = $value->getFechaFactura();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if (($otra >= $fecha1) && ($otra <= $fecha2)) {
                $array[] = $value;
                $total+=$value->getTotal();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca factura de compras, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param int  $tipo     0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarFacturaServicioFecha(\DateTime $fecha1, $tipo) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($this->facturasServicio as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 1) {
            foreach ($this->facturasServicio as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra <= $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 2) {
            $hoy = new \DateTime('now');
            foreach ($this->facturasServicio as $value) {
                $otra = $value->getFechaFactura();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra >= $fecha1) && ($fecha1 <= $hoy)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    /**
     * Busca Factura Servicio , de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarFacturaServicioMesAño($mes, $año) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($this->facturasServicio as $value) {
                $otra = $value->getFechaFactura();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($mes == 0 && $año > 0) {
            foreach ($this->facturasServicio as $value) {
                $otra = $value->getFechaFactura();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    //BUSCARPagos
    /**
     * Busca Pagos desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int   $tiempo      La diferencia del tiempo menor o igual a la fecha actual.
     * @param char    $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarPago($tiempo, $tipo, $pagos) {
        $array = null;
        $total = 0.00;
        $hoy = new \DateTime('now');
        $fecha = new \DateTime('now');
        $fecha = new \DateTime(date_format($fecha, 'Y/m/d'));
        if ($tipo == 3) {
            $fecha->modify('-' . $tiempo . ' month');
        } elseif ($tipo == 2) {
            $fecha->modify('-' . $tiempo . ' week');
        } elseif ($tipo == 1) {
            $fecha->modify('-' . $tiempo . ' day');
        } elseif ($tipo == 4) {
            $fecha->modify('-' . $tiempo . ' year');
        }

        foreach ($pagos as $value) {
            $otra = $value->getFecha();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if ($otra >= $fecha && $otra <= $hoy) {
                $array[] = $value;
                $total+=$value->getImporte();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca Pago, desde una fecha determinada, hasta otra.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param DateTime  $fecha2     Rango mayor de la fecha. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha. 
     */
    public function buscarPagoEntreFechas(\DateTime $fecha1, \DateTime $fecha2, $pagos) {
        $array = null;
        $total = 0.00;
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($pagos as $value) {
            $otra = $value->getFecha();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if (($otra >= $fecha1) && ($otra <= $fecha2)) {
                $array[] = $value;
                $total+=$value->getImporte();
            }
        }
        $array[] = $total;
        return $array;
    }

    /**
     * Busca pagos, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param int  $tipo     0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarPagoFecha(\DateTime $fecha1, $tipo, $pagos) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($pagos as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getImporte();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 1) {
            foreach ($pagos as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra <= $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getImporte();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 2) {
            $hoy = new \DateTime('now');
            foreach ($pagos as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra >= $fecha1) && ($fecha1 <= $hoy)) {
                    $array[] = $value;
                    $total+=$value->getImporte();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    /**
     * Busca pagos , de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarPagoMesAño($mes, $año, $pagos) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($pagos as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes)) {
                    $array[] = $value;
                    $total+=$value->getImporte();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($mes == 0 && $año > 0) {
            foreach ($pagos as $value) {
                $otra = $value->getFecha();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año)) {
                    $array[] = $value;
                    $total+=$value->getImporte();
                }
            }
            $array[] = $total;
            return $array;
        }
        return $array;
    }

    //BUSCARFIN
    //SergioFIN

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
     * @return Empresa
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Empresa
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     * @return Empresa
     */
    public function setCuit($cuit) {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string 
     */
    public function getCuit() {
        return $this->cuit;
    }

    /**
     * Set direccion
     *
     * @param SisGG\FinalBundle\Entity\Direccion $direccion
     * @return Empresa
     */
    public function setDireccion(\SisGG\FinalBundle\Entity\Direccion $direccion = null) {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return SisGG\FinalBundle\Entity\Direccion 
     */
    public function getDireccion() {
        return $this->direccion;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add clientes
     *
     * @param SisGG\FinalBundle\Entity\Cliente $clientes
     * @return Empresa
     */
    public function addCliente(\SisGG\FinalBundle\Entity\Cliente $clientes) {
        $this->clientes[] = $clientes;

        return $this;
    }

    /**
     * Remove clientes
     *
     * @param SisGG\FinalBundle\Entity\Cliente $clientes
     */
    public function removeCliente(\SisGG\FinalBundle\Entity\Cliente $clientes) {
        $this->clientes->removeElement($clientes);
    }

    /**
     * Get clientes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getClientes() {
        return $this->clientes;
    }

    /**
     * Add proveedores
     *
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedores
     * @return Empresa
     */
    public function addProveedore(\SisGG\FinalBundle\Entity\Proveedor $proveedores) {
        $this->proveedores[] = $proveedores;

        return $this;
    }

    /**
     * Remove proveedores
     *
     * @param SisGG\FinalBundle\Entity\Proveedor $proveedores
     */
    public function removeProveedore(\SisGG\FinalBundle\Entity\Proveedor $proveedores) {
        $this->proveedores->removeElement($proveedores);
    }

    /**
     * Get proveedores
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProveedores() {
        return $this->proveedores;
    }

    /**
     * Add provincias
     *
     * @param SisGG\FinalBundle\Entity\Provincia $provincias
     * @return Empresa
     */
    public function addProvincia(\SisGG\FinalBundle\Entity\Provincia $provincias) {
        $this->provincias[] = $provincias;

        return $this;
    }

    /**
     * Remove provincias
     *
     * @param SisGG\FinalBundle\Entity\Provincia $provincias
     */
    public function removeProvincia(\SisGG\FinalBundle\Entity\Provincia $provincias) {
        $this->provincias->removeElement($provincias);
    }

    /**
     * Get provincias
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProvincias() {
        return $this->provincias;
    }

    /**
     * Add categoriasPV
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriasPV
     * @return Empresa
     */
    public function addCategoriasPV(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriasPV) {
        $this->categoriasPV[] = $categoriasPV;

        return $this;
    }

    /**
     * Remove categoriasPV
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriasPV
     */
    public function removeCategoriasPV(\SisGG\FinalBundle\Entity\CategoriaProductoVenta $categoriasPV) {
        $this->categoriasPV->removeElement($categoriasPV);
    }

    /**
     * Get categoriasPV
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoriasPV() {
        return $this->categoriasPV;
    }

    /**
     * Add categoriasPP
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoriasPP
     * @return Empresa
     */
    public function addCategoriasPP(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoriasPP) {
        $this->categoriasPP[] = $categoriasPP;

        return $this;
    }

    /**
     * Remove categoriasPP
     *
     * @param SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoriasPP
     */
    public function removeCategoriasPP(\SisGG\FinalBundle\Entity\CategoriaProductoProduccion $categoriasPP) {
        $this->categoriasPP->removeElement($categoriasPP);
    }

    /**
     * Get categoriasPP
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategoriasPP() {
        return $this->categoriasPP;
    }

    /**
     * Add productos
     *
     * @param SisGG\FinalBundle\Entity\Producto $productos
     * @return Empresa
     */
    public function addProducto(\SisGG\FinalBundle\Entity\Producto $productos) {
        $this->productos[] = $productos;

        return $this;
    }

    /**
     * Remove productos
     *
     * @param SisGG\FinalBundle\Entity\Producto $productos
     */
    public function removeProducto(\SisGG\FinalBundle\Entity\Producto $productos) {
        $this->productos->removeElement($productos);
    }

    /**
     * Get productos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getProductos() {
        return $this->productos;
    }

    /**
     * Add registrosProduccion
     *
     * @param SisGG\FinalBundle\Entity\RegistroProduccion $registrosProduccion
     * @return Empresa
     */
    public function addRegistrosProduccion(\SisGG\FinalBundle\Entity\RegistroProduccion $registrosProduccion) {
        $this->registrosProduccion[] = $registrosProduccion;

        return $this;
    }

    /**
     * Remove registrosProduccion
     *
     * @param SisGG\FinalBundle\Entity\RegistroProduccion $registrosProduccion
     */
    public function removeRegistrosProduccion(\SisGG\FinalBundle\Entity\RegistroProduccion $registrosProduccion) {
        $this->registrosProduccion->removeElement($registrosProduccion);
    }

    /**
     * Get registrosProduccion
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRegistrosProduccion() {
        return $this->registrosProduccion;
    }

    /**
     * Add ums
     *
     * @param SisGG\FinalBundle\Entity\UnidadMedida $ums
     * @return Empresa
     */
    public function addUm(\SisGG\FinalBundle\Entity\UnidadMedida $ums) {
        $this->ums[] = $ums;

        return $this;
    }

    /**
     * Remove ums
     *
     * @param SisGG\FinalBundle\Entity\UnidadMedida $ums
     */
    public function removeUm(\SisGG\FinalBundle\Entity\UnidadMedida $ums) {
        $this->ums->removeElement($ums);
    }

    /**
     * Get ums
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUms() {
        return $this->ums;
    }

    /**
     * Add notas
     *
     * @param SisGG\FinalBundle\Entity\NotaPedido $notas
     * @return Empresa
     */
    public function addNota(\SisGG\FinalBundle\Entity\NotaPedido $notas) {
        $this->notas[] = $notas;

        return $this;
    }

    /**
     * Remove notas
     *
     * @param SisGG\FinalBundle\Entity\NotaPedido $notas
     */
    public function removeNota(\SisGG\FinalBundle\Entity\NotaPedido $notas) {
        $this->notas->removeElement($notas);
    }

    /**
     * Get notas
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotas() {
        return $this->notas;
    }

    /**
     * Add compras
     *
     * @param SisGG\FinalBundle\Entity\Compra $compras
     * @return Empresa
     */
    public function addCompra(\SisGG\FinalBundle\Entity\Compra $compras) {
        $this->compras[] = $compras;

        return $this;
    }

    /**
     * Remove compras
     *
     * @param SisGG\FinalBundle\Entity\Compra $compras
     */
    public function removeCompra(\SisGG\FinalBundle\Entity\Compra $compras) {
        $this->compras->removeElement($compras);
    }

    /**
     * Get compras
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCompras() {
        return $this->compras;
    }

    /**
     * Add servicios
     *
     * @param \SisGG\FinalBundle\Entity\Servicio $servicios
     * @return Empresa
     */
    public function addServicio(\SisGG\FinalBundle\Entity\Servicio $servicios) {
        $this->servicios[] = $servicios;

        return $this;
    }

    /**
     * Remove servicios
     *
     * @param \SisGG\FinalBundle\Entity\Servicio $servicios
     */
    public function removeServicio(\SisGG\FinalBundle\Entity\Servicio $servicios) {
        $this->servicios->removeElement($servicios);
    }

    /**
     * Get servicios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServicios() {
        return $this->servicios;
    }

    /**
     * Add ivas
     *
     * @param \SisGG\FinalBundle\Entity\IVA $ivas
     * @return Empresa
     */
    public function addIva(\SisGG\FinalBundle\Entity\IVA $ivas) {
        $this->ivas[] = $ivas;

        return $this;
    }

    /**
     * Remove ivas
     *
     * @param \SisGG\FinalBundle\Entity\IVA $ivas
     */
    public function removeIva(\SisGG\FinalBundle\Entity\IVA $ivas) {
        $this->ivas->removeElement($ivas);
    }

    /**
     * Get ivas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIvas() {
        return $this->ivas;
    }

    /**
     * Set responsable
     *
     * @param boolean $responsable
     * @return Empresa
     */
    public function setResponsable($responsable) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return boolean 
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Add mantenimientos
     *
     * @param \SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos
     * @return Empresa
     */
    public function addMantenimiento(\SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos) {
        $this->mantenimientos[] = $mantenimientos;

        return $this;
    }

    /**
     * Remove mantenimientos
     *
     * @param \SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos
     */
    public function removeMantenimiento(\SisGG\FinalBundle\Entity\Mantenimiento $mantenimientos) {
        $this->mantenimientos->removeElement($mantenimientos);
    }

    /**
     * Get mantenimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMantenimientos() {
        return $this->mantenimientos;
    }

    /**
     * Add ivaCompras
     *
     * @param \SisGG\FinalBundle\Entity\LibroIvaCompra $ivaCompras
     * @return Empresa
     */
    public function addIvaCompra(\SisGG\FinalBundle\Entity\LibroIvaCompra $ivaCompras) {
        $this->ivaCompras[] = $ivaCompras;

        return $this;
    }

    /**
     * Remove ivaCompras
     *
     * @param \SisGG\FinalBundle\Entity\LibroIvaCompra $ivaCompras
     */
    public function removeIvaCompra(\SisGG\FinalBundle\Entity\LibroIvaCompra $ivaCompras) {
        $this->ivaCompras->removeElement($ivaCompras);
    }

    /**
     * Get ivaCompras
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIvaCompras() {
        return $this->ivaCompras;
    }

    /**
     * Add tipoPagos
     *
     * @param \SisGG\FinalBundle\Entity\TipoPago $tipoPagos
     * @return Empresa
     */
    public function addTipoPago(\SisGG\FinalBundle\Entity\TipoPago $tipoPagos) {
        $this->tipoPagos[] = $tipoPagos;

        return $this;
    }

    /**
     * Remove tipoPagos
     *
     * @param \SisGG\FinalBundle\Entity\TipoPago $tipoPagos
     */
    public function removeTipoPago(\SisGG\FinalBundle\Entity\TipoPago $tipoPagos) {
        $this->tipoPagos->removeElement($tipoPagos);
    }

    /**
     * Get tipoPagos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTipoPagos() {
        return $this->tipoPagos;
    }

    /**
     * Add facturasServicio
     *
     * @param \SisGG\FinalBundle\Entity\FacturaServicio $facturasServicio
     * @return Empresa
     */
    public function addFacturasServicio(\SisGG\FinalBundle\Entity\FacturaServicio $facturasServicio) {
        $this->facturasServicio[] = $facturasServicio;

        return $this;
    }

    /**
     * Remove facturasServicio
     *
     * @param \SisGG\FinalBundle\Entity\FacturaServicio $facturasServicio
     */
    public function removeFacturasServicio(\SisGG\FinalBundle\Entity\FacturaServicio $facturasServicio) {
        $this->facturasServicio->removeElement($facturasServicio);
    }

    /**
     * Get facturasServicio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFacturasServicio() {
        return $this->facturasServicio;
    }

    /**
     * Add personaEmpleados
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $personaEmpleados
     * @return Empresa
     */
    public function addPersonaEmpleado(\SisGG\FinalBundle\Entity\PersonaEmpleado $personaEmpleados) {
        $this->personaEmpleados[] = $personaEmpleados;

        return $this;
    }

    /**
     * Remove personaEmpleados
     *
     * @param \SisGG\FinalBundle\Entity\PersonaEmpleado $personaEmpleados
     */
    public function removePersonaEmpleado(\SisGG\FinalBundle\Entity\PersonaEmpleado $personaEmpleados) {
        $this->personaEmpleados->removeElement($personaEmpleados);
    }

    /**
     * Get personaEmpleados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonaEmpleados() {
        return $this->personaEmpleados;
    }

    /**
     * Add cargosSistema
     *
     * @param \SisGG\FinalBundle\Entity\CargoSistema $cargosSistema
     * @return Empresa
     */
    public function addCargosSistema(\SisGG\FinalBundle\Entity\CargoSistema $cargosSistema) {
        $this->cargosSistema[] = $cargosSistema;

        return $this;
    }

    /**
     * Remove cargosSistema
     *
     * @param \SisGG\FinalBundle\Entity\CargoSistema $cargosSistema
     */
    public function removeCargosSistema(\SisGG\FinalBundle\Entity\CargoSistema $cargosSistema) {
        $this->cargosSistema->removeElement($cargosSistema);
    }

    /**
     * Get cargosSistema
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCargosSistema() {
        return $this->cargosSistema;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     * @return Empresa
     */
    public function setSlogan($slogan) {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string 
     */
    public function getSlogan() {
        return $this->slogan;
    }

    /**
     * Add usuarios
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarios
     * @return Empresa
     */
    public function addUsuario(\SisGG\FinalBundle\Entity\Usuario $usuarios) {
        $this->usuarios[] = $usuarios;

        return $this;
    }

    /**
     * Remove usuarios
     *
     * @param \SisGG\FinalBundle\Entity\Usuario $usuarios
     */
    public function removeUsuario(\SisGG\FinalBundle\Entity\Usuario $usuarios) {
        $this->usuarios->removeElement($usuarios);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuarios() {
        return $this->usuarios;
    }

    /**
     * Busca un usuario
     *
     * @return Usuario
     */
    public function obtenerUsuario($id) {
        if ($this->usuarios == null)
            return null;
        foreach ($this->usuarios as $value) {
            if ($value->getId() == $id) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Add cajas
     *
     * @param \SisGG\FinalBundle\Entity\Caja $cajas
     * @return Empresa
     */
    public function addCaja(\SisGG\FinalBundle\Entity\Caja $cajas) {
        $this->cajas[] = $cajas;

        return $this;
    }

    /**
     * Remove cajas
     *
     * @param \SisGG\FinalBundle\Entity\Caja $cajas
     */
    public function removeCaja(\SisGG\FinalBundle\Entity\Caja $cajas) {
        $this->cajas->removeElement($cajas);
    }

    /**
     * Get cajas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCajas() {
        return $this->cajas;
    }

    /**
     * Busca una caja
     *
     * @return Caja
     */
    function obtenerCaja($id) {
        if ($this->cajas == null)
            return null;
        foreach ($this->cajas as $value) {
            if ($value->getId() == $id) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Add conceptos
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $conceptos
     * @return Empresa
     */
    public function addConcepto(\SisGG\FinalBundle\Entity\Concepto $conceptos) {
        $this->conceptos[] = $conceptos;

        return $this;
    }

    /**
     * Remove conceptos
     *
     * @param \SisGG\FinalBundle\Entity\Concepto $conceptos
     */
    public function removeConcepto(\SisGG\FinalBundle\Entity\Concepto $conceptos) {
        $this->conceptos->removeElement($conceptos);
    }

    /**
     * Get conceptos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConceptos() {
        return $this->conceptos;
    }

    /**
     * Busca un concepto
     *
     * @return Concepto
     */
    function obtenerConcepto($id) {
        if ($this->conceptos == null)
            return null;
        foreach ($this->conceptos as $value) {
            if ($value->getId() == $id) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Busca un concepto
     *
     * @return TipoCobro
     */
    function obtenerTipoCobro($nombre) {
        if ($this->tiposCobro == null)
            return null;
        foreach ($this->tiposCobro as $value) {
            if ($value->getNombre() == $nombre) {
                return $value;
            }
        }
        return null;
    }

    /**
     * Busca una tasa
     *
     * @return Tasa
     */
    function obtenerTasa($id) {
        if ($this->ums == null)
            return null;
        foreach ($this->ums as $um) {
            foreach ($um->getTasas() as $value) {
                if ($value->getId() == $id) {
                    return $value;
                }
            }
        }
        return null;
    }

    function estaCajaAbierta() {
        /* @var $caja Caja */
        $caja = $this->obtenerCaja(1);
        if ($caja->getAbierta()) {
            return true;
        }
        return false;
    }
    
 /**
     * Calificar categorias
     *
     * @return CategoriaProductoVenta
     */
    public function calificarPorCategorias($productos) {
        // $productos=  $this->productos;
        $array = null;
        foreach ($productos as $p) {
            $this->agruparCatVenta($p, $array);
        }
        sort($array);
        return $array;
    }

    private function agruparCatVenta(Producto $p, &$array) {
        if ($array == null) {
            $ps[] = array('nombre' => $p->getNombre(), 'producto' => $p);
            $array[] = array('categoria' => $p->catPadres(), 'productos' => $ps);
            return true;
        }
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['categoria'] == $p->catPadres()) {
                $ps = $array[$i]['productos'];
                $ps[] = array('nombre' => $p->getNombre(), 'producto' => $p);
                $array[$i] = array('categoria' => $p->catPadres(), 'productos' => $ps);
                return true;
            }
        }
        $ps[] = array('nombre' => $p->getNombre(), 'producto' => $p);
        $array[] = array('categoria' => $p->catPadres(), 'productos' => $ps);
        return false;
    }


    /**
     * Add telefonos
     *
     * @param \SisGG\FinalBundle\Entity\Telefono $telefonos
     * @return Empresa
     */
    public function addTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos) {
        $this->telefonos[] = $telefonos;

        return $this;
    }

    /**
     * Remove telefonos
     *
     * @param \SisGG\FinalBundle\Entity\Telefono $telefonos
     */
    public function removeTelefono(\SisGG\FinalBundle\Entity\Telefono $telefonos) {
        $this->telefonos->removeElement($telefonos);
    }

    /**
     * Get telefonos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTelefonos() {
        return $this->telefonos;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Empresa
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set foto
     *
     * @param \SisGG\FinalBundle\Entity\Image $foto
     * @return Empresa
     */
    public function setFoto(\SisGG\FinalBundle\Entity\Image $foto = null) {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return \SisGG\FinalBundle\Entity\Image 
     */
    public function getFoto() {
        return $this->foto;
    }

    /**
     * Set inicioAct
     *
     * @param \DateTime $inicioAct
     * @return Empresa
     */
    public function setInicioAct($inicioAct) {
        $this->inicioAct = $inicioAct;

        return $this;
    }

    /**
     * Get inicioAct
     *
     * @return \DateTime 
     */
    public function getInicioAct() {
        return $this->inicioAct;
    }

    /**
     * Add tiposCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tiposCobro
     * @return Empresa
     */
    public function addTiposCobro(\SisGG\FinalBundle\Entity\TipoCobro $tiposCobro) {
        $this->tiposCobro[] = $tiposCobro;

        return $this;
    }

    /**
     * Remove tiposCobro
     *
     * @param \SisGG\FinalBundle\Entity\TipoCobro $tiposCobro
     */
    public function removeTiposCobro(\SisGG\FinalBundle\Entity\TipoCobro $tiposCobro) {
        $this->tiposCobro->removeElement($tiposCobro);
    }

    /**
     * Get tiposCobro
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTiposCobro() {
        return $this->tiposCobro;
    }

    /**
     * Set contrasenia
     *
     * @param string $contrasenia
     * @return Empresa
     */
    public function setContrasenia($contrasenia) {
        $this->contrasenia = $contrasenia;

        return $this;
    }

    /**
     * Get contrasenia
     *
     * @return string 
     */
    public function getContrasenia() {
        return $this->contrasenia;
    }

    /**
     * Set carpeta
     *
     * @param string $carpeta
     * @return Empresa
     */
    public function setCarpeta($carpeta) {
        $this->carpeta = $carpeta;

        return $this;
    }

    /**
     * Get carpeta
     *
     * @return string 
     */
    public function getCarpeta() {
        return $this->carpeta;
    }

    /**
     * Set carpetaAuditoria
     *
     * @param string $carpetaAuditoria
     * @return Empresa
     */
    public function setCarpetaAuditoria($carpetaAuditoria) {
        $this->carpetaAuditoria = $carpetaAuditoria;

        return $this;
    }

    /**
     * Get carpetaAuditoria
     *
     * @return string 
     */
    public function getCarpetaAuditoria() {
        return $this->carpetaAuditoria;
    }

    /**
     * Set condicionIva
     *
     * @param \SisGG\FinalBundle\Entity\CondicionIva $condicionIva
     * @return Empresa
     */
    public function setCondicionIva(\SisGG\FinalBundle\Entity\CondicionIva $condicionIva = null) {
        $this->condicionIva = $condicionIva;

        return $this;
    }

    /**
     * Get condicionIva
     *
     * @return \SisGG\FinalBundle\Entity\CondicionIva 
     */
    public function getCondicionIva() {
        return $this->condicionIva;
    }



    /**
     * Set edad
     *
     * @param integer $edad
     * @return Empresa
     */
    public function setEdad($edad)
    {
        $this->edad = $edad;
    
        return $this;
    }

    /**
     * Get edad
     *
     * @return integer 
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Empresa
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }
}