<?php
namespace App\Interfaces;

interface Assignable
{
    public function assignTo(int $userId): void;
    public function unassign(): void;
    public function isAssigned(): bool;
    public function getAssigneeId(): ?int;
}