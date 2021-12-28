<?php

namespace App\Entity;

use App\Entity\Extraction;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\TypePeriodiciteRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=TypePeriodiciteRepository::class)
 */
class TypePeriodicite
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
     * @ORM\OneToMany(targetEntity=Extraction::class, mappedBy="typePeriodicite")
     */
    private $extractions;

    public function __construct()
    {
        $this->extractions = new ArrayCollection();
    }

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

    /**
     * @return Collection|Extraction[]
     */
    public function getExtractions(): Collection
    {
        return $this->extractions;
    }

    public function addExtraction(Extraction $extraction): self
    {
        if (!$this->extractions->contains($extraction)) {
            $this->extractions[] = $extraction;
            $extraction->setTypePeriodicite($this);
        }

        return $this;
    }

    public function removeExtraction(Extraction $extraction): self
    {
        if ($this->extractions->removeElement($extraction)) {
            // set the owning side to null (unless already changed)
            if ($extraction->getTypePeriodicite() === $this) {
                $extraction->setTypePeriodicite(null);
            }
        }

        return $this;
    }
}
