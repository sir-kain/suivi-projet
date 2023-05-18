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
    #[Route('/', name: 'app_depense_index', methods: ['GET'])]
    public function index(DepenseRepository $depenseRepository): Response
    {
        return $this->render('depense/index.html.twig', [
            'depenses' => $depenseRepository->findBy(['bande' => null]),
        ]);
    }

    #[Route('/new', name: 'app_depense_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepenseRepository $depenseRepository): Response
    {
        $isAjaxRequest = $request->query->get('ajax');
        $templateUrl = 'depense/new.html.twig';
        if ($isAjaxRequest) {
            $templateUrl = 'depense/_form.html.twig';
        }
        $depense = new Depense();
        $form = $this->createForm(DepenseType::class, $depense, ['action' => $this->generateUrl('app_depense_new')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depenseRepository->save($depense, true);

            if ($depense->getBande()) {
                return $this->redirectToRoute('app_bande_show', ['id' => $depense->getBande()->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_depense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($templateUrl, [
            'depense' => $depense,
            'formDepense' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_depense_show', methods: ['GET'])]
    public function show(Request $request, Depense $depense): Response
    {
        $isAjaxRequest = $request->query->get('ajax');
        $templateUrl = 'depense/show-hb.html.twig';
        if ($isAjaxRequest) {
            $templateUrl = 'depense/show.html.twig';
        }
        return $this->render($templateUrl, [
            'depense' => $depense,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_depense_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Depense $depense, DepenseRepository $depenseRepository): Response
    {
        $isAjaxRequest = $request->query->get('ajax');
        $formAction = $this->generateUrl('app_depense_edit', ['id' => $depense->getId()]);
        $templateUrl = 'depense/new.html.twig';
        if ($isAjaxRequest) {
            $formAction = $this->generateUrl('app_depense_edit', ['id' => $depense->getId(), 'ajax' => 'true']);
            $templateUrl = 'depense/_form.html.twig';
        }
        $form = $this->createForm(DepenseType::class, $depense, ['action' => $formAction]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depenseRepository->save($depense, true);

            if ($isAjaxRequest) {
                return $this->redirectToRoute('app_bande_show', ['id' => $depense->getBande()->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_depense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($templateUrl, [
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
