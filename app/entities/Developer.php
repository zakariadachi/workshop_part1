<?php
declare(strict_types=1);

namespace App\Entities;

class Developer extends TeamMember
{
    public function canCreateProject(): bool {
         return false; 
    }
    public function canAssignTasks(): bool {
         return false; 
    }
    public function getRolePermissions(): array 
    {
        return ['work_on_tasks', 'view_projects'];
    }
}