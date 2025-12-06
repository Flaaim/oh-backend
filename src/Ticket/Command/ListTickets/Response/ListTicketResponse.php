<?php

namespace App\Ticket\Command\ListTickets\Response;

use App\Ticket\Entity\PaginatedResults;

class ListTicketResponse implements \JsonSerializable
{
    public function __construct(
        public readonly array $items,
        public readonly int $totalItems,
        public readonly int $currentPage,
        public readonly int $perPage,
        public readonly int $totalPages
    )
    {}

    public static function fromResult($result): self
    {
        /** @var PaginatedResults $result */
        return new self(
            $result->getResults(),
            $result->getTotalItems(),
            $result->getCurrentPage(),
            $result->getPerPage(),
            $result->getTotalPages()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'items' => array_map(
                fn($ticket) => [
                    'id' => $ticket->getId()->getValue(),
                    'name' => $ticket->getName(),
                    'cipher' => $ticket->getCipher(),
                ],
                $this->items,
            ),
            'totalItems' => $this->totalItems,
            'currentPage' => $this->currentPage,
            'perPage' => $this->perPage,
        ];
    }

}
