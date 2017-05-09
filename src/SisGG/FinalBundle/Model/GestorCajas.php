<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\Lote;
use SisGG\FinalBundle\Entity\Entrada;
use SisGG\FinalBundle\Entity\Cobro;
use SisGG\FinalBundle\Entity\TipoCobro;
use SisGG\FinalBundle\Entity\ItemCierre;
use SisGG\FinalBundle\Entity\Usuario;
use SisGG\FinalBundle\Entity\Cierre;
use SisGG\FinalBundle\Entity\Movimiento;
use SisGG\FinalBundle\Entity\Salida;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Entity\TipoFactura;
use SisGG\FinalBundle\Entity\Valor;
use SisGG\FinalBundle\Entity\Recibo;
/**
 * Description of GestorCajas
 *
 * @author martin
 */
class GestorCajas {
    /* @var $em \Doctrine\ORM\EntityManager */
    private $em = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorMensajes */
    private $gestorMensajes = null;

    public function __construct(EntityManager $entityManager, GestorMensajes $gestorMensajes) {
        $this->em = $entityManager;
        $this->gestorMensajes = $gestorMensajes;
    }

    /**
     * @param \SisGG\FinalBundle\Model\SisGG\FinalBundle\Entity\Apertura $unaApertura
     */
    public function aperturaCaja(Apertura $unaApertura) {
        $retorno = null;
        if ($this->getCaja()->getAbierta()) {
            $retorno = 'La caja ya se encuentra abierta';
        } else {
            $unLote = new Lote();
            $unLote->setCaja($this->getCaja());
            $unLote->setUsuario($unaApertura->getUsuario());
            $unLote->setFechaApertura(new \DateTime('now'));
            $unLote->addMovimiento($unaApertura);
            $this->getCaja()->setAbierta(true);
            $unaApertura->setLote($unLote);
            $unaApertura->setCaja($unLote->getCaja());
            $unaApertura->setTipo($this->em->getRepository("SisGGFinalBundle:TipoCobro")->find(1));
            $this->getCaja()->setSaldo($unaApertura->getImporte());
            $this->em->persist($unLote);
            $this->em->flush();
        }
        return $retorno;
    }

    /**
     * @param \SisGG\FinalBundle\Model\SisGG\FinalBundle\Entity\Cierre $unCierre
     */
    public function cierreCaja(Cierre $unCierre) {
        $retorno = null;
        if (!$this->getCaja()->getAbierta()) {
            $retorno = 'La caja se encuentra cerrada';
        } else {
            /* @var $unLote Lote */
            $unLote = $this->getCaja()->getLotes()->first();
            $this->getCaja()->setAbierta(false);
            $cierres = $this->em->getRepository("SisGGFinalBundle:Cierre")->findAll();
            $i = 1;
            foreach ($cierres as $cierre) {
                $i = $i + 1;
            }
            $unCierre->setNumeroCierre($i);
            $unCierre->setLote($unLote);
            $unCierre->setFecha(new \DateTime('now'));
            $unLote->setFechaCierre(new \DateTime('now'));
            $this->em->persist($unCierre);
            $this->em->flush();
        }
        return $retorno;
    }

    /**
     * @return Caja
     */
    public function getCaja() {
        $caja = $this->em->getRepository("SisGGFinalBundle:Caja")->findCaja();
        return $caja[0];
    }

    /**
     * 
     * @param \SisGG\FinalBundle\Entity\TipoFactura $tipoFactura
     * @return array
     */
    public function getNumeroSiguiente(TipoFactura $tipoFactura) {
        $retorno = array();
        if ($tipoFactura->getId() == 1) {
            $retorno = array("puntoVenta" => $this->getCaja()->getPuntoVentaA(), "numero" => $this->getCaja()->getUltimaFacturaA() + 1);
        } else if ($tipoFactura->getId() == 2) {
            $retorno = array("puntoVenta" => $this->getCaja()->getPuntoVentaB(), "numero" => $this->getCaja()->getUltimaFacturaB() + 1);
        } else if ($tipoFactura->getId() == 3) {
            $retorno = array("puntoVenta" => $this->getCaja()->getPuntoVentaC(), "numero" => $this->getCaja()->getUltimaFacturaC() + 1);
        }
        return $retorno;
    }

