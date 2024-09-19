<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffCredentialNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('password.reset', $this->token, false));
        $logo = asset('assets/images/3.png');

        return (new MailMessage)
        ->subject('ברוכים הבאים לאופקית')
        ->view(
            'emails.staff-credential', // Path to your custom Blade view
            [
                'resetUrl' => $resetUrl,
                'siteUrl' => url(config('app.url')),
                'logo' => asset('assets/images/3.png'),
                'notifiable' => $notifiable,
                'username' => $notifiable->email, // Assuming email is the username
                'password' => 'your-temporary-password', // Replace this with actual logic or variable
            ]
        );
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
