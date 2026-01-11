<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

class TeamMemberRepository extends BaseRepository
{
    protected string $table = 'team_members';

    public function save(array $data): bool
    {
        if (isset($data['id'])) {
            $stmt = $this->db->prepare(
                "UPDATE {$this->table} SET username = :username, email = :email, role = :role, team_id = :team_id WHERE id = :id"
            );
            return $stmt->execute([
                'id' => $data['id'],
                'username' => $data['username'],
                'email' => $data['email'],
                'role' => $data['role'],
                'team_id' => $data['team_id']
            ]);
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (username, email, password_hash, role, team_id) 
             VALUES (:username, :email, :password_hash, :role, :team_id)"
        );
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
            'role' => $data['role'],
            'team_id' => $data['team_id']
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
