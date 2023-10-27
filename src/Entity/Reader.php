<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readers')]

    private Collection $Book;

    public function __construct()
    {
        $this->Book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBook(): Collection
    {
        return $this->Book;
    }

    public function addBook(Book $book): static
    {
        if (!$this->Book->contains($book)) {
            $this->Book->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->Book->removeElement($book);

        return $this;
    }
}
