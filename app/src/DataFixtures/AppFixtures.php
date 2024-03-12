<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    //PHP rray contains information for 10 movies, including their names, durations, directors, synopses, and visibility status set to 1, using the short bracket format
    private array $movies = [
        ["name" => "The Godfather", "duration" => 175, "director" => "Francis Ford Coppola", "synopsis" => "The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.", "score" => 100, "visible" => 1],
        ["name" => "Pulp Fiction", "duration" => 154, "director" => "Quentin Tarantino", "synopsis" => "The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.", "score" => 94, "visible" => 1],
        ["name" => "The Shawshank Redemption", "duration" => 142, "director" => "Frank Darabont", "synopsis" => "Two imprisoned men bond over a number of years, finding solace and eventual redemption through acts of common decency.", "score" => 80, "visible" => 1],
        ["name" => "The Dark Knight", "duration" => 152, "director" => "Christopher Nolan", "synopsis" => "When the menace known as The Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.", "score" => 84, "visible" => 1],
        ["name" => "Schindler's List", "duration" => 195, "director" => "Steven Spielberg", "synopsis" => "In German-occupied Poland during World War II, Oskar Schindler gradually becomes concerned for his Jewish workforce after witnessing their persecution by the Nazis.", "score" => 94, "visible" => 1],
        ["name" => "Forrest Gump", "duration" => 142, "director" => "Robert Zemeckis", "synopsis" => "The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75, whose only desire is to be reunited with his childhood sweetheart.", "score" => 82, "visible" => 1],
        ["name" => "Inception", "duration" => 148, "director" => "Christopher Nolan", "synopsis" => "A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.", "score" => 74, "visible" => 1],
        ["name" => "The Matrix", "duration" => 136, "director" => "The Wachowskis", "synopsis" => "A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.", "score" => 73, "visible" => 1],
        ["name" => "Fight Club", "duration" => 139, "director" => "David Fincher", "synopsis" => "An insomniac office worker and a devil-may-care soapmaker form an underground fight club that evolves into something much, much more.", "score" => 66, "visible" => 1],
        ["name" => "The Lord of the Rings: The Return of the King", "duration" => 201, "director" => "Peter Jackson", "synopsis" => "Gandalf and Aragorn lead the World of Men against Sauron's army to draw his gaze from Frodo and Sam as they approach Mount Doom with the One Ring.", "score" => 94, "visible" => 1]
    ];


    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);

        // Convert array to JSON string
        $jsonString = \json_encode($this->movies);

        // Convert JSON string to object
        $moviesObject = json_decode($jsonString);

        foreach ($moviesObject as $movie) {
            $film = new Movie();
            $film->setName($movie->name);
            $film->setDuration($movie->duration);
            $film->setDirector($movie->director);
            $film->setSynopsis($movie->synopsis);
            $film->setScore($movie->score);
            $film->setVisible($movie->visible);
            $manager->persist($film);
        }

        $manager->flush();
    }
}
