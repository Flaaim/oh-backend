<?php

declare(strict_types=1);

namespace Test\Functional\Recipient\Add;

use App\Recipient\Entity\RecipientRepository;
use Test\Functional\WebTestCase;

/**
 * @internal
 */
final class RequestActionTest extends WebTestCase
{
    private RecipientRepository $recipients;
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
        $this->recipients = $this->container->get(RecipientRepository::class);

        $this->mailer()->clear();
    }
    public function testSuccess(): void
    {

        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/payment-webhook',
            $this->getRequestBody('access')
        ));

        self::assertEquals(204, $response->getStatusCode());

        $recipients = $this->recipients->findAll();

        self::assertEquals('test@app.ru', $recipients[0]->getEmail()->getValue());
    }

    public function testDuplicate(): void
    {
        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/payment-webhook',
            $this->getRequestBody('access')
        ));

        $response = $this->app()->handle(self::json(
            'POST',
            '/payment-service/payment-webhook',
            $this->getRequestBody('access')
        ));

        self::assertEquals(204, $response->getStatusCode());

        $recipients = $this->recipients->findAll();

        self::assertCount(1, $recipients);
    }

    private function getRequestBody(string $type): array
    {
        return [
            'type' => 'notification',
            'event' => 'payment.succeeded',
            'object' => [
                'id' => 'hook_test_payment_id',
                'status' => 'succeeded',
                'paid' => true,
                'amount' => [
                    'value' => '350.00',
                    'currency' => 'RUB',
                ],
                'income_amount' => [
                    'value' => '325.00',
                    'currency' => 'RUB',
                ],
                'recipient' => [
                    'account_id' => '221345',
                    'gateway_id' => '2093840',
                ],
                'created_at' => '2025-10-13T05:19:27.347Z',
                'captured_at' => '2025-10-13T05:20:00.000Z',
                'metadata' => [
                    'productId' => 'b38e76c0-ac23-4c48-85fd-975f32c8801f',
                    'cms_name' => 'yookassa_sdk_php_3',
                    'email' => 'recipient@app.ru',
                    'type' => $type,
                ],
            ],
        ];
    }
}
