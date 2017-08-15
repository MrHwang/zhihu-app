<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Mail;
use Naux\Mail\SendCloudTemplate;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * ForgortPasswordController -> SendsPasswordResetEmails:sendResetLinkEmail -> PasswordBroker ->CanResetPassword:sendPasswordResetNotification
     * -> ResetPassword:toMail
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        //$this->notify(new ResetPasswordNotification($token));
        $bind_data = [
            'url' => url('password/reset', $token),
        ];
        $template = new SendCloudTemplate('zhihu_app_password_reset', $bind_data);

        Mail::raw($template, function ($message){
            $message->from('longjian.hwang@gmail.com', 'Zhihu');

            $message->to($this->email);
        });
    }

    /*public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url('password/reset', $this->token))
            ->line('If you did not request a password reset, no further action is required.');
    }*/

    public function owns(Model $model)
    {
        return $this->id == $model->user_id;
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function follows()
    {
       /*return Follow::create([
           'question_id' => $question,
           'user_id' => $this->id
       ]);*/
       return $this->belongsToMany(Question::class,'user_question')->withTimestamps();
    }

    public function followThis($question)
    {
        return $this->follows()->toggle($question);
    }

    public function followed($question)
    {
        // 返回bool值
        return !! $this->follows()->where('question_id',$question)->count();
    }
}
