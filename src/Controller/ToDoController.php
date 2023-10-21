<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use App\Entity\ToDo;
use App\Entity\Person;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/toDo', name: 'api_todo')]
class ToDoController extends AbstractController
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/create', name: 'todo_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $toDo = new toDo();
        $toDo->setUuid(Uuid::v7());
        if(isset($data["title"])) $toDo->setTitle($data["title"]);
        if(isset($data["description"])) $toDo->setDescription($data["description"]);
        if(isset($data["due_date"])) $toDo->setDueDate(new DateTime($data["due_date"]));
        if(isset($data["comments"])) $toDo->setComments($data["comments"]);
        if(isset($data["is_completed"])) $toDo->setIsCompleted($data["is_completed"]);
        if(isset($data["person_uuid"])) $toDo->setPerson($this->entityManager->getRepository(Person::class)->find($data["person_uuid"]));
        $this->entityManager->persist($toDo);
        $this->entityManager->flush();
        return $this->json(["message" => "ToDo is created !"]);
    }

    #[Route('/read/{uuid}', name: 'todo_read', methods: ["GET"])]
    public function read(string $uuid): JsonResponse
    {
        $toDo = $this->entityManager->getRepository(ToDo::class)->find($uuid);
        if(isset($toDo)) {
            $data = [
                "uuid" => $toDo->getUuid(),
                "title" => $toDo->getTitle(),
                "description" => $toDo->getDescription(),
                "comments" => $toDo->getComments(),
                "due_date" => $toDo->getDueDate(),
                "is_completed" => $toDo->getIsCompleted(),
                "person_uuid" => $toDo->getPerson()->getUuid()
            ];
            return $this->json($data);
        } else {
            return $this->json(["message" => "ToDo does not exist !"], 404);
        }
    }

    #[Route('/update/{uuid}', name: "todo_update", methods: ["PUT"])]
    public function update(Request $request, string $uuid): JsonResponse
    {
        $toDo = $this->entityManager->getRepository(ToDo::class)->find($uuid);
        if(isset($toDo)) {
            $data = json_decode($request->getContent(), true);
            if(isset($data["title"])) $toDo->setTitle($data["title"]);
            if(isset($data["description"])) $toDo->setDescription($data["description"]);
            if(isset($data["comments"])) $toDo->setComments($data["comments"]);
            if(isset($data["due_date"])) $toDo->setDueDate(new DateTime($data["due_date"]));
            if(isset($data["is_completed"])) $toDo->setIsCompleted($data["is_completed"]);
            $this->entityManager->flush();
            return $this->json(["message" => "ToDo is updated !"]);
        } else {
            return $this->json(["message" => "ToDo does not exist !"], 404);
        }
    }

    #[Route('/delete/{uuid}', name: "toDo_delete", methods: ["DELETE"])]
    public function delete(string $uuid): JsonResponse
    {
        $toDo = $this->entityManager->getRepository(ToDo::class)->find($uuid);
        if(isset($toDo)) {
            $this->entityManager->remove($toDo);
            $this->entityManager->flush();
            return $this->json(["message" => "ToDo is deleted !"]);
        } else {
            return $this->json(["message" => "ToDo does not exist !"], 404);
        }
    }

    #[Route('/list', name: 'toDo_list', methods: ["GET"])]
    public function list(): JsonResponse
    {
        $toDos = $this->entityManager->getRepository(ToDo::class)->findAll();
        if(isset($toDos) && $toDos != []) {
            $data = [];
            foreach($toDos as $toDo) {
                $data[] = [
                    "uuid" => $toDo->getUuid(),
                    "title" => $toDo->getTitle(),
                    "description" => $toDo->getDescription(),
                    "due_date" => $toDo->getDueDate(),
                    "comments" => $toDo->getComments(),
                    "is_completed" => $toDo->getIsCompleted(),
                    "person_uuid" => $toDo->getPerson()->getUuid()
                ];
            }
            return $this->json($data);
        } else {
            return $this->json(["message" => "ToDo does not exist !"]);
        }
    }
}
