<?php

namespace App\Entity;

use App\Repository\VerificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: VerificationRepository::class)]
class Verification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $est_conforme = true;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(
        message: "Le commentaire ne peut pas être vide lorsque l'audit n'est pas conforme.", 
        groups: ['verifier_commentaire']
    )]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'verifications')]
    private ?Operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'verifications')]
    private ?Audit $audit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(
        message: "La photo ne peut pas être vide lorsque l'audit n'est pas conforme.", 
        groups: ['verifier_photo']
    )]
    private ?string $photo = null;

    #[Assert\Callback]
    public function validateCommentaire(ExecutionContextInterface $context)
    {
        if ($this->isEstConforme() === false && empty(trim($this->commentaire))) {
            $context->buildViolation('Le commentaire est obligatoire si la vérification n\'est pas conforme.')
                ->atPath('commentaire')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validatePhoto(ExecutionContextInterface $context)
    {
        if ($this->isEstConforme() === false) {
            $context->buildViolation('La photo est obligatoire si la vérification n\'est pas conforme.')
                ->atPath('photo')
                ->addViolation();
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEstConforme(): ?bool
    {
        return $this->est_conforme ?? true;
    }

    public function setEstConforme(?bool $est_conforme): static
    {
        $this->est_conforme = $est_conforme ?? true;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getAudit(): ?Audit
    {
        return $this->audit;
    }

    public function setAudit(?Audit $audit): static
    {
        $this->audit = $audit;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
