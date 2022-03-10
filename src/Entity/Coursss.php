<?php

namespace App\Entity;

use App\Repository\CoursssRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @ORM\Entity(repositoryClass=CoursssRepository::class)
 */
class Coursss
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Champ description est obligatoire")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Champ video est obligatoire")
     */
    private $video;

    /**
     * @ORM\Column(type="date")
     */
    private $dateajoutt;

    /**
     * @ORM\ManyToOne(targetEntity=Formmattion::class)
     */
    private $formations;

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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getDateajoutt(): ?\DateTimeInterface
    {
        return $this->dateajoutt;
    }

    public function setDateajoutt(\DateTimeInterface $dateajoutt): self
    {
        $this->dateajoutt = $dateajoutt;

        return $this;
    }

    public function getFormations(): ?Formmattion
    {
        return $this->formations;
    }

    public function setFormations(?Formmattion $formations): self
    {
        $this->formations = $formations;

        return $this;
    }
}
