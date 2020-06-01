@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>
            {{ $question->title }}
        </h1>
        <p>{{$question->body}}</p>
        <small>Posted on: {{ $question->created_date }} </small>
    </div>



@endsection
