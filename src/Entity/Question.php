<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\ManyToOne(inversedBy: 'question')]
    private ?Quiz $quiz = null;

    /**
     * @var Collection<int, Response>
     */
    #[ORM\ManyToMany(targetEntity: Response::class, inversedBy: 'questions')]
    private Collection $response;

    public function __construct()
    {
        $this->response = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getResponse(): Collection
    {
        return $this->response;
    }

    public function addResponse(Response $response): static
    {
        if (!$this->response->contains($response)) {
            $this->response->add($response);
        }

        return $this;
    }

    public function removeResponse(Response $response): static
    {
        $this->response->removeElement($response);

        return $this;
    }
}
