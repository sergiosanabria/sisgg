<?php

namespace SisGG\FinalBundle\Controller;

use SisGG\FinalBundle\Entity\Backup;
//use Ps\PdfBundle\Annotation\Pdf;
use SisGG\FinalBundle\Form\BackupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SisGG\FinalBundle\Entity\Empresa;
use \ZipArchive;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of ClienteController
 *
 * @author martin
 */
class BackupRestoreController extends Controller {

    public function backupAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::BACKUP_REGISTRAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $em = $this->getDoctrine()->getEntityManager();
        $hoy = new \DateTime('now');
        $backup = new Backup();
        /* @var $empresa Empresa */
        $empresa = $this->getEmpresa();
        if ($this->getRequest()->getMethod() === 'POST') {

            $backup->setCarpeta($empresa->getCarpeta());
            if (!is_dir($backup->getCarpeta())) {
                return $this->render('SisGGFinalBundle:BackupRestore:backup.html.twig', array('form' => null, 'empresa' => $empresa, 'error' => 'La carpeta que elegio no existe.', 'gestor_autenticacion' => $gestor));
            }
            $factory = $this->get('backup_restore.factory');
            $backupInstance = $factory->getBackupInstance('doctrine.dbal.default_connection');
            $archivo = $backupInstance->backupDatabase($backup->getCarpeta());
            $backup->setArchivo($archivo);
            $backup->setFecha($hoy);
            $backup->setUsuario($this->getUser());
            $em->persist($backup);
            $em->flush();

            //Generamos el zip
            $zip = new ZipArchive();
            $path = __DIR__ . '/../../../../web/temp/';
            $aux = explode('.', $backup->getArchivo());
            $filename = $path . $aux[0] . '.zip';

            if ($zip->open($filename, ZIPARCHIVE::CREATE) === true) {
                $zip->addFile($backup->getCarpeta() . $backup->getArchivo(), $backup->getArchivo());
                $zip->close();
            } else {
                $this->getRequest()->getSession()->set('errorAUD', 'Error al generar el archivo. ');
                return $this->redirect($this->generateUrl('auditorias'));
            }

            $this->getRequest()->getSession()->set('msjback', 'El archivo de respaldo se ha creado correctamente');
            return $this->redirect($this->generateUrl('backups'));
//            }
        }
        return $this->render('SisGGFinalBundle:BackupRestore:backup.html.twig', array('form' => null, 'error' => null, 'empresa' => $empresa));
    }

    public function descargarBackupAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::BACKUP_DESCARGAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $archivo = $this->getRequest()->get('arc');
        if ($archivo == null) {
            $this->getRequest()->getSession()->set('errorback', 'Error al seleccionar archivo back-up');
            return $this->redirect($this->generateUrl('backups'));
        }
        $aux = explode('.', $archivo);
        $archivo = $aux[0] . '.zip';

        $response = new Response();
        $path = __DIR__ . '/../../../../web/temp/';
        $filename = $path . $archivo;
        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename="backup.zip"');
        $response->headers->set('Content-length', filesize($filename));
        // Send headers before outputting anything
        $response->sendHeaders();
        $response->setContent(readfile($filename));
        return $response;
    }

    public function backupsAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::BACKUP_LISTAR)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        $msj = null;
        $msj = $this->getRequest()->getSession()->get('msjback');
        $this->getRequest()->getSession()->remove('msjback');
        $error = $this->getRequest()->getSession()->get('errorback');
        $this->getRequest()->getSession()->remove('errorback');
        $em = $this->getDoctrine()->getEntityManager();
        $registros = $em->getRepository('SisGGFinalBundle:Backup')->findAll();

        return $this->render('SisGGFinalBundle:BackupRestore:backups.html.twig', array('form' => null, 'error' => $error, 'registros' => $registros, 'msj' => $msj, 'gestor_autenticacion' => $gestor));
    }

    public function elegirCarpetaBackupAction() {
        $gestor = $this->get("gestor_autenticacion");
        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::BACKUP_ELEGIR_CARPETA)) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        /* @var $empresa Empresa */
        $empresa = $this->getEmpresa();
        if ($this->getRequest()->getMethod() === 'POST') {
            $carpeta = $this->getRequest()->get('carpeta');
            //$carpeta= stripos($carpeta, '/'); 
            if (!is_dir($carpeta)) {
                return $this->render('SisGGFinalBundle:BackupRestore:elegirCarpeta.html.twig', array('form' => null, 'empresa' => $empresa, 'error' => 'La carpeta que elegio no existe.'));
            }
            $empresa->setCarpeta($carpeta);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($empresa);
            $em->flush();
            return $this->redirect($this->generateUrl('parametros'));
        }
        return $this->render('SisGGFinalBundle:BackupRestore:elegirCarpeta.html.twig', array('form' => null, 'error' => null, 'empresa' => $empresa, 'msj' => null));
    }

    public function fileChooserAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $_POST['dir'] = urldecode($_POST['dir']);
            $root = '/';
            $files = null;
            if (file_exists($_POST['dir'])) {
                $files = scandir($_POST['dir']);
                natcasesort($files);
                if (count($files) > 1) { /* The 2 accounts for . and .. */
                    return $this->render('SisGGFinalBundle:BackupRestore:folder.html.twig', array('files' => $files, 'dir' => $_POST["dir"],));
                }
                return $this->render('SisGGFinalBundle:BackupRestore:folder.html.twig', array('files' => $files, 'dir' => $_POST["dir"],));
            }
        }
    }

    public function getEmpresa() {
        $em = $this->getDoctrine()->getEntityManager();
        $empresa = $em->getRepository('SisGGFinalBundle:Empresa')->find(1);
        return $empresa;
    }

//    /**
//     * @Pdf()
//     */
//    public function impListaBackupAction() {
//        $gestor = $this->get("gestor_autenticacion");
//        if (!$gestor->isGranted($this->getUser(), \SisGG\FinalBundle\Autenticacion\GestorAutenticacion::BACKUP_IMPRIMIR)) {
//            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
//        }
//        $lista = explode(",", $this->getRequest()->get('ids'));
//        $info = $this->getRequest()->get('info');
//        $array = null;
//        foreach ($lista as $id) {
//            if ($id != null)
//                $array[] = $this->getDoctrine()->getRepository('SisGGFinalBundle:Backup')->find($id);
//        }
//        $format = $this->get('request')->get('_format');
//        $parameters = array('form' => null, 'items' => $array, 'info' => $info, 'empresa' => $this->getEmpresa());
//        return $this->render('SisGGFinalBundle:BackupRestore:imp_lista.' . $format . '.twig', $parameters);
//    }

}