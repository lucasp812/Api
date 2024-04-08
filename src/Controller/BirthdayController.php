<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Birthday;
use App\Repository\BirthdayRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BirthdayController extends AbstractController
{ 

/**
* @Route("/birthday", name="app_birthday_GET", methods={"GET"})
*/
    public function index(BirthdayRepository $birthdayRepository, SerializerInterface $serializer): Response
    {
        $birthdays = $birthdayRepository->findAll();
        $jsonContent = $serializer->serialize($birthdays, 'json');
        
        return new Response($jsonContent, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

/**
 * @Route("/birthday/{id}", name="app_birthday_find_by_id", methods={"GET"})
 */
public function findById(int $id, BirthdayRepository $birthdayRepository, SerializerInterface $serializer): Response
{
    $birthday = $birthdayRepository->find($id);
    $jsonContent = $serializer->serialize($birthday, 'json');
    return new Response($jsonContent, 200, [
        'Content-Type' => 'application/json'
    ]);
}

/**
 * @Route("/birthday/{id}", name="app_birthday_delete", methods={"DELETE"})
 */
public function delete(int $id, BirthdayRepository $birthdayRepository, SerializerInterface $serializer): Response
{
    $birthday = $birthdayRepository->find($id);
    if (!$birthday) {
        throw $this->createNotFoundException('Anniversaire non trouvé pour cet ID : ' . $id);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($birthday);
    $entityManager->flush();

    $responseContent = $serializer->serialize(['message' => 'Anniversaire supprimé avec succès'], 'json');

    return new Response($responseContent, 200, [
        'Content-Type' => 'application/json'
    ]);
}

    /**
    * @Route("/birthday", name="app_birthday", methods={"POST"})
    */
public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): Response
{
    $jsonData = $request->getContent();
    $birthday = $serializer->deserialize($jsonData, Birthday::class, 'json');
    $errors = $validator->validate($birthday);
    if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }
        $responseContent = $serializer->serialize(['errors' => $errorMessages], 'json');
        return new Response($responseContent, 400, [
            'Content-Type' => 'application/json'
        ]);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($birthday);
    $entityManager->flush();
    $jsonContent = $serializer->serialize($birthday, 'json');
    return new Response($jsonContent, 201, [
        'Content-Type' => 'application/json'
    ]);
}
/**
 * @Route("/birthday/{id}", name="app_birthday_update", methods={"PUT"})
 */
public function update(int $id, Request $request, BirthdayRepository $birthdayRepository, SerializerInterface $serializer, ValidatorInterface $validator): Response
{
    $birthday = $birthdayRepository->find($id);
    if (!$birthday) {
        throw $this->createNotFoundException('Anniversaire non trouvé pour cet ID : ' . $id);
    }
    $jsonData = $request->getContent();
    $updatedBirthday = $serializer->deserialize($jsonData, Birthday::class, 'json', ['object_to_populate' => $birthday]);
    $errors = $validator->validate($updatedBirthday);
    if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }
        $responseContent = $serializer->serialize(['errors' => $errorMessages], 'json');
        return new Response($responseContent, 400, [
            'Content-Type' => 'application/json'
        ]);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    $jsonContent = $serializer->serialize($updatedBirthday, 'json');

    return new Response($jsonContent, 200, [
        'Content-Type' => 'application/json'
    ]);
}
}
  