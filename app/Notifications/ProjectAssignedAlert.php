<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\SubscriberMailMessage;
use App\Task;
use Illuminate\Support\Facades\URL;

class ProjectAssignedAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $project)
    {
        $this->user = $user;
        $this->project = $project;
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
                    ->greeting(__('New project notification'))
                    ->subject(__('You have been assigned to a new project'))
                    ->line(__('You have been assigned to the <b>:project</b> project.', ['project' => $this->project->name]))
                    ->action(__('View project'), route('projects.show', $this->project->id))
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
