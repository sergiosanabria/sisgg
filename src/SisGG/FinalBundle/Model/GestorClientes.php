<?php
namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Validator\Constraints\Cliente;
use SisGG\FinalBundle\Entity\Telefono;
use SisGG\FinalBundle\Entity\Direccion;
use \Exception;

/**
 * Description of GestorClientes
 *
 * @author martin
 */
class GestorClientes {
    /* @var $em \Doctrine\ORM\EntityManager */
    private $em = null;
    /* @var $gestor \SisGG\FinalBundle\Model\GestorMensajes */
    private $gestorMensajes = null;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    /**
     * @param integer $id
     * @return Cliente
     */
    public function getCliente(integer $id){
        $unCliente = $this->em->getRepository("SisGGFinalBundle:Cliente")->find($id);
        return $unCliente;
    }
    
    public function nuevoCliente(){
        throw new \InvalidArgumentException("El numero de telefono ingresado ya se encuentra registrado para otro cliente", null, null);
    }
    
}

?>
