<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;


class ContactController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/contact', name: 'contact', methods: ['POST', 'OPTIONS'])]
    public function contact(Request $request, EntityManagerInterface $manager): Response
    {
        $this->logger->info('Received contact request');

        if ($request->isMethod('OPTIONS')) {
            $this->logger->info('OPTIONS request received');
            return new Response('', 204);
        }
        if ($request->headers->get('Content-Type') !== 'application/json') {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid Content-Type'
            ], 400);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'status' => 'error',
                'message' => 'Invalid JSON'
            ], 400);
        }

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

        $contact = new Contact();
        $contact->setName($nom);
        $contact->setSurname($prenom);
        $contact->setMail($email);
        $contact->setMessage($message);
        
        $manager->persist($contact);
        $manager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Formulaire reçu avec succès',
            'data' => compact('nom', 'prenom', 'email', 'message')
        ]);
    }
}
