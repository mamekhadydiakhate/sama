<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Interim;
use App\Entity\Workflow;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdminPPRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=AdminPPRepository::class)
 */
class AdminPP extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ORM\OneToMany(targetEntity=user::class, mappedBy="adminPP")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=workflow::class, mappedBy="adminPP")
     */
    private $workflow;

    /**
     * @ORM\OneToMany(targetEntity=interim::class, mappedBy="adminPP")
     */
    private $interim;

    public function __construct()
    {
        parent::__construct();
        $this->interim = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection|user[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(user $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setAdminPP($this);
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdminPP() === $this) {
                $user->setAdminPP(null);
            }
        }

        return $this;
    }


    public function addWorkflow(workflow $workflow): self
    {
        if (!$this->workflow->contains($workflow)) {
            $this->workflow[] = $workflow;
            $workflow->setAdminPP($this);
        }

        return $this;
    }

    public function removeWorkflow(workflow $workflow): self
    {
        if ($this->workflow->removeElement($workflow)) {
            // set the owning side to null (unless already changed)
            if ($workflow->getAdminPP() === $this) {
                $workflow->setAdminPP(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|interim[]
     */
    public function getInterim(): Collection
    {
        return $this->interim;
    }

    public function addInterim(interim $interim): self
    {
        if (!$this->interim->contains($interim)) {
            $this->interim[] = $interim;
            $interim->setAdminPP($this);
        }

        return $this;
    }

    public function removeInterim(interim $interim): self
    {
        if ($this->interim->removeElement($interim)) {
            // set the owning side to null (unless already changed)
            if ($interim->getAdminPP() === $this) {
                $interim->setAdminPP(null);
            }
        }

        return $this;
    }
}
