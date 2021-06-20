<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::when(request('search'), function ($query) {
            $query->where('title', 'like', '%' . request('search') . '%');
        })
            ->latest('id')
            ->paginate(5);

        if (request()->wantsJson()) {
            return view('posts', compact('posts'))->render();
        }

        return view('welcome', compact('posts'));
    }
}
