<?php

namespace App\Service;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactService
{
    private $logger;
    private $entityManager;
    private $mailer;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function handleContactForm(array $data): array
    {
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;
        $email = $data['email'] ?? null;
        $message = $data['message'] ?? null;

        $this->logger->info('Contact form data', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'message' => $message,
        ]);

        // Enregistrer le contact en base de données
        $contact = new Contact();
        $contact->setName($nom);
        $contact->setSurname($prenom);
        $contact->setMail($email);
        $contact->setMessage($message);
        
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $summaryEmail = (new Email())
            ->from('jdegruson@goodshi.fr')
            ->to('goodshiteam@gmail.com')
            ->subject('Nouvelle demande de contact')
            ->text("Une nouvelle demande de contact a été reçue :\n\nNom : $nom\nPrénom : $prenom\nEmail : $email\nMessage : $message");

        $this->mailer->send($summaryEmail);

        return compact('nom', 'prenom', 'email', 'message');
    }
}
