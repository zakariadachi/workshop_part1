<?php
declare(strict_types=1);

namespace App\Entities;

class Manager extends TeamMember
{
    public function canCreateProject(): bool {
        return true; 
    }
    public function canAssignTasks(): bool {
         return true; 
    }
    public function getRolePermissions(): array 
    {
        return ['create_projects', 'assign_tasks', 'view_all_tasks'];
    }
}