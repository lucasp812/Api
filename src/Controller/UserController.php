<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
* @Route("/api", name="api_route")
*/
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="app_register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {
        $jsonData = $request->getContent();
        $user = $serializer->deserialize($jsonData, User::class, 'json');
        $errors = $validator->validate($user);
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $userData = [
            'email' => $user->getEmail(),
        ];
        $jsonContent = $serializer->serialize($userData, 'json');

        return new Response($jsonContent, 201, [
            'Content-Type' => 'application/json'
        ]);
    }
  
}
