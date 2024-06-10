<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/contact', name: 'contact', methods: ['POST', 'OPTIONS'])]
    public function contact(Request $request): Response
    {
        $this->logger->info('Received contact request');

        if ($request->isMethod('OPTIONS')) {
            $this->logger->info('OPTIONS request received');
            return new Response('', 204);
        }

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        $this->logger->info('Contact form data', [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'message' => $message,
        ]);

        return $this->json([
            'status' => 'success',
            'message' => 'Formulaire reçu avec succès',
            'data' => compact('nom', 'prenom', 'email', 'message')
        ]);
    }
}
