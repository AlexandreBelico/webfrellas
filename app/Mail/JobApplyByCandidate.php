<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobApplyByCandidate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($job,$user_full_name,$user_id)
    {
        $this->job = $job;
        $this->user_full_name = $user_full_name;
        $this->user_id = $user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ppa.logixbuilt@gmail.com', 'Alexandre')
            ->to($this->job->company->email, $this->job->company->name)
            ->subject('Job seeker named "' . $this->user_full_name . '" has applied on job "' . $this->job->title)
            ->view('emails.job_applied_company_message')
            ->with([
                'job_title' => $this->job->title,
                'company_name' => $this->job->company->name,
                'user_name' => $this->user_full_name,
                'user_link' => route('user.profile', $this->user_id),
                'job_link' => route('job.detail', [$this->job->slug])
            ]);
    }
}
