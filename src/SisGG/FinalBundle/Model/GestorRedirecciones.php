<?php

namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\Redireccion;
use SisGG\FinalBundle\Entity\Usuario;

/**
 * Description of GestorRedirecciones
 *
 * @author martin
 */
class GestorRedirecciones {
    
    /* @var $em \Doctrine\ORM\EntityManager */
    private $em = null;
    
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    
    public function nuevaRedireccion(Usuario $usuarioDe,$url=null,$parametros = null){
        $unaRedireccion = new Redireccion();
        $unaRedireccion->setUsuarioA($usuarioDe);
        $unaRedireccion->setUsuarioDe($usuarioDe);
        $unaRedireccion->setUrl($url);
        $unaRedireccion->setParametros($parametros);
        return $unaRedireccion;
    }
    
    
    /**
     * @return EntityManager
     */
    public function getEntityManager(){
        return $this->em;
    }
    
    public function obtenerRedirecciones(Usuario $unUsuario){
        $redirecciones = $this->getEntityManager()->getRepository("SisGGFinalBundle:Redireccion")->findRedirecciones($unUsuario);
        $retorno = array();
        /* @var $r Redireccion*/
        foreach ($redirecciones as $r) {
            $retorno[] = array("url"=>$r->getUrl(),"usuario"=>$r->getUsuarioDe()->getApellidoYNombre().''); 
            $r->setVisto(true);
        }
        $this->getEntityManager()->flush();
        return $retorno;
    }
    
}

?>
