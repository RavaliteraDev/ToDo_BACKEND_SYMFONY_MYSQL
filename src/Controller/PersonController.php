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
        if(isset($data["last_name"])) $person->setLastName($data["last_name"]);
        if(isset($data["first_name"])) $person->setFirstName($data["first_name"]);
        if(isset($data["email_address"])) $person->setEmailAddress($data["email_address"]);
        if(isset($data["phone_number"])) $person->setPhoneNumber($data["phone_number"]);
        if(isset($data["password"])) $person->setPassword($data["password"]);
        $this->entityManager->persist($person);
        $this->entityManager->flush();
        return $this->json(["message" => "Person is created !"]);
    }

    #[Route('/read/{uuid}', name: 'person_read', methods: ["GET"])]
    public function read(string $uuid): JsonResponse
    {
        $person = $this->entityManager->getRepository(Person::class)->find($uuid);
        if(isset($person)) {
            $data = [
                "uuid" => $person->getUuid(),
                "last_name" => $person->getLastName(),
                "first_name" => $person->getFirstName(),
                "email" => $person->getEmailAddress(),
                "phone_number" => $person->getPhoneNumber(),
                "is_activated" => $person->getIsActivated()
            ];
            return $this->json($data);
        } else {
            return $this->json(["message" => "Person does not exist !"]);
        }
    }

    #[Route('/update/{uuid}', name: "person_update", methods: ["PUT"])]
    public function update(Request $request, string $uuid): JsonResponse
    {
        $person = $this->entityManager->getRepository(Person::class)->find($uuid);
        if(isset($people)) {            
            $data = json_decode($request->getContent(), true);
            if(isset($data["last_name"])) $person->setLastName($data["last_name"]);
            if(isset($data["first_name"])) $person->setFirstName($data["first_name"]);
            if(isset($data["email_address"])) $person->setEmailAddress($data["email_address"]);
            if(isset($data["phone_number"])) $person->setPhoneNumber($data["phone_number"]);
            if(isset($data["password"])) $person->setPassword($data["password"]);
            if(isset($data["is_activated"])) $person->setIsActivated($data["is_activated"]);
            $this->entityManager->flush();
            return $this->json(["message" => "Person is updated !"]);
        } else {
            return $this->json(["message" => "Person does not exist !"], 404);
        }
    }
    
    #[Route('/delete/{uuid}', name: "person_delete", methods: ["DELETE"])]
    public function delete(string $uuid): JsonResponse
    {
        $person = $this->entityManager->getRepository(Person::class)->find($uuid);
        if(isset($people)) {
            $this->entityManager->remove($person);
            $this->entityManager->flush();
            return $this->json(["message" => "Person deleted !"]);
        } else {
            return $this->json(["message" => "Person does not exist !"], 404);
        }
    }

    #[Route('/list', name: 'person_list', methods: ["GET"])]
    public function list(): JsonResponse
    {
        $people = $this->entityManager->getRepository(Person::class)->findAll();
        if(isset($people) && $people != []) {
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
        } else {
            return $this->json(["message" => "Person does not exist !"]);
        }
    }
}
