<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // private string $content;

    // private bool $isApproved = false;

    // private User $author;

    // private Recipe $recipe;

    // private DateTimeImmutable $createdAt;

    // public function __construct()
    // {
    //     $this->createdAt = new DateTimeImmutable();
    // }


    // public function getId(): ?int
    // {
    //     return $this->id;
    // }

    // public function getContent(): ?string
    // {
    //     return $this->content;
    // }

    // public function setContent(): ?string
    // {
    //     return $this->content;
    // }

    // public function getContent(): ?string
    // {
    //     return $this->content;
    // }


}
