<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Task;

class TaskOverdueAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $tasks;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Tasks Reminder')
                    ->subject('You have '. $this->tasks->count(). ' overdue task(s)')
                    ->line('You have '. $this->tasks->count(). ' overdue task(s).')
                    ->action('View All', url(route("projects.index").'/#tasks'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->tasks->pluck('title', 'id')->toArray();
    }
}
