<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\TeamMemberRepository;
use App\Core\Validator;
use Exception;

class TaskService
{
    private TaskRepository $taskRepo;
    private TeamMemberRepository $memberRepo;

    public function __construct()
    {
        $this->taskRepo = new TaskRepository();
        $this->memberRepo = new TeamMemberRepository();
    }

    public function createTask(array $data): bool
    {
        if (!Validator::validateTaskPriority($data['priority'])) {
            throw new Exception("Invalid task priority.");
        }

        if ($data['priority'] === 'critical' && (empty($data['due_date']) || $data['due_date'] === null)) {
            throw new Exception("Critical tasks must have due dates.");
        }

        if (isset($data['estimated_hours']) && !Validator::validatePositiveInt((int)$data['estimated_hours'])) {
            throw new Exception("Estimated hours must be positive.");
        }

        return $this->taskRepo->save($data);
    }

    public function assignTask(int $taskId, int $assigneeId, int $managerId): bool
    {
        $manager = $this->memberRepo->find($managerId);
        if (!$manager || $manager['role'] !== 'manager') {
            throw new Exception("Only managers can assign tasks.");
        }

        return $this->taskRepo->assignTask($taskId, $assigneeId);
    }

    public function updateStatus(int $taskId, string $newStatus): bool
    {
        $task = $this->taskRepo->find($taskId);
        if (!$task) {
            throw new Exception("Task not found.");
        }

        if (!Validator::validateTaskStatus($newStatus)) {
            throw new Exception("Invalid task status.");
        }


        if ($task['status'] === 'done' && $newStatus !== 'done') {
            throw new Exception("Cannot reopen a completed task.");
        }

        $task['status'] = $newStatus;
        return $this->taskRepo->save($task);
    }

    public function logHours(int $taskId, int $hours): bool
    {
        if ($hours <= 0) {
            throw new Exception("Logged hours must be positive.");
        }

        $task = $this->taskRepo->find($taskId);
        if (!$task) {
            throw new Exception("Task not found.");
        }

        // For demo, we just update the estimated_hours as a total logged time
        $task['estimated_hours'] = ($task['estimated_hours'] ?? 0) + $hours;
        return $this->taskRepo->save($task);
    }
}
