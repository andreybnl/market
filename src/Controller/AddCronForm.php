<?php

namespace App\Controller;

use App\Entity\Crontask;
use App\Form\CreateQuenyAsJob;
use App\Form\CreateTask;
use App\Form\CronTaskRun;
use App\Form\Result;
use App\Form\TaskDelete;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Repository\SupplierRepository;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Model\CustomCron;


/**
 * Class AddCronForm
 * @package App\Controller
 */
class AddCronForm extends AbstractController  //extends CronCommand //only for getContainer //AbstractController
{
    protected $customCron;

    public function __construct(
        CustomCron $customCron
    )
    {
        $this->customCron = $customCron;
    }

    /**
     * @Route("/cronaddform")
     */
    public function newAction(Request $request, KernelInterface $kernel)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $createQuenyform = $this->createForm(CreateQuenyAsJob::class)->handleRequest($request);
        $createTaskform = $this->createForm(CreateTask::class)->handleRequest($request);
        $taskDeleteForm = $this->createForm(TaskDelete::class)->handleRequest($request);
        $form3 = $this->createForm(CronTaskRun::class)->handleRequest($request);
        $form4 = $this->createForm(Result::class)->handleRequest($request);

        if ($createQuenyform->isSubmitted() && $createQuenyform->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($entityManager->getRepository(Crontask::class)->findOneBy(['priority' => $createQuenyform['Priority']->getData()])) {
                $form4->get('result')->setData(date("H:i:s", time()) . ' Wrong Priority');
            }
            $query = $entityManager->getRepository(\App\Entity\MarketQuery::class)->findOneBy(['label' =>
                $createQuenyform['Query']->getData()->getLabel()]);
            try {
                $this->customCron->newCronTask($query->getLabel(),
                    'cron:custom_command ' . $query->getQuery() . ' ' . $query->getSupplier() . ' ' . $query->getRequestType(),
                    $createQuenyform['Schedule']->getData(),
                    $createQuenyform['Priority']->getData(),
                    $createQuenyform['Retry']->getData()
                );
            }
        catch(\Exception $e)
        {
            $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
        }}

        if ($createTaskform->isSubmitted() && $createTaskform->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($entityManager->getRepository(Crontask::class)->findOneBy(['priority' => $createTaskform['Priority']->getData()])) {
                $form4->get('result')->setData(date("H:i:s", time()) . ' Wrong Priority');
            }
            if ($createTaskform['task']->getData()) {
                $this->customCron->newCronTask($createTaskform['task']->getData()->getCode(), $createTaskform['task']->getData()->getCode(),
                    $createTaskform['Schedule']->getData(),
                    $createTaskform['Priority']->getData(), $createTaskform['Retry']->getData());
                $form4->get('result')->setData(date("H:i:s", time()) . ' CronJob adeed');
            } else {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        if ($taskDeleteForm->isSubmitted() && $taskDeleteForm->isValid()) {
            if ($taskDeleteForm['task_to_delete']->getData()) {
                $this->customCron->deleteCronTask($taskDeleteForm['task_to_delete']->getData()->getName());
                $form4->get('result')->setData(date("H:i:s", time()) . ' CronJob deleted');
            } else {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        if ($form3->isSubmitted() && $form3->isValid()) {
            if ($form3['task']->getData()) {
                $application = new Application($kernel);
                $application->setAutoExit(false);
                $input = new ArrayInput([
                    'command' => $form3['task']->getData()->getCode(),
                ]);
                if ($application->run($input, null) == 'sucess') {
                    $form4->get('result')->setData(date("H:i:s", time()) . ' Task finished');
                }
            } else {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        return $this->render('form/cronform.html.twig', array(
            'createQuenyform' => $createQuenyform->createView(),
            'form2' => $taskDeleteForm->createView(),
            'form3' => $form3->createView(),
            'form_t' => $createTaskform->createView(),
            'form4' => $form4->createView()));
    }
}