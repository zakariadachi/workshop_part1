<?php
declare(strict_types=1);

namespace App\Entities;

use DateTime;

abstract class TeamMember
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected int $teamId;
    protected DateTime $createdAt;

    public function __construct(string $username, string $email, string $password, int $teamId, ?int $id = null)
    {
        $this->username = $username;
        $this->email = $email;
        $this->setPassword($password);
        $this->teamId = $teamId;
        $this->id = $id ?? 0;
        $this->createdAt = new DateTime();
    }

    abstract public function canCreateProject(): bool;
    abstract public function canAssignTasks(): bool;
    abstract public function getRolePermissions(): array;

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getId()
    {
         return $this->id; 
    }
    public function getUsername(){
        return $this->username; 
    }
    public function getEmail(){
         return $this->email; 
    }
    public function getTeamId(){
         return $this->teamId; 
    }
    public function getCreatedAt(){ 
        return $this->createdAt;
    }
}