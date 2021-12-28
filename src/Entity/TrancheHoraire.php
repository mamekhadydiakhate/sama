<?php

namespace App\Entity;

use App\Entity\Activite;
use App\Entity\Evenement;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\TrancheHoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TrancheHoraireRepository::class)
 */
class TrancheHoraire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"activite:read"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Activite::class, mappedBy="trancheHoraire")
     */
    private $activites;

    /**
     * @ORM\ManyToOne(targetEntity=evenement::class, inversedBy="trancheHoraires")
     */
    private $evenement;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setTrancheHoraire($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getTrancheHoraire() === $this) {
                $activite->setTrancheHoraire(null);
            }
        }

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
