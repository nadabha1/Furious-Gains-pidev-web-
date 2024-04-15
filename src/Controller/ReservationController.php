<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Evenement;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/{user_id}')]
class ReservationController extends AbstractController
{

    //////////////////// Client Functions ///////////////////////
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, int $user_id): Response
    {
        $reservations = $reservationRepository->findBy(['id_client' => $user_id]);
    
        return $this->render('reservation/ClientView/index.html.twig', [
            'reservations' => $reservations,
            'user_id' => $user_id,
            
        ]);
    }
    


    #[Route('/new/{Event_id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $user_id, int $Event_id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($user_id); // Retrieve the User object
    
        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }
    
        $evenement = $entityManager->getRepository(Evenement::class)->find($Event_id); // Retrieve the Evenement object
    
        if (!$evenement) {
            throw $this->createNotFoundException('Evenement not found.');
        }
    
        $reservation = new Reservation();
        $reservation->setIdClient($user); // Set idClient with the User object
        $reservation->setEvenement($evenement); // Set the Evenement object
        $reservation->setStatusRes('Pending');
        $form = $this->createForm(ReservationType::class, $reservation);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reservation_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reservation/ClientView/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'user_id' => $user_id,
            'Event_id' => $Event_id,
        ]);
    }
    
    #[Route('/delete/{id}', name: 'app_reservation_delete', methods: ['POST'])]
public function delete(Request $request, Reservation $reservation, int $user_id): Response
{
    
    if ($this->isCsrfTokenValid('delete' . $reservation->getId_Res(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reservation);
        $entityManager->flush();
            return $this->redirectToRoute('app_reservation_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);

    }

}
#[Route('/update/{event_id}', name: 'app_reservation_update', methods: ['GET', 'POST'])]
public function update(Request $request, EntityManagerInterface $entityManager, int $user_id, int $event_id): Response
{
    $user = $entityManager->getRepository(User::class)->find($user_id); // Retrieve the User object
    
    if (!$user) {
        throw $this->createNotFoundException('User not found.');
    }

    $reservation = $entityManager->getRepository(Reservation::class)->findOneBy([
        'id_client' => $user_id,
        'evenement' => $event_id,
    ]); // Retrieve the Reservation object

    if (!$reservation) {
        throw $this->createNotFoundException('Reservation not found.');
    }

    $form = $this->createForm(ReservationType::class, $reservation);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Update only the number of participants
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservation/ClientView/edit.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
        'user_id' => $user_id,
        'event_id' => $event_id,
    ]);
}


   ////////////////////// Admin Function /////////////////

   

   #[Route('/Admin/{Event_id}/Reservations', name: 'app_evenement_show_reservations', methods: ['GET'])]
   public function ResAdmin(ReservationRepository $reservationRepository, int $user_id, int $Event_id): Response
   {
       // Fetch reservations related to the specific event ID
       $reservations = $reservationRepository->findBy(['evenement' => $Event_id]);
   
       return $this->render('reservation/AdminView/Reservation.html.twig', [
           'reservations' => $reservations,
           'user_id' => $user_id,
           'Event_id' => $Event_id,
       ]);
   }

   #[Route('/Admin/reservation/{id}/delete', name: 'admin_reservation_delete', methods: ['POST'])]
   public function deleteAdmin(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
   {
       if ($this->isCsrfTokenValid('delete'.$reservation->getId_Res(), $request->request->get('_token'))) {
           $entityManager->remove($reservation);
           $entityManager->flush();
       }
   
       // Get the Event ID and User ID from the reservation object
       $eventId = $reservation->getEvenement()->getId_event();
       $userId = $reservation->getIdClient()->getIdClient();
   
       return $this->redirectToRoute('app_evenement_show_reservations', [
           'user_id' => $userId,
           'Event_id' => $eventId,
       ], Response::HTTP_SEE_OTHER);
   }
   
#[Route('/Admin/reservation/{id}/approve', name: 'app_reservation_approve', methods: ['POST'])]
public function approveReservation(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, int $user_id): Response
{
    $reservation->setStatusRes('Approved');
    $entityManager->flush();

    return $this->redirectToRoute('app_evenement_show_reservations', ['Event_id' => $reservation->getEvenement()->getId_event(), 'user_id' => $user_id]);
}

#[Route('/Admin/reservation/{id}/decline', name: 'app_reservation_decline', methods: ['POST'])]
public function declineReservation(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, int $user_id): Response
{
    $reservation->setStatusRes('Declined');
    $entityManager->flush();

    return $this->redirectToRoute('app_evenement_show_reservations', ['Event_id' => $reservation->getEvenement()->getId_event(), 'user_id' => $user_id]);
}

}
