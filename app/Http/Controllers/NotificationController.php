<?php

namespace App\Http\Controllers;

use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Notification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Image $image)
    {
        $like = auth()->user()->likesImage($image->id);

        event(new ImageLiked($image, $like));

        return new ItemResource($like);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Format  $format
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $result = auth()->user()->dislikesImage($image->id);
        return ['success' => true];
    }

    /**
      * Sends the notifications daily summary by email
      *
      * @return array
      */
    public function sendDaily()
    {
        // Get all likes from last week
        $notifications = $this->model->limit(100000)->where('created_at', date('Y-m-d', strtotime('-1 day')), '>')->where('mailed', 0)->get();

        // Group comments and notifications by user
        $users = [];
        foreach ($notifications as $notification) {
            if (($notification['type'] === 'APPROVAL') && !$notification['mailed']) {
                if (!isset($users[$notification['user_id']])) {
                    $users[$notification['user_id']] = array_merge($this->users->find($notification['user_id']), [
                        'notification_counters' => ['APPROVAL' => 0]
                    ]);
                }
                $users[$notification['user_id']]['notifications'][] = $notification;
                $users[$notification['user_id']]['notification_counters'][$notification['type']]++;
            }
        }

        // Create email
        foreach ($users as $user) {
            $html = $this->app->view->render('emails/notifications/approvals', compact('user'));
            $subject = ($user['notification_counters']['APPROVAL'] > 1)? 'Tus obras fueron aprobadas' : 'Tu obra fue aprobada';
            $compose = $this->emails->compose($subject, $user['email'], $html);
        }

        // Update notifications
        $updated = [];
        foreach ($notifications as $notification) {
            if (($notification['type'] === 'APPROVAL') && !$notification['mailed']) {
                $updated[] = $this->model->update($notification['id'], ['mailed' => 1]);
            }
        }

        // Show feedback
        return ['success' => true, 'data' => $updated];
    }
}
