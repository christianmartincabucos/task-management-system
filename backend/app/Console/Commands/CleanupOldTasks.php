<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOldTasks extends Command
{
    protected $signature = 'tasks:cleanup';
    protected $description = 'Delete tasks older than 30 days';

    public function handle()
    {
        $date = now()->subDays(30);
        $count = Task::where('created_at', '<', $date)->count();
        
        if ($count > 0) {
            Task::where('created_at', '<', $date)->delete();
            Log::info("Deleted {$count} tasks older than 30 days.");
            $this->info("Deleted {$count} tasks older than 30 days.");
        } else {
            Log::info("No tasks older than 30 days found.");
            $this->info("No tasks older than 30 days found.");
        }
        
        return Command::SUCCESS;
    }
}