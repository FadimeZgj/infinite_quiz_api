<?php

namespace App\Tests\Unit;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Response;
use PHPUnit\Framework\TestCase;

class QuestionTest extends TestCase
{
    private Question $question;

    protected function setUp(): void
    {
        $this->question = new Question();
    }

    public function testGetAndSetQuestion(): void
    {
        $questionText = 'Test question';
        $this->question->setQuestion($questionText);

        self::assertEquals($questionText, $this->question->getQuestion());
    }

    public function testGetAndSetQuiz(): void
    {
        $quiz = new Quiz();
        $this->question->setQuiz($quiz);

        self::assertEquals($quiz, $this->question->getQuiz());
    }

    public function testGetAndAddResponse(): void
    {
        $response = new Response();
        $this->question->addResponse($response);

        self::assertTrue($this->question->getResponse()->contains($response));
    }

    public function testRemoveResponse(): void
    {
        $response = new Response();
        $this->question->addResponse($response);
        $this->question->removeResponse($response);

        self::assertFalse($this->question->getResponse()->contains($response));
    }
}
