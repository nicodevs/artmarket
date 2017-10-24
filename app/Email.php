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
     * @return \App\Email
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
     * Composes an approval email.
     *
     * @param array $notifications
     * @return \App\Email
     */
    public function composeApprovalEmail($notifications)
    {
        $html = view('approvals')->with($notifications)->render();
        $subject = ($notifications['notification_counters']['APPROVAL'] > 1)? 'Tus obras fueron aprobadas' : 'Tu obra fue aprobada';
        return $this->compose($subject, $notifications['user']['email'], $html);
    }

    /**
     * Composes an interactions email.
     *
     * @param array $notifications
     * @return \App\Email
     */
    public function composeInteractionsEmail($notifications)
    {
        $html = view('interactions')->with($notifications)->render();
        $subject = 'Tu resumen semanal';
        return $this->compose($subject, $notifications['user']['email'], $html);
    }

    /**
     * Composes a welcome email.
     *
     * @param \App\User $user
     * @return \App\Email
     */
    public function composeWelcomeEmail($user)
    {
        $html = view('welcome')->with(compact('user'))->render();
        $subject = 'Bienvenido a Enpics';
        return $this->compose($subject, $user['email'], $html);
    }

    /**
     * Composes a password recovery email.
     *
     * @param \App\User $user
     * @param string $token
     * @return \App\Email
     */
    public function composePasswordRecoveryEmail($user, $token)
    {
        $html = view('passwordRecovery')->with(compact('user', 'token'))->render();
        $subject = 'Recuperar clave';
        return $this->compose($subject, $user['email'], $html);
    }

    /**
     * Composes a image flag email.
     *
     * @param array $data
     * @param \App\Image $image
     * @return \App\Email
     */
    public function composeImageFlagEmail($data, $image)
    {
        $html = view('imageFlag')->with(compact('data', 'image'))->render();
        $subject = 'Imagen ' . $image->id . ' reportada';
        return $this->compose($subject, env('ADMIN_EMAIL'), $html);
    }

    /**
     * Composes a contact email.
     *
     * @param array $data
     * @return \App\Email
     */
    public function composeContactEmail($data)
    {
        $html = view('contact')->with(compact('data'))->render();
        $subject = 'Mensaje de ' . $data['email'] . ' desde el sitio web';
        return $this->compose($subject, env('ADMIN_EMAIL'), $html);
    }
}
