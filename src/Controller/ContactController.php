<?php
namespace App\Controller;

use App\Service\ContactService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    private $logger;
    private $contactService;

    public function __construct(LoggerInterface $logger, ContactService $contactService)
    {
        $this->logger = $logger;
        $this->contactService = $contactService;
    }

    #[Route('/contact', name: 'contact', methods: ['POST', 'OPTIONS'])]
    public function contact(Request $request): Response
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

        $contactData = $this->contactService->handleContactForm($data);

        return $this->json([
            'status' => 'success',
            'message' => 'Formulaire reçu avec succès',
            'data' => $contactData
        ]);
    }
}
