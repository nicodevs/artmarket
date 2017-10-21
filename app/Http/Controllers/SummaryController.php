<?php

namespace App\Http\Controllers;

use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Notification;
use App\User;
use App\Email;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Sends the notifications daily summary by email
     *
     * @param App\Notification $notification
     * @param App\User $user
     * @param App\Email $email
     * @return void
     */
    public function index(Notification $notification, User $user, Email $email)
    {
        $notifications = $notification->where([
            ['created_at', '>', date('Y-m-d', strtotime('-1 day'))],
            ['mailed', '=', 0]
        ])->get();

        $notifications = $notifications->filter(function ($notification) {
            return $notification->type === 'APPROVAL' && !$notification->mailed;
        });

        $result = $notifications
            ->groupBy('user_id')
            ->map(function ($notifications, $userId) use ($user) {
                return [
                    'user' => $user->find($userId)->toArray(),
                    'notifications' => $notifications->toArray(),
                    'notification_counters' => $notifications
                        ->groupBy('type')
                        ->map(function ($type) {
                            return $type->count();
                        })->toArray()
                ];
            })
            ->map(function ($group) use ($email) {
                $group['email'] = $email->composeApprovalEmail($group);
                return $group;
            });

        $notifications->each(function ($notification) {
            $notification->update(['mailed' => 1]);
        });

        return ['success' => true, 'data' => array_values($result->toArray())];
    }
}
