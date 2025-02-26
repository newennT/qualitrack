<?php

namespace App\Entity;

use App\Repository\VerificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VerificationRepository::class)]
class Verification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $est_conforme = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'verifications')]
    private ?Operation $operation = null;

    #[ORM\ManyToOne(inversedBy: 'verifications')]
    private ?Audit $audit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEstConforme(): ?bool
    {
        return $this->est_conforme;
    }

    public function setEstConforme(bool $est_conforme): static
    {
        $this->est_conforme = $est_conforme;

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
}
