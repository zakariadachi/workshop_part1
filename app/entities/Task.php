<?php
declare(strict_types=1);

namespace App\Entities;

use DateTime;
use App\Interfaces\Assignable;
use App\Interfaces\Prioritizable;
use App\Interfaces\Commentable;

abstract class Task implements Assignable, Prioritizable, Commentable
{
    protected int $id;
    protected string $title;
    protected string $description;
    protected int $projectId;
    protected ?int $assigneeId = null;
    protected int $reporterId;
    protected string $priority;
    protected string $status;
    protected float $estimatedHours;
    protected float $actualHours;
    protected DateTime $dueDate;
    protected DateTime $createdAt;
    protected DateTime $updatedAt;
    protected array $comments = [];

    public function __construct(
        string $title,
        string $description,
        int $projectId,
        int $reporterId,
        string $priority = 'medium',
        string $status = 'todo',
        float $estimatedHours = 0.0,
        ?int $id = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->projectId = $projectId;
        $this->reporterId = $reporterId;
        $this->priority = $priority;
        $this->status = $status;
        $this->estimatedHours = $estimatedHours;
        $this->actualHours = 0.0;
        $this->id = $id ?? 0;
        $this->dueDate = new DateTime('+7 days');
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    abstract public function calculateComplexity(): string;
    abstract public function getRequiredSkills(): array;

    // Assignable implementation
    public function assignTo(int $userId): void {
        $this->assigneeId = $userId; 
        }
    public function unassign(): void {
        $this->assigneeId = null; 
    }
    public function isAssigned(): bool { 
        return $this->assigneeId !== null; 
    }
    public function getAssigneeId(): ?int {
         return $this->assigneeId; 
    }

    // Prioritizable implementation
    public function setPriority(string $priority): void {
        $this->priority = $priority; 
    }
    public function getPriority(): string {
        return $this->priority; 
        }
    public function isHighPriority(): bool {
         return in_array($this->priority, ['high', 'critical']); 
    }
    public function getPriorityLevel(): int 
    {
        return match($this->priority) {
            'low' => 1,
            'medium' => 2,
            'high' => 3,
            'critical' => 4,
            default => 0
        };
    }

    // Commentable implementation
    public function addComment(string $comment): void {
        $this->comments[] = $comment; 
    }
    public function getComments(): array {
         return $this->comments;
    }
    public function clearComments(): void {
         $this->comments = []; 
    }
    public function getCommentCount(): int {
        return count($this->comments); 
    }

    // Getters and Setters
    public function getId(){
        return $this->id; 
    }
    public function getTitle(){ 
        return $this->title; 
    }
    public function getStatus(){
        return $this->status; 
    }
    public function setStatus(string $status){
        $this->status = $status; 
    }
}
