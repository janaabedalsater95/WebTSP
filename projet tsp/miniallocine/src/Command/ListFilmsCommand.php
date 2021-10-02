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

class ListFilmsCommand extends Command
{
    protected static $defaultName = 'app: list-films';
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
    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->setDescription('List films')
            ->addArgument('year', InputArgument::OPTIONAL, 'Filter films of a single year')
            ;
        
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
       // $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

       // if ($arg1) {
       //     $io->note(sprintf('You passed an argument: %s', $arg1));
       // }

        //if ($input->getOption('option1')) {
            // ...
        //}

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        //return 0;
     
        
        $year= $input ->getArgument('year');
        if(!$year)
        {
            $film=$this->filmRepository->findAll();
        }
        else{
            $films = $this->filmRepository->findBy(
                    ['year' => $year],
                    ['title' => 'ASC'])
                
            ;
        }
        
        if(!$films) {
            $output->writeln('<comment>no films found<comment>');
            exit(1);
        }
        
        foreach($films as $film)
        {
            $output->writeln($film);
        }

        return 0;
    }
}
