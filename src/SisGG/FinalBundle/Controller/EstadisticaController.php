<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Form\AperturaType;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Entity\Cierre;
use SisGG\FinalBundle\Form\CierreType;
use SisGG\FinalBundle\Form\CajaType;
use SisGG\FinalBundle\Entity\Venta;
use SisGG\FinalBundle\Entity\LineaVenta;
use \DateTime;
use \DatePeriod;
use \DateInterval;

/**
 * Description of ClienteController
 *
 * @author martin
 */
class EstadisticaController extends Controller {

     function compras_totalAction() {
       // if ($this->getRequest()->isXmlHttpRequest()) {

            $empresa = $this->getEmpresa();
            $retorno = null;
            $lista = explode(",", $this->getRequest()->get('ids'));
            $tipo = $this->getRequest()->get('tipo');
            $compras = null;
            foreach ($lista as $id) {
                if ($id != null)
                    $compras[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->find($id);
            }

            if ($tipo == 1) {
                $array = $empresa->agruparCompras($compras, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['factura'];
                        $total+=$obj->getTotal();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'd-m-y'), 'total' => $total);
                }
            } elseif ($tipo == 2) {
                $array = $empresa->agruparCompras($compras, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['factura'];
                        $total+=$obj->getTotal();
                    }
                    $retorno[] = array('fecha' => $value[0]['fecha'], 'total' => $total);
                }
            } elseif ($tipo == 3) {
                $array = $empresa->agruparCompras($compras, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['factura'];
                        $total+=$obj->getTotal();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'Y-m'), 'total' => $total);
                }
            }

            asort($retorno);
            $salida = null;
            foreach ($retorno as $value) {
                $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
            }
            return new Response(json_encode($salida));
      //  }
    }

    function compras_compararAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $empresa = $this->getEmpresa();
            $lista = explode(",", $this->getRequest()->get('ids'));
            $tipo = $this->getRequest()->get('tipo');
            $año1 = $this->getRequest()->get('año1');
            $año2 = $this->getRequest()->get('año2');
            $dosArray = null;
            $compras = null;
            foreach ($lista as $id) {
                if ($id != null)
                    $compras[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Compra')->find($id);
            }

            if ($tipo == 1) {
                $comparar = $this->compararCompras($compras, $tipo, $año1, $año2);
                //G1
                $retorno = null;
                $array = $empresa->agruparCompras($comparar[0], 3);
                if ($array != null) {
                    foreach ($array as $value) {
                        $total = 0.00;
                        foreach ($value as $item) {
                            $obj = $item['factura'];
                            $total+=$obj->getTotal();
                        }
                        $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'n'), 'total' => $total);
                    }
                    asort($retorno);
                    $salida = null;
                    foreach ($retorno as $value) {
                        $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
                    }
                    $dosArray[0] = $salida;
                } else {
                    $sss = null;
                    for ($i = 1; $i < 13; $i++) {
                        $sss[] = array('fecha' => $i, 'total' => 0.00);
                    }
                    $dosArray[0] = $sss;
                }
                //G2
                $retorno = null;
                $array = $empresa->agruparCompras($comparar[1], 3);
                if ($array != null) {
                    foreach ($array as $value) {
                        $total = 0.00;
                        foreach ($value as $item) {
                            $obj = $item['factura'];
                            $total+=$obj->getTotal();
                        }
                        $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'n'), 'total' => $total);
                    }
                    asort($retorno);
                    $salida = null;
                    foreach ($retorno as $value) {
                        $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
                    }
                    $dosArray[1] = $salida;
                } else {
                    $sss = null;
                    for ($i = 1; $i < 13; $i++) {
                        $sss[] = array('fecha' => $i, 'total' => 0.00);
                    }
                    $dosArray[1] = $sss;
                }
                $fechas[] = 'error';
                $fechas[] = 'Enero';
                $fechas[] = 'Febrero';
                $fechas[] = 'Marzo';
                $fechas[] = 'Abril';
                $fechas[] = 'Mayo';
                $fechas[] = 'Junio';
                $fechas[] = 'Julio';
                $fechas[] = 'Agosto';
                $fechas[] = 'Septiembre';
                $fechas[] = 'Octubre';
                $fechas[] = 'Noviembre';
                $fechas[] = 'Diciembre';
                $dosArray[2] = $fechas;
            } elseif ($tipo == 2) {
                $array = $empresa->agruparCompras($compras, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['factura'];
                        $total+=$obj->getTotal();
                    }
                    $retorno[] = array('fecha' => $value[0]['fecha'], 'total' => $total);
                }
            } elseif ($tipo == 3) {
                $array = $empresa->agruparCompras($compras, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['factura'];
                        $total+=$obj->getTotal();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'Y-m'), 'total' => $total);
                }
            }

            return new Response(json_encode($dosArray));
        }
    }

    private function compararCompras($array, $tipo, $año1, $año2, $mes1 = null, $mes2 = null) {
        $retorno = null;
        $g1 = null;
        $g2 = null;
        if ($tipo == 1) {
            foreach ($array as $value) {
                if (date_format($value->getFechaFactura(), 'Y') == $año1) {
                    $g1[] = $value;
                }if (date_format($value->getFechaFactura(), 'Y') == $año2) {
                    $g2[] = $value;
                }
            }
        }
        $retorno[0] = $g1;
        $retorno[1] = $g2;
        return $retorno;
    }

    function pagos_totalAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $empresa = $this->getEmpresa();
            $retorno = null;
            $lista = explode(",", $this->getRequest()->get('ids'));
            $tipo = $this->getRequest()->get('tipo');
            $pagos = null;
            foreach ($lista as $id) {
                if ($id != null)
                    $pagos[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pago')->find($id);
            }

            if ($tipo == 1) {
                $array = $empresa->agruparPagos($pagos, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['pago'];
                        $total+=$obj->getImporte();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'd-m-y'), 'total' => $total);
                }
            } elseif ($tipo == 2) {
                $array = $empresa->agruparPagos($pagos, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['pago'];
                        $total+=$obj->getImporte();
                    }
                    $retorno[] = array('fecha' => $value[0]['fecha'], 'total' => $total);
                }
            } elseif ($tipo == 3) {
                $array = $empresa->agruparPagos($pagos, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['pago'];
                        $total+=$obj->getImporte();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'Y-m'), 'total' => $total);
                }
            }

            asort($retorno);
            $salida = null;
            foreach ($retorno as $value) {
                $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
            }
            return new Response(json_encode($salida));
        }
    }

    function pagos_compararAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $empresa = $this->getEmpresa();
            $lista = explode(",", $this->getRequest()->get('ids'));
            $tipo = $this->getRequest()->get('tipo');
            $año1 = $this->getRequest()->get('año1');
            $año2 = $this->getRequest()->get('año2');
            $dosArray = null;
            $pagos = null;
            foreach ($lista as $id) {
                if ($id != null)
                    $pagos[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Pago')->find($id);
            }

            if ($tipo == 1) {
                $comparar = $this->compararPagos($pagos, $tipo, $año1, $año2);
                //G1
                $retorno = null;
                $array = $empresa->agruparPagos($comparar[0], 3);
                if ($array != null) {
                    foreach ($array as $value) {
                        $total = 0.00;
                        foreach ($value as $item) {
                            $obj = $item['pago'];
                            $total+=$obj->getImporte();
                        }
                        $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'n'), 'total' => $total);
                    }
                    asort($retorno);
                    $salida = null;
                    foreach ($retorno as $value) {
                        $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
                    }
                    $dosArray[0] = $salida;
                } else {
                    $sss = null;
                    for ($i = 1; $i < 13; $i++) {
                        $sss[] = array('fecha' => $i, 'total' => 0.00);
                    }
                    $dosArray[0] = $sss;
                }
                //G2
                $retorno = null;
                $array = $empresa->agruparPagos($comparar[1], 3);
                if ($array != null) {
                    foreach ($array as $value) {
                        $total = 0.00;
                        foreach ($value as $item) {
                            $obj = $item['pago'];
                            $total+=$obj->getImporte();
                        }
                        $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'n'), 'total' => $total);
                    }
                    asort($retorno);
                    $salida = null;
                    foreach ($retorno as $value) {
                        $salida[] = array('fecha' => $value['fecha'], 'total' => $value['total']);
                    }
                    $dosArray[1] = $salida;
                } else {
                    $sss = null;
                    for ($i = 1; $i < 13; $i++) {
                        $sss[] = array('fecha' => $i, 'total' => 0.00);
                    }
                    $dosArray[1] = $sss;
                }
                $fechas[] = 'Enero';
                $fechas[] = 'Enero';
                $fechas[] = 'Febrero';
                $fechas[] = 'Marzo';
                $fechas[] = 'Abril';
                $fechas[] = 'Mayo';
                $fechas[] = 'Junio';
                $fechas[] = 'Julio';
                $fechas[] = 'Agosto';
                $fechas[] = 'Septiembre';
                $fechas[] = 'Octubre';
                $fechas[] = 'Noviembre';
                $fechas[] = 'Diciembre';
                $dosArray[2] = $fechas;
            } elseif ($tipo == 2) {
                $array = $empresa->agruparPagos($pagos, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['pago'];
                        $total+=$obj->getImporte();
                    }
                    $retorno[] = array('fecha' => $value[0]['fecha'], 'total' => $total);
                }
            } elseif ($tipo == 3) {
                $array = $empresa->agruparPagos($pagos, $tipo);
                foreach ($array as $value) {
                    $total = 0.00;
                    foreach ($value as $item) {
                        $obj = $item['pago'];
                        $total+=$obj->getImporte();
                    }
                    $retorno[] = array('fecha' => date_format($value[0]['fecha'], 'Y-m'), 'total' => $total);
                }
            }

            return new Response(json_encode($dosArray));
        }
    }

    private function compararPagos($array, $tipo, $año1, $año2, $mes1 = null, $mes2 = null) {
        $retorno = null;
        $g1 = null;
        $g2 = null;
        if ($tipo == 1) {
            foreach ($array as $value) {
                if (date_format($value->getFecha(), 'Y') == $año1) {
                    $g1[] = $value;
                }if (date_format($value->getFecha(), 'Y') == $año2) {
                    $g2[] = $value;
                }
            }
        }
        $retorno[0] = $g1;
        $retorno[1] = $g2;
        return $retorno;
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }
    public function estadisticasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $caja = $this->get('gestor_cajas')->getCaja();
        return $this->render('SisGGFinalBundle:Estadistica:estadisticas.html.twig', array('form' => null, 'gestor_autenticacion' => $gestor, 'caja' => $caja));
    }

    public function estadisticasVentasAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::CAJA_APERTURA_Y_CIERRRE)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        if ($this->getRequest()->getMethod() === 'POST') {
            $pasos = $this->getRequest()->get("pasos");
            $graficar = $this->getRequest()->get("graficar");
            $tipoProducto = $this->getRequest()->get("tipoProducto");
            $productos = $this->getRequest()->get("productos");
            $categorias = $this->getRequest()->get("categorias");
            $periodoSeleccionado = $this->getRequest()->get("periodoSeleccionado");
            $diaDesde = $this->getRequest()->get("diaDesde");
            $diaHasta = $this->getRequest()->get("diaHasta");
            $mesDesde = $this->getRequest()->get("mesDesde");
            $añoMesDesde = $this->getRequest()->get("añoMesDesde");
            $mesHasta = $this->getRequest()->get("mesHasta");
            $añoMesHasta = $this->getRequest()->get("añoMesHasta");
            $temporadaDesde = $this->getRequest()->get("temporadaDesde");
            $añoTemporadaDesde = $this->getRequest()->get("añoTemporadaDesde");
            $temporadaHasta = $this->getRequest()->get("temporadaHasta");
            $añoTemporadaHasta = $this->getRequest()->get("añoTemporadaHasta");
            $añoAnualDesde = $this->getRequest()->get("añoAnualDesde");
            $añoAnualHasta = $this->getRequest()->get("añoAnualHasta");
            $productosVendidos = null;
            $diaHastaArray = explode('/', $diaHasta);
            $diaDesdeArray = explode('/', $diaDesde);
            if ($graficar == 1) {//Productos Vendidos
                if ($tipoProducto == 1) {//Todos
                    if ($periodoSeleccionado == 1) { //Fechas
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]));
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($mesDesdeArray, $mesHastaArray);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray);
                    }
                } elseif ($tipoProducto == 2) {//Categoria Especifica
                    $categoria = $this->getDoctrine()->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->find($categorias);
                    if ($periodoSeleccionado == 1) { //Fechas
                        $ventas = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]));
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $ventas = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($mesDesdeArray, $mesHastaArray);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $ventas = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $ventas = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray);
                    }
                } elseif ($tipoProducto == 3) {//Productos Especificos
                    if ($periodoSeleccionado == 1) { //Fechas
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]), $productos);
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($mesDesdeArray, $mesHastaArray, $productos);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray, $productos);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray, $productos);
                    }
                }
            } else {//Recaudación por Ventas
                if ($tipoProducto == 1) {//Todos
                    if ($periodoSeleccionado == 1) { //Fechas
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]));
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($mesDesdeArray, $mesHastaArray);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray);
                    }
                } elseif ($tipoProducto == 2) {//Categoria Especifica
                    $categoria = $this->getDoctrine()->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->find($categorias);
                    if ($periodoSeleccionado == 1) { //Fechas
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]));
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($mesDesdeArray, $mesHastaArray);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray);
                    }
                } elseif ($tipoProducto == 3) {//Productos Especificos
                    //$p = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->findByIds($productos);
                    if ($periodoSeleccionado == 1) { //Fechas
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas(new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]), new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]), $productos);
                    } elseif ($periodoSeleccionado == 2) { //Meses
                        $mesHastaArray = new \DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta));
                        $mesDesdeArray = new \DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($mesDesdeArray, $mesHastaArray, $productos);
                    } elseif ($periodoSeleccionado == 3) { //Temporada
                        $temporadaDesdeArray = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                        $temporadaHastaArray = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($temporadaDesdeArray, $temporadaHastaArray, $productos);
                    } else if ($periodoSeleccionado == 4) {  //Años
                        $añoAnualDesdeArray = new \DateTime($añoAnualDesde . '-01-01');
                        $añoAnualHastaArray = new \DateTime($añoAnualHasta . '-12-31');
                        $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray, $productos);
                    }
                }
            }
            $ejeX = array();
            $ejeY = array();

            $begin = new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0]);
            $end = new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0]);

            $interval = null;
            
            if ($pasos == 1) {
                $interval = new DateInterval("PT1H");
            } elseif ($pasos == 2) {
                $interval = new DateInterval("P1D");
            } elseif ($pasos == 3) {
                $interval = new DateInterval("P1M");
            } elseif ($pasos == 4) {
                $interval = new DateInterval("P1Y");
            }

            if ($periodoSeleccionado == 1) { //Fechas
                $begin = new \DateTime($diaDesdeArray[2] . $diaDesdeArray[1] . $diaDesdeArray[0].' 00:00:00');
                $end = new \DateTime($diaHastaArray[2] . $diaHastaArray[1] . $diaHastaArray[0].' 23:59:59');
            } elseif ($periodoSeleccionado == 2) { //Meses
                $end =  new DateTime($añoMesHasta . '-' . $mesHasta . '-' . cal_days_in_month(CAL_GREGORIAN, $mesHasta, $añoMesHasta).' 23:59:59');
                $begin = new DateTime($añoMesDesde . '-' . $mesDesde . '-' . '1'.' 00:00:00');
            } elseif ($periodoSeleccionado == 3) { //Temporada
                $begin = $this->temporada($temporadaDesde, $añoTemporadaDesde);
                $end = $this->temporada($temporadaHasta, $añoTemporadaHasta, 'FIN');
            } else if ($periodoSeleccionado == 4) {  //Años
                $begin = new DateTime($añoAnualDesde . '-01-01 00:00:00');
                $end = new DateTime($añoAnualHasta . '-12-31 23:59:59');
            }
            
            $period = new DatePeriod($begin, $interval, $end);

            $eX=array();
            foreach ($period as $dt) {
                $ejeX[] = $dt->format("Y-m-d H:i:s");
                $eX[]= $dt;
                //$ejeY[] = $productosVendidos = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findRecaudacionVentasEntreFechas($añoAnualDesdeArray, $añoAnualHastaArray, $productos);
            }

            /*for ($index = 0; $index < count($ejeX)-1; $index++) {
                $principio = $eX[i];
                $fin = $eX[i+1];
                $retorno = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findCantidadVentasEntreFechas($principio,$fin);
                $ejeY[ ]= $retorno;
            }*/
            
            return new Response(json_encode(array('productosVendidos' => $productosVendidos, $ejeX,$eX)));
            return $this->render('SisGGFinalBundle:Estadistica:actualizarVentas.html.twig', array('productosVendidos' => $productosVendidos));
        }
        $ventas = $this->getDoctrine()->getRepository("SisGGFinalBundle:Venta")->findAll();
        $categorias = $this->getDoctrine()->getRepository("SisGGFinalBundle:CategoriaProductoVenta")->findAll();
        $productosVenta = $this->getDoctrine()->getRepository("SisGGFinalBundle:ProductoVenta")->findAll();
        return $this->render('SisGGFinalBundle:Estadistica:estadisticasVentas.html.twig', array('form' => null, 'gestor_autenticacion' => $gestor, 'ventas' => $ventas, 'categorias' => $categorias, 'productosVenta' => $productosVenta));
    }

    private function temporada($t, $a, $s = 'COMIENZO') {
        $retorno = null;
        if ($t == 1) {
            if ($s == 'COMIENZO') {
                $retorno = new \DateTime($a . '-12-21 00:00:00');
            } else if ($s == 'FIN') {
                $retorno = new \DateTime($a . '-03-20 23:59:59');
            }
        } else if ($t == 2) {
            if ($s == 'COMIENZO') {
                $retorno = new \DateTime($a . '-03-21 00:00:00');
            } else if ($s == 'FIN') {
                $retorno = new \DateTime($a . '-07-20 23:59:59');
            }
        } else if ($t == 3) {
            if ($s == 'COMIENZO') {
                $retorno = new \DateTime($a . '-07-21 00:00:00');
            } else if ($s == 'FIN') {
                $retorno = new \DateTime($a . '-09-20 23:59:59');
            }
        } else if ($t == 4) {
            if ($s == 'COMIENZO') {
                $retorno = new \DateTime($a . '-09-21 00:00:00');
            } else if ($s == 'FIN') {
                $retorno = new \DateTime($a . '-12-20 23:59:59');
            }
        }
        return $retorno;
    }

    
}