<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TeamRepository extends BaseRepository
{
    protected string $table = 'teams';

    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id"
            );
            return $stmt->execute($data);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)"
        );
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }
}
