<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use App\Service\ProgramDuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs
        ]);
    }

    #[Route(
        '/{slug}/seasons/{season}',
        methods: ['GET'],
        requirements: ['program' => '\d+', 'season' => '\d+'],
        name: 'season_show'
    )]
    public function showSeason(Program $program, Season $season)
    {

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season . ' found in season\'s table.'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' =>  $season,
        ]);
    }

    #[Route(
        '/{slug}/season/{season}/episode/{episode}',
        requirements: ['program' => '\d+', 'season' => '\d+', 'episode' => '\d+'],
        name: 'episode_show'
    )]
    public function showEpisode(Request $request, Program $program, Season $season, Episode $episode, CommentRepository $commentRepository)
    {
        // Create a new Category Object
        $comment = new Comment();
        // Create the associated Form
        $form = $this->createForm(CommentType::class, $comment);
        // Get data from HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $commentRepository->save($comment, true);

            $this->addFlash('success', 'L\'avis a été ajouté avec succès');

            // Redirect to categories list
            return $this->renderForm('program/episode_show.html.twig', [
                'program' => $program,
                'season' =>  $season,
                'episode' => $episode,
                'form' => $form
            ]);
        }

        return $this->renderForm('program/episode_show.html.twig', [
            'program' => $program,
            'season' =>  $season,
            'episode' => $episode,
            'form' => $form
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        // Create a new Category Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $programRepository->save($program, true);

            $this->addFlash('success', 'La série a été ajoutée avec succès');

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', methods: ['GET'], requirements: ['program' => '\d+'], name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $programDuration->calculate($program),
        ]);
    }
}
