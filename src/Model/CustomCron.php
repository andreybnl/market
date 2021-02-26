<?php

namespace App\Model;

use App\Entity\Crontask;
use Cron\CronBundle\Entity\CronJob;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Response;

class CustomCron
{
    private $container;
    private $DM;

    public function __construct(Container $container, \Doctrine\ORM\EntityManagerInterface $DM)
    {
        $this->container = $container;
        $this->DM = $DM;
    }

    public function newCronTask(
        $name,
        $job_c,
        $schedule,
        $priority,
        $retry
    )
    {
        $job = new CronJob();
        $job->setName($name);
        $job->setCommand($job_c . ' ' . $retry);
        $job->setSchedule($schedule);
        $job->setDescription('cron job');
        $job->setEnabled(false);                        //change to true after smoke-test!
        $this->container->get('cron.manager')->saveJob($job);

        $Task = new Crontask();                     //internal table for statistic only!
        $Task->setName($name);
        $Task->setSchedule($schedule);
        $Task->setDetails($job_c);
        $Task->setType('Task');
        $Task->setPriority($priority);
        $Task->setRetry($retry);
        $this->DM->persist($Task);
        $this->DM->flush();
        return new Response('Job ' . $job_c . ' is created');
    }

    public function deleteCronTask($taskName)
    {
        global $kernel;
        $task = $this->DM->getRepository(Crontask::class)->findOneBy(['name' => $taskName]);
        $cronMAnager = $kernel->getContainer()->get('cron.manager');
        $job = $cronMAnager->getJobByName($taskName);
        $cronMAnager->deleteJob($job);
        $this->DM->remove($task);
        $this->DM->flush();
        return true;
    }


}