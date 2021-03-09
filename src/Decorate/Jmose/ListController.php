<?php

namespace App\Decorate\Jmose;

use App\Model\TaskLogger;
use JMose\CommandSchedulerBundle\Controller\BaseController;
use JMose\CommandSchedulerBundle\Entity\ScheduledCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends BaseController
{
    /**
     * @var string
     */
    private $lockTimeout;

    /**
     * @param $lockTimeout string
     */
    public function setLockTimeout($lockTimeout)
    {
        $this->lockTimeout = $lockTimeout;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $scheduledCommands = $this->getDoctrine()->getManager()->getRepository(
            'JMoseCommandSchedulerBundle:ScheduledCommand'
        )->findAll();

        return $this->render(
            '@JMoseCommandScheduler/List/index.html.twig',
            ['scheduledCommands' => $scheduledCommands]
        );
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scheduledCommand = $entityManager->getRepository(ScheduledCommand::class)->find($id);

        $entityManager->remove($scheduledCommand);
        $entityManager->flush();

        // Add a flash message and do a redirect to the list
//        $this->get('session')->getFlashBag()
//            ->add('success'
//                //, $this->translator->trans('flash.deleted', [], 'JMoseCommandScheduler')
//        );
        //mur
        $taskLogger = new TaskLogger();
        $taskLogger->userLog($this->getUser()->getUsername(),' delete Cron ' . $scheduledCommand->getName(), 'sucess' );
        $entityManager->persist($scheduledCommand);
        return $this->redirect($this->generateUrl('jmose_command_scheduler_list'));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toggleAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scheduledCommand = $entityManager->getRepository(ScheduledCommand::class)->find($id);
        $scheduledCommand->setDisabled(!$scheduledCommand->isDisabled());
        $entityManager->flush();

        return $this->redirect($this->generateUrl('jmose_command_scheduler_list'));
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAction($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $scheduledCommand = $entityManager->getRepository(ScheduledCommand::class)->find($id);
        $scheduledCommand->setExecuteImmediately(true);
        $entityManager->flush();

        // Add a flash message and do a redirect to the list
        $this->get('session')->getFlashBag()
            ->add('success', $this->translator->trans('flash.execute', [], 'JMoseCommandScheduler'));

        if ($request->query->has('referer')) {
            return $this->redirect($request->getSchemeAndHttpHost().urldecode($request->query->get('referer')));
        }

        return $this->redirect($this->generateUrl('jmose_command_scheduler_list'));
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlockAction($id, Request $request)
    {
        $entityManager = $entityManager = $this->getDoctrine()->getManager();
        $scheduledCommand = $entityManager->getRepository(ScheduledCommand::class)->find($id);
        $scheduledCommand->setLocked(false);
        $entityManager->flush();

        // Add a flash message and do a redirect to the list
        $this->get('session')->getFlashBag()
            ->add('success', $this->translator->trans('flash.unlocked', [], 'JMoseCommandScheduler'));

        if ($request->query->has('referer')) {
            return $this->redirect($request->getSchemeAndHttpHost().urldecode($request->query->get('referer')));
        }

        return $this->redirect($this->generateUrl('jmose_command_scheduler_list'));
    }

    /**
     * method checks if there are jobs which are enabled but did not return 0 on last execution or are locked.<br>
     * if a match is found, HTTP status 417 is sent along with an array which contains name, return code and locked-state.
     * if no matches found, HTTP status 200 is sent with an empty array.
     *
     * @return JsonResponse
     */
    public function monitorAction()
    {
        $failedCommands = $this->getDoctrine()->getManager()
            ->getRepository(ScheduledCommand::class)
            ->findFailedAndTimeoutCommands($this->lockTimeout);

        $jsonArray = [];
        foreach ($failedCommands as $command) {
            $jsonArray[$command->getName()] = [
                'LAST_RETURN_CODE' => $command->getLastReturnCode(),
                'B_LOCKED' => $command->getLocked() ? 'true' : 'false',
                'DH_LAST_EXECUTION' => $command->getLastExecution(),
            ];
        }

        $response = new JsonResponse();
        $response->setContent(json_encode($jsonArray));
        $response->setStatusCode(count($jsonArray) > 0 ? Response::HTTP_EXPECTATION_FAILED : Response::HTTP_OK);

        return $response;
    }
}