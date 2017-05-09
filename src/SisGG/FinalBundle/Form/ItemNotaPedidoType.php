<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemNotaPedidoType extends AbstractType
{
    private $productos;
    private $tipo;
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->tipo){
        $builder
            ->add('producto', 'entity' , array('preferred_choices'=>array($this->productos['productos'][0]),'attr'=>array('tipo'=>'buscar' ,'buscarClick'=>'buscarEnCombo','onchange'=>'cambioProducto(this)'),'class'=>"SisGG\FinalBundle\Entity\Producto", 'query_builder' => function (\SisGG\FinalBundle\Entity\ProductoRepository $repository) { return $repository->createQueryBuilder('p')->where("p.activo=1");} ))
            //->add('producto', 'choice',array( 'choices'=>  $this->pasarProducto() ,'attr'=>array('tipo'=>'buscar' ,'onchange'=>'cambioProducto(this)')))
            ->add('cantidad', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl','onkeyup' => 'cambiarCantidad (this)')))
            ->add('tasa')//, 'choice', array('choices'=>  $this->pasarTasa(), 'preferred_choices'=>array($this->productos['productos'][0]->getTasa()->getId())))
            ->add('precioCosto', 'number', array('attr' => array('class'=>'input-mini','dir'=>'rtl', 'onkeyup' => 'cambiarCosto(this)')))
            ->add('total', 'money', array('read_only'=>true))
        ;}
        else{
            $builder
            ->add('producto')
            //->add('producto', 'choice',array( 'choices'=>  $this->pasarProducto() ,'attr'=>array('tipo'=>'buscar' ,'onchange'=>'cambioProducto(this)')))
            ->add('cantidad')
            ->add('tasa')//, 'choice', array('choices'=>  $this->pasarTasa(), 'preferred_choices'=>array($this->productos['productos'][0]->getTasa()->getId())))
            ->add('precioCosto')
            ->add('total');
        }
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemNotaPedido'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_itemnotapedidotype';
    }
    public function getDefaultOptions(array $options) {
        return $options;
    }
    
    public function getProductos() {
        return $this->productos;
    }
    public function setProductos($productos) {
         $this->productos=$productos;
        return $this;
    }
    
    public function getTipo() {
        return $this->tipo;
    }
    public function setTipo($tipo) {
         $this->tipo=$tipo;
        return $this;
    }
    
    public function pasarProducto() {
        $a=null;
        $b=null;
        foreach ($this->productos['productos'] as $value) {
            $a[]=$value->getId();
            $b[]=$value->getNombre();
        }
        $array=array_combine($a, $b);
        return $array;
    }
    public function pasarTasa() {
        $a=null;
        $b=null;
        foreach ($this->productos['tasas'] as $value) {
            $a[]=$value->getId();
            $b[]=$value->__toString();
        }
        $array=array_combine($a, $b);
        return $array;
    }
    
}
