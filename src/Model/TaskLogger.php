<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TaskLog;
use App\Entity\Crontask;
use App\Entity\ManualLog;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

class TaskLogger
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    //for update crontask table - with last data
    public function CronLogUpdate($taskName, $start, $end, $duration, $status)
    {
        $task = $this->em->getRepository(Crontask::class)->findOneBy(['name' => $taskName]);
        if ($task) {
            $task->setCronStart($start);
            $task->setCronEnd($end);
            $task->setCronDuration($duration);
            $task->setLastStatus($status);
            $this->em->persist($task);
            $this->em->flush();
            return true;
        }
        return false;
    }

    //for update task_log table - table with every task run data
    public function TaskLogAdd($taskCode, $start, $size, $end, $duration, $status,
                               $record_t, $records_p, $records_e = 0)
    {
        $TaskLog = new TaskLog();
        $TaskLog->setRecordsError($records_e);
        $TaskLog->setRecords($record_t);
        $TaskLog->setRecordsProcessed($records_p);
        $TaskLog->setRequestSize($size);
        $TaskLog->setStartTime($start);
        $TaskLog->setTaskCode($taskCode);
        $TaskLog->setEndTime($end);
        $TaskLog->setDuration($duration);
        $TaskLog->setStatus($status);
        $this->em->persist($TaskLog);
        $this->em->flush();
        return true;
    }

    public function ManualLog($request, $result, $answer, $body = '')
    {
        $manualLog = new ManualLog();
        $manualLog->setRequest($request);
        $manualLog->setResult($result);
        $manualLog->setAnswer($answer);
        $manualLog->setBody($body);
        $this->em->persist($manualLog);
        $this->em->flush();
        return true;
    }
}