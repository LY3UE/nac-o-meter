<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CallsignSearch extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->setAction($options['action'])
            ->add('callsign',TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Įveskite šaukinį',
                    'class' => 'form-control mr-sm-2'
                ), 
            'label' => false))
            ->add('save', SubmitType::class, array(
                'label' => 'Pirmyn',
                'attr' => array(
                    'class' => 'btn btn-outline my-2 my-sm-0'
                )
            ))
        ;
    }
}