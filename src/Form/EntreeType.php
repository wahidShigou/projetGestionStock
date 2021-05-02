<?php

namespace App\Form;

use App\Entity\Entree;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateE', DateType::class, array('label' => "Date d'entree", 'attr' => array('class' => 'form-control form-group')))
            ->add('qtE', TextType::class, array('label' => 'Quantite achetee', 'attr' => array('class' => 'form-control form-group')))
            ->add('produit', EntityType::class, array('class' => 'App\Entity\Produit', 'label' => 'Produit', 'attr' => array('class' => 'form-control form-group')))
            ->add('Valider', SubmitType::class, array('attr' => array('class' => 'btn btn-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entree::class,
        ]);
    }
}
