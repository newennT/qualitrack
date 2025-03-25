<?php

namespace App\Form;

use App\Entity\Audit;
use App\Entity\Operation;
use App\Entity\Verification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class VerificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('est_conforme', ChoiceType::class, [
                'label' => 'Est conforme ?',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => 'Ignorer',
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'constraints' => [
                    new NotBlank(['groups' => ['verifier_commentaire']]),
                ],
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'label' => 'Photo',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image JPG ou PNG',
                    ]),
                    new NotBlank(['groups' => ['verifier_photo']])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Verification::class,
        ]);
    }
}
