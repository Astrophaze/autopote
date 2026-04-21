<?php
// src/Controller/PartController.php

namespace App\Controller;

use App\DTO\CreatePartDTO;
use App\Entity\Part;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
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
        BrandRepository $brandRepository,
        CategoryRepository $categoryRepository,
    ): JsonResponse {

        // À ce stade, $dto est déjà validé.
        // Sinon, on reçoit une 422 en réponse HTTP
        $brand = $brandRepository->find($dto->brand);
        $category = $categoryRepository->find($dto->category);

        $part = new Part();
        $part->setName($dto->name);
        $part->setReference($this->generateReference($dto->name));
        $part->setDescription($dto->description);
        $part->setPrice(0.0);
        $part->setStock(5);
        $part->setPartCondition('Neuf');
        $part->setIsAvailable(true);
        $part->setBrand($brand);
        $part->setCategory($category);

        // Et on persiste la pièce
        $em->persist($part);
        $em->flush();

        // Puis on renvoie l'objet créé en lui passant le groupe de lecture : il saura quelles propriétés donner ! 
        return $this->json($part, 201, [], ['groups' => ['part:read']]);
    }


    private function generateReference(string $name): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name), 0, 3));
        $prefix = str_pad($prefix, 3, 'X');
        $unique = strtoupper(substr(uniqid(), -6));

        return sprintf('PART-%s-%s', $prefix, $unique);
    }

}
