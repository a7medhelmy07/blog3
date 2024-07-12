<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;


class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $post = Post::all();
        return view('posts.index' , compact('post'));
    }

    public function postsTrashed()
    {
        $post = Post ::onlyTrashed()->get();
        return view('posts.trashed' , compact('post'));
    }


    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        $this->validate($request ,[
            'title' => 'required',
            'content' => 'required',
            'photo' => 'required|image',
        ]);

        $photo = $request-> photo;
        $newphoto = time().$photo->getClientOriginalName();
        $photo->move('upload/posts',$newphoto);

        $post = Post::create([
            'title'=> $request->title,
            'content'=> $request->content,
            'photo'=> 'upload/posts/'.$newphoto,
            'slug' =>   str::slug($request->title),
            'user_id'=> Auth::id()
        ]);

        return redirect()->route('posts');
    }


    public function show($slug)
    {
        $post = Post::where('slug' , $slug)->firstOrFail();
        return view('posts.show' , compact('post'));
    }


    public function edit($id)
    {
        $post = Post::find( $id);
        return view('posts.edit' , compact('post'));

    }


    public function update(Request $request, $id , Post $post)
    {
        if (! Gate::allows('update-post',$post)) {
           abort(403);
        }
        $post = Post::find( $id);
        $this->validate($request ,[
            'title' => 'required',
            'content' => 'required',
            'photo' => 'required|image',
        ]);
    if ($request ->has('photo')) {
        $photo = $request-> photo;
        $newphoto = time().$photo->getClientOriginalName();
        $photo->move('upload/posts',$newphoto);
        $post->photo = 'upload/posts'.$newphoto;
    }
    $post->title = $request->title;
    $post->content = $request->content;
    $post->save;
    return redirect()->route('posts');

    }


    public function destroy($id ,Request $request , Post $post)
    {
        if (! Gate::allows('delete-post',$post)) {
            abort(403);
         }
        $post = Post::find( $id);
        $post->delete();
        return redirect()->route('posts');
    }

    public function hdelete($id ,Request $request , Post $post)
    {
        if (! Gate::allows('hdelete-post',$post)) {
           abort(403);
        }
        $post = Post::withTrashed()->where( 'id' ,$id)->firstOrFail();
        $post->forceDelete();
        return redirect()->route('posts');

    }

    public function restore($id,Request $request , Post $post)
    {
        if (! Gate::allows('restore-post',$post)) {
            abort(403);
         }
        $post = Post::withTrashed()->where( 'id' ,$id)->firstOrFail();
        $post->restore();
        return redirect()->route('posts');

    }
}
