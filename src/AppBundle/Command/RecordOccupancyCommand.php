<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class RecordOccupancyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:RecordOccupancy')
            // the short description shown while running "php bin/console list"
            ->setDescription('Record the current occupancy of an event venue.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Record current occupany of event venue, requirs event id and venue id")
                
            ->setDefinition(
                new InputDefinition(array(
                    new InputOption('event', 'eId', InputOption::VALUE_REQUIRED,"The Event ID as a string","0"),
                    new InputOption('venue', 'vId', InputOption::VALUE_REQUIRED,"The Venue ID as a string","0"),
                ))
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Record Occupancy Symfony command',// A line
            '============',// Another line
            '',// Empty line
        ]);
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $active_events = $em->getRepository('AppBundle\Entity\event')->getActiveEvents();

        $output->write($active_events);
        // outputs a message without adding a "\n" at the end of the line
        $output->write("You've succesfully implemented your first command");
        
    }
}