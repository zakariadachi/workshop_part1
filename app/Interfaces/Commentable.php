<?php
namespace App\Interfaces;


interface Commentable
{
    public function addComment(string $comment): void;
    public function getComments(): array;
    public function clearComments(): void;
    public function getCommentCount(): int;
}
