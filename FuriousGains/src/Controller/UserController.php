<?php

namespace App\Controller;
use App\Form\RechercheType;
use App\Form\UserType2;
use App\Service\UploderService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Dompdf\Dompdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
#[Route('/user')]

class UserController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry,
                                private UserRepository $userRepository,
                                private UploderService  $uploderService

    )
    {

    }
    #[Route('/delete/{iduser}', name: 'Delete'),IsGranted('ROLE_ADMIN')]
    public function Deleteuser(UserRepository $repo,$iduser,ManagerRegistry $manager){
        $obj=$repo->find($iduser);
        $em=$manager->getManager();
        $em->remove($obj);
        $em->flush();
        return $this->redirectToRoute('Admin');

    }
    #[Route('/user', name: 'app_user'),IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('Admin/test.html.twig', [
            'name' => 'UserController',
        ]);
    }
    #[Route('/{iduser}/edit', name: 'editt', methods: ['GET', 'POST'])]
    public function edit(Request $request,UserRepository $repo,$iduser, EntityManagerInterface $entityManager): Response
    { $user = $repo->findOneBy(['id_user' => $iduser]);
        $form = $this->createForm(UserType2::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('readUser', [
                'form' => $form->createView(),
                'User' => $user,
                ['iduser' => $iduser]
            ]);        }

        return $this->renderForm('user/aditAmin.html.twig', [
            'User' => $user,
            'form' => $form,
            ['iduser' => $iduser]
        ]);
    }
    #[Route('/readUser/{iduser}', name: 'readUser', methods: ['GET', 'POST'])]
    public function readUser(UserRepository $repo,EntityManagerInterface $manager, Request $request, $iduser): Response
    {
        $user = $repo->findOneBy(['id_user' => $iduser]);
        if (!$user) {
            throw $this->createNotFoundException('User not found'); // Handle the case when user is not found
        }
        $form=$this->createForm(UserType2::class,$user);
        $form->handleRequest($request);
        //5
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
           // return $this->redirectToRoute('readUser', ['iduser' => $iduser]);
            return $this->renderForm('user/AfficherUser.html.twig', [
                'form' => $form->createView(),
                'User' => $user,
                'iduser' => $iduser
            ]);

        }
        return $this->render('user/AfficherUser.html.twig', [
            'form' => $form->createView(),
            'User' => $user,
            ['iduser' => $iduser]
        ]);
    }
    #[Route('/homee', name: 'homee')]
    public function homeee(ManagerRegistry $manager,Request $request): Response
    { $user =new User();
        $form=$this->createForm(UserType::class,$user);
        $user->setBan(0);
        $user->setRole("Client");
        $user->setIdCodePromo(1);
        $form->handleRequest($request);
        //5

        if($form->isSubmitted() &&$form->isValid()){
            //6
            $imageFile = $form->get('image')->getData();

            // if($imageFile){
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $user->setImage($newFilename);

            $em=$manager->getManager();
            //7
            $passwordcrybt = password_hash($user->getPassword(), PASSWORD_BCRYPT);
            $user->setPassword($passwordcrybt);
            $em->persist($user);
            $em->flush();
            //8
            return $this->redirectToRoute('homee'); }
        return $this->renderForm('user/acceuil.html.twig',['formUser'=>$form]);

    }


    #[Route('/addUser', name: 'addUser')]
    public function addUser(ManagerRegistry $manager,Request $request): Response
    {   $user =new User();
        $form=$this->createForm(UserType::class,$user);
        $user->setBan(0);
        $user->setRole("Client");
        $user->setIdCodePromo(1);
        $form->handleRequest($request);
        //5

        if($form->isSubmitted() &&$form->isValid()){
            //6
            $imageFile = $form->get('image')->getData();

            // if($imageFile){
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images'),
                    $newFilename
                );
            } catch (FileException $e) {

            }
            $user->setImage($newFilename);

            $em=$manager->getManager();
        //7
            $passwordcrybt = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $user->setPassword($passwordcrybt);
        $em->persist($user);
        $em->flush();
        //8
       return $this->redirectToRoute('homee'); }
       return $this->renderForm('user/acceuil.html.twig',['formUser'=>$form]);
      
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder,ManagerRegistry $manager,Request $request,UserRepository $repo)
    {
        // Récupérez l'email et le mot de passe de l'utilisateur depuis le formulaire de connexion
        $email = $request->request->get('loginUsername');
        $password = $request->request->get('loginpass');

        // Récupérez l'utilisateur à partir de votre source de données (par exemple, la base de données)
        $user = $repo->findOneByEmail($email);

        if (!$user) {
            $this->session->getFlashBag()->add('error', 'L\'utilisateur n\'existe pas.');
            return $this->redirectToRoute('homee');
        }
        // Vérifiez le mot de passe en utilisant le PasswordEncoder
        if ($passwordEncoder->isPasswordValid($user, $password)) {
            if ($user->getBan()==0){
                if ($user->getRoles()=="Client")
            {
                return $this->redirectToRoute('readUser', [
                    'User' => $user,'iduser'=>$user->getCin()
                ]);

            }
            elseif ($user->getRoles()=="Admin"){
                return $this->redirectToRoute('Admin', [
                    'User' => $user,'iduser'=>$user->getCin()
                ]);
            }
            }
            else{
                $this->session->getFlashBag()->add('error', 'Votre compte est banée');
                return $this->redirectToRoute('homee');
            }

        } else {
            $this->session->getFlashBag()->add('error', 'L\'utilisateur n\'existe pas.');
            return $this->redirectToRoute('homee');

        }

    }

    #[Route('/ajout', name: 'app_user.Ajout'),IsGranted('ROLE_ADMIN')]
    public function AjouterUser(Request $request,UserPasswordHasherInterface $userPasswordHasher): Response
    {     $user = new User();
        $em = $this->managerRegistry->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('token');
        $user->setBan(0);
        $user->setIdCodePromo(1);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('image')->getData();
            $password = $form->get('password')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                ));
            if ($picture) {
                $newFileName = $this->uploderService->uploadFile($picture, $this->getParameter('images'));
                $user->setImage($newFileName);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"user ajouté avec succe");
            return $this->redirectToRoute("app_user.Afficher");
        }

        return $this->render('Admin/AddAmin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/afficherAdmin', name: 'app_user.Afficher'),IsGranted('ROLE_ADMIN')]
    public function AfficherUser(Request $request,UserRepository $userRepository): Response
    { $i=$userRepository->findAll();
        $client= $userRepository->findAll();
        //search
        $searchForm = $this->createForm(RechercheType::class);
        $searchForm->add("Recherche",SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $Email = $searchForm['Email']->getData();
            $resulta = $userRepository->searchNom($Email);
            return $this->render('Admin/Admin.html.twig', array(
                "Users" => $resulta,
                "searchUser" => $searchForm->createView()));
        }
        return $this->render('Admin/Admin.html.twig', array(
            "Users" => $client,
            "searchUser" => $searchForm->createView()));

    }
    #[Route('/telecharger_pdf/{id_user}', name: 'telecharger_pdf')]
    public function telechargerPdf(Request $request, int $id_user,UserRepository $userRepository): Response
    {
        // Récupérer le user depuis la base de usernées (ou tout autre moyen)
        $user = $userRepository->find($id_user);

        if (!$user) {
            throw $this->createNotFoundException('User non trouvé');
        }

        // Créer un nouveau document PDF avec Dompdf
        $dompdf = new Dompdf();
        $html = $this->renderView('user/pdf.html.twig', [
            'user' => $user,
            'date_telechargement' => new \DateTime(),
        ]);
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options du PDF
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $nomFichier = sprintf('Profil_%s.pdf', $user->getId_user());

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
    #[Route('/recherche', name: 'app_user.recherche', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function recherche(Request $request, UserRepository $userRepository): Response
    {
        $searchValue = $request->request->get('searchValue');
        // Effectuer la recherche en utilisant le UserRepository ou toute autre méthode appropriée
        $results = $userRepository->findBy(['nom' => $searchValue]);

        // Passer les résultats à votre template Twig pour les afficher
        return $this->render('Admin/Admin.html.twig', [
            'Users' => $results
        ]);

    }
    #[Route('/supprimerAdmin/{id}', name: 'app_user.Supprimer'),IsGranted('ROLE_ADMIN')]
    public function SupprimerUser(UserRepository $userRepository,$id,ManagerRegistry $managerRegistry): Response
    {$i=$userRepository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($i);
        $em->flush();
        return $this->redirectToRoute('app_user.Afficher');
    }
    #[Route('/modifierAdmin/{id}', name: 'app_user.Modifier'),IsGranted('ROLE_ADMIN')]
    public function modifierUser($id,Request $request )
    {$user=$this->userRepository->find($id);
        $form=$this->createForm(UserType::class,$user);
        $form->remove('token');
        $form->remove('image');
        $form->remove('password');
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em=$this->managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user.Afficher') ;
        }
        return $this->render('Admin/ModifierUser.html.twig',[
            'form'=>$form->createView()

        ]);}

}
