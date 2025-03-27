<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private ?string $support = null;

    #[ORM\Column]
    private ?bool $est_actif = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    private ?string $critere = null;

    /**
     * @var Collection<int, Verification>
     */
    #[ORM\OneToMany(targetEntity: Verification::class, mappedBy: 'operation')]
    private Collection $verifications;

    public function __construct()
    {
        $this->verifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(string $support): static
    {
        $this->support = $support;

        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->est_actif;
    }

    public function setEstActif(bool $est_actif): static
    {
        $this->est_actif = $est_actif ?? true;

        return $this;
    }

    public function getCritere(): ?string
    {
        return $this->critere;
    }

    public function setCritere(string $critere): static
    {
        $this->critere = $critere;

        return $this;
    }

    /**
     * @return Collection<int, Verification>
     */
    public function getVerifications(): Collection
    {
        return $this->verifications;
    }

    public function addVerification(Verification $verification): static
    {
        if (!$this->verifications->contains($verification)) {
            $this->verifications->add($verification);
            $verification->setOperation($this);
        }

        return $this;
    }

    public function removeVerification(Verification $verification): static
    {
        if ($this->verifications->removeElement($verification)) {
            // set the owning side to null (unless already changed)
            if ($verification->getOperation() === $this) {
                $verification->setOperation(null);
            }
        }

        return $this;
    }
}
