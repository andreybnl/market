<?php

namespace App\Model;

use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserLoger;
use App\Entity\TaskLog;
use App\Entity\Crontask;
use App\Entity\ManualLog;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;

class TaskLogger
{
    private static $entityManager;

//    private $em;
//    public function __construct(EntityManagerInterface $em)
//    {
//        $this->em = $em;
//    }

    /**
     * @param EntityManagerInterface $entityManager
     * @required
     */
    public function setEm(EntityManagerInterface $entityManager)
    {
        static::$entityManager = $entityManager;
    }

    //for update crontask table - with last data
    public function CronLogUpdate($taskName, $start, $end, $duration, $status)
    {
        $task = static::$entityManager->getRepository(Crontask::class)->findOneBy(['name' => $taskName]);
        if ($task) {
            $task->setCronStart($start);
            $task->setCronEnd($end);
            $task->setCronDuration($duration);
            $task->setLastStatus($status);
            static::$entityManager->persist($task);
            static::$entityManager->flush();
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
        static::$entityManager->persist($TaskLog);
        static::$entityManager->flush();
        return true;
    }

    public function ManualLog($request, $result, $answer, $body = '')
    {
        $manualLog = new ManualLog();
        $manualLog->setRequest($request);
        $manualLog->setResult($result);
        $manualLog->setAnswer($answer);
        $manualLog->setBody($body);
        static::$entityManager->persist($manualLog);
        static::$entityManager->flush();
        return true;
    }

    public function userLog($user, $activity, $result)
    {
        $userLog = new UserLoger();
        $userLog->setDateTime( new \DateTime('now'));
        $userLog->setUserLogin($user);
        $userLog->setActivity($activity);
        $userLog->setResult($result);
        static::$entityManager->persist($userLog);
        static::$entityManager->flush();
        return true;
    }
}