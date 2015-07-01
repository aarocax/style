<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchDateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', 'date', array(
                'label' => 'Fecha:',
                'required' => true,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_date';
    }
}
