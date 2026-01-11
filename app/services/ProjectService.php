<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Repositories\TeamMemberRepository;
use App\Repositories\TaskRepository;
use Exception;

class ProjectService
{
    private ProjectRepository $projectRepo;
    private TeamMemberRepository $memberRepo;
    private TaskRepository $taskRepo;

    public function __construct()
    {
        $this->projectRepo = new ProjectRepository();
        $this->memberRepo = new TeamMemberRepository();
        $this->taskRepo = new TaskRepository();
    }

    public function createProject(array $data, int $creatorId): bool
    {
        $creator = $this->memberRepo->find($creatorId);
        if (!$creator || !in_array($creator['role'], ['manager', 'admin'])) {
            throw new Exception("Only managers and admins can create projects.");
        }

        if (empty($data['team_id'])) {
            throw new Exception("Project must belong to a team.");
        }

        return $this->projectRepo->save($data);
    }

    public function getProjectStats(int $projectId): array
    {
        $tasks = $this->taskRepo->findByProject($projectId);
        $total = count($tasks);
        $done = count(array_filter($tasks, fn($t) => $t['status'] === 'done'));
        
        return [
            'total_tasks' => $total,
            'completed_tasks' => $done,
            'completion_rate' => $total > 0 ? round(($done / $total) * 100, 2) : 0
        ];
    }

    public function archiveProject(int $projectId): bool
    {
        if ($this->projectRepo->hasActiveTasks($projectId)) {
            throw new Exception("Cannot archive project with active tasks.");
        }

        $project = $this->projectRepo->find($projectId);
        if (!$project) {
            throw new Exception("Project not found.");
        }

        $project['status'] = 'archived';
        return $this->projectRepo->save($project);
    }

    public function deleteProject(int $projectId): bool
    {
        if ($this->projectRepo->hasActiveTasks($projectId)) {
            throw new Exception("Cannot delete project with active tasks.");
        }

        return $this->projectRepo->delete($projectId);
    }
}
