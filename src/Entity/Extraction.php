<?php

namespace App\Entity;

use App\Entity\Structure;
use App\Entity\TypePeriodicite;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ExtractionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ExtractionRepository::class)
 */
class Extraction
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
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeExtraction;

    /**
     * @ORM\OneToMany(targetEntity=Structure::class, mappedBy="extraction")
     */
    private $structure;

    /**
     * @ORM\ManyToOne(targetEntity=TypePeriodicite::class, inversedBy="extractions")
     */
    private $typePeriodicite;

    public function __construct()
    {
        $this->structure = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeExtraction(): ?string
    {
        return $this->typeExtraction;
    }

    public function setTypeExtraction(string $typeExtraction): self
    {
        $this->typeExtraction = $typeExtraction;

        return $this;
    }

    /**
     * @return Collection|structure[]
     */
    public function getStructure(): Collection
    {
        return $this->structure;
    }

    public function addStructure(structure $structure): self
    {
        if (!$this->structure->contains($structure)) {
            $this->structure[] = $structure;
            $structure->setExtraction($this);
        }

        return $this;
    }

    public function removeStructure(structure $structure): self
    {
        if ($this->structure->removeElement($structure)) {
            // set the owning side to null (unless already changed)
            if ($structure->getExtraction() === $this) {
                $structure->setExtraction(null);
            }
        }

        return $this;
    }

    public function getTypePeriodicite(): ?typePeriodicite
    {
        return $this->typePeriodicite;
    }

    public function setTypePeriodicite(?typePeriodicite $typePeriodicite): self
    {
        $this->typePeriodicite = $typePeriodicite;

        return $this;
    }
}
