<?php

namespace App\DTO;

use App\Entity\Brand;
use App\Entity\Category;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePartDTO
{
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    public string $name;

    #[Assert\NotBlank(message: 'La référence est obligatoire.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'La référence doit faire au moins {{ limit }} caractères.',
        maxMessage: 'La référence ne peut pas dépasser {{ limit }} caractères.'
    )]
    public string $reference;

    #[Assert\Length(
        max: 500,
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères.'
    )]
    public ?string $description = null;

    #[Assert\NotBlank(message: 'Le prix est obligatoire.')]
    #[Assert\Positive(message: 'Le prix doit être un nombre positif.')]
    public float $price;

    #[Assert\PositiveOrZero(message: 'Le stock doit être un nombre positif ou nul.')]
    public int $stock;

    #[Assert\NotBlank(message: 'La condition de la pièce est obligatoire.')]
    public string $part_condition;

    public bool $isAvailable = true;

    public DateTimeImmutable $createdAt;


    #[Assert\NotBlank(message: 'La marque est obligatoire.')]
    public Brand $brand;

    #[Assert\NotBlank(message: 'La catégorie est obligatoire.')]
    public Category $category;

}