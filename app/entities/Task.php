<?php

    abstract class Task implements Assignable, Prioritizable, Commentable
    {
        protected int $id;
        protected string $title;
        protected string $description;
        protected int $projectId;
        protected int $assigneeId;
        protected int $reporterId;
        protected string $priority;
        protected string $status;
        protected int $estimatedHour;
        protected int $actualHour;
        protected DateTime $dueDate;
        protected DateTime $createdAt;
        protected DateTime $updatedAt;

        public function __construct(int $id,string $title,string $description,int $projectId,int $assigneeId,int $reporterId,string $priority,string $status,int $estimatedHour,int $actualHour,DateTime $dueDate) 
        {
            $this->id = $id;
            $this->title = $title;
            $this->description = $description;
            $this->projectId = $projectId;
            $this->assigneeId = $assigneeId;
            $this->reporterId = $reporterId;
            $this->priority = $priority;
            $this->status = $status;
            $this->estimatedHour = $estimatedHour;
            $this->actualHour = $actualHour;
            $this->dueDate = $dueDate;
            $this->createdAt = new DateTime();
            $this->updatedAt = new DateTime();
        }

        abstract function calculateComplexity();
        
        abstract function getRequiredSkills();
    }
