@extends('layouts.app');

@section('content')
    <div class="container">
        <div class="row">
            @php
                dd($category->posts);
            @endphp
            @foreach ($category->posts as $post)
                <div class="col-4">
                    <div class="d-flex">
                        <div class="mr-3">{{ $post->image }}</div>
                        <h2>{{ $post->title }}</h2>
                    </div>
                    <p>{{ $post->content }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
