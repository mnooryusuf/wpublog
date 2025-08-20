<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->where('author_id', Auth::user()->id);

        if (request('keyword')) {
            $posts->where('title', 'like', '%' . request('keyword') . '%');
        }

        return view('dashboard.index', ['posts' => $posts->paginate(7)->withQueryString()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.create', [
            'title' => 'Create New Post',
            'post' => new Post(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        // $request->validate([
        //     'title' => 'required|max:255|unique:posts|min:4|max:255',
        //     'category_id' => 'required',
        //     'body' => 'required'
        // ]);

        Validator::make(
            $request->all(),
            [
                'title' => 'required|max:255|unique:posts|min:4',
                'category_id' => 'required',
                'body' => 'required'
            ],
            [
                'title.reqired' => 'Field :attribute harus diisi!',
                'category_id.required' => 'Pilih :attribute terlebih dahulu!',
                'body.required' => 'Isi :attribute terlebih dahulu!'
            ],
            [
                'title' => 'Judul',
                'category_id' => 'Kategori',
                'body' => 'Konten'
            ]

        )->validate();

        Post::create([
            'title' => $request->title,
            'slug' => str()->slug($request->title),
            'body' => $request->body,
            'category_id' => $request->category_id,
            'author_id' => Auth::user()->id,
        ]);

        return redirect('/dashboard')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('dashboard.show', [
            'post' => $post,
            'title' => 'Post Details'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
