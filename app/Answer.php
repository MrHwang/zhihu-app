<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $fillable = ['user_id','question_id','body'];

    public function user()
    {
        // 关联users表
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        // 关联Questions表
        return $this->belongsTo(Question::class);
    }
}
