<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\Livraison1Type;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livraisons = $entityManager
            ->getRepository(Livraison::class)
            ->findAll();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $this->addFlash('success', 'Livraison ajouté avec succès.');
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{idLivraison}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Livraison1Type::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{idLivraison}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getIdLivraison(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/telecharger_pdf/{idLivraison}', name: 'telecharger_pdf')]
    public function telechargerPdf(Request $request, $idLivraison): Response
    {
        // Récupérer le don depuis la base de données (ou tout autre moyen)
        $livraison= $this->getDoctrine()->getRepository(Livraison::class)->find($idLivraison);

        if (!$livraison) {
            throw $this->createNotFoundException('Livraison non trouvé');
        }

        // Créer un nouveau document PDF avec livpdf
        $dompdf = new Dompdf();
        $html = $this->renderView('livraison/pdf.html.twig', [
            'livraison' => $livraison,
            'date_telechargement' => new \DateTime(),
        ]);
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options du PDF
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $nomFichier = sprintf('livraison_%s.pdf', $livraison->getidlivraison());

        // Créer une réponse avec le contenu du PDF
        $response = new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $nomFichier)
            ]
        );

        // Envoyer la réponse pour télécharger le fichier PDF
        return $response;
    }
    #[Route('/search/livraison', name: 'search_livraison')]
    public function searchProducts(Request $request, LivraisonRepository $livraisonRepository): Response
    {
        /*$keyword = $request->query->get('keyword');

        // Recherche des produits correspondant au mot-clé
        $livraisons = $livraisonRepository->findByKeywordQuery($keyword);

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);*/
        $cin = $request->query->get('cin1');
        if ($cin) {
            $recherche_par = $request->query->get('recherche_par');
            switch ($recherche_par) {
                case 'statutLivraison':
                    $livraisons = $livraisonRepository->findByKeywordQuery($cin);
                    break;
                case 'adresse':
                    $livraisons = $livraisonRepository->findOneByadresse($cin);
                    break;
                default:
                    $livraisons = [];
            }
        } else {
            $livraisons = [];
        }
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);

    }
}
