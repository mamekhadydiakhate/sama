<?php
namespace App\Entity;

use App\Entity\Profil;
use App\Entity\Workflow;
use App\Entity\Structure;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Ldap\Ldap;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Controller\Annotations\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="utilisateur")
 * Post(Role("ROLE_PP"))
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="matricule", message="Le matricule {{ value }} existe deja. Veuillez en choisir un nouveau")
 * @UniqueEntity(fields="email", message="Le Email {{ value }} existe deja. Veuillez en choisir un nouveau")
 * @UniqueEntity(fields="username", message="Le username {{ value }} existe deja. Veuillez en choisir un nouveau")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @var string The hashed password
     * 
     */
    protected $password;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loginTentative;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $loginTentativeAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:show" ,"structure:read" ,"activite:read" ,"activite:show" ,"evenement:read" ,"difficulte:read" ,"evenement:detail"})
     */
    private $nom;

    protected $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:show"  ,"structure:read" ,"activite:read","activite:show" ,"evenement:read" ,"evenement:detail" ,"difficulte:read"})
     * 
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read" , "user:show" ,"structure:read" ,"activite:show", "activite:read" ,"evenement:detail"})
     */
    private $matricule;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity=profil::class, inversedBy="users")
     * @Groups({"user:read"})
     */
    private $profil;

    /**
     * @ORM\ManyToOne(targetEntity=workflow::class, inversedBy="users")
     */
    private $workflow;

    /**
     * @ORM\OneToMany(targetEntity=Activite::class, mappedBy="user")
     */
    private $activites;

    /**
     * @ORM\ManyToOne(targetEntity=AdminPP::class, inversedBy="user")
     */
    private $adminPP;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="user")
     */
    private $evenements;

    /**
     * @ORM\OneToOne(targetEntity=TypeService::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $typeService;

    /**
     * @ORM\ManyToOne(targetEntity=structure::class, inversedBy="users")
     *  @Groups({"user:show"})
     */
    private $structure;

    public function __construct()
    {
        parent::__construct();
        $this->activites = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the hashed password
     *
     * @param  string  $password  The hashed password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getLoginTentative(): ?int
    {
        return $this->loginTentative;
    }

    public function setLoginTentative(?int $loginTentative): self
    {
        $this->loginTentative = $loginTentative;

        return $this;
    }

    public function getLoginTentativeAt(): ?\DateTimeInterface
    {
        return $this->loginTentativeAt;
    }

    public function setLoginTentativeAt(?\DateTimeInterface $loginTentativeAt): self
    {
        $this->loginTentativeAt = $loginTentativeAt;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantir que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function setProfil(?profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getProfil(): ?profil
    {
        return $this->profil;
    }

    public function getWorkflow(): ?workflow
    {
        return $this->workflow;
    }

    public function setWorkflow(?workflow $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
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
            $activite->setUser($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getUser() === $this) {
                $activite->setUser(null);
            }
        }

        return $this;
    }

    public function getAdminPP(): ?AdminPP
    {
        return $this->adminPP;
    }

    public function setAdminPP(?AdminPP $adminPP): self
    {
        $this->adminPP = $adminPP;

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
            $evenement->setUser($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getUser() === $this) {
                $evenement->setUser(null);
            }
        }

        return $this;
    }

    public function getTypeService(): ?TypeService
    {
        return $this->typeService;
    }

    public function setTypeService(?TypeService $typeService): self
    {
        $this->typeService = $typeService;

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

   

  
}
