<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;

class AuthController extends AbstractController
{

    /** Route GET - Restituire i template */

    #[Route('/login', name: 'auth.login')]
    public function login(): Response
    {
        return $this->render('page/auth/login.html.twig');
    }

    #[Route('/register', name: 'auth.register')]
    public function register(): Response
    {
        return $this->render('page/auth/register.html.twig');
    }

    #[Route('/logout', name: 'auth.logout')]
    public function logout(Request $request): RedirectResponse
    {
        $request->getSession()->invalidate();
        return $this->redirect($this->generateUrl("auth.login"));
    }

    /** Route POST - Submit dei form */

    #[Route('/login_submit', name: 'auth.login.submit', methods: ["post"])]
    public function login_submit(Request $request, EntityManagerInterface $entityManagerInterface): RedirectResponse
    {
        $payload = $request->getPayload();
        $username = $payload->get("username");
        $password = $payload->get("password");

        if ($username && $password) {
            $hash = hash("sha256", $password);
            $repository = $entityManagerInterface->getRepository(User::class);

            $user = $repository->findOneBy(["username" => $username, "password" => $hash]);

            if ($user) {
                $this->addFlash("success", "You successfully logged into your account!");

                /** @var User $user */
                $request->getSession()->set($_ENV["SESSION_COOKIE"], $user->getId());

                return $this->redirect($this->generateUrl("app"));
            }
        }

        $this->addFlash("danger", "Incorrect credentials. Please try again!");
        return $this->redirect($this->generateUrl("auth.login"));
    }

    #[Route('/register_submit', name: 'auth.register.submit', methods: ["post"])]
    public function register_submit(Request $request, EntityManagerInterface $entityManagerInterface): RedirectResponse
    {
        $payload = $request->getPayload();
        $name = $payload->get("name");
        $username = $payload->get("username");
        $password = $payload->get("password");

        if ($name && $username && $password) {
            // Il campo "username" deve esere unico!
            if ($this->usernameExists($entityManagerInterface, $username)) {
                $this->addFlash("warning", "Username $username already exists!");
                return $this->redirect($this->generateUrl("auth.register"));
            }

            $user = new User();
            $user->setName($name);
            $user->setUsername($username);
            // Hash della password usando l'algoritmo SHA256
            $hash = hash("sha256", $password);
            $user->setPassword($hash);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            $this->addFlash("success", "You successfully created an account!");

            $request->getSession()->set($_ENV["SESSION_COOKIE"], $user->getId());

            return $this->redirect($this->generateUrl("app"));
        } else {
            $this->addFlash("warning", "An unexpected error has occurred. Please try again!");
            return $this->redirect($this->generateUrl("auth.register"));
        }
    }

    private function usernameExists(EntityManagerInterface  $entityManagerInterface, string $username): bool
    {
        $repository = $entityManagerInterface->getRepository(User::class);

        return $repository->findOneBy(["username" => $username]) !== null;
    }
}
