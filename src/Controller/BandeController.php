<?php

namespace App\Controller;

use App\Entity\Bande;
use App\Entity\Depense;
use App\Entity\Vente;
use App\Form\BandeType;
use App\Form\DepenseType;
use App\Form\VenteType;
use App\Repository\BandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bande')]
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
            'formBande' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bande_show', methods: ['GET'])]
    public function show(Bande $bande, BandeRepository $bandeRepository): Response
    {
        $formBande = $this->createForm(BandeType::class, $bande, ['action' => $this->generateUrl('app_bande_edit', ['id' => $bande->getId()])]);
        $formDepense = $this->createForm(DepenseType::class, new Depense(), ['action' => $this->generateUrl('app_depense_new')]);
        $formVente = $this->createForm(VenteType::class, new Vente(), ['action' => $this->generateUrl('app_vente_new')]);

        return $this->render('bande/show.html.twig', [
            'bande' => $bande,
            'totalDepense' => $bandeRepository->totalDepense($bande),
            'totalVente' => $bandeRepository->totalVente($bande),
            'stock' => $bandeRepository->stock($bande),
            'bilan' => $bandeRepository->bilan($bande),
            'formBande' => $formBande,
            'formDepense' => $formDepense,
            'formVente' => $formVente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bande $bande, BandeRepository $bandeRepository): Response
    {
        $form = $this->createForm(BandeType::class, $bande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bandeRepository->save($bande, true);

            return $this->redirectToRoute('app_bande_show', ['id' => $bande->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bande/edit.html.twig', [
            'bande' => $bande,
            'formBande' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bande_delete', methods: ['POST'])]
    public function delete(Request $request, Bande $bande, BandeRepository $bandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $bande->getId(), $request->request->get('_token'))) {
            $bandeRepository->remove($bande, true);
        }

        return $this->redirectToRoute('app_bande_index', [], Response::HTTP_SEE_OTHER);
    }
}
