<?php

namespace App\Model;

use App\Entity\Crontask;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use Cron\CronBundle\Entity\CronJob;
use Doctrine\Persistence\ObjectManager;

class AlterCron
{
    private $DM;
    private $objectManager;
    public function __construct(
        \Doctrine\ORM\EntityManagerInterface $DM
       // ObjectManager $objectManager
    )
    {
        $this->DM = $DM;
     //   $this->objectManager = $objectManager;
    }

    //not used
    public function AddCron($name, $command, $arguments, $cronExpression, $logFile, $priority, $lastExecution,
                            $locked = false, $disabled = false, $executeNow = false, $lastReturnCode = null)
    {
        $scheduledCommand = new ScheduledCommand();
        $scheduledCommand
            ->setName($name)
            ->setCommand($command)
            ->setArguments($arguments)
            ->setCronExpression($cronExpression)
            ->setLogFile($logFile)
            ->setPriority($priority)
            ->setLastExecution($lastExecution)
            ->setLocked($locked)
            ->setDisabled($disabled)
            ->setLastReturnCode($lastReturnCode)
            ->setExecuteImmediately($executeNow);
        $this->DM->persist($scheduledCommand);
        $this->DM->flush();
    }

}