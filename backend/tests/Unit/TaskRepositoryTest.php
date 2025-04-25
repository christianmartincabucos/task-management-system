<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private TaskRepository $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository(new Task());
    }
    public function test_create_task()
    {
        // Create a user first
        $user = User::factory()->create();
        
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'status' => 'pending',
            'priority' => 'medium',
            'order' => 1,
            'user_id' => $user->id,
        ];

        $task = $this->taskRepository->create($taskData);
        
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
            'user_id' => $user->id,
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
        $user = User::factory()->create();
        
        $task1 = Task::factory()->create([
            'user_id' => $user->id,
            'order' => 1
        ]);
        
        $task2 = Task::factory()->create([
            'user_id' => $user->id,
            'order' => 2
        ]);
    
        $this->taskRepository->updateOrder([
            ['id' => $task1->id, 'order' => 2],
            ['id' => $task2->id, 'order' => 1]
        ]);
    
        $task1->refresh();
        $task2->refresh();
        
        $this->assertEquals(2, $task1->order);
        $this->assertEquals(1, $task2->order);
    }
}