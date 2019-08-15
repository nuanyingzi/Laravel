@extends('layouts.default')

@section('content')
    <div class="jumbotron">
        <h1>绿色微博</h1>
        <p class="lead">
            大驾光临，有失远迎
        </p>
        <p>在此分享您的喜怒哀乐吧~</p>
        <p>
            <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
        </p>
    </div>
@stop