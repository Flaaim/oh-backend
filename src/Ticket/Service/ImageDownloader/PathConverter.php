<?php

namespace App\Ticket\Service\ImageDownloader;

use App\Ticket\Entity\Ticket;


class PathConverter
{
    public function __construct(private readonly UrlBuilder $urlBuilder){}

    public function convertQuestionImages(Ticket $ticket, array $results): self
    {
        foreach ($results as $result) {
            if($result['status'] === 'success') {
                $ticket->updateQuestionImagesUrl(
                    $result['question_id'],
                    $this->urlBuilder->buildNewQuestionUrl($result['path'])
                );
            }
        }
        return $this;
    }
    public function convertAnswerImages(Ticket $ticket, array $results): self
    {

        foreach ($results as $result) {
            if($result['status'] === 'success') {
                $ticket->updateAnswerImagesUrl(
                    $result['answer_id'],
                    $this->urlBuilder->buildNewQuestionUrl($result['path'])
                );
            }
        }
        return $this;
    }
}
