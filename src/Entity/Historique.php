<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Activite;
use App\Entity\Evenement;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HistoriqueRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=HistoriqueRepository::class)
 */
class Historique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addresseIp;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="historiques")
     */
    private $user;

     /**
     * @ORM\OneToMany(targetEntity=activite::class, mappedBy="historique")
     */
    private $activite;

    /**
     * @ORM\OneToMany(targetEntity=evenement::class, mappedBy="historique")
     */
    private $evenement;

    public function __construct()
    {
        $this->date=new \DateTime("now");
        $this->evenement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAddresseIp(): ?string
    {
        return $this->addresseIp;
    }

    public function setAddresseIp(?string $addresseIp): self
    {
        $this->addresseIp = $addresseIp;

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

    /**
     * @return Collection|evenement[]
     */
    public function getEvenement(): Collection
    {
        return $this->evenement;
    }

    public function addEvenement(evenement $evenement): self
    {
        if (!$this->evenement->contains($evenement)) {
            $this->evenement[] = $evenement;
            $evenement->setHistorique($this);
        }

        return $this;
    }

    public function removeEvenement(evenement $evenement): self
    {
        if ($this->evenement->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getHistorique() === $this) {
                $evenement->setHistorique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|activite[]
     */
    public function getActivite(): Collection
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activite->contains($activite)) {
            $this->activite[] = $activite;
            $activite->setHistorique($this);
        }

        return $this;
    }

    public function removeActivite(activite $activite): self
    {
        if ($this->activite->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getHistorique() === $this) {
                $activite->setHistorique(null);
            }
        }

        return $this;
    }
}
