<?php

namespace App\Ticket\Test\Builder;

class QuestionProvider
{
    private array $questions = [];
    public function toArrayWithImages(): array
    {
        $this->questions = $this->withImages();
        return $this->questions;
    }
    public function toArrayWithoutImages(): array
    {
        $this->questions = $this->withoutImages();
        return $this->questions;
    }
    public function toArrayWithImagesOnlyInQuestion(): array
    {
        $this->questions = $this->withImagesOnlyInQuestion();
        return $this->questions;
    }
    public function toArrayWithImagesOnlyInAnswers(): array
    {
        $this->questions = $this->withImagesOnlyInAnswers();
        return $this->questions;
    }
    private function withImages(): array
    {
         return [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg"
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg"
                ]
            ],
        ];
    }
    private function withoutImages(): array
    {
        return [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => '',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => ""
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => ""
                ]
            ],

        ];
    }

    private function withImagesOnlyInQuestion(): array
    {
        return  [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpg',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => ""
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => ""
                ]
            ],

        ];
    }

    private function withImagesOnlyInAnswers(): array
    {
        return  [
            'id' => '49336cb09422414399ec69aa582f60e4',
            'number' => '1',
            'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
            'image' => '',
            'answers' => [
                [
                    "id" => "30604d45-60be-4316-8f97-58f2cfa18fda",
                    "text" => "Кабель должен быть в кислостойком шланге",
                    "isCorrect" => true,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg"
                ],
                [
                    "id" => "71a6e6e9-6215-41e6-a5ac-745f86182730",
                    "text" => "Кабель должен иметь не более 3 скруток",
                    "isCorrect" => false,
                    "image" => "http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg"
                ]
            ],

        ];
    }
}