<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Recommendation;

class AppFixtures extends Fixture
{
    /**
     * Generates initialization data for films : [title, year]
     * @return \\Generator
     */
    private static function filmsDataGenerator()
    {
    yield ["Evil Dead", 1981];
    yield ["Evil Dead", 2013];
    yield ["Fanfan la Tulipe", 2003];
    yield ["Fanfan la Tulipe", 1952];
    yield ["Mary a tout prix", 1998];
    yield ["Black Sheep", 2008];
    yield ["Le Monde de Nemo", 2003];
    }

    /**
     * Generates initialization data for film recommendations:
     *  [film_title, film_year, recommendation]
     * @return \\Generator
     */
    private static function filmRecommendationsGenerator()
    {
    yield ["Evil Dead", 1981, "Ouh ! Mais ça fait peur !"];
    yield ["Evil Dead", 2013, "Même pas peur !"];
    yield ["Evil Dead", 2013, "Insipide et sans saveur"];
    yield ["Fanfan la Tulipe", 1952, "Manque de couleurs"];
    yield ["Fanfan la Tulipe", 1952, "Super scènes de combat"];
    yield ["Fanfan la Tulipe", 2003, "Mais pourquoi ???"];
    yield ["Mary a tout prix", 1998, "Le meilleur film de tous les temps"];
    yield ["Black Sheep", 2008, "Un scenario de génie"];
    yield ["Black Sheep", 2008, "Une réalisation parfaite"];
    yield ["Black Sheep", 2008, "À quand Black Goat ?"];  
    }

    public function load(ObjectManager $manager)
    {
    $filmRepo = $manager->getRepository(Film::class);

    foreach (self::filmsDataGenerator() as [$title, $year] ) {
        $film = new Film();
        $film->setTitle($title);
        $film->setYear($year);
        $manager->persist($film);           
    }
    $manager->flush();

    foreach (self::filmRecommendationsGenerator() as [$title, $year, $recommendation])
    {
        $film = $filmRepo->findOneBy(['title' => $title, 'year' => $year]);
        $reco = new Recommendation();
        $reco->setRecommendation($recommendation);
        $film->addRecommendation($reco);
        // there's a cascade persist on fim-recommendations which avoids persisting down the relation
        $manager->persist($film);
    }
    $manager->flush();
    }
}