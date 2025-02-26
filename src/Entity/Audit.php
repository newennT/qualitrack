<?php

namespace App\Entity;

use App\Repository\AuditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditRepository::class)]
class Audit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_heure_audit = null;

    #[ORM\Column]
    private ?float $score_conformite = null;

    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    #[ORM\ManyToOne(inversedBy: 'audits')]
    private ?Auditeur $auditeur = null;

    #[ORM\ManyToOne(inversedBy: 'audits')]
    private ?Site $site = null;

    /**
     * @var Collection<int, Verification>
     */
    #[ORM\OneToMany(targetEntity: Verification::class, mappedBy: 'audit')]
    private Collection $verifications;

    public function __construct()
    {
        $this->verifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureAudit(): ?\DateTimeInterface
    {
        return $this->date_heure_audit;
    }

    public function setDateHeureAudit(\DateTimeInterface $date_heure_audit): static
    {
        $this->date_heure_audit = $date_heure_audit;

        return $this;
    }

    public function getScoreConformite(): ?float
    {
        return $this->score_conformite;
    }

    public function setScoreConformite(float $score_conformite): static
    {
        $this->score_conformite = $score_conformite;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getAuditeur(): ?Auditeur
    {
        return $this->auditeur;
    }

    public function setAuditeur(?Auditeur $auditeur): static
    {
        $this->auditeur = $auditeur;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): static
    {
        $this->site = $site;

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
            $verification->setAudit($this);
        }

        return $this;
    }

    public function removeVerification(Verification $verification): static
    {
        if ($this->verifications->removeElement($verification)) {
            // set the owning side to null (unless already changed)
            if ($verification->getAudit() === $this) {
                $verification->setAudit(null);
            }
        }

        return $this;
    }
}
