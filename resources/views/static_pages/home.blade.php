@extends('layouts.default')

@section('content')
    <div class="jumbotron">
        <h1>Laravel</h1>
        <p class="lead">
            Now you can see <a href="https://learnku.com/courses/laravel-essential-training">Laravel </a> homepage。
        </p>
        <p>一切，将从这里开始。</p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
        </p>
    </div>
@stop