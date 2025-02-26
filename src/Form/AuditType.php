<?php

namespace App\Form;

use App\Entity\Audit;
use App\Entity\Auditeur;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_heure_audit', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure'
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone'
            ])
            ->add('auditeur', EntityType::class, [
                'class' => Auditeur::class,
                'choice_label' => 'id',
                'label' => 'Auditeur'
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'id',
                'label' => 'Site'
            ])
            ->add('verifications', CollectionType::class, [
                'entry_type' => VerificationType::class,
                'allow_add' => true,
                'by_reference' => false,
                'label' => 'Vérifications',
                'prototype' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Audit::class,
        ]);
    }
}
