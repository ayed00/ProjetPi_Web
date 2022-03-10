<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Validator as fullnameAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"Username"}, message="There is already an account with this Username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("user")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     * message = "{{ value }} is not a valid email.")
     * @Groups("user")
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups("user")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     *  @Assert\Length(
     *      min = 4,
     *      max = 12,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Groups("user")
     */
    private $Username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Regex(
     *     pattern     = "/^[a-zA-Z]+ [a-zA-Z]+/",
     * )
     * @Groups("user")
     */
    private $fullname;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Groups("user")
     */
    private $naissance;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsBanned=false;

    /**
     * @ORM\OneToMany(targetEntity=Formmattion::class, mappedBy="User")
     */
    private $formmattions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Photo;

    /**
     * @ORM\OneToMany(targetEntity=Reclamation::class, mappedBy="User")
     */
    private $reclamations;

    /**
     * @ORM\OneToMany(targetEntity=EventsReservation::class, mappedBy="User")
     */
    private $eventsReservations;

    /**
     * @ORM\OneToMany(targetEntity=Calendar::class, mappedBy="User")
     */
    private $calendars;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="User")
     */
    private $avis;



    public function __construct()
    {
        $this->formmattions = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->eventsReservations = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }




    //protected $captchaCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->Username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }
    /*public function getCaptchaCode()
    {
        return $this->captchaCode;
    }*/

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function setUsername(string $Username): self
    {
        $this->Username = $Username;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getNaissance(): ?\DateTimeInterface
    {
        return $this->naissance;
    }

    public function setNaissance(\DateTimeInterface $naissance): self
    {
        $this->naissance = $naissance;

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->IsBanned;
    }

    public function setIsBanned(bool $IsBanned): self
    {
        $this->IsBanned = $IsBanned;

        return $this;
    }
    /*public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }*/
    public function __toString()
    {
       return $this->getUsername();
    }

    /**
     * @return Collection<int, Formmattion>
     */
    public function getFormmattions(): Collection
    {
        return $this->formmattions;
    }

    public function addFormmattion(Formmattion $formmattion): self
    {
        if (!$this->formmattions->contains($formmattion)) {
            $this->formmattions[] = $formmattion;
            $formmattion->setUser($this);
        }

        return $this;
    }

    public function removeFormmattion(Formmattion $formmattion): self
    {
        if ($this->formmattions->removeElement($formmattion)) {
            // set the owning side to null (unless already changed)
            if ($formmattion->getUser() === $this) {
                $formmattion->setUser(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(?string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations[] = $reclamation;
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): self
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventsReservation>
     */
    public function getEventsReservations(): Collection
    {
        return $this->eventsReservations;
    }

    public function addEventsReservation(EventsReservation $eventsReservation): self
    {
        if (!$this->eventsReservations->contains($eventsReservation)) {
            $this->eventsReservations[] = $eventsReservation;
            $eventsReservation->setUser($this);
        }

        return $this;
    }

    public function removeEventsReservation(EventsReservation $eventsReservation): self
    {
        if ($this->eventsReservations->removeElement($eventsReservation)) {
            // set the owning side to null (unless already changed)
            if ($eventsReservation->getUser() === $this) {
                $eventsReservation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Calendar>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): self
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars[] = $calendar;
            $calendar->setUser($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): self
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getUser() === $this) {
                $calendar->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }














}
