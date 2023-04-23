<?php

namespace App\Controller;

use App\Entity\Bande;
use App\Form\BandeType;
use App\Repository\BandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class BandeController extends AbstractController
{
    #[Route('/', name: 'app_bande_index', methods: ['GET'])]
    public function index(BandeRepository $bandeRepository): Response
    {
        return $this->render('bande/index.html.twig', [
            'bandes' => $bandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BandeRepository $bandeRepository): Response
    {
        $bande = new Bande();
        $form = $this->createForm(BandeType::class, $bande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bandeRepository->save($bande, true);

            return $this->redirectToRoute('app_bande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bande/new.html.twig', [
            'bande' => $bande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bande_show', methods: ['GET'])]
    public function show(Bande $bande): Response
    {
        return $this->render('bande/show.html.twig', [
            'bande' => $bande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bande $bande, BandeRepository $bandeRepository): Response
    {
        $form = $this->createForm(BandeType::class, $bande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bandeRepository->save($bande, true);

            return $this->redirectToRoute('app_bande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bande/edit.html.twig', [
            'bande' => $bande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bande_delete', methods: ['POST'])]
    public function delete(Request $request, Bande $bande, BandeRepository $bandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bande->getId(), $request->request->get('_token'))) {
            $bandeRepository->remove($bande, true);
        }

        return $this->redirectToRoute('app_bande_index', [], Response::HTTP_SEE_OTHER);
    }
}
