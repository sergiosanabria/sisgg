<?php

namespace SisGG\FinalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IngredienteType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['attr']['tipo'] == 1) {
            $builder
            ->add('cantidad', 'number')
            ->add('tasa')
            ->add('obligatorio', 'choice', array(  'choices' => array(true => 'Si', false => 'No')))
            //->add('productoProduccion', null, array('attr' => array('style' => "display: none;"), 'label' => 'Producto producción'))
            ->add('productoProduccion', 'entity', array('label'=>'Producto producción','class' => "SisGG\FinalBundle\Entity\ProductoProduccion",'required'=>true ,'empty_value'=>"Selecione un producto" ,'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->where("p.activo=1")->orderBy('p.nombre', 'asc');
                            }))
            ->add('preElaborado', null, array('attr' => array('style' => "display: none;")))
            ;
        } else {
            $builder
                    ->add('cantidad', 'number')
                    ->add('tasa')
                    ->add('obligatorio', 'choice', array('choices' => array(true => 'Si', false => 'No')))
                    //->add('productoProduccion', null, array('attr' => array('style' => "display: none;"), 'label' => 'Producto producción'))
                    ->add('productoProduccion', 'entity', array('label'=>'Producto producción','class' => "SisGG\FinalBundle\Entity\ProductoProduccion",'required'=>true ,'empty_value'=>"Selecione un producto" ,'query_builder' => function ($repository) {
                                return $repository->createQueryBuilder('p')->where("p.activo=1")->orderBy('p.nombre', 'asc');
                            }))
                    ->add('plato', null, array('attr' => array('style' => "display: none;")));
                    
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'SisGG\FinalBundle\Entity\Ingrediente'
        ));
    }

    public function getName() {
        return 'sisgg_finalbundle_ingredientetype';
    }

}
