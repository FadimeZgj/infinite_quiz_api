<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('response:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('response:read')]
    private ?string $response = null;

    #[ORM\Column]
    #[Groups('response:read')]
    private ?bool $isCorrect = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'responses')]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function isCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->addResponse($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            $question->removeResponse($this);
        }

        return $this;
    }
}
