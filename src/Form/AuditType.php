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
use Symfony\Component\Form\FormInterface;

class AuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_heure_audit', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure',
                'data' => new \DateTime()
            ])
            ->add('zone', TextType::class, [
                'label' => 'Zone'
            ])
            ->add('auditeur', EntityType::class, [
                'class' => Auditeur::class,
                'choice_label' => function (Auditeur $auditeur) { return $auditeur->getNom() . ' ' . $auditeur->getPrenom(); },
                'label' => 'Auditeur'
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom_site',
                'label' => 'Site'
            ])
            ->add('verifications', CollectionType::class, [
                'entry_type' => VerificationType::class,
                'allow_add' => true,
                'by_reference' => false,
                'prototype' => true,
                'entry_options' => [
                    'validation_groups' => function(FormInterface $form) {
                        $verification = $form->getData();
                        return $verification && !$verification->isEstConforme() ? ['Default', 'verifier_commentaire', 'verifier_photo'] : ['Default'];
                    }
                ]
            ])
            ->add('score_conformite', NumberType::class, [
                'attr' => ['style' => 'display: none;'],
                'label' => false
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
