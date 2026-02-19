<?php

namespace App\Access\Entity;

class View
{
    private int $maxViews = 3;
    private int $currentView;
    private function __construct(){
        $this->currentView = 0;
    }
    public static function create(): self
    {
        return new self();
    }
    public function getValue(): int
    {
        return $this->currentView;
    }
    public function getMaxViews(): int
    {
        return $this->maxViews;
    }
    public function increment(): void
    {
        if($this->currentView > $this->maxViews){
            throw new \DomainException('Превышено максимальное количество просмотров!');
        }
        $this->currentView++;
    }
    public function isAccessible(): bool
    {
        return $this->currentView < $this->maxViews;
    }
}