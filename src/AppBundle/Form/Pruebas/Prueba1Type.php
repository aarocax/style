<?php

namespace AppBundle\Form\Pruebas;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Prueba1Type extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nombre:','required' => true))
            ->add('lastName', 'text', array('label' => 'Nombre:','required' => true))
            ->add('address','text', array('label' => 'Dirección:','required' => false))
            ->add('phone','text', array('label' => 'Teléfono:','required' => false))
            ->add('date', 'date', array(
                'label' => 'Fecha:',
                'required' => true,
                'format' => 'dd-MM-yyyy',
                'input'  => 'datetime',
                // 'widget' => 'text',
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_prueba1';
    }
}
