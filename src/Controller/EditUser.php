<?php

namespace App\Controller;

use App\Form\CreateUser;
use App\Form\DeleteUser;
use App\Form\EditUserForm;
use App\Form\Result;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EditUser extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/user_form")
     */
    public function users(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(EditUserForm::class, array('label' => 'Users'))->handleRequest($request);
        $form2 = $this->createForm(CreateUser::class)->handleRequest($request);
        $form3 = $this->createForm(DeleteUser::class)->handleRequest($request);
        $form4 = $this->createForm(Result::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form['user']->getData()) {
                $entityManager->getRepository(User::class)->upgradePassword($form['user']->getData(), $form['password']->getData());
                $form4->get('result')->setData(date("H:i:s", time()) . ' User updated');
            }
            else{
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        if ($form2->isSubmitted() && $form2->isValid()) {
            $firstUser = new User();
            $firstUser->setRoles(array('ROLE_USER'));
            $firstUser->setPassword($this->passwordEncoder->encodePassword(
                $firstUser, $form2['password']->getData()));
            $firstUser->setEmail($form2['email']->getData());
            $entityManager->persist($firstUser);
            $entityManager->flush();
            $form4->get('result')->setData(date("H:i:s", time()) . ' User created');
        }

        if ($form3->isSubmitted() && $form3->isValid()) {
            if($form3['user']->getData()) {
                $entityManager->remove($form3['user']->getData());
                $entityManager->flush();
                $form4->get('result')->setData(date("H:i:s", time()) . ' User deleted');
            }
            else {
                $form4->get('result')->setData(date("H:i:s", time()) . ' ERROR');
            }
        }

        return $this->render('form/userform.html.twig', array(
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'form4' => $form4->createView()));
    }
}