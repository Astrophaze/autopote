<?php
// src/Controller/PartController.php

namespace App\Controller;

use App\DTO\CreatePartDTO;
use App\Entity\Part;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PartController extends AbstractController
{
    #[Route('/api/parts', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(
        #[MapRequestPayload] CreatePartDTO $dto,
        EntityManagerInterface $em,
    ): JsonResponse {

        // À ce stade, $dto est déjà validé.
        // Sinon, on reçoit une 422 en réponse HTTP

        $part = new Part();
        $part->setName($dto->name);
        $part->setReference($dto->reference);
        $part->setDescription($dto->description);
        $part->setPrice($dto->price);
        $part->setStock($dto->stock);
        $part->setPartCondition($dto->part_condition);
        $part->setIsAvailable($dto->isAvailable);
        $part->setCreatedAt($dto->createdAt);
        $part->setBrand($dto->brand);
        $part->setCategory($dto->category);

        // Et on persiste la pièce
        $em->persist($part);
        $em->flush();

        // Puis on renvoie l'objet créé en lui passant le groupe de lecture : il saura quelles propriétés donner ! 
        return $this->json($part, 201, [], ['groups' => ['part:read']]);
    }
}
