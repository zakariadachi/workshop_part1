<?php
declare(strict_types=1);

namespace App\Entities;

class FeatureTask extends Task
{
    public function calculateComplexity(): string
    {
        return $this->estimatedHours > 20 ? 'High' : 'Medium';
    }

    public function getRequiredSkills(): array
    {
        return ['development', 'design', 'unit_testing'];
    }
}