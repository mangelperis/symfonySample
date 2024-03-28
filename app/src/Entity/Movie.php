<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
#[ORM\Table(name: "movie")]
#[ORM\UniqueConstraint(name: "unique_name", columns: ["name"])]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["serialization"])]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\Column]
    #[Groups(["serialization"])]
    #[Assert\Type(type: 'integer')]
    private int $duration;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $director = null;

    #[ORM\Column(length: 255)]
    #[Groups(["serialization"])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $synopsis = null;

    #[ORM\Column]
    #[Groups(["serialization"])]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 0, max: 100)]
    private ?int $score = null;

    #[ORM\Column]
    #[Assert\Type(type: 'integer')]
    #[Assert\Range(min: 0, max: 1)]
    private int $visible = 1;

    public static function create(
        string  $name,
        int     $duration,
        ?string $director,
        ?string $synopsis,
        ?int    $score,
        int     $visible
    ): self
    {
        $movie = new self();
        $movie->name = $name;
        $movie->duration = $duration;
        $movie->director = $director;
        $movie->synopsis = $synopsis;
        $movie->score = $score;
        $movie->visible = $visible;
        return $movie;
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

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
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
