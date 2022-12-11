<?php

namespace App\Controller;

use App\Entity\Actor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{

    #[route('/{id}', methods: ['GET'], name: 'show')]
    public function show(Actor $actor): Response
    {

        if (!$actor) {
            throw $this->createNotFoundException(
                'No program with id : ' . $actor . ' found in program\'s table.'
            );
        }

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }
}
