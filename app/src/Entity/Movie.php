<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["serialization"])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    private string $name;

    #[ORM\Column]
    #[Groups(["serialization"])]
    private int $duration;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    private ?string $director = null;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    private ?string $synopsis = null;

    #[ORM\Column]
    #[Groups(["serialization"])]
    private ?int $score = null;

    #[ORM\Column]
    private int $visible = 1;

    public function __construct(
        string  $name,
        int     $duration,
        ?string $director,
        ?string $synopsis,
        ?int    $score,
        int     $visible
    )
    {
        $this->name = $name;
        $this->duration = $duration;
        $this->director = $director;
        $this->synopsis = $synopsis;
        $this->score = $score;
        $this->visible = $visible;
    }

    /**
     * @param object $movie
     * @return Movie
     */
    public function createFromObject(object $movie): Movie
    {
        return new self(
            $movie->name,
            $movie->duration,
            $movie->director,
            $movie->synopsis,
            $movie->score,
            $movie->visible
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getVisible(): ?int
    {
        return $this->visible;
    }

    public function setVisible(?int $visible): void
    {
        $this->visible = $visible;
    }

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(?string $director): void
    {
        $this->director = $director;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): void
    {
        $this->synopsis = $synopsis;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): void
    {
        $this->score = $score;
    }
}
