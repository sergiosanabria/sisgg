<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use SisGG\FinalBundle\Entity\Pedido;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\TipoOperacion;
use SisGG\FinalBundle\Entity\CondicionIva;
use SisGG\FinalBundle\Entity\TipoFactura;
use SisGG\FinalBundle\Entity\Cliente;
use SisGG\FinalBundle\Entity\LineaVenta;
use SisGG\FinalBundle\Model\GestorCajas;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Entity\NotaCredito;

/**
 * Description of GestorVentas
 *
 * @author martin
 */
class GestorVentas {
    /* @var $em \Doctrine\ORM\EntityManager */

    private $em = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorMensajes */
    private $gestorMensajes = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorCajas */
    private $gestorCajas = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorCuentasCorriente */
    private $gestorCuentasCorriente = null;

    public function __construct(EntityManager $entityManager, GestorMensajes $gestorMensajes, GestorCajas $gestorCajas, GestorCuentasCorriente $gestorCuentasCorrientes) {
        $this->em = $entityManager;
        $this->gestorMensajes = $gestorMensajes;
        $this->gestorCajas = $gestorCajas;
        $this->gestorCuentasCorriente = $gestorCuentasCorrientes;
    }

    /**
     * @return GestorCajas
     */
    public function getGestorCajas() {
        return $this->gestorCajas;
    }
    
