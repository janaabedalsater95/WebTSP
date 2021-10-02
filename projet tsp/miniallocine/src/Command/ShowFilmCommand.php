<?php

namespace App\Command;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShowFilmCommand extends Command
{
    protected static $defaultName = 'app:show-film';
    protected static $defaultDescription = 'Add a short description for your command';
    /**
     * @var FilmRepository
     */
    private $filmRepository;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->filmRepository = $container->get('doctrine')->getManager()->getRepository(Film::class);
    }
    protected function configure()
    {
        $this
        ->setDescription('Show recommendations for a film')
        ->addArgument('title', InputArgument::REQUIRED, 'Title of the film (spaces must be quoted)')
        ->addArgument('year', InputArgument::REQUIRED, 'Year of the film')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $title = $input->getArgument('title');
        $year = $input->getArgument('year');
        
        if($year && ! preg_match( '/^\d+$/', $year ) ) {
            $output->writeln('<error>second argument must be integer</error>');
            exit(2);
        }
        
        $film = $this->filmRepository->findOneBy(
            ['year' => $year,
                'title' => $title]);
            if(!$film) {
                $output->writeln('unknown film: ' . $title . ' (' . $year .')');
                exit(1);
            }
            $output->writeln($film . ':');
            
            foreach($film->getRecommendations() as $recommendation) {
                $output->writeln('-'. $recommendation);
            }
    }
   
}
