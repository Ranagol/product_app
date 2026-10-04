<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final class EmailTestController extends AbstractController
{
    #[Route('/email/test', name: 'app_email_test')]
    public function index(MailerInterface $mailer): Response
    {
        // This is how we create an email message
        // $email = new Email();
        // $email->from('sender@example.com')
        //       ->to(new Address('recipient@example.com', 'Andor'))
        //       ->subject('Test Email')
        //       ->text('This is a test email.');

        // This is how we create an email message using a Twig template
        $email = new TemplatedEmail();
        $email->from('sender@example.com')
            ->to(new Address('recipient@example.com', 'Andor'))
            ->subject('Test Email')
            ->htmlTemplate('email_test/example.html.twig')
            ->context([
                'name' => 'Andor',
            ]);

        $mailer->send($email);

        return $this->render('email_test/index.html.twig', [
            'controller_name' => 'EmailTestController',
        ]);
    }
}
