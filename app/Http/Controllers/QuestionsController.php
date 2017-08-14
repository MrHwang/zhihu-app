<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Repositories\QuestionRepository;
use Auth;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->middleware('auth')->except(['index','show']);
        $this->questionRepository = $questionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $questions = $this->questionRepository->getQuestionsFeed();
        return view('questions.index',compact('questions'));
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
     * @param StoreQuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreQuestionRequest $request)
    {

        $topics = $this->questionRepository->normalizeTopic($request->get('topics'));
        $data = [
            'title' =>  $request->get('title'),
            'body'  =>  $request->get('body'),
            'user_id'=> Auth::id()
        ];
        $question = $this->questionRepository->create($data);

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
        // $question = Question::where('id',$id)->with('topics')->first();
        $question = $this->questionRepository->byIdWithTopicsAndAnswers($id);
        //dd($question->answers);
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
        $question = $this->questionRepository->byId($id);
        if(Auth::user()->owns($question)){
            return view('questions.edit',compact('question'));
        }
        return back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param StoreQuestionRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreQuestionRequest $request, $id)
    {
        //
        $question = $this->questionRepository->byId($id);
        $question->update([
                'title' => $request->get('title'),
                'body'  => $request->get('body')
            ]
        );

        $topics = $this->questionRepository->normalizeTopic($request->get('topics'));
        $question->topics()->sync($topics);

        return redirect()->route('questions.show',[$question->id]);
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
        $question = $this->questionRepository->byId($id);
        if(Auth::user()->owns($question)){
            $question->delete();
            return redirect('/');
        }

        abort(403,'Forbidden');// return back();
    }

}
