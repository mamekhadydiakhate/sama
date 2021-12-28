<?php

namespace App\Entity;

use Assert\Date;
use App\Entity\Evenement;
use App\Entity\Structure;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PeriodiciteRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraint;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PeriodiciteRepository::class)
 */
class Periodicite 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * @var string A "d-m-y " formatted value
     * @Groups({"evenement:read","structure:read" ,"evenement:detail", "periodicite:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date
     * @var string A "d-m-y " formatted value
     * @Groups({"evenement:read" ,"structure:read" ,"evenement:detail", "periodicite:read"})
     */
    private $dateFin;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="periodicite")
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setPeriodicite($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getPeriodicite() === $this) {
                $evenement->setPeriodicite(null);
            }
        }

        return $this;
    }

}
