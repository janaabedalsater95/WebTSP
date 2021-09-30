<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use App\Entity\Film;

class AddFilmCommand extends Command
{
    protected static $defaultName = 'app:add-film';
    protected static $defaultDescription = 'Add a short description for your command';
    
    private $em;
    
    
    /**
     * @var EntityManager : gère les fonctions liées à la persistence
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->em = $container->get('doctrine')->getManager();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addArgument('arg2', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');
        $arg2 = $input->getArgument('arg2');
        $title = $arg1;
        $year = $arg2;
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }
        if ($arg2) {
            $io->note(sprintf('You passed an argument: %s', $arg2));
        }
        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $film = new Film();
        $film->setTitle($title);
        $film->setYear($year);
        
        // marque l'instance comme "à sauvegarder" en base
        $this->em->persist($film);
        
        // génère les requêtes en base
        $this->em->flush();
        
        

        return 0;
    }
    
}
