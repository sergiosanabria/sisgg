<?php
    namespace SisGG\FinalBundle\Model;
    
    use Doctrine\ORM\EntityManager;
    use SisGG\FinalBundle\Entity\Mensaje;
    use SisGG\FinalBundle\Entity\Usuario;
    
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author martin
 */
class GestorMensajes {
    
    var $em = null;
    
    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    
    public function addMensaje($txt,  Usuario $usuario ,$tipo) {
        $mensaje = new Mensaje();
        $mensaje->setMensaje($txt);
        $mensaje->setLeido(false);
        $mensaje->setUsuario($usuario);
        $mensaje->setTipo($tipo);
        $mensaje->setNuevo(1);
        $this->em->persist($mensaje);
        $this->em->flush();
                
    }
}

?>