    /**
     * 
     * @param \SisGG\FinalBundle\Entity\NotaCredito $unaNotaCredito
     * @return array
     */
    public function getNumeroSiguienteNotaCredito(\SisGG\FinalBundle\Entity\NotaCredito $unaNotaCredito) {
        $retorno = array();
        if ($unaNotaCredito->getFactura()->getTipoFactura()->getId() == 1) {
            $retorno = array("serie" => $this->getCaja()->getSerieNotaCreditoA(), "numero" => $this->getCaja()->getUltimoNumeroNotaCreditoA() + 1);
        } else if ($unaNotaCredito->getFactura()->getTipoFactura()->getId() == 2) {
            $retorno = array("serie" => $this->getCaja()->getSerieNotaCreditoB(), "numero" => $this->getCaja()->getUltimoNumeroNotaCreditoB() + 1);
        } else if ($unaNotaCredito->getFactura()->getTipoFactura()->getId() == 3) {
            $retorno = array("serie" => $this->getCaja()->getSerieNotaCreditoC(), "numero" => $this->getCaja()->getUltimoNumeroNotaCreditoC() + 1);
        }
        return $retorno;
    }
    
    /**
     * 
     * @param \SisGG\FinalBundle\Entity\TipoFactura $tipoFactura
     * @return array
     */
    public function getNumeroSiguienteRecibo() {
        $retorno = array("serie" => $this->getCaja()->getSerieRecibo(), "numero" => $this->getCaja()->getUltimoNumeroRecibo() + 1);
        return $retorno;
    }
    
    public function setNumeroRecibo(Recibo $unRecibo) {
        $this->getCaja()->setSerieRecibo($unRecibo->getSerie());
        $this->getCaja()->setUltimoNumeroRecibo($unRecibo->getNumero());
    }

    public function setNumeroFactura(Venta $unaVenta) {
        $tipoFactura = $unaVenta->getTipoFactura();
        if ($tipoFactura->getId() == 1) {
            $this->getCaja()->setPuntoVentaA($unaVenta->getSerie());
            $this->getCaja()->setUltimaFacturaA($unaVenta->getNumero());
        } else if ($tipoFactura->getId() == 2) {
            $this->getCaja()->setPuntoVentaB($unaVenta->getSerie());
            $this->getCaja()->setUltimaFacturaB($unaVenta->getNumero());
        } else if ($tipoFactura->getId() == 3) {
            $this->getCaja()->setPuntoVentaC($unaVenta->getSerie());
            $this->getCaja()->setUltimaFacturaC($unaVenta->getNumero());
        }
    }

    public function setNumeroNotaCredito(\SisGG\FinalBundle\Entity\NotaCredito $unaNotaCredito) {
        $tipoFactura = $unaNotaCredito->getFactura()->getTipoFactura();
        if ($tipoFactura->getId() == 1) {
            $this->getCaja()->setSerieNotaCreditoA($unaNotaCredito->getSerie());
            $this->getCaja()->setUltimoNumeroNotaCreditoA($unaNotaCredito->getNumero());
        } else if ($tipoFactura->getId() == 2) {
            $this->getCaja()->setSerieNotaCreditoB($unaNotaCredito->getSerie());
            $this->getCaja()->setUltimoNumeroNotaCreditoB($unaNotaCredito->getNumero());
        } else if ($tipoFactura->getId() == 3) {
            $this->getCaja()->setSerieNotaCreditoC($unaNotaCredito->getSerie());
            $this->getCaja()->setUltimoNumeroNotaCreditoC($unaNotaCredito->getNumero());
        }
    }

