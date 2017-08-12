<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = ['title','body','user_id'];

    public function topics()
    {
        // belongsToMany第二个参数可以传入自定义的关联表名
        // 关联表默认是单数下划线间隔，如question_table
        // withTimestamps决定是否添加时间戳到关联表中
        return $this->belongsToMany(Topic::class,'question_topic')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_hidden','F');
    }
}
