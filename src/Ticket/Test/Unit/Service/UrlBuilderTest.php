<?php

namespace App\Ticket\Test\Unit\Service;

use App\Ticket\Service\ImageDownloader\UrlBuilder;
use PHPUnit\Framework\TestCase;

class UrlBuilderTest extends TestCase
{
    public function testBuildSuccess()
    {
        $urlBuilder = new UrlBuilder('http://localhost/QuestionImages');
        $url = $urlBuilder->buildNewQuestionUrl(
            '/app/config/common/../../public/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/1.jpg'
        );

        $this->assertEquals('http://localhost/QuestionImages/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/1.jpg', $url);
    }

    public function testBuildNotFound()
    {
        $urlBuilder = new UrlBuilder('http://localhost/QuestionImages');
        $url = $urlBuilder->buildNewQuestionUrl(
            '/app/config/common/../../public/Question/fb87c290-07de-4183-b67b-d4d697ff7f04/969f4b34-43e4-4f76-bc0e-b02f8633734e/1.jpg'
        );
        $this->assertEquals('http://localhost/QuestionImages', $url);
    }

    public function testBuildEmpty()
    {
        $urlBuilder = new UrlBuilder('http://localhost/QuestionImages');
        $url = $urlBuilder->buildNewQuestionUrl('');
        $this->assertEquals('http://localhost/QuestionImages', $url);
    }
}
