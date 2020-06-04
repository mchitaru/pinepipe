<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Task;
use Illuminate\Support\Facades\URL;
use App\Mail\SubscriberMailMessage;

class TaskOverdueAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $tasks;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $tasks)
    {
        $this->user = $user;
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
        // return ['mail', 'database'];
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
                    ->greeting(__('Tasks Reminder'))
                    ->subject(__('You have :count overdue task(s)', ['count' => $this->tasks->count()]))
                    ->line(__('You have :count overdue task(s)', ['count' => $this->tasks->count()]))
                    ->action(__('View all'), route("home"))
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
        return $this->tasks->pluck('title', 'id')->toArray();
    }
}
