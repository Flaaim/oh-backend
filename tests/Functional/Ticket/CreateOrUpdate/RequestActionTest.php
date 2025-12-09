<?php

namespace Test\Functional\Ticket\CreateOrUpdate;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
//    public function setUp(): void
//    {
//        parent::setUp();
//        $this->loadFixtures([
//            RequestFixture::class
//        ]);
//    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(
            self::json('POST', '/payment-service/tickets/create', $this->ticketArrayProvider()
        ));

        $this->assertEquals(201, $response->getStatusCode());
        self::assertEquals('', $response->getBody());

        $pathToFiles = '/8c68fbe7-c32d-4bec-a094-fd5d9773ca35/d5d36c98-c483-42d6-8105-32063eef8c64/';

        self::assertDirectoryExists(sys_get_temp_dir().'/8c68fbe7-c32d-4bec-a094-fd5d9773ca35');
        self::assertFileExists(sys_get_temp_dir(). $pathToFiles . '8dcf298b-dfdd-4f18-9cdc-75be69a67c5b/1.jpg');
        self::assertFileExists(sys_get_temp_dir(). $pathToFiles . '61f5545f-39ba-429c-aac3-9764de3f126c/2.jpg');
        self::assertFileExists(sys_get_temp_dir(). $pathToFiles . '68195ccd-e42b-4a37-bbc7-674787fa8a11/3.jpg');

    }
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/tickets/create'));

        $this->assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'ticket' => 'This value should not be blank.',
                'ticket[id]' => 'This field is missing.',
                'ticket[cipher]' => 'This field is missing.',
                'ticket[name]' => 'This field is missing.',
                'ticket[status]' => 'This field is missing.',
                'ticket[updatedAt]' => 'This field is missing.',
                'ticket[questions]' => 'This field is missing.',
            ]
        ], $data);
    }
    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/tickets/create', [
            'id' => 'something',
            'name' => 'something',
            'questions' => []
        ]));

        $this->assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'ticket[id]' => 'This is not a valid UUID.',
                'ticket[cipher]' => 'This field is missing.',
                'ticket[status]' => 'This field is missing.',
                'ticket[updatedAt]' => 'This field is missing.',
                'ticket[questions]' => 'This value should not be blank.',
            ]
        ], $data);
    }
    private function ticketArrayProvider(): array
    {
        return
            [
                'id' => '8c68fbe7-c32d-4bec-a094-fd5d9773ca35',
                'name' => 'Оказание первой помощи пострадавшим',
                'cipher' => 'ОТ 201.18',
                'status' => 'inactive',
                'updatedAt' => '28.11.2025',
                'questions' => array(
                    array(
                        'id' => 'dc97acee-c848-47eb-91c2-a3c43aa8b713',
                        'number' => '1',
                        'text' => 'Установите правильную последовательность действий во время оказания первой помощи при отравлении через кожу.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'd71db86e-28ef-45e2-b4f0-177c071059df',
                                'text' => 'Прекратить поступление яда в организм',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bfe88d03-bed0-4b41-8008-9576a0470604',
                                'text' => 'Снять загрязненную одежду',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '0fe98918-a3c3-4361-a586-1706575e00da',
                                'text' => 'Удалить яд с поверхности кожи, промыв ее',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '544c306b-c714-48d5-b5a3-33e38b002ef1',
                                'text' => 'Наложить повязку при наличии повреждений',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'caa24509-f4aa-43d9-b3f7-facbfa1e0014',
                        'number' => '2',
                        'text' => 'Какой способ следует применять для временной остановки сильного артериального кровотечения?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '579eda60-eae0-47b4-a6dc-bbebdf7449ac',
                                'text' => 'Пальцевое прижатие артерии',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bc59b4b2-c4c6-481e-bbb6-cc494a2f8faf',
                                'text' => 'Максимальное сгибание конечности в суставе',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '219bf21e-fc21-4cca-aeae-be16630274dc',
                                'text' => 'Наложение холода на рану и обездвижение конечности',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '6d35f4c4-2b60-4e41-bf00-4b619ea16bba',
                                'text' => 'Наложение кровоостанавливающего жгута',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '5a2b5779-9b63-437a-8b30-fa5e8d66849a',
                        'number' => '3',
                        'text' => 'Что необходимо сделать во время оказания первой помощи при травмах живота с выпадением внутренних органов? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '68e2964b-d248-47b5-ab46-75c7e766677c',
                                'text' => 'Выпавшие внутренние органы закрыть салфетками или чистой тканью, смоченной водой',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bd636fe3-edd7-46a0-b2d0-3b1ea8e43d77',
                                'text' => 'Вернуть выпавшие внутренние органы в брюшную полость',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2871a31e-1872-41cc-842a-245315895f25',
                                'text' => 'Предложить пострадавшему теплое питье и обезболивающий препарат',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '19a37165-5089-4611-b42a-f86d84e210f0',
                                'text' => 'Туго прибинтовать выпавшие внутренние органы',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '440617b7-13b6-4871-8c2d-e5d144a8b66b',
                                'text' => 'Наложить нетугую фиксирующую повязку',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e82213a1-724c-4f84-9e51-05b01ff79826',
                        'number' => '4',
                        'text' => 'Что необходимо сделать во время оказания первой помощи при отравлении через рот, если пострадавший в сознании?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '44bff91e-1dcb-4d6b-be61-7cbe8887cb9c',
                                'text' => 'Дать пострадавшему выпить 5 - 6 стаканов воды и вызвать рвоту',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f5846169-f056-4130-b65e-20d561d42b97',
                                'text' => 'Дать пострадавшему активированный уголь',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c4122eec-4227-49cd-a928-dcd2bf661703',
                                'text' => 'Уложить пострадавшего на спину и не давать пить',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '5375336a-5d60-4bc0-80ed-3bc9bfc1f38c',
                                'text' => 'Выполнить 5 надавливаний на живот пострадавшего для удаления отравляющего вещества',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '3c3fb266-aeac-4412-b902-0df61428a9ce',
                        'number' => '5',
                        'text' => 'Что следует сделать во время оказания первой помощи при укусе змеи?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '334b1254-ea0a-4a51-8d1c-12a34b52f401',
                                'text' => 'Отсосать яд из раны, затем наложить поверх раны повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '995baed1-019f-4af7-80c8-f9315f1de20c',
                                'text' => 'Приложить холод к месту укуса и ограничить подвижность поврежденной части тела',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '8b9bf0d1-06c1-4926-8a2a-4bdaaa9c826e',
                                'text' => 'Промыть рану водой, воспользоваться спиртовыми салфетками при их наличии',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '03889317-898d-467a-ab55-b552043c3826',
                                'text' => 'Надавить на рану, затем наложить жгут выше места укуса',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'c68fe043-1ad2-4cc0-9aa5-617bac576d49',
                        'number' => '6',
                        'text' => 'Какой должна быть глубина продавливания грудной клетки при проведении сердечно-легочной реанимации?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '64721ed0-d616-491a-b997-cff82f09c1a1',
                                'text' => '5 - 6 см',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'fa9384bd-9bc1-4acd-9da6-56ff23c100a7',
                                'text' => '2 - 3 см',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '17d06c28-c8a9-4393-918e-2d5c3a692c55',
                                'text' => '3 - 4 см',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '0a249ace-0c1a-4cc4-8e53-63f1e5031706',
                        'number' => '7',
                        'text' => 'Что относится к признакам кровопотери? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'a9afb069-cfd2-4dcf-8f96-4dd8e838ccb0',
                                'text' => 'Чувство жажды',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cdc9c1b4-c071-4044-87e7-a5449f1d0f15',
                                'text' => 'Повышенная температура тела',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '976e6735-a2b2-4ec1-b4ec-8430ef2519d6',
                                'text' => 'Частое дыхание',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '36aa0105-5c7c-46c0-a5d0-a478d4e491a5',
                                'text' => 'Судороги',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e147d59e-906b-4499-88c5-818321666e3a',
                        'number' => '8',
                        'text' => 'Какой способ остановки наружного кровотечения может применяться в случаях, когда прямое давление на рану невозможно, опасно или неэффективно? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'e05810fa-c2f5-4376-8f16-f1df9b9400f6',
                                'text' => 'Наложение давящей повязки',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'ce7a4f4b-aca5-40ff-996a-cd50aa3bf8b3',
                                'text' => 'Пальцевое прижатие сонной артерии',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'e96b8dbd-7531-4dfb-b8eb-65a2de96ba31',
                                'text' => 'Приложение холода к ране',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '7e54843f-7908-414a-960a-1151cbb8b71f',
                                'text' => 'Наложение кровоостанавливающего жгута',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '263b5a07-85c6-4e3a-8657-c39e40576c7c',
                        'number' => '9',
                        'text' => 'Что необходимо сделать, если давящая повязка начинает незначительно пропитываться кровью?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '61f71116-0c8a-42c1-bddc-99896096f886',
                                'text' => 'Наложить новую давящую повязку поверх имеющейся',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '046fa0b0-a1e0-426b-b6cc-c48a0f7f3f19',
                                'text' => 'Снять промокшую повязку и наложить новую, не промывая раны',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f78af13b-a31c-4d6f-83c8-200db4eddb4f',
                                'text' => 'Снять промокшую повязку, удалить сгустки крови, наложить новую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c6bb78e8-b555-42cb-a3ff-262a113ddfbf',
                                'text' => 'Осуществить прямое давление на рану поверх повязки',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '207c9aa7-d8c3-4005-8246-f2045196096d',
                        'number' => '10',
                        'text' => 'Что входит в комплект аптечки для оказания работниками первой помощи пострадавшим?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '3d8ebc02-07aa-4d5e-a8be-787fe176e300',
                                'text' => 'Йод',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '79a0e689-9c9e-48c3-8481-12b315c7daef',
                                'text' => 'Спасательное изотермическое покрывало',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '70b1c77c-1fa7-447d-bdd3-9d2e5c46f934',
                                'text' => 'Спиртовые салфетки',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '3f8a7462-4028-4695-87b0-81ca488fdde9',
                                'text' => 'Активированный уголь',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'cbf46a66-b944-4af1-ba0f-ab864c8d7a5d',
                        'number' => '11',
                        'text' => 'Для чего предназначен автоматический наружный дефибриллятор? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '95b79e7a-365e-4fe2-81fc-d4d2f1823d19',
                                'text' => 'Для анализа ритма сердца пострадавшего',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '3541e4c1-1867-4ef5-8526-224d7eea32f0',
                                'text' => 'Для определения признаков жизни у пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f9cb4770-d4c3-440f-8289-94ed81a1910f',
                                'text' => 'Для поддержания нормального дыхания у пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'b4f1f41b-0fb6-4508-9858-b63f4afac097',
                                'text' => 'Для нанесения разряда электрического тока пострадавшему',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'f7d1422f-165b-4179-be02-6ccddbcd4f30',
                        'number' => '12',
                        'text' => 'Что следует сделать во время оказания первой помощи пострадавшему при химическом ожоге?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '05dabf4d-1aa2-4dfb-97a0-897753561c82',
                                'text' => 'Промыть поврежденное место под проточной водой не менее 20 минут и наложить на рану нетугую повязку',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '26940c81-9959-4506-87c4-bbc576467cf7',
                                'text' => 'Нейтрализовать химическое вещество щелочью и наложить на рану тугую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '47254cb4-cb0e-443e-9636-85440ec1e8bd',
                                'text' => 'Промыть поврежденное место под проточной водой не менее 10 минут и нанести на рану мазь',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2ba36c4d-8204-46a8-b844-4decb0f5c2fb',
                                'text' => 'Промыть поврежденное место под проточной водой не менее 5 минут и наложить на рану давящую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '195249be-092b-4184-88fb-e756e10780a4',
                        'number' => '13',
                        'text' => 'Какое определение соответствует понятию "первая помощь"?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'e56008da-4634-4a03-ba38-a73d24f81963',
                                'text' => 'Комплекс мероприятий до оказания медицинской помощи, направленных на сохранение и поддержание жизни и здоровья пострадавших и проводимых при несчастных случаях, травмах, ранениях, поражениях, отравлениях, других состояниях и заболеваниях, угрожающих жизни и здоровью пострадавших',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '50882b32-05c9-49cb-8e1e-3cfea846b267',
                                'text' => 'Деятельность медицинского персонала по оказанию услуг в целях сохранения, укрепления, предупреждения, лечения либо восстановления физического и психического здоровья человека, регулирования, конструирования жизнедеятельности человека и управление ей с использованием всех дозволенных методов и технологий',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '7fa67211-1bec-4591-9b7e-ad23966b355c',
                                'text' => 'Комплекс мероприятий, направленных на поддержание и (или) восстановление здоровья и включающих в себя предоставление медицинских услуг',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f110a90a-32e9-4ab7-bf4a-91d91f756e77',
                                'text' => 'Профессиональная медицинская помощь, оказываемая на месте происшествия, включающая диагностику и экстренное лечение',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '81c5e6d3-f8cd-4651-b609-c26ed779d811',
                        'number' => '14',
                        'text' => 'Что необходимо сделать после восстановления самостоятельного дыхания у пострадавшего с отсутствующим сознанием?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '9ef18733-938f-4c92-822e-4093bb67f242',
                                'text' => 'Потормошить пострадавшего за плечи',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '23bc3f17-8851-49a5-9b80-982dce80d72a',
                                'text' => 'Продолжить выполнять сердечно-легочную реанимацию до появления сознания у пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'ae4cb6c5-9b15-4a5c-9bc8-db53e32a6a3c',
                                'text' => 'Дать пострадавшему понюхать нашатырный спирт',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2d924f99-69e3-4849-a109-5ad5b7358842',
                                'text' => 'Придать пострадавшему устойчивое боковое положение',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '26e48e27-a9ef-4a6e-b781-ea0a09919a9e',
                        'number' => '15',
                        'text' => 'С какой периодичностью необходимо проводить обучение работников по оказанию первой помощи пострадавшим?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '6ecc0865-4e6e-4b18-b3b6-b42df8e1db94',
                                'text' => 'Не реже 1 раза в 5 лет',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'aebc721d-d4c2-4ab5-abea-3536bc0da869',
                                'text' => 'Не реже 1 раза в 3 года',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '46dee881-4f7c-4bd1-b1d6-0a06662a3f5b',
                                'text' => 'Не реже 1 раза в год',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '038c2a2b-a890-46c7-9525-88e259622a62',
                                'text' => 'Не реже 1 раза в квартал',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '51ce0c19-c8b1-4d7c-8e02-c6bee40b99dd',
                        'number' => '16',
                        'text' => 'В каких случаях разрешается прекратить проведение сердечно-легочной реанимации (СЛР)? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'cb6f4c27-5736-484d-adc3-5b0aa5c11081',
                                'text' => 'В случае прибытия скорой медицинской помощи и распоряжения о прекращении реанимации',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '27702d5b-56bc-43c6-a850-b7c4d01c79df',
                                'text' => 'В случае отсутствия признаков жизни у пострадавшего после 3 циклов проведения СЛР',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '76dfdb96-1040-4b79-8c3d-1ad45cf17378',
                                'text' => 'В случае появления угрозы для работника, оказывающего первую помощь',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cddf4012-3640-4bd0-973c-064d0e710888',
                                'text' => 'В случае отсутствия автоматического наружного дефибриллятора',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'a8a13332-6e26-4e21-a7da-cee13b412f29',
                        'number' => '17',
                        'text' => 'На какое время допускается снять кровоостанавливающий жгут, если максимальное время его наложения истекло, а пострадавшего не транспортировали в медицинскую организацию?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'd545b934-92c7-4f72-a98b-ca805e012fab',
                                'text' => 'На 15 минут',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '29a0b64e-dbe9-4531-8f60-4332fb911a6e',
                                'text' => 'На 10 минут',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c37fea8d-07d4-47cf-8ac5-04a131167560',
                                'text' => 'На 30 минут',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '5ec52258-fa5c-4de7-a432-ecdcb37ceb22',
                                'text' => 'Снимать жгут не рекомендуется',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'a388f5e3-2397-4509-b7eb-6c38239b2028',
                        'number' => '18',
                        'text' => 'Какой срок наложения кровоостанавливающего жгута является безопасным?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '40e4bd1c-a5c8-4df6-a4aa-36769e09c280',
                                'text' => '2 часа, независимо от температуры окружающей среды',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '41bd9fda-2e0f-4cf3-8a87-3b703d8af09c',
                                'text' => '3 часа при наложении в теплое время года, 2 часа - в холодное',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd8573a51-4bcc-4d64-b866-d8b8c15747fb',
                                'text' => '3 часа, независимо от температуры окружающей среды',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '0102a5fa-926f-4307-ac6f-7cc53395a7bb',
                                'text' => '2 часа при наложении в теплое время года, 3 часа - в холодное',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1bf4e8df-9db0-4b78-a0d0-cade1c10f50d',
                        'number' => '19',
                        'text' => 'Что является признаком ранения грудной клетки с нарушением герметичности?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '37d8fb64-6dea-4ef2-b643-0dc7722bfb40',
                                'text' => 'Наличие раны в грудной клетке, через которую во время вдоха засасывается воздух с характерным звуком',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '1252df35-f78c-4e9e-9cdb-ab607821a89b',
                                'text' => 'Кровотечение и выделение из раны в грудной клетке жидкости желтого цвета, одышка и повышение температуры тела',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2b69dcc9-016d-46e3-8650-bd0b39de3258',
                                'text' => 'Острая боль в области грудной клетки, выраженная слабость, затрудненное дыхание, сопровождающееся сильным кашлем',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '22af0671-3f8d-4ee5-808c-7795ed0d5f7a',
                        'number' => '20',
                        'text' => 'Какой должна быть продолжительность 1 выдоха при проведении искусственного дыхания?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '39e2d927-a59d-4942-8995-7550b72fd5d6',
                                'text' => '1 секунда',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '9407162a-f357-4874-a4f5-7c3c011fa429',
                                'text' => '2 секунды',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cb718c7b-d260-45c8-a198-b02be6bd579e',
                                'text' => '3 секунды',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c5b96453-b69a-4d3f-9fb4-124e0ef05f0e',
                                'text' => 'Не регламентируется',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'eabfa3ca-d0be-420d-a5f6-4e8618a1a40b',
                        'number' => '21',
                        'text' => 'Кто обязан организовывать оказание первой помощи пострадавшим при несчастном случае на производстве?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '4ac2902a-2424-4cc6-8c7b-cc300250cb9f',
                                'text' => 'Работодатель (его представитель)',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'b0154d6e-3945-46fb-8ebc-ca7a1440a261',
                                'text' => 'Медицинский работник, прибывший на место происшествия первым',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '5ec73509-3b29-4b4d-9e5e-971c2d2c3704',
                                'text' => 'Специалист по охране труда',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '85995777-99a5-4bf6-bbd8-5ee3406d8275',
                                'text' => 'Непосредственный руководитель пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'c5ca01aa-3155-4fe6-a02c-6b3ec15d95e4',
                        'number' => '22',
                        'text' => 'Что следует сделать во время оказания первой помощи пострадавшему с термическим ожогом?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '8f777ab6-7a5f-46a2-8933-5e19e07b45ab',
                                'text' => 'Наложить на пораженную поверхность гелевую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '507fd0ed-f48e-4b81-9994-8fbef8fcb5b8',
                                'text' => 'Охладить пораженную поверхность прохладной водой не менее 20 минут',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '09f3d5fd-8182-4b10-892f-410b8a9afe0e',
                                'text' => 'Обработать пораженную поверхность ранозаживляющей мазью',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '4e2356f1-d9c0-47ab-a54f-4f2021a2d8bc',
                                'text' => 'Вскрыть ожоговые пузыри',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '03da51ce-bd50-4d56-a320-924c0d14ba1d',
                        'number' => '23',
                        'text' => 'Каким должно быть соотношение количества надавливаний на грудную клетку пострадавшего и количества вдохов искусственного дыхания при проведении сердечно-легочной реанимации?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '7441e6cf-f51d-4c72-a4e3-79b212c3fe19',
                                'text' => '30 : 2',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cfb53f40-ee63-429f-af4a-29d9b1329058',
                                'text' => '15 : 2',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '56996752-15d7-49cd-b7e7-0f5543a840e4',
                                'text' => '15 : 1',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'b11cbb38-fb2b-46b9-840c-038643047031',
                                'text' => '60 : 4',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '7584ba3b-d84d-4d70-831e-84fa060f6943',
                        'number' => '24',
                        'text' => 'Установите соответствие между медицинскими изделиями, которыми укомплектована аптечка для оказания первой помощи работникам, и их назначением.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'a2e37aa0-272d-4381-a30b-128d76d1285e',
                                'text' => 'Маска медицинская нестерильная одноразовая-Применяется для снижения риска инфицирования человека, оказывающего первую помощь',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '608f1d00-a60d-4e41-91c6-f5c33efbf7d2',
                                'text' => 'Бинт марлевый медицинский-Предназначен для наложения различных повязок и фиксации травмированных конечностей',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '47a60252-6ae8-4f68-9c80-1748ba151068',
                                'text' => 'Жгут кровоостанавливающий-Предназначен для остановки артериального кровотечения',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'b1f06356-bd8a-48d7-9074-afffb690773a',
                                'text' => 'Лейкопластырь бактерицидный-Применяется для закрытия мелких ран и царапин',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e28e74dd-2490-40b8-bd91-141a15bbac4c',
                        'number' => '25',
                        'text' => 'Как следует выполнять иммобилизацию поврежденной конечности, за исключением травм плеча и бедра?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'a8b58e86-b483-4145-a43a-557fb36b77ca',
                                'text' => 'Путем обездвиживания 2 соседних суставов (одного ниже, другого выше перелома)',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bb03fc07-8a65-4c26-b25b-209b72475021',
                                'text' => 'Путем обездвиживания 1 сустава, расположенного выше места перелома',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '567300f2-b9fe-451e-8428-8d0a24026038',
                                'text' => 'Путем обездвиживания 1 сустава, расположенного ниже места перелома',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'd5d36c98-c483-42d6-8105-32063eef8c64',
                        'number' => '26',
                        'text' => 'Установите соответствие между травмами (состояниями) пострадавшего и оптимальными положениями тела при этих травмах (состояниях).',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '8dcf298b-dfdd-4f18-9cdc-75be69a67c5b',
                                'text' => 'Отсутствие сознания',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/06c51404-0198-4131-a0a3-58b72bec5779/1/1.jpg'
                            ),
                            array(
                                'id' => '61f5545f-39ba-429c-aac3-9764de3f126c',
                                'text' => 'Признаки кровопотери',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/06c51404-0198-4131-a0a3-58b72bec5779/1/2.jpg'
                            ),
                            array(
                                'id' => '68195ccd-e42b-4a37-bbc7-674787fa8a11',
                                'text' => 'Травма таза',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/06c51404-0198-4131-a0a3-58b72bec5779/1/3.jpg'
                            )
                        )
                    ),
                    array(
                        'id' => '9d95df47-a088-4fc9-b602-fe7ac50aeb34',
                        'number' => '27',
                        'text' => 'Что входит в перечень мероприятий по оказанию первой помощи?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '1d747f45-2998-48a1-9531-667dc2e94173',
                                'text' => 'Помощь пострадавшему в принятии лекарственных препаратов',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c94f58af-d971-4ce7-b0ab-52ff20036085',
                                'text' => 'Внутривенная инъекция обезболивающего препарата пострадавшему',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f4861765-7a04-4cdb-a5af-a20c57a59a0f',
                                'text' => 'Вправление костей при переломах или вывихах',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'acb8a26d-ebf5-4513-86ea-50ba05749fd2',
                                'text' => 'Наложение швов на раны',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '079bd8a3-21f9-41c5-9442-ac0e253a73aa',
                        'number' => '28',
                        'text' => 'Что относится к универсальным способам самопомощи в экстренных ситуациях в рамках психологической поддержки? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'b3043c87-61a2-46d6-b41e-9ed4575dc212',
                                'text' => 'Чередование глубокого и нормального дыхания',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cfba51ad-0e61-435f-a75d-c66a9e645191',
                                'text' => 'Прием седативных препаратов',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'ae383307-4e8a-40e8-beae-08f021f5de30',
                                'text' => 'Занятие физическим трудом',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '8771e4b0-e6d5-48fe-8ec1-10b8c15e6418',
                                'text' => 'Абстрагирование от окружающей обстановки в темноте и тишине',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e7f16de7-794b-4ca3-b4ae-d1898eb0aaab',
                        'number' => '29',
                        'text' => 'Что следует сделать в первую очередь во время оказания первой помощи при отравлении через дыхательные пути?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '5dd56c2f-489b-43a9-8737-c984d2c7c436',
                                'text' => 'Вынести или вывести пострадавшего на свежий воздух',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '759dc5d6-9e4f-4303-bca9-306a570d64f2',
                                'text' => 'Дважды промыть пострадавшему желудок',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '4a2d19af-21cf-49de-aad2-bb9d7a3e360f',
                                'text' => 'Дать пострадавшему обильное питье',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2d88b661-1231-4d2f-9b4a-8215c2007926',
                                'text' => 'Провести сердечно-легочную реанимацию',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '674c22cd-8d10-4fad-a78e-c5bdd37b5459',
                        'number' => '30',
                        'text' => 'В каком случае разрешается помочь пострадавшему принять лекарственные препараты?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '2dcb0c88-6d4b-489d-ab38-b1b090b04eb8',
                                'text' => 'Если лекарственный препарат принадлежит самому пострадавшему и назначен его лечащим врачом',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'a632aa61-6869-4141-bb27-5d437eaac120',
                                'text' => 'Если лекарственный препарат находится в аптечке',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '6490b797-6b98-49d5-bdd1-40f7fb9e8eaa',
                                'text' => 'Если прием лекарственного препарата возможен только внутривенно',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '020f56f1-39a0-43aa-9045-ad124992a317',
                        'number' => '31',
                        'text' => 'Что необходимо сделать во время оказания первой помощи пострадавшему при травме головы, сопровождающейся кровотечением, без повреждения костей черепа?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'c605c6c5-c272-4397-910e-b88b6370e6dd',
                                'text' => 'Остановить кровотечение прямым давлением на рану или наложением давящей повязки',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '67da0160-d89f-40bf-b7a8-286f3e3c2e85',
                                'text' => 'Дать пострадавшему таблетку анальгина и обеспечить покой',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '0426eabe-d857-4bb9-bfce-ee8c09b92a61',
                                'text' => 'Наложить кровоостанавливающий жгут на сонную артерию',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '52a52d02-d1cd-438a-9e25-d1fefb2ece6e',
                                'text' => 'Предложить пострадавшему горячий крепкий черный чай и обеспечить покой',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'cdebb756-b110-4af5-9c09-5f98af2a8847',
                        'number' => '32',
                        'text' => 'Установите соответствие между травмами (состояниями) пострадавшего и оптимальными положениями тела при этих травмах (состояниях).',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '6ae57d66-84eb-4ed8-8f99-1f72007d6ab9',
                                'text' => 'Тяжелые травмы живота',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/d4e11da8-d404-43b9-b22b-f0ef6b2c51bf/1/1.jpg'
                            ),
                            array(
                                'id' => '188ac6f9-d963-432f-852c-ebb1f083cd4e',
                                'text' => 'Травма грудной клетки',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/d4e11da8-d404-43b9-b22b-f0ef6b2c51bf/1/2.jpg'
                            ),
                            array(
                                'id' => 'e9ca3ae0-126e-4903-8127-95b7d90d511a',
                                'text' => 'Травма позвоночника',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/d4e11da8-d404-43b9-b22b-f0ef6b2c51bf/1/3.jpg'
                            )
                        )
                    ),
                    array(
                        'id' => '2d716d19-2a9d-40a0-b93d-8660fb4b1d7b',
                        'number' => '33',
                        'text' => 'Что необходимо сделать во время оказания первой помощи пострадавшему при травмах глаз?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '2f0f9e0e-c610-43f3-bdb0-394a3b495ad7',
                                'text' => 'Наложить повязку на оба глаза',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'dc20a972-0d0a-4082-a804-dfcf129e91ba',
                                'text' => 'Наложить повязку только на травмированный глаз',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '0dbdf29c-0162-4612-b37b-4cbeb4284d13',
                                'text' => 'Промыть глаз и удалить из него мелкие предметы (осколки)',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '9febd052-63bc-4b99-8626-035c292e6c83',
                                'text' => 'Обработать травмированный глаз антисептическими средствами и наложить повязку',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e17834f2-8f25-462f-bed2-d78ee209dea5',
                        'number' => '34',
                        'text' => 'Что необходимо сделать в первую очередь при оказании первой помощи пострадавшему, находящемуся без сознания?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '09ee9a05-5979-47b4-9482-64d0af59ab2a',
                                'text' => 'Провести подробный осмотр пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '77c651af-5b53-4817-a111-b8ef967dea85',
                                'text' => 'Восстановить проходимость дыхательных путей',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'c5eda339-32a9-4ea2-91b2-3562f35d595b',
                                'text' => 'Провести сердечно-легочную реанимацию',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '805ce4ac-0cf1-4fff-bdea-049471b31e01',
                                'text' => 'Придать пострадавшему устойчивое боковое положение',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1894c72c-5e3c-4592-9c91-96b26784bad4',
                        'number' => '35',
                        'text' => 'Какой жгут разрешается накладывать на голое тело?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '69249d76-dd78-4669-808d-3f54ef70e4c5',
                                'text' => 'Жгут-турникет',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd2757f3d-74fe-4489-93ec-748526f1ae47',
                                'text' => 'Табельный жгут',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '99066d47-a0ac-4f3d-a81b-c29395517634',
                                'text' => 'Импровизированный жгут из платка и металлического прута',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '7ef34c96-e717-4e6b-bf48-2fbed909f08f',
                        'number' => '36',
                        'text' => 'Чем характеризуются глубокие ожоги? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '16e0744f-b4c5-4283-91d2-6e6b43389102',
                                'text' => 'Небольшим покраснением кожи',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '8d9537b8-e413-45ea-a9f2-86e6a367baa5',
                                'text' => 'Наличием пузырей, заполненных прозрачной желтоватой жидкостью',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'eaf01228-97da-4a3b-9a1a-c32c73b7b869',
                                'text' => 'Наличием пузырей, заполненных кровянистым содержимым',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '709da7c1-8f90-447b-b227-ee699e510536',
                                'text' => 'Обугливанием кожи',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '9864c69b-8cc3-4bdf-a561-2597330c9070',
                        'number' => '37',
                        'text' => 'С какой частотой следует надавливать руками на грудину пострадавшего при проведении сердечно-легочной реанимации?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '45ed7f3b-105b-47ca-b52e-217fbc921802',
                                'text' => '100 - 120 надавливаний в минуту',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'e8eb20a0-9684-44f2-af81-5adba28dc361',
                                'text' => '60 - 80 надавливаний в минуту',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '8e85f603-8a1f-44ea-b8fe-65a7e0ba7c97',
                                'text' => '140 - 160 надавливаний в минуту',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '189be9a7-e441-4d1e-b65b-2a1162baa5ff',
                                'text' => '70 - 90 надавливаний в минуту',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1243a68c-0768-4a80-a3e9-e557637fa361',
                        'number' => '38',
                        'text' => 'Чем характеризуются поверхностные ожоги? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '01132ea7-031f-430d-9843-e71d29d43ead',
                                'text' => 'Наличием больших пузырей с кровянистым содержимым',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd7faf212-c891-4aca-bd0e-11d05fd06c52',
                                'text' => 'Интенсивным покраснением кожи, отеком',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '913e67d5-8d36-4e05-95c3-371e4c3560d0',
                                'text' => 'Наличием пузырей, заполненных прозрачной  жидкостью',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '653dd684-6889-4e04-9c4a-27e4852af710',
                                'text' => 'Обугливанием мягких тканей на большую глубину вплоть до костей',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1afa17ca-f2a7-49e8-b0bc-6f2922440dd1',
                        'number' => '39',
                        'text' => 'Что необходимо сделать во время оказания первой помощи при перегревании? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '9b9862a3-cd02-489f-a2e3-8d2e2637d606',
                                'text' => 'Переместить пострадавшего в прохладное место',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '6dcdda6d-4786-44fe-86a7-5b27b3b90e9b',
                                'text' => 'Приложить холод к голове и шее пострадавшего',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '46c72b10-27f1-4b41-960e-d6d467cae28b',
                                'text' => 'Окунуть пострадавшего в ледяную воду',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '83752813-fdab-436a-b421-f4ef3d96ed0c',
                                'text' => 'Растереть кожу пострадавшего или заставить его активно двигаться',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '49f10357-2463-4316-a791-fa54de9701e1',
                        'number' => '40',
                        'text' => 'Для чего предназначено спасательное изотермическое покрывало? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'f5ac990d-98f9-45a9-a9bb-9f69b7e7d980',
                                'text' => 'Для укутывания пострадавшего с тяжелой травмой',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd4c71434-675b-45fb-844e-7553bc7bfc55',
                                'text' => 'Для защиты от электрического тока',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'a87f3a44-401f-455b-9a22-0c10ef9a1f3c',
                                'text' => 'Для защиты кожи от химических веществ',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '8de603f0-3ed3-4ae4-a744-853a9d054b21',
                                'text' => 'Для сохранения тепла и согревания пострадавшего при переохлаждении',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'f23f1118-dca4-484e-b3a0-47428f165736',
                        'number' => '41',
                        'text' => 'На кого возлагается обязанность по организации обучения работников оказанию первой помощи?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '2963ec8a-003f-4091-a5e6-452f605faa14',
                                'text' => 'На специалиста по охране труда',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd2561005-da4b-49d5-b175-c4d377457230',
                                'text' => 'На работодателя',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bd2c681b-f8b8-41bf-9d7a-c35c063c9984',
                                'text' => 'На дежурного медицинского работника организации',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '6f932e80-6abb-4e1b-9ad8-96abbf6cb05a',
                        'number' => '42',
                        'text' => 'Что необходимо сделать, если наложение второй давящей повязки поверх первой не остановило кровотечение?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'bb13c3e6-018d-4dae-b148-009e9a5d6767',
                                'text' => 'Наложить кровоостанавливающий жгут',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'ee58c695-71ec-4e6c-98b3-6d047773388c',
                                'text' => 'Снять обе повязки и наложить давящую повязку заново',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'eba094cc-ab01-4104-9366-17df2b5e7f23',
                                'text' => 'Осуществить прямое давление на рану поверх повязки',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '3f683e84-730f-401d-a7f9-fdaa84e5696c',
                                'text' => 'Приложить холод к ране',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '7cb8e627-a3bd-41ca-8f96-df6dc721309a',
                        'number' => '43',
                        'text' => 'Как рекомендуется определять наличие сознания у пострадавшего?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'b71c01c5-be5e-4f1d-8bee-37e4a55e1eb7',
                                'text' => 'Вступить с пострадавшим в словесный и тактильный контакт, проверяя его реакцию',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '604aa83f-f133-43aa-89dc-b0085093654c',
                                'text' => 'Использовать резкий раздражитель (облить холодной водой, громко крикнуть)',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '15e0c7ca-98de-4143-b958-8d5a3a25be0b',
                                'text' => 'Проверить реакцию зрачков пострадавшего на свет',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'bb355294-3836-4899-a0a5-c51e07edfa76',
                        'number' => '44',
                        'text' => 'Что входит в комплект аптечки для оказания работниками первой помощи пострадавшим?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '17761e8c-7ecf-49b9-93bc-244e95542cbc',
                                'text' => 'Антигистаминные препараты',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '71c46b28-5490-4107-8bf7-0155d8ecce4f',
                                'text' => 'Автоматический наружный дефибриллятор',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '884b8056-0a74-4185-a558-89e31a83b396',
                                'text' => 'Мешок Амбу',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '2929409c-da1d-496a-9e2c-50e42d10c2a6',
                                'text' => 'Устройство для проведения искусственного дыхания "Рот - устройство - рот"',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '3c878bf3-1115-4061-854e-bd6d4890d578',
                        'number' => '45',
                        'text' => 'Установите соответствие между кровотечениями и их характеристикой.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'ab7d8c77-17d7-405d-8c88-69dba6ad9e81',
                                'text' => 'Артериальное-Пульсирующая алая струя крови, быстро расплывающаяся лужа крови алого цвета, быстро пропитывающаяся кровью одежда пострадавшего',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd959a8a2-ebfd-49b2-a446-72c0d0c7fd36',
                                'text' => 'Венозное-Меньшая скорость кровопотери, кровь темно-вишневая, вытекает ручьем',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '5fd6bdab-c9ea-400e-84ac-5dc40a8d019f',
                                'text' => 'Капиллярное-Наблюдается при ссадинах, порезах, царапинах, непосредственной угрозы для жизни, как правило, не представляет',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'f5d59823-20a3-47ba-9bfc-fbe17b438797',
                        'number' => '46',
                        'text' => 'Что необходимо сделать при носовом кровотечении?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'acd9da14-4f4c-47f5-9fa5-0dd67856bae0',
                                'text' => 'Наклонить голову вперед, сжать крылья носа',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '0abfefc1-6300-4584-849c-5e1e0ff10477',
                                'text' => 'Запрокинуть голову назад, сжать крылья носа',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '57608605-6688-45c3-b085-61c63d392a58',
                                'text' => 'Принять таблетку анальгина',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '688ffe65-6aac-4a9a-af73-63323d6c39f7',
                                'text' => 'Прижать сонную артерию',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '56891d7b-168f-454b-9f11-3d63a6b39c09',
                        'number' => '47',
                        'text' => 'Какое наказание предусматривается Уголовным кодексом Российской Федерации за неумышленное причинение вреда пострадавшему в ходе оказания первой помощи?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'e8ababcd-8d24-4e2a-bc2f-62a87c6290ba',
                                'text' => 'Наказание не предусматривается, если причиненный вред не был равным или более значительным, чем предотвращенный',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '9ad2d764-24c3-42d4-ae9f-7dbd14a1b627',
                                'text' => 'Исправительные работы на срок до 3 лет, либо ограничение свободы на срок до 3 лет, либо лишение свободы на тот же срок',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'e8206990-be9d-43bd-8c19-8985259ae30e',
                                'text' => 'Исправительные работы на срок до 2 лет, либо ограничение свободы на срок до 2 лет, либо лишение свободы на тот же срок',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f8f67e72-1d27-4bd7-bc3b-7e9af04c6a3c',
                                'text' => 'Исправительные работы на срок до 1 года, либо ограничение свободы на срок до 1 года, либо лишение свободы на тот же срок',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'db9113fa-1309-4e78-ab98-bcabd26b6bdc',
                        'number' => '48',
                        'text' => 'Как следует располагать электроды автоматического наружного дефибриллятора для проведения сердечно-легочной реанимации?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'fbf04837-7d3a-4155-b81c-4c55d9ada341',
                                'text' => 'Один электрод на правую часть груди под ключицей, второй - на левую половину груди',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '40069e71-35a5-4578-ba6f-e4e6debf2bc6',
                                'text' => 'Один электрод в области сердца, второй - в области сонной артерии',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'ffea7c1e-7ce0-4e97-8054-380819cb72c6',
                                'text' => 'Оба электрода на левой стороне грудной клетки: один выше, другой ниже области сердца',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '059479b3-9db2-49f3-86ba-34a48504d266',
                                'text' => 'Не регламентируется',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'f7e268c3-6749-467a-8877-d6757e207e5c',
                        'number' => '49',
                        'text' => 'Что входит в перечень состояний, при которых необходимо оказывать первую помощь?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'eaa5dcf3-b7b7-45e0-be2e-2590152ec319',
                                'text' => 'Обострение гастрита',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '802a1a5b-6c91-4122-9145-bfd914ca5e19',
                                'text' => 'Сезонный аллергический ринит',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '263f9ba2-40e4-474e-9edd-c3d9a7d10668',
                                'text' => 'Укус змеи',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '87485a36-00eb-4300-8cd7-dd6dc4ec5a9a',
                                'text' => 'Артериальная гипертония',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '2bcc890b-343a-4be6-b9ed-3cf8fd3364db',
                        'number' => '50',
                        'text' => 'Что относится к признакам перегрева? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '5519fa96-f1eb-446d-9bee-c66ec4cdc0bc',
                                'text' => 'Тошнота и рвота',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'a38dbfeb-e0b9-41b3-b6d9-ce2448a45a7a',
                                'text' => 'Головокружение',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '9c52aff9-b91c-401b-b3db-30ff0bc61c62',
                                'text' => 'Холодный пот',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '923cb293-0199-43ea-bc29-244741438e38',
                                'text' => 'Снижение частоты сердцебиения',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1531bd54-6433-43e0-8462-3dc0682e8f14',
                        'number' => '51',
                        'text' => 'Как следует оказывать первую помощь пострадавшему при отсутствии у него интенсивного кровотечения от инородного предмета в ране?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '8d7e4703-8325-48c1-b913-ffcf46cead51',
                                'text' => 'Извлечь инородный предмет из раны и наложить давящую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'a24dd76d-968d-4f22-b65b-2eaf2646a033',
                                'text' => 'Обложить края раны и инородное тело бинтами и наложить давящую повязку',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '96b8ecfb-957c-4cbf-b55a-cde2bcb4f876',
                                'text' => 'Оставить инородное тело в ране и ограничить двигательную активность пострадавшего',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '0a0bd8fb-323a-45da-b8e4-b6e94e8b1814',
                                'text' => 'Наложить кровоостанавливающий жгут',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'a55ab354-cb9e-4c2d-83d2-a004b7b9c946',
                        'number' => '52',
                        'text' => 'На что следует ориентироваться при принятии решения о проведении сердечно-легочной реанимации?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '96f51609-9497-4613-b24a-69e22b8c59b8',
                                'text' => 'На отсутствие у пострадавшего сознания и нормального дыхания',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => 'eb0a32ec-cec9-4555-8b72-02ec312b3613',
                                'text' => 'На отсутствие у пострадавшего пульса при наличии ровного дыхания',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '40e0d891-4922-4b71-a722-ae642f9c22bd',
                                'text' => 'На наличие у пострадавшего сильного кровотечения',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bbc7699b-d592-4766-89bd-49d5402638b6',
                                'text' => 'На отсутствие у пострадавшего реакции зрачков на свет',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '84bd1234-fe76-45ce-acea-8c0f91a12257',
                        'number' => '53',
                        'text' => 'Кто обязан оказывать первую помощь до оказания медицинской помощи? Выберите два правильных варианта ответа.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '1e240957-92d1-418d-af60-7cb13c80e10c',
                                'text' => 'Водители транспортных средств, ставшие свидетелями дорожно-транспортного происшествия',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f1d5b82e-7492-48a6-b3ef-5054181c2068',
                                'text' => 'Спасатели аварийно-спасательных формирований и аварийно-спасательных служб',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '8fd7d6f1-4429-4cea-ac04-c0e2b3f16854',
                                'text' => 'Люди, находящиеся вблизи места происшествия и первыми обнаружившие пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '4d6306b0-98e5-429c-8de9-bf93e0c83002',
                                'text' => 'Сотрудники, военнослужащие и работники Государственной противопожарной службы',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e00e48d8-c658-4227-a639-8e2c7c48c5b1',
                        'number' => '54',
                        'text' => 'Установите соответствие между травмами (состояниями) пострадавшего и рекомендуемыми способами его перемещения.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'a2b3aa8c-9225-45b3-922a-02ad8e1e2ad2',
                                'text' => 'Легкие травмы, пострадавший в сознании',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/80fb36c7-e279-48fd-a022-e06e62c93dba/1/1.jpg'
                            ),
                            array(
                                'id' => '12631548-bc14-490d-9896-8ccb3fc4982e',
                                'text' => 'Травмы позвоночника',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/80fb36c7-e279-48fd-a022-e06e62c93dba/1/2.jpg'
                            ),
                            array(
                                'id' => '5057a869-cdf6-45c7-a2af-47d04c12c097',
                                'text' => 'Пострадавший без сознания',
                                'isCorrect' => true,
                                'image' => 'https://olimpoks.hydroschool.ru/QuestionImages/80fb36c7-e279-48fd-a022-e06e62c93dba/1/3.jpg'
                            )
                        )
                    ),
                    array(
                        'id' => '1a6ff8ec-ce91-4a5e-95e4-17d6ee8f06f4',
                        'number' => '55',
                        'text' => 'По каким номерам телефона следует вызывать скорую медицинскую помощь?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '2e6b3d46-6f12-4bc3-b7c8-16b347d29edc',
                                'text' => '03, 103, 112',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '50cefdac-29f4-41d9-9dee-64273b5ffe6b',
                                'text' => '02, 102',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '1c4a4453-443c-4f03-8a24-35ddbba3fec4',
                                'text' => '04, 114',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'db06c45c-5675-4ad7-b7c2-a92d5ad8abf9',
                                'text' => '01, 101, 111',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '2e2ffe97-0ba4-41da-8b83-eca2918f2202',
                        'number' => '56',
                        'text' => 'Как следует транспортировать пострадавшего с подозрением на травму позвоночника?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'edb96653-d896-4c6a-9d08-b0f9f284533d',
                                'text' => 'Переносить на плече',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'cf8543ba-b6f2-46a4-964a-3134e4e33bd2',
                                'text' => 'Переносить методом Раутека',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '69c3dee6-1d02-4684-bae8-d1eca4a099b5',
                                'text' => 'Переносить вдвоем, используя замок из 4 рук',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '8b81b348-2844-4e08-a1b6-6cc2c2ad55f2',
                                'text' => 'Переносить методом "нидерландский мост"',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '4346098c-e28c-4010-9a0c-703ee4e7c310',
                        'number' => '57',
                        'text' => 'Что необходимо сделать, если пострадавший потерял сознание вследствие нарушения проходимости верхних дыхательных путей?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '4d365e68-c163-4b27-ab8e-6b5548a782d6',
                                'text' => 'Начать проводить сердечно-легочную реанимацию',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '081b84d0-e710-43a8-813b-6ada4be289f7',
                                'text' => 'Подручными средствами попытаться достать инородный предмет из дыхательных путей',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '03fb5f6f-6ff6-40bc-9d92-8a67c5d5be03',
                                'text' => 'Попытаться удалить инородный предмет путем надавливания на верхнюю часть живота пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '36ce7c72-e372-4973-9823-ceac5a19a612',
                                'text' => 'Нанести пострадавшему прекардиальный удар',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'ca5f1fc7-16d7-45ec-af25-386781231dd2',
                        'number' => '58',
                        'text' => 'Что необходимо сделать для оказания первой помощи пострадавшему с частичным нарушением проходимости дыхательных путей?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '3c75a447-4995-402a-aef2-e3cf2235522d',
                                'text' => 'Нанести 5 резких ударов ладонью между лопатками пострадавшего',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'd9bad780-7203-4ee7-9d77-9a7084944dea',
                                'text' => 'Предложить пострадавшему покашлять',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '0efb60e0-b8f0-450a-a1e3-90bbccaa5465',
                                'text' => 'Совершить 5 надавливаний кулаком на живот пострадавшего, расположив руку над его пупком',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '5de41182-025d-4922-8e9f-2f59607d418e',
                                'text' => 'Начать сердечно-легочную реанимацию',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '571f1f3e-7bdb-48ef-aab3-9abcbc47a9da',
                        'number' => '59',
                        'text' => 'Что необходимо сделать во время оказания первой помощи при отморожениях?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '60c86374-6942-4650-b56d-b7090c5d2c18',
                                'text' => 'Укутать отмороженные участки тела пострадавшего теплоизолирующим материалом',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '70701e51-4da4-47e5-b04e-5fdf43b3e088',
                                'text' => 'Растереть отмороженные участки тела пострадавшего снегом',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'f7778961-5e9a-49a5-81a6-e74cc17be0f0',
                                'text' => 'Поместить пострадавшего в воду с высокой температурой',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '9e0d79c9-f3a6-4e40-9260-76707cc7d0da',
                                'text' => 'Разместить пострадавшего у открытого огня и растереть отмороженные участки тела',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '691308a9-639d-4a68-b00c-9e6be23175db',
                        'number' => '60',
                        'text' => 'Что необходимо использовать для герметизации раны грудной клетки?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '419c2123-c5aa-4f11-be9c-2891023fc18d',
                                'text' => 'Воздухонепроницаемый материал',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '87cce4c5-41ed-4015-a124-38babbb73299',
                                'text' => 'Любую ткань',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => 'bbe8a0a8-e50e-4873-aaf8-c7ac2ca75c39',
                                'text' => 'Жгут',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '7f4f022f-da85-4b06-a0d4-39da931089b5',
                                'text' => 'Стерильную марлю',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => 'e5d79e2f-ab39-431c-8448-f4c749a2f508',
                        'number' => '61',
                        'text' => 'Что необходимо сделать при возникновении у пострадавшего судорожного приступа с потерей сознания?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => 'd3b90b2a-4a0e-4495-b0cc-ce048e2283c9',
                                'text' => 'Убрать от пострадавшего острые, бьющиеся предметы',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '4d6603a5-7f79-44ce-a029-9efeb7bac475',
                                'text' => 'Крепко держать пострадавшего для исключения его травмирования',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '14a8fb9f-a12d-4031-a7a6-58945f60420a',
                                'text' => 'Поместить ложку в рот пострадавшему',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '48e57a75-73d7-4cff-a3fe-b57698d72ef8',
                                'text' => 'Дать пострадавшему лекарственные препараты во время приступа',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '1634ee04-ebba-49bf-a170-4fb8e7c84519',
                        'number' => '62',
                        'text' => 'Установите правильную последовательность действий при подробном осмотре пострадавшего.',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '7b20d2c5-352c-4d6e-8253-fac96fe67f24',
                                'text' => 'Опросить пострадавшего',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '0f197fe0-11e6-4c8e-8f3d-9c444df990a7',
                                'text' => 'Осмотреть голову',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '8eb87e2b-9dab-4b93-b02d-8db1d0b6ed48',
                                'text' => 'Осмотреть шею',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '064859f9-2617-45ed-8e60-38ad1b4a1b10',
                                'text' => 'Осмотреть грудь и спину',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '4a4957ad-0e3d-4c75-91fd-21684437e67f',
                                'text' => 'Осмотреть живот и таз',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '5a048545-1291-4fb4-a403-d3e51a5dc1e3',
                                'text' => 'Осмотреть конечности',
                                'isCorrect' => true,
                                'image' => ''
                            )
                        )
                    ),
                    array(
                        'id' => '0247fc79-ad0f-4332-b91d-395e8ee1eae1',
                        'number' => '63',
                        'text' => 'Какому понятию соответствует определение: "Событие, в результате которого работник погиб или получил повреждение здоровья при выполнении им обязанностей по трудовому договору"?',
                        'image' => '',
                        'answers' => array(
                            array(
                                'id' => '43fbb00d-adef-4ddc-874e-b775dfb4c627',
                                'text' => 'Несчастный случай на производстве',
                                'isCorrect' => true,
                                'image' => ''
                            ),
                            array(
                                'id' => '4d904dc3-bb01-48cb-8b82-19ef01bad95c',
                                'text' => 'Инцидент на производстве',
                                'isCorrect' => false,
                                'image' => ''
                            ),
                            array(
                                'id' => '84a6576d-506e-41c6-a0f3-d2594ffdf733',
                                'text' => 'Авария на производстве',
                                'isCorrect' => false,
                                'image' => ''
                            )
                        )
                    )
                )
            ];

    }
}