    /*
     * Relacionar Cobro a Entrada en vez de hacerlo por aca
     */

    public function registrarCobro(Cobro $unCobro, Usuario $unUsuario) {
        $unaEntrada = new Entrada();
        $unaEntrada->setCaja($this->getCaja());
        $unaEntrada->setLote($this->getCaja()->getLotes()->first());
        $unaEntrada->setUsuario($unUsuario);
        $unaEntrada->setTipo($unCobro->getTipoCobro());
        $unaEntrada->setImporte($unCobro->getImporte());
        $unaEntrada->setAclaracion($unCobro);
        $unaEntrada->setFecha(new \DateTime());
        /* @var $valor Valor */
        foreach ($unCobro->getValores() as $valor) {
            $valor->setCobro($unCobro);
        }
        if ($unaEntrada->getTipo()->getId() == 1) {
            $this->getCaja()->setSaldo($this->getCaja()->getSaldo() + $unaEntrada->getImporte());
        }
        $this->em->persist($unaEntrada);
        $this->em->flush();
    }

    public function registrarRendicionEnvio($unUsuario, \SisGG\FinalBundle\Entity\RegistroEnvio $unRegistroEnvio) {
        $unaEntrada = new Entrada();
        $unaEntrada->setCaja($this->getCaja());
        $unaEntrada->setLote($this->getCaja()->getLotes()->first());
        $unaEntrada->setUsuario($unUsuario);
        $tiposCobro = $this->em->getRepository("SisGGFinalBundle:TipoCobro")->findAll();
        $unaEntrada->setTipo($tiposCobro[0]);
        $unaEntrada->setImporte($unRegistroEnvio->getTotalRendido());
        $unaEntrada->setAclaracion($unRegistroEnvio);
        $unaEntrada->setFecha(new \DateTime());
        if ($unaEntrada->getTipo()->getId() == 1) {
            $this->getCaja()->setSaldo($this->getCaja()->getSaldo() + $unaEntrada->getImporte());
        }
        $this->em->persist($unaEntrada);
        $this->em->flush();
    }

    public function registrarCambio(Venta $unaVenta, Usuario $unUsuario) {
        $unaSalida = new Salida();
        $unaSalida->setCaja($this->getCaja());
        $unaSalida->setLote($this->getCaja()->getLotes()->first());
        $unaSalida->setUsuario($unUsuario);
        $unaSalida->setTipo($this->em->getRepository("SisGGFinalBundle:TipoCobro")->find(1));
        $importe = 0.00;
        /* @var $cobro Cobro */
        foreach ($unaVenta->getCobros() as $cobro) {
            $importe = $importe + $cobro->getImporte();
        }
        $unaSalida->setImporte($importe - $unaVenta->getTotal());
        $unaSalida->setAclaracion("Cambio " . $unaVenta->getTipoFactura() . " " . $unaVenta->getNumeroFactura());
        $unaSalida->setFecha(new \DateTime());
        if ($unaSalida->getTipo()->getId() == 1) {
            $this->getCaja()->setSaldo($this->getCaja()->getSaldo() - $unaSalida->getImporte());
        }
        $this->em->persist($unaSalida);
        $this->em->flush();
    }

