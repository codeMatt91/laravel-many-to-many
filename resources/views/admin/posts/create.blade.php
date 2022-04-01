@extends('layouts.app')

@section('content')
    <div class="container">
        <header>
            <h1>Crea un nuovo Post</h1>
        </header>

        <form action="{{ route('admin.posts.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-8">

                    <!-- TITOLO -->
                    <div class="form-group">
                        <label for="title">Titolo</label>
                        <input type="text" class="form-control" @error('title') is-valid @enderror id="title" name="title">
                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- CATEGORIA -->
                <div class="col-4">
                    <label for="category">Catogoria</label>
                    <select class="form-control" id="category" name="category_id" @error('category_id') is-valid @enderror>
                        <option value=""> Nessuna categoria</option>
                        @foreach ($categories as $category)
                            <option @if (old('category_id', $post->category_id) == $category->id) selected @endif value="{{ $category->id }}">
                                {{ $category->label }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- TAG -->
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        @foreach ($tags as $tag)
                            <input class="form-check-input ml-2" type="checkbox" id="tag-{{ $loop->iteration }}"
                                value="{{ $tag->id }}" name="tags[]" @if (in_array($tag->id, old('tags', []))) checked @endif>
                            <label class="form-check-label" for="tag-{{ $loop->iteration }}">{{ $tag->label }}</label>
                        @endforeach
                    </div>
                </div>

                <!-- CONTENUTO POST -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="content">Contenuto</label>
                        <textarea id="content" rows="6" class="form-control" name="content" @error('content') is-valid @enderror
                            placeholder="Contenuto del post.."></textarea>
                    </div>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-2">
                    <img src="https://icons.iconarchive.com/icons/ccard3dev/dynamic-yosemite/1024/Preview-icon.png"
                        alt="placeholder" class="img-fluid" id="preview">
                </div>

                <!-- IMMAGINE -->
                <div class="col-10 d-flex flex-column justify-content-center">
                    <div class="form-group">
                        <label for="image">Immagine</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Salva</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Indietro</a>
        </form>
    </div>
@endsection

@section('scripts')
    {{-- Script per la preview dell'immagine --}}

    <script>
        const placeholder =
            "https://icons.iconarchive.com/icons/ccard3dev/dynamic-yosemite/1024/Preview-icon.png";
        const imgInput = document.getElementById('image');
        const imgPreview = document.getElementById('preview');

        imgInput.addEventListener('change', e => {
            const url = imgInput.value;
            if (url) imgPreview.setAttribute('src', url);
            else imgPreview.setAttribute('src', placeholder);
        })
    </script>
@endsection
