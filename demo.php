<?php
declare(strict_types=1);

require_once 'app/Core/Database.php';
require_once 'app/Core/Validator.php';
require_once 'app/Repositories/BaseRepository.php';
require_once 'app/Repositories/TeamRepository.php';
require_once 'app/Repositories/TeamMemberRepository.php';
require_once 'app/Repositories/ProjectRepository.php';
require_once 'app/Repositories/TaskRepository.php';
require_once 'app/Services/TaskService.php';
require_once 'app/Services/ProjectService.php';

use App\Services\TaskService;
use App\Services\ProjectService;
use App\Repositories\{TeamRepository, TeamMemberRepository, TaskRepository, ProjectRepository};

echo "=== TASKFLOW PART 2: WORKING DEMO ===\n\n";

try {
    $taskService = new TaskService();
    $projectService = new ProjectService();
    $teamRepo = new TeamRepository();
    $memberRepo = new TeamMemberRepository();
    $projectRepo = new ProjectRepository();
    $taskRepo = new TaskRepository();

    echo "1. Creating Team and Members...\n";
    $teams = $teamRepo->findAll();
    $alphaTeam = null;
    foreach ($teams as $t) {
        if ($t['name'] === 'Alpha Team') $alphaTeam = $t;
    }

    if (!$alphaTeam) {
        $teamRepo->save(['name' => 'Alpha Team', 'description' => 'Frontend development team']);
        $teams = $teamRepo->findAll();
        foreach ($teams as $t) if ($t['name'] === 'Alpha Team') $alphaTeam = $t;
    }
    echo "   Team 'Alpha Team' ready (ID: {$alphaTeam['id']})\n";

    // Ensure we have a manager and a developer
    $manager = $memberRepo->findByEmail('charlie_manager@demo.com');
    if (!$manager) {
        $memberRepo->save([
            'username' => 'charlie_manager',
            'email' => 'charlie_manager@demo.com',
            'password_hash' => password_hash('pass123', PASSWORD_DEFAULT),
            'role' => 'manager',
            'team_id' => $alphaTeam['id']
        ]);
        $manager = $memberRepo->findByEmail('charlie_manager@demo.com');
    }

    $developer = $memberRepo->findByEmail('alice_dev@demo.com');
    if (!$developer) {
        $memberRepo->save([
            'username' => 'alice_dev',
            'email' => 'alice_dev@demo.com',
            'password_hash' => password_hash('pass123', PASSWORD_DEFAULT),
            'role' => 'developer',
            'team_id' => $alphaTeam['id']
        ]);
        $developer = $memberRepo->findByEmail('alice_dev@demo.com');
    }
    echo "   Users ready: Charlie (Manager, ID: {$manager['id']}), Alice (Developer, ID: {$developer['id']})\n";

    echo "\n2. Creating Project...\n";
    // Create project as manager
    $projectId = null;
    $projects = $projectRepo->findAll();
    foreach($projects as $p) {
        if ($p['name'] === 'Website Redesign') $projectId = $p['id'];
    }

    if (!$projectId) {
        $projectService->createProject([
            'name' => 'Website Redesign',
            'description' => 'Complete website overhaul',
            'team_id' => $alphaTeam['id'],
            'status' => 'active'
        ], (int)$manager['id']);
        
        $projects = $projectRepo->findAll();
        foreach($projects as $p) if ($p['name'] === 'Website Redesign') $projectId = $p['id'];
    }
    echo "   Project 'Website Redesign' ready (ID: $projectId)\n";

    echo "\n3. Creating Tasks...\n";
    // Create a feature task
    $taskService->createTask([
        'title' => 'Implement Login',
        'description' => 'User OAuth integration',
        'status' => 'todo',
        'priority' => 'high',
        'assignee_id' => null,
        'project_id' => $projectId,
        'task_type' => 'feature',
        'reporter_id' => $manager['id']
    ]);
    
    // Create a critical task with due date
    $taskService->createTask([
        'title' => 'Fix Security Hole',
        'description' => 'Critical CSRF fix',
        'status' => 'todo',
        'priority' => 'critical',
        'assignee_id' => null,
        'project_id' => $projectId,
        'due_date' => date('Y-m-d', strtotime('+1 day')),
        'task_type' => 'bug',
        'reporter_id' => $manager['id']
    ]);
    echo "   Tasks created successfully.\n";

    echo "\n4. Assigning Tasks...\n";
    $allTasks = $taskRepo->findByProject((int)$projectId);
    $taskToAssign = $allTasks[0];
    $taskService->assignTask((int)$taskToAssign['id'], (int)$developer['id'], (int)$manager['id']);
    echo "   Task '{$taskToAssign['title']}' assigned to Alice by Charlie.\n";

    echo "\n5. Testing Business Rules...\n";

    // Rule 1: Only managers can assign tasks
    try {
        echo "   Testing: Developer assigning task... ";
        $taskService->assignTask((int)$taskToAssign['id'], (int)$manager['id'], (int)$developer['id']);
        echo "FAIL (Should have thrown exception)\n";
    } catch (Exception $e) {
        echo "PASS (Rule enforced: " . $e->getMessage() . ")\n";
    }

    // Rule 2: Critical tasks must have due dates
    try {
        echo "   Testing: Critical task without due date... ";
        $taskService->createTask([
            'title' => 'Missing Due Date',
            'description' => 'Should fail',
            'status' => 'todo',
            'priority' => 'critical',
            'assignee_id' => null,
            'project_id' => $projectId,
            'task_type' => 'bug',
            'reporter_id' => $manager['id']
        ]);
        echo "FAIL (Should have thrown exception)\n";
    } catch (Exception $e) {
        echo "PASS (Rule enforced: " . $e->getMessage() . ")\n";
    }

    // Rule 3: Cannot delete project with active tasks
    try {
        echo "   Testing: Deleting project with active tasks... ";
        $projectService->deleteProject((int)$projectId);
        echo "FAIL (Should have thrown exception)\n";
    } catch (Exception $e) {
        echo "PASS (Rule enforced: " . $e->getMessage() . ")\n";
    }

    echo "\n6. Generating Reports...\n";
    $stats = $projectService->getProjectStats((int)$projectId);
    echo "   Project Statistics for 'Website Redesign':\n";
    echo "   - Total Tasks: {$stats['total_tasks']}\n";
    echo "   - Completed Tasks: {$stats['completed_tasks']}\n";
    echo "   - Completion Rate: {$stats['completion_rate']}%\n";

} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
}

echo "\n=== DEMO COMPLETE ===\n";
