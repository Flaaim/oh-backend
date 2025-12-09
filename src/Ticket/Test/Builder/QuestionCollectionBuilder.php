<?php

namespace App\Ticket\Test\Builder;

use Doctrine\Common\Collections\ArrayCollection;

class QuestionCollectionBuilder
{
    private array $questions = [];

    public function withImages(): ArrayCollection
    {
        $this->questions = $this->collectionWithImages();
        return $this->build();
    }
    private function build(): ArrayCollection
    {
        return new ArrayCollection($this->questions);
    }
    private function collectionWithImages(): array
    {
        return  [
            [
                'id' => '49336cb09422414399ec69aa582f60e4',
                'number' => '1',
                'text' => 'Какое требование предъявляется к кабелю переносной лампы, применяемой в работе с кислотными аккумуляторными батареями?',
                'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/c37111/49336cb0-9422-4143-99ec-69aa582f60e4/8/1.jpgg',
                'answers' => [
                    [
                        'id' => '30604d45-60be-4316-8f97-58f2cfa18fda',
                        'text' => 'Кабель должен быть в кислостойком шланге',
                        'isCorrect' => true,
                        'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/1.jpg'
                    ],
                    [
                        'id' => '71a6e6e9-6215-41e6-a5ac-745f86182730',
                        'text' => 'Кабель должен иметь не более 3 скруток',
                        'isCorrect' => false,
                        'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg'
                    ],
                    [
                        'id' => 'e6dac57f-2c3d-43f2-87b4-79ba3b92c8ae',
                        'text' => 'Кабель должен быть только в тканевой оплетке',
                        'isCorrect' => false,
                        'image' => ''
                    ],
                    [
                        'id' => 'e09aa4b3-0474-4e25-90ba-240565a62a0a',
                        'text' => 'Кабель должен быть длиной не более 1,5 м',
                        'isCorrect' => false,
                        'image' => ''
                    ]
                ]
            ],
            [
                'id' => '7c7f0af42f28486484010dccaf6942c8',
                'number' => '3',
                'text' => 'Установите правильную последовательность действий работника в случае обнаружения пожара.',
                'image' => 'http://olimpoks.chukk.ru:82/QuestionImages/81703c22-7f8e-4a37-9591-e0d59f4fc093/8/2.jpg',
                'answers' => [
                    [
                        'id' => '50d0ebdd-6c82-4fca-aa6c-6f7054c54dde',
                        'text' => 'Сообщить о возгорании по телефону в пожарную охрану',
                        'isCorrect' => true,
                        'image' => ''
                    ],
                    [
                        'id' => 'deafa7ab-76c5-4f24-afa2-de5bce32aacc',
                        'text' => 'Принять меры по эвакуации людей',
                        'isCorrect' => true,
                        'image' => ''
                    ],
                    [
                        'id' => 'b46f838d-3a5a-4104-887c-6ae04652076c',
                        'text' => 'Приступить к тушению пожара в начальной стадии (при отсутствии угрозы жизни и здоровью людей)',
                        'isCorrect' => true,
                        'image' => ''
                    ]
                ]
            ],

        ];
    }
}