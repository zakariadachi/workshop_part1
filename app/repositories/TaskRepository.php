<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TaskRepository extends BaseRepository
{
    protected string $table = 'tasks';

    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET 
                    title = :title, 
                    description = :description, 
                    status = :status, 
                    priority = :priority, 
                    assignee_id = :assignee_id, 
                    project_id = :project_id,
                    due_date = :due_date,
                    estimated_hours = :estimated_hours,
                    task_type = :task_type,
                    reporter_id = :reporter_id
                WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $data['id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
                'priority' => $data['priority'],
                'assignee_id' => $data['assignee_id'],
                'project_id' => $data['project_id'],
                'due_date' => $data['due_date'] ?? null,
                'estimated_hours' => $data['estimated_hours'] ?? 0,
                'task_type' => $data['task_type'],
                'reporter_id' => $data['reporter_id']
            ]);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (title, description, status, priority, assignee_id, project_id, due_date, estimated_hours, task_type, reporter_id) 
             VALUES (:title, :description, :status, :priority, :assignee_id, :project_id, :due_date, :estimated_hours, :task_type, :reporter_id)"
        );
        return $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'priority' => $data['priority'],
            'assignee_id' => $data['assignee_id'],
            'project_id' => $data['project_id'],
            'due_date' => $data['due_date'] ?? null,
            'estimated_hours' => $data['estimated_hours'] ?? 0,
            'task_type' => $data['task_type'],
            'reporter_id' => $data['reporter_id']
        ]);
    }

    public function findByAssignee(int $assigneeId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE assignee_id = :assignee_id");
        $stmt->execute(['assignee_id' => $assigneeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByProject(int $projectId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE project_id = :project_id");
        $stmt->execute(['project_id' => $projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByStatus(string $status): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignTask(int $taskId, int $assigneeId): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET assignee_id = :assignee_id WHERE id = :id");
        return $stmt->execute([
            'id' => $taskId,
            'assignee_id' => $assigneeId
        ]);
    }
}
