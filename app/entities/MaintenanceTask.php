<?php
declare(strict_types=1);

namespace App\Entities;

class MaintenanceTask extends Task
{
    public function calculateComplexity(): string
    {
        return 'Low';
    }

    public function getRequiredSkills(): array
    {
        return ['updates', 'optimization', 'server_management'];
    }
}
