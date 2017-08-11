<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Question;
use App\Topic;
use Auth;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("questions.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionRequest $request)
    {

        $topics = $this->normalizeTopic($request->get('topics'));
        $data = [
            'title' =>  $request->get('title'),
            'body'  =>  $request->get('body'),
            'user_id'=> Auth::id()
        ];
        $question = Question::create($data);

        $question->topics()->attach($topics);// 问题关联话题->question_topic
        return redirect()->route('questions.show',[$question->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // with('topics')中的'topics'是Question模型中function topics的方法名
        $question = Question::where('id',$id)->with('topics')->first();
        return view('questions.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function normalizeTopic(array $topics)
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
}
