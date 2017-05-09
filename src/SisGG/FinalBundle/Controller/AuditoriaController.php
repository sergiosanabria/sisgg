<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SisGG\FinalBundle\Entity\Apertura;
use SisGG\FinalBundle\Form\AperturaType;
use SisGG\FinalBundle\Entity\Caja;
use SisGG\FinalBundle\Form\CajaType;
use Gedmo\Mapping\Annotation as Gedmo;
use \DateTime;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Empresa;
use Symfony\Component\HttpFoundation\File\UploadedFile;
//use Ps\PdfBundle\Annotation\Pdf;
use \ZipArchive;

/**
 * Description of ClienteController
 *
 * @author martin
 */
class AuditoriaController extends Controller
{

    public function auditoriasAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::AUDITORIA_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        /* @var $repo \Gedmo\Loggable\Document\LogEntry */
        $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');

        $error = $this->getRequest()->getSession()->get('errorAUD');
        $msj = $this->getRequest()->getSession()->get('msjAUD');
        $this->getRequest()->getSession()->remove('errorAUD');
        $this->getRequest()->getSession()->remove('msjAUD');
        $en = $repo->findAll();
        $parameters = array("entries" => $en, 'form' => null, 'error' => $error, 'msj' => $msj, 'arc' => false);
        return $this->render("SisGGFinalBundle:Auditoria:auditorias.html.twig", $parameters);
    }

    public function elegirCarpetaAuditoriaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::AUDITORIA_ELEGIR_CARPETA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $empresa Empresa */
        $empresa = $this->getEmpresa();
        if ($this->getRequest()->getMethod() === 'POST') {
            $carpeta = $this->getRequest()->get('carpeta');
            //$carpeta= stripos($carpeta, '/'); 
            if (!(is_dir($carpeta))) {
                return $this->render('SisGGFinalBundle:Auditoria:elegirCarpeta.html.twig', array('form' => null, 'empresa' => $empresa, 'error' => 'La carpeta ' . $carpeta . ' que elegio no existe.'));
            }
            $empresa->setCarpetaAuditoria($carpeta);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($empresa);
            $em->flush();
            return $this->redirect($this->generateUrl('parametros'));
        }

        //$file->get();
        return $this->render('SisGGFinalBundle:Auditoria:elegirCarpeta.html.twig', array('form' => null, 'error' => null, 'empresa' => $empresa, 'msj' => null));
    }

    public function archivoAuditoriaAction()
    {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::AUDITORIA_DESCARGARARCHIVO)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $empresa Empresa */
        $empresa = $this->getEmpresa();
        $carpeta = $empresa->getCarpetaAuditoria();
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
        //$entrada = new \Gedmo\Loggable\Entity\LogEntry();
        $csv_end = "\n";
        $csv_sep = ";";
        $csv_file = $carpeta . "datas.csv";
        $csv = "";
        $csv .= "ID" . $csv_sep . "Entidad" . $csv_sep . "Acción" . $csv_sep . "Fecha" . $csv_sep . "Usuario" . $csv_sep . "Datos" . $csv_end;
        $i = 0;
        foreach ($lista as $value) {
            /* @var $value \Gedmo\Loggable\Entity\LogEntry */

            $clase = explode("\\", $value->getObjectClass());
            if ($value->getAction() == 'create') {
                $accion = "Creado";
            } elseif ($value->getAction() == 'remove') {
                $accion = "Borrado";
            } elseif ($value->getAction() == 'update') {
                $accion = "Modificado";
            }

            $pal = "";
            if ($value->getData() != null) {
                foreach ($value->getData() as $key => $val) {
                    if ($val instanceof DateTime) {
                        $pal .= $key . '=' . date_format($val, 'd/m/Y H:i:s') . '|';
                    } else {
                        if (is_array($val)) {
                            foreach ($val as $uno) {
                                $pal .= $key . '=' . $uno . '|';
                            }
                        } else {
                            $pal .= $key . '=' . $val . '|';
                        }
                    }
                }
            }
            //$csv_sep . json_encode($value->getData())
            $csv .= $value->getId() . $csv_sep . $clase[3] . $csv_sep . $accion . $csv_sep . date_format($value->getLoggedAt(), 'd/m/y H:i:s') . $csv_sep . $value->getUsername() . $csv_sep . $pal . $csv_end;


        }


        //Generamos el csv de todos los datos
        if (!$handle = fopen($csv_file, "w")) {
            $this->getRequest()->getSession()->set('errorAUD', 'Error al generar el archivo. ');
            return $this->redirect($this->generateUrl('auditorias'));
        }
        if (fwrite($handle, utf8_decode($csv)) === FALSE) {
            $this->getRequest()->getSession()->set('errorAUD', 'Error al generar el archivo. ');
            return $this->redirect($this->generateUrl('auditorias'));
        }
        fclose($handle);
        //Generamos el zip
        $zip = new ZipArchive();
        $path = __DIR__ . '/../../../../web/temp/';
        $filename = $path . 'auditoria.zip';

        if ($zip->open($filename, ZIPARCHIVE::CREATE) === true) {
            $zip->addFile($csv_file, 'auditoria.csv');
            $zip->close();
        } else {
            $this->getRequest()->getSession()->set('errorAUD', 'Error al generar el archivo. ');
            return $this->redirect($this->generateUrl('auditorias'));
        }
        return $this->redirect($this->generateUrl('descargarAuditoria'));
    }

    public function descargarAuditoriaAction()
    {
        $response = new Response();
        $path = __DIR__ . '/../../../../web/temp/';
        $filename = $path . 'auditoria.zip';
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="auditoria.zip"');
        $response->headers->set('Content-length', filesize($filename));
        // Send headers before outputting anything
        $response->sendHeaders();
        $response->setContent(readfile($filename));
        return $response;
    }

    function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public function getEmpresa()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaAuditoriaAction()
