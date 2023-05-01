<?php

namespace App\Controller;

use App\Entity\Depense;
use App\Form\DepenseType;
use App\Repository\DepenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/depense')]
class DepenseController extends AbstractController
{
//    #[Route('/', name: 'app_depense_index', methods: ['GET'])]
//    public function index(DepenseRepository $depenseRepository): Response
//    {
//        return $this->render('depense/index.html.twig', [
//            'depenses' => $depenseRepository->findAll(),
//        ]);
//    }

    #[Route('/new', name: 'app_depense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepenseRepository $depenseRepository): Response
    {
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense, ['action' => $this->generateUrl('app_depense_new')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depenseRepository->save($depense, true);

            return $this->redirectToRoute('app_bande_show', ['id' => $depense->getBande()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('depense/_form.html.twig', [
            'depense' => $depense,
            'formDepense' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_depense_show', methods: ['GET'])]
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_depense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Depense $depense, DepenseRepository $depenseRepository): Response
    {
        $form = $this->createForm(DepenseType::class, $depense, ['action' => $this->generateUrl('app_depense_edit', ['id' => $depense->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depenseRepository->save($depense, true);

            return $this->redirectToRoute('app_bande_show', ['id' => $depense->getBande()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('depense/_form.html.twig', [
            'depense' => $depense,
            'formDepense' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_depense_delete', methods: ['POST'])]
    public function delete(Request $request, Depense $depense, DepenseRepository $depenseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $depense->getId(), $request->request->get('_token'))) {
            $depenseRepository->remove($depense, true);
        }

        return $this->redirectToRoute('app_bande_show', ['id' => $depense->getBande()->getId()], Response::HTTP_SEE_OTHER);
    }
}
