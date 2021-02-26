<?php


namespace App\Controller;

use App\Entity\Tasks;
use App\Entity\Crontask;
use App\Entity\TaskLog;
use App\Entity\QuenyLog;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CronDetailsAlt extends AbstractController
{
    /**
     * @Route("/showcronalt", name="showcronalt")
     */
    public function showCron(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $tasklog_repository = $this->getDoctrine()->getRepository(TaskLog::class);
        $tasklog = $tasklog_repository->findBy(array(),array('id'=>'DESC'),3,0);
        $quenylog_repository = $this->getDoctrine()->getRepository(QuenyLog::class);
        $quenylog = $quenylog_repository->findBy(array(),array('id'=>'DESC'),10,0);

        return $this->render('alt/cronResult_alt.html.twig',
            array('task_log' => $tasklog, 'queny_log' => $quenylog));
    }
}