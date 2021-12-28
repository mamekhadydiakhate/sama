<?php

namespace App\Entity;

use App\Entity\Structure;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeServiceRepository;

/**
 * @ORM\Entity(repositoryClass=TypeServiceRepository::class)
 */
class TypeService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=structure::class, inversedBy="typeServices")
     */
    private $structure;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="typeService", cascade={"persist", "remove"})
     */
    private $user;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getStructure(): ?structure
    {
        return $this->structure;
    }

    public function setStructure(?structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

   
}
