<?php
declare(strict_types=1);

namespace App\Entities;

class BugTask extends Task
{
    public function calculateComplexity(): string
    {
        return $this->priority === 'critical' ? 'High' : 'Medium';
    }

    public function getRequiredSkills(): array
    {
        return ['debugging', 'diagnostics', 'regression_testing'];
    }
}
