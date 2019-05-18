<?php

// src/MessageHandler/SmsNotificationHandler.php
namespace App\MessageHandler;

use App\Entity\Users;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * @var \Twig_Environment
     */
    private $twig;


    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer,\Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
      
    public function __invoke(SmsNotification $SmsNotification)
    {
        $user= $SmsNotification->getUser();
        $message = (new \Swift_Message('Verify Your Email'))
        ->setFrom('test@example.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->twig->render(
                 'emails/registration.html.twig'
                ,
               array('user'=>$user)
            ),
            'text/html'
        )
        ->addPart(
            $this->twig->render(
                'emails/registration.txt.twig',
                array('user'=>$user)
                ),
            'text/plain'
            );        
     $this->mailer->send($message);

    }
}