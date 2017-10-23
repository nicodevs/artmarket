<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'user_id',
        'image_id',
        'type',
        'description',
        'visualized',
        'mailed'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'image_id' => 'integer',
        'visualized' => 'boolean',
        'mailed' => 'boolean'
    ];

    /**
     * The user that the comment belongs to.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The image that the comment belongs to.
     */
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    /**
     * Composes a new notification.
     *
     * @param string $type
     * @param App\User $recipient
     * @param App\Image $image
     * @param mixed $extra
     * @return boolean
     */
    public function compose($type, $recipient, $image, $extra = null)
    {
        switch ($type) {
            case 'COMMENT':
                $description = '<a href="/author/' . $extra->user->username . '">' . $extra->user->first_name . '</a> dej&oacute; un comentario en tu imagen <a href="/images/' . $image->id . '">' . $image->name . '</a>: ' . $extra->text;
                break;

            case 'LIKE':
                $description = 'A <a href="/author/' . $extra->user->username . '">' . $extra->user->first_name . '</a> le gusta tu imagen <a href="/images/' . $image->id . '">' . $image->name . '</a>';
                break;

            case 'APPROVAL':
                $description = 'Tu imagen <a href="/images/' . $image->id . '">' . $image->name . '</a> ha sido aproada';
                break;

            default:
                $description = '';
                break;
        }

        return $this->create([
            'type' => $type,
            'user_id' => $recipient->id,
            'image_id' => $image->id,
            'description' => $description
        ]);
    }

    /**
     * Get the latest notifications grouped by user
     *
     * @param string $period
     * @param array $types
     * @return array
     */
    public function unreadGroupedByUser($period, $types)
    {
        return $this->with('user')
            ->where([
                ['created_at', '>', date('Y-m-d', strtotime($period))],
                ['mailed', '=', 0]
            ])
            ->whereIn('type', $types)
            ->get()
            ->groupBy('user_id')
            ->map(function ($notifications) {
                return [
                    'user' => $notifications->first()->user,
                    'notifications' => $notifications,
                    'notification_counters' => $notifications
                        ->groupBy('type')
                        ->map(function ($type) {
                            return $type->count();
                        })->toArray()
                ];
            });
    }
}
