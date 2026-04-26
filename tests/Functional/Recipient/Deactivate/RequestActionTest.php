<?php

namespace Test\Functional\Recipient\Deactivate;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\RecipientRepository;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{

    private RecipientRepository $recipients;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
        $this->recipients = $this->container->get(RecipientRepository::class);
    }
    public function testSpamBlock(): void
    {
        $response = $this->app()->handle(self::json(
            'POST', '/payment-service/recipients/unsubscribe', $this->requestSpamBLocData())
        );

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json(
            'POST', '/payment-service/recipients/unsubscribe'));

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json(
            'POST', '/payment-service/recipients/unsubscribe', $this->requestSuccessData())
        );

        $this->assertEquals(200, $response->getStatusCode());

        $recipient = $this->recipients->findByEmail(new Email('unsubscribed@app.ru'));
        self::assertFalse($recipient->isActive());

    }
    private function requestSpamBLocData(): array
    {
        return [
            'auth' => getenv('UNI_SENDER_API'),
            'events_by_user' => [
                [
                    'user_id' => 456,
                    'project_id' => '6432890213745872',
                    'project_name' => 'MyProject',
                    'events' => [
                        [
                            'event_name' => 'transactional_spam_block',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'block_time' => '2015-11-30 15:09:42',
                                    'block_type' => 'one_smtp',
                                    'domain' => 'domain.com',
                                    "SMTP_blocks_count" => 8,
                                    "domain_status" => "blocked",
                                ]
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }

    private function requestSuccessData(): array
    {
        return [
            'auth' => getenv('UNI_SENDER_API'),
            'events_by_user' => [
                [
                    'user_id' => 456,
                    'project_id' => '6432890213745872',
                    'project_name' => 'MyProject',
                    'events' => [
                        [
                            'event_name' => 'transactional_email_status',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'key1' => 'val1',
                                    'key2' => 'val2'
                                ],
                                'email' => 'unsubscribed@app.ru',
                                'status' => 'unsubscribed',
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111'
                                ]
                            ]
                        ],
                        [
                            'event_name' => 'transactional_email_status',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'key1' => 'val1',
                                    'key2' => 'val2'
                                ],
                                'email' => 'sent@app.ru',
                                'status' => 'sent',
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111'
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

}