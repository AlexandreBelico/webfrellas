<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Milestonechangestatus extends Mailable
{

    use SerializesModels;

    public $milestone;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($milestone)
    {
        $this->milestone = $milestone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                        ->subject('Milestone status changed')
                        ->view('emails.milestone_change_status')
                        ->with(
                                [
                                    'name' => $this->user->name,
                                    'email' => $this->user->email,
                                    'link' => route('user.profile', $this->user->id),
                                    'link_admin' => route('edit.user', ['id' => $this->user->id])
                                ]
        );
    }

}
