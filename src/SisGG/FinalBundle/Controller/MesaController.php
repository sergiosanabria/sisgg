<?php

namespace SisGG\FinalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of MesaController
 *
 * @author martin
 */
class MesaController extends Controller {

    public function mesasAction() {
        return new Response('Hello world!');
    }
    
}