//    {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::AUDITORIA_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//
//        $em = $this->getDoctrine()->getEntityManager();
//        $entradas = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->obtenerEntrada($id, $entradas);
//        }
//        $format = $this->get('request')->get('_format');
//        set_time_limit(370);
//        $parameters = array('form' => null, 'items' => $array, 'datos' => $this->getRequest()->get('datos'), 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:Auditoria:imp_lista.' . $format . '.twig', $parameters);
//    }

    private function obtenerEntrada($id, $entradas)
    {
        if (isset($entradas)) {
            foreach ($entradas as $value) {
                if ($value->getId() == $id)
                    return $value;
            }
        }
        return null;
    }

    public function buscarAuditoriasAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $tipo = $this->getRequest()->get('tipo');
            $array = null;
            $total = 0.00;
            if ($tipo == -1) {
                $em = $this->getDoctrine()->getEntityManager();
                $array = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();

            } elseif ($tipo == 0) {
                $clase = $this->getRequest()->get('clase');
                $tiempo = $this->getRequest()->get('tiempo');
                $array = $this->buscarAuditoria($tiempo, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 1) {
                $fecha1 = $this->getRequest()->get('fecha1');
                $fecha2 = $this->getRequest()->get('fecha2');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $partes = explode("/", $fecha2);
                $fecha2 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $this->buscarAuditoriaEntreFechas($fecha1, $fecha2);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 2) {
                $clase = $this->getRequest()->get('clase');
                $fecha1 = $this->getRequest()->get('fecha1');
                $partes = explode("/", $fecha1);
                $fecha1 = new \DateTime($partes[2] . '/' . $partes[1] . '/' . $partes[0]);
                $array = $this->buscarAuditoriaFecha($fecha1, $clase);
                if ($array != null)
                    $total = array_pop($array);
            } elseif ($tipo == 3) {
                $mes = $this->getRequest()->get('mes');
                $año = $this->getRequest()->get('año');
                $array = $this->buscarAuditoriaMesAño($mes, $año);
                if ($array != null)
                    $total = array_pop($array);
            }
            $parameters = array('entries' => $array, 'form' => null, 'total' => $total);
            return $this->render('SisGGFinalBundle:Auditoria:cargarTabla.html.twig', $parameters);
        }
    }

    //BUSCAR

    /**
     * Busca auditorias de pedido desde el tiempo establecido, hasta el dia de hoy.
     *
     * @param int $tiempo La diferencia del tiempo menor o igual a la fecha actual.
     * @param char $tipo 'W' diferencia en semanas; 'z' diferencia en dias del año; 'n' diferncia en meses
     *
     * @return Array Devuelve un array con todas las coincidencias.
     */
    public function buscarAuditoria($tiempo, $tipo)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
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

        foreach ($lista as $value) {
            $otra = $value->getLoggedAt();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if ($otra >= $fecha && $otra <= $hoy)
                $array[] = $value;
        }
        return $array;
    }

    /**
     * Busca auditoria, desde una fecha determinada, hasta otra.
     *
     * @param DateTime $fecha1 Rango menor de la fecha.
     * @param DateTime $fecha2 Rango mayor de la fecha.
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     * @return False Error en estableciemiento de rangos de fecha.
     */
    public function buscarAuditoriaEntreFechas(\DateTime $fecha1, \DateTime $fecha2)
    {
        $array = null;
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
        if ($fecha1 > $fecha2)
            return false; //Error en estableciemiento de rangos de fecha
        foreach ($lista as $value) {
            $otra = $value->getLoggedAt();
            $otra = new \DateTime(date_format($otra, 'Y/m/d'));
            if (($otra >= $fecha1) && ($otra <= $fecha2))
                $array[] = $value;
        }
        return $array;
    }

    /**
     * Busca auditoria, de una fecha determinada o bien hasta una fecha determinada.
     *
     * @param DateTime $fecha1 Rango menor de la fecha.
     * @param int $tipo 0=Fecha exacta; 1= Hasta esa fecha; 2= Desde esa Hata hoy.
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarAuditoriaFecha(\DateTime $fecha1, $tipo)
    {
        $array = null;
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
        //falta sumar un dia
        if ($tipo == 0) {
            foreach ($lista as $value) {
                $otra = $value->getLoggedAt();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra == $fecha1))
                    $array[] = $value;
            }
            return $array;
        } elseif ($tipo == 1) {
            foreach ($lista as $value) {
                $otra = $value->getLoggedAt();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra <= $fecha1))
                    $array[] = $value;
            }
            return $array;
        } elseif ($tipo == 2) {
            $hoy = new \DateTime('now');
            foreach ($lista as $value) {
                $otra = $value->getLoggedAt();
                $otra = new \DateTime(date_format($otra, 'Y/m/d'));
                if (($otra >= $fecha1) && ($fecha1 <= $hoy))
                    $array[] = $value;
            }
            return $array;
        }
    }

    /**
     * Busca auditoria, de un mes determinado de un año (cuando el mes es mayor a cero) o solamente del año, cuando el mes es igual a cero.
     *
     * @param int $mes [1..12].
     * @param int $año [>0]
     *
     * @return Array Devuelve un array con todas las coincidencias.
     * @return Null No hubo coincidencias.
     */
    public function buscarAuditoriaMesAño($mes, $año)
    {
        $array = null;
        $em = $this->getDoctrine()->getEntityManager();
        $lista = $em->getRepository('Gedmo\Loggable\Entity\LogEntry')->findAll();
        //falta sumar un dia
        if ($mes > 0 && $mes <= 12 && $año > 0) {
            foreach ($lista as $value) {
                $otra = $value->getLoggedAt();
                $otraAño = date_format($otra, 'Y');
                $otraMes = date_format($otra, 'n');
                if (($otraAño == $año) && ($otraMes == $mes))
                    $array[] = $value;
            }
            return $array;
        } elseif ($mes == 0 && $año > 0) {
            foreach ($lista as $value) {
                $otra = $value->getLoggedAt();
                $otraAño = date_format($otra, 'Y');
                if (($otraAño == $año))
                    $array[] = $value;
            }
            return $array;
        }
        return $array;
    }

}