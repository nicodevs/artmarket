<?php

namespace App\Http\Controllers;

use App\Email;
use App\Exceptions\InvalidSummaryTypeException;
use App\Http\Resources\Core\Collection as CollectionResource;
use App\Http\Resources\Core\Item as ItemResource;
use App\Notification;
use App\User;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Sends the notifications daily summary by email
     *
     * @param \Illuminate\Http\Request $request
     * @param App\Notification $notification
     * @param App\User $user
     * @param App\Email $email
     * @return void
     */
    public function index(Request $request, Notification $notification, Email $email)
    {
        switch ($request->type) {
            case 'approvals':
                $period = '-1 day';
                $types = ['APPROVAL'];
                $method = 'composeApprovalEmail';
                break;

            case 'interactions':
                $period = '-60 days';
                $types = ['COMMENT', 'LIKE'];
                $method = 'composeInteractionsEmail';
                break;

            default:
                throw new InvalidSummaryTypeException;
                break;
        }

        $result = $notification->unreadGroupedByUser($period, $types)
            ->each(function ($group) use ($email, $method) {
                call_user_func([$email, $method], $group);
                $group['notifications']->each(function ($notification) {
                    $notification->update(['mailed' => 1]);
                });
            });

        return ['success' => true, 'data' => array_values(json_decode(json_encode($result), true))];
    }
}
