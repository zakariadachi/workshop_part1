<?php
declare(strict_types=1);

// Custom Autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    
    // On Windows, case doesn't matter for file paths, 
    // but we'll try to match PSR-4 by replacing backslash with forward slash.
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        // Fallback for case-sensitive systems (or just to be safe if directories are lowercase)
        // If the exact path doesn't exist, try lowercase directory names
        $parts = explode('\\', $relative_class);
        $filename = array_pop($parts) . '.php';
        $path = $base_dir . strtolower(implode('/', $parts)) . '/' . $filename;
        if (file_exists($path)) {
            require_once $path;
        }
    }
});

use App\Core\Database;
use App\Entities\{Developer, Manager, FeatureTask, BugTask};

echo "=== TASKFLOW PART 1: ARCHITECTURE VALIDATION ===\n\n";

// Test 1: Singleton Database
echo "1. Testing Singleton Database:\n";
try {
    $db1 = Database::getInstance();
    $db2 = Database::getInstance();
    
    if ($db1 === $db2) {
        echo "   PASS: Singleton works correctly (same instance)\n";
    } else {
        echo "FAIL: Singleton pattern broken (different instances)\n";
    }
} catch (Exception $e) {
    echo " FAIL: " . $e->getMessage() . "\n";
}

// Test 2: Inheritance Hierarchy
echo "\n2. Testing Inheritance Hierarchy:\n";
try {
    $developer = new Developer("john_dev", "john@company.com", "password123", 1);
    $manager = new Manager("jane_manager", "jane@company.com", "password123", 1);
    
    // Check inheritance
    if ($developer instanceof \App\Entities\TeamMember) {
        echo " PASS: Developer extends TeamMember\n";
    }
    
    if ($manager instanceof \App\Entities\TeamMember) {
        echo " PASS: Manager extends TeamMember\n";
    }
    
    // Check abstract methods
    echo "   Developer can create project: " . ($developer->canCreateProject() ? 'Yes' : 'No') . " (expected: No)\n";
    echo "   Manager can create project: " . ($manager->canCreateProject() ? 'Yes' : 'No') . " (expected: Yes)\n";
    
} catch (Exception $e) {
    echo "  FAIL: " . $e->getMessage() . "\n";
}

// Test 3: Task Hierarchy
echo "\n3. Testing Task Hierarchy:\n";
try {
    // FeatureTask constructor: string $title, string $description, int $projectId, int $reporterId, ...
    $featureTask = new FeatureTask("New Login Feature", "Implement OAuth login", 1, 1);
    $bugTask = new BugTask("Fix CSS Bug", "Button alignment issue", 1, 1);
    
    if ($featureTask instanceof \App\Entities\Task) {
        echo "  PASS: FeatureTask extends Task\n";
    }
    
    if ($bugTask instanceof \App\Entities\Task) {
        echo "  PASS: BugTask extends Task\n";
    }
    
    // Check interface implementation
    if ($featureTask instanceof \App\Interfaces\Assignable) {
        echo "  PASS: FeatureTask implements Assignable\n";
    }
    
    if ($bugTask instanceof \App\Interfaces\Prioritizable) {
        echo "  PASS: BugTask implements Prioritizable\n";
    }
    
} catch (Exception $e) {
    echo "   FAIL: " . $e->getMessage() . "\n";
}

// Test 4: Abstract Class Prevention
echo "\n4. Testing Abstract Class Instantiation Prevention:\n";
try {
    // This should fail
    // Using reflection or just trying to instantiate
    $reflection = new ReflectionClass(\App\Entities\Task::class);
    if ($reflection->isAbstract()) {
        echo "   ✅ PASS: Task class is abstract\n";
    } else {
        echo "  FAIL: Task class should be abstract\n";
    }
    
    try {
        $task = new \App\Entities\Task("Title", "Desc", 1, 1);
        echo "  FAIL: Should not be able to instantiate abstract Task class\n";
    } catch (Throwable $e) {
        echo "   ✅ PASS: Cannot instantiate abstract Task class\n";
    }
} catch (Exception $e) {
    echo "   FAIL: " . $e->getMessage() . "\n";
}

echo "\n=== VALIDATION COMPLETE ===\n";
echo "If all tests pass, you're ready for Part 2!\n";
