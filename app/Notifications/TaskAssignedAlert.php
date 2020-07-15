<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\SubscriberMailMessage;
use App\Task;
use Illuminate\Support\Facades\URL;

class TaskAssignedAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $unsubscribeUrl = URL::signedRoute('unsubscribe.edit', ['user' => $this->user]);

        return (new SubscriberMailMessage($unsubscribeUrl))
                    ->greeting(__('New task notification'))
                    ->subject(__('You have been assigned a new task'))
                    ->line(__('You have been assigned the <b>:task</b> task.', ['task' => $this->task->title]))
                    ->action(__('View tasks'), route('tasks.board'))
                    ->line(__('Thank you for using our application!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
