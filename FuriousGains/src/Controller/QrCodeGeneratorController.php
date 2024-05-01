<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Evenement;
use App\Entity\User;
use App\Form\SearchType;
use App\Repository\UserRepository;
use App\Service\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QrCodeGeneratorController extends AbstractController
{
    #[Route('/generateQr', name: 'indexq')]
    public function indexq(Request $request, QrcodeService $qrcodeService,EntityManagerInterface $em,UserRepository $userRepository): Response
    {
        $qrCode = null;
        $form = $this->createForm(SearchType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $name = $data['name'];
            $user=$userRepository->findOneBy(['nom' => $name]);

            $evenementid = $user->getId_user();
            $qrCode = $qrcodeService->qrcode($evenementid);



        }

        return $this->render('Admin/User/bidqr.html.twig', [
            'form' => $form->createView(),
            'qrCode' => $qrCode
        ]);
    }
}
