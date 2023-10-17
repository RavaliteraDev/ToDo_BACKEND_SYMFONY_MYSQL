<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Person;

#[Route('/person', name: 'api_person')]
class PersonController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create', name: 'person_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $person = new Person();
        $person->setUuid(Uuid::v7());
        $person->setLastName($data["last_name"]);
        $person->setFirstName($data["first_name"]);
        $person->setEmailAddress($data["email_address"]);
        $person->setPhoneNumber($data["phone_number"]);
        $person->setPassword($data["password"]);
        $this->entityManager->persist($person);
        $this->entityManager->flush();
        return $this->json(["message" => "Person created !"]);
    }

    #[Route('/read/{id}', name: 'person_read', methods: ["GET"])]
    public function read(string $id): JsonResponse
    {
        $person = $this->entityManager->getRepository(Person::class)->find($id);
        $data = [
            "uuid" => $person->getUuid(),
            "last_name" => $person->getLastName(),
            "first_name" => $person->getFirstName(),
            "email" => $person->getEmailAddress(),
            "phone_number" => $person->getPhoneNumber(),
            "is_activated" => $person->getIsActivated()
        ];
        return $this->json($data);
    }

    #[Route('/update/{id}', name: "person_update", methods: ["PUT"])]
    public function update(Request $request, Person $person): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $person->setLastName($data["last_name"]);
        $person->setFirstName($data["first_name"]);
        $person->setEmailAddress($data["email_address"]);
        $person->setPhoneNumber($data["phone_number"]);
        $person->setPassword($data["password"]);
        $this->entityManager->flush();
        return $this->json(["message" => "Person updated !"]);
    }
    
    #[Route('/delete/{id}', name: "person_delete", methods: ["DELETE"])]
    public function delete(Person $person): JsonResponse
    {
        $this->entityManager->remove($person);
        $this->entityManager->flush();
        return $this->json(["message" => "Person deleted !"]);
    }

    #[Route('/list', name: 'person_list', methods: ["GET"])]
    public function list(): JsonResponse
    {
        $people = $this->entityManager->getRepository(Person::class)->findAll();
        $data = [];
        foreach($people as $person) {
            $data[] = [
                "uuid" => $person->getUuid(),
                "last_name" => $person->getLastName(),
                "first_name" => $person->getFirstName(),
                "email" => $person->getEmailAddress(),
                "phone_number" => $person->getPhoneNumber(),
                "is_activated" => $person->getIsActivated()
            ];
        }
        return $this->json($data);
    }
}
