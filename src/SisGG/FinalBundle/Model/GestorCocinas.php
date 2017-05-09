<?php
namespace SisGG\FinalBundle\Model;

use Doctrine\ORM\EntityManager;
use SisGG\FinalBundle\Entity\Cocina;
use SisGG\FinalBundle\Entity\Tanda;
use SisGG\FinalBundle\Entity\Pedido;

/**
 * Gestion de Cocinas
 *
 * @author martin
 */
class GestorCocinas {
    /*@var $em EntityManager*/
    private $em = null;
    
    public function __construct(EntityManager $em) {
        $this->em=$em;
    }
    
    /**
     * @return Cocina
     */
    public function getCocina(){
        $cocinas = $this->em->getRepository("SisGGFinalBundle:Cocina")->findAll();
        $cocina = $cocinas[0];
        return $cocina;
    }
    
    public function getTandas(){
        $this->getCocina()->getTandas();
    }
}

?>
