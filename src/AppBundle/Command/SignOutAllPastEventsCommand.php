<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use AppBundle\Entity\event_control_register;

class SignOutAllPastEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:SignOutAllPastEvents')
            // the short description shown while running "php bin/console list"
            ->setDescription('Automatically sign-out all people on the register who are still signed in on all past events')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Automatically sign-out all people on the register who are still signed in on all past events")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Automatically sign-out all people on the register who are still signed in on all past events',// A line
            '============',// Another line
            '',// Empty line
        ]);
        
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $past_events = $em->getRepository('AppBundle\Entity\event')->getFinishedEvents();
        
        foreach ($past_events as $past_event) {
            $event = $em->getRepository('AppBundle\Entity\event')->find($past_event['id']);

            $attendees = $em->getRepository('AppBundle\Entity\event_control_register')->findBy(array('time_out' => null, 'event' => $event));

            if ($attendees){
                $output->writeln(['Attendees auto signed out:', '']);
                foreach($attendees as $attendee){
                    $attendee->setTimeOut(new \DateTime());
                    $em->persist($attendee);
                    $em->flush();

                    $attendee_name = $attendee->getName();
                    $attendee_time_out = $attendee->getTimeOut();
                    $email_address = $attendee->getEmail();
                    $attendee_time_in = $attendee->getTimeIn();
                    $heading = "Event Control Automatic Site Sign-out";
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Automatic Control Room Sign-out - Please alert the control room if this in incorrect')
                        ->setFrom('event.control@nb221.com')
                        ->setTo($email_address)
                        ->setBody(
                            $this->getContainer()->get('templating')->render(
                                'emailFireRegisterOut.html.twig',
                                    array('heading' => $heading,
                                        'name' => $attendee_name,
                                        'time_out' => $attendee_time_out,
                                    )
                                ),
                            'text/html'
                            )
                    ;
                $this->getContainer()->get('mailer')->send($message);
                $output->writeln(['$attendee_name', '']);
                }
            }
        }
    }
}
