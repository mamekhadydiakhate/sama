<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Structure;
use Assert\NotBlank;
use App\Entity\Difficulte;
use App\Entity\Historique;
use App\Entity\TrancheHoraire;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\PointDeCoordination;
use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 * @UniqueEntity("libelle")
 */
class Activite
{
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"activite:read", "activite:show" ,"structure:read"  ,"difficulte:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"activite:read", "activite:show" ,"structure:read"  ,"difficulte:read"})
     */
    protected $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Structure::class, inversedBy="activite")
     * @Groups({"activite:read", "activite:show" ,"difficulte:read"})
     */
    private $structure;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="activites")
     * @Groups({"activite:read", "activite:show" ,"difficulte:read"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Difficulte::class, mappedBy="activite")
     */
    private $difficulte;

    /**
     * @ORM\ManyToOne(targetEntity=Historique::class, inversedBy="activite")
     */
    private $historique;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, mappedBy="activite")
     */
    private $evenements;

    /**
     * @ORM\Column(type="date")
     * @var string A "d-m-y" formatted value
     * @Groups({"activite:read", "activite:show"})
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=PointDeCoordination::class, mappedBy="activite")
     */
    private $pointDeCoordination;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $archives;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"activite:read", "activite:show" ,"difficulte:read"})
     */
    private $semaine;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->pointDeCoordination = new ArrayCollection();
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

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }


   

    /**
     * @return Collection|difficulte[]
     */
    public function getDifficulte(): Collection
    {
        return $this->difficulte;
    }

    public function addDifficulte(difficulte $difficulte): self
    {
        if (!$this->difficulte->contains($difficulte)) {
            $this->difficulte[] = $difficulte;
            $difficulte->setActivite($this);
        }

        return $this;
    }

    public function removeDifficulte(difficulte $difficulte): self
    {
        if ($this->difficulte->removeElement($difficulte)) {
            // set the owning side to null (unless already changed)
            if ($difficulte->getActivite() === $this) {
                $difficulte->setActivite(null);
            }
        }

        return $this;
    }


    public function getHistorique(): ?Historique
    {
        return $this->historique;
    }

    public function setHistorique(?Historique $historique): self
    {
        $this->historique = $historique;

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
            $evenement->addActivite($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeActivite($this);
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        $date->format('d/m/Y');

        return $this;
    }

    /**
     * @return Collection|PointDeCoordination[]
     */
    public function getPointDeCoordination(): Collection
    {
        return $this->pointDeCoordination;
    }

    public function addPointDeCoordination(PointDeCoordination $pointDeCoordination): self
    {
        if (!$this->pointDeCoordination->contains($pointDeCoordination)) {
            $this->pointDeCoordination[] = $pointDeCoordination;
            $pointDeCoordination->setActivite($this);
        }

        return $this;
    }

    public function removePointDeCoordination(PointDeCoordination $pointDeCoordination): self
    {
        if ($this->pointDeCoordination->removeElement($pointDeCoordination)) {
            // set the owning side to null (unless already changed)
            if ($pointDeCoordination->getActivite() === $this) {
                $pointDeCoordination->setActivite(null);
            }
        }

        return $this;
    }

    public function getArchives(): ?bool
    {
        return $this->archives;
    }

    public function setArchives(?bool $archives): self
    {
        $this->archives = $archives;

        return $this;
    }

    public function getSemaine(): ?int
    {
        return $this->semaine;
    }

    public function setSemaine(int $semaine): self
    {
        $this->semaine = $semaine;

        return $this;
    }


    
}
