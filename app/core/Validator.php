<?php
declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validateTaskPriority(string $priority): bool
    {
        return in_array($priority, ['low', 'medium', 'high', 'critical']);
    }
    
    public static function validateTaskStatus(string $status): bool
    {
        return in_array($status, ['todo', 'in_progress', 'in_review', 'done']);
    }

    public static function validatePositiveInt(int $value): bool
    {
        return $value > 0;
    }
}
