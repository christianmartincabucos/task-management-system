<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
    }

    public function test_create_task()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'status' => 'pending',
            'priority' => 'medium',
            'order' => 1,
            'user_id' => 1,
        ];

        $task = $this->taskRepository->create($taskData);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
        ]);
    }

    public function test_update_task()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $updatedData = [
            'status' => 'completed',
        ];

        $this->taskRepository->update($task->id, $updatedData);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }

    public function test_delete_task()
    {
        $task = Task::factory()->create();

        $this->taskRepository->delete($task->id);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_reorder_tasks()
    {
        $task1 = Task::factory()->create(['order' => 1]);
        $task2 = Task::factory()->create(['order' => 2]);

        $this->taskRepository->reorder([$task2->id, $task1->id]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task1->id,
            'order' => 2,
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task2->id,
            'order' => 1,
        ]);
    }
}