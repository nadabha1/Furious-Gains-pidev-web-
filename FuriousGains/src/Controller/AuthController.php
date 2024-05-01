<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\UploderService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class AuthController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry,
                                private UserRepository $userRepository,
                                private UploderService  $uploderService)
    {

    }
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route(path: '/login_check', name: 'login_check')]
    public function loginCheck(Request $request,AuthenticationUtils $authenticationUtils,UserRepository $userRepository )
    {            $lastUsername = $authenticationUtils->getLastUsername();
        $user=$userRepository->findOneByEmail($lastUsername);
        if ($user instanceof User && $user->isBan()) {
            throw new DisabledException('Votre compte est bloqué. Veuillez contacter l\'administrateur.');
        }else{
        // Redirect user to a specific space based on some condition
        if ( $this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('app_user.Afficher');

        } else if($this->isGranted("ROLE_USER")) {
            // Handle other cases or provide a default redirect
            return $this->redirectToRoute('homee');
        }}

    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return  $this->redirectToRoute('app_login');
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,ManagerRegistry $manager): Response
    {  $user = new User();
        $em = $manager->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('token');
        $form->remove('roles');

        $user->setBan(0);
        $user->setIdCodePromo(1);
        $form->handleRequest($request);
        $user->setRoles(['ROLE_USER']);
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
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/iscriptions.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
