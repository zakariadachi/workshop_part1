<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ProjectRepository extends BaseRepository
{
    protected string $table = 'projects';

    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET name = :name, description = :description, team_id = :team_id, status = :status WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $data['id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'team_id' => $data['team_id'],
                'status' => $data['status']
            ]);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, description, team_id, status) VALUES (:name, :description, :team_id, :status)"
        );
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'team_id' => $data['team_id'],
            'status' => $data['status']
        ]);
    }

    public function hasActiveTasks(int $projectId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM tasks WHERE project_id = :project_id AND status != 'done'");
        $stmt->execute(['project_id' => $projectId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
