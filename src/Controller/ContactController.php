<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$logger = new Logger('channel-name');


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['POST', 'OPTIONS'])]
    public function contact(Request $request): Response
    {
        $logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::INFO));
        if ($request->isMethod('OPTIONS')) {
            return new Response('', 204);
        }

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $message = $request->request->get('message');

        return $this->json([
            'status' => 'success',
            'message' => 'Formulaire reçu avec succès',
            'data' => compact('nom', 'prenom', 'email', 'message')
        ]);
    }
}
