<?php

declare(strict_types=1);

namespace Test\Functional\Recipient\Deactivate;

use App\Recipient\Entity\Email;
use App\Recipient\Entity\RecipientRepository;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
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
    }
    public function testSpamBlock(): void
    {
        $events = [
            [
                'user_id' => 456,
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
                            ],
                        ],
                    ],
                ],
            ],
        ];
        $response = $this->app()->handle($this->createValidWebhookRequest($events, getenv('UNI_SENDER_API')));

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle($this->createValidWebhookRequest([]));

        self::assertEquals(403, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $events = [
            [
                'user_id' => 456,
                'events' => [
                    [
                        'event_name' => 'transactional_email_status',
                        'event_data' => [
                            'email' => 'unsubscribed@app.ru',
                            'status' => 'unsubscribed',
                        ],
                    ],
                    [
                        'event_name' => 'transactional_email_status',
                        'event_data' => [
                            'email' => 'sent@app.ru',
                            'status' => 'sent',
                        ],
                    ],
                ],
            ],
        ];
        $response = $this->app()->handle($this->createValidWebhookRequest($events, getenv('UNI_SENDER_API')));

        self::assertEquals(200, $response->getStatusCode());

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
                                ],
                            ],
                        ],
                    ],
                ],
            ],
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
                                    'key2' => 'val2',
                                ],
                                'email' => 'unsubscribed@app.ru',
                                'status' => 'unsubscribed',
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111',
                                ],
                            ],
                        ],
                        [
                            'event_name' => 'transactional_email_status',
                            'event_data' => [
                                'job_id' => '1a3Q2V-0000OZ-S0',
                                'metadata' => [
                                    'key1' => 'val1',
                                    'key2' => 'val2',
                                ],
                                'email' => 'sent@app.ru',
                                'status' => 'sent',
                                'event_time' => '2015-11-30 15:09:42',
                                'url' => 'http://some.url.com',
                                'delivery_info' => [
                                    'delivery_status' => 'err_delivery_failed',
                                    'destination_response' => '550 Spam rejected',
                                    'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
                                    'ip' => '111.111.111.111',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }


    private function createValidWebhookRequest(array $eventsByUser, string $apiKey = 'test-auth'): ServerRequestInterface
    {
        $initialData = [
            'auth' => $apiKey,
            'events_by_user' => $eventsByUser,
        ];
        $jsonStringWithKey = json_encode($initialData);
        $validHash = md5($jsonStringWithKey);
        $finalRawBody = str_replace($apiKey, $validHash, $jsonStringWithKey);
        $finalParsedBody = json_decode($finalRawBody, true);

        $request = (new ServerRequestFactory())->createServerRequest('POST', '/payment-service/recipients/unsubscribe');

        $request->getBody()->write($finalRawBody);
        $request->getBody()->rewind();

        $request = $request->withParsedBody($finalParsedBody);
        return $request;
    }
}
