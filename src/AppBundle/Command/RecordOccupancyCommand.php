<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

use AppBundle\Entity\PeopleCounterLog;

class RecordOccupancyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:RecordOccupancy')
            // the short description shown while running "php bin/console list"
            ->setDescription('Record the current occupancy of active event venues.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Record current occupany of active events")
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
        
        $now = new \DateTime();
        
        foreach ($active_events as $event) {
            //$venues = $em->getRepository('AppBundle\Entity\venue')->getEventVenues($event['id']);
            $venue_event = $em->getRepository('AppBundle\Entity\venue')->getEventVenues($event['id']);
            foreach ($venue_event as $key => $value) {
                $venues[$key]['id'] = $value['venue_id']['id'];
                $venues[$key]['name'] = $value['venue_id']['name'];
                $venues[$key]['count'] = $em->getRepository('AppBundle\Entity\venue')->getvenuecount($value['venue_id']['id'], $value['event_id']['event_log_stop_date'], $value['doors']);
                $venues[$key]['running'] = $venues[$key]['count']['running_count_in'] - $venues[$key]['count']['running_count_out'];
                $venue = $em->getRepository('AppBundle\Entity\venue')->findOneBy(array('id' => $value['venue_id']['id']));
                
                $new_people_counter_log = new PeopleCounterLog();
                $new_people_counter_log->setTimestamp(new \DateTime());
                $new_people_counter_log->setVenue($venue);
                $new_people_counter_log->setEvent($event);
                $new_people_counter_log->setRunningIn($venues[$key]['count']['running_count_in']);
                $new_people_counter_log->setRunningOut($venues[$key]['count']['running_count_out']);
                
                $em->persist($new_people_counter_log);
                $em->flush();
            }
            $output->write(json_encode($venues));
           
        }

    }
}