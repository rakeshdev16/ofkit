<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $otp;
    public function __construct($otp)
    {
        $this->otp = $otp;
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
        // $resetUrl = url(config('app.url') . route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false));
        $logo = asset('assets/images/3.png');

        return (new MailMessage)
            ->subject('קוד כניסה')
            ->view(
                'emails.send-otp-notification', // Path to your custom Blade view
                ['siteUrl' => url(config('app.url')), 'logo' => $logo, 'notifiable' => $notifiable, 'otp' => $this->otp] // Pass necessary data to the view
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
