<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Crontask;
use App\Entity\TaskLog;
use App\Entity\QuenyLog;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CronDetails extends AbstractController
{
    /**
     * @Route("/showcron")
     */
    public function showCron(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $tasks_repository = $this->getDoctrine()->getRepository(Tasks::class);
        $tasks = $tasks_repository->findAll();
        $crontask_repository = $this->getDoctrine()->getRepository(Crontask::class);
        $crontask = $crontask_repository->findAll();
        $tasklog_repository = $this->getDoctrine()->getRepository(TaskLog::class);
        $tasklog = $tasklog_repository->findBy(array(),array('id'=>'DESC'),3,0);
        $quenylog_repository = $this->getDoctrine()->getRepository(QuenyLog::class);
        $quenylog = $quenylog_repository->findBy(array(),array('id'=>'DESC'),10,0);

        return $this->render('form/cronResult.html.twig',
            array('tasks' => $tasks, 'crontasks' => $crontask, 'task_log' => $tasklog, 'queny_log' => $quenylog));
    }
}