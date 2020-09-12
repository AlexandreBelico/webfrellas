<?php


namespace App\Http\Controllers\Job;


use App\Events\NotificationUpdate;
use App\Http\Controllers\Controller;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationController extends Controller
{
    public function notificationPage()
    {
        $userId = Auth::user();
        $companyId = Auth::guard('company')->user();
        if ($userId != null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $userId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $userId->id)
                ->where('isRead', 'false')
                ->count();
        } else if ($companyId !== null) {
            $notification = Notification::with('getJobDetails')
                ->where('to_user_id', $companyId->id)
                ->orderby('id', 'asc')
                ->limit(5)
                ->get();
            $notificationCount = Notification::where('to_user_id', $companyId->id)
                ->where('isRead', 'false')
                ->count();
        }

        if (isset($notification)) {
            return view('notification.notification')
                ->with('notification', $notification)
                ->with('notificationCount', $notificationCount);
        } else {
            return view('notification.notification');
        }
    }

    public function getNotificationList(Request $request)
    {
        $companyId = Auth::guard('company')->user();
        $userId = Auth::user();
        if ($companyId != null) {
            if ($request->ajax()) {
                if ($request->id > 0) {
                    $notification = Notification::with('getJobDetails')
                        ->where('id', $request->id)
                        ->where('to_user_id', $companyId->id)
                        ->orderBy('id','asc')
                        ->paginate(10);
                } else {
                    $notification = Notification::with('getJobDetails')
                        ->where('to_user_id', $companyId->id)
                        ->orderBy('id','asc')
                        ->paginate(10);
                }

                $output = '';
                $last_id = '';

                if (!$notification->isEmpty()) {
                    foreach ($notification as $o) {
                        $output .= "<li>
                                <div class='notification'>
                                        <a href='" . url('job') . '/' . $o->getJobDetails->slug . "' style='color: #000;'>$o->content</a>
                                        <div class='pull-right'>
                                            " . $o->created_at->format('d M') . "
                                        </div>
                                    </div>
                                </li>";
                        $last_id = $o->id;
                    }

                    if (count($notification) > 10) {
                        $output .= '<div id="load_more">
                            <button type="button" name="load_more_button" class="btn btn-success form-control" data-id="' . $last_id . '" id="load_more_button">Load More</button>
                        </div>';
                    }
                } else {
                    $output .= '<div id="load_more">
                        <button type="button" name="load_more_button" class="btn btn-info form-control">No Data Found</button>
                    </div>';
                }

                echo $output;
            }
        } else {
//            $notification = Notification::with('getJobDetails')->where('to_user_id', $userId->id)->paginate(10);
            if ($request->ajax()) {
                if ($request->id > 0) {
                    $notification = Notification::with('getJobDetails')
                        ->where('id', $request->id)
                        ->where('to_user_id', $userId->id)
                        ->orderBy('id','asc')
                        ->paginate(10);
                } else {
                    $notification = Notification::with('getJobDetails')
                        ->where('to_user_id', $userId->id)
                        ->orderBy('id','asc')
                        ->paginate(10);
                }

                $output = '';
                $last_id = '';

                if (!$notification->isEmpty()) {
                    foreach ($notification as $o) {
                        $output .= "<li>
                                <div class='notification'>
                                        <a href='" . url('job') . '/' . $o->getJobDetails->slug . "' style='color: #000;'>$o->content</a>
                                        <div class='pull-right'>
                                            " . $o->created_at->format('d M') . "
                                        </div>
                                    </div>
                                </li>";
                        $last_id = $o->id;
                    }

                    if (count($notification) > 10) {
                        $output .= '<div id="load_more">
                            <button type="button" name="load_more_button" class="btn btn-success form-control" data-id="' . $last_id . '" id="load_more_button">Load More</button>
                        </div>';
                    }
                } else {
                    $output .= '<div id="load_more">
                        <button type="button" name="load_more_button" class="btn btn-info form-control">No Data Found</button>
                    </div>';
                }
                echo $output;
            }
        }
//        return view('notification.notification')->with('notification', $notification);
//        return view('notification.notification');
    }
}