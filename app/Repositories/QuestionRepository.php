<?php
namespace App\Repositories;


use App\Question;
use App\Topic;

class QuestionRepository
{
    public function byIdWithTopics($id)
    {
        return Question::where('id',$id)->with('topics')->first();
    }

    public function byId($id)
    {
        return Question::find($id);
    }

    public function create(array $attributes)
    {
        return Question::create($attributes);
    }

    public function normalizeTopic(array $topics)
    {
        return collect($topics)->map(function ($topic){
            if(is_numeric($topic)){
                Topic::find($topic)->increment('questions_count');
                return (int) $topic;
            }
            $newTopic = Topic::create(['name'=>$topic,'questions_count'=>1]);
            return $newTopic->id;
        }
        )->toArray();
    }

    public function getQuestionsFeed()
    {
        // with('user' 使用Question模型中的user方法关联users表)
        //return Question::latest('updated_at')->with('user')->get();
        // published() 对应Question模型中的scopePublished
        return Question::published()->latest('updated_at')->with('user')->get();
    }
}