    /**
     * @return GestorCuentasCorriente
     */
    public function getGestorCuentasCorriente() {
        return $this->gestorCuentasCorriente;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getVentas() {
        return $this->em->getRepository("SisGGFinalBundle:Venta")->findAll();
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getEmpresa() {
        return $this->em->getRepository("SisGGFinalBundle:Empresa")->findAll()[0];
    }

    /**
     * @return Pedido
     */
    public function getPedido($id) {
        return $this->em->getRepository("SisGGFinalBundle:Pedido")->find($id);
    }

    /**
     * @return Venta
     */
    public function getVenta($id) {
        return $this->em->getRepository("SisGGFinalBundle:Venta")->find($id);
    }

    /**
     * @return Cliente
     */
    public function getClientePorDefecto() {
        $clientes = $this->em->getRepository("SisGGFinalBundle:Cliente")->findPorDefecto();
        return $clientes[0];
    }

    public function getTipoFactura(Venta $unaVenta) {
        $unaVenta->setTipoOperacion($this->em->getRepository("SisGGFinalBundle:TipoOperacion")->find(1));
        $unaVenta->setOperacion($this->em->getRepository("SisGGFinalBundle:Operacion")->findOperacion($unaVenta->getTipoOperacion(), $unaVenta->getCondicionIvaEmpresa(), $unaVenta->getCondicionIva())[0]);
        return $unaVenta->getOperacion()->getTipoFactura();
    }

    public function nuevaVenta($usuario, $id) {
        $unaVenta = new Venta();
        $unaVenta->setUsuario($usuario);
        $unaVenta->setFecha(new \DateTime('now'));
        $unPedido = $this->getPedido($id);
        /* @var $cliente Cliente */
        $cliente = $unPedido->getCliente() != null ? $unPedido->getCliente() : $this->getClientePorDefecto();
        $unaVenta->setCliente($cliente);
        $unaVenta->setPedido($unPedido);
        $unaVenta->setCondicionIva($cliente->getCondicionIva());
        $unaVenta->setCuit($cliente->getCuit());
        $unaVenta->setNombre($unPedido->getSolicitante() != null ? $unPedido->getSolicitante() : $cliente->getApellido() . " " . $cliente->getNombre());
        $unaVenta->setCondicionIvaEmpresa($this->getEmpresa()->getCondicionIva());
        $unaVenta->setTipoFactura($this->getTipoFactura($unaVenta));
        $unaVenta->setSerie($this->getGestorCajas()->getNumeroSiguiente($unaVenta->getTipoFactura())['puntoVenta']);
        $unaVenta->setNumero($this->getGestorCajas()->getNumeroSiguiente($unaVenta->getTipoFactura())['numero']);
        /* @var $lineaVenta LineaVenta */
        foreach ($unaVenta->getLineasVenta() as $lineaVenta) {
            $unaVenta->sumarIva($lineaVenta->getTotal(), $lineaVenta->getIvaProductoVenta());
        }
        /* @var $item \SisGG\FinalBundle\Entity\ItemRecargoVenta */
        foreach ($unaVenta->getItemsRecargoVenta() as $item) {
            $unaVenta->sumarIva($item->getTotalRecargo(), $item->getIva());
        }
        $unaVenta->calcularTotal();
        return $unaVenta;
    }

    public function guardarVenta($unUsuario, $id, Venta $unaVenta) {
        $total = 0.00;
        $estado = "Sin Cobrar";
        /* @var $unCobro Cobro */
        foreach ($unaVenta->getCobros() as $unCobro) {
            $unCobro->setVenta($unaVenta);
            $this->gestorCajas->registrarCobro($unCobro, $unUsuario);
            $total = $total + $unCobro->getImporte();
            $estado = "Parcialmente Cobrado";
        }
        if ($total == $unaVenta->getTotal()) {
            $estado = "Cobrado";
        } else if ($total > $unaVenta->getTotal()) {
            $this->getGestorCajas()->registrarCambio($unaVenta, $unUsuario);
            $estado = "Cobrado";
        }
        foreach ($unaVenta->getLineasVenta() as $lineaVenta) {
            $lineaVenta->setVenta($unaVenta);
        }
        foreach ($unaVenta->getItemsDescuentoVenta() as $itemDescuentoVenta) {
            $itemDescuentoVenta->setVenta($unaVenta);
        }
        foreach ($unaVenta->getItemsRecargoVenta() as $itemRecargoVenta) {
            $itemRecargoVenta->setVenta($unaVenta);
        }
        $unPedido = $this->getPedido($id);
        $unPedido->setEstado("Facturado");
        $unaVenta->setPedido($unPedido);
        $unaVenta->setEstado($estado);
        $this->gestorCajas->setNumeroFactura($unaVenta);
        $this->em->persist($unaVenta);
        $this->em->flush();
        $this->getGestorCuentasCorriente()->registrarMovimientoDebe($unaVenta);
        $this->em->flush();
    }

    public function anularVenta($unUsuario, $id) {
        $unaVenta = $this->getVenta($id);
        if ($unaVenta->getTipoFactura()->getNotaCreditoAnulacion() == false) {
            $unaVenta->setEstado("Anulado");
            $unaVenta->getPedido()->setEstado("Listo");
            $unaVenta->setPedido(null);
            $this->em->flush();
        }
        return $unaVenta;
    }

    public function nuevaNotaCredito($unUsuario, $id) {
        $unaVenta = $this->getVenta($id);
        $unaNotaCredito = new NotaCredito();
        $unaNotaCredito->setFactura($unaVenta);
        $unaNotaCredito->setFecha(new \DateTime('now'));
        $unaNotaCredito->setUsuario($unUsuario);
        $unaNotaCredito->setSerie($this->getGestorCajas()->getNumeroSiguienteNotaCredito($unaNotaCredito)['serie']);
        $unaNotaCredito->setNumero($this->getGestorCajas()->getNumeroSiguienteNotaCredito($unaNotaCredito)['numero']);
        return $unaNotaCredito;
    }

    public function guardarNotaCredito($unUsuario, NotaCredito $unaNotaCredito) {
        $unaVenta = $unaNotaCredito->getFactura();
        $unaVenta->setEstado("Anulado");
        $unaVenta->getPedido()->setEstado("Listo");
        $unaVenta->setPedido(null);
        $this->em->flush();
        $this->gestorCajas->setNumeroFactura($unaVenta);
        $this->em->persist($unaNotaCredito);
        $this->em->flush();
    }

    //BUSCARVentas
    /**
     * Busca factura de compras desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int   $tiempo      La diferencia del tiempo menor o igual a la fecha actual.
     * @param char    $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarVenta($tiempo, $tipo) {
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

        foreach ($this->getVentas() as $value) {
            $otra = $value->getFecha();
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
     * Busca factura de Ventas, desde una fecha determinada, hasta otra.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param DateTime  $fecha2     Rango mayor de la fecha. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha. 
     */
    public function buscarVentaEntreFechas(\DateTime $fecha1, \DateTime $fecha2) {
        $array = null;
        $total = 0.00;
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($this->getVentas() as $value) {
            $otra = $value->getFecha();
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
     * Busca factura de Ventas, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime   $fecha1      Rango menor de la fecha.
     * @param int  $tipo     0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy. 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarVentaFecha(\DateTime $fecha1, $tipo) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($this->getVentas() as $value) {
                $otra = $value->getFecha();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1)) {
                    $array[] = $value;
                    $total+=$value->getTotal();
                }
            }
            $array[] = $total;
            return $array;
        } elseif ($tipo == 1) {
            foreach ($this->getVentas() as $value) {
                $otra = $value->getFecha();
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
            foreach ($this->getVentas() as $value) {
                $otra = $value->getFecha();
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
     * Busca factura de Venta, de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int   $mes      [1..12].
     * @param int  $año       [>0] 
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarVentaMesAño($mes, $año) {
        $array = null;
        $total = 0.00;
        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($this->getVentas() as $value) {
                $otra = $value->getFecha();
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
            /* @var $value Venta */
            foreach ($this->getVentas() as $value) {
                $otra = $value->getFecha();
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

}

?>
