<?php

namespace App\Shared\Domain\Response;

use App\Http\HtmlResponse;
use App\Http\JsonResponse;
use App\Http\YamlResponse;
use Slim\Psr7\Response;
use Twig\Environment;
use Webmozart\Assert\Assert;

class TicketResponseFactory
{
    public const ALLOWED_TYPES = [
        'json',
        'html',
        'yaml',
    ];
    private string $type;
    private ?Environment $twig = null;
    public function __construct(string $type, ?Environment $twig = null)
    {
        Assert::oneOf($type, self::ALLOWED_TYPES, sprintf('Type %s not provided', $type));
        if ($type === 'html' && $twig === null) {
            throw new \InvalidArgumentException('Twig is required for HTML responses');
        }
        $this->type = $type;
        $this->twig = $twig;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function createResponse(TicketResponse $ticket): Response
    {
        return match ($this->type) {
            'json' => new JsonResponse($ticket),
            'yaml' => new YamlResponse($ticket->yamlSerialize()),
            'html' => new HtmlResponse(
                $this->twig->render('/parser/ticket.html.twig', ['response' => $ticket])
            ),
            default => throw new \RuntimeException('Unknown response type')
        };
    }
}
