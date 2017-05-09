<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemEspeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('producto', 'entity' , array('attr'=>array('tipo'=>'buscar' ,'buscarClick'=>'buscarEnCombo','onchange'=>'cambioProducto(this)'),'class'=>"SisGG\FinalBundle\Entity\Producto", 'query_builder' => function (\SisGG\FinalBundle\Entity\ProductoRepository $repository) { return $repository->createQueryBuilder('p')->where("p.activo=1");} ))
            //->add('producto', 'choice',array( 'choices'=>  $this->pasarProducto() ,'attr'=>array('tipo'=>'buscar' ,'onchange'=>'cambioProducto(this)')))
            ->add('cantidad', 'number',array('attr'=>array('class'=>'input-mini','onkeyup'=>'cambiarCantidad (this)') )) 
            ->add('tasa')//, 'choice', array('choices'=>  $this->pasarTasa(), 'preferred_choices'=>array($this->productos['productos'][0]->getTasa()->getId())))
            ->add('precioCosto', 'number', array('attr'=>array('disabled'=>true,'class'=>'input-mini','onkeyup'=>'cambiarCosto(this)')))//, 'value'=>$this->productos['productos'][0]->getPrecioCosto())))//, null, array('attr'=>array('value'=>$this->productos['tasas'])))            
            ->add('total', 'money', array('read_only'=>true,'attr'=>array('class'=>'input-mini')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\ItemEspecies'
        ));
    }

    public function getName()
    {
        return 'sisgg_finalbundle_itemespeciestype';
    }
}
