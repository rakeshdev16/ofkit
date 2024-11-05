<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDetailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $password)
    {
        $this->fullName = $user->first_name.' '.$user->last_name;
        $this->email = $user->email;
        $this->password = $password;
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
        // return (new MailMessage)
        //     ->greeting('Hello ' . $this->fullName)
        //     ->subject('SleimanJi Account Credentials')
        //     ->line('Your account has been successfully created!')
        //     ->line('Email: '.$this->email)
        //     ->line('Password: '.$this->email)
        //     ->line('Thank you for using our application!');

        return (new MailMessage)
        ->subject('ברוכים הבאים לאופקית')
        ->view(
            'emails.account-detail', // Path to your custom Blade view
            [
                'siteUrl' => url(config('app.url')),
                'logo' => asset('assets/images/3.png'),
                'notifiable' => $notifiable,
                'username' => $this->email, // Assuming email is the username
                'password' => $this->password, // Replace this with actual logic or variable
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
