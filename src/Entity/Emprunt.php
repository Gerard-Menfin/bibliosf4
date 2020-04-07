<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmpruntRepository")
 */
class Emprunt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_sortie;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_rendu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Livre", inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Abonne", inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $abonne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->date_sortie;
    }

    public function setDateSortie(\DateTimeInterface $date_sortie): self
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }

    public function getDateRendu(): ?\DateTimeInterface
    {
        return $this->date_rendu;
    }

    public function setDateRendu(?\DateTimeInterface $date_rendu): self
    {
        $this->date_rendu = $date_rendu;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAbonne(): ?Abonne
    {
        return $this->abonne;
    }

    public function setAbonne(?Abonne $abonne): self
    {
        $this->abonne = $abonne;

        return $this;
    }
}
