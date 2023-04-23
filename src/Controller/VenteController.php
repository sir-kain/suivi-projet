<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Form\VenteType;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vente')]
class VenteController extends AbstractController
{
    // #[Route('/', name: 'app_vente_index', methods: ['GET'])]
    // public function index(VenteRepository $venteRepository): Response
    // {
    //     return $this->render('vente/index.html.twig', [
    //         'ventes' => $venteRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_vente_new', methods: ['POST'])]
    public function new(Request $request, VenteRepository $venteRepository): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $venteRepository->save($vente, true);

            return $this->redirectToRoute('app_bande_show', ['id' => $vente->getBande()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vente/new.html.twig', [
            'vente' => $vente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vente_show', methods: ['GET'])]
    public function show(Vente $vente): Response
    {
        return $this->render('vente/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vente $vente, VenteRepository $venteRepository): Response
    {
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $venteRepository->save($vente, true);

            return $this->redirectToRoute('app_bande_show', ['id' => $vente->getBande()->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('vente/edit.html.twig', [
            'vente' => $vente,
            'formVente' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vente_delete', methods: ['POST'])]
    public function delete(Request $request, Vente $vente, VenteRepository $venteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vente->getId(), $request->request->get('_token'))) {
            $venteRepository->remove($vente, true);
        }
        
        return $this->redirectToRoute('app_bande_show', ['id' => $vente->getBande()->getId()], Response::HTTP_SEE_OTHER);
    }
}
