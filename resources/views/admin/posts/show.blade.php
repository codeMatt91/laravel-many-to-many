@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-3">{{ $post->title }}</h2>
        <div class="d-flex mb-3">
            <img width="100px" src="{{ $post->image }}"
                alt="{{ $post->title }}">
            <p class="ml-3">{{ $post->content }}</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('admin.posts.index', $post->id) }}"
                class="btn btn-secondary mr-3">Indietro</a>
            <form action="{{ route('admin.posts.destroy', $post->id) }}"
                method="post" id="delete">
                @method('delete')
                @csrf
                <button type="submit" class="btn btn-danger">Elimina</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // METODO PER APRIRE UNA MODALE E CHIEDERE CONFERMA ELIMINAZIONE
        const delectForm = document.getElementById('delete');

        deleteForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const accept = confirm('Are you sure you want to delete this?');
            if (accept) e.target.submit();
        });
    </script>
@endsection
