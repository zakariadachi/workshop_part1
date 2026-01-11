<?php
declare(strict_types=1);

namespace App\Entities;

class Administrator extends TeamMember
{
    public function canCreateProject(): bool { 
        return true; 
    }
    public function canAssignTasks(): bool { 
        return true; 
    }
    public function getRolePermissions(): array 
    {
        return ['manage_teams', 'manage_users', 'all_permissions'];
    }
}