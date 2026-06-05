<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;
use App\Models\User;

class ContactAddedToChurchMember extends Notification
{
    use Queueable;

    public Ministry $ministry;
    public Event $event;
    public Church $church;
    public User $member;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ministry $ministry, Event $event, Church $church, User $member)
    {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->church = $church;
        $this->member = $member;
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
            ->subject(__('New Contact Added'))
            ->greeting(__('Hello') . ' ' . $this->member->first_name . ',')
            ->line(__('We\'ve added a new contact for you.'))
            ->action(__('See your new contact now'), url(route('churches.contacts', [$this->ministry, $this->event, $this->church])))
            ->line(__('Please take care of the new contact as soon as possible!'))
            ->salutation(__('Best regards, the') . ' ' . $this->church->name . ' Team');
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
