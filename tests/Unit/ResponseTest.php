<?php

namespace App\Tests\Unit;

use App\Entity\Question;
use App\Entity\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    private Response $response;

    protected function setUp(): void
    {
        $this->response = new Response();
    }

    public function testGetAndSetResponse(): void
    {
        $value = 'This is a response';
        $this->response->setResponse($value);
        self::assertEquals($value, $this->response->getResponse());
    }

    public function testGetAndSetCorrect(): void
    {
        $value = true;
        $this->response->setCorrect($value);
        self::assertEquals($value, $this->response->isCorrect());
    }

    public function testAddAndRemoveQuestion(): void
    {
        $question = new Question();
        $this->response->addQuestion($question);
        self::assertTrue($this->response->getQuestions()->contains($question));

        $this->response->removeQuestion($question);
        self::assertFalse($this->response->getQuestions()->contains($question));
    }
}