    public function arqueo() {
        $retorno = new Cierre();
        $retorno->setCaja($this->getCaja());
        $retorno->setLote($this->getCaja()->getLotes()->first());
        $cierres = $this->em->getRepository("SisGGFinalBundle:Cierre")->findAll();
        $i = 1;
        foreach ($cierres as $cierre) {
            $i = $i + 1;
        }
        $retorno->setNumeroCierre($i);
        $tiposCobro = $this->em->getRepository("SisGGFinalBundle:TipoCobro")->findAll();
        /* @var $tipoCobro TipoCobro */
        foreach ($tiposCobro as $tipoCobro) {
            if ($tipoCobro->getActivo() and $tipoCobro->getLiquido()) {
                $itemCierre = new ItemCierre();
                $itemCierre->setCierre($retorno);
                $itemCierre->setTipoCobro($tipoCobro);
                $saldo = 0;
                /* @var $movimiento Movimiento */
                foreach ($retorno->getLote()->getMovimientos() as $movimiento) {
                    if ($movimiento instanceof Entrada || $movimiento instanceof Apertura) {
                        /* @var $movimiento Entrada */
                        if ($movimiento->getTipo() == $itemCierre->getTipoCobro()) {
                            $saldo = $saldo + $movimiento->getImporte();
                        }
                    } elseif ($movimiento instanceof Salida) {
                        /* @var $movimiento Salida */
                        if ($movimiento->getTipo() == $itemCierre->getTipoCobro()) {
                            $saldo = $saldo - $movimiento->getImporte();
                        }
                    }
                }
                $itemCierre->setImporteSistema($saldo);
                $retorno->addItemCierre($itemCierre);
            }
        }
        return $retorno;
    }

    /**
     * @param \SisGG\FinalBundle\Entity\Movimiento $movimiento
     * @param \SisGG\FinalBundle\Entity\Usuario $unUsuario
     */
    public function nuevoMovimiento(Movimiento $movimiento, Usuario $unUsuario) {
        switch ($movimiento->t) {
            case 'Entrada':
                $unaEntrada = new Entrada();
                $unaEntrada->setAclaracion($movimiento->getAclaracion());
                $unaEntrada->setCaja($this->getCaja());
                $unaEntrada->setConcepto($movimiento->getConcepto());
                $unaEntrada->setImporte($movimiento->getImporte());
                $unaEntrada->setLote($this->getCaja()->getLotes()->first());
                $unaEntrada->setTipo($movimiento->getTipo());
                $unaEntrada->setUsuario($unUsuario);
                if ($unaEntrada->getTipo()->getId() == 1) {
                    $this->getCaja()->setSaldo($this->getCaja()->getSaldo() + $unaEntrada->getImporte());
                }
                $this->em->persist($unaEntrada);
                $this->em->flush();
                return $unaEntrada;
                break;
            case 'Salida':
                $unaSalida = new Salida();
                $unaSalida->setAclaracion($movimiento->getAclaracion());
                $unaSalida->setCaja($this->getCaja());
                $unaSalida->setConcepto($movimiento->getConcepto());
                $unaSalida->setImporte($movimiento->getImporte());
                $unaSalida->setLote($this->getCaja()->getLotes()->first());
                $unaSalida->setTipo($movimiento->getTipo());
                $unaSalida->setUsuario($unUsuario);
                if ($unaSalida->getTipo()->getId() == 1) {
                    $this->getCaja()->setSaldo($this->getCaja()->getSaldo() - $unaSalida->getImporte());
                }
                $this->em->persist($unaSalida);
                $this->em->flush();
                return $unaSalida;
                break;
        }
    }
    
    public function registrarRecibo(Recibo $unRecibo,$unUsuario){
        $unaEntrada = new Entrada();
        $unaEntrada->setCaja($this->getCaja());
        $unaEntrada->setLote($this->getCaja()->getLotes()->first());
        $unaEntrada->setUsuario($unUsuario);
        $unaEntrada->setTipo($this->em->getRepository("SisGGFinalBundle:TipoCobro")->findAll()[0]);
        $unaEntrada->setImporte($unRecibo->getTotal());
        $unaEntrada->setAclaracion($unRecibo);
        $unaEntrada->setFecha(new \DateTime());
        $this->setNumeroRecibo($unRecibo);
        if ($unaEntrada->getTipo()->getId() == 1) {
            $this->getCaja()->setSaldo($this->getCaja()->getSaldo() + $unaEntrada->getImporte());
        }
        $this->em->persist($unaEntrada);
        $this->em->flush();
    }

}

?>
