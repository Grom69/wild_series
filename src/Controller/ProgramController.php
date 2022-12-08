<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/{program}', methods: ['GET'], requirements: ['program' => '\d+'], name: 'show')]
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();

        // dd($seasons);

        return $this->render('program/show.html.twig', [
            'program' => $program, 'seasons' =>  $seasons
        ]);
    }

    #[Route(
        '/{program}/seasons/{season}',
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

        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' =>  $season,
            'episodes' => $episodes
        ]);
    }

    #[Route(
        '/{program}/season/{season}/episode/{episode}',
        requirements: ['program' => '\d+', 'season' => '\d+', 'episode' => '\d+'],
        name: 'episode_show'
    )]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' =>  $season,
            'episode' => $episode
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        // Create a new Category Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->save($program, true);

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }
}
