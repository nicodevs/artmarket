<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'subject',
        'recipient',
        'sent',
        'tries',
        'body'
    ];

    /**
     * Composes a new email.
     *
     * @param string $subject
     * @param string $recipent
     * @param string $body
     * @return App\Email
     */
    public function compose($subject, $recipient, $body)
    {
        $this->subject = $subject;
        $this->recipient = $recipient;
        $this->body = $body;
        $this->save();
        return $this->toArray();
    }

    /**
     * Composes an approval email
     *
     * @param array $notifications
     * @return App\Email
     */
    public function composeApprovalEmail($notifications)
    {
        $html = view('approvals')->with($notifications)->render();
        $subject = ($notifications['notification_counters']['APPROVAL'] > 1)? 'Tus obras fueron aprobadas' : 'Tu obra fue aprobada';
        return $this->compose($subject, $notifications['user']['email'], $html);
    }

    /**
     * Composes an interactions email
     *
     * @param array $notifications
     * @return App\Email
     */
    public function composeInteractionsEmail($notifications)
    {
        $html = view('interactions')->with($notifications)->render();
        $subject = 'Tu resumen semanal';
        return $this->compose($subject, $notifications['user']['email'], $html);
    }
}
