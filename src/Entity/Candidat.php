<?php

namespace App\Entity;

use App\Repository\CandidatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatRepository::class)]
class Candidat{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\OneToOne(targetEntity: Users::class, cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Users $user;
    #[ORM\Column(type: 'string', length: 255)]
    private string $nomcomplet;
    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $dateNaissance;
    #[ORM\Column(type: 'string', length: 50)]
    private string $cnie;
    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $sexe;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ville;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $cvPath;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagePersonnelle;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $competences;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $experiences;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $formations;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcomplet(): ?string
    {
        return $this->nomcomplet;
    }

    public function setNomcomplet(string $nomcomplet): static
    {
        $this->nomcomplet = $nomcomplet;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getCnie(): ?string
    {
        return $this->cnie;
    }

    public function setCnie(string $cnie): static
    {
        $this->cnie = $cnie;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCvPath(): ?string
    {
        return $this->cvPath;
    }

    public function setCvPath(?string $cvPath): static
    {
        $this->cvPath = $cvPath;

        return $this;
    }

    public function getImagePersonnelle(): ?string
    {
        return $this->imagePersonnelle;
    }

    public function setImagePersonnelle(?string $imagePersonnelle): static
    {
        $this->imagePersonnelle = $imagePersonnelle;

        return $this;
    }

    public function getCompetences(): ?string
    {
        return $this->competences;
    }

    public function setCompetences(?string $competences): static
    {
        $this->competences = $competences;

        return $this;
    }

    public function getExperiences(): ?string
    {
        return $this->experiences;
    }

    public function setExperiences(?string $experiences): static
    {
        $this->experiences = $experiences;

        return $this;
    }

    public function getFormations(): ?string
    {
        return $this->formations;
    }

    public function setFormations(?string $formations): static
    {
        $this->formations = $formations;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(Users $user): static
    {
        $this->user = $user;

        return $this;
    }
}
