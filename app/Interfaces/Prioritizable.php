<?php
namespace App\Interfaces;


interface Prioritizable
{
    public function setPriority(string $priority): void;
    public function getPriority(): string;
    public function isHighPriority(): bool;
    public function getPriorityLevel(): int;
}
