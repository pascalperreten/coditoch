<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Ministry;
use App\Models\Event;

class Invitation extends Notification implements ShouldQueue
{
    use Queueable;
    public User $newMember;
    public Ministry $ministry;
    public Event $event;
    /**
     * Create a new notification instance.
     */
    public function __construct(User $newMember, Ministry $ministry, Event $event)
    {
        $this->newMember = $newMember;
        $this->ministry = $ministry;
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Invitation to') . ' ' . $this->event->name . ' ' . $this->event->city)
            ->greeting(__('Hello') . ' ' . $this->newMember->first_name)
            ->line(__('Please click the link below to set up your account.'))
            ->action(__('Set up your account now'), url(route('invitation', [$this->ministry, $this->newMember->invitation_token])))
            ->line(__('Thank you!'))
            ->salutation(__('Best regards, the') . ' ' . $this->ministry->name . ' ' . __('Team'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
