<?php

namespace App\Entity;

use App\Entity\Personne;
use App\Entity\Structure;
use App\Entity\Evenement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AutoriteRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AutoriteRepository::class)
 * @UniqueEntity( 
 *     fields={"libelle"},
 *     message="La libelle doit être unique")
 */
class Autorite 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"autorite:read", "structure:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=evenement::class, inversedBy="autorites")
     * @Groups({"autorite:read"})
     */
    private $evenement;

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

    public function getEvenement(): ?evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
