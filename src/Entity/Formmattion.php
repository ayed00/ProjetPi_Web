<?php

namespace App\Entity;

use App\Repository\FormmattionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FormmattionRepository::class)
 */
class Formmattion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Champ nom est obligatoire")
     * @Assert\Length(
     *     min=5,
     *     max=50,
     *     minMessage="Le titre doit contenir au moins 5 carcatères ",
     *     maxMessage="Le titre doit contenir au plus 20 carcatères"
     * )
     *  @Groups("post:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)

     *  @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)

     *  @Groups("post:read")
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Le prix doît être différent de 0")
     *  @Groups("post:read")
     */
    private $prix;

    /**
     *
     * @ORM\Column(type="date")
     *  @Groups("post:read")
     */

    private $datede;

    /**
     *
     * @ORM\Column(type="date")
     *  @Groups("post:read")
     */
    private $datefi;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="formmattions")
     */
    private $User;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatede(): ?\DateTimeInterface
    {
        return $this->datede;
    }

    public function setDatede(\DateTimeInterface $datede): self
    {
        $this->datede = $datede;

        return $this;
    }

    public function getDatefi(): ?\DateTimeInterface
    {
        return $this->datefi;
    }

    public function setDatefi(\DateTimeInterface $datefi): self
    {
        $this->datefi = $datefi;

        return $this;
    }
    public function __toString()
    {
        return (string)$this->getNom();
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }



}