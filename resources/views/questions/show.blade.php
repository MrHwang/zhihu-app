@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$question->title}}</div>
                    @foreach($question->topics as $topic)
                        <span class="topic">{{$topic->name}}</span>
                    @endforeach
                    <div class="panel-body">
                        {!!$question->body!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
