<?php

namespace App\Command;

use App\Entity\Film;
use App\Entity\Recommendation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddRecommendationCommand extends Command
{
    protected static $defaultName = 'app:add-recommendation';
    protected static $defaultDescription = 'Add a short description for your command';
   
    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('title', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('year', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('recommendation', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       
            $title = $input->getArgument('title');
            $year = $input->getArgument('year');
            
            $recotext = $input->getArgument('recommendation');
            
            // chargement du film de cette recommendation 
            $recommendation = new Recommendation();
            $filmRepository=$this->em->getRepository(Film::class);
            
            $film = $filmRepository->findOneBy(
                ['year' => $year,
                    'title' => $title]);
                
                // crée une instance en mémoire
                //$recommendation = new Recommendation();
                $recommendation->setRecommendation($recotext);
                
                // ajout en mémoire dans la collection des recommendations de ce film
                $film->addRecommendation($recommendation);
                
                // marque l'instance de film comme "à sauvegarder" en base
                $this->em->persist($film);
                
                // génère les requêtes en base
                $this->em->flush();
                
                return 0;
                
        }
    }

