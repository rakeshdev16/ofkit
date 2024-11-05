<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        // Store the token in the class property
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param object $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false));
        $logo = asset('assets/images/3.png');

        return (new MailMessage)
            ->subject('איפוס סיסמה')
            ->view(
                'emails.reset-password', // Path to your custom Blade view
                ['resetUrl' => $resetUrl, 'siteUrl' => url(config('app.url')), 'logo' => $logo, 'notifiable' => $notifiable] // Pass necessary data to the view
            );
    }


    /**
     * Get the array representation of the notification.
     *
     * @param object $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
