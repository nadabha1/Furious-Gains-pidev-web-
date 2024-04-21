<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/evenement/{user_id}')]
class EvenementController extends AbstractController
{


    /////////////////////// Client Functions ///////////////
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository , int $user_id): Response
    {
        return $this->render('evenement/ClientView/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'user_id' => $user_id, 
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement, int $user_id): Response
    {
        return $this->render('evenement/ClientView/show.html.twig', [
            'evenement' => $evenement,
            'user_id' => $user_id, 

        ]);
    }
  /////////////////////////////// Admin Functions ////////////////
  #[Route('/Admin/index', name: 'admin_evenement_index', methods: ['GET'])]
  public function Adminindex(EvenementRepository $evenementRepository , int $user_id): Response
  {
      return $this->render('evenement/AdminView/adminIndex.html.twig', [
          'evenements' => $evenementRepository->findAll(),
          'user_id' => $user_id, 
      ]);
  }
  
  #[Route('/Admin/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager , int $user_id): Response
  {
      $evenement = new Evenement();
      $form = $this->createForm(EvenementType::class, $evenement);
      $form->handleRequest($request);
  
      if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->persist($evenement);
          $entityManager->flush();
  
          return $this->redirectToRoute('admin_evenement_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
      }
  
      return $this->render('evenement/AdminView/new.html.twig', [
          'evenement' => $evenement,
          'form' => $form->createView(),
          'user_id' => $user_id, 

      ]);
  }
  
  
  

    #[Route('/Admin/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, int $user_id): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_evenement_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/AdminView/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
            'user_id' => $user_id, 
        ]);
    }

    #[Route('/Admin/delete/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, int $user_id): Response
    {
        $reservations = $evenement->getReservations();
    
        try {
            $entityManager->beginTransaction();
    
            // Delete all associated reservations
            foreach ($reservations as $reservation) {
                $entityManager->remove($reservation);
            }
    
            // Now delete the event itself
            $entityManager->remove($evenement);
            $entityManager->flush();
    
            $entityManager->commit();
    
            return $this->redirectToRoute('admin_evenement_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        } catch (\Exception $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function filterEvenements(Request $request): Response
    {
        $searchText = $request->query->get('search');

        // Fetch events from the database or any other data source
        $repository = $this->getDoctrine()->getRepository(Evenement::class);
        $filteredEvenements = $repository->createQueryBuilder('e')
            ->andWhere('e.titre LIKE :searchText')
            ->setParameter('searchText', '%' . $searchText . '%')
            ->getQuery()
            ->getResult();

        // Render the filtered events as HTML (replace this with your logic)
        $html = $this->renderView('evenement/filtered_evenements.html.twig', [
            'filteredEvenements' => $filteredEvenements,
        ]);

        // Return JSON response with HTML content
        return $this->json(['html' => $html]);
    }
    

    public function generatePdf($id, $user_id, $eventId): Response
{
    // Fetch event and reservations data (you may need to adjust this based on your entity structure)
    $event = $this->getDoctrine()->getRepository(Evenement::class)->find($eventId);
    $reservations = $event->getReservations();

    // Load the PDF template (you'll create this next)
    $html = $this->renderView('evenement/AdminView/pdf.html.twig', [
        'event' => $event,
        'eventId' => $eventId,
        'reservations' => $reservations,
    ]);

    // Configure Dompdf options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    // Instantiate Dompdf
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // Set paper size and orientation (optional)
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Stream the generated PDF to the browser
    $pdfContent = $dompdf->output();
    
    // Set HTTP headers for PDF download
    $response = new Response($pdfContent);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="event_details.pdf"');

    return $response;
}
}
