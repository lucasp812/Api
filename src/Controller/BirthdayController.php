<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\BirthdayRepository;

class BirthdayController extends AbstractController
{
   
    /**
    * @Route("/birthday", name="app_birthday", methods={"GET"})
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
 * @Route("/birthday/{id}", name="app_birthday_find_by_id", methods={"DELETE"})
 */
public function delete(int $id, BirthdayRepository $birthdayRepository, SerializerInterface $serializer): Response
{
    $birthday = $birthdayRepository->find($id);
    $jsonContent = $serializer->serialize($birthday, 'json');

    return new Response($jsonContent, 200, [
        'Content-Type' => 'application/json'
    ]);
}





    }



  