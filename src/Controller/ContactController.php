<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request): Response
    {
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
