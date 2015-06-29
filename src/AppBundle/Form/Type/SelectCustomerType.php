<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SelectCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', 'autocomplete_entity', array(
                'class'        => 'AppBundle\Entity\Customer',
                'update_route' => 'customer_get',
                'label'        => 'Customer',
                'constraints'  => new NotNull(),
            ))
        ;
    }

    public function getName()
    {
        return 'select_customer';
    }
}