<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\TaskOverdueAlert;
use App\Task;
use App\User;

class TaskReminderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users = User::whereHas('tasks', function ($query) {
                                                    
                                $query->whereHas('stage', function ($query) {
                                
                                    $query->where('open', 1);
                                })                                                
                                ->where('tasks.due_date', '<', date('Y-m-d'));
                            })
                            ->with(['tasks' => function ($query) {

                                $query->whereHas('stage', function ($query) {
                                
                                    $query->where('open', 1);
                                })                                                
                                ->where('tasks.due_date', '<', date('Y-m-d'))->pluck('tasks.title', 'tasks.id');
                            }])            
                            ->where('type', '!=', 'super admin')
                            ->where('notify_item_overdue','=',true)
                            ->get();

        // $this->users = User::with(['tasks' => function ($query) {
        //     $query->where('tasks.due_date', '<', date('Y-m-d'))->pluck('tasks.title', 'tasks.id');
        // }])
        // ->where('type', '!=', 'super admin')
        // ->where('notify_item_overdue','=',true)
        // ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->users as $user)
        {
            if(!$user->tasks->isEmpty())
            {
                $user->notify(new TaskOverdueAlert($user, $user->tasks));
            }
        }        
    }
}
