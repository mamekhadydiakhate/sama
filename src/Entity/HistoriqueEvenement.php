<?php

namespace App\Entity;

use App\Entity\Evenement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoriqueEvenementRepository;

/**
 * @ORM\Entity(repositoryClass=HistoriqueEvenementRepository::class)
 */
class HistoriqueEvenement
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
    private $id_utilisateur;

    /**
     * @ORM\OneToOne(targetEntity=Evenement::class, cascade={"persist", "remove"})
     */
    private $evenement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?string
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(string $id_utilisateur): self
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }
    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
