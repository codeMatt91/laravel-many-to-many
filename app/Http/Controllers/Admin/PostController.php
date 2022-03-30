<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = Category::all();
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(10);
        $tags = Tag::all();
        return view('admin.posts.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $categories = Category::all();
        $post = new Post();
        $tags = Tag::all();

        //Qui ho già il post e posso fare la relazione con la relazione tags e mettere l'if perchè potrebbe non arrivarmi niente da $data[tags]
       // if(array_key_exists('tags', $data)) $post->tags()->attach($data['tags']);
        return view('admin.posts.create', compact('categories', 'post', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|string|unique:posts|min:5/max:50',
            'content'=>'required|string',
            'image'=>'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'tags'=> 'nullable|exists:tags,id'
        ],
        [
            'title.required'=>'Il titolo è obbligatorio',
            'title.min'=>'La lunghezza minima del titolo è di 5 caratteri',
            'title.max'=>'La lunghezza massim del titolo è di 50 caratteri',
            'title.unique'=>"Esiste già un post dal titolo $request->title",
            'tags.exists' => 'Il tag selezionato non è valido'
        ]);


        $data = $request->all();

        $post = new Post();
        $data['slug'] = Str::slug($request->title , '-');

        $post->fill($data);
        $post->save();

        //Qui ho già il post e posso fare la relazione con la relazione tags e mettere l'if perchè potrebbe non arrivarmi niente da $data[tags]
        if(array_key_exists('tags', $data)) $post->tags()->attach($data['tags']);

        return redirect()->route('admin.posts.show', compact('post'));

    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        // Sto recuperando dalla tabella di collegamento tra post e tag gli id dei tag relazionati con lo specifico post
        $post_tags = $post->tags->pluck('id')->toArray();
        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'post_tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'=>['required','string', 'min:5', 'max:50'],
            'content'=>'required|string',
            'image'=>'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'tags'=> 'nullable|exists:tags,id'
        ],
        [
            'title.required'=>'Il titolo è obbligatorio',
            'title.min'=>'La lunghezza minima del titolo è di 5 caratteri',
            'title.max'=>'La lunghezza massim del titolo è di 50 caratteri',
            'title.unique'=>"Esiste già un post dal titolo $request->title",
            'tags.exists' => 'Il tag selezionato non è valido'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title, '-');
        $post->update($data);
        
        //Qui facciamo una relazione di sync per togliere e mettere i valori nella tabella e poi passarli alla show, se pero non arriva nulla allora cancella tutto quello che c'era prima
        if(!array_key_exists('tags', $data)) $post->tags()->detach();
        else $post->tags()->sync($data['tags']);

        return redirect()->route('admin.posts.show', compact('post'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('massage', "il post '$post->id' è stato eliminato")->with('type', 'success');
    }
}
