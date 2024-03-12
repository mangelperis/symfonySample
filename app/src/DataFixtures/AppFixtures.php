<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private array $movies = array(
        "The Godfather" => 175,
        "Pulp Fiction" => 154,
        "The Shawshank Redemption" => 142,
        "The Dark Knight" => 152,
        "Schindler's List" => 195,
        "Forrest Gump" => 142,
        "Inception" => 148,
        "The Matrix" => 136,
        "Fight Club" => 139,
        "The Lord of the Rings: The Return of the King" => 201
    );

    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);

        foreach ($this->movies as $name => $duration) {
            $film = new Movie();
            $film->setName($name);
            $film->setDuration($duration);
            $manager->persist($film);
        }

        $manager->flush();
    }
}
