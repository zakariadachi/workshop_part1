<?php
declare(strict_types=1);

namespace App\Entities;

class Tester extends TeamMember
{
    public function canCreateProject(): bool {
         return false; 
    }
    public function canAssignTasks(): bool { 
        return false; 
    }
    public function getRolePermissions(): array 
    {
        return ['test_tasks', 'review_tasks', 'view_projects'];
    }
}