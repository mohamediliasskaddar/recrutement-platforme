<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;
    #[ORM\Column(type: 'string', length: 255)]
    private string $password;
    #[ORM\Column(type: 'string', length: 50)]
    private string $role;
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $date_inscription = null;
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function getRole(): ?string
    {
        return $this->role;
    }
    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }
    public function setDateInscription(?\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;
        return $this;
    }
}

