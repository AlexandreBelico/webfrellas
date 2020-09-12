<?php


namespace App\Http\Controllers;

use App\Job;
use App\JobApply;
use App\JobFeedback;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function giveFeedback(Request $request)
    {
        $in = $request->all();
        if ($in['type'] == "employer") {
            $jobApply = Job::where('id',$in['job_id'])->firstorfail();

            $feedback = JobFeedback::create($in);
            $updateStatus = JobApply::where('job_id', $in['job_id'])
                ->update([
                    'isEmployeerContractStatus' => 'close',
                    'EmployerCloseContract'=>Carbon::now()
                ]);

            $data = [
                'job_title' => $jobApply->title,
                'user_link' => route('user.profile', $in['user_id']),
                'job_link' => route('job.detail', [$jobApply->slug])
            ];
            $user_full_name = $in['userName'];
            $to_email = $in['userEmail'];
            Mail::send('emails.job_feedback', $data, function ($message) use ($user_full_name, $to_email) {
                $message->to($to_email, $user_full_name)->subject('Project Feedback');
                $message->from('support@jobsportal.com', 'WebFreeLas');
            });

            flash(__('Feedback given successfully.'));
            return response()->json(['status' => true, 'feedback' => $feedback, 'message' => 'Success']);
        } else {
            $jobApply = Job::where('id',$in['job_id'])->firstorfail();
            $feedback = JobFeedback::create($in);
            $updateStatus = JobApply::where('job_id', $in['job_id'])
                ->update([
                    'isCandidateContractStatus' => 'close',
                    'CandidateCloseContract'=>Carbon::now()
                ]);

            $data = [
                'job_title' => $jobApply->title,
                'user_link' => route('user.profile', $in['user_id']),
                'job_link' => route('job.detail', [$jobApply->slug])
            ];
            $user_full_name = $in['companyName'];
            $to_email = $in['companyEmail'];
            Mail::send('emails.job_feedback', $data, function ($message) use ($user_full_name, $to_email) {
                $message->to($to_email, $user_full_name)->subject('Project Feedback');
                $message->from('support@jobsportal.com', 'WebFreeLas');
            });
            flash(__('Feedback given successfully.'));
            return response()->json(['status' => true, 'feedback' => $feedback, 'message' => 'Success']);
        }
    }

    public function editFeedbackById($userId, $jobId, $companyId)
    {
        try {
            $feedback = JobFeedback::where('user_id', $userId)->where('job_id', $jobId)->where('company_id', $companyId)
                ->first();

            return response()->json(['status' => true, 'feedback' => $feedback, 'message' => 'Get Feedback Successfully.']);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function updateFeedback($userId, $jobId, $companyId, Request $request)
    {
        try {
            $in = $request->all();
//            dd($in);
            if (isset($in['rating']) && isset($in['feedback'])) {
                $feedback = JobFeedback::where('user_id', $userId)
                    ->where('job_id', $jobId)
                    ->where('company_id', $companyId)
                    ->update([
                        'feedback' => $in['feedback'],
                        'rating' => $in['rating']
                    ]);
                flash(__('Feedback updated successfully.'));
                return response()->json(['status' => true, 'feedback' => "", 'message' => 'Update Feedback Successfully.']);
            } else {
                $feedback = JobFeedback::where('user_id', $userId)
                    ->where('job_id', $jobId)
                    ->where('company_id', $companyId)
                    ->update([
                        'feedback' => $in['feedback']
                    ]);
                flash(__('Feedback updated successfully.'));
                return response()->json(['status' => true, 'feedback' => "", 'message' => 'Update Feedback Successfully.']);
            }

        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